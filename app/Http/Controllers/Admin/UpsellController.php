<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Upsell;
use Illuminate\Http\Request;

class UpsellController extends Controller
{
    /**
     * Liste des upsells d'un produit donné.
     */
    public function index(Produit $produit)
    {
        $this->autoriser($produit);

        $upsells = $produit->upsells()->with('produitUpsell')->get();

        return view('admin.upsells.index', compact('produit', 'upsells'));
    }

    /**
     * Formulaire de création d'un upsell pour un produit.
     */
    public function create(Produit $produit)
    {
        $this->autoriser($produit);

        $boutiqueId = session('boutique_id');

        // Produits disponibles de la même boutique (sauf le produit lui-même)
        $produitsDisponibles = Produit::where('boutique_id', $boutiqueId)
            ->where('id', '!=', $produit->id)
            ->where('est_publie', true)
            ->get();

        return view('admin.upsells.create', compact('produit', 'produitsDisponibles'));
    }

    /**
     * Enregistre un nouvel upsell.
     */
    public function store(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $request->validate([
            'produit_upsell_id' => 'required|exists:produits,id|different:produit_id',
            'titre_offre'       => 'required|string|max:255',
            'description_offre' => 'nullable|string|max:1000',
            'prix_special'      => 'nullable|numeric|min:0',
            'ordre'             => 'nullable|integer|min:0',
        ]);

        // Vérifier qu'il n'existe pas déjà
        $existant = Upsell::where('produit_id', $produit->id)
                          ->where('produit_upsell_id', $request->produit_upsell_id)
                          ->first();

        if ($existant) {
            return back()->with('error', 'Cet upsell existe déjà pour ce produit.');
        }

        Upsell::create([
            'produit_id'        => $produit->id,
            'produit_upsell_id' => $request->produit_upsell_id,
            'titre_offre'       => $request->titre_offre,
            'description_offre' => $request->description_offre,
            'prix_special'      => $request->prix_special ?: null,
            'ordre'             => $request->ordre ?? 0,
            'est_actif'         => true,
        ]);

        return redirect()->route('admin.produits.upsells.index', $produit)
            ->with('success', 'Upsell ajouté avec succès !');
    }

    /**
     * Formulaire d'édition d'un upsell.
     */
    public function edit(Produit $produit, Upsell $upsell)
    {
        $this->autoriser($produit);

        $boutiqueId = session('boutique_id');

        $produitsDisponibles = Produit::where('boutique_id', $boutiqueId)
            ->where('id', '!=', $produit->id)
            ->where('est_publie', true)
            ->get();

        return view('admin.upsells.edit', compact('produit', 'upsell', 'produitsDisponibles'));
    }

    /**
     * Met à jour un upsell.
     */
    public function update(Request $request, Produit $produit, Upsell $upsell)
    {
        $this->autoriser($produit);

        $request->validate([
            'titre_offre'       => 'required|string|max:255',
            'description_offre' => 'nullable|string|max:1000',
            'prix_special'      => 'nullable|numeric|min:0',
            'ordre'             => 'nullable|integer|min:0',
            'est_actif'         => 'boolean',
        ]);

        $upsell->update([
            'titre_offre'       => $request->titre_offre,
            'description_offre' => $request->description_offre,
            'prix_special'      => $request->prix_special ?: null,
            'ordre'             => $request->ordre ?? 0,
            'est_actif'         => $request->boolean('est_actif', true),
        ]);

        return redirect()->route('admin.produits.upsells.index', $produit)
            ->with('success', 'Upsell mis à jour.');
    }

    /**
     * Active / désactive un upsell.
     */
    public function toggleActif(Produit $produit, Upsell $upsell)
    {
        $this->autoriser($produit);

        $upsell->update(['est_actif' => !$upsell->est_actif]);

        return back()->with('success', 'Statut de l\'upsell mis à jour.');
    }

    /**
     * Supprime un upsell.
     */
    public function destroy(Produit $produit, Upsell $upsell)
    {
        $this->autoriser($produit);

        $upsell->delete();

        return redirect()->route('admin.produits.upsells.index', $produit)
            ->with('success', 'Upsell supprimé.');
    }

    /**
     * Vérifie que le produit appartient à la boutique active du marchand.
     */
    private function autoriser(Produit $produit): void
    {
        if ($produit->boutique_id !== session('boutique_id')) {
            abort(403, 'Accès refusé.');
        }
    }
}
