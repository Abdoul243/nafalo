<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCommunaute extends Model
{
    protected $table = 'messages_communaute';

    protected $fillable = [
        'produit_id', 'client_id', 'est_marchand', 'nom_auteur', 'contenu',
    ];

    protected $casts = [
        'est_marchand' => 'boolean',
    ];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function client()  { return $this->belongsTo(Client::class); }
}
