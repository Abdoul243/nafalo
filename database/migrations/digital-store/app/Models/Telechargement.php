<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telechargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'achat_id',
        'client_id',
        'ip_adresse'
    ];

    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}