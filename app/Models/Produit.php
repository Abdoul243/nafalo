<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    const TYPE_PAYANT  = 'payant';
    const TYPE_GRATUIT = 'gratuit';

    // Champs optionnels que le marchand peut activer sur son formulaire de capture
    const CHAMPS_LEAD_DISPONIBLES = [
        'telephone'  => 'Téléphone',
        'ville'      => 'Ville',
        'profession' => 'Profession / Métier',
        'pays'       => 'Pays',
    ];

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'nom',
        'slug',
        'description',
        'prix',
        'image',
        'image_mime',
        'image_taille',
        'fichier',
        'format',
        'acces_type',
        'abonnement_intervalle',
        'est_publie',
        'nb_ventes',
        // Lead magnet
        'type',
        'lead_champs_requis',
        'lead_limite_dl',
        'lead_compteur',
    ];

    protected $casts = [
        'prix'              => 'decimal:2',
        'est_publie'        => 'boolean',
        'nb_ventes'         => 'integer',
        'lead_champs_requis'=> 'array',
        'lead_limite_dl'    => 'integer',
        'lead_compteur'     => 'integer',
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function codesPromo()
    {
        return $this->belongsToMany(CodePromo::class, 'code_promo_produits');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    /* ── Co-publication ────────────────────────────────────────────── */

    /** Co-publication initiée par le propriétaire de ce produit */
    public function copublications()
    {
        return $this->hasMany(Copublication::class);
    }

    /** Co-publication acceptée (partenariat actif) */
    public function copublicationActive()
    {
        return $this->hasOne(Copublication::class)
                    ->where('statut', Copublication::STATUT_ACCEPTE);
    }

    /* ── Upsells ───────────────────────────────────────────────────── */

    /** Upsells proposés après l'achat de CE produit */
    public function upsells()
    {
        return $this->hasMany(Upsell::class, 'produit_id')
                    ->with('produitUpsell')
                    ->orderBy('ordre');
    }

    /** Produits dans lesquels CE produit est proposé en upsell */
    public function upsellsInverse()
    {
        return $this->hasMany(Upsell::class, 'produit_upsell_id');
    }

    /* ── Formation (espace membre) ─────────────────────────────────── */

    /** Modules de la formation (avec leurs leçons) */
    public function modules()
    {
        return $this->hasMany(ModuleFormation::class, 'produit_id')->orderBy('ordre');
    }

    /** Ce produit est-il une formation (espace membre) ? */
    public function estFormation(): bool
    {
        return $this->format === 'formation';
    }

    /** Nombre total de leçons de la formation */
    public function nbLecons(): int
    {
        return Lecon::whereIn('module_id', $this->modules()->pluck('id'))->count();
    }

    /* ── Abonnement ────────────────────────────────────────────────── */

    /** Ce produit est-il vendu en abonnement (accès récurrent) ? */
    public function estAbonnement(): bool
    {
        return $this->acces_type === 'abonnement';
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'produit_id');
    }

    /* ── Helpers Lead Magnet ───────────────────────────────────────── */

    public function estGratuit(): bool
    {
        return $this->type === self::TYPE_GRATUIT;
    }

    public function estPayant(): bool
    {
        return $this->type === self::TYPE_PAYANT;
    }

    /** Vérifie si la limite de téléchargements gratuits est atteinte */
    public function limiteAtteinte(): bool
    {
        if (!$this->lead_limite_dl) {
            return false; // illimité
        }
        return $this->lead_compteur >= $this->lead_limite_dl;
    }

    /** Nombre de places restantes (null = illimité) */
    public function placesRestantes(): ?int
    {
        if (!$this->lead_limite_dl) {
            return null;
        }
        return max(0, $this->lead_limite_dl - $this->lead_compteur);
    }

    /** Champs optionnels activés par le marchand */
    public function champsLeadActifs(): array
    {
        return $this->lead_champs_requis ?? [];
    }

    public function getUrlAttribute()
    {
        return route('boutique.produit.show', [
            'slug' => $this->slug,
            'boutique' => $this->boutique->domaine_personnalise ?? $this->boutique->id
        ]);
    }

    public function getCheckoutUrlAttribute()
    {
        return route('boutique.checkout.produit', ['id' => $this->id]);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return route('media.produits.image', $this);
    }
}
