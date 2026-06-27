<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Ajoute des en-têtes de sécurité HTTP sur toutes les réponses web.
 *
 * NB : on n'impose volontairement PAS de Content-Security-Policy stricte
 * car la fonctionnalité "Pixels marketing" laisse les marchands injecter
 * du JS tiers (Facebook, TikTok…). Une CSP restrictive casserait ça.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Empêche le navigateur de "deviner" le type MIME (anti-sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Anti-clickjacking : interdit l'inclusion dans une iframe externe
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // Limite les infos de référent envoyées aux sites tiers
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Désactive des API sensibles par défaut
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS : force HTTPS pendant 1 an (uniquement sur connexion sécurisée)
        if ($request->secure() || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
