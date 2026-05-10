<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AccueilController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }
    
    public function index()
    {
        $produits = Produit::where('boutique_id', $this->boutique->id)
            ->where('est_publie', true)
            ->with('categorie')
            ->latest()
            ->paginate(12);
            
        $categories = Categorie::where('boutique_id', $this->boutique->id)
            ->withCount('produits')
            ->get();
            
        return view('boutique.accueil', [
            'boutique' => $this->boutique,
            'produits' => $produits,
            'categories' => $categories
        ]);
    }
}

