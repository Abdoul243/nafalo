<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Boutique;

class BoutiqueMiddleware
{
    /** Hôtes considérés comme locaux (dev) */
    private const LOCAL_HOSTS = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];

    public function handle(Request $request, Closure $next)
    {
        $boutique = null;
        $host     = $request->getHost();

        // 1. Domaine passé en paramètre de route (/{domaine}/boutique)
        $domaine = $request->route('domaine');
        if ($domaine) {
            $boutique = Boutique::where('domaine_personnalise', $domaine)
                ->where('est_active', true)
                ->first();
        }

        // 2. Hôte personnalisé (production)
        if (!$boutique && !in_array($host, self::LOCAL_HOSTS, true)) {
            $boutique = Boutique::where('domaine_personnalise', $host)
                ->where('est_active', true)
                ->first();
        }

        // 3. Hôte local : on cherche via la session
        if (!$boutique) {
            // a) domaine mémorisé (via /{domaine}/boutique)
            $domaineSess = session('boutique_domaine');
            if ($domaineSess) {
                $boutique = Boutique::where('domaine_personnalise', $domaineSess)
                    ->where('est_active', true)
                    ->first();
            }

            // b) boutique_id de l'admin connecté
            if (!$boutique && session('boutique_id')) {
                $boutique = Boutique::where('id', session('boutique_id'))
                    ->where('est_active', true)
                    ->first();
            }

            // c) première boutique active disponible
            if (!$boutique) {
                $boutique = Boutique::where('est_active', true)->first();
            }
        }

        if (!$boutique) {
            abort(404, 'Aucune boutique active trouvée.');
        }

        // Partager la boutique avec toutes les vues
        view()->share('boutique', $boutique);

        return $next($request);
    }
}
