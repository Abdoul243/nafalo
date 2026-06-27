@extends('layouts.boutique')

@section('title', $boutique->nom)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   ACCUEIL BOUTIQUE — Style Chariow (clair)
═══════════════════════════════════════════════════════════ */
body { background: #fff; }

/* ── HERO : grand titre = description ── */
.cw-hero {
    max-width: 1240px;
    margin: 0 auto;
    padding: 3rem 2rem 1.5rem;
}
.cw-hero h1 {
    font-family: 'Inter', sans-serif;
    font-size: clamp(1.7rem, 3.8vw, 2.8rem);
    font-weight: 800;
    color: #111827;
    line-height: 1.25;
    letter-spacing: -0.02em;
    margin: 0;
    max-width: 1000px;
}

/* ── FILTRES ── */
.cw-filters {
    max-width: 1240px;
    margin: 0 auto;
    padding: 1.25rem 2rem 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.cw-search {
    flex: 1;
    min-width: 220px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.7rem 1rem;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.cw-search:focus-within {
    border-color: #c4b5fd;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
}
.cw-search i { color: #9ca3af; font-size: 0.9rem; }
.cw-search input {
    border: none; outline: none; flex: 1;
    font-size: 0.9rem; color: #111827;
    background: transparent; font-family: inherit;
}
.cw-search input::placeholder { color: #9ca3af; }

.cw-select {
    padding: 0.7rem 2.2rem 0.7rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    background: #fff;
    cursor: pointer;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    font-family: inherit;
    outline: none;
    min-width: 150px;
    transition: border-color 0.15s;
}
.cw-select:focus { border-color: #c4b5fd; }

/* ── GRILLE PRODUITS ── */
.cw-grid-wrap {
    max-width: 1240px;
    margin: 0 auto;
    padding: 1.5rem 2rem 4rem;
}
.cw-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
@media(max-width:1100px) { .cw-grid { grid-template-columns: repeat(3,1fr); } }
@media(max-width:760px)  { .cw-grid { grid-template-columns: repeat(2,1fr); gap: 1rem; } }
@media(max-width:430px)  { .cw-grid { grid-template-columns: repeat(2,1fr); gap: 0.75rem; } }

/* ── CARTE PRODUIT ── */
.cw-card {
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
}
.cw-card:hover {
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    transform: translateY(-4px);
    border-color: #e0e0e0;
    text-decoration: none;
}
.cw-card-img {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1/1;
    background: #f4f4f5;
}
.cw-card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s;
}
.cw-card:hover .cw-card-img img { transform: scale(1.05); }
.cw-card-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 0.5rem;
    color: #cbd5e1;
}
.cw-card-ph i { font-size: 2.4rem; }

/* Badge % OFF — coin haut droit, fond noir */
.cw-badge-off {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #111827;
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.32rem 0.6rem;
    border-radius: 8px;
    letter-spacing: 0.02em;
}

.cw-card-body {
    padding: 0.95rem 1rem 1.1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.cw-card-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.4;
    margin-bottom: 0.7rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.55em;
}
.cw-prices {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 0.55rem;
    flex-wrap: wrap;
}
.cw-price-old {
    font-size: 0.82rem;
    color: #9ca3af;
    text-decoration: line-through;
}
.cw-price-new {
    font-size: 1.05rem;
    font-weight: 800;
    color: #e11d48;
}
.cw-price-normal {
    font-size: 1.05rem;
    font-weight: 800;
    color: #111827;
}
.cw-price-free {
    font-size: 1rem;
    font-weight: 800;
    color: #16a34a;
}

/* ── EMPTY ── */
.cw-empty {
    text-align: center;
    padding: 5rem 2rem;
    color: #6b7280;
}
.cw-empty i { font-size: 3rem; color: #d1d5db; display: block; margin-bottom: 1rem; }
.cw-empty h3 { font-size: 1.1rem; color: #111827; font-weight: 700; margin-bottom: 0.5rem; }

/* ── PAGINATION ── */
.cw-pagination { display: flex; justify-content: center; }
.cw-pagination .pagination { gap: 4px; }
.cw-pagination .page-link {
    background: #fff !important;
    border: 1px solid #e5e7eb !important;
    color: #374151 !important;
    border-radius: 8px !important;
}
.cw-pagination .page-link:hover { background: #f3f4f6 !important; }
.cw-pagination .page-item.active .page-link {
    background: #111827 !important;
    border-color: #111827 !important;
    color: #fff !important;
}

@media(max-width:760px) {
    .cw-hero { padding: 2rem 1.25rem 1rem; }
    .cw-filters { padding: 1rem 1.25rem 0.5rem; }
    .cw-grid-wrap { padding: 1.25rem 1.25rem 3rem; }
    .cw-select { flex: 1; min-width: 0; }
    .cw-card-img img, .cw-card-ph { }
}
</style>
@endpush

@section('content')

{{-- ══ HERO : description en grand titre ══ --}}
<section class="cw-hero">
    <h1>{{ $boutique->description ?: 'Découvrez nos produits numériques — téléchargement immédiat après paiement.' }}</h1>
</section>

{{-- ══ FILTRES ══ --}}
<form action="{{ route('boutique.accueil') }}" method="GET" id="cw-filter-form">
<div class="cw-filters">
    <div class="cw-search">
        <i class="fas fa-search"></i>
        <input type="text" name="recherche" placeholder="Rechercher" value="{{ request('recherche') }}">
    </div>
    @if($categories->count() > 0)
    <select name="categorie" class="cw-select" onchange="document.getElementById('cw-filter-form').submit()">
        <option value="">Catégorie</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                {{ $cat->nom }}
            </option>
        @endforeach
    </select>
    @endif
    <select name="tri" class="cw-select" onchange="document.getElementById('cw-filter-form').submit()">
        <option value="">Type de produit</option>
        <option value="nouveaute" {{ request('tri')=='nouveaute' ? 'selected':'' }}>Nouveautés</option>
        <option value="prix_asc"  {{ request('tri')=='prix_asc'  ? 'selected':'' }}>Prix croissant</option>
        <option value="prix_desc" {{ request('tri')=='prix_desc' ? 'selected':'' }}>Prix décroissant</option>
    </select>
</div>
</form>

{{-- ══ GRILLE PRODUITS ══ --}}
<div class="cw-grid-wrap">
    @if($produits->count() > 0)
    <div class="cw-grid">
        @foreach($produits as $produit)
        @php
            $hasPromo = isset($produit->prix_promo) && $produit->prix_promo > 0 && $produit->prix_promo < $produit->prix;
            $discount = $hasPromo ? round((($produit->prix - $produit->prix_promo) / $produit->prix) * 100) : 0;
        @endphp
        <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="cw-card">
            <div class="cw-card-img">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy">
                @else
                    <div class="cw-card-ph">
                        <i class="fas fa-file-download"></i>
                    </div>
                @endif
                @if($discount > 0)
                    <div class="cw-badge-off">{{ $discount }}% OFF</div>
                @endif
            </div>
            <div class="cw-card-body">
                <div class="cw-card-title">{{ $produit->nom }}</div>
                <div class="cw-prices">
                    @if($produit->estGratuit())
                        <span class="cw-price-free">Gratuit</span>
                    @elseif($hasPromo)
                        <span class="cw-price-old" data-xof="{{ (int)$produit->prix }}">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                        <span class="cw-price-new" data-xof="{{ (int)$produit->prix_promo }}">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</span>
                    @else
                        <span class="cw-price-normal" data-xof="{{ (int)$produit->prix }}">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @if($produits->hasPages())
    <div class="cw-pagination">
        {{ $produits->withQueryString()->links() }}
    </div>
    @endif

    @else
    <div class="cw-empty">
        <i class="fas fa-box-open"></i>
        <h3>
            @if(request('recherche'))
                Aucun résultat pour "{{ request('recherche') }}"
            @elseif(request('categorie'))
                Aucun produit dans cette catégorie
            @else
                Aucun produit disponible pour le moment
            @endif
        </h3>
        <p style="font-size:0.9rem;margin-top:0.5rem;">
            @if(request('recherche') || request('categorie'))
                <a href="{{ route('boutique.accueil') }}" style="color:var(--accent);font-weight:600;">← Voir tous les produits</a>
            @else
                Revenez bientôt, de nouveaux produits arrivent !
            @endif
        </p>
    </div>
    @endif
</div>

@endsection
