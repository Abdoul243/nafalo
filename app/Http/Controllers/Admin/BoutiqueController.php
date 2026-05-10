<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Http\Requests\BoutiqueRequest;

class BoutiqueController extends Controller
{
    public function index()
    {
        $boutiques = Boutique::paginate(10);
        return view('admin.boutiques.index', compact('boutiques'));
    }
    
    public function create()
    {
        return view('admin.boutiques.create');
    }
    
    public function store(BoutiqueRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $data['logo'] = file_get_contents($logo->getRealPath());
            $data['logo_mime'] = $logo->getMimeType();
            $data['logo_taille'] = $logo->getSize();
        }
        
        $boutique = Boutique::create($data);
        
        // Créer la configuration par défaut
        $boutique->configuration()->create([
            'devise' => 'EUR',
            'relance_delai_jours' => 3
        ]);
        
        return redirect()->route('admin.boutiques.index')
            ->with('success', 'Boutique créée avec succès.');
    }
    
    public function edit(Boutique $boutique)
    {
        return view('admin.boutiques.edit', compact('boutique'));
    }
    
    public function update(BoutiqueRequest $request, Boutique $boutique)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $data['logo'] = file_get_contents($logo->getRealPath());
            $data['logo_mime'] = $logo->getMimeType();
            $data['logo_taille'] = $logo->getSize();
        }
        
        $boutique->update($data);
        
        return redirect()->route('admin.boutiques.index')
            ->with('success', 'Boutique mise à jour avec succès.');
    }
    
    public function destroy(Boutique $boutique)
    {
        $boutique->delete();
        
        return redirect()->route('admin.boutiques.index')
            ->with('success', 'Boutique supprimée avec succès.');
    }
    
    public function toggleActivation(Boutique $boutique)
    {
        $boutique->update(['est_active' => !$boutique->est_active]);
        
        return response()->json([
            'success' => true,
            'est_active' => $boutique->est_active
        ]);
    }
}
