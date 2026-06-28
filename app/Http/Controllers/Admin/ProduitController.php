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

        // Format pré-sélectionné depuis l'écran de choix
        $formatInitial = in_array($request->query('format'), ['fichier', 'formation', 'licence', 'bundle', 'communaute', 'coaching'])
            ? $request->query('format')
            : 'fichier';

        return view('admin.produits.create', compact('categories', 'formatInitial'));
    }
    
    /** Wizard dédié à la création d'une séance de coaching (étapes Chariow). */
    public function createCoaching()
    {
        $categories = Categorie::where('boutique_id', session('boutique_id'))->get();
        return view('admin.produits.create-coaching', compact('categories'));
    }

    public function storeCoaching(Request $request)
    {
        $data = $request->validate([
            'nom'            => 'required|string|max:255',
            'categorie_id'   => 'nullable|exists:categories,id',
            'description'    => 'nullable|string',
            'prix'           => 'required|numeric|min:0',
            'image'          => 'nullable|image|max:2048',
            'coaching_duree' => 'required|integer|min:5|max:600',
            'coaching_pause' => 'nullable|integer|min:0|max:240',
            'jours'          => 'nullable|array',
            'est_publie'     => 'nullable|boolean',
        ]);

        // Disponibilité hebdomadaire
        $dispo = [];
        foreach (['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $j) {
            $v = $request->input("jours.$j");
            if (!empty($v['actif']) && !empty($v['debut']) && !empty($v['fin']) && $v['debut'] < $v['fin']) {
                $dispo[$j] = [['debut' => $v['debut'], 'fin' => $v['fin']]];
            }
        }

        $slug = Str::slug($data['nom']);
        if (Produit::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $payload = [
            'boutique_id'             => session('boutique_id'),
            'nom'                     => $data['nom'],
            'slug'                    => $slug,
            'categorie_id'            => $data['categorie_id'] ?? null,
            'description'             => $data['description'] ?? null,
            'prix'                    => $data['prix'],
            'type'                    => 'payant',
            'format'                  => 'coaching',
            'coaching_duree'          => $data['coaching_duree'],
            'coaching_pause'          => $data['coaching_pause'] ?? 0,
            'coaching_disponibilites' => $dispo ?: null,
            'est_publie'              => $request->boolean('est_publie'),
        ];

        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $payload['image']        = $img->store('produits/images', 'public');
            $payload['image_mime']   = $img->getMimeType();
            $payload['image_taille'] = $img->getSize();
        }

        $produit = Produit::create($payload);

        return redirect()->route('admin.produits.coaching.reservations', $produit)
            ->with('success', 'Séance de coaching créée ! Vos disponibilités sont enregistrées.');
    }

    /** Wizard dédié à la création d'un produit Fichier (étapes Chariow). */
    public function createFichier()
    {
        $categories = Categorie::where('boutique_id', session('boutique_id'))->orderBy('nom')->get();
        return view('admin.produits.create-fichier', compact('categories'));
    }

    public function storeFichier(Request $request)
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:255',
            'categorie_id' => 'nullable|exists:categories,id',
            'description'  => 'nullable|string',
            'prix'         => 'required|numeric|min:0',
            'fichier'      => 'required|file|mimes:pdf,zip,mp3,mp4,docx,xlsx,png,jpg|max:102400',
            'image'        => 'nullable|image|max:2048',
            'est_publie'   => 'nullable|boolean',
        ]);

        $slug = Str::slug($data['nom']);
        if (Produit::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $payload = [
            'boutique_id'  => session('boutique_id'),
            'nom'          => $data['nom'],
            'slug'         => $slug,
            'categorie_id' => $data['categorie_id'] ?? null,
            'description'  => $data['description'] ?? null,
            'prix'         => $data['prix'],
            'type'         => 'payant',
            'format'       => 'fichier',
            'fichier'      => $request->file('fichier')->store('produits/fichiers', 'local'),
            'est_publie'   => $request->boolean('est_publie'),
        ];

        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $payload['image']        = $img->store('produits/images', 'public');
            $payload['image_mime']   = $img->getMimeType();
            $payload['image_taille'] = $img->getSize();
        }

        $produit = Produit::create($payload);

        return redirect()->route('admin.produits.index')
            ->with('success', 'Produit « ' . $produit->nom . ' » créé avec succès.');
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

        // Si c'est une licence → aller gérer les clés.
        if ($produit->estLicence()) {
            return redirect()->route('admin.produits.licences.gestion', $produit)
                ->with('success', 'Produit créé. Ajoutez maintenant vos clés de licence.');
        }

        // Si c'est un bundle → aller composer le pack.
        if ($produit->estBundle()) {
            return redirect()->route('admin.produits.bundle.gestion', $produit)
                ->with('success', 'Pack créé. Choisissez maintenant les produits inclus.');
        }

        // Si c'est une communauté → aller publier la première annonce.
        if ($produit->estCommunaute()) {
            return redirect()->route('admin.produits.communaute.gestion', $produit)
                ->with('success', 'Communauté créée. Publiez un message de bienvenue.');
        }

        // Si c'est du coaching → aller gérer les réservations / réglages.
        if ($produit->estCoaching()) {
            return redirect()->route('admin.produits.coaching.reservations', $produit)
                ->with('success', 'Séance de coaching créée. Réglez la durée et gérez les réservations ici.');
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