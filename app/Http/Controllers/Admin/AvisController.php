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

        $baseQuery = Avis::whereHas('produit', function ($q) use ($boutiqueId) {
            $q->where('boutique_id', $boutiqueId);
        });

        // Stats globales
        $stats = [
            'total'   => (clone $baseQuery)->count(),
            'visibles' => (clone $baseQuery)->where('est_visible', true)->count(),
            'masques' => (clone $baseQuery)->where('est_visible', false)->count(),
            'moyenne' => round((clone $baseQuery)->avg('note') ?? 0, 1),
        ];

        $query = (clone $baseQuery)->with(['produit', 'client']);

        if ($request->filled('est_visible')) {
            $query->where('est_visible', $request->est_visible);
        }

        if ($request->filled('note')) {
            $query->where('note', $request->note);
        }

        $avis = $query->latest()->paginate(15);

        return view('admin.avis.index', compact('avis', 'stats'));
    }
    
    public function toggleVisibilite(Avis $avis)
    {
        abort_if($avis->produit?->boutique_id !== session('boutique_id'), 403);

        $avis->update(['est_visible' => !$avis->est_visible]);

        return response()->json([
            'success'     => true,
            'est_visible' => $avis->est_visible,
        ]);
    }

    public function destroy(Avis $avis)
    {
        abort_if($avis->produit?->boutique_id !== session('boutique_id'), 403);

        $avis->delete();

        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis supprimé avec succès.');
    }
}