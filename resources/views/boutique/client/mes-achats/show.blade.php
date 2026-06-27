@extends('layouts.boutique')

@section('title', 'Détails — ' . $achat->produit->nom)

@push('styles')
<style>
.detail-page { max-width: 900px; margin: 0 auto; padding: 2.5rem 1.25rem 5rem; }

/* Breadcrumb */
.breadcrumb-nav {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.78rem; color: var(--text-3); margin-bottom: 1.75rem;
}
.breadcrumb-nav a { color: var(--text-3); text-decoration: none; transition: color 0.2s; }
.breadcrumb-nav a:hover { color: var(--accent); }
.breadcrumb-nav i { font-size: 0.55rem; }
.breadcrumb-nav span { color: var(--text-2); font-weight: 600; }

/* Back button */
.btn-back {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.55rem 1.1rem;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border); border-radius: 10px;
    color: var(--text-2); font-size: 0.82rem; font-weight: 600;
    text-decoration: none; margin-bottom: 1.5rem;
    transition: all 0.2s;
}
.btn-back:hover { border-color: var(--accent); color: var(--accent); background: rgba(124,58,237,0.07); }

/* Hero */
.product-hero {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    overflow: hidden; margin-bottom: 1.5rem;
}
.product-hero-top {
    display: grid; grid-template-columns: 260px 1fr; gap: 0;
}
@media(max-width: 640px) { .product-hero-top { grid-template-columns: 1fr; } }

.product-hero-img {
    height: 260px; overflow: hidden;
    background: rgba(0,0,0,0.03);
    display: flex; align-items: center; justify-content: center;
}
.product-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.product-hero-img-ph { color: var(--text-3); font-size: 3rem; }

.product-hero-info {
    padding: 1.75rem 1.75rem 1.5rem;
    display: flex; flex-direction: column;
    border-left: 1px solid var(--border);
}
@media(max-width: 640px) { .product-hero-info { border-left: none; border-top: 1px solid var(--border); } }

.product-hero-cat {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: var(--accent); margin-bottom: 0.5rem;
}
.product-hero-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.35rem; font-weight: 700; color: var(--text-1);
    line-height: 1.3; margin-bottom: 1rem;
}
.product-hero-meta {
    display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 1.25rem;
}
.meta-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border);
    border-radius: 20px; padding: 0.3rem 0.75rem;
    font-size: 0.77rem; color: var(--text-2); font-weight: 500;
}
.meta-pill i { color: var(--text-3); font-size: 0.7rem; }

.product-hero-price {
    font-size: 1.5rem; font-weight: 900; color: var(--accent);
    margin-top: auto; padding-top: 1rem;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; gap: 0.75rem;
}
.paid-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.25);
    color: #22c55e; border-radius: 20px;
    font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.65rem;
}

/* Transaction info */
.txn-row {
    display: flex; gap: 1.5rem; flex-wrap: wrap;
    padding: 1rem 1.75rem; border-top: 1px solid var(--border);
}
.txn-item { display: flex; flex-direction: column; gap: 2px; }
.txn-label { color: var(--text-3); font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; }
.txn-value { color: var(--text-1); font-weight: 700; font-family: 'Courier New', monospace; font-size: 0.88rem; }

/* Layout */
.layout-grid {
    display: grid; grid-template-columns: 1fr 270px; gap: 1.5rem; align-items: start;
}
@media(max-width: 768px) { .layout-grid { grid-template-columns: 1fr; } }

/* Sections */
.detail-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px; overflow: hidden;
    margin-bottom: 1.25rem;
}
.section-head {
    padding: 1rem 1.4rem; border-bottom: 1px solid var(--border);
    font-size: 0.76rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3);
    display: flex; align-items: center; gap: 7px;
}
.section-head i { color: var(--accent); }
.section-body { padding: 1.25rem 1.4rem; }

