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
            ->with('categorie')
            ->withCount('achats');
            
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
        
        // ✅ Stocker l'image en fichier (pas en binaire DB)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('produits/images', 'public');
            $data['image'] = $path;
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'public');
        }
        
        // Lead magnet : forcer prix à 0 si gratuit
        if (($data['type'] ?? 'payant') === 'gratuit') {
            $data['prix'] = 0;
        }

        Produit::create($data);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }
    
    public function show(Produit $produit)
    {
        return view('admin.produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);

        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();

        return view('admin.produits.edit', compact('produit', 'categories'));
    }

    public function update(ProduitRequest $request, Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
        
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nom']);
        
        // ✅ Stocker la nouvelle image en fichier, supprimer l'ancienne
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $image = $request->file('image');
            $path = $image->store('produits/images', 'public');
            $data['image'] = $path;
            $data['image_mime'] = $image->getMimeType();
            $data['image_taille'] = $image->getSize();
        }
        
        if ($request->hasFile('fichier')) {
            if ($produit->fichier) {
                Storage::disk('public')->delete($produit->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'public');
        }
        
        // Lead magnet : forcer prix à 0 si gratuit
        if (($data['type'] ?? $produit->type) === 'gratuit') {
            $data['prix'] = 0;
        }

        $produit->update($data);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }
    
    public function destroy(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);

        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        if ($produit->fichier) {
            Storage::disk('public')->delete($produit->fichier);
        }
        
        $produit->delete();
        
        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}