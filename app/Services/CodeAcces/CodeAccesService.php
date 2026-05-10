<?php

namespace App\Services\CodeAcces;

use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CodeAccesService
{
    /**
     * Génère et envoie un code d'accès directement par email
     */
    public function genererEtEnvoyerCode(Client $client)
    {
        $code = $client->genererCodeAcces();

        try {
            $boutique = $client->boutique;
            $fromEmail = config('mail.from.address', 'hello@example.com');
            $fromName  = $boutique->nom ?? config('mail.from.name', 'Digital Store');

            $contenu = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Code d'accès</title>
                </head>
                <body style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px;'>
                    <h2 style='color: #0f172a;'>Votre code d'accès</h2>
                    <p>Bonjour <strong>{$client->nom}</strong>,</p>
                    <p>Voici votre code d'accès pour consulter vos achats sur <strong>{$fromName}</strong> :</p>
                    <div style='font-size: 36px; font-weight: bold; text-align: center; padding: 20px; background: #f0f4ff; border-radius: 10px; letter-spacing: 8px; color: #2563eb; margin: 20px 0;'>
                        {$code}
                    </div>
                    <p style='color: #64748b; font-size: 0.9rem;'>Ce code est valable <strong>15 minutes</strong>.</p>
                    <p style='color: #64748b; font-size: 0.9rem;'>Si vous n'avez pas demandé ce code, ignorez cet email.</p>
                    <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                    <p style='color: #94a3b8; font-size: 0.8rem;'>{$fromName} &copy; " . date('Y') . "</p>
                </body>
                </html>
            ";

            Mail::send([], [], function ($message) use ($client, $contenu, $fromEmail, $fromName) {
                $message->to($client->email, $client->nom)
                    ->from($fromEmail, $fromName)
                    ->subject("Votre code d'accès - {$fromName}")
                    ->html($contenu);
            });

            Log::info('Code accès envoyé directement', [
                'client_id' => $client->id,
                'email'     => $client->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi code accès', [
                'client_id' => $client->id,
                'error'     => $e->getMessage(),
            ]);
            throw $e;
        }

        return $code;
    }

    /**
     * Nettoie les codes expirés
     */
    public function nettoyerCodesExpires()
    {
        return Client::whereNotNull('code_acces')
            ->where('code_expire_at', '<', now())
            ->update([
                'code_acces'      => null,
                'code_expire_at'  => null,
            ]);
    }
}