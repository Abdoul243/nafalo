<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copublication extends Model
{
    use HasFactory;

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_ACCEPTE    = 'accepte';
    const STATUT_REFUSE     = 'refuse';

    protected $fillable = [
        'produit_id',
        'proprietaire_id',
        'copublicateur_id',
        'boutique_copublicateur_id',
        'pourcentage_proprietaire',
        'pourcentage_copublicateur',
        'statut',
        'message',
    ];

    protected $casts = [
        'pourcentage_proprietaire'  => 'decimal:2',
        'pourcentage_copublicateur' => 'decimal:2',
    ];

    /* ── Relations ─────────────────────────────────────────────────── */

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function proprietaire()
    {
        return $this->belongsTo(Utilisateur::class, 'proprietaire_id');
    }

    public function copublicateur()
    {
        return $this->belongsTo(Utilisateur::class, 'copublicateur_id');
    }

    public function boutiqueCopublicateur()
    {
        return $this->belongsTo(Boutique::class, 'boutique_copublicateur_id');
    }

    /* ── Helpers ───────────────────────────────────────────────────── */

    public function estEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function estAccepte(): bool
    {
        return $this->statut === self::STATUT_ACCEPTE;
    }

    public function estRefuse(): bool
    {
        return $this->statut === self::STATUT_REFUSE;
    }

    /**
     * Calcule le gain du propriétaire sur un montant net (après commission Nafalo).
     */
    public function gainProprietaire(float $montantNet): float
    {
        return round($montantNet * ($this->pourcentage_proprietaire / 100), 2);
    }

    /**
     * Calcule le gain du co-publicateur sur un montant net.
     */
    public function gainCopublicateur(float $montantNet): float
    {
        return round($montantNet * ($this->pourcentage_copublicateur / 100), 2);
    }
}
