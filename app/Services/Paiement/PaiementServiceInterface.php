<?php

namespace App\Services\Paiement;

interface PaiementServiceInterface
{
    /**
     * Crée un intent de paiement
     */
    public function createPaymentIntent(int $montant, string $devise, array $options = []);
    
    /**
     * Traite un paiement
     */
    public function processPayment(string $paymentMethodId, int $montant, string $devise);
    
    /**
     * Gère le webhook
     */
    public function handleWebhook(string $payload, string $sigHeader);
    
    /**
     * Rembourse un paiement
     */
    public function rembourser(string $paymentIntentId);
}