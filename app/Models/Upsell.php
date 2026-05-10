<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upsell extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'produit_upsell_id',
        'titre_offre',
        'description_offre',
        'prix_special',
        'ordre',
        'est_actif',
    ];

    protected $casts = [
        'prix_special' => 'decimal:2',
        'est_actif'    => 'boolean',
        'ordre'        => 'integer',
    ];

    /* ── Relations ─────────────────────────────────────────────────── */

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function produitUpsell()
    {
        return $this->belongsTo(Produit::class, 'produit_upsell_id');
    }

    /* ── Helpers ───────────────────────────────────────────────────── */

    /**
     * Retourne le prix effectif (spécial ou normal).
     */
    public function getPrixEffectifAttribute(): float
    {
        if ($this->prix_special !== null) {
            return (float) $this->prix_special;
        }

        return (float) ($this->produitUpsell->prix ?? 0);
    }
}
