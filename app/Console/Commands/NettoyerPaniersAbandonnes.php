<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\Email\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NettoyerPaniersAbandonnes extends Command
{
    protected $signature   = 'boutique:nettoyer-paniers';
    protected $description = 'Marque les transactions en attente comme abandonnées et envoie les relances';

    public function handle(EmailService $emailService)
    {
        // Transactions en attente depuis + de 10 min → abandonnées
        $abandonnees = Transaction::where('statut', 'en_attente')
            ->where('updated_at', '<', now()->subMinutes(10))
            ->with(['client', 'boutique'])
            ->get();

        foreach ($abandonnees as $transaction) {
            $transaction->update(['statut' => 'abandonne']);

            // Envoyer email de relance avec les produits
            try {
                if ($transaction->client && $transaction->client->email) {

                    // Récupérer les produits depuis les détails de la transaction
                    $panier = [];
                    $details = $transaction->details ?? [];
                    foreach ($details as $item) {
                        $panier[] = [
                            'nom'  => $item['nom'] ?? 'Produit',
                            'prix' => $item['prix'] ?? 0,
                        ];
                    }

                    $emailService->envoyerRelancePanier(
                        $transaction->client->email,
                        $transaction->client->nom,
                        $panier,
                        $transaction->boutique
                    );

                    Log::info('Email relance envoyé', [
                        'transaction_id' => $transaction->id,
                        'email'          => $transaction->client->email,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erreur relance panier', [
                    'transaction_id' => $transaction->id,
                    'error'          => $e->getMessage(),
                ]);
            }

            Log::info('Transaction abandonnée', ['id' => $transaction->id]);
        }

        $this->info($abandonnees->count() . ' transactions marquées abandonnées.');
        return 0;
    }
}