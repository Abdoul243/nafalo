<?php

namespace App\Http\Controllers\Boutique;

use App\Models\PageIa;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LandingPageController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }

    /**
     * Affiche la page de vente IA intégrée à la boutique.
     * URL : /boutique/vente/{slug}
     */
    public function show(string $slug)
    {
        $page = PageIa::where('slug_page', $slug)
            ->where('boutique_id', $this->boutique->id)
            ->where('est_publiee', true)
            ->firstOrFail();

        $produit = $page->produit;

        if (!$produit) abort(404);

        // Construire les URLs nécessaires
        $checkoutUrl  = route('boutique.checkout.produit', ['id' => $produit->id]);
        $boutiqueUrl  = route('boutique.accueil');
        $produitUrl   = route('boutique.produit.show', $produit->slug ?? $produit->id);
        $prix         = $produit->type === 'gratuit'
            ? 'GRATUIT'
            : number_format($produit->prix, 0, ',', ' ') . ' FCFA';

        $btnTexte = $produit->type === 'gratuit'
            ? '🎁 Obtenir gratuitement'
            : '🛒 Acheter — ' . $prix;

        // Logo boutique
        $logoHtml = $this->boutique->logo
            ? '<img src="' . asset('storage/' . $this->boutique->logo) . '" alt="' . e($this->boutique->nom) . '">'
            : '<span style="width:32px;height:32px;border-radius:8px;background:#2563eb;color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.9rem;">'
              . strtoupper(substr($this->boutique->nom, 0, 1)) . '</span>';

        // Couleur principale de la boutique
        $couleurPrimaire = $this->boutique->configuration->couleur_primaire ?? '#2563eb';

        $html = $this->injecterBoutiqueBar(
            html: $page->contenu_html,
            boutiqueName: $this->boutique->nom,
            logoHtml: $logoHtml,
            boutiqueUrl: $boutiqueUrl,
            produitUrl: $produitUrl,
            checkoutUrl: $checkoutUrl,
            produitNom: $produit->nom,
            prix: $prix,
            btnTexte: $btnTexte,
            couleur: $couleurPrimaire,
            estGratuit: $produit->type === 'gratuit',
        );

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /* ─── Injection de la barre boutique dans le HTML généré ──────── */

    private function injecterBoutiqueBar(
        string $html,
        string $boutiqueName,
        string $logoHtml,
        string $boutiqueUrl,
        string $produitUrl,
        string $checkoutUrl,
        string $produitNom,
        string $prix,
        string $btnTexte,
        string $couleur,
        bool   $estGratuit,
    ): string {

        $btnBg = $estGratuit
            ? 'linear-gradient(135deg, #16a34a, #15803d)'
            : "linear-gradient(135deg, {$couleur}, #1d4ed8)";

        $cssInjection = <<<CSS
        <style id="nafalo-boutique-style">
        /* ── Barre Nafalo boutique ── */
        #nafalo-top-bar {
            position: fixed; top: 0; left: 0; right: 0; height: 54px;
            background: white; border-bottom: 1px solid rgba(0,0,0,0.08);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.25rem; z-index: 2147483647;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
        }
        #nafalo-top-bar .ntb-brand {
            display: flex; align-items: center; gap: 9px;
            text-decoration: none; color: #0f172a;
        }
        #nafalo-top-bar .ntb-brand img {
            height: 32px; width: auto; object-fit: contain;
        }
        #nafalo-top-bar .ntb-name {
            font-weight: 800; font-size: 0.95rem; color: #0f172a; line-height: 1;
        }
        #nafalo-top-bar .ntb-back {
            font-size: 0.8rem; color: #64748b; text-decoration: none;
            display: flex; align-items: center; gap: 5px; padding: 6px 12px;
            border: 1px solid #e2e8f0; border-radius: 8px; transition: all 0.15s;
        }
        #nafalo-top-bar .ntb-back:hover { border-color: #94a3b8; color: #0f172a; }

        #nafalo-cta-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; border-top: 1px solid rgba(0,0,0,0.08);
            padding: 0.875rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 2147483647;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
            gap: 1rem;
        }
        #nafalo-cta-bar .ncb-info { flex: 1; min-width: 0; }
        #nafalo-cta-bar .ncb-nom {
            font-size: 0.88rem; font-weight: 700; color: #0f172a;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        #nafalo-cta-bar .ncb-prix {
            font-size: 0.78rem; color: #64748b; margin-top: 1px;
        }
        #nafalo-cta-bar .ncb-btn {
            background: {$btnBg}; color: white;
            font-weight: 700; font-size: 0.92rem;
            border: none; border-radius: 12px;
            padding: 0.72rem 1.5rem; cursor: pointer;
            white-space: nowrap; text-decoration: none;
            display: inline-block; transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        #nafalo-cta-bar .ncb-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
            color: white;
        }
        /* Espace pour les barres fixes */
        body {
            padding-top: 54px !important;
            padding-bottom: 82px !important;
        }
        /* Responsive */
        @media (max-width: 480px) {
            #nafalo-top-bar .ntb-back span { display: none; }
            #nafalo-cta-bar .ncb-btn { font-size: 0.82rem; padding: 0.65rem 1rem; }
        }
        </style>
        CSS;

        $htmlBarre = <<<HTML
        <!-- Nafalo Boutique Bar (Top) -->
        <div id="nafalo-top-bar">
            <a class="ntb-brand" href="{$boutiqueUrl}">
                {$logoHtml}
                <span class="ntb-name">{$boutiqueName}</span>
            </a>
            <a class="ntb-back" href="{$produitUrl}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
                <span>Retour au produit</span>
            </a>
        </div>

        <!-- Nafalo Boutique Bar (Bottom CTA) -->
        <div id="nafalo-cta-bar">
            <div class="ncb-info">
                <div class="ncb-nom">{$produitNom}</div>
                <div class="ncb-prix">{$prix}</div>
            </div>
            <a href="{$checkoutUrl}" class="ncb-btn">{$btnTexte}</a>
        </div>

        <!-- Script de connexion boutique -->
        <script>
        (function() {
            var checkoutUrl = '{$checkoutUrl}';

            function goCheckout(e) {
                if (e) e.preventDefault();
                window.location.href = checkoutUrl;
            }

            // 1. Boutons avec postMessage (générés par l'IA)
            document.querySelectorAll('[onclick]').forEach(function(el) {
                var oc = el.getAttribute('onclick') || '';
                if (oc.indexOf('postMessage') !== -1 || oc.indexOf('acheter') !== -1) {
                    el.removeAttribute('onclick');
                    el.addEventListener('click', goCheckout);
                }
            });

            // 2. Boutons avec texte "acheter" ou "commander"
            document.querySelectorAll('a, button').forEach(function(el) {
                var txt = (el.innerText || el.textContent || '').toLowerCase();
                var href = (el.href || '').toLowerCase();
                if (
                    txt.indexOf('acheter') !== -1 ||
                    txt.indexOf('commander') !== -1 ||
                    txt.indexOf('je veux') !== -1 ||
                    txt.indexOf('obtenir') !== -1 ||
                    txt.indexOf('accéder') !== -1 ||
                    href.indexOf('checkout') !== -1 ||
                    href.indexOf('acheter') !== -1
                ) {
                    el.addEventListener('click', goCheckout);
                    if (el.tagName === 'A') el.href = checkoutUrl;
                }
            });

            // 3. Écouter les postMessages depuis des iframes éventuels
            window.addEventListener('message', function(e) {
                if (e.data === 'acheter') goCheckout();
            });
        })();
        </script>
        HTML;

        // Injecter CSS dans <head>
        $html = preg_replace('/<\/head>/i', $cssInjection . '</head>', $html, 1);

        // Injecter les barres et script avant </body>
        $html = preg_replace('/<\/body>/i', $htmlBarre . '</body>', $html, 1);

        return $html;
    }
}
