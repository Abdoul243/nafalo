<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function produitImage(Produit $produit)
    {
        return $this->serveMedia(
            $produit->image,
            $produit->image_mime,
            $produit->image_taille
        );
    }

    public function boutiqueLogo(Boutique $boutique)
    {
        return $this->serveMedia(
            $boutique->logo,
            $boutique->logo_mime,
            $boutique->logo_taille
        );
    }

    /**
     * Sert un média stocké de 3 façons possibles :
     *  1. URL externe  → redirect
     *  2. Fichier disk → Storage::response
     *  3. Binaire DB   → response avec Content-Type
     */
    private function serveMedia(?string $content, ?string $mime, ?int $size)
    {
        if (!$content) {
            abort(404);
        }

        // 1. URL externe
        if (str_starts_with($content, 'http://') || str_starts_with($content, 'https://')) {
            return redirect($content);
        }

        // 2. Chemin de fichier sur le disque public
        if (Storage::disk('public')->exists($content)) {
            return Storage::disk('public')->response($content);
        }

        // 3. Données binaires stockées en DB (contient des octets nuls ou mime/taille renseignés
        //    MAIS seulement si ce n'est pas un simple chemin lisible)
        if ($this->isBinaryData($content)) {
            return response($content, 200, [
                'Content-Type'   => $mime ?: 'application/octet-stream',
                'Content-Length' => (string) strlen($content),
                'Cache-Control'  => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }

    /**
     * Détecte si une chaîne contient de vraies données binaires
     * (octets nuls ou caractères non-UTF8) plutôt qu'un chemin texte.
     */
    private function isBinaryData(string $content): bool
    {
        // Présence d'octet nul → binaire certain
        if (str_contains($content, "\0")) {
            return true;
        }

        // Si ça ressemble à un chemin de fichier → pas binaire
        if (preg_match('#^[\w/\-\.]+$#', $content)) {
            return false;
        }

        // Teste si la chaîne est invalide en UTF-8 → probablement binaire
        return !mb_check_encoding($content, 'UTF-8');
    }
}
