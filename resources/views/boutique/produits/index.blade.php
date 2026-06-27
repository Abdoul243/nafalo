@extends('layouts.boutique')

@section('title', 'Tous les produits')

@push('styles')
<style>
/* ── Page hero ── */
.page-hero {
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    padding: 2.5rem 0 0;
    position: relative;
    overflow: hidden;
}
.page-hero::before {
    content: '';
    position: absolute;
    top: -60px; left: -60px;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%);
    pointer-events: none;
}
.page-hero-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.page-hero-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.75rem; color: var(--text-3);
    margin-bottom: 0.875rem;
}
.page-hero-breadcrumb a { color: var(--text-3); text-decoration: none; transition: color 0.2s; }
.page-hero-breadcrumb a:hover { color: var(--accent); }
.page-hero-breadcrumb i { font-size: 0.55rem; color: var(--text-3); }

.page-hero h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.35rem;
}
.page-hero p { font-size: 0.875rem; color: var(--text-3); margin-bottom: 0; }

/* ── Filter bar ── */
.filters-bar {
    max-width: 1280px;
    margin: 0 auto;
    padding: 1.5rem 2rem 0;
}
.filters-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.filter-input-wrap {
    flex: 1; min-width: 180px;
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.04);
    border: 1.5px solid var(--border);
    border-radius: 11px;
    padding: 0.6rem 0.9rem;
    transition: border-color 0.15s;
}
.filter-input-wrap:focus-within {
    border-color: var(--accent);
    background: rgba(124,58,237,0.05);
}
.filter-input-wrap i { color: var(--text-3); font-size: 0.82rem; }
.filter-input-wrap input {
    border: none; outline: none; flex: 1;
    font-size: 0.875rem; color: var(--text-1);
    background: transparent; font-family: inherit;
}
.filter-input-wrap input::placeholder { color: var(--text-3); }

.f-select {
    padding: 0.6rem 2rem 0.6rem 0.9rem;
    background: rgba(255,255,255,0.04);
    border: 1.5px solid var(--border);
    border-radius: 11px;
    font-size: 0.85rem; font-weight: 500;
    color: var(--text-2);
    cursor: pointer;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%2394a3b8' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    font-family: inherit; outline: none;
    transition: border-color 0.2s;
}
.f-select:focus { border-color: var(--accent); }
.f-select option { background: #1e1e2a; color: var(--text-1); }

.btn-filter {
    padding: 0.65rem 1.4rem;
    background: var(--accent); color: white;
    border: none; border-radius: 11px;
    font-weight: 700; font-size: 0.875rem;
    cursor: pointer; font-family: inherit;
    display: flex; align-items: center; gap: 6px;
    transition: all 0.2s; white-space: nowrap;
}
.btn-filter:hover { background: var(--accent-hover); }

/* ── Category pills ── */
.cat-pills {
    display: flex; gap: 0.5rem; flex-wrap: wrap;
    padding: 1.5rem 0 1.25rem;
}
.cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.4rem 0.9rem; border-radius: 20px;
    border: 1.5px solid var(--border);
    font-size: 0.8rem; font-weight: 600;
    color: var(--text-2); background: var(--bg-card);
    text-decoration: none; transition: all 0.2s;
}
.cat-pill:hover {
    border-color: rgba(124,58,237,0.4);
    color: var(--accent);
    background: rgba(124,58,237,0.07);
}
.cat-pill.active {
    background: var(--accent); color: white;
    border-color: var(--accent);
    box-shadow: 0 3px 12px rgba(124,58,237,0.3);
}
.cat-pill .cnt {
    background: rgba(255,255,255,0.15);
    border-radius: 10px; padding: 1px 6px;
    font-size: 0.68rem;
}

/* ── Product grid ── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}
@media(max-width:1200px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
@media(max-width:768px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; } }
@media(max-width:480px)  { .product-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; } }

/* ── Product card ── */
.p-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: all 0.3s;
    position: relative;
}
.p-card:hover {
    border-color: rgba(124,58,237,0.35);
    transform: translateY(-5px);
    box-shadow: 0 16px 44px rgba(0,0,0,0.3);
}

