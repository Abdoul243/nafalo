@extends('layouts.boutique')

@section('title', $produit->nom)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   FICHE PRODUIT — Dark Mode
═══════════════════════════════════════════════════════════ */
.prod-page { padding-bottom: 5rem; }

/* Breadcrumb */
.prod-breadcrumb {
    padding: 1.5rem 0 1rem;
    font-size: 0.82rem;
    color: var(--text-3);
    display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
}
.prod-breadcrumb a { color: var(--text-3); text-decoration: none; transition: color 0.15s; }
.prod-breadcrumb a:hover { color: var(--text-1); }
.prod-breadcrumb .sep { opacity: 0.3; }
.prod-breadcrumb .current { color: var(--text-2); }

/* Badge catégorie + tendance */
.prod-badges { display: flex; gap: 8px; margin-bottom: 1.25rem; flex-wrap: wrap; }
.prod-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700;
    padding: 0.3rem 0.8rem; border-radius: 6px;
    letter-spacing: 0.04em; text-transform: uppercase;
}
.prod-badge-cat    { background: rgba(124,58,237,0.15); color: var(--accent); border: 1px solid rgba(124,58,237,0.3); }
.prod-badge-hot    { background: rgba(249,115,22,0.15); color: #c2410c; border: 1px solid rgba(249,115,22,0.3); }
.prod-badge-new    { background: rgba(34,197,94,0.12);  color: #166534; border: 1px solid rgba(34,197,94,0.25); }

/* Titre */
.prod-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800; color: var(--text-1);
    line-height: 1.15; margin: 0 0 0.85rem;
    letter-spacing: -0.02em;
}

/* Méta */
.prod-meta {
    display: flex; align-items: center; gap: 0;
    font-size: 0.85rem; color: var(--text-3);
    flex-wrap: wrap; margin-bottom: 2rem;
}
.meta-item { display: flex; align-items: center; gap: 5px; }
.meta-sep { margin: 0 10px; opacity: 0.3; }
.prod-stars i { color: #f59e0b; font-size: 0.82rem; }

/* Layout 2 colonnes */
.prod-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3.5rem;
    align-items: start;
}
@media (max-width: 960px) {
    .prod-layout { grid-template-columns: 1fr; gap: 2rem; }
    .prod-sidebar { order: -1; }
}

/* ── Image ── */
.prod-image-block {
    border-radius: 20px;
    overflow: hidden;
    background: var(--bg-elevated);
    aspect-ratio: 4/3;
    display: flex; align-items: center; justify-content: center;
    position: relative;
    border: 1px solid var(--border);
}
.prod-image-block img { width: 100%; height: 100%; object-fit: contain; }
.prod-image-empty {
    display: flex; align-items: center; justify-content: center;
    width: 100%; height: 100%; min-height: 320px;
    color: rgba(0,0,0,0.18); font-size: 5rem;
    background: linear-gradient(135deg, #f3f4f6 0%, #ede9fe 50%, #eef2ff 100%);
}

/* Overlay "Aperçu" */
.prod-video-label {
    position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.15);
    color: white; font-size: 0.75rem; font-weight: 600;
    letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.4rem 1.1rem; border-radius: 20px;
    white-space: nowrap;
}

/* Description */
.prod-description { margin-top: 2.5rem; }
.prod-description h2 {
    font-size: 1rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
}
.description-content {
    color: var(--text-2);
    line-height: 1.85;
    font-size: 0.95rem;
}
.description-content img { max-width: 100%; border-radius: 12px; margin: 1rem 0; }
.description-content h1, .description-content h2, .description-content h3 { color: var(--text-1); margin-top: 1.5rem; font-family: 'Playfair Display', serif; }
.description-content ul, .description-content ol { padding-left: 1.5rem; }
.description-content strong { color: var(--text-1); }

/* Reviews */
.prod-reviews { margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border); }
.prod-reviews h2 { font-size: 1rem; font-weight: 700; color: var(--text-1); margin-bottom: 1.25rem; }
.review-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px; padding: 1.25rem;
    margin-bottom: 0.875rem;
}
.rating-summary {
    display: flex; gap: 2rem; align-items: center;
    padding: 1.25rem; background: var(--bg-elevated);
    border: 1px solid var(--border);
    border-radius: 14px; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.rating-big { font-size: 3rem; font-weight: 900; color: var(--text-1); line-height: 1; }
.rating-bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
.rating-bar-label { font-size: 0.8rem; color: var(--text-3); width: 40px; flex-shrink: 0; }
.rating-bar-track { flex: 1; height: 5px; background: rgba(0,0,0,0.06); border-radius: 3px; overflow: hidden; }
.rating-bar-fill { height: 100%; background: #f59e0b; border-radius: 3px; }
.rating-bar-count { font-size: 0.75rem; color: var(--text-3); width: 16px; text-align: right; }

/* Similar */
.prod-similaires { margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border); }
.prod-similaires h2 { font-size: 1rem; font-weight: 700; color: var(--text-1); margin-bottom: 1.25rem; }
.sim-card {
    background: var(--bg-card);
    border: 1px solid var(--border); border-radius: 14px;
    overflow: hidden; text-decoration: none; display: block;
    transition: all 0.2s;
}
.sim-card:hover { border-color: var(--border-hover); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
.sim-card img { width: 100%; height: 130px; object-fit: cover; }
.sim-img-ph { height: 130px; background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; color: var(--text-3); font-size: 2rem; }
.sim-body { padding: 12px 14px; }
.sim-name { font-size: 0.875rem; font-weight: 600; color: var(--text-1); }
.sim-price { font-size: 0.82rem; color: var(--text-3); margin-top: 4px; }

/* ═══════════════════════
   SIDEBAR
═══════════════════════ */
.prod-sidebar { position: sticky; top: 80px; }
.sidebar-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1.75rem;
    overflow: hidden;
}

/* Prix */
.sidebar-price {
    font-size: 2.5rem; font-weight: 900;
    color: var(--text-1); line-height: 1;
    letter-spacing: -0.02em;
}
.sidebar-price-currency { font-size: 1rem; font-weight: 600; color: var(--text-3); margin-left: 4px; }
.sidebar-price-old { font-size: 0.95rem; color: var(--text-3); text-decoration: line-through; margin-bottom: 4px; }
.sidebar-promo-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.2);
    color: #166534; font-size: 0.72rem; font-weight: 700;
    padding: 0.25rem 0.65rem; border-radius: 6px;
    margin-left: 0.6rem;
}
.sidebar-price-sub {
    font-size: 0.8rem; color: var(--text-3);
    margin-top: 0.4rem;
}
.sidebar-price-free {
    display: inline-block;
    background: var(--green-dim);
    border: 1px solid rgba(34,197,94,0.25);
    color: var(--green);
    font-size: 1.2rem; font-weight: 800;
    padding: 8px 20px; border-radius: 12px;
}

