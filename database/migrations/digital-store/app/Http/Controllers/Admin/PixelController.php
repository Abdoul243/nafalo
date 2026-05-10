<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PixelMarketing;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    public function index()
    {
        $boutiqueId = session('boutique_id');
        
        $pixels = PixelMarketing::where('boutique_id', $boutiqueId)
            ->latest()
            ->paginate(15);
            
        return view('admin.pixels.index', compact('pixels'));
    }
    
    public function create()
    {
        return view('admin.pixels.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_pixel' => 'required|string',
            'emplacement' => 'required|in:header,footer,checkout,confirmation',
            'est_actif' => 'boolean'
        ]);
        
        $validated['boutique_id'] = session('boutique_id');
        
        PixelMarketing::create($validated);
        
        return redirect()->route('admin.pixels.index')
            ->with('success', 'Pixel marketing ajouté avec succès.');
    }
    
    public function edit(PixelMarketing $pixel)
    {
        $this->authorize('update', $pixel);
        return view('admin.pixels.edit', compact('pixel'));
    }
    
    public function update(Request $request, PixelMarketing $pixel)
    {
        $this->authorize('update', $pixel);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_pixel' => 'required|string',
            'emplacement' => 'required|in:header,footer,checkout,confirmation',
            'est_actif' => 'boolean'
        ]);
        
        $pixel->update($validated);
        
        return redirect()->route('admin.pixels.index')
            ->with('success', 'Pixel marketing mis à jour avec succès.');
    }
    
    public function destroy(PixelMarketing $pixel)
    {
        $this->authorize('delete', $pixel);
        
        $pixel->delete();
        
        return redirect()->route('admin.pixels.index')
            ->with('success', 'Pixel marketing supprimé avec succès.');
    }
    
    public function toggleActivation(PixelMarketing $pixel)
    {
        $this->authorize('update', $pixel);
        
        $pixel->update(['est_actif' => !$pixel->est_actif]);
        
        return response()->json([
            'success' => true,
            'est_actif' => $pixel->est_actif
        ]);
    }
}