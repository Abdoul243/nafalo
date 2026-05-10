<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'nom',
        'slug',
        'description',
        'prix',
        'image',
        'image_mime',
        'image_taille',
        'fichier',
        'est_publie',
        'nb_ventes'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'est_publie' => 'boolean',
        'nb_ventes' => 'integer'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function codesPromo()
    {
        return $this->belongsToMany(CodePromo::class, 'code_promo_produits');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function getUrlAttribute()
    {
        return route('boutique.produit.show', [
            'slug' => $this->slug,
            'boutique' => $this->boutique->domaine_personnalise ?? $this->boutique->id
        ]);
    }

    public function getCheckoutUrlAttribute()
    {
        return route('boutique.checkout.produit', ['id' => $this->id]);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return route('media.produits.image', $this);
    }
}
