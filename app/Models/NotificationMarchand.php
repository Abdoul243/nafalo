<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationMarchand extends Model
{
    protected $table = 'notifications_marchands';

    const TYPE_VENTE               = 'vente';
    const TYPE_AVIS                = 'avis';
    const TYPE_LEAD                = 'lead';
    const TYPE_COPUB_INVITATION    = 'copub_invitation';
    const TYPE_COPUB_REPONSE       = 'copub_reponse';

    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'message',
        'lien',
        'data',
        'lu_le',
    ];

    protected $casts = [
        'data'  => 'array',
        'lu_le' => 'datetime',
    ];

    /* ── Relations ──────────────────────────────────────────────────── */

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    /* ── Scopes ─────────────────────────────────────────────────────── */

    public function scopeNonLues($query)
    {
        return $query->whereNull('lu_le');
    }

    /* ── Helpers ─────────────────────────────────────────────────────── */

    public function estLue(): bool
    {
        return $this->lu_le !== null;
    }

    public function marquerLue(): void
    {
        if (!$this->lu_le) {
            $this->update(['lu_le' => now()]);
        }
    }

    public function icone(): string
    {
        return match ($this->type) {
            self::TYPE_VENTE             => 'fas fa-wallet',
            self::TYPE_AVIS              => 'fas fa-star',
            self::TYPE_LEAD              => 'fas fa-user-plus',
            self::TYPE_COPUB_INVITATION  => 'fas fa-handshake',
            self::TYPE_COPUB_REPONSE     => 'fas fa-reply',
            default                      => 'fas fa-bell',
        };
    }

    public function couleur(): string
    {
        return match ($this->type) {
            self::TYPE_VENTE             => '#22c55e',
            self::TYPE_AVIS              => '#f59e0b',
            self::TYPE_LEAD              => '#8b5cf6',
            self::TYPE_COPUB_INVITATION  => '#2563eb',
            self::TYPE_COPUB_REPONSE     => '#06b6d4',
            default                      => '#94a3b8',
        };
    }

    public function couleurBg(): string
    {
        return match ($this->type) {
            self::TYPE_VENTE             => '#f0fdf4',
            self::TYPE_AVIS              => '#fffbeb',
            self::TYPE_LEAD              => '#f5f3ff',
            self::TYPE_COPUB_INVITATION  => '#eff6ff',
            self::TYPE_COPUB_REPONSE     => '#ecfeff',
            default                      => '#f8fafc',
        };
    }
}
