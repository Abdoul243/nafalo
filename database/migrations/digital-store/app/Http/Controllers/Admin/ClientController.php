<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Client::where('boutique_id', $boutiqueId)
            ->withCount('achats', 'transactions');
            
        if ($request->has('recherche')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->recherche . '%')
                  ->orWhere('nom', 'like', '%' . $request->recherche . '%');
            });
        }
        
        $clients = $query->paginate(20);
        
        return view('admin.clients.index', compact('clients'));
    }
    
    public function show(Client $client)
    {
        $this->authorize('view', $client);
        
        $client->load(['achats.produit', 'transactions' => function($q) {
            $q->latest()->limit(10);
        }]);
        
        $totalAchats = $client->achats()->count();
        $totalDepense = $client->transactions()
            ->where('statut', 'reussi')
            ->sum('montant_total');
            
        return view('admin.clients.show', compact('client', 'totalAchats', 'totalDepense'));
    }
    
    public function historique(Client $client)
    {
        $this->authorize('view', $client);
        
        $achats = $client->achats()
            ->with('produit', 'transaction')
            ->latest()
            ->paginate(20);
            
        return view('admin.clients.historique', compact('client', 'achats'));
    }
}