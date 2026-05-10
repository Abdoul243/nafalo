<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Produit;
use App\Models\CodePromo;
use App\Models\PanierAbandonne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PanierController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }
    
    public function index()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);
        $produits = [];
        $total = 0;
        
        if (!empty($panier)) {
            $ids = array_keys($panier);
            $produits = Produit::whereIn('id', $ids)->get();
            
            foreach ($produits as $produit) {
                $total += $produit->prix * $panier[$produit->id];
            }
        }
        
        return view('boutique.panier.index', [
            'boutique' => $this->boutique,
            'panier' => $panier,
            'produits' => $produits,
            'total' => $total
        ]);
    }
    
    public function ajouter(Request $request, Produit $produit)
    {
        if ($produit->boutique_id != $this->boutique->id || !$produit->est_publie) {
            abort(404);
        }
        
        $quantite = $request->get('quantite', 1);
        
        $panier = Session::get('panier_' . $this->boutique->id, []);
        
        // ✅ Un seul ajout par produit autorisé
        if (isset($panier[$produit->id])) {
            return redirect()->route('boutique.checkout.informations')
                ->with('info', 'Ce produit est déjà dans votre panier.');
        }
        
        $panier[$produit->id] = 1;
        
        Session::put('panier_' . $this->boutique->id, $panier);
        
        // ✅ Redirection directe vers le checkout
        return redirect()->route('boutique.checkout.informations')
            ->with('success', 'Produit ajouté — finalisez votre commande.');
    }
    
    public function mettreAJour(Request $request)
    {
        $request->validate([
            'quantites' => 'nullable|array',
            'quantites.*' => 'nullable|integer|min:0',
        ]);

        $panier = Session::get('panier_' . $this->boutique->id, []);
        $quantites = $request->input('quantites', []);
        
        foreach ($quantites as $produitId => $quantite) {
            if ($quantite > 0) {
                $panier[$produitId] = $quantite;
            } else {
                unset($panier[$produitId]);
            }
        }
        
        Session::put('panier_' . $this->boutique->id, $panier);
        
        return redirect()->route('boutique.panier.index')
            ->with('success', 'Panier mis à jour.');
    }
    
    public function supprimer(Produit $produit)
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);
        
        if (isset($panier[$produit->id])) {
            unset($panier[$produit->id]);
            Session::put('panier_' . $this->boutique->id, $panier);
        }
        
        return redirect()->route('boutique.panier.index')
            ->with('success', 'Produit retiré du panier.');
    }
    
    public function appliquerCodePromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $codePromo = CodePromo::where('boutique_id', $this->boutique->id)
            ->where('code', $request->code)
            ->where('est_actif', true)
            ->first();
            
        if (!$codePromo || !$codePromo->estValide()) {
            return redirect()->back()->with('error', 'Code promo invalide.');
        }
        
        Session::put('code_promo_' . $this->boutique->id, $codePromo->id);
        
        return redirect()->back()->with('success', 'Code promo appliqué avec succès.');
    }
    
    public function supprimerCodePromo()
    {
        Session::forget('code_promo_' . $this->boutique->id);
        
        return redirect()->back()->with('success', 'Code promo retiré.');
    }
    
    public function abandonner()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);
        
        if (!empty($panier) && !Session::has('client_temp_' . $this->boutique->id)) {
            PanierAbandonne::create([
                'boutique_id' => $this->boutique->id,
                'contenu' => $panier,
                'montant_total' => $this->calculerTotal($panier)
            ]);
        }
        
        return redirect()->route('boutique.accueil');
    }
    
    private function calculerTotal($panier)
    {
        $total = 0;
        $ids = array_keys($panier);
        $produits = Produit::whereIn('id', $ids)->get();
        
        foreach ($produits as $produit) {
            $total += $produit->prix * $panier[$produit->id];
        }
        
        return $total;
    }
}