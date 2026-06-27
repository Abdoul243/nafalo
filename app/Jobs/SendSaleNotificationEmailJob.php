<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSaleNotificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public int $transactionId)
    {
        $this->afterCommit();
    }

    public function handle(EmailService $emailService): void
    {
        $transaction = Transaction::with([
            'boutique.utilisateur',
            'boutique.configuration',
            'client',
            'achats.produit',
        ])->find($this->transactionId);

        if (!$transaction) {
            Log::warning('Sale notification email job skipped: transaction not found', [
                'transaction_id' => $this->transactionId,
            ]);
            return;
        }

        $emailService->envoyerNotificationVente($transaction);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendSaleNotificationEmailJob failed', [
            'transaction_id' => $this->transactionId,
            'error'          => $e->getMessage(),
        ]);
    }
}
