<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function produitImage(Produit $produit)
    {
        if (!$produit->image) {
            abort(404);
        }

        if ($this->isBinaryMedia($produit->image, $produit->image_mime, $produit->image_taille)) {
            $mime = $produit->image_mime ?: 'application/octet-stream';
            $content = $produit->image;

            return response($content, 200, [
                'Content-Type' => $mime,
                'Content-Length' => (string) strlen($content),
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if (Storage::disk('public')->exists($produit->image)) {
            return Storage::disk('public')->response($produit->image);
        }

        abort(404);
    }

    public function boutiqueLogo(Boutique $boutique)
    {
        if (!$boutique->logo) {
            abort(404);
        }

        if ($this->isBinaryMedia($boutique->logo, $boutique->logo_mime, $boutique->logo_taille)) {
            $mime = $boutique->logo_mime ?: 'application/octet-stream';
            $content = $boutique->logo;

            return response($content, 200, [
                'Content-Type' => $mime,
                'Content-Length' => (string) strlen($content),
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if (Storage::disk('public')->exists($boutique->logo)) {
            return Storage::disk('public')->response($boutique->logo);
        }

        abort(404);
    }

    private function isBinaryMedia(mixed $content, ?string $mime, ?int $size): bool
    {
        if (!is_string($content)) {
            return false;
        }

        if ($mime || $size) {
            return true;
        }

        return str_contains($content, "\0");
    }
}
