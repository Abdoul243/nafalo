<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\PageIa;
use Illuminate\Http\Request;

class ProduitController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }
    
    public function index(Request $request)
    {
        $query = Produit::where('boutique_id', $this->boutique->id)
            ->where('est_publie', true)
            ->with('categorie');
            
        if ($request->has('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }
        
        if ($request->has('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }
        
        if ($request->has('tri')) {
            switch ($request->tri) {
                case 'prix_asc':
                    $query->orderBy('prix');
                    break;
                case 'prix_desc':
                    $query->orderByDesc('prix');
                    break;
                case 'nouveaute':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $produits = $query->paginate(12);
        $categories = Categorie::where('boutique_id', $this->boutique->id)->get();
        
        return view('boutique.produits.index', [
            'boutique' => $this->boutique,
            'produits' => $produits,
            'categories' => $categories
        ]);
    }
    
    public function show($slug)
    {
        $produit = Produit::where('boutique_id', $this->boutique->id)
            ->where('slug', $slug)
            ->where('est_publie', true)
            ->with(['categorie', 'avis' => function($q) {
                $q->where('est_visible', true)->latest();
            }])
            ->firstOrFail();
            
        $produitsSimilaires = Produit::where('boutique_id', $this->boutique->id)
            ->where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->where('est_publie', true)
            ->limit(4)
            ->get();
            
        // Page de vente IA publiée pour ce produit
        $pageIa = PageIa::where('produit_id', $produit->id)
            ->where('boutique_id', $this->boutique->id)
            ->where('est_publiee', true)
            ->first();

        return view('boutique.produits.show', [
            'boutique' => $this->boutique,
            'produit' => $produit,
            'produitsSimilaires' => $produitsSimilaires,
            'pageIa' => $pageIa,
        ]);
    }
}

