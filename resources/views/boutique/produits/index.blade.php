@extends('layouts.boutique')

@section('title', 'Produits — ' . $boutique->nom)

@push('styles')
<style>
/* ── Override dark theme pour cette page ── */
body { background: #f8f8f8 !important; }
.boutique-header { background: #fff !important; border-bottom: 1px solid #e5e7eb !important; }
.boutique-header * { color: #111 !important; }
.boutique-header .nav-link { color: #374151 !important; }
.boutique-header .nav-link:hover { color: #111 !important; }

/* ── Hero description ── */
.store-hero {
    background: #fff;
    padding: 2.5rem 0 2rem;
    border-bottom: 1px solid #e9eaeb;
}
.store-hero-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.store-hero h1 {
    font-family: 'Inter', sans-serif;
    font-size: 1.9rem;
    font-weight: 700;
    color: #111;
    line-height: 1.3;
    max-width: 800px;
    margin: 0;
}

/* ── Filters ── */
.filters-wrap {
    background: #fff;
    border-bottom: 1px solid #e9eaeb;
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 100;
}
.filters-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.fi-search {
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.55rem 0.9rem;
    background: #fff;
}
.fi-search:focus-within { border-color: #6b7280; }
.fi-search i { color: #9ca3af; font-size: 0.85rem; }
.fi-search input {
    border: none; outline: none; flex: 1;
    font-size: 0.875rem; color: #111;
    background: transparent; font-family: inherit;
}
.fi-search input::placeholder { color: #9ca3af; }
.fi-select {
    padding: 0.55rem 2rem 0.55rem 0.8rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #374151;
    background: #fff;
    cursor: pointer;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%236b7280' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    font-family: inherit;
    outline: none;
}
.fi-select:focus { border-color: #6b7280; }
.fi-btn {
    padding: 0.55rem 1.25rem;
    background: #111;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}
.fi-btn:hover { background: #374151; }

/* ── Category pills ── */
.cat-row {
    max-width: 1280px;
    margin: 0 auto;
    padding: 1.25rem 2rem;
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.cat-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    border: 1.5px solid #e5e7eb;
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
    background: #fff;
    text-decoration: none;
    transition: all 0.15s;
}
.cat-pill:hover { border-color: #111; color: #111; }
.cat-pill.active { background: #111; color: #fff; border-color: #111; }

/* ── Product grid ── */
.prod-grid-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem 4rem;
}
.prod-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}
@media(max-width:1200px) { .prod-grid { grid-template-columns: repeat(3,1fr); } }
@media(max-width:768px)  { .prod-grid { grid-template-columns: repeat(2,1fr); gap: 0.875rem; } }
@media(max-width:420px)  { .prod-grid { grid-template-columns: repeat(2,1fr); gap: 0.6rem; } }

/* ── Product card ── */
.pc {
    background: #fff;
    border: 1px solid #e9eaeb;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}
.pc:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    transform: translateY(-3px);
    text-decoration: none;
}
.pc-img {
    position: relative;
    overflow: hidden;
    background: #f3f4f6;
}
.pc-img img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: transform 0.35s;
}
.pc:hover .pc-img img { transform: scale(1.04); }
.pc-img-ph {
    width: 100%;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 0.5rem;
    color: #9ca3af;
}
.pc-img-ph i { font-size: 2.2rem; }

/* Badge % OFF — coin haut droit, style Chariow */
.pc-badge-off {
    position: absolute;
    top: 0;
    right: 0;
    background: #111;
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.3rem 0.6rem;
    border-bottom-left-radius: 8px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

.pc-body {
    padding: 0.875rem 0.9rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.pc-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #111;
    line-height: 1.4;
    margin-bottom: 0.6rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.pc-prices {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.pc-price-old {
    font-size: 0.8rem;
    color: #9ca3af;
    text-decoration: line-through;
}
.pc-price-new {
    font-size: 1rem;
    font-weight: 800;
    color: #e53e3e;
}
.pc-price-normal {
    font-size: 1rem;
    font-weight: 800;
    color: #111;
}

/* ── Empty ── */
.empty-wrap {
    text-align: center;
    padding: 5rem 2rem;
    background: #fff;
    border: 1px solid #e9eaeb;
    border-radius: 12px;
}
.empty-wrap h3 { font-size: 1.1rem; color: #111; margin-bottom: 0.5rem; }
.empty-wrap p { color: #6b7280; font-size: 0.875rem; }

@media(max-width:768px) {
    .store-hero-inner, .filters-inner, .cat-row, .prod-grid-wrap { padding-left: 1rem; padding-right: 1rem; }
    .store-hero h1 { font-size: 1.4rem; }
    .fi-btn span { display: none; }
    .pc-img img, .pc-img-ph { height: 170px; }
}
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<div class="store-hero">
    <div class="store-hero-inner">
        <h1>{{ $boutique->description ?: 'Découvrez tous nos produits numériques — téléchargement immédiat.' }}</h1>
    </div>
</div>

{{-- ── FILTERS ── --}}
<form action="{{ route('boutique.produit.index') }}" method="GET" id="filter-form">
<div class="filters-wrap">
    <div class="filters-inner">
        <div class="fi-search">
            <i class="fas fa-search"></i>
            <input type="text" name="recherche" placeholder="Rechercher" value="{{ request('recherche') }}">
        </div>
        @if($categories->count() > 0)
        <select name="categorie" class="fi-select" onchange="document.getElementById('filter-form').submit()">
            <option value="">Catégorie</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select>
        @endif
        <select name="tri" class="fi-select" onchange="document.getElementById('filter-form').submit()">
            <option value="">Trier par</option>
            <option value="nouveaute" {{ request('tri')=='nouveaute' ? 'selected':'' }}>Nouveautés</option>
            <option value="prix_asc"  {{ request('tri')=='prix_asc'  ? 'selected':'' }}>Prix croissant</option>
            <option value="prix_desc" {{ request('tri')=='prix_desc' ? 'selected':'' }}>Prix décroissant</option>
        </select>
        <button type="submit" class="fi-btn">
            <i class="fas fa-search"></i> <span>Rechercher</span>
        </button>
    </div>
</div>
</form>

{{-- ── CATEGORY PILLS ── --}}
@if($categories->count() > 0)
<div class="cat-row">
    <a href="{{ route('boutique.produit.index') }}"
       class="cat-pill {{ !request('categorie') ? 'active' : '' }}">
        Tout
    </a>
    @foreach($categories as $cat)
    <a href="{{ route('boutique.produit.index', ['categorie' => $cat->id]) }}"
       class="cat-pill {{ request('categorie') == $cat->id ? 'active' : '' }}">
        {{ $cat->nom }}
    </a>
    @endforeach
</div>
@else
<div style="height:1.25rem;"></div>
@endif

{{-- ── PRODUCT GRID ── --}}
<div class="prod-grid-wrap">
    @if($produits->count() > 0)
    <div class="prod-grid">
        @foreach($produits as $produit)
        @php
            $hasPromo = isset($produit->prix_promo) && $produit->prix_promo > 0 && $produit->prix_promo < $produit->prix;
            $discount = $hasPromo ? round((($produit->prix - $produit->prix_promo) / $produit->prix) * 100) : 0;
        @endphp
        <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="pc">
            <div class="pc-img">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy">
                @else
                    <div class="pc-img-ph">
                        <i class="fas fa-file-download"></i>
                    </div>
                @endif
                @if($discount > 0)
                    <div class="pc-badge-off">{{ $discount }}% OFF</div>
                @endif
            </div>
            <div class="pc-body">
                <div class="pc-name">{{ $produit->nom }}</div>
                <div class="pc-prices">
                    @if($hasPromo)
                        <span class="pc-price-old" data-xof="{{ (int)$produit->prix }}">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                        <span class="pc-price-new" data-xof="{{ (int)$produit->prix_promo }}">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</span>
                    @else
                        <span class="pc-price-normal" data-xof="{{ (int)$produit->prix }}">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div style="display:flex;justify-content:center;">
        {{ $produits->withQueryString()->links() }}
    </div>

    @else
    <div class="empty-wrap">
        <h3>Aucun produit trouvé</h3>
        <p>
            @if(request('recherche') || request('categorie'))
                <a href="{{ route('boutique.produit.index') }}" style="color:#111;font-weight:600;">
                    ← Voir tous les produits
                </a>
            @else
                Revenez bientôt, de nouveaux produits arrivent !
            @endif
        </p>
    </div>
    @endif
</div>

@endsection
