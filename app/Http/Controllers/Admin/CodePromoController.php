<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodePromo;
use App\Models\Produit;
use App\Http\Requests\CodePromoRequest;
use Illuminate\Http\Request;

class CodePromoController extends Controller
{
    public function index()
    {
        $boutiqueId = session('boutique_id');
        
        $codesPromo = CodePromo::where('boutique_id', $boutiqueId)
            ->withCount('achats')
            ->latest()
            ->paginate(15);
            
        return view('admin.codes-promo.index', compact('codesPromo'));
    }
    
    public function create()
    {
        $boutiqueId = session('boutique_id');
        $produits = Produit::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.codes-promo.create', compact('produits'));
    }
    
    public function store(CodePromoRequest $request)
    {
        $data = $request->validated();
        $data['boutique_id'] = session('boutique_id');
        
        $codePromo = CodePromo::create($data);
        
        if ($request->has('produits')) {
            $codePromo->produits()->attach($request->produits);
        }
        
        return redirect()->route('admin.codes-promo.index')
            ->with('success', 'Code promo créé avec succès.');
    }
    
    public function edit(CodePromo $codePromo)
    {
        $this->authorize('update', $codePromo);
        
        $boutiqueId = session('boutique_id');
        $produits = Produit::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.codes-promo.edit', compact('codePromo', 'produits'));
    }
    
    public function update(CodePromoRequest $request, CodePromo $codePromo)
    {
        $this->authorize('update', $codePromo);
        
        $codePromo->update($request->validated());
        
        if ($request->has('produits')) {
            $codePromo->produits()->sync($request->produits);
        } else {
            $codePromo->produits()->detach();
        }
        
        return redirect()->route('admin.codes-promo.index')
            ->with('success', 'Code promo mis à jour avec succès.');
    }
    
    public function destroy(CodePromo $codePromo)
    {
        $this->authorize('delete', $codePromo);
        
        $codePromo->delete();
        
        return redirect()->route('admin.codes-promo.index')
            ->with('success', 'Code promo supprimé avec succès.');
    }
}