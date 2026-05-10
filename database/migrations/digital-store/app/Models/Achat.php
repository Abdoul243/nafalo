<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'client_id',
        'produit_id',
        'prix_unitaire',
        'quantite',
        'code_promo_id',
        'reduction_appliquee'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'quantite' => 'integer',
        'reduction_appliquee' => 'decimal:2'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function codePromo()
    {
        return $this->belongsTo(CodePromo::class);
    }

    public function telechargements()
    {
        return $this->hasMany(Telechargement::class);
    }

    public function avis()
    {
        return $this->hasOne(Avis::class);
    }

    public function getTotalAttribute()
    {
        return ($this->prix_unitaire * $this->quantite) - $this->reduction_appliquee;
    }
}