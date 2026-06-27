@extends('layouts.boutique')

@section('title', $boutique->nom)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PAGE ACCUEIL — Dark Discovery
═══════════════════════════════════════════════════════════ */

/* ── HERO ── */
.hero {
    position: relative;
    padding: 5rem 0 4rem;
    overflow: hidden;
    text-align: center;
}
.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 50% -10%, rgba(124,58,237,0.28) 0%, transparent 60%),
        radial-gradient(ellipse 50% 40% at 20% 80%, rgba(59,130,246,0.1) 0%, transparent 50%);
    pointer-events: none;
}
/* Grille décorative */
.hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 0%, black 0%, transparent 70%);
    pointer-events: none;
}
.hero-inner {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 1;
}

/* Badge édition */
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.35);
    color: #c4b5fd;
    font-size: 0.76rem; font-weight: 700;
    padding: 0.35rem 1rem; border-radius: 20px;
    letter-spacing: 0.04em; text-transform: uppercase;
    margin-bottom: 1.75rem;
}
.hero-badge-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--accent);
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: 0.5; transform: scale(1.4); }
}

/* Titre éditorial */
.hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2.8rem, 7vw, 5.5rem);
    font-weight: 900;
    color: var(--text-1);
    line-height: 1.05;
    margin: 0 0 1.5rem;
    letter-spacing: -0.02em;
}
.hero-title em {
    font-style: italic;
    color: rgba(255,255,255,0.55);
}

.hero-sub {
    color: var(--text-2);
    font-size: 1.05rem;
    margin: 0 0 2.5rem;
    line-height: 1.75;
    max-width: 560px;
    margin-left: auto; margin-right: auto;
}
.hero-sub strong { color: var(--text-1); }

/* CTA buttons */
.hero-ctas {
    display: flex; align-items: center; justify-content: center;
    gap: 0.875rem; flex-wrap: wrap;
}
.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.85rem 1.75rem;
    background: var(--accent);
    color: white; font-weight: 700; font-size: 0.95rem;
    border: none; border-radius: 50px;
    cursor: pointer; text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 6px 24px var(--accent-glow);
}
.btn-hero-primary:hover {
    background: var(--accent-hover);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}
.btn-hero-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.85rem 1.75rem;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.15);
    color: var(--text-1); font-weight: 600; font-size: 0.95rem;
    border-radius: 50px; text-decoration: none;
    transition: all 0.2s;
}
.btn-hero-secondary:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.25);
    color: var(--text-1);
    transform: translateY(-2px);
}

/* Stripe paiements */
.payments-strip {
    display: flex; align-items: center; justify-content: center;
    gap: 0; flex-wrap: wrap;
    margin-top: 3.5rem;
    padding-top: 3rem;
    border-top: 1px solid var(--border);
}
.payment-sep {
    width: 4px; height: 4px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    margin: 0 1.25rem; flex-shrink: 0;
}
.payment-name {
    font-size: 0.72rem; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: rgba(255,255,255,0.25);
}

/* ── SECTION PRODUITS ── */
.discovery-section {
    max-width: 1280px; margin: 0 auto;
    padding: 4rem 2rem 5rem;
}

/* Header de section */
.section-meta {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em;
    color: var(--text-3); margin-bottom: 0.6rem;
}
.section-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: var(--text-1);
    margin: 0 0 2rem;
    line-height: 1.2;
}

/* Category tabs */
.cat-tabs {
    display: flex; align-items: center; gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
}
.cat-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.45rem 1.1rem;
    border-radius: 20px;
    font-size: 0.82rem; font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: 1px solid transparent;
}
.cat-tab.all {
    background: var(--text-1);
    color: var(--bg);
    border-color: var(--text-1);
}
.cat-tab:not(.all) {
    background: rgba(255,255,255,0.04);
    color: var(--text-2);
    border-color: var(--border);
}
.cat-tab:not(.all):hover, .cat-tab:not(.all).active {
    background: rgba(124,58,237,0.12);
    border-color: rgba(124,58,237,0.4);
    color: #c4b5fd;
}
.cat-tab.all.active, .cat-tab.all:hover {
    background: var(--text-1);
    color: var(--bg);
}

/* ── PRODUCT GRID ── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
@media (max-width: 1100px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; } }
@media (max-width: 480px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; } }

/* ── PRODUCT CARD ── */
.p-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}
.p-card:hover {
    border-color: rgba(255,255,255,0.12);
    transform: translateY(-4px);
    box-shadow: 0 16px 50px rgba(0,0,0,0.5);
    color: inherit;
}

