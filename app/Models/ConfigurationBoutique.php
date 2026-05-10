<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationBoutique extends Model
{
    use HasFactory;
    
    protected $table = 'configurations_boutique';

    protected $fillable = [
        'boutique_id',
        'email_expediteur',
        'email_template_achat',
        'email_template_relance',
        'relance_delai_jours',
        'cle_api_paiement',
        'secret_api_paiement',
        'passerelle_paiement',
        'devise',
        'theme',
        'couleur',
        'langue',
    ];

    protected $casts = [
        'relance_delai_jours' => 'integer'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }
}
