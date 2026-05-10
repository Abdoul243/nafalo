<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';

    protected $table = 'utilisateurs';
    protected $authPasswordName = 'mot_de_passe';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function estAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
