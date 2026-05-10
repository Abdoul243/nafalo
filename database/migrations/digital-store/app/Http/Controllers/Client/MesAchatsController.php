<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MesAchatsController extends Controller
{
    public function index()
    {
        $boutique = $this->getBoutique();
        $clientEmail = Session::get('client_acces_' . $boutique->id);
        
        if (!$clientEmail) {
            return redirect()->route('client.acces.demande');
        }
        
        $client = Client::where('boutique_id', $boutique->id)
            ->where('email', $clientEmail)
            ->first();
            
        if (!$client) {
            Session::forget('client_acces_' . $boutique->id);
            return redirect()->route('client.acces.demande');
        }
        
        $achats = Achat::where('client_id', $client->id)
            ->with(['produit', 'transaction'])
            ->whereHas('transaction', function($q) {
                $q->where('statut', 'reussi');
            })
            ->latest()
            ->paginate(20);
            
        return view('boutiques.client.mes-achats.index', [
            'boutique' => $boutique,
            'client' => $client,
            'achats' => $achats
        ]);
    }
    
    public function show(Achat $achat)
    {
        $boutique = $this->getBoutique();
        $clientEmail = Session::get('client_acces_' . $boutique->id);
        
        if (!$clientEmail) {
            return redirect()->route('client.acces.demande');
        }
        
        $client = Client::where('boutique_id', $boutique->id)
            ->where('email', $clientEmail)
            ->first();
            
        if (!$client || $achat->client_id != $client->id) {
            abort(403);
        }
        
        $achat->load(['produit', 'transaction', 'telechargements' => function($q) {
            $q->latest();
        }]);
        
        return view('boutiques.client.mes-achats.show', [
            'boutique' => $boutique,
            'client' => $client,
            'achat' => $achat
        ]);
    }
    
    protected function getBoutique()
    {
        $domaine = request()->route('domaine');
        if (!$domaine) {
            $host = request()->getHost();
            if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
                $domaine = session('boutique_domaine');
            }
            $domaine = $domaine ?: $host;
        }
        
        return Boutique::where('domaine_personnalise', $domaine)
            ->where('est_active', true)
            ->firstOrFail();
    }
}