/* Description */
.desc-body {
    font-size: 0.9rem; line-height: 1.8; color: var(--text-2);
}
.desc-body h1, .desc-body h2, .desc-body h3 {
    color: var(--text-1); font-weight: 800; margin-top: 1.25rem; margin-bottom: 0.5rem;
}
.desc-body h1 { font-size: 1.2rem; }
.desc-body h2 { font-size: 1rem; }
.desc-body h3 { font-size: 0.9rem; }
.desc-body p { margin-bottom: 0.85rem; }
.desc-body strong { color: var(--text-1); }
.desc-body ul, .desc-body ol { padding-left: 1.5rem; margin-bottom: 0.85rem; }

/* Download history */
.dl-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.6rem 0; border-bottom: 1px solid var(--border);
    font-size: 0.8rem;
}
.dl-row:last-child { border-bottom: none; }
.dl-date { color: var(--text-2); font-weight: 600; }
.dl-ip { color: var(--text-3); font-family: 'Courier New', monospace; font-size: 0.75rem; }
.dl-empty { text-align: center; color: var(--text-3); font-size: 0.875rem; padding: 1rem 0; }
.dl-count-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(124,58,237,0.1);
    border: 1px solid rgba(124,58,237,0.2);
    color: var(--accent); border-radius: 20px;
    font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.55rem;
}

/* Sidebar */
.sidebar-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.4rem;
    position: sticky; top: 90px;
}
.sidebar-title {
    font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3); margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: 7px;
}
.sidebar-title::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

.btn-download-main {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 0.9rem;
    background: var(--accent);
    color: white; border: none; border-radius: 12px;
    font-weight: 700; font-size: 0.9rem; text-decoration: none;
    cursor: pointer; transition: all 0.2s; margin-bottom: 0.6rem;
    box-shadow: 0 4px 16px rgba(124,58,237,0.3);
}
.btn-download-main:hover { background: var(--accent-hover); transform: translateY(-2px); color: white; }

.btn-sidebar-secondary {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.75rem;
    background: rgba(0,0,0,0.03);
    color: var(--text-2);
    border: 1px solid var(--border); border-radius: 11px;
    font-weight: 600; font-size: 0.83rem; text-decoration: none;
    transition: all 0.2s; margin-bottom: 0.5rem;
}
.btn-sidebar-secondary:hover { background: rgba(0,0,0,0.06); color: var(--text-1); border-color: rgba(0,0,0,0.15); }

