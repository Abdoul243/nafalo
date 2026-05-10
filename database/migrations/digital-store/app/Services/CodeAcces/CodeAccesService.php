<?php

namespace App\Services\CodeAcces;

use App\Models\Client;
use App\Jobs\SendAccessCodeEmailJob;
use Illuminate\Support\Facades\Log;

class CodeAccesService
{
    /**
     * Génère et envoie un code d'accès
     */
    public function genererEtEnvoyerCode(Client $client)
    {
        $code = $client->genererCodeAcces();
        
        try {
            SendAccessCodeEmailJob::dispatch($client->id, $code);
            
            Log::info('Code d\'accès envoyé', [
                'client_id' => $client->id,
                'email' => $client->email
            ]);
            
            return $code;
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi code d\'accès', [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Nettoie les codes expirés
     */
    public function nettoyerCodesExpires()
    {
        return Client::whereNotNull('code_acces')
            ->where('code_expire_at', '<', now())
            ->update([
                'code_acces' => null,
                'code_expire_at' => null
            ]);
    }
}
