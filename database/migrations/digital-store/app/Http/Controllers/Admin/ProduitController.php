<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Http\Requests\ProduitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Produit::where('boutique_id', $boutiqueId)
            ->with('categorie');
            
        if ($request->has('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }
        
        if ($request->has('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }
        
        $produits = $query->paginate(15);
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.produits.index', compact('produits', 'categories'));
    }
    
    public function create()
    {
        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.produits.create', compact('categories'));
    }
    
    public function store(ProduitRequest $request)
    {
        $data = $request->validated();
        $data['boutique_id'] = session('boutique_id');
        $data['slug'] = Str::slug($data['nom']);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = file_get_contents($image->getRealPath());
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'public');
        }
        
        Produit::create($data);
        
        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }
    
    public function edit(Produit $produit)
    {
        $this->authorize('view', $produit);
        
        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.produits.edit', compact('produit', 'categories'));
    }
    
    public function update(ProduitRequest $request, Produit $produit)
    {
        $this->authorize('update', $produit);
        
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nom']);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = file_get_contents($image->getRealPath());
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            if ($produit->fichier) {
                Storage::disk('public')->delete($produit->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'public');
        }
        
        $produit->update($data);
        
        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }
    
    public function destroy(Produit $produit)
    {
        $this->authorize('delete', $produit);

        if ($produit->fichier) {
            Storage::disk('public')->delete($produit->fichier);
        }
        
        $produit->delete();
        
        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}
