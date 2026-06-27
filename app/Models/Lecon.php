<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecon extends Model
{
    protected $table = 'lecons';

    protected $fillable = [
        'module_id', 'titre', 'contenu', 'video_url', 'video_fichier',
        'ressource_fichier', 'duree', 'est_apercu', 'ordre',
    ];

    protected $casts = [
        'est_apercu' => 'boolean',
        'duree'      => 'integer',
        'ordre'      => 'integer',
    ];

    public function module()
    {
        return $this->belongsTo(ModuleFormation::class, 'module_id');
    }

    public function progressions()
    {
        return $this->hasMany(ProgressionLecon::class, 'lecon_id');
    }

    /** Type de vidéo : 'lien', 'upload' ou null */
    public function typeVideo(): ?string
    {
        if ($this->video_url) return 'lien';
        if ($this->video_fichier) return 'upload';
        return null;
    }

    /** Convertit un lien YouTube/Vimeo en URL d'intégration (embed) */
    public function videoEmbedUrl(): ?string
    {
        if (!$this->video_url) return null;
        $url = $this->video_url;

        // YouTube
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([\w-]{11})~', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        // Vimeo
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return $url; // déjà une URL d'embed ou autre
    }
}
