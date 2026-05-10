<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\PageIa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PageIaController extends Controller
{
    /** Index — toutes les pages IA de la boutique */
    public function index()
    {
        $boutiqueId = session('boutique_id');
        $pages = PageIa::where('boutique_id', $boutiqueId)
            ->with('produit')
            ->latest()
            ->get();

        return view('admin.pages-ia.index', compact('pages'));
    }

    /** Formulaire de génération pour un produit */
    public function create(Produit $produit)
    {
        $this->autoriser($produit);
        $pageExistante = PageIa::where('produit_id', $produit->id)->latest()->first();
        return view('admin.pages-ia.create', compact('produit', 'pageExistante'));
    }

    /** Générer la page via Claude API */
    public function generer(Request $request, Produit $produit)
    {
        set_time_limit(300);
        $this->autoriser($produit);

        $request->validate([
            'style'         => 'nullable|in:moderne,minimaliste,audacieux,professionnel',
            'couleur_theme' => 'nullable|string|max:7',
            'instructions'  => 'nullable|string|max:1000',
        ]);

        $apiKey = config('services.anthropic.api_key');
        if (!$apiKey) {
            return back()->with('error', 'Clé API Anthropic non configurée. Ajoutez ANTHROPIC_API_KEY dans votre .env');
        }

        $boutique    = \App\Models\Boutique::find(session('boutique_id'));
        $prix        = $produit->type === 'gratuit' ? 'GRATUIT' : number_format($produit->prix, 0, ',', ' ') . ' FCFA';
        $couleur     = $request->couleur_theme ?? '#2563eb';
        $style       = $request->style ?? 'moderne';
        $checkoutUrl = route('boutique.checkout.produit', ['id' => $produit->id]);

        $prompt = $this->construirePrompt(
            produit: $produit,
            boutique: $boutique,
            prix: $prix,
            checkoutUrl: $checkoutUrl,
            couleur: $couleur,
            style: $style,
            instructionsBonus: $request->instructions ?? '',
        );

        try {
            $response = Http::withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
            ->timeout(240)
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-sonnet-4-5-20250929',
                'max_tokens' => 5000,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->failed()) {
                $body  = $response->json();
                $error = $body['error']['message'] ?? ('HTTP ' . $response->status() . ' — ' . $response->body());
                return back()->with('error', "Erreur API Anthropic : {$error}")->withInput();
            }

            $data     = $response->json();
            $htmlBrut = $data['content'][0]['text'] ?? '';
            $tokens   = $data['usage']['output_tokens'] ?? null;

            if (empty($htmlBrut)) {
                return back()->with('error', "L'IA n'a retourné aucun contenu. Réessayez.")->withInput();
            }

            $html = $this->extraireHtml($htmlBrut);

            $page = PageIa::updateOrCreate(
                ['produit_id' => $produit->id],
                [
                    'boutique_id'      => $produit->boutique_id,
                    'prompt_original'  => substr(strip_tags($produit->description ?? $produit->nom), 0, 500),
                    'contenu_html'     => $html,
                    'slug_page'        => Str::slug($produit->nom) . '-' . $produit->id,
                    'modele_ia'        => 'claude-sonnet-4-5-20250929',
                    'tokens_utilises'  => $tokens,
                    'est_publiee'      => false,
                ]
            );

            return redirect()->route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $page->id])
                ->with('success', '✅ Page générée avec succès ! Prévisualisez-la avant de la publier.');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Délai dépassé : l\'API Anthropic n\'a pas répondu. Réessayez.')->withInput();
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /** Aperçu de la page générée */
    public function apercu(Produit $produit, PageIa $page)
    {
        $this->autoriser($produit);

        $boutique = \App\Models\Boutique::find($produit->boutique_id);
        $prix = $produit->type === 'gratuit' ? 'GRATUIT' : number_format($produit->prix, 0, ',', ' ') . ' FCFA';
        $checkoutUrl = route('boutique.checkout.produit', ['id' => $produit->id]);
        $boutiqueUrl = route('boutique.accueil');
        $produitUrl  = route('boutique.produit.show', $produit->slug ?? $produit->id);

        $logoHtml = $boutique?->logo
            ? '<img src="' . asset('storage/' . $boutique->logo) . '" alt="' . e($boutique->nom ?? '') . '" style="height:32px;width:auto;object-fit:contain;">'
            : '<span style="width:32px;height:32px;border-radius:8px;background:#2563eb;color:white;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:0.9rem;">' . strtoupper(substr($boutique->nom ?? 'B', 0, 1)) . '</span>';

        $couleurPrimaire = $boutique?->configuration?->couleur_primaire ?? '#2563eb';
        $btnTexte = $produit->type === 'gratuit' ? '🎁 Obtenir gratuitement' : '🛒 Acheter — ' . $prix;
        $btnBg = $produit->type === 'gratuit' ? 'linear-gradient(135deg,#16a34a,#15803d)' : "linear-gradient(135deg,{$couleurPrimaire},#1d4ed8)";

        $htmlPreview = $this->injecterPreviewBars(
            html: $page->contenu_html,
            boutiqueName: $boutique?->nom ?? 'Ma boutique',
            logoHtml: $logoHtml,
            boutiqueUrl: $boutiqueUrl,
            produitUrl: $produitUrl,
            checkoutUrl: $checkoutUrl,
            produitNom: $produit->nom,
            prix: $prix,
            btnTexte: $btnTexte,
            btnBg: $btnBg,
        );

        return view('admin.pages-ia.apercu', compact('produit', 'page', 'htmlPreview'));
    }

    /** Publier / dépublier */
    public function togglePublier(Produit $produit, PageIa $page)
    {
        $this->autoriser($produit);
        $page->update(['est_publiee' => !$page->est_publiee]);
        $action = $page->est_publiee ? 'publiée' : 'dépubliée';
        return back()->with('success', "Page {$action} avec succès.");
    }

    /** Éditer le contenu HTML manuellement */
    public function editContenu(Produit $produit, PageIa $page)
    {
        $this->autoriser($produit);
        return view('admin.pages-ia.edit-contenu', compact('produit', 'page'));
    }

    /** Sauvegarder le contenu HTML édité */
    public function saveContenu(Request $request, Produit $produit, PageIa $page)
    {
        $this->autoriser($produit);
        $request->validate(['contenu_html' => 'required|string']);
        $page->update(['contenu_html' => $request->contenu_html]);
        return redirect()->route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $page->id])
            ->with('success', 'Contenu sauvegardé avec succès.');
    }

    /** Supprimer une page IA */
    public function destroy(Produit $produit, PageIa $page)
    {
        $this->autoriser($produit);
        $page->delete();
        return redirect()->route('admin.pages-ia.index')
            ->with('success', 'Page de vente supprimée.');
    }

    /** Page publique (raw HTML fallback — la vraie version boutique est dans LandingPageController) */
    public function publique(string $slug)
    {
        $page = PageIa::where('slug_page', $slug)->where('est_publiee', true)->firstOrFail();
        return response($page->contenu_html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /* ── Privées ─────────────────────────────────────────────────────── */

    private function autoriser(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    private function injecterPreviewBars(
        string $html,
        string $boutiqueName,
        string $logoHtml,
        string $boutiqueUrl,
        string $produitUrl,
        string $checkoutUrl,
        string $produitNom,
        string $prix,
        string $btnTexte,
        string $btnBg,
    ): string {
        $css = <<<CSS
        <style id="nafalo-preview-style">
        #nafalo-top-bar {
            position: fixed; top: 0; left: 0; right: 0; height: 54px;
            background: white; border-bottom: 1px solid rgba(0,0,0,0.08);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.25rem; z-index: 2147483647;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        #nafalo-top-bar .ntb-brand { display:flex;align-items:center;gap:9px;text-decoration:none;color:#0f172a; }
        #nafalo-top-bar .ntb-name  { font-weight:800;font-size:0.95rem;color:#0f172a; }
        #nafalo-top-bar .ntb-back  { font-size:0.8rem;color:#64748b;text-decoration:none;display:flex;align-items:center;gap:5px;padding:6px 12px;border:1px solid #e2e8f0;border-radius:8px; }
        #nafalo-cta-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; border-top: 1px solid rgba(0,0,0,0.08);
            padding: 0.875rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 2147483647;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            gap: 1rem;
        }
        #nafalo-cta-bar .ncb-info { flex:1;min-width:0; }
        #nafalo-cta-bar .ncb-nom  { font-size:0.88rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
        #nafalo-cta-bar .ncb-prix { font-size:0.78rem;color:#64748b;margin-top:1px; }
        #nafalo-cta-bar .ncb-btn  { background:{$btnBg};color:white;font-weight:700;font-size:0.92rem;border:none;border-radius:12px;padding:0.72rem 1.5rem;cursor:pointer;white-space:nowrap;text-decoration:none;display:inline-block; }
        body { padding-top: 54px !important; padding-bottom: 82px !important; }
        </style>
        CSS;

        $bars = <<<HTML
        <div id="nafalo-top-bar">
            <a class="ntb-brand" href="{$boutiqueUrl}">
                {$logoHtml}
                <span class="ntb-name">{$boutiqueName}</span>
            </a>
            <a class="ntb-back" href="{$produitUrl}">← Retour au produit</a>
        </div>
        <div id="nafalo-cta-bar">
            <div class="ncb-info">
                <div class="ncb-nom">{$produitNom}</div>
                <div class="ncb-prix">{$prix}</div>
            </div>
            <a href="{$checkoutUrl}" class="ncb-btn">{$btnTexte}</a>
        </div>
        HTML;

        $html = preg_replace('/<\/head>/i', $css . '</head>', $html, 1);
        $html = preg_replace('/<\/body>/i', $bars . '</body>', $html, 1);

        return $html;
    }

    private function extraireHtml(string $texte): string
    {
        // Si entouré de ```html ... ```
        if (preg_match('/```html\s*([\s\S]*?)```/i', $texte, $m)) {
            return trim($m[1]);
        }
        // Si entouré de ``` ... ```
        if (preg_match('/```\s*([\s\S]*?)```/i', $texte, $m)) {
            return trim($m[1]);
        }
        return trim($texte);
    }

    private function construirePrompt(
        Produit $produit,
        $boutique,
        string $prix,
        string $checkoutUrl,
        string $couleur,
        string $style,
        string $instructionsBonus = '',
    ): string {
        $boutiqueName     = $boutique?->nom ?? 'Ma boutique';
        $categorieNom     = $produit->categorie?->nom ?? '';
        $descriptionBrute = trim(strip_tags($produit->description ?? ''));
        $imageUrl         = $produit->image ? asset('storage/' . $produit->image) : null;
        $typeLabel        = $produit->type === 'gratuit' ? 'Produit gratuit (lead magnet — collecte email)' : 'Produit payant';

        $styleDesc = match($style) {
            'minimaliste'   => 'design épuré, beaucoup d\'espace blanc, typographie élégante, sobre',
            'audacieux'     => 'couleurs vives, gros titres impactants, éléments visuels forts, dynamique',
            'professionnel' => 'sobre, corporate, structuré, inspire la confiance et la crédibilité',
            default         => 'moderne, dynamique, sections bien définies, optimisé pour la conversion',
        };

        $imageSection = $imageUrl
            ? "IMAGE DU PRODUIT : {$imageUrl}\n  → Affiche-la dans le hero avec : <img src=\"{$imageUrl}\" alt=\"{$produit->nom}\" style=\"width:100%;max-height:420px;object-fit:cover;border-radius:16px;display:block;margin:1.5rem auto;\">"
            : "Pas d'image disponible — utilise un hero avec fond dégradé en couleur principale à la place.";

        $bonusSection = $instructionsBonus
            ? "\nINSTRUCTIONS COMPLÉMENTAIRES DU VENDEUR :\n{$instructionsBonus}\n"
            : '';

        $descSection = $descriptionBrute
            ? "DESCRIPTION DU PRODUIT (rédigée par le vendeur — analyse-la pour créer le contenu) :\n---\n{$descriptionBrute}\n---"
            : "Pas de description disponible — génère un contenu convaincant basé sur le nom et la catégorie.";

        return <<<PROMPT
Tu es un expert en copywriting et design de pages de vente pour le marché africain francophone (FCFA).

Génère une page de vente HTML complète, autonome (tout-en-un avec <style> tag dans <head>), en français, ultra-convaincante pour ce produit digital. Analyse attentivement toutes les données ci-dessous :

════════════════════════════════════════
DONNÉES DU PRODUIT
════════════════════════════════════════
NOM           : {$produit->nom}
BOUTIQUE      : {$boutiqueName}
CATÉGORIE     : {$categorieNom}
PRIX AFFICHÉ  : {$prix}
TYPE          : {$typeLabel}
URL D'ACHAT   : {$checkoutUrl}

{$descSection}

{$imageSection}
{$bonusSection}
════════════════════════════════════════
STRUCTURE OBLIGATOIRE DE LA PAGE
════════════════════════════════════════
1. HERO        — Titre accrocheur + sous-titre + image produit + bouton CTA
2. PROBLÈME    — "Tu vis peut-être ça..." (douleurs spécifiques du public cible)
3. SOLUTION    — Comment ce produit résout ces problèmes
4. BÉNÉFICES   — 4 à 6 bénéfices concrets avec icônes emoji
5. CONTENU     — Liste détaillée de ce que l'acheteur reçoit (ce qui est dans le produit)
6. TÉMOIGNAGES — 2 à 3 avis fictifs mais réalistes (prénoms africains, contextes locaux authentiques)
7. CTA PRINCIPAL — Gros bouton d'achat visible, prix {$prix}, urgence
8. FAQ         — 3 à 4 questions fréquentes avec réponses rassurantes
9. GARANTIE    — Section confiance (satisfait ou remboursé / sécurité paiement)
10. FOOTER     — Nom de la boutique : {$boutiqueName}

════════════════════════════════════════
RÈGLES TECHNIQUES ABSOLUES
════════════════════════════════════════
— Tout le CSS dans un seul <style> tag dans <head>
— Responsive mobile-first (max-width: 640px media queries)
— TOUS les boutons CTA (Acheter, Commander, Obtenir, Je veux...) doivent avoir href="{$checkoutUrl}" — jamais #, jamais javascript:void
— Pas de JavaScript externe, pas de Bootstrap, pas de CDN
— Police : system-ui, -apple-system, 'Segoe UI', sans-serif
— Couleur principale : {$couleur}
— Style visuel : {$styleDesc}
— Contenu adapté au marché Afrique de l'Ouest (contexte local, exemples pertinents)
— Page complète de <!DOCTYPE html> à </html>

Réponds UNIQUEMENT avec le code HTML complet. Aucune explication, aucun markdown autour du code.
PROMPT;
    }
}
