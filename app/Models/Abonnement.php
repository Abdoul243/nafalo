<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $table = 'abonnements';

    protected $fillable = [
        'boutique_id', 'client_id', 'produit_id', 'statut', 'intervalle',
        'prix', 'date_debut', 'date_fin', 'rappel_envoye', 'derniere_transaction_id',
    ];

    protected $casts = [
        'prix'          => 'decimal:2',
        'date_debut'    => 'datetime',
        'date_fin'      => 'datetime',
        'rappel_envoye' => 'boolean',
    ];

    public function client()  { return $this->belongsTo(Client::class); }
    public function produit()  { return $this->belongsTo(Produit::class); }
    public function boutique() { return $this->belongsTo(Boutique::class); }

    /** L'abonnement est-il actif (statut actif et non expiré) ? */
    public function estActif(): bool
    {
        return $this->statut === 'actif'
            && $this->date_fin
            && $this->date_fin->isFuture();
    }

    /** Jours restants avant expiration (négatif si expiré). */
    public function joursRestants(): int
    {
        return $this->date_fin ? now()->startOfDay()->diffInDays($this->date_fin->startOfDay(), false) : 0;
    }

    /** Durée d'un intervalle en mois. */
    public static function moisPourIntervalle(string $intervalle): int
    {
        return $intervalle === 'annuel' ? 12 : 1;
    }
}