/* CTA buttons */
.btn-cta-main {
    width: 100%; padding: 1rem;
    font-size: 1rem; font-weight: 700;
    background: var(--accent); color: white;
    border: none; border-radius: 14px;
    cursor: pointer; text-align: center;
    display: block; text-decoration: none;
    margin-bottom: 0.7rem; letter-spacing: 0.01em;
    transition: all 0.2s;
    font-family: inherit;
}
.btn-cta-main:hover {
    background: var(--accent-hover); color: white;
    transform: translateY(-1px);
    box-shadow: 0 8px 24px var(--accent-glow);
}
.btn-cta-secondary {
    width: 100%; padding: 0.875rem;
    font-size: 0.9rem; font-weight: 600;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border);
    color: var(--text-2); border-radius: 14px;
    cursor: pointer; text-align: center;
    display: block; text-decoration: none;
    transition: all 0.2s; font-family: inherit;
}
.btn-cta-secondary:hover {
    background: rgba(0,0,0,0.06);
    border-color: var(--border-hover);
    color: var(--text-1);
}
.btn-cta-free {
    background: var(--green-dim);
    border: 1px solid rgba(34,197,94,0.25);
    color: var(--green) !important;
}
.btn-cta-free:hover {
    background: rgba(34,197,94,0.2) !important;
    box-shadow: 0 8px 24px rgba(34,197,94,0.25) !important;
}

/* Moyens de paiement */
.pm-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3); margin-bottom: 0.7rem;
}
.pm-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}
.pm-item {
    display: flex; align-items: center; gap: 8px;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border);
    border-radius: 10px; padding: 0.55rem 0.75rem;
}
.pm-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pm-name { font-size: 0.8rem; font-weight: 600; color: var(--text-2); }

