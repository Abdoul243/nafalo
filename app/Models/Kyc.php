<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kyc extends Model
{
    protected $table = 'kycs';

    const STATUT_NON_SOUMIS = 'non_soumis';
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_APPROUVE   = 'approuve';
    const STATUT_REJETE     = 'rejete';

    const TYPES_DOCUMENT = [
        'cni'      => "Carte Nationale d'Identité",
        'passeport'=> 'Passeport',
        'permis'   => 'Permis de conduire',
    ];

    protected $fillable = [
        'utilisateur_id',
        'statut',
        'type_document',
        'document_recto',
        'document_verso',
        'note_admin',
        'soumis_le',
        'traite_le',
        'traite_par',
    ];

    protected $casts = [
        'soumis_le' => 'datetime',
        'traite_le' => 'datetime',
    ];

    /* ── Relations ──────────────────────────────────────────────────── */

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'traite_par');
    }

    /* ── Helpers ─────────────────────────────────────────────────────── */

    public function estApprouve(): bool { return $this->statut === self::STATUT_APPROUVE; }
    public function estEnAttente(): bool { return $this->statut === self::STATUT_EN_ATTENTE; }
    public function estRejete(): bool { return $this->statut === self::STATUT_REJETE; }

    public function badgeHtml(): string
    {
        return match ($this->statut) {
            self::STATUT_APPROUVE   => '<span class="badge" style="background:#dcfce7;color:#166534;">✅ Vérifié</span>',
            self::STATUT_EN_ATTENTE => '<span class="badge" style="background:#fef9c3;color:#713f12;">⏳ En attente</span>',
            self::STATUT_REJETE     => '<span class="badge" style="background:#fee2e2;color:#991b1b;">❌ Rejeté</span>',
            default                 => '<span class="badge" style="background:#f1f5f9;color:#64748b;">Non soumis</span>',
        };
    }
}