.p-img {
    position: relative; overflow: hidden;
    background: rgba(255,255,255,0.04);
}
.p-img img {
    width: 100%; height: 200px;
    object-fit: cover; display: block;
    transition: transform 0.4s;
}
.p-card:hover .p-img img { transform: scale(1.06); }
.p-img-ph {
    width: 100%; height: 200px;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 0.5rem; color: var(--text-3);
}
.p-img-ph i { font-size: 2rem; }
.p-img-ph span { font-size: 0.7rem; font-weight: 600; }

.p-badge-wrap {
    position: absolute; top: 10px; left: 10px;
    display: flex; flex-direction: column; gap: 4px;
}
.p-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.65rem; font-weight: 800;
    padding: 0.22rem 0.55rem; border-radius: 20px;
}
.p-badge-promo { background: #ef4444; color: white; }
.p-badge-new   { background: var(--accent); color: white; }
.p-badge-hot   { background: #f97316; color: white; }

.p-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(10,10,15,0.85) 0%, transparent 55%);
    opacity: 0; transition: opacity 0.3s;
    display: flex; align-items: flex-end; justify-content: center;
    padding-bottom: 1rem;
}
.p-card:hover .p-overlay { opacity: 1; }
.btn-quick {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 1.2rem;
    background: white; color: #0a0a0f;
    border: none; border-radius: 50px;
    font-weight: 800; font-size: 0.78rem;
    cursor: pointer; font-family: inherit;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: all 0.2s;
}
.btn-quick:hover { background: var(--accent); color: white; }

