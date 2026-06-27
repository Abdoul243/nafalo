<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\Email\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Relance par email les clients qui ont saisi leur email au checkout mais n'ont
 * pas finalisé : paniers abandonnés (en_attente) ou paiements échoués (echoue).
 *
 * - Un seul email par transaction (flag relance_envoyee).
 * - Ne relance pas si le client a fini par payer entre-temps.
 * - Marque les vieilles transactions en attente comme "abandonne" (ménage).
 *
 * Planifiée toutes les minutes ; n'agit qu'au-delà du délai (5 min par défaut).
 */
class NettoyerPaniersAbandonnes extends Command
{
    protected $signature   = 'boutique:nettoyer-paniers {--minutes=5 : Délai avant relance}';
    protected $description = 'Envoie les relances email des paniers abandonnés / paiements échoués';

    public function handle(EmailService $emailService): int
    {
        $delai = max(1, (int) $this->option('minutes'));

        $aRelancer = Transaction::with(['client', 'boutique'])
            ->whereIn('statut', [Transaction::STATUT_EN_ATTENTE, Transaction::STATUT_ECHOUE])
            ->where('relance_envoyee', false)
            ->where('created_at', '<=', now()->subMinutes($delai))
            ->limit(200)
            ->get();

        $envoyes = 0;

        foreach ($aRelancer as $transaction) {
            $client = $transaction->client;

            // Pas d'email exploitable → on marque comme traité, sans renvoyer.
            if (!$client || !$client->email || !$transaction->boutique) {
                $transaction->forceFill(['relance_envoyee' => true, 'relance_envoyee_at' => now()])->save();
                continue;
            }

            // Le client a fini par payer depuis → pas de relance.
            $aDejaPaye = Transaction::where('boutique_id', $transaction->boutique_id)
                ->where('client_id', $transaction->client_id)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->where('created_at', '>=', $transaction->created_at)
                ->exists();

            if ($aDejaPaye) {
                $transaction->forceFill(['relance_envoyee' => true, 'relance_envoyee_at' => now()])->save();
                continue;
            }

            try {
                $panier = [];
                foreach (($transaction->details ?? []) as $item) {
                    $panier[] = [
                        'nom'  => $item['nom'] ?? 'Produit',
                        'prix' => $item['prix'] ?? 0,
                    ];
                }

                $emailService->envoyerRelancePanier(
                    $client->email,
                    $client->nom ?? 'Client',
                    $panier,
                    $transaction->boutique
                );

                $transaction->forceFill(['relance_envoyee' => true, 'relance_envoyee_at' => now()])->save();
                $envoyes++;

                Log::info('Email relance envoyé', [
                    'transaction_id' => $transaction->id,
                    'email'          => $client->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('Erreur relance panier', [
                    'transaction_id' => $transaction->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        // Ménage : transactions encore "en_attente" depuis + de 24h → abandonnées.
        $abandonnees = Transaction::where('statut', Transaction::STATUT_EN_ATTENTE)
            ->where('created_at', '<', now()->subDay())
            ->update(['statut' => Transaction::STATUT_ABANDONNE]);

        $this->info("Relances envoyées : {$envoyes} | Transactions abandonnées : {$abandonnees}");

        return self::SUCCESS;
    }
}
