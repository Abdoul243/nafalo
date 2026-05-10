<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Produit;
use App\Models\Avis;
use App\Models\Achat;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AvisController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }
    
    public function create(Produit $produit)
    {
        // Vérifier que le client a acheté ce produit
        $clientEmail = Session::get('client_acces_' . $this->boutique->id);
        
        if (!$clientEmail) {
            return redirect()->route('client.acces.demande')
                ->with('error', 'Veuillez vous connecter pour laisser un avis.');
        }
        
        $client = Client::where('boutique_id', $this->boutique->id)
            ->where('email', $clientEmail)
            ->first();
            
        if (!$client) {
            return redirect()->route('client.acces.demande')
                ->with('error', 'Client non trouvé.');
        }
        
        // Vérifier si le client a acheté ce produit
        $achat = Achat::where('client_id', $client->id)
            ->where('produit_id', $produit->id)
            ->whereHas('transaction', function($q) {
                $q->where('statut', Transaction::STATUT_REUSSI);
            })
            ->first();
            
        if (!$achat) {
            return redirect()->route('boutique.produit.show', $produit->slug)
                ->with('error', 'Vous devez acheter ce produit pour laisser un avis.');
        }
        
        // Vérifier si un avis existe déjà
        if ($achat->avis) {
            return redirect()->route('boutique.produit.show', $produit->slug)
                ->with('error', 'Vous avez déjà laissé un avis pour ce produit.');
        }
        
        return view('boutique.avis.create', [
            'boutique' => $this->boutique,
            'produit' => $produit,
            'achat' => $achat
        ]);
    }
    
    public function store(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000'
        ]);
        
        $clientEmail = Session::get('client_acces_' . $this->boutique->id);
        
        if (!$clientEmail) {
            return redirect()->route('client.acces.demande')
                ->with('error', 'Veuillez vous connecter pour laisser un avis.');
        }
        
        $client = Client::where('boutique_id', $this->boutique->id)
            ->where('email', $clientEmail)
            ->first();
            
        if (!$client) {
            return redirect()->route('client.acces.demande')
                ->with('error', 'Client non trouvé.');
        }
        
        $achat = Achat::where('client_id', $client->id)
            ->where('produit_id', $produit->id)
            ->whereHas('transaction', function($q) {
                $q->where('statut', Transaction::STATUT_REUSSI);
            })
            ->first();
            
        if (!$achat) {
            return redirect()->route('boutique.produit.show', $produit->slug)
                ->with('error', 'Vous devez acheter ce produit pour laisser un avis.');
        }
        
        Avis::create([
            'produit_id' => $produit->id,
            'client_id' => $client->id,
            'achat_id' => $achat->id,
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'],
            'est_visible' => true
        ]);
        
        return redirect()->route('boutique.produit.show', $produit->slug)
            ->with('success', 'Merci pour votre avis !');
    }
}

