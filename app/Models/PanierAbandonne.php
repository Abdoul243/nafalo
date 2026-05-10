<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanierAbandonne extends Model
{
    use HasFactory;
    
    protected $table = 'paniers_abandonnes';

    protected $fillable = [
        'boutique_id',
        'client_id',
        'email',
        'contenu',
        'montant_total',
        'relance_envoyee',
        'date_relance'
    ];

    protected $casts = [
        'contenu' => 'array',
        'montant_total' => 'decimal:2',
        'relance_envoyee' => 'boolean',
        'date_relance' => 'datetime',
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
}
