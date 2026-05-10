<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageIa extends Model
{
    protected $table = 'pages_ia';

    protected $fillable = [
        'produit_id',
        'boutique_id',
        'prompt_original',
        'contenu_html',
        'slug_page',
        'est_publiee',
        'modele_ia',
        'tokens_utilises',
    ];

    protected $casts = [
        'est_publiee' => 'boolean',
    ];

    /* ── Relations ──────────────────────────────────────────────────── */

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }
}
