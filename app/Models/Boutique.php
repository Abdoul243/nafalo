<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;

    /** À la création d'une boutique, on livre les catégories standard. */
    protected static function booted(): void
    {
        static::created(function (Boutique $boutique) {
            Categorie::creerStandards($boutique->id);
        });
    }

    protected $fillable = [
        'nom',
        'utilisateur_id',
        'description',
        'logo',
        'logo_mime',
        'logo_taille',
        'email',
        'telephone',
        'reseaux_sociaux',
        'domaine_personnalise',
        'est_active'
    ];

    protected $casts = [
        'reseaux_sociaux' => 'array',
        'est_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function codesPromo()
    {
        return $this->hasMany(CodePromo::class);
    }

    public function pixels()
    {
        return $this->hasMany(PixelMarketing::class);
    }

    public function paniersAbandonnes()
    {
        return $this->hasMany(PanierAbandonne::class);
    }

    public function configuration()
    {
        return $this->hasOne(ConfigurationBoutique::class);
    }

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class);
    }

    // Relation singular — marchand propriétaire de la boutique
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // URL externe stockée directement dans le champ logo
        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return route('media.boutiques.logo', $this);
    }
}