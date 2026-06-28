<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleLicence extends Model
{
    protected $table = 'cles_licence';

    protected $fillable = [
        'produit_id', 'cle', 'statut', 'client_id', 'achat_id', 'attribuee_at',
    ];

    protected $casts = [
        'attribuee_at' => 'datetime',
    ];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function client()  { return $this->belongsTo(Client::class); }
    public function achat()   { return $this->belongsTo(Achat::class); }

    public function estDisponible(): bool
    {
        return $this->statut === 'disponible';
    }
}
