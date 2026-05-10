<?php

namespace App\Services\Email;

use App\Models\Boutique;
use App\Models\PanierAbandonne;
use Illuminate\Support\Facades\Log;

class RelancePanierService
{
    protected $emailService;
    
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    /**
     * Envoie les relances pour les paniers abandonnés
     */
    public function envoyerRelances()
    {
        $boutiques = Boutique::where('est_active', true)->get();
        
        foreach ($boutiques as $boutique) {
            $this->envoyerRelancesPourBoutique($boutique);
        }
    }
    
    /**
     * Envoie les relances pour une boutique spécifique
     */
    public function envoyerRelancesPourBoutique(Boutique $boutique)
    {
        $delaiJours = $boutique->configuration->relance_delai_jours ?? 3;
        
        $paniers = PanierAbandonne::where('boutique_id', $boutique->id)
            ->where('relance_envoyee', false)
            ->where('created_at', '<=', now()->subDays($delaiJours))
            ->whereNotNull('email')
            ->get();
            
        foreach ($paniers as $panier) {
            try {
                $this->emailService->envoyerRelancePanier(
                    $panier->email,
                    $panier->client?->nom ?? 'Client',
                    $panier->contenu,
                    $boutique
                );
                
                $panier->update([
                    'relance_envoyee' => true,
                    'date_relance' => now()
                ]);
                
                Log::info('Relance envoyée', ['panier_id' => $panier->id, 'email' => $panier->email]);
                
            } catch (\Exception $e) {
                Log::error('Erreur envoi relance', [
                    'panier_id' => $panier->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}