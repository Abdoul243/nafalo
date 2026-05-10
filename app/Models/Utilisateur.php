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
        'role',
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

    public function estSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function boutiques()
    {
        return $this->hasMany(Boutique::class);
    }

    /** Co-publications où ce marchand est propriétaire */
    public function copublicationsProprietaire()
    {
        return $this->hasMany(Copublication::class, 'proprietaire_id');
    }

    /** Co-publications où ce marchand est co-publicateur invité */
    public function copublicationsCopublicateur()
    {
        return $this->hasMany(Copublication::class, 'copublicateur_id');
    }

    /** Invitations en attente reçues */
    public function invitationsCopublicationEnAttente()
    {
        return $this->hasMany(Copublication::class, 'copublicateur_id')
                    ->where('statut', Copublication::STATUT_EN_ATTENTE);
    }

    /** Notifications in-app */
    public function notificationsMarchand()
    {
        return $this->hasMany(NotificationMarchand::class);
    }

    public function notificationsNonLues()
    {
        return $this->hasMany(NotificationMarchand::class)->whereNull('lu_le');
    }

    /** KYC */
    public function kyc()
    {
        return $this->hasOne(Kyc::class);
    }

    public function estVerifieKyc(): bool
    {
        return $this->kyc?->statut === Kyc::STATUT_APPROUVE;
    }
}