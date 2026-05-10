<?php

namespace App\Http\Controllers\Boutique;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;

/**
 * Contrôleur de base pour toutes les pages boutique.
 * Résout la boutique correctement en local (nafalo.test) et en production.
 */
abstract class BoutiqueBaseController extends Controller
{
    private const LOCAL_HOSTS = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];

    protected function resolveBoutique(Request $request): Boutique
    {
        // 1. Domaine passé en paramètre de route
        $domaine = $request->route('domaine');
        if ($domaine) {
            $b = Boutique::where('domaine_personnalise', $domaine)->where('est_active', true)->first();
            if ($b) return $b;
        }

        $host = $request->getHost();

        // 2. Hôte de production (domaine personnalisé)
        if (!in_array($host, self::LOCAL_HOSTS, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 3. Environnement local — plusieurs fallbacks
        // a) domaine mémorisé en session
        $domaineSess = session('boutique_domaine');
        if ($domaineSess) {
            $b = Boutique::where('domaine_personnalise', $domaineSess)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // b) boutique_id de l'admin connecté
        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        // c) première boutique active
        $b = Boutique::where('est_active', true)->first();
        if ($b) return $b;

        abort(404, 'Aucune boutique active trouvée.');
    }
}
