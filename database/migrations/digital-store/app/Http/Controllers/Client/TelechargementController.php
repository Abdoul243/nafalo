<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use App\Models\Telechargement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TelechargementController extends Controller
{
    public function telecharger(Achat $achat)
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
        
        if (!$achat->transaction->estReussie()) {
            abort(403, 'La transaction n\'est pas confirmée.');
        }
        
        $produit = $achat->produit;
        
        if (!$produit->fichier || !Storage::disk('public')->exists($produit->fichier)) {
            abort(404, 'Fichier non trouvé.');
        }
        
        // Enregistrer le téléchargement
        Telechargement::create([
            'achat_id' => $achat->id,
            'client_id' => $client->id,
            'ip_adresse' => request()->ip()
        ]);
        
        return Storage::disk('public')->download($produit->fichier, $produit->nom . '.pdf');
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

