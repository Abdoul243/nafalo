<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Avis::whereHas('produit', function($q) use ($boutiqueId) {
                $q->where('boutique_id', $boutiqueId);
            })
            ->with(['produit', 'client']);
            
        if ($request->has('est_visible')) {
            $query->where('est_visible', $request->est_visible);
        }
        
        if ($request->has('note')) {
            $query->where('note', $request->note);
        }
        
        $avis = $query->latest()->paginate(20);
        
        return view('admin.avis.index', compact('avis'));
    }
    
    public function toggleVisibilite(Avis $avis)
    {
        $this->authorize('update', $avis);
        
        $avis->update(['est_visible' => !$avis->est_visible]);
        
        return response()->json([
            'success' => true,
            'est_visible' => $avis->est_visible
        ]);
    }
    
    public function destroy(Avis $avis)
    {
        $this->authorize('delete', $avis);
        
        $avis->delete();
        
        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis supprimé avec succès.');
    }
}