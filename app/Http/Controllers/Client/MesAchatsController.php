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

        // Abonnements du client, indexés par produit (pour afficher statut/renouvellement)
        $abonnements = \App\Models\Abonnement::where('client_id', $client->id)
            ->get()
            ->keyBy('produit_id');

        // Clés de licence attribuées, indexées par achat
        $clesLicence = \App\Models\CleLicence::whereIn('achat_id', $achats->pluck('id'))
            ->get()
            ->keyBy('achat_id');

        return view('boutique.client.mes-achats.index', [
            'boutique'    => $boutique,
            'client'      => $client,
            'achats'      => $achats,
            'abonnements' => $abonnements,
            'clesLicence' => $clesLicence,
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
        
        return view('boutique.client.mes-achats.show', [
            'boutique' => $boutique,
            'client' => $client,
            'achat' => $achat
        ]);
    }
    
    protected function getBoutique()
    {
        static $localHosts = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];
        $request = request();
        $host    = $request->getHost();

        $domaine = $request->route('domaine');
        if ($domaine) {
            $b = Boutique::where('domaine_personnalise', $domaine)->where('est_active', true)->first();
            if ($b) return $b;
        }

        if (!in_array($host, $localHosts, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }

        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }
        if (session('boutique_domaine')) {
            $b = Boutique::where('domaine_personnalise', session('boutique_domaine'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        return Boutique::where('est_active', true)->firstOrFail();
    }
}