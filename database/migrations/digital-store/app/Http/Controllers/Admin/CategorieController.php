<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index()
    {
        $boutiqueId = $this->resolveBoutiqueId();
        if (!$boutiqueId) {
            return redirect()->route('admin.boutiques.create')
                ->with('error', 'Créez d\'abord une boutique avant d\'ajouter des catégories.');
        }

        $categories = Categorie::where('boutique_id', $boutiqueId)
            ->withCount('produits')
            ->paginate(15);
            
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $boutiqueId = $this->resolveBoutiqueId();
        if (!$boutiqueId) {
            return redirect()->route('admin.boutiques.create')
                ->with('error', 'Créez d\'abord une boutique avant d\'ajouter des catégories.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $validated['boutique_id'] = $boutiqueId;
        $validated['slug'] = Str::slug($validated['nom']);
        
        Categorie::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }
    
    public function edit(Categorie $category)
    {
        $this->authorize('view', $category);
        return view('admin.categories.edit', ['categorie' => $category]);
    }
    
    public function update(Request $request, Categorie $category)
    {
        $this->authorize('update', $category);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $validated['slug'] = Str::slug($validated['nom']);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }
    
    public function destroy(Categorie $category)
    {
        $this->authorize('delete', $category);
        
        if ($category->produits()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer une catégorie qui contient des produits.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
    private function resolveBoutiqueId(): ?int
    {
        $boutiqueId = session('boutique_id');
        if ($boutiqueId) {
            return (int) $boutiqueId;
        }

        $firstBoutiqueId = Boutique::query()->orderBy('id')->value('id');
        if ($firstBoutiqueId) {
            session(['boutique_id' => $firstBoutiqueId]);
            return (int) $firstBoutiqueId;
        }

        return null;
    }
}
