<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'client_id',
        'achat_id',
        'note',
        'commentaire',
        'est_visible'
    ];

    protected $casts = [
        'note' => 'integer',
        'est_visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }
}