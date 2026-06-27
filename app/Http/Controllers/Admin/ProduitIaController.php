<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Produit;
use App\Services\ProduitIaService;
use Illuminate\Http\Request;

class ProduitIaController extends Controller
{
    // ── Feature 1 : Génération page de vente ────────────────
    public function genererPageVente(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:10|max:500',
            'categorie'   => 'nullable|string|max:100',
            'type'        => 'nullable|in:payant,gratuit',
        ]);

        $service = new ProduitIaService();
        $resultat = $service->genererPageVente(
            $request->description,
            $request->categorie ?? 'Produit numérique',
            $request->type ?? 'payant'
        );

        if (empty($resultat['titre'])) {
            return response()->json(['error' => 'Génération échouée. Réessayez.'], 500);
        }

        return response()->json($resultat);
    }

    // ── Feature 6 : Traduction & adaptation ─────────────────
    public function traduire(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:191',
            'description' => 'required|string',
            'langue'      => 'required|in:en,sw,ha,pt,ar',
        ]);

        $service = new ProduitIaService();
        $resultat = $service->traduireAdapter(
            $request->nom,
            strip_tags($request->description),
            $request->langue
        );

        return response()->json($resultat);
    }

    // ── Feature 7 : Score compatibilité (appelé depuis ia-search) ──
    public function scorerCompatibilite(Request $request)
    {
        $request->validate([
            'boutique_id' => 'required|exists:boutiques,id',
        ]);

        $boutiqueId = session('boutique_id');
        if (!$boutiqueId) {
            return response()->json(['error' => 'Aucune boutique sélectionnée.'], 422);
        }

        $maBoutique = Boutique::with('categories')
            ->withCount(['produits', 'transactions as ventes' => fn($q) => $q->where('statut', 'reussi')])
            ->findOrFail($boutiqueId);

        $partenaire = Boutique::with('categories')
            ->withCount(['produits', 'transactions as ventes' => fn($q) => $q->where('statut', 'reussi')])
            ->findOrFail($request->boutique_id);

        $service  = new ProduitIaService();
        $resultat = $service->scorerCompatibilite(
            [
                'nom'        => $maBoutique->nom,
                'categories' => $maBoutique->categories->pluck('nom')->join(', ') ?: 'Non définies',
                'produits'   => $maBoutique->produits_count . ' produits',
                'ventes'     => $maBoutique->ventes . ' ventes',
            ],
            [
                'nom'        => $partenaire->nom,
                'categories' => $partenaire->categories->pluck('nom')->join(', ') ?: 'Non définies',
                'produits'   => $partenaire->produits_count . ' produits',
                'ventes'     => $partenaire->ventes . ' ventes',
            ]
        );

        return response()->json($resultat);
    }
}
