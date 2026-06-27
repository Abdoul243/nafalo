<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;

class BoutiqueSelectController extends Controller
{
    /**
     * Page de sélection de boutique — uniquement CELLES de l'utilisateur.
     */
    public function choisir()
    {
        $boutiques = auth()->user()->boutiques()->orderBy('nom')->get();
        return view('admin.boutiques.select', compact('boutiques'));
    }

    /**
     * Sélectionner une boutique et aller au dashboard.
     *
     * SÉCURITÉ : on vérifie que la boutique appartient bien à l'utilisateur
     * connecté avant de l'activer en session. Sans ce contrôle, n'importe quel
     * marchand pourrait accéder aux données d'une autre boutique (IDOR), car
     * tous les contrôleurs admin se basent sur session('boutique_id').
     */
    public function select($id)
    {
        $boutique = auth()->user()->boutiques()->findOrFail($id);

        session(['boutique_id' => $boutique->id]);
        // Stocker le vrai domaine (ou null) — surtout PAS un domaine de repli :
        // un repli ('digital-store.test') pointerait vers la boutique d'un autre.
        session(['boutique_domaine' => $boutique->domaine_personnalise]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Boutique "' . $boutique->nom . '" sélectionnée.');
    }
}
