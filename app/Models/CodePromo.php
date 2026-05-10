<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodePromo extends Model
{
    use HasFactory;
    
    
    protected $table = 'code_promos';

    const TYPE_FIXE = 'fixe';
    const TYPE_POURCENTAGE = 'pourcentage';

    protected $fillable = [
        'boutique_id',
        'code',
        'type_reduction',
        'valeur_reduction',
        'date_debut',
        'date_fin',
        'utilisation_max',
        'utilisation_actuelle',
        'est_actif'
    ];

    protected $casts = [
        'valeur_reduction' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'utilisation_max' => 'integer',
        'utilisation_actuelle' => 'integer',
        'est_actif' => 'boolean'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'code_promo_produits');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function estValide()
    {
        if (!$this->est_actif) {
            return false;
        }

        if ($this->date_debut && $this->date_debut->isFuture()) {
            return false;
        }

        if ($this->date_fin && $this->date_fin->isPast()) {
            return false;
        }

        if ($this->utilisation_max && $this->utilisation_actuelle >= $this->utilisation_max) {
            return false;
        }

        return true;
    }

    public function calculerReduction($montant)
    {
        if ($this->type_reduction === self::TYPE_FIXE) {
            return min($this->valeur_reduction, $montant);
        }
        
        return ($montant * $this->valeur_reduction) / 100;
    }
}