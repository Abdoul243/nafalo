<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Boutique;

class BoutiqueMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $domaine = $request->route('domaine');

        if (!$domaine) {
            $host = $request->getHost();
            if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
                $domaine = session('boutique_domaine');
            }
            $domaine = $domaine ?: $host;
        }
        
        $boutique = Boutique::where('domaine_personnalise', $domaine)
            ->where('est_active', true)
            ->first();
            
        if (!$boutique) {
            abort(404, 'Boutique non trouvée ou désactivée.');
        }
        
        session(['boutique_domaine' => $domaine]);

        // Partager la boutique avec toutes les vues
        view()->share('boutique', $boutique);
        
        return $next($request);
    }
}