/* Features */
.sidebar-features {
    list-style: none; padding: 0; margin: 0;
}
.sidebar-features li {
    display: flex; align-items: center; gap: 10px;
    font-size: 0.85rem; color: var(--text-2);
    padding: 0.55rem 0;
    border-bottom: 1px solid var(--border);
}
.sidebar-features li:last-child { border: none; }
.sidebar-features li i { color: var(--green); font-size: 0.85rem; width: 16px; flex-shrink: 0; }

/* Urgency */
.urgency-bar {
    background: rgba(249,115,22,0.08);
    border: 1px solid rgba(249,115,22,0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: #c2410c; font-weight: 600;
}
.urgency-bar i { color: #f97316; flex-shrink: 0; }
.cd-seg {
    background: rgba(0,0,0,0.10);
    color: var(--text-1); border-radius: 5px;
    padding: 2px 6px; font-size: 0.82rem;
    font-variant-numeric: tabular-nums; font-weight: 700;
}
.cd-sep { color: rgba(0,0,0,0.30); margin: 0 2px; }

/* Social proof */
.social-proof {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: var(--text-3);
    margin-bottom: 1rem;
}
.social-avatars { display: flex; }
.soc-av {
    width: 24px; height: 24px; border-radius: 50%;
    border: 2px solid var(--bg-card);
    background: linear-gradient(135deg, var(--accent), #2563eb);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.52rem; font-weight: 800;
    margin-left: -7px;
}
.soc-av:first-child { margin-left: 0; }

/* Creator card */
.creator-mini {
    display: flex; align-items: center; gap: 12px;
    padding: 0.875rem;
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 1rem;
}
.creator-mini-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #2563eb);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 800; font-size: 1rem; flex-shrink: 0;
}
.creator-mini-name { font-weight: 700; font-size: 0.875rem; color: var(--text-1); }
.creator-mini-sub { font-size: 0.75rem; color: var(--text-3); margin-top: 2px; }
.btn-follow {
    margin-left: auto;
    padding: 0.35rem 0.85rem;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-2);
    font-size: 0.78rem; font-weight: 600;
    cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap;
}
.btn-follow:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-light);
}

/* Security badges */
.security-note {
    font-size: 0.72rem; color: var(--text-3);
    text-align: center; margin-top: 1rem;
    line-height: 1.6;
}

/* Sidebar divider */
.s-divider {
    border: none; border-top: 1px solid var(--border);
    margin: 1.25rem 0;
}

/* Country selector */
.country-selector { position: relative; margin-bottom: 1.25rem; }
.country-selector-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 0.6rem 1rem;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border);
    border-radius: 10px;
    cursor: pointer; font-size: 0.85rem; color: var(--text-2);
    font-weight: 500; transition: all 0.15s;
    user-select: none;
}
.country-selector-btn:hover { border-color: var(--border-hover); color: var(--text-1); }
.country-selector-btn .chevron {
    margin-left: auto; color: var(--text-3); font-size: 0.7rem;
    transition: transform .2s;
}
.country-selector-btn.open .chevron { transform: rotate(180deg); }
.country-dropdown {
    position: absolute; top: calc(100% + 4px);
    left: 0; right: 0;
    background: var(--bg-elevated);
    border: 1px solid var(--border-hover);
    border-radius: 14px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.15);
    z-index: 999;
    max-height: 260px; overflow-y: auto;
    display: none; padding: 6px 0;
}
.country-dropdown.open { display: block; }
.country-option {
    display: flex; align-items: center; gap: 10px;
    padding: 0.6rem 1rem;
    font-size: 0.85rem; color: var(--text-2);
    cursor: pointer; transition: background 0.1s;
}
.country-option:hover { background: rgba(0,0,0,0.04); color: var(--text-1); }
.country-option.active { background: var(--accent-light); color: var(--accent); font-weight: 600; }
.country-option .currency { margin-left: auto; font-size: 0.75rem; color: var(--text-3); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .prod-page .store-wrap { padding: 0 1.25rem; }
}
@media (max-width: 480px) {
    .prod-title { font-size: 1.75rem; }
    .prod-meta { font-size: 0.8rem; }
    .sidebar-card { padding: 1.25rem; border-radius: 16px; }
    .sidebar-price { font-size: 2rem; }
}
</style>
@endpush

