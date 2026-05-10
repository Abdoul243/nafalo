<?php

namespace App\Http\Middleware;

use App\Models\Boutique;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientAuthMiddleware
{
    private const LOCAL_HOSTS = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];

    public function handle(Request $request, Closure $next)
    {
        $boutique = $this->resolveBoutique($request);

        if (!$boutique || !Session::has('client_acces_' . $boutique->id)) {
            return redirect()->route('client.acces.demande');
        }

        return $next($request);
    }

    private function resolveBoutique(Request $request): ?Boutique
    {
        $host = $request->getHost();

        // 1. Domaine en paramètre de route
        $domaine = $request->route('domaine');
        if ($domaine) {
            $b = Boutique::where('domaine_personnalise', $domaine)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 2. Hôte de production (domaine personnalisé)
        if (!in_array($host, self::LOCAL_HOSTS, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 3. Environnement local — fallbacks session
        if (session('boutique_domaine')) {
            $b = Boutique::where('domaine_personnalise', session('boutique_domaine'))->where('est_active', true)->first();
            if ($b) return $b;
        }
        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 4. Première boutique active
        return Boutique::where('est_active', true)->first();
    }
}
