<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\Email\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Gère le cycle de vie des abonnements (renouvellement manuel) :
 *  - rappel d'échéance J-3 (un seul email par cycle)
 *  - expiration : statut "actif" → "expire" quand la date de fin est passée
 *    (+ un email d'expiration si pas déjà prévenu)
 *
 * Planifiée une fois par jour.
 */
class VerifierAbonnements extends Command
{
    protected $signature   = 'abonnements:verifier {--jours=3 : Délai du rappel avant échéance}';
    protected $description = 'Rappels d\'échéance et expiration des abonnements';

    public function handle(EmailService $emailService): int
    {
        $jours    = max(1, (int) $this->option('jours'));
        $rappels  = 0;
        $expires  = 0;

        // 1. Rappels d'échéance (actifs, échéance dans <= N jours, pas encore prévenus)
        $aRappeler = Abonnement::with(['client', 'produit', 'boutique'])
            ->where('statut', 'actif')
            ->where('rappel_envoye', false)
            ->whereNotNull('date_fin')
            ->where('date_fin', '>', now())
            ->where('date_fin', '<=', now()->addDays($jours))
            ->limit(300)
            ->get();

        foreach ($aRappeler as $abo) {
            try {
                $emailService->envoyerRappelAbonnement($abo);
                $abo->forceFill(['rappel_envoye' => true])->save();
                $rappels++;
            } catch (\Throwable $e) {
                Log::error('Rappel abonnement échoué', ['abonnement_id' => $abo->id, 'error' => $e->getMessage()]);
            }
        }

        // 2. Expiration (échéance passée)
        $aExpirer = Abonnement::with(['client', 'produit', 'boutique'])
            ->where('statut', 'actif')
            ->whereNotNull('date_fin')
            ->where('date_fin', '<', now())
            ->limit(300)
            ->get();

        foreach ($aExpirer as $abo) {
            $abo->forceFill(['statut' => 'expire'])->save();
            $expires++;
            // Email d'expiration si le client n'avait pas encore été relancé ce cycle
            try {
                $emailService->envoyerRappelAbonnement($abo);
            } catch (\Throwable $e) {
                Log::error('Email expiration abonnement échoué', ['abonnement_id' => $abo->id, 'error' => $e->getMessage()]);
            }
        }

        $this->info("Rappels envoyés : {$rappels} | Abonnements expirés : {$expires}");

        return self::SUCCESS;
    }
}
