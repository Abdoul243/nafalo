<?php

namespace App\Jobs;

use App\Models\Client;
use App\Services\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAccessCodeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $clientId,
        public string $code
    ) {
        $this->afterCommit();
    }

    public function handle(EmailService $emailService): void
    {
        $client = Client::with('boutique.configuration')->find($this->clientId);

        if (!$client) {
            Log::warning('Access code email job skipped: client not found', [
                'client_id' => $this->clientId,
            ]);
            return;
        }

        $emailService->envoyerCodeAcces($client, $this->code);
    }
}
