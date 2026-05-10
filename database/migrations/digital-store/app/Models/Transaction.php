<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_REUSSI = 'reussi';
    const STATUT_ECHOUE = 'echoue';
    const STATUT_ABANDONNE = 'abandonne';

    protected $fillable = [
        'boutique_id',
        'client_id',
        'reference',
        'montant_total',
        'statut',
        'mode_paiement',
        'details_paiement',
        'ip_client',
        'user_agent'
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'details_paiement' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function estReussie()
    {
        return $this->statut === self::STATUT_REUSSI;
    }

    public function estEchouee()
    {
        return $this->statut === self::STATUT_ECHOUE;
    }

    public function estAbandonnee()
    {
        return $this->statut === self::STATUT_ABANDONNE;
    }

    public function estEnAttente()
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }
}