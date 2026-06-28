<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    private function autoriser(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    /** Page de composition du pack. */
    public function gestion(Produit $produit)
    {
        $this->autoriser($produit);

        if (!$produit->estBundle()) {
            $produit->update(['format' => 'bundle']);
        }

        // Produits sélectionnables : ceux de la boutique, hors ce bundle et hors autres bundles
        $disponibles = Produit::where('boutique_id', session('boutique_id'))
            ->where('id', '!=', $produit->id)
            ->where('format', '!=', 'bundle')
            ->orderBy('nom')
            ->get();

        $inclusIds = $produit->produitsInclus->pluck('id')->all();

        return view('admin.bundles.gestion', compact('produit', 'disponibles', 'inclusIds'));
    }

    /** Enregistre les produits inclus. */
    public function enregistrer(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate([
            'produits'   => 'nullable|array',
            'produits.*' => 'integer|exists:produits,id',
        ]);

        // On ne garde que des produits de la même boutique, hors bundles
        $ids = Produit::whereIn('id', $data['produits'] ?? [])
            ->where('boutique_id', session('boutique_id'))
            ->where('format', '!=', 'bundle')
            ->where('id', '!=', $produit->id)
            ->pluck('id');

        $produit->produitsInclus()->sync($ids);

        return back()->with('success', $ids->count() . ' produit(s) dans le pack.');
    }
}
