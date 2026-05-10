<?php

namespace App\Http\Middleware;

use App\Models\Boutique;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientAuthMiddleware
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

        if (!$boutique || !Session::has('client_acces_' . $boutique->id)) {
            return redirect()->route('client.acces.demande');
        }

        return $next($request);
    }
}
