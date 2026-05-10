<?php

namespace App\Http\Controllers\Boutique;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Transaction;
use App\Services\Paiement\PaiementServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaiementController extends Controller
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
    
    public function webhook(Request $request)
    {
        // Vérifier la signature du webhook
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        try {
            $event = $this->paiementService->handleWebhook($payload, $sigHeader);
            
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccess($paymentIntent);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailure($paymentIntent);
                    break;
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    protected function handlePaymentSuccess($paymentIntent)
    {
        // Mettre à jour la transaction
        Transaction::where('reference', $paymentIntent->metadata->reference)
            ->update(['statut' => Transaction::STATUT_REUSSI]);
    }
    
    protected function handlePaymentFailure($paymentIntent)
    {
        // Mettre à jour la transaction
        Transaction::where('reference', $paymentIntent->metadata->reference)
            ->update(['statut' => Transaction::STATUT_ECHOUE]);
    }
    
    public function success(Request $request)
    {
        $reference = $request->get('reference');
        
        $transaction = Transaction::where('boutique_id', $this->boutique->id)
            ->where('reference', $reference)
            ->first();
            
        if ($transaction && $transaction->estReussie()) {
            return redirect()->route('boutique.checkout.confirmation', ['reference' => $reference]);
        }
        
        return redirect()->route('boutique.panier.index')
            ->with('error', 'Une erreur est survenue lors du paiement.');
    }
    
    public function cancel(Request $request)
    {
        return redirect()->route('boutique.checkout.paiement')
            ->with('error', 'Paiement annulé.');
    }
}

