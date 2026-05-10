<?php

namespace App\Services\Paiement;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;
use Exception;

class PayPalService implements PaiementServiceInterface
{
    protected $client;
    
    public function __construct()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        $mode = config('services.paypal.mode', 'sandbox');
        
        if ($mode === 'sandbox') {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        
        $this->client = new PayPalHttpClient($environment);
    }
    
    public function createPaymentIntent(int $montant, string $devise, array $options = [])
    {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $devise,
                        'value' => number_format($montant / 100, 2)
                    ],
                    'reference_id' => $options['reference'] ?? uniqid()
                ]],
                'application_context' => [
                    'return_url' => $options['return_url'] ?? route('paiement.success'),
                    'cancel_url' => $options['cancel_url'] ?? route('paiement.cancel')
                ]
            ];
            
            $response = $this->client->execute($request);
            
            return [
                'id' => $response->result->id,
                'approval_url' => $this->getApprovalUrl($response->result->links)
            ];
        } catch (Exception $e) {
            throw new Exception('Erreur PayPal : ' . $e->getMessage());
        }
    }
    
    public function processPayment(string $paymentMethodId, int $montant, string $devise)
    {
        // PayPal utilise une approche différente, ceci est géré via les webhooks
        return true;
    }
    
    public function handleWebhook(string $payload, string $sigHeader)
    {
        try {
            $data = json_decode($payload, true);
            
            if ($data['event_type'] === 'PAYMENT.CAPTURE.COMPLETED') {
                // Paiement réussi
                $resource = $data['resource'];
                return [
                    'type' => 'payment.succeeded',
                    'reference' => $resource['custom_id'] ?? null,
                    'data' => $resource
                ];
            }
            
            return $data;
        } catch (Exception $e) {
            throw new Exception('Erreur webhook PayPal : ' . $e->getMessage());
        }
    }
    
    public function rembourser(string $paymentId)
    {
        try {
            $request = new CapturesRefundRequest($paymentId);
            $response = $this->client->execute($request);
            
            return $response->result->status === 'COMPLETED';
        } catch (Exception $e) {
            throw new Exception('Erreur remboursement PayPal : ' . $e->getMessage());
        }
    }
    
    protected function getApprovalUrl($links)
    {
        foreach ($links as $link) {
            if ($link->rel === 'approve') {
                return $link->href;
            }
        }
        
        return null;
    }
}