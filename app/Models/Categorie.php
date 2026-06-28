<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'nom',
        'slug',
        'description'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    /** Catégories standard livrées à chaque boutique (façon Chariow). */
    public const STANDARDS = [
        'Arts Créatifs',
        'Technologie',
        'Business & Finance',
        'Marketing & Vente',
        'Développement Personnel',
        'Éducation & Apprentissage',
        'Santé & Bien-être',
        'Divertissement',
    ];

    /** Crée les catégories standard pour une boutique (idempotent). */
    public static function creerStandards(int $boutiqueId): void
    {
        foreach (self::STANDARDS as $nom) {
            self::firstOrCreate(
                ['boutique_id' => $boutiqueId, 'slug' => \Illuminate\Support\Str::slug($nom)],
                ['nom' => $nom]
            );
        }
    }
}