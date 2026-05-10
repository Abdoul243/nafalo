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

class SendPurchaseFilesEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $transactionId)
    {
        $this->afterCommit();
    }

    public function handle(EmailService $emailService): void
    {
        $transaction = Transaction::with([
            'boutique.configuration',
            'client',
            'achats.produit',
        ])->find($this->transactionId);

        if (!$transaction) {
            Log::warning('Purchase files email job skipped: transaction not found', [
                'transaction_id' => $this->transactionId,
            ]);
            return;
        }

        $emailService->envoyerFichiersAchat($transaction);
    }
}
