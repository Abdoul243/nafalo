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
        
        if ($request->filled('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'publie') {
                $query->where('est_publie', true);
            } elseif ($request->statut === 'brouillon') {
                $query->where('est_publie', false);
            }
        }

        $produits = $query->latest()->paginate(15);
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();
        
        return view('admin.produits.index', compact('produits', 'categories'));
    }
    
    /** Écran de choix du type de produit (style Chariow). */
    public function choisirType()
    {
        return view('admin.produits.choisir-type');
    }

    public function create(Request $request)
    {
        $boutiqueId = session('boutique_id');
        $categories = Categorie::where('boutique_id', $boutiqueId)->get();

        // Format pré-sélectionné depuis l'écran de choix (fichier | formation)
        $formatInitial = in_array($request->query('format'), ['fichier', 'formation'])
            ? $request->query('format')
            : 'fichier';

        return view('admin.produits.create', compact('categories', 'formatInitial'));
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
            // Disque PRIVÉ : le fichier produit n'est jamais accessible en direct,
            // uniquement via la route de téléchargement protégée par token.
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'local');
        }

        // Lead magnet : forcer prix à 0 si gratuit
        if (($data['type'] ?? 'payant') === 'gratuit') {
            $data['prix'] = 0;
        }

        $produit = Produit::create($data);

        // Si c'est une formation → aller directement construire le programme.
        if ($produit->estFormation()) {
            return redirect()->route('admin.produits.formation.programme', $produit)
                ->with('success', 'Produit créé. Construisez maintenant le programme.');
        }

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }
    
    public function show(Produit $produit)
    {
        return redirect()->route('admin.produits.edit', $produit);
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
                Storage::disk('local')->delete($produit->fichier);
            }
            // Disque PRIVÉ (cf. store())
            $data['fichier'] = $request->file('fichier')->store('produits/fichiers', 'local');
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
            Storage::disk('local')->delete($produit->fichier);
        }
        
        $produit->delete();

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Téléchargement du fichier produit par le marchand propriétaire.
     * Le fichier est sur le disque privé : seul ce point d'accès contrôlé y mène.
     */
    public function telechargerFichier(Produit $produit)
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
        abort_if(!$produit->fichier || !Storage::disk('local')->exists($produit->fichier), 404);

        return Storage::disk('local')->download($produit->fichier, basename($produit->fichier));
    }
}