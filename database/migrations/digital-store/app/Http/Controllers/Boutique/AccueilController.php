<?php

namespace App\Http\Controllers\Boutique;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    protected $boutique;
    
    public function __construct(Request $request)
    {
        $domaine = $request->route('domaine');
        if (!$domaine) {
            $host = $request->getHost();
            if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
                $domaine = session('boutique_domaine');
            }
            $domaine = $domaine ?: $host;
        }
        
        $this->boutique = Boutique::where('domaine_personnalise', $domaine)
            ->where('est_active', true)
            ->firstOrFail();
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
            
        return view('boutiques.accueil', [
            'boutique' => $this->boutique,
            'produits' => $produits,
            'categories' => $categories
        ]);
    }
}