/* Image */
.p-img {
    position: relative;
    overflow: hidden;
    aspect-ratio: 16/9;
    background: var(--bg-elevated);
    flex-shrink: 0;
}
.p-img img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform 0.5s;
}
.p-card:hover .p-img img { transform: scale(1.06); }
.p-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1e1e2e 0%, #2a1a3e 50%, #1a2a3e 100%);
    position: relative;
    overflow: hidden;
}
.p-img-placeholder::before {
    content: '';
    position: absolute; inset: 0;
    background: repeating-linear-gradient(
        -45deg,
        transparent, transparent 10px,
        rgba(255,255,255,0.02) 10px, rgba(255,255,255,0.02) 20px
    );
}
.p-img-placeholder i {
    font-size: 2rem;
    color: rgba(255,255,255,0.15);
    position: relative; z-index: 1;
}

/* Badge catégorie */
.p-badge-wrap {
    position: absolute; top: 10px; left: 10px;
    display: flex; gap: 5px;
}
.p-badge {
    font-size: 0.68rem; font-weight: 700;
    padding: 0.25rem 0.6rem; border-radius: 6px;
    letter-spacing: 0.03em;
}
.p-badge-cat  { background: rgba(0,0,0,0.5); color: rgba(255,255,255,0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); }
.p-badge-promo{ background: var(--red); color: white; }
.p-badge-new  { background: var(--accent); color: white; }
.p-badge-hot  { background: #f97316; color: white; }

/* Corps */
.p-body { padding: 1rem 1.1rem 1.25rem; flex: 1; display: flex; flex-direction: column; }
.p-creator {
    font-size: 0.72rem; color: var(--text-3);
    margin-bottom: 0.35rem; font-weight: 500;
}
.p-title {
    font-weight: 700; font-size: 0.93rem;
    color: var(--text-1); line-height: 1.35;
    margin-bottom: 0.75rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.p-stars {
    display: flex; align-items: center; gap: 3px;
    margin-bottom: 0.75rem;
}
.p-stars i { color: #f59e0b; font-size: 0.7rem; }
.p-stars i.empty { color: rgba(255,255,255,0.15); }
.p-stars span { font-size: 0.72rem; color: var(--text-3); margin-left: 4px; }
.p-footer {
    display: flex; align-items: center;
    justify-content: space-between; margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border);
}
.p-price-old { font-size: 0.76rem; color: var(--text-3); text-decoration: line-through; }
.p-price { font-size: 1.05rem; font-weight: 900; color: var(--text-1); }
.p-price.promo { color: var(--green); }
.btn-p-buy {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 0.45rem 0.9rem;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.3);
    border-radius: 8px; color: #c4b5fd;
    font-size: 0.78rem; font-weight: 700;
    transition: all 0.2s; cursor: pointer;
    white-space: nowrap;
}
.btn-p-buy:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center; padding: 5rem 2rem;
    color: var(--text-3);
}
.empty-state i { font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.4; }
.empty-state h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-2); margin-bottom: 0.5rem; }

/* ── PAGINATION ── */
.pagination-wrap {
    display: flex; justify-content: center;
    margin-top: 3rem;
}
.pagination .page-link {
    background: var(--bg-card) !important;
    border-color: var(--border) !important;
    color: var(--text-2) !important;
    border-radius: 8px !important;
    margin: 0 2px;
}
.pagination .page-link:hover {
    background: var(--bg-elevated) !important;
    color: var(--text-1) !important;
}
.pagination .page-item.active .page-link {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: white !important;
}

