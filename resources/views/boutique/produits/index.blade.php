@extends('layouts.boutique')

@section('title', 'Tous les produits')

@push('styles')
<style>
/* Mini hero */
.page-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 2.5rem 0;
}
.page-hero-inner { max-width:1280px; margin:0 auto; padding:0 2rem; }
.page-hero h1 { font-size:1.8rem; font-weight:900; color:white; margin:0 0 0.4rem; }
.page-hero p  { color:rgba(255,255,255,0.55); font-size:0.9rem; margin:0; }
.breadcrumb-custom { display:flex; align-items:center; gap:6px; font-size:0.78rem; color:rgba(255,255,255,0.4); margin-bottom:0.75rem; }
.breadcrumb-custom a { color:rgba(255,255,255,0.55); text-decoration:none; }
.breadcrumb-custom a:hover { color:white; }
.breadcrumb-custom i { font-size:0.6rem; }

/* Filtres */
.filters-bar {
    max-width:1280px; margin:-1.25rem auto 0; padding:0 2rem;
    position:relative; z-index:10;
}
.filters-card {
    background:white; border-radius:16px;
    box-shadow:0 6px 30px rgba(0,0,0,0.12);
    padding:1rem 1.25rem;
    display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;
}
.filter-input-wrap {
    flex:1; min-width:180px;
    display:flex; align-items:center; gap:8px;
    background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px;
    padding:0.6rem 0.9rem; transition:border-color 0.15s;
}
.filter-input-wrap:focus-within { border-color:#2563eb; background:white; }
.filter-input-wrap i { color:#94a3b8; font-size:0.85rem; }
.filter-input-wrap input { border:none; outline:none; flex:1; font-size:0.875rem; color:#111; background:transparent; font-family:inherit; }
.f-select {
    padding:0.6rem 2rem 0.6rem 0.9rem;
    border:1.5px solid #e2e8f0; border-radius:11px;
    font-size:0.85rem; font-weight:500; color:#374151;
    background:#f8fafc; cursor:pointer;
    -webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%23666' d='M5 7L0 2h10z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 8px center;
    font-family:inherit; outline:none;
}
.f-select:focus { border-color:#2563eb; background:white; }
.btn-filter {
    padding:0.65rem 1.4rem; background:#2563eb; color:white;
    border:none; border-radius:11px; font-weight:700; font-size:0.875rem;
    cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;
    transition:all 0.2s; white-space:nowrap;
}
.btn-filter:hover { background:#1d4ed8; }

/* Pills catégories */
.cat-pills { display:flex; gap:0.5rem; flex-wrap:wrap; padding:2rem 0 1.5rem; }
.cat-pill {
    display:inline-flex; align-items:center; gap:6px;
    padding:0.4rem 0.9rem; border-radius:20px;
    border:1.5px solid #e2e8f0; font-size:0.82rem; font-weight:600;
    color:#475569; background:white; text-decoration:none; transition:all 0.2s;
}
.cat-pill:hover { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
.cat-pill.active { background:#2563eb; color:white; border-color:#2563eb; }
.cat-pill .cnt { background:rgba(0,0,0,0.1); border-radius:10px; padding:1px 6px; font-size:0.7rem; }
.cat-pill.active .cnt { background:rgba(255,255,255,0.25); }

/* Grid */
.product-grid {
    display:grid; grid-template-columns:repeat(4,1fr);
    gap:1.5rem; margin-bottom:3rem;
}
@media(max-width:1200px){.product-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:768px) {.product-grid{grid-template-columns:repeat(2,1fr);gap:1rem;}}
@media(max-width:480px) {.product-grid{grid-template-columns:1fr;}}

/* Card */
.p-card { background:white; border-radius:20px; border:1px solid #f1f5f9; overflow:hidden; display:flex; flex-direction:column; transition:all 0.3s; position:relative; }
.p-card:hover { box-shadow:0 12px 40px rgba(0,0,0,0.1); transform:translateY(-5px); border-color:#e2e8f0; }
.p-img { position:relative; overflow:hidden; background:linear-gradient(135deg,#f8fafc,#f1f5f9); }
.p-img img { width:100%; height:210px; object-fit:cover; display:block; transition:transform 0.4s; }
.p-card:hover .p-img img { transform:scale(1.06); }
.p-img-ph { width:100%; height:210px; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:0.5rem; color:#cbd5e1; }
.p-img-ph i { font-size:2.2rem; }

.p-badge-wrap { position:absolute; top:10px; left:10px; display:flex; flex-direction:column; gap:4px; }
.p-badge { display:inline-flex; align-items:center; gap:4px; font-size:0.67rem; font-weight:800; padding:0.25rem 0.6rem; border-radius:20px; }
.p-badge-promo { background:#ef4444; color:white; }
.p-badge-new { background:#0f172a; color:white; }
.p-badge-hot { background:#f97316; color:white; }

.p-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(15,23,42,0.8) 0%,transparent 60%); opacity:0; transition:opacity 0.3s; display:flex; align-items:flex-end; justify-content:center; padding-bottom:1rem; }
.p-card:hover .p-overlay { opacity:1; }
.btn-quick { display:inline-flex; align-items:center; gap:6px; padding:0.55rem 1.3rem; background:white; color:#0f172a; border:none; border-radius:50px; font-weight:800; font-size:0.8rem; cursor:pointer; font-family:inherit; text-decoration:none; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition:all 0.2s; }
.btn-quick:hover { background:#2563eb; color:white; }

.p-body { padding:1rem 1.2rem 1.25rem; flex:1; display:flex; flex-direction:column; }
.p-cat { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#2563eb; margin-bottom:0.35rem; }
.p-title { font-weight:800; font-size:0.92rem; color:#0f172a; line-height:1.35; margin-bottom:0.55rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.p-stars { display:flex; align-items:center; gap:2px; margin-bottom:0.7rem; }
.p-stars i { color:#f59e0b; font-size:0.7rem; }
.p-stars i.e { color:#e2e8f0; }
.p-stars span { font-size:0.72rem; color:#94a3b8; margin-left:5px; }

.p-footer { display:flex; align-items:center; justify-content:space-between; margin-top:auto; padding-top:0.7rem; border-top:1px solid #f1f5f9; }
.p-price-old { font-size:0.75rem; color:#94a3b8; text-decoration:line-through; }
.p-price { font-size:1.05rem; font-weight:900; color:#0f172a; }
.p-price.promo { color:#ef4444; }
.btn-buy { display:inline-flex; align-items:center; gap:5px; padding:0.5rem 0.9rem; background:#2563eb; color:white; border:none; border-radius:9px; font-weight:700; font-size:0.78rem; cursor:pointer; font-family:inherit; transition:all 0.2s; }
.btn-buy:hover { background:#1d4ed8; transform:translateY(-1px); }

/* Empty */
.empty-state { text-align:center; padding:5rem 2rem; color:#94a3b8; }
.empty-state i { font-size:3rem; display:block; margin-bottom:1rem; }
.empty-state h3 { font-size:1.1rem; font-weight:800; color:#334155; margin-bottom:0.5rem; }

/* ── RESPONSIVE ── */
@media(max-width:768px) {
    .page-hero { padding:2rem 0; }
    .page-hero h1 { font-size:1.4rem; }
    .filters-bar { padding:0 1rem; }
    .filters-card { flex-direction:column; align-items:stretch; gap:0.5rem; }
    .filter-input-wrap { min-width:auto; }
    .btn-filter { justify-content:center; }
    .cat-pills { padding:1.25rem 0 1rem; gap:0.4rem; }
    .store-wrap { padding:0 1rem; }
}
@media(max-width:480px) {
    .product-grid { grid-template-columns:1fr 1fr; gap:0.75rem; }
    .p-img img, .p-img-ph { height:160px; }
    .p-body { padding:0.75rem 0.875rem 1rem; }
    .p-title { font-size:0.85rem; }
    .p-price { font-size:0.95rem; }
}
</style>
@endpush

@section('content')

{{-- Mini hero --}}
<div class="page-hero">
    <div class="page-hero-inner">
        <div class="breadcrumb-custom">
            <a href="{{ route('boutique.accueil') }}">Accueil</a>
            <i class="fas fa-chevron-right"></i>
            <span>Produits</span>
        </div>
        <h1>🛍 Tous les produits</h1>
        <p>{{ $produits->total() }} produit{{ $produits->total() > 1 ? 's' : '' }} disponibles — téléchargement immédiat</p>
    </div>
</div>

{{-- Barre de filtres --}}
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

    {{-- Pills catégories --}}
    @if($categories->count() > 0)
    <div class="cat-pills">
        <a href="{{ route('boutique.produit.index') }}" class="cat-pill {{ !request('categorie') ? 'active' : '' }}">
            <i class="fas fa-th"></i> Tout
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('boutique.produit.index', ['categorie' => $cat->id]) }}"
           class="cat-pill {{ request('categorie') == $cat->id ? 'active' : '' }}">
            {{ $cat->nom }}
        </a>
        @endforeach
    </div>
    @else
    <div style="height:2rem;"></div>
    @endif

    {{-- Grille produits --}}
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
                    <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}">
                @else
                    <div class="p-img-ph">
                        <i class="fas fa-file-download"></i>
                        <span style="font-size:0.72rem;font-weight:600;">Produit digital</span>
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
                        <i class="fas fa-eye"></i> Voir
                    </a>
                </div>
            </div>

            <div class="p-body">
                @if($produit->categorie)
                <div class="p-cat">{{ $produit->categorie->nom }}</div>
                @endif
                <div class="p-title">{{ $produit->nom }}</div>

                <div class="p-stars">
                    @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i<=round($moy)?'':'e' }}"></i>@endfor
                    <span>({{ $nbAvis ?: 'Nouveau' }})</span>
                </div>

                <div class="p-footer">
                    <div>
                        @if($hasPromo)
                            <div class="p-price-old">{{ number_format($produit->prix,0,',',' ') }} FCFA</div>
                            <div class="p-price promo">{{ number_format($produit->prix_promo,0,',',' ') }} FCFA</div>
                        @else
                            <div class="p-price">{{ number_format($produit->prix,0,',',' ') }} FCFA</div>
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
        <i class="fas fa-box-open"></i>
        <h3>Aucun produit trouvé</h3>
        <p style="font-size:0.875rem;">
            @if(request('recherche') || request('categorie'))
                <a href="{{ route('boutique.produit.index') }}" style="color:#2563eb;font-weight:600;">← Voir tous les produits</a>
            @else
                Revenez bientôt, de nouveaux produits arrivent !
            @endif
        </p>
    </div>
    @endif

</div>
@endsection
