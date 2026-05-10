<?php

namespace App\Services\Pixel;

use App\Models\PixelMarketing;
use App\Models\Boutique;

class PixelInjectionService
{
    /**
     * Injecte les pixels dans la vue
     */
    public function injecter(Boutique $boutique, string $emplacement): string
    {
        $pixels = PixelMarketing::where('boutique_id', $boutique->id)
            ->where('emplacement', $emplacement)
            ->where('est_actif', true)
            ->get();
            
        $html = '';
        
        foreach ($pixels as $pixel) {
            $html .= $this->nettoyerPixel($pixel->code_pixel) . "\n";
        }
        
        return $html;
    }
    
    /**
     * Nettoie le code du pixel
     */
    protected function nettoyerPixel($code)
    {
        // Supprimer les balises script si elles sont déjà présentes
        $code = trim($code);
        
        if (!str_starts_with($code, '<script')) {
            $code = '<script>' . $code . '</script>';
        }
        
        return $code;
    }
    
    /**
     * Injecte les pixels de conversion
     */
    public function injecterConversion(Boutique $boutique, array $data = []): string
    {
        $pixels = PixelMarketing::where('boutique_id', $boutique->id)
            ->where('emplacement', 'confirmation')
            ->where('est_actif', true)
            ->get();
            
        $html = '';
        
        foreach ($pixels as $pixel) {
            $code = $this->injecterDonneesConversion($pixel->code_pixel, $data);
            $html .= $this->nettoyerPixel($code) . "\n";
        }
        
        return $html;
    }
    
    /**
     * Injecte les données de conversion dans le pixel
     */
    protected function injecterDonneesConversion($code, array $data)
    {
        // Remplacer les variables dans le code
        $code = str_replace('{{montant}}', $data['montant'] ?? '0', $code);
        $code = str_replace('{{reference}}', $data['reference'] ?? '', $code);
        $code = str_replace('{{devise}}', $data['devise'] ?? 'EUR', $code);
        
        return $code;
    }
}