@section('content')
<div class="prod-page">
<div class="store-wrap">

    {{-- Breadcrumb --}}
    <div class="prod-breadcrumb">
        <a href="{{ route('boutique.accueil') }}">{{ $boutique->nom }}</a>
        <span class="sep">/</span>
        <a href="{{ route('boutique.produit.index') }}">Produits</a>
        @if($produit->categorie)
            <span class="sep">/</span>
            <span>{{ $produit->categorie->nom }}</span>
        @endif
        <span class="sep">/</span>
        <span class="current">{{ Str::limit($produit->nom, 45) }}</span>
    </div>

    {{-- Badges --}}
    <div class="prod-badges">
        @if($produit->categorie)
            <span class="prod-badge prod-badge-cat">
                <i class="fas fa-tag" style="font-size:0.65rem;"></i>
                {{ $produit->categorie->nom }}
            </span>
        @endif
        @php $nbVentes = $produit->achats()->count(); @endphp
        @if($nbVentes >= 10)
            <span class="prod-badge prod-badge-hot">⭐ Tendance</span>
        @elseif($produit->created_at->diffInDays(now()) <= 7)
            <span class="prod-badge prod-badge-new">✦ Nouveau</span>
        @endif
    </div>

    {{-- Titre --}}
    <h1 class="prod-title">{{ $produit->nom }}</h1>

    {{-- Méta --}}
    @php
        $moyenne   = $produit->avis->where('est_visible', true)->avg('note') ?? 0;
        $totalAvis = $produit->avis->where('est_visible', true)->count();
    @endphp
    <div class="prod-meta">
        <div class="meta-item">
            <i class="fas fa-shopping-bag" style="font-size:0.75rem;"></i>
            {{ $nbVentes }}+ achats
        </div>
        @if($totalAvis > 0)
        <span class="meta-sep">·</span>
        <div class="meta-item prod-stars">
            @for($i=1;$i<=5;$i++)<i class="{{ $i<=round($moyenne)?'fas':'far' }} fa-star"></i>@endfor
            <span style="margin-left:5px;color:var(--text-3);">{{ number_format($moyenne,1) }} ({{ $totalAvis }})</span>
        </div>
        @endif
        <span class="meta-sep">·</span>
        <div class="meta-item">
            MAJ {{ $produit->updated_at->format('d M Y') }}
        </div>
    </div>

    {{-- Layout 2 colonnes --}}
    <div class="prod-layout">

        {{-- ══ GAUCHE ══ --}}
        <div class="prod-left">
            {{-- Image / Média --}}
            <div class="prod-image-block">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}">
                @else
                    <div class="prod-image-empty">
                        <i class="fas fa-box-open"></i>
                    </div>
                @endif
                @if($produit->fichier_type === 'video' || ($produit->fichier && str_contains($produit->fichier, '.mp4')))
                    <div class="prod-video-label">
                        <i class="fas fa-play" style="font-size:0.6rem;"></i> APERÇU
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($produit->description)
            <div class="prod-description">
                <h2>À propos de ce produit</h2>
                <div class="description-content">{!! $produit->description !!}</div>
            </div>
            @endif

            {{-- Reviews --}}
            @if($totalAvis > 0)
            @php
                $avisVisible = $produit->avis->where('est_visible', true);
                $distribution = [];
                for ($n=5;$n>=1;$n--) { $distribution[$n] = $avisVisible->where('note',$n)->count(); }
            @endphp
            <div class="prod-reviews">
                <h2>Avis clients ({{ $totalAvis }})</h2>
                <div class="rating-summary">
                    <div style="text-align:center;flex-shrink:0;">
                        <div class="rating-big">{{ number_format($moyenne,1) }}</div>
                        <div class="prod-stars" style="display:flex;gap:3px;justify-content:center;margin:8px 0 4px;">
                            @for($i=1;$i<=5;$i++)<i class="{{ $i<=round($moyenne)?'fas':'far' }} fa-star"></i>@endfor
                        </div>
                        <div style="font-size:0.78rem;color:var(--text-3);">{{ $totalAvis }} avis</div>
                    </div>
                    <div style="flex:1;min-width:160px;">
                        @for($n=5;$n>=1;$n--)
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">{{ $n }} ★</div>
                            <div class="rating-bar-track"><div class="rating-bar-fill" style="width:{{ $totalAvis>0?round(($distribution[$n]/$totalAvis)*100):0 }}%;"></div></div>
                            <div class="rating-bar-count">{{ $distribution[$n] }}</div>
                        </div>
                        @endfor
                    </div>
                </div>
                @foreach($avisVisible->take(10) as $avis)
                <div class="review-card">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#2563eb);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:0.9rem;flex-shrink:0;">
                                {{ strtoupper(substr($avis->client->nom ?? $avis->client->email ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:0.875rem;font-weight:600;color:var(--text-1);">{{ $avis->client->nom ?? 'Client vérifié' }}</div>
                                <div class="prod-stars" style="display:flex;gap:2px;margin-top:3px;">
                                    @for($i=1;$i<=5;$i++)<i class="{{ $i<=$avis->note?'fas':'far' }} fa-star"></i>@endfor
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:0.75rem;color:var(--text-3);">{{ $avis->created_at->format('d/m/Y') }}</div>
                            <div style="font-size:0.7rem;color:var(--green);display:flex;align-items:center;gap:3px;margin-top:3px;justify-content:flex-end;">
                                <i class="fas fa-check-circle"></i> Achat vérifié
                            </div>
                        </div>
                    </div>
                    @if($avis->commentaire)
                    <p style="font-size:0.875rem;line-height:1.7;color:var(--text-2);margin:0;">{{ $avis->commentaire }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- Similar --}}
            @if($produitsSimilaires->count() > 0)
            <div class="prod-similaires">
                <h2>Produits similaires</h2>
                <div class="row g-3">
                    @foreach($produitsSimilaires as $similaire)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('boutique.produit.show', $similaire->slug) }}" class="sim-card">
                            @if($similaire->image)
                                <img src="{{ $similaire->image_url }}" alt="{{ $similaire->nom }}">
                            @else
                                <div class="sim-img-ph"><i class="fas fa-image"></i></div>
                            @endif
                            <div class="sim-body">
                                <div class="sim-name">{{ Str::limit($similaire->nom, 35) }}</div>
                                <div class="sim-price">{{ $similaire->estGratuit() ? 'Gratuit' : number_format($similaire->prix,0,',',' ').' F' }}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ══ SIDEBAR ══ --}}
        <div class="prod-sidebar">
            <div class="sidebar-card">

                {{-- Prix (conversion gérée par le sélecteur global du header) --}}
                <div style="margin-bottom:1.25rem;">
                    @if($produit->estGratuit())
                        <div class="sidebar-price-free">🎁 GRATUIT</div>
                    @else
                        @php
                            $hasPromo = isset($produit->prix_promo) && $produit->prix_promo > 0 && $produit->prix_promo < $produit->prix;
                            $discount = $hasPromo ? round((($produit->prix - $produit->prix_promo) / $produit->prix) * 100) : 0;
                        @endphp
                        @if($hasPromo)
                            <div class="sidebar-price-old" data-xof="{{ (int)$produit->prix }}">
                                {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                        <div style="display:flex;align-items:baseline;gap:0.5rem;flex-wrap:wrap;">
                            <div class="sidebar-price" data-xof="{{ $hasPromo ? (int)$produit->prix_promo : (int)$produit->prix }}">
                                {{ $hasPromo ? number_format($produit->prix_promo, 0, ',', ' ') : number_format($produit->prix, 0, ',', ' ') }} FCFA
                            </div>
                            @if($hasPromo)
                                <span class="sidebar-promo-badge">-{{ $discount }}%</span>
                            @endif
                        </div>
                        <div class="sidebar-price-sub">Paiement unique · Accès à vie</div>
                    @endif
                </div>

                {{-- Urgency countdown --}}
                @if($produit->estPayant())
                <div class="urgency-bar">
                    <i class="fas fa-bolt"></i>
                    Téléchargement immédiat ·
                    <span class="cd-seg" id="cd-h">23</span><span class="cd-sep">h</span>
                    <span class="cd-seg" id="cd-m">59</span><span class="cd-sep">m</span>
                    <span class="cd-seg" id="cd-s">59</span><span class="cd-sep">s</span>
                </div>
                @endif

                {{-- Social proof --}}
                @if($nbVentes > 0)
                <div class="social-proof">
                    <div class="social-avatars">
                        @for($av=0;$av<min(4,$nbVentes);$av++)
                        <div class="soc-av">{{ chr(65+$av) }}</div>
                        @endfor
                    </div>
                    <span><strong style="color:var(--text-1);">{{ $nbVentes }}</strong> achat{{ $nbVentes>1?'s':'' }}</span>
                </div>
                @endif

                {{-- CTA --}}
                @if($produit->estPayant())
                <form action="{{ route('boutique.panier.ajouter', $produit) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn-cta-main">
                        Acheter maintenant
                    </button>
                </form>
                <form action="{{ route('boutique.panier.ajouter', $produit) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn-cta-secondary">
                        Ajouter au panier
                    </button>
                </form>
                @else
                    @if($produit->limiteAtteinte())
                    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#dc2626;border-radius:12px;padding:14px;text-align:center;font-size:0.9rem;font-weight:600;">
                        <i class="fas fa-lock me-2"></i> Ce produit n'est plus disponible
                    </div>
                    @else
                    <button type="button" class="btn-cta-main btn-cta-free"
                            onclick="document.getElementById('lead-form-modal').style.display='flex'">
                        <i class="fas fa-gift me-2"></i> Obtenir gratuitement
                    </button>
                    @if($produit->lead_limite_dl)
                    <div class="urgency-bar" style="margin-top:0.75rem;">
                        <i class="fas fa-fire"></i>
                        Plus que <strong>{{ $produit->placesRestantes() }}</strong> téléchargements gratuits
                    </div>
                    @endif
                    @endif
                @endif

                <hr class="s-divider">

                {{-- Paiement en un clic --}}
                <div class="pm-label">Paiement en un clic</div>
                <div class="pm-grid" id="paymentMethodsGrid">
                    <div class="pm-item"><div class="pm-dot" style="background:#00B9F1;"></div><span class="pm-name">Wave</span></div>
                    <div class="pm-item"><div class="pm-dot" style="background:#FF6600;"></div><span class="pm-name">Orange Money</span></div>
                    <div class="pm-item"><div class="pm-dot" style="background:#FFCC00;"></div><span class="pm-name">MTN MoMo</span></div>
                    <div class="pm-item"><div class="pm-dot" style="background:#0080C8;"></div><span class="pm-name">Moov Money</span></div>
                    <div class="pm-item"><div class="pm-dot" style="background:#1A1F71;"></div><span class="pm-name">Visa / MC</span></div>
                </div>

                <hr class="s-divider">

                {{-- Créateur --}}
                <div class="creator-mini">
                    <div class="creator-mini-avatar">
                        {{ strtoupper(substr($boutique->nom, 0, 2)) }}
                    </div>
                    <div>
                        <div class="creator-mini-name">{{ $boutique->nom }}</div>
                        <div class="creator-mini-sub">{{ $nbVentes }} élèves · Créateur vérifié</div>
                    </div>
                    <button class="btn-follow">Suivre</button>
                </div>

                <hr class="s-divider">

                {{-- Features --}}
                <ul class="sidebar-features">
                    <li><i class="fas fa-bolt"></i> Téléchargement immédiat après paiement</li>
                    <li><i class="fas fa-infinity"></i> Accès à vie</li>
                    <li><i class="fas fa-sync-alt"></i> Mises à jour incluses</li>
                    <li><i class="fas fa-shield-alt"></i> Paiement 100% sécurisé</li>
                    <li><i class="fas fa-medal"></i> Garantie 30 jours satisfait ou remboursé</li>
                </ul>

                <div class="security-note">
                    SÉCURISÉ PAR MONEROO · ISO 27001<br>
                    GARANTIE 30 JOURS · POLITIQUE D'ANNULATION
                </div>

            </div>
        </div>

    </div>{{-- end layout --}}
</div>
</div>

{{-- ══ MODAL LEAD MAGNET ══ --}}
@if($produit->estGratuit() && !$produit->limiteAtteinte())
<div id="lead-form-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;padding:1rem;"
     onclick="if(event.target===this) this.style.display='none'">
    <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:24px;width:100%;max-width:460px;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,0.25);animation:slideUp .25s ease;">
        <div style="background:linear-gradient(135deg,var(--accent),#2563eb);padding:24px 24px 20px;position:relative;">
            <button onclick="document.getElementById('lead-form-modal').style.display='none'"
                    style="position:absolute;top:14px;right:14px;background:rgba(255,255,255,0.15);border:none;color:white;border-radius:50%;width:30px;height:30px;cursor:pointer;font-size:0.9rem;display:flex;align-items:center;justify-content:center;">✕</button>
            <div style="font-size:1.8rem;margin-bottom:6px;">🎁</div>
            <h3 style="color:white;margin:0;font-size:1.15rem;font-weight:700;font-family:'Playfair Display',serif;">{{ $produit->nom }}</h3>
            <p style="color:rgba(255,255,255,0.7);margin:5px 0 0;font-size:0.88rem;">Entrez vos informations pour recevoir votre accès gratuit.</p>
        </div>
        <form action="{{ route('boutique.lead.capturer', $produit) }}" method="POST" style="padding:20px 24px 24px;">
            @csrf
            @foreach([['nom','Votre prénom & nom','text','Ex: Moussa Traoré'],['email','Votre email','email','votre@email.com']] as [$field,$label,$type,$ph])
            <div style="margin-bottom:14px;">
                <label style="font-weight:600;font-size:0.85rem;color:var(--text-2);display:block;margin-bottom:5px;">{{ $label }} *</label>
                <input type="{{ $type }}" name="{{ $field }}" required placeholder="{{ $ph }}"
                       style="width:100%;background:rgba(0,0,0,0.03);border:1px solid var(--border);border-radius:10px;padding:10px 13px;font-size:0.9rem;outline:none;color:var(--text-1);font-family:inherit;transition:border-color 0.15s;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            @endforeach
            @foreach($produit->champsLeadActifs() as $champ)
            @php $lbls=['telephone'=>'Téléphone','ville'=>'Ville','profession'=>'Profession','pays'=>'Pays'];$phs=['telephone'=>'+221 77 000 00 00','ville'=>'Dakar','profession'=>'Entrepreneur','pays'=>'Sénégal'];$tps=['telephone'=>'tel','ville'=>'text','profession'=>'text','pays'=>'text']; @endphp
            <div style="margin-bottom:14px;">
                <label style="font-weight:600;font-size:0.85rem;color:var(--text-2);display:block;margin-bottom:5px;">{{ $lbls[$champ] ?? $champ }}</label>
                <input type="{{ $tps[$champ]??'text' }}" name="{{ $champ }}" placeholder="{{ $phs[$champ]??'' }}"
                       style="width:100%;background:rgba(0,0,0,0.03);border:1px solid var(--border);border-radius:10px;padding:10px 13px;font-size:0.9rem;outline:none;color:var(--text-1);font-family:inherit;">
            </div>
            @endforeach
            <button type="submit" style="width:100%;background:var(--accent);color:white;border:none;border-radius:12px;padding:13px;font-weight:700;font-size:1rem;cursor:pointer;margin-top:6px;font-family:inherit;transition:background 0.15s;"
                    onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
                <i class="fas fa-paper-plane me-2"></i> Recevoir mon accès gratuit
            </button>
            <p style="font-size:0.75rem;color:var(--text-3);text-align:center;margin:10px 0 0;">🔒 Vos données sont confidentielles. Aucun spam.</p>
        </form>
    </div>
</div>
<style>@keyframes slideUp { from{transform:translateY(30px);opacity:0} to{transform:translateY(0);opacity:1} }</style>
@endif

@endsection

@push('scripts')
<script>
// Countdown
(function() {
    const key = 'nafalo_cd_{{ $produit->id }}';
    let end = sessionStorage.getItem(key);
    if (!end) { end = Date.now() + 24*3600*1000; sessionStorage.setItem(key, end); }
    end = parseInt(end);
    function pad(n) { return n < 10 ? '0'+n : n; }
    function tick() {
        const diff = Math.max(0, end - Date.now());
        const eH = document.getElementById('cd-h');
        const eM = document.getElementById('cd-m');
        const eS = document.getElementById('cd-s');
        if(eH) eH.textContent = pad(Math.floor(diff/3600000));
        if(eM) eM.textContent = pad(Math.floor((diff%3600000)/60000));
        if(eS) eS.textContent = pad(Math.floor((diff%60000)/1000));
    }
    tick(); setInterval(tick, 1000);
})();
</script>
@endpush
