<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleFormation extends Model
{
    protected $table = 'modules_formation';

    protected $fillable = ['produit_id', 'titre', 'ordre'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lecons()
    {
        return $this->hasMany(Lecon::class, 'module_id')->orderBy('ordre');
    }
}
