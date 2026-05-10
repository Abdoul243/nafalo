<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PixelMarketing extends Model
{
    use HasFactory;
    
    protected $table = 'pixels_marketing';

    const EMPLACEMENT_HEADER = 'header';
    const EMPLACEMENT_FOOTER = 'footer';
    const EMPLACEMENT_CHECKOUT = 'checkout';
    const EMPLACEMENT_CONFIRMATION = 'confirmation';

    protected $fillable = [
        'boutique_id',
        'nom',
        'code_pixel',
        'emplacement',
        'est_actif'
    ];

    protected $casts = [
        'est_actif' => 'boolean'
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }
}
