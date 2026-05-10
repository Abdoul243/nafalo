<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;

class BoutiqueSelectController extends Controller
{
    /**
     * Page de sélection de boutique
     */
    public function choisir()
    {
        $boutiques = Boutique::orderBy('nom')->get();
        return view('admin.boutiques.select', compact('boutiques'));
    }

    /**
     * Sélectionner une boutique et aller au dashboard
     */
    public function select($id)
    {
        $boutique = Boutique::findOrFail($id);
        session(['boutique_id' => $boutique->id]);
        session(['boutique_domaine' => $boutique->domaine_personnalise ?? 'digital-store.test']);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Boutique "' . $boutique->nom . '" sélectionnée.');
    }
}
