<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Http\Requests\ProduitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Produit::where('boutique_id', $boutiqueId)
            ->with('categorie')
            ->withCount('achats');
            
        if ($request->has('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }
        
        if ($request->filled('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'publie') {
                $query->where('est_publie', true);
            } elseif ($request->statut === 'brouillon') {
                $query->where('est_publie', false);
            }
        }

        $produits = $query->latest()->paginate(15);
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.produits.index', compact('produits', 'categories'));
    }
    
    /** Écran de choix du type de produit (style Chariow). */
    public function choisirType()
    {
        return view('admin.produits.choisir-type');
    }

    public function create(Request $request)
    {
        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();

        // Format pré-sélectionné depuis l'écran de choix
        $formatInitial = in_array($request->query('format'), ['fichier', 'formation', 'licence', 'bundle', 'communaute', 'coaching'])
            ? $request->query('format')
            : 'fichier';

        return view('admin.produits.create', compact('categories', 'formatInitial'));
    }
    
    /** Wizard dédié à la création d'une séance de coaching (étapes Chariow). */
    public function createCoaching()
    {
        $categories = Categorie::where('boutique_id', session('boutique_id'))->get();
        return view('admin.produits.create-coaching', compact('categories'));
    }

    public function storeCoaching(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes() + [
            'coaching_duree' => 'required|integer|min:5|max:600',
            'coaching_pause' => 'nullable|integer|min:0|max:240',
            'jours'          => 'nullable|array',
        ]);

        // Disponibilité hebdomadaire
        $dispo = [];
        foreach (['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $j) {
            $v = $request->input("jours.$j");
            if (!empty($v['actif']) && !empty($v['debut']) && !empty($v['fin']) && $v['debut'] < $v['fin']) {
                $dispo[$j] = [['debut' => $v['debut'], 'fin' => $v['fin']]];
            }
        }

        $produit = Produit::create($this->basePayload($data) + [
            'format'                  => 'coaching',
            'coaching_duree'          => $data['coaching_duree'],
            'coaching_pause'          => $data['coaching_pause'] ?? 0,
            'coaching_disponibilites' => $dispo ?: null,
        ]);

        return redirect()->route('admin.produits.coaching.reservations', $produit)
            ->with('success', 'Séance de coaching créée ! Vos disponibilités sont enregistrées.');
    }

    /** Wizard dédié à la création d'un produit Fichier (étapes Chariow). */
    public function createFichier()
    {
        $categories = Categorie::where('boutique_id', session('boutique_id'))->orderBy('nom')->get();
        return view('admin.produits.create-fichier', compact('categories'));
    }

    public function storeFichier(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes() + [
            'fichier' => 'required|file|mimes:pdf,zip,mp3,mp4,docx,xlsx,png,jpg|max:102400',
        ]);

        $payload = $this->basePayload($data) + [
            'format'  => 'fichier',
            'fichier' => $request->file('fichier')->store('produits/fichiers', 'local'),
        ];

        $produit = Produit::create($payload);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit « ' . $produit->nom . ' » créé avec succès.');
    }

    /* ─────────── Wizards dédiés (Formation, Licence, Bundle, Communauté) ─────────── */

    private function slugUnique(string $nom): string
    {
        $slug = Str::slug($nom);
        if (Produit::where('slug', $slug)->exists()) $slug .= '-' . Str::lower(Str::random(4));
        return $slug;
    }

    private function basePayload(array $data): array
    {
        $type  = ($data['type'] ?? 'payant') === 'gratuit' ? 'gratuit' : 'payant';
        $prix  = $type === 'gratuit' ? 0 : (float) $data['prix'];
        $promo = (!empty($data['prix_promo']) && $data['prix_promo'] > 0 && $data['prix_promo'] < $prix)
            ? (float) $data['prix_promo'] : null;

        $p = [
            'boutique_id'  => session('boutique_id'),
            'nom'          => $data['nom'],
            'slug'         => $this->slugUnique($data['nom']),
            'categorie_id' => $data['categorie_id'] ?? null,
            'description'  => $data['description'] ?? null,
            'prix'         => $prix,
            'prix_promo'   => $promo,
            'type'         => $type,
            'est_publie'   => request()->boolean('est_publie'),
        ];
        if (request()->hasFile('image')) {
            $img = request()->file('image');
            $p['image']        = $img->store('produits/images', 'public');
            $p['image_mime']   = $img->getMimeType();
            $p['image_taille'] = $img->getSize();
        }
        return $p;
    }

    private function categoriesBoutique()
    {
        return Categorie::where('boutique_id', session('boutique_id'))->orderBy('nom')->get();
    }

    /**
     * Si le marchand a choisi « Créer une nouvelle catégorie », on la crée
     * (ou réutilise) pour sa boutique et on injecte son id dans la requête,
     * pour qu'elle passe la validation `exists:categories,id` comme une normale.
     */
    private function preparerCategorie(Request $request): void
    {
        if ($request->input('categorie_id') !== '__new__') {
            return;
        }
        $nom = trim((string) $request->input('nouvelle_categorie', ''));
        if ($nom === '') {
            $request->merge(['categorie_id' => null]); // déclenchera l'erreur "catégorie requise"
            return;
        }
        $cat = Categorie::firstOrCreate(
            ['boutique_id' => session('boutique_id'), 'slug' => Str::slug($nom)],
            ['nom' => $nom]
        );
        $request->merge(['categorie_id' => $cat->id]);
    }

    private function reglesCommunes(): array
    {
        return [
            'nom'          => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'description'  => 'nullable|string',
            'type'         => 'nullable|in:payant,gratuit',
            'prix'         => 'required_unless:type,gratuit|nullable|numeric|min:0',
            'prix_promo'   => 'nullable|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'est_publie'   => 'nullable|boolean',
        ];
    }

    // ── Formation ──
    public function createFormation()
    {
        return view('admin.produits.create-formation', ['categories' => $this->categoriesBoutique()]);
    }
    public function storeFormation(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes());
        $produit = Produit::create($this->basePayload($data) + ['format' => 'formation']);
        return redirect()->route('admin.produits.formation.programme', $produit)
            ->with('success', 'Formation créée. Construisez maintenant le programme.');
    }

    // ── Licence ──
    public function createLicence()
    {
        return view('admin.produits.create-licence', ['categories' => $this->categoriesBoutique()]);
    }
    public function storeLicence(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes() + [
            'cle_type' => 'nullable|in:alphanumerique,uuid',
            'cle_longueur' => 'nullable|integer|in:8,16,24,32',
            'cle_prefixe' => 'nullable|string|max:12',
            'cle_quantite' => 'nullable|integer|min:0|max:1000',
        ]);
        $produit = Produit::create($this->basePayload($data) + ['format' => 'licence']);

        // Génération initiale des clés (optionnelle)
        $qte = (int) ($data['cle_quantite'] ?? 0);
        if ($qte > 0) {
            $type = $data['cle_type'] ?? 'alphanumerique';
            $len  = $data['cle_longueur'] ?? 16;
            $pre  = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $data['cle_prefixe'] ?? ''));
            $faites = 0; $tent = 0;
            while ($faites < $qte && $tent < $qte * 5) {
                $tent++;
                $base = $type === 'uuid' ? strtoupper((string) Str::uuid()) : implode('-', str_split(strtoupper(Str::random($len)), 4));
                $cle  = ($pre ? $pre . '-' : '') . $base;
                if ($produit->clesLicence()->where('cle', $cle)->exists()) continue;
                $produit->clesLicence()->create(['cle' => $cle, 'statut' => 'disponible']);
                $faites++;
            }
        }
        return redirect()->route('admin.produits.licences.gestion', $produit)
            ->with('success', 'Produit licence créé. Gérez vos clés ici.');
    }

    // ── Bundle ──
    public function createBundle()
    {
        $disponibles = Produit::where('boutique_id', session('boutique_id'))
            ->where('format', '!=', 'bundle')->orderBy('nom')->get();
        return view('admin.produits.create-bundle', ['categories' => $this->categoriesBoutique(), 'disponibles' => $disponibles]);
    }
    public function storeBundle(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes() + [
            'produits' => 'nullable|array', 'produits.*' => 'integer|exists:produits,id',
        ]);
        $produit = Produit::create($this->basePayload($data) + ['format' => 'bundle']);
        $ids = Produit::whereIn('id', $data['produits'] ?? [])
            ->where('boutique_id', session('boutique_id'))->where('format', '!=', 'bundle')->pluck('id');
        $produit->produitsInclus()->sync($ids);
        return redirect()->route('admin.produits.bundle.gestion', $produit)
            ->with('success', 'Pack créé avec ' . $ids->count() . ' produit(s).');
    }

    // ── Communauté ──
    public function createCommunaute()
    {
        return view('admin.produits.create-communaute', ['categories' => $this->categoriesBoutique()]);
    }
    public function storeCommunaute(Request $request)
    {
        $this->preparerCategorie($request);
        $data = $request->validate($this->reglesCommunes() + [
            'acces_type' => 'nullable|in:unique,abonnement',
            'abonnement_intervalle' => 'nullable|in:mensuel,annuel',
        ]);
        $extra = [
            'format'     => 'communaute',
            'acces_type' => $data['acces_type'] ?? 'unique',
            'abonnement_intervalle' => ($data['acces_type'] ?? 'unique') === 'abonnement' ? ($data['abonnement_intervalle'] ?? 'mensuel') : null,
        ];
        $produit = Produit::create($this->basePayload($data) + $extra);
        return redirect()->route('admin.produits.communaute.gestion', $produit)
            ->with('success', 'Communauté créée. Publiez un message de bienvenue.');
    }

    public function store(ProduitRequest $request)
    {
        $data = $request->validated();
        $data['boutique_id'] = session('boutique_id');
        $data['slug'] = Str::slug($data['nom']);
        
        // ✅ Stocker l'image en fichier (pas en binaire DB)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('produits/images', 'public');
            $data['image'] = $path;
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            // Disque PRIVÉ : le fichier produit n'est jamais accessible en direct,
            // uniquement via la route de téléchargement protégée par token.
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'local');
        }

        // Lead magnet : forcer prix à 0 si gratuit
        if (($data['type'] ?? 'payant') === 'gratuit') {
            $data['prix'] = 0;
        }

        $produit = Produit::create($data);

        // Si c'est une formation → aller directement construire le programme.
        if ($produit->estFormation()) {
            return redirect()->route('admin.produits.formation.programme', $produit)
                ->with('success', 'Produit créé. Construisez maintenant le programme.');
        }

        // Si c'est une licence → aller gérer les clés.
        if ($produit->estLicence()) {
            return redirect()->route('admin.produits.licences.gestion', $produit)
                ->with('success', 'Produit créé. Ajoutez maintenant vos clés de licence.');
        }

        // Si c'est un bundle → aller composer le pack.
        if ($produit->estBundle()) {
            return redirect()->route('admin.produits.bundle.gestion', $produit)
                ->with('success', 'Pack créé. Choisissez maintenant les produits inclus.');
        }

        // Si c'est une communauté → aller publier la première annonce.
        if ($produit->estCommunaute()) {
            return redirect()->route('admin.produits.communaute.gestion', $produit)
                ->with('success', 'Communauté créée. Publiez un message de bienvenue.');
        }

        // Si c'est du coaching → aller gérer les réservations / réglages.
        if ($produit->estCoaching()) {
            return redirect()->route('admin.produits.coaching.reservations', $produit)
                ->with('success', 'Séance de coaching créée. Réglez la durée et gérez les réservations ici.');
        }

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }
    
    public function show(Produit $produit)
    {
        return redirect()->route('admin.produits.edit', $produit);
    }

    public function edit(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);

        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();

        return view('admin.produits.edit', compact('produit', 'categories'));
    }

    public function update(ProduitRequest $request, Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
        
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nom']);
        
        // ✅ Stocker la nouvelle image en fichier, supprimer l'ancienne
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $image = $request->file('image');
            $path = $image->store('produits/images', 'public');
            $data['image'] = $path;
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            if ($produit->fichier) {
                Storage::disk('local')->delete($produit->fichier);
            }
            // Disque PRIVÉ (cf. store())
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'local');
        }

        // Lead magnet : forcer prix à 0 si gratuit
        if (($data['type'] ?? $produit->type) === 'gratuit') {
            $data['prix'] = 0;
        }

        $produit->update($data);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }
    
    public function destroy(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);

        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        if ($produit->fichier) {
            Storage::disk('local')->delete($produit->fichier);
        }
        
        $produit->delete();

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Téléchargement du fichier produit par le marchand propriétaire.
     * Le fichier est sur le disque privé : seul ce point d'accès contrôlé y mène.
     */
    public function telechargerFichier(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
        abort_if(!$produit->fichier || !Storage::disk('local')->exists($produit->fichier), 404);

        return Storage::disk('local')->download($produit->fichier, basename($produit->fichier));
    }
}