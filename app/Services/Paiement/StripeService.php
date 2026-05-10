<?php

namespace App\Services\Paiement;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Exception;

class StripeService implements PaiementServiceInterface
{
    protected $secretKey;
    protected $webhookSecret;
    
    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->webhookSecret = config('services.stripe.webhook_secret');
        
        Stripe::setApiKey($this->secretKey);
    }
    
    public function createPaymentIntent(int $montant, string $devise, array $options = [])
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => $montant,
                'currency' => strtolower($devise),
                'metadata' => $options['metadata'] ?? [],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            
            return [
                'client_secret' => $intent->client_secret,
                'id' => $intent->id
            ];
        } catch (Exception $e) {
            throw new Exception('Erreur Stripe : ' . $e->getMessage());
        }
    }
    
    public function processPayment(string $paymentMethodId, int $montant, string $devise)
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => $montant,
                'currency' => strtolower($devise),
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);
            
            return $intent->status === 'succeeded';
        } catch (Exception $e) {
            throw new Exception('Erreur Stripe : ' . $e->getMessage());
        }
    }
    
    public function handleWebhook(string $payload, string $sigHeader)
    {
        try {
            $event = Webhook::constructEvent(
                $payload, 
                $sigHeader, 
                $this->webhookSecret
            );
            
            return $event;
        } catch (Exception $e) {
            throw new Exception('Erreur webhook Stripe : ' . $e->getMessage());
        }
    }
    
    public function rembourser(string $paymentIntentId)
    {
        try {
            $intent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($intent->status === 'succeeded') {
                $intent->refund();
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception('Erreur remboursement Stripe : ' . $e->getMessage());
        }
    }
}