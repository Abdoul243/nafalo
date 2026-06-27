<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressionLecon extends Model
{
    protected $table = 'progressions_lecon';

    protected $fillable = ['client_id', 'lecon_id', 'terminee', 'terminee_at'];

    protected $casts = [
        'terminee'    => 'boolean',
        'terminee_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lecon()
    {
        return $this->belongsTo(Lecon::class, 'lecon_id');
    }
}
