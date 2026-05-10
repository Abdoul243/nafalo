@extends('layouts.boutique')

@section('title', $boutique->nom)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════════ */
.hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
    padding: 4rem 0 3rem;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 70% 50%, rgba(37,99,235,0.18) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(124,58,237,0.12) 0%, transparent 50%);
}
.hero-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 1;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85);
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    margin-bottom: 1.25rem;
    backdrop-filter: blur(8px);
}
.hero-badge i { color: #60a5fa; }
.hero h1 {
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900;
    color: white;
    line-height: 1.1;
    margin: 0 0 1rem;
    letter-spacing: -0.02em;
}
.hero h1 span {
    background: linear-gradient(90deg, #60a5fa, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero p {
    color: rgba(255,255,255,0.65);
    font-size: 1.05rem;
    margin: 0 0 2rem;
    max-width: 540px;
    line-height: 1.7;
}
.hero-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}
.hero-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
}
.hero-stat i { color: #60a5fa; }
.hero-stat strong { color: white; }

/* ═══════════════════════════════════════════════════
   SEARCH BAR
═══════════════════════════════════════════════════ */
.search-section {
    max-width: 1280px;
    margin: -1.5rem auto 0;
    padding: 0 2rem;
    position: relative;
    z-index: 10;
}
.search-bar {
    background: white;
    border-radius: 18px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    padding: 1rem 1.25rem;
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}
.search-input-wrap {
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.65rem 1rem;
    transition: border-color 0.15s;
}
.search-input-wrap:focus-within { border-color: #2563eb; background: white; }
.search-input-wrap i { color: #94a3b8; font-size: 0.9rem; }
.search-input-wrap input {
    border: none;
    outline: none;
    flex: 1;
    font-size: 0.9rem;
    color: #111;
    background: transparent;
    font-family: inherit;
}
.filter-sel {
    padding: 0.65rem 2.2rem 0.65rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    background: #f8fafc;
    cursor: pointer;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%23666' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    font-family: inherit;
    outline: none;
}
.filter-sel:focus { border-color: #2563eb; background: white; }
.btn-search {
    padding: 0.7rem 1.5rem;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-search:hover { background: #1d4ed8; transform: translateY(-1px); }

/* ═══════════════════════════════════════════════════
   CATÉGORIE PILLS
═══════════════════════════════════════════════════ */
.section-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.cat-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 2.5rem 0 1.5rem;
}
.cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.45rem 1rem;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    background: white;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}
.cat-pill:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
.cat-pill.active { background: #2563eb; color: white; border-color: #2563eb; }
.cat-pill .count {
    background: rgba(0,0,0,0.1);
    border-radius: 10px;
    padding: 1px 6px;
    font-size: 0.72rem;
}
.cat-pill.active .count { background: rgba(255,255,255,0.25); }

/* ═══════════════════════════════════════════════════
   SECTION TITRE
═══════════════════════════════════════════════════ */
.section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.section-head h2 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.section-head p {
    font-size: 0.85rem;
    color: #94a3b8;
    margin: 2px 0 0;
}
.sort-sel {
    padding: 0.45rem 2rem 0.45rem 0.85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 500;
    color: #374151;
    background: white;
    cursor: pointer;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%23666' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    font-family: inherit;
    outline: none;
}

/* ═══════════════════════════════════════════════════
   PRODUCT GRID
═══════════════════════════════════════════════════ */
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}
@media (max-width: 1200px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; } }
@media (max-width: 480px)  { .product-grid { grid-template-columns: 1fr; } }

/* ═══════════════════════════════════════════════════
   PRODUCT CARD
═══════════════════════════════════════════════════ */
.p-card {
    background: white;
    border-radius: 20px;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s;
    position: relative;
}
.p-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    transform: translateY(-5px);
    border-color: #e2e8f0;
}
.p-img {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}
.p-img img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s;
}
.p-card:hover .p-img img { transform: scale(1.06); }
.p-img-ph {
    width: 100%;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 0.5rem;
    color: #cbd5e1;
}
.p-img-ph i { font-size: 2.5rem; }
.p-img-ph span { font-size: 0.75rem; font-weight: 600; }

/* Badges sur l'image */
.p-badge-wrap {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.p-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 0.28rem 0.65rem;
    border-radius: 20px;
    letter-spacing: 0.03em;
}
.p-badge-promo  { background: #ef4444; color: white; }
.p-badge-new    { background: #0f172a; color: white; }
.p-badge-hot    { background: #f97316; color: white; }

/* Overlay bouton au survol */
.p-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, transparent 100%);
    padding: 2rem 1rem 1rem;
    opacity: 0;
    transition: opacity 0.3s;
    display: flex;
    justify-content: center;
}
.p-card:hover .p-overlay { opacity: 1; }
.btn-quick {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.6rem 1.4rem;
    background: white;
    color: #0f172a;
    border: none;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.82rem;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.btn-quick:hover { background: #2563eb; color: white; }

/* Corps de la carte */
.p-body { padding: 1.1rem 1.25rem 1.3rem; flex: 1; display: flex; flex-direction: column; }
.p-cat {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #2563eb;
    margin-bottom: 0.4rem;
}
.p-title {
    font-weight: 800;
    font-size: 0.95rem;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 0.6rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.p-stars {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-bottom: 0.75rem;
}
.p-stars i { color: #f59e0b; font-size: 0.72rem; }
.p-stars i.empty { color: #e2e8f0; }
.p-stars span { font-size: 0.75rem; color: #94a3b8; margin-left: 4px; }

.p-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}
.p-price-wrap {}
.p-price-old { font-size: 0.78rem; color: #94a3b8; text-decoration: line-through; }
.p-price { font-size: 1.1rem; font-weight: 900; color: #0f172a; }
.p-price.promo { color: #ef4444; }

.btn-buy {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.55rem 1rem;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.8rem;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-buy:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.35); }

/* ═══════════════════════════════════════════════════
   TRUST BADGES
═══════════════════════════════════════════════════ */
.trust-section {
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    padding: 2rem 0;
    margin-bottom: 3rem;
}
.trust-grid {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    text-align: center;
}
@media (max-width: 768px) { .trust-grid { grid-template-columns: repeat(2, 1fr); } }
.trust-item {}
.trust-item i { font-size: 1.4rem; color: #2563eb; margin-bottom: 0.5rem; display: block; }
.trust-item strong { display: block; font-size: 0.875rem; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.trust-item span { font-size: 0.78rem; color: #94a3b8; }

/* ═══════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    color: #94a3b8;
}
.empty-state i { font-size: 3.5rem; display: block; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1.2rem; font-weight: 800; color: #334155; margin-bottom: 0.5rem; }

/* ═══════════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════════ */
.pagination-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 4rem;
}

/* ═══════════════════════════════════════════════════
   RESPONSIVE GLOBAL
═══════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .hero { padding: 2.5rem 0 2rem; }
    .hero-inner { padding: 0 1rem; }
    .hero p { font-size: 0.95rem; }
    .hero-stats { gap: 1rem; }
    .store-wrap { padding: 0 1rem !important; }
    .section-inner { padding: 2.5rem 1rem; }
    .product-grid { gap: 0.75rem; }
    .trust-grid { grid-template-columns: repeat(2,1fr); gap: 1rem; }
    .pagination-wrap { margin-bottom: 2.5rem; }
}
@media (max-width: 480px) {
    .hero-stats { flex-direction: column; gap: 0.6rem; }
    .product-grid { grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .trust-grid { grid-template-columns: 1fr 1fr; }
    .section-inner { padding: 2rem 1rem; }
}
</style>
@endpush

@section('content')

{{-- ── HERO ─────────────────────────────────────────────── --}}
<div class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <i class="fas fa-store"></i>
            Boutique digitale officielle
        </div>

        <h1>
            @if($boutique->description)
                {{ Str::limit($boutique->description, 60) }}
            @else
                Bienvenue sur <span>{{ $boutique->nom }}</span>
            @endif
        </h1>

        <p>Découvrez notre sélection de produits digitaux — téléchargement immédiat après paiement sécurisé.</p>

        <div class="hero-stats">
            <div class="hero-stat">
                <i class="fas fa-box"></i>
                <span><strong>{{ $produits->total() }}</strong> produit{{ $produits->total() > 1 ? 's' : '' }} disponibles</span>
            </div>
            <div class="hero-stat">
                <i class="fas fa-bolt"></i>
                <span><strong>Accès immédiat</strong> après paiement</span>
            </div>
            <div class="hero-stat">
                <i class="fas fa-shield-alt"></i>
                <span><strong>Paiement sécurisé</strong></span>
            </div>
        </div>
    </div>
</div>

{{-- ── BARRE DE RECHERCHE ───────────────────────────────── --}}
<form action="{{ route('boutique.accueil') }}" method="GET" id="search-form">
<div class="search-section">
    <div class="search-bar">
        <div class="search-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="recherche" placeholder="Rechercher un produit..."
                   value="{{ request('recherche') }}">
        </div>
        @if($categories->count() > 0)
        <select name="categorie" class="filter-sel" onchange="document.getElementById('search-form').submit()">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select>
        @endif
        <select name="tri" class="filter-sel" onchange="document.getElementById('search-form').submit()">
            <option value="">Trier par</option>
            <option value="nouveaute" {{ request('tri') == 'nouveaute' ? 'selected' : '' }}>Nouveautés</option>
            <option value="prix_asc"  {{ request('tri') == 'prix_asc'  ? 'selected' : '' }}>Prix croissant</option>
            <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
        </select>
        <button type="submit" class="btn-search">
            <i class="fas fa-search"></i> Rechercher
        </button>
    </div>
</div>
</form>

{{-- ── PILLS CATÉGORIES ─────────────────────────────────── --}}
@if($categories->count() > 0)
<div class="section-wrap">
    <div class="cat-pills">
        <a href="{{ route('boutique.accueil') }}"
           class="cat-pill {{ !request('categorie') ? 'active' : '' }}">
            <i class="fas fa-th"></i> Tout
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('boutique.accueil', ['categorie' => $cat->id]) }}"
           class="cat-pill {{ request('categorie') == $cat->id ? 'active' : '' }}">
            {{ $cat->nom }}
            <span class="count">{{ $cat->produits_count }}</span>
        </a>
        @endforeach
    </div>
</div>
@else
<div style="height:2.5rem;"></div>
@endif

{{-- ── GRILLE PRODUITS ──────────────────────────────────── --}}
<div class="section-wrap">
    @if($produits->count() > 0)
    <div class="section-head">
        <div>
            <h2>
                @if(request('recherche'))
                    Résultats pour "{{ request('recherche') }}"
                @elseif(request('categorie') && $categories->firstWhere('id', request('categorie')))
                    {{ $categories->firstWhere('id', request('categorie'))->nom }}
                @else
                    Tous les produits
                @endif
            </h2>
            <p>{{ $produits->total() }} produit{{ $produits->total() > 1 ? 's' : '' }} trouvé{{ $produits->total() > 1 ? 's' : '' }}</p>
        </div>
    </div>

    <div class="product-grid">
        @foreach($produits as $produit)
        @php
            $hasPromo  = isset($produit->prix_promo) && $produit->prix_promo > 0 && $produit->prix_promo < $produit->prix;
            $discount  = $hasPromo ? round((($produit->prix - $produit->prix_promo) / $produit->prix) * 100) : 0;
            $isNew     = $produit->created_at->diffInDays(now()) <= 7;
            $isHot     = ($produit->achats()->count() ?? 0) >= 5;
            $avisVis   = $produit->avis->where('est_visible', true);
            $totalAvis = $avisVis->count();
            $moyenne   = $avisVis->avg('note') ?? 0;
        @endphp
        <div class="p-card">
            {{-- Image --}}
            <div class="p-img">
                @if($produit->image)
                    <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}">
                @else
                    <div class="p-img-ph">
                        <i class="fas fa-file-download"></i>
                        <span>Produit digital</span>
                    </div>
                @endif

                {{-- Badges --}}
                <div class="p-badge-wrap">
                    @if($discount > 0)
                        <span class="p-badge p-badge-promo"><i class="fas fa-tag"></i> -{{ $discount }}%</span>
                    @endif
                    @if($isNew && !$discount)
                        <span class="p-badge p-badge-new"><i class="fas fa-sparkles"></i> Nouveau</span>
                    @endif
                    @if($isHot && !$discount && !$isNew)
                        <span class="p-badge p-badge-hot"><i class="fas fa-fire"></i> Populaire</span>
                    @endif
                </div>

                {{-- Overlay bouton voir --}}
                <div class="p-overlay">
                    <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="btn-quick">
                        <i class="fas fa-eye"></i> Voir le produit
                    </a>
                </div>
            </div>

            {{-- Corps --}}
            <div class="p-body">
                @if($produit->categorie)
                <div class="p-cat">{{ $produit->categorie->nom }}</div>
                @endif

                <div class="p-title">{{ $produit->nom }}</div>

                {{-- Stars --}}
                @if($totalAvis > 0)
                <div class="p-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($moyenne) ? '' : 'empty' }}"></i>
                    @endfor
                    <span>({{ $totalAvis }})</span>
                </div>
                @else
                <div class="p-stars">
                    @for($i = 1; $i <= 5; $i++)<i class="fas fa-star empty"></i>@endfor
                    <span>Soyez le premier</span>
                </div>
                @endif

                {{-- Prix + bouton --}}
                <div class="p-footer">
                    <div class="p-price-wrap">
                        @if($hasPromo)
                            <div class="p-price-old">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                            <div class="p-price promo">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</div>
                        @else
                            <div class="p-price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                        @endif
                    </div>

                    <form action="{{ route('boutique.panier.ajouter', $produit) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="btn-buy">
                            <i class="fas fa-cart-plus"></i> Acheter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($produits->hasPages())
    <div class="pagination-wrap">
        {{ $produits->withQueryString()->links() }}
    </div>
    @endif

    @else
    {{-- Empty state --}}
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>
            @if(request('recherche'))
                Aucun résultat pour "{{ request('recherche') }}"
            @else
                Aucun produit disponible pour le moment
            @endif
        </h3>
        <p style="font-size:0.9rem;">
            @if(request('recherche') || request('categorie'))
                <a href="{{ route('boutique.accueil') }}" style="color:#2563eb;font-weight:600;">← Voir tous les produits</a>
            @else
                Revenez bientôt, de nouveaux produits arrivent !
            @endif
        </p>
    </div>
    @endif
</div>

{{-- ── TRUST BADGES ─────────────────────────────────────── --}}
<div class="trust-section">
    <div class="trust-grid">
        <div class="trust-item">
            <i class="fas fa-bolt"></i>
            <strong>Livraison instantanée</strong>
            <span>Téléchargement immédiat après paiement</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-lock"></i>
            <strong>Paiement sécurisé</strong>
            <span>Vos données sont protégées</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-infinity"></i>
            <strong>Accès à vie</strong>
            <span>Téléchargez quand vous voulez</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-headset"></i>
            <strong>Support disponible</strong>
            <span>On vous répond rapidement</span>
        </div>
    </div>
</div>

@endsection
