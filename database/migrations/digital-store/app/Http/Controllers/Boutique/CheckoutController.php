<?php

namespace App\Http\Controllers\Boutique;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Achat;
use App\Models\CodePromo;
use App\Models\PanierAbandonne;
use App\Jobs\SendPurchaseFilesEmailJob;
use App\Services\Paiement\PaiementServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $boutique;
    protected $paiementService;
    
    public function __construct(Request $request, PaiementServiceInterface $paiementService)
    {
        $domaine = $request->route('domaine');
        if (!$domaine) {
            $host = $request->getHost();
            if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
                $domaine = session('boutique_domaine');
            }
            $domaine = $domaine ?: $host;
        }
        
        $this->boutique = Boutique::where('domaine_personnalise', $domaine)
            ->where('est_active', true)
            ->firstOrFail();
            
        $this->paiementService = $paiementService;
    }
    
    public function informations()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);
        
        if (empty($panier)) {
            return redirect()->route('boutique.panier.index');
        }
        
        return view('boutiques.checkout.informations', [
            'boutique' => $this->boutique
        ]);
    }
    
    public function traiterInformations(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:50'
        ]);
        
        Session::put('client_temp_' . $this->boutique->id, $validated);
        
        return redirect()->route('boutique.checkout.paiement');
    }
    
    public function paiement()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);
        $clientData = Session::get('client_temp_' . $this->boutique->id);
        
        if (empty($panier) || !$clientData) {
            return redirect()->route('boutique.panier.index');
        }
        
        $total = $this->calculerTotal($panier);
        $intentSecret = $this->paiementService->createPaymentIntent($total * 100, $this->boutique->configuration->devise ?? 'EUR');
        
        return view('boutiques.checkout.paiement', [
            'boutique' => $this->boutique,
            'total' => $total,
            'intentSecret' => $intentSecret,
            'clientData' => $clientData
        ]);
    }
    
    public function traiterPaiement(Request $request)
    {
        $paymentMethodId = $request->input('payment_method_id', 'kkiapay-test');

        $panier = Session::get('panier_' . $this->boutique->id, []);
        $clientData = Session::get('client_temp_' . $this->boutique->id);
        
        if (empty($panier) || !$clientData) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Session expirée'], 400);
            }

            return redirect()->route('boutique.panier.index')
                ->with('error', 'Session expirée.');
        }
        
        DB::beginTransaction();
        
        try {
            // Créer ou récupérer le client
            $client = Client::firstOrCreate(
                [
                    'boutique_id' => $this->boutique->id,
                    'email' => $clientData['email']
                ],
                [
                    'nom' => $clientData['nom'],
                    'telephone' => $clientData['telephone'] ?? null
                ]
            );
            
            // Calculer le total
            $total = $this->calculerTotal($panier);
            
            // Appliquer le code promo si existant
            $codePromoId = Session::get('code_promo_' . $this->boutique->id);
            
            // Créer la transaction
            $transaction = Transaction::create([
                'boutique_id' => $this->boutique->id,
                'client_id' => $client->id,
                'reference' => 'TRX-' . uniqid(),
                'montant_total' => $total,
                'statut' => Transaction::STATUT_EN_ATTENTE,
                'mode_paiement' => 'carte',
                'ip_client' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Créer les achats
            foreach ($panier as $produitId => $quantite) {
                $produit = Produit::findOrFail($produitId);
                
                Achat::create([
                    'transaction_id' => $transaction->id,
                    'client_id' => $client->id,
                    'produit_id' => $produitId,
                    'prix_unitaire' => $produit->prix,
                    'quantite' => $quantite,
                    'code_promo_id' => $codePromoId
                ]);
            }
            
            // Traiter le paiement
            $paiementReussi = $this->paiementService->processPayment(
                $paymentMethodId,
                $total * 100,
                $this->boutique->configuration->devise ?? 'EUR'
            );
            
            if ($paiementReussi) {
                $transaction->update(['statut' => Transaction::STATUT_REUSSI]);
                
                // Nettoyer la session
                Session::forget('panier_' . $this->boutique->id);
                Session::forget('client_temp_' . $this->boutique->id);
                Session::forget('code_promo_' . $this->boutique->id);
                
                // Supprimer le panier abandonné s'il existe
                PanierAbandonne::where('boutique_id', $this->boutique->id)
                    ->where('email', $client->email)
                    ->delete();
                
                DB::commit();

                SendPurchaseFilesEmailJob::dispatch($transaction->id);
                
                $redirectUrl = route('boutique.checkout.confirmation', ['reference' => $transaction->reference]);
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrl
                    ]);
                }

                return redirect($redirectUrl);
            } else {
                $transaction->update(['statut' => Transaction::STATUT_ECHOUE]);
                DB::commit();
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Paiement echoue'], 400);
                }

                return redirect()->route('boutique.checkout.paiement')
                    ->with('error', 'Paiement echoue.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->route('boutique.checkout.paiement')
                ->with('error', $e->getMessage());
        }
    }
    
    public function confirmation($reference)
    {
        $transaction = Transaction::where('boutique_id', $this->boutique->id)
            ->where('reference', $reference)
            ->with(['achats.produit', 'client'])
            ->firstOrFail();
            
        return view('boutiques.checkout.merci', [
            'boutique' => $this->boutique,
            'transaction' => $transaction
        ]);
    }
    
    private function calculerTotal($panier)
    {
        $total = 0;
        $ids = array_keys($panier);
        $produits = Produit::whereIn('id', $ids)->get();
        
        foreach ($produits as $produit) {
            $total += $produit->prix * $panier[$produit->id];
        }
        
        // Appliquer le code promo si existant
        $codePromoId = Session::get('code_promo_' . $this->boutique->id);
        if ($codePromoId) {
            $codePromo = CodePromo::find($codePromoId);
            if ($codePromo && $codePromo->estValide()) {
                $total = $total - $codePromo->calculerReduction($total);
            }
        }
        
        return max(0, $total);
    }
}



