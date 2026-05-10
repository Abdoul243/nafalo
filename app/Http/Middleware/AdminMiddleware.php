<?php

namespace App\Http\Middleware;

use App\Models\Boutique;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }
        
        $utilisateur = Auth::guard('web')->user();
        
        if (!$utilisateur->estAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Initialiser une boutique active en session si absente
        if (!session()->has('boutique_id')) {
            $boutiqueId = Boutique::query()->orderBy('id')->value('id');
            if ($boutiqueId) {
                session(['boutique_id' => $boutiqueId]);
            }
        }
        
        return $next($request);
    }
}
