<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'email',
        'nom',
        'telephone',
        'code_acces',
        'code_expire_at'
    ];

    protected $casts = [
        'code_expire_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function telechargements()
    {
        return $this->hasMany(Telechargement::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function paniersAbandonnes()
    {
        return $this->hasMany(PanierAbandonne::class);
    }

    public function aUnCodeValide()
    {
        return $this->code_acces && $this->code_expire_at && $this->code_expire_at->isFuture();
    }

    public function genererCodeAcces()
    {
        $this->code_acces = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->code_expire_at = now()->addMinutes(config('boutique.code_acces_expiration', 15));
        $this->save();
        
        return $this->code_acces;
    }
}