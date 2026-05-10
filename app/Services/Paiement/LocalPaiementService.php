<?php

namespace App\Services\Paiement;

class LocalPaiementService implements PaiementServiceInterface
{
    public function createPaymentIntent(int $montant, string $devise, array $options = [])
    {
        return [
            'client_secret' => 'local_intent_' . uniqid(),
            'id' => 'local_pi_' . uniqid(),
        ];
    }

    public function processPayment(string $paymentMethodId, int $montant, string $devise)
    {
        return true;
    }

    public function handleWebhook(string $payload, string $sigHeader)
    {
        return (object) [
            'type' => 'local.webhook',
            'data' => (object) ['object' => []],
        ];
    }

    public function rembourser(string $paymentIntentId)
    {
        return true;
    }
}
