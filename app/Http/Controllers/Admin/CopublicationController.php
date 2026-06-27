<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Copublication;
use App\Models\Produit;
use App\Models\Utilisateur;
use App\Models\Boutique;
use App\Models\Transaction;
use App\Services\CollaborationSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopublicationController extends Controller
{
    /**
     * Liste des co-publications du marchand connecté
     * (propriétaire + co-publicateur).
     */
    public function index()
    {
        $utilisateur = Auth::user();
        $boutiqueId  = session('boutique_id');

        // Co-publications où je suis propriétaire
        $enTantQueProprietaire = Copublication::where('proprietaire_id', $utilisateur->id)
            ->whereHas('produit', fn($q) => $q->where('boutique_id', $boutiqueId))
            ->with(['produit', 'copublicateur', 'boutiqueCopublicateur'])
            ->latest()
            ->get();

        // Invitations reçues (je suis co-publicateur)
        $invitationsRecues = Copublication::where('copublicateur_id', $utilisateur->id)
            ->with(['produit.boutique', 'proprietaire'])
            ->latest()
            ->get();

        return view('admin.copublications.index', compact(
            'enTantQueProprietaire',
            'invitationsRecues'
        ));
    }

    /**
     * Formulaire d'invitation d'un co-publicateur pour un produit.
     */
    public function create(Request $request)
    {
        $boutiqueId = session('boutique_id');
        $produits   = Produit::where('boutique_id', $boutiqueId)
                             ->where('est_publie', true)
                             ->get();

        $produitSelectionne = $request->has('produit_id')
            ? Produit::find($request->produit_id)
            : null;

        // Pré-remplissage email depuis la recherche IA
        $emailPartenaire = $request->get('email', '');
        $nomPartenaire   = $request->get('partenaire', '');

        return view('admin.copublications.create', compact(
            'produits',
            'produitSelectionne',
            'emailPartenaire',
            'nomPartenaire'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  RECHERCHE IA DE PARTENAIRES
    // ─────────────────────────────────────────────────────────────

    /**
     * Page de recherche IA de partenaires.
     */
    public function rechercher()
    {
        return view('admin.copublications.rechercher');
    }

    /**
     * Endpoint AJAX : analyse la requête avec Claude et retourne
     * les boutiques partenaires classées par popularité.
     */
    public function iaSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:500',
        ]);

        try {
            $utilisateur    = Auth::user();
            $boutiqueActive = session('boutique_id');
            // Exclure toutes les boutiques de l'utilisateur connecté
            $mesBoutiqueIds = $utilisateur->boutiques()->pluck('id')->toArray();
            // Inclure aussi la boutique active si elle ne lui appartient pas (super admin)
            if ($boutiqueActive && !in_array($boutiqueActive, $mesBoutiqueIds)) {
                $mesBoutiqueIds[] = $boutiqueActive;
            }

            // ── 1. Analyse IA de la requête ──────────────────────────
            $service = new CollaborationSearchService();
            $analyse = $service->analyserRequete($request->input('query'));

            $niches = array_values(array_filter(
                array_map('trim', array_merge(
                    $analyse['niches'] ?? [],
                    $analyse['niches_complementaires'] ?? [],
                    $analyse['mots_cles_produits'] ?? []
                )),
                fn($n) => $n !== ''
            ));

            // Si aucune niche extraite, utiliser les mots de la requête brute
            if (empty($niches)) {
                $niches = array_values(array_filter(
                    array_map('trim', explode(' ', strtolower($request->query))),
                    fn($m) => strlen($m) > 2
                ));
            }

            // ── 2. Recherche boutiques — tout dans un seul groupe OR ──
            $query = Boutique::with(['utilisateur', 'categories'])
                ->where('est_active', true)
                ->where(function ($q) use ($niches, $mesBoutiqueIds) {
                    // Exclure nos boutiques
                    $q->whereNotIn('id', $mesBoutiqueIds);
                    // Correspondance dans nom/description boutique OU catégories OU produits
                    $q->where(function ($inner) use ($niches) {
                        foreach ($niches as $niche) {
                            $inner->orWhere('nom', 'LIKE', "%{$niche}%")
                                  ->orWhere('description', 'LIKE', "%{$niche}%");
                        }
                        // Correspondance via catégories
                        $inner->orWhereHas('categories', function ($c) use ($niches) {
                            $c->where(function ($cc) use ($niches) {
                                foreach ($niches as $niche) {
                                    $cc->orWhere('nom', 'LIKE', "%{$niche}%");
                                }
                            });
                        });
                        // Correspondance via produits
                        $inner->orWhereHas('produits', function ($p) use ($niches) {
                            $p->where(function ($pp) use ($niches) {
                                foreach ($niches as $niche) {
                                    $pp->orWhere('nom', 'LIKE', "%{$niche}%")
                                       ->orWhere('description', 'LIKE', "%{$niche}%");
                                }
                            });
                        });
                    });
                });

            // ── 3. Classement par popularité ─────────────────────────
            $boutiques = $query
                ->withCount(['transactions as total_ventes' => fn($q) => $q->where('statut', Transaction::STATUT_REUSSI)])
                ->withCount('produits as nb_produits')
                ->orderBy('total_ventes', 'desc')
                ->limit(24)
                ->get();

            // ── 4. Exclure partenaires déjà invités ──────────────────
            $dejaInvitesIds = Copublication::where('proprietaire_id', $utilisateur->id)
                ->join('boutiques', 'boutiques.utilisateur_id', '=', 'copublications.copublicateur_id')
                ->pluck('boutiques.id')
                ->toArray();

            $boutiques = $boutiques->whereNotIn('id', $dejaInvitesIds)->values();

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('iaSearch error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }

        // ── 5. Formatage de la réponse ───────────────────────────
        return response()->json([
            'analyse'   => $analyse,
            'boutiques' => $boutiques->map(fn($b) => [
                'id'           => $b->id,
                'nom'          => $b->nom,
                'description'  => $b->description
                                  ? mb_substr(strip_tags($b->description), 0, 120) . '...'
                                  : null,
                'logo_url'     => $b->logo_url,
                'total_ventes' => $b->total_ventes,
                'nb_produits'  => $b->nb_produits,
                'email'        => $b->utilisateur?->email ?? '',
                'invite_url'   => route('admin.copublications.create', [
                    'email'      => $b->utilisateur?->email ?? '',
                    'partenaire' => $b->nom,
                ]),
            ]),
            'total' => $boutiques->count(),
        ]);
    }

    /**
     * Envoie l'invitation de co-publication.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produit_id'                => 'required|exists:produits,id',
            'email_copublicateur'       => 'required|email',
            'pourcentage_proprietaire'  => 'required|numeric|min:1|max:99',
            'pourcentage_copublicateur' => 'required|numeric|min:1|max:99',
            'message'                   => 'nullable|string|max:500',
        ]);

        // Vérifier que les pourcentages font 100 %
        $total = (float) $request->pourcentage_proprietaire + (float) $request->pourcentage_copublicateur;
        if (abs($total - 100) > 0.01) {
            return back()->withErrors(['pourcentage' => 'Les pourcentages doivent totaliser 100 %.'])
                         ->withInput();
        }

        $utilisateur = Auth::user();
        $produit     = Produit::findOrFail($request->produit_id);

        // Vérifier que le produit appartient bien à la boutique active
        if ($produit->boutique_id !== session('boutique_id')) {
            return back()->with('error', 'Ce produit ne vous appartient pas.');
        }

        // Trouver le co-publicateur par email
        $copublicateur = Utilisateur::where('email', $request->email_copublicateur)->first();
        if (!$copublicateur) {
            return back()->withErrors(['email_copublicateur' => 'Aucun marchand Nafalo trouvé avec cet email.'])
                         ->withInput();
        }

        if ($copublicateur->id === $utilisateur->id) {
            return back()->withErrors(['email_copublicateur' => 'Vous ne pouvez pas vous inviter vous-même.'])
                         ->withInput();
        }

        // Récupérer la première boutique active du co-publicateur
        $boutiqueCopub = $copublicateur->boutiques()->where('est_active', true)->first();
        if (!$boutiqueCopub) {
            return back()->withErrors(['email_copublicateur' => 'Ce marchand n\'a pas de boutique active.'])
                         ->withInput();
        }

        // Vérifier qu'il n'y a pas déjà une co-publication pour ce couple
        $existant = Copublication::where('produit_id', $produit->id)
                                 ->where('copublicateur_id', $copublicateur->id)
                                 ->first();

        if ($existant) {
            return back()->with('error', 'Une invitation existe déjà pour ce marchand sur ce produit (statut : ' . $existant->statut . ').');
        }

        $copub = Copublication::create([
            'produit_id'                => $produit->id,
            'proprietaire_id'           => $utilisateur->id,
            'copublicateur_id'          => $copublicateur->id,
            'boutique_copublicateur_id' => $boutiqueCopub->id,
            'pourcentage_proprietaire'  => $request->pourcentage_proprietaire,
            'pourcentage_copublicateur' => $request->pourcentage_copublicateur,
            'statut'                    => Copublication::STATUT_EN_ATTENTE,
            'message'                   => $request->message,
        ]);

        // Notification in-app au copublicateur invité
        try {
            $copub->load(['produit', 'proprietaire', 'copublicateur']);
            \App\Services\NotificationService::invitationCopublication($copub);
        } catch (\Throwable $e) { /* silencieux */ }

        return redirect()->route('admin.copublications.index')
            ->with('success', 'Invitation envoyée à ' . $copublicateur->nom . ' avec succès !');
    }

    /**
     * Accepter une invitation reçue.
     */
    public function accepter(Copublication $copublication)
    {
        $utilisateur = Auth::user();

        if ($copublication->copublicateur_id !== $utilisateur->id) {
            abort(403);
        }

        $copublication->update(['statut' => Copublication::STATUT_ACCEPTE]);

        // Notifier le propriétaire
        try {
            $copublication->load(['produit', 'proprietaire', 'copublicateur']);
            \App\Services\NotificationService::reponseCopublication($copublication, true);
        } catch (\Throwable $e) { /* silencieux */ }

        return redirect()->route('admin.copublications.index')
            ->with('success', 'Co-publication acceptée ! Vous recevrez votre part à chaque vente.');
    }

    /**
     * Refuser une invitation reçue.
     */
    public function refuser(Copublication $copublication)
    {
        $utilisateur = Auth::user();

        if ($copublication->copublicateur_id !== $utilisateur->id) {
            abort(403);
        }

        $copublication->update(['statut' => Copublication::STATUT_REFUSE]);

        // Notifier le propriétaire
        try {
            $copublication->load(['produit', 'proprietaire', 'copublicateur']);
            \App\Services\NotificationService::reponseCopublication($copublication, false);
        } catch (\Throwable $e) { /* silencieux */ }

        return redirect()->route('admin.copublications.index')
            ->with('info', 'Invitation refusée.');
    }

    /**
     * Annuler / supprimer une co-publication (propriétaire ou copublicateur).
     */
    public function destroy(Copublication $copublication)
    {
        $utilisateur = Auth::user();

        if ($copublication->proprietaire_id !== $utilisateur->id
            && $copublication->copublicateur_id !== $utilisateur->id) {
            abort(403);
        }

        $copublication->delete();

        return redirect()->route('admin.copublications.index')
            ->with('success', 'Co-publication supprimée.');
    }
}