.btn-review {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.75rem;
    background: rgba(234,179,8,0.08);
    color: #fbbf24;
    border: 1px solid rgba(234,179,8,0.2); border-radius: 11px;
    font-weight: 600; font-size: 0.83rem; text-decoration: none;
    transition: all 0.2s;
}
.btn-review:hover { background: rgba(234,179,8,0.14); color: #fbbf24; }

.security-note {
    font-size: 0.72rem; color: var(--text-3);
    text-align: center; line-height: 1.6;
    padding-top: 1rem; margin-top: 1rem;
    border-top: 1px solid var(--border);
}
.security-note i { color: #22c55e; }
</style>
@endpush

@section('content')
<div class="detail-page">

    <a href="{{ route('client.mes-achats.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour à mes achats
    </a>

    <div class="breadcrumb-nav">
        <a href="{{ route('boutique.accueil') }}">Boutique</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('client.mes-achats.index') }}">Mes achats</a>
        <i class="fas fa-chevron-right"></i>
        <span>{{ Str::limit($achat->produit->nom, 40) }}</span>
    </div>

    {{-- Hero --}}
    <div class="product-hero">
        <div class="product-hero-top">
            <div class="product-hero-img">
                @if($achat->produit->image)
                    <img src="{{ $achat->produit->image_url }}" alt="{{ $achat->produit->nom }}">
                @else
                    <i class="fas fa-file-download product-hero-img-ph"></i>
                @endif
            </div>
            <div class="product-hero-info">
                @if($achat->produit->categorie)
                <div class="product-hero-cat">
                    <i class="fas fa-tag"></i> {{ $achat->produit->categorie->nom }}
                </div>
                @endif

                <div class="product-hero-name">{{ $achat->produit->nom }}</div>

                <div class="product-hero-meta">
                    <div class="meta-pill">
                        <i class="fas fa-calendar-alt"></i>
                        Acheté le {{ $achat->created_at->format('d/m/Y') }}
                    </div>
                    <div class="meta-pill">
                        <i class="fas fa-clock"></i>
                        {{ $achat->created_at->format('H:i') }}
                    </div>
                    @php $dlCount = $achat->telechargements()->count(); @endphp
                    @if($dlCount > 0)
                    <div class="meta-pill">
                        <i class="fas fa-download"></i>
                        {{ $dlCount }} téléchargement{{ $dlCount > 1 ? 's' : '' }}
                    </div>
                    @endif
                </div>

                <div class="product-hero-price">
                    {{ number_format($achat->produit->prix, 0, ',', ' ') }} FCFA
                    <span class="paid-badge"><i class="fas fa-check"></i> Payé</span>
                </div>
            </div>
        </div>

        @if($achat->transaction)
        <div class="txn-row">
            <div class="txn-item">
                <span class="txn-label">Référence</span>
                <span class="txn-value">{{ $achat->transaction->reference }}</span>
            </div>
            <div class="txn-item">
                <span class="txn-label">Statut</span>
                <span class="txn-value" style="font-family:inherit;color:#22c55e;">
                    <i class="fas fa-check-circle"></i> Confirmé
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- Layout 2 cols --}}
    <div class="layout-grid">

        <div>
            {{-- Description --}}
            @if($achat->produit->description)
            <div class="detail-section">
                <div class="section-head"><i class="fas fa-align-left"></i> Description du produit</div>
                <div class="section-body">
                    <div class="desc-body">{!! $achat->produit->description !!}</div>
                </div>
            </div>
            @endif

            {{-- Download history --}}
            <div class="detail-section">
                <div class="section-head">
                    <i class="fas fa-history"></i> Historique des téléchargements
                    @if($dlCount > 0)
                        <span class="dl-count-chip">{{ $dlCount }}</span>
                    @endif
                </div>
                <div class="section-body">
                    @if($achat->telechargements->count() > 0)
                        @foreach($achat->telechargements as $dl)
                        <div class="dl-row">
                            <div class="dl-date">
                                <i class="fas fa-download" style="color:var(--accent);margin-right:6px;font-size:0.7rem;"></i>
                                {{ $dl->created_at->format('d/m/Y à H:i:s') }}
                            </div>
                            <div class="dl-ip">{{ $dl->ip_adresse }}</div>
                        </div>
                        @endforeach
                    @else
                        <div class="dl-empty">
                            <i class="fas fa-download" style="font-size:1.4rem;color:var(--text-3);display:block;margin-bottom:0.5rem;"></i>
                            Aucun téléchargement pour le moment
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="sidebar-card">
                <div class="sidebar-title">Actions</div>

                <a href="{{ route('client.telechargement', $achat) }}" class="btn-download-main">
                    <i class="fas fa-download"></i> Télécharger le fichier
                </a>

                <a href="{{ route('boutique.produit.show', $achat->produit->slug) }}" class="btn-sidebar-secondary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Voir la page produit
                </a>

                @if(!$achat->avis)
                <a href="{{ route('boutique.avis.create', $achat->produit) }}" class="btn-review">
                    <i class="fas fa-star"></i> Laisser un avis
                </a>
                @else
                <div style="text-align:center;font-size:0.8rem;color:#22c55e;padding:0.5rem;font-weight:600;">
                    <i class="fas fa-check-circle"></i> Avis déjà soumis — Merci !
                </div>
                @endif

                <div class="security-note">
                    <i class="fas fa-shield-alt"></i>
                    Fichier protégé par filigrane numérique
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
