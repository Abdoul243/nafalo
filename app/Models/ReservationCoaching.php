<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationCoaching extends Model
{
    protected $table = 'reservations_coaching';

    protected $fillable = [
        'produit_id', 'client_id', 'achat_id', 'date_souhaitee', 'date_confirmee',
        'statut', 'lien_visio', 'message',
    ];

    protected $casts = [
        'date_souhaitee' => 'datetime',
        'date_confirmee' => 'datetime',
    ];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function client()  { return $this->belongsTo(Client::class); }
}