/* ── TRUST STRIP ── */
.trust-strip {
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 2.5rem 0;
}
.trust-grid {
    max-width: 1280px; margin: 0 auto; padding: 0 2rem;
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem; text-align: center;
}
@media (max-width: 768px) { .trust-grid { grid-template-columns: repeat(2, 1fr); } }
.trust-item i { font-size: 1.3rem; color: var(--accent); margin-bottom: 0.6rem; display: block; }
.trust-item strong { display: block; font-size: 0.875rem; font-weight: 700; color: var(--text-1); margin-bottom: 3px; }
.trust-item span { font-size: 0.78rem; color: var(--text-3); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .hero { padding: 3.5rem 0 3rem; }
    .hero-inner { padding: 0 1.25rem; }
    .discovery-section { padding: 3rem 1.25rem 4rem; }
    .hero-sub { font-size: 0.95rem; }
    .payments-strip { margin-top: 2.5rem; }
}
@media (max-width: 480px) {
    .hero-ctas { flex-direction: column; align-items: stretch; }
    .btn-hero-primary, .btn-hero-secondary { justify-content: center; }
    .payments-strip { gap: 0; }
    .payment-sep { margin: 0 0.75rem; }
    .cat-tabs { gap: 0.4rem; }
    .cat-tab { padding: 0.4rem 0.85rem; font-size: 0.78rem; }
    .p-body { padding: 0.75rem 0.875rem 1rem; }
    .p-title { font-size: 0.85rem; }
    .p-price { font-size: 0.95rem; }
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-inner">

        {{-- Badge édition --}}
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            {{ $boutique->nom }} · {{ now()->isoFormat('D MMMM YYYY') }}
        </div>

        {{-- Titre éditorial --}}
        <h1 class="hero-title">
            @if($boutique->description)
                {!! nl2br(e(Str::limit($boutique->description, 80))) !!}
            @else
                Le digital africain,<br><em>en toute clarté.</em>
            @endif
        </h1>

        <p class="hero-sub">
            Achetez. Téléchargez. Apprenez.
            <strong>{{ $produits->total() }} produit{{ $produits->total() > 1 ? 's' : '' }}</strong>
            disponibles sur <strong>{{ $boutique->nom }}</strong> —
            paiements mobiles instantanés et livraison immédiate.
        </p>

        {{-- CTAs --}}
        <div class="hero-ctas">
            <a href="#produits" class="btn-hero-primary">
                Découvrir les produits
            </a>
            <a href="{{ route('client.acces.demande') }}" class="btn-hero-secondary">
                Mes achats →
            </a>
        </div>

        {{-- Stripe paiements --}}
        <div class="payments-strip">
            @foreach(['WAVE', 'ORANGE MONEY', 'MTN MOMO', 'MOOV MONEY', 'VISA'] as $i => $pm)
                @if($i > 0)<span class="payment-sep"></span>@endif
                <span class="payment-name">{{ $pm }}</span>
            @endforeach
        </div>

    </div>
</section>

{{-- ══ PRODUITS ══ --}}
<section class="discovery-section" id="produits">

    {{-- Header --}}
    <div class="section-meta">
        {{ now()->isoFormat('W[ème semaine] · MMMM YYYY') }}
    </div>
    <h2 class="section-title">À voir cette semaine</h2>

    {{-- Filtres catégories --}}
    <div class="cat-tabs">
        <a href="{{ route('boutique.accueil') }}"
           class="cat-tab all {{ !request('categorie') ? 'active' : '' }}">
            Tout
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('boutique.accueil', ['categorie' => $cat->id, 'tri' => request('tri')]) }}"
           class="cat-tab {{ request('categorie') == $cat->id ? 'active' : '' }}">
            {{ $cat->nom }}
        </a>
        @endforeach
    </div>

    {{-- Grille produits --}}
    @if($produits->count() > 0)
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
        <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="p-card">
            {{-- Image --}}
            <div class="p-img">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy">
                @else
                    <div class="p-img-placeholder">
                        <i class="fas fa-file-download"></i>
                    </div>
                @endif

                {{-- Badges --}}
                <div class="p-badge-wrap">
                    @if($produit->categorie)
                        <span class="p-badge p-badge-cat">{{ $produit->categorie->nom }}</span>
                    @endif
                    @if($discount > 0)
                        <span class="p-badge p-badge-promo">-{{ $discount }}%</span>
                    @elseif($isNew)
                        <span class="p-badge p-badge-new">Nouveau</span>
                    @elseif($isHot)
                        <span class="p-badge p-badge-hot">🔥</span>
                    @endif
                </div>
            </div>

            {{-- Corps --}}
            <div class="p-body">
                <div class="p-creator">{{ $boutique->nom }}</div>
                <div class="p-title">{{ $produit->nom }}</div>

                @if($totalAvis > 0)
                <div class="p-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($moyenne) ? '' : 'empty' }}"></i>
                    @endfor
                    <span>{{ number_format($moyenne, 1) }} ({{ $totalAvis }})</span>
                </div>
                @endif

                <div class="p-footer">
                    <div>
                        @if($produit->estGratuit())
                            <div class="p-price" style="color:var(--green);">Gratuit</div>
                        @else
                            @if($hasPromo)
                                <div class="p-price-old">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                <div class="p-price promo">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</div>
                            @else
                                <div class="p-price">{{ number_format($produit->prix, 0, ',', ' ') }} F</div>
                            @endif
                        @endif
                    </div>
                    <div class="btn-p-buy">
                        @if($produit->estGratuit())
                            <i class="fas fa-gift"></i> Obtenir
                        @else
                            Acheter →
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($produits->hasPages())
    <div class="pagination-wrap">
        {{ $produits->withQueryString()->links() }}
    </div>
    @endif

    @else
    <div class="empty-state">
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
                <a href="{{ route('boutique.accueil') }}" style="color:var(--accent);">← Voir tous les produits</a>
            @else
                Revenez bientôt, de nouveaux produits arrivent !
            @endif
        </p>
    </div>
    @endif

</section>

{{-- ══ TRUST STRIP ══ --}}
<div class="trust-strip">
    <div class="trust-grid">
        <div class="trust-item">
            <i class="fas fa-bolt"></i>
            <strong>Livraison instantanée</strong>
            <span>Téléchargement immédiat</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-shield-alt"></i>
            <strong>Paiement sécurisé</strong>
            <span>Données protégées</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-infinity"></i>
            <strong>Accès à vie</strong>
            <span>Téléchargez quand vous voulez</span>
        </div>
        <div class="trust-item">
            <i class="fas fa-medal"></i>
            <strong>Satisfait ou remboursé</strong>
            <span>30 jours de garantie</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Smooth scroll vers #produits
document.querySelectorAll('a[href="#produits"]').forEach(a => {
    a.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('produits')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