.p-body {
    padding: 1rem 1.1rem 1.1rem;
    flex: 1; display: flex; flex-direction: column;
}
.p-cat {
    font-size: 0.68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--accent); margin-bottom: 0.3rem;
}
.p-title {
    font-weight: 800; font-size: 0.88rem;
    color: var(--text-1); line-height: 1.35;
    margin-bottom: 0.5rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.p-stars {
    display: flex; align-items: center; gap: 2px; margin-bottom: 0.7rem;
}
.p-stars i { color: #f59e0b; font-size: 0.68rem; }
.p-stars i.e { color: var(--text-3); }
.p-stars span { font-size: 0.7rem; color: var(--text-3); margin-left: 5px; }

.p-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto; padding-top: 0.7rem;
    border-top: 1px solid var(--border);
}
.p-price-old { font-size: 0.72rem; color: var(--text-3); text-decoration: line-through; }
.p-price { font-size: 1rem; font-weight: 900; color: var(--text-1); }
.p-price.promo { color: #22c55e; }

.btn-buy {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 0.48rem 0.85rem;
    background: var(--accent); color: white;
    border: none; border-radius: 9px;
    font-weight: 700; font-size: 0.75rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.2s;
    box-shadow: 0 3px 10px rgba(124,58,237,0.3);
}
.btn-buy:hover { background: var(--accent-hover); transform: translateY(-1px); }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 5rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
}
.empty-state-icon {
    width: 72px; height: 72px;
    background: rgba(124,58,237,0.08);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 1.8rem; color: var(--accent);
}
.empty-state h3 { font-size: 1.1rem; font-weight: 800; color: var(--text-1); margin-bottom: 0.5rem; }
.empty-state p { color: var(--text-3); font-size: 0.875rem; }

/* ── Responsive ── */
@media(max-width:768px) {
    .page-hero-inner { padding: 0 1.25rem; }
    .page-hero h1 { font-size: 1.5rem; }
    .filters-bar { padding: 1.25rem 1.25rem 0; }
    .filters-card { flex-direction: column; align-items: stretch; gap: 0.5rem; }
    .filter-input-wrap { min-width: auto; }
    .btn-filter { justify-content: center; }
    .store-wrap { padding: 0 1.25rem; }
    .cat-pills { padding: 1.25rem 0 1rem; }
}
@media(max-width:480px) {
    .p-img img, .p-img-ph { height: 155px; }
    .p-body { padding: 0.75rem 0.875rem 1rem; }
    .p-title { font-size: 0.82rem; }
    .p-price { font-size: 0.9rem; }
}
</style>
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="page-hero-inner">
        <div class="page-hero-breadcrumb">
            <a href="{{ route('boutique.accueil') }}">Accueil</a>
            <i class="fas fa-chevron-right"></i>
            <span>Produits</span>
        </div>
        <h1>Tous les produits</h1>
        <p>{{ $produits->total() }} produit{{ $produits->total() > 1 ? 's' : '' }} disponibles — téléchargement immédiat après paiement</p>
    </div>
</div>

{{-- Filters --}}
<form action="{{ route('boutique.produit.index') }}" method="GET" id="filter-form">
<div class="filters-bar">
    <div class="filters-card">
        <div class="filter-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="recherche" placeholder="Rechercher un produit..."
                   value="{{ request('recherche') }}">
        </div>
        @if($categories->count() > 0)
        <select name="categorie" class="f-select" onchange="document.getElementById('filter-form').submit()">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select>
        @endif
        <select name="tri" class="f-select" onchange="document.getElementById('filter-form').submit()">
            <option value="">Trier par</option>
            <option value="nouveaute" {{ request('tri')=='nouveaute' ? 'selected':'' }}>Nouveautés</option>
            <option value="prix_asc"  {{ request('tri')=='prix_asc'  ? 'selected':'' }}>Prix croissant</option>
            <option value="prix_desc" {{ request('tri')=='prix_desc' ? 'selected':'' }}>Prix décroissant</option>
        </select>
        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Rechercher
        </button>
    </div>
</div>
</form>

<div class="store-wrap">

    {{-- Category pills --}}
    @if($categories->count() > 0)
    <div class="cat-pills">
        <a href="{{ route('boutique.produit.index') }}"
           class="cat-pill {{ !request('categorie') ? 'active' : '' }}">
            <i class="fas fa-th" style="font-size:0.7rem;"></i> Tout
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('boutique.produit.index', ['categorie' => $cat->id]) }}"
           class="cat-pill {{ request('categorie') == $cat->id ? 'active' : '' }}">
            {{ $cat->nom }}
        </a>
        @endforeach
    </div>
    @else
    <div style="height:1.75rem;"></div>
    @endif

    {{-- Product grid --}}
    @if($produits->count() > 0)
    <div class="product-grid">
        @foreach($produits as $produit)
        @php
            $hasPromo = isset($produit->prix_promo) && $produit->prix_promo > 0 && $produit->prix_promo < $produit->prix;
            $discount = $hasPromo ? round((($produit->prix - $produit->prix_promo) / $produit->prix) * 100) : 0;
            $isNew    = $produit->created_at->diffInDays(now()) <= 7;
            $avisVis  = $produit->avis->where('est_visible', true);
            $nbAvis   = $avisVis->count();
            $moy      = $avisVis->avg('note') ?? 0;
        @endphp
        <div class="p-card">
            <div class="p-img">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy">
                @else
                    <div class="p-img-ph">
                        <i class="fas fa-file-download"></i>
                        <span>Produit digital</span>
                    </div>
                @endif

                <div class="p-badge-wrap">
                    @if($discount > 0)
                        <span class="p-badge p-badge-promo"><i class="fas fa-tag"></i> -{{ $discount }}%</span>
                    @elseif($isNew)
                        <span class="p-badge p-badge-new">Nouveau</span>
                    @endif
                </div>

                <div class="p-overlay">
                    <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="btn-quick">
                        <i class="fas fa-eye"></i> Voir le produit
                    </a>
                </div>
            </div>

            <div class="p-body">
                @if($produit->categorie)
                <div class="p-cat">{{ $produit->categorie->nom }}</div>
                @endif
                <div class="p-title">{{ $produit->nom }}</div>

                <div class="p-stars">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star {{ $i <= round($moy) ? '' : 'e' }}"></i>
                    @endfor
                    <span>({{ $nbAvis ?: 'Nouveau' }})</span>
                </div>

                <div class="p-footer">
                    <div>
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

    <div style="display:flex;justify-content:center;margin-bottom:3rem;">
        {{ $produits->withQueryString()->links() }}
    </div>

    @else
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
        <h3>Aucun produit trouvé</h3>
        <p>
            @if(request('recherche') || request('categorie'))
                <a href="{{ route('boutique.produit.index') }}" style="color:var(--accent);font-weight:600;">
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
