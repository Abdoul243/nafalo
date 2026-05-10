@extends('layouts.boutique')

@section('title', 'Détails — ' . $achat->produit->nom)

@push('styles')
<style>
.detail-page { max-width: 900px; margin: 0 auto; padding: 2.5rem 1.5rem 5rem; }

/* Breadcrumb */
.breadcrumb-nav {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: #94a3b8; margin-bottom: 1.75rem;
}
.breadcrumb-nav a { color: #64748b; text-decoration: none; font-weight: 500; }
.breadcrumb-nav a:hover { color: #2563eb; }
.breadcrumb-nav i { font-size: 0.6rem; color: #cbd5e1; }
.breadcrumb-nav span { color: #0f172a; font-weight: 600; }

/* Hero produit */
.product-hero {
    background: white; border: 1px solid #e2e8f0; border-radius: 24px;
    overflow: hidden; margin-bottom: 1.5rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}
.product-hero-top {
    display: grid; grid-template-columns: 280px 1fr; gap: 0;
}
@media(max-width: 640px) {
    .product-hero-top { grid-template-columns: 1fr; }
}
.product-hero-img {
    height: 280px; overflow: hidden;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    display: flex; align-items: center; justify-content: center;
    position: relative;
}
.product-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.product-hero-img-ph { color: #94a3b8; font-size: 3.5rem; }

.product-hero-info {
    padding: 2rem 2rem 1.75rem;
    display: flex; flex-direction: column;
}
.product-hero-cat {
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: #2563eb; margin-bottom: 0.5rem;
}
.product-hero-name {
    font-size: 1.45rem; font-weight: 900; color: #0f172a;
    line-height: 1.25; margin-bottom: 1rem;
}
.product-hero-meta {
    display: flex; flex-wrap: wrap; gap: 0.85rem; margin-bottom: 1.5rem;
}
.meta-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 20px; padding: 0.35rem 0.85rem;
    font-size: 0.8rem; color: #475569; font-weight: 500;
}
.meta-pill i { color: #94a3b8; font-size: 0.75rem; }

.product-hero-price {
    font-size: 1.6rem; font-weight: 900; color: #2563eb;
    margin-top: auto; padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}
.paid-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #dcfce7; color: #15803d; border-radius: 20px;
    font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.75rem;
    margin-left: 0.75rem; vertical-align: middle;
}

/* Actions sidebar */
.layout-grid {
    display: grid; grid-template-columns: 1fr 280px; gap: 1.5rem; align-items: start;
}
@media(max-width: 768px) { .layout-grid { grid-template-columns: 1fr; } }

/* Sidebar sticky */
.sidebar-card {
    background: white; border: 1px solid #e2e8f0; border-radius: 20px;
    padding: 1.5rem; position: sticky; top: 90px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.sidebar-title {
    font-size: 0.85rem; font-weight: 800; color: #0f172a;
    margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.04em;
}
.btn-download-main {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 1rem;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white; border: none; border-radius: 14px;
    font-weight: 800; font-size: 0.95rem; text-decoration: none;
    cursor: pointer; transition: all 0.2s; margin-bottom: 0.75rem;
    box-shadow: 0 6px 20px rgba(37,99,235,0.3);
}
.btn-download-main:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(37,99,235,0.4); color: white; }
.btn-product-page {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.8rem;
    background: #f8fafc; color: #475569;
    border: 1.5px solid #e2e8f0; border-radius: 12px;
    font-weight: 600; font-size: 0.875rem; text-decoration: none;
    transition: all 0.2s; margin-bottom: 0.75rem;
}
.btn-product-page:hover { background: #f1f5f9; color: #0f172a; border-color: #cbd5e1; }
.btn-avis {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.8rem;
    background: #fffbeb; color: #b45309;
    border: 1.5px solid #fde68a; border-radius: 12px;
    font-weight: 600; font-size: 0.875rem; text-decoration: none;
    transition: all 0.2s;
}
.btn-avis:hover { background: #fef3c7; color: #92400e; }

/* Téléchargements */
.dl-history {
    background: white; border: 1px solid #e2e8f0; border-radius: 18px;
    overflow: hidden; margin-bottom: 1.5rem;
}
.section-header {
    padding: 1.1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 8px;
}
.section-header h3 {
    font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;
}
.section-header i { color: #2563eb; }
.section-body { padding: 1.25rem 1.5rem; }

.dl-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.6rem 0; border-bottom: 1px solid #f8fafc;
    font-size: 0.83rem;
}
.dl-row:last-child { border-bottom: none; }
.dl-date { color: #0f172a; font-weight: 600; }
.dl-ip { color: #94a3b8; font-family: monospace; font-size: 0.78rem; }
.dl-empty { text-align: center; color: #94a3b8; font-size: 0.875rem; padding: 1rem 0; }
.dl-count-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #eff6ff; color: #2563eb; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; margin-left: 8px;
}

/* Description */
.desc-section {
    background: white; border: 1px solid #e2e8f0; border-radius: 18px;
    overflow: hidden; margin-bottom: 1.5rem;
}
.desc-body {
    padding: 1.5rem;
    font-size: 0.9rem; line-height: 1.75; color: #334155;
}
.desc-body h1, .desc-body h2, .desc-body h3 {
    color: #0f172a; font-weight: 800; margin-top: 1.25rem; margin-bottom: 0.5rem;
}
.desc-body h1 { font-size: 1.3rem; }
.desc-body h2 { font-size: 1.1rem; }
.desc-body h3 { font-size: 0.95rem; }
.desc-body p { margin-bottom: 0.85rem; }
.desc-body strong { color: #0f172a; }
.desc-body ul, .desc-body ol { padding-left: 1.5rem; margin-bottom: 0.85rem; }
.desc-body li { margin-bottom: 0.35rem; }

/* Transaction info */
.transaction-info {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    font-size: 0.82rem;
}
.txn-item { display: flex; flex-direction: column; gap: 2px; }
.txn-label { color: #94a3b8; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.txn-value { color: #0f172a; font-weight: 700; font-family: monospace; }

/* Back button */
.btn-back {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.6rem 1.2rem; background: white;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    color: #64748b; font-size: 0.85rem; font-weight: 600;
    text-decoration: none; margin-bottom: 1.5rem;
    transition: all 0.2s;
}
.btn-back:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
</style>
@endpush

@section('content')
<div class="detail-page">

    {{-- Retour --}}
    <a href="{{ route('client.mes-achats.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour à mes achats
    </a>

    {{-- Breadcrumb --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('boutique.accueil') }}">Boutique</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('client.mes-achats.index') }}">Mes achats</a>
        <i class="fas fa-chevron-right"></i>
        <span>{{ Str::limit($achat->produit->nom, 40) }}</span>
    </div>

    {{-- Hero produit --}}
    <div class="product-hero">
        <div class="product-hero-top">
            <div class="product-hero-img">
                @if($achat->produit->image)
                    <img src="{{ asset('storage/' . $achat->produit->image) }}" alt="{{ $achat->produit->nom }}">
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

                @if($achat->transaction)
                <div class="transaction-info">
                    <div class="txn-item">
                        <span class="txn-label">Référence</span>
                        <span class="txn-value">{{ $achat->transaction->reference }}</span>
                    </div>
                    <div class="txn-item">
                        <span class="txn-label">Statut</span>
                        <span class="txn-value" style="color:#15803d;font-family:inherit;">
                            <i class="fas fa-check-circle" style="color:#22c55e;"></i> Confirmé
                        </span>
                    </div>
                </div>
                @endif

                <div class="product-hero-price">
                    {{ number_format($achat->produit->prix, 0, ',', ' ') }} FCFA
                    <span class="paid-badge"><i class="fas fa-check"></i> Payé</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Layout 2 colonnes --}}
    <div class="layout-grid">

        {{-- Colonne gauche --}}
        <div>

            {{-- Description --}}
            @if($achat->produit->description)
            <div class="desc-section">
                <div class="section-header">
                    <i class="fas fa-align-left"></i>
                    <h3>Description du produit</h3>
                </div>
                <div class="desc-body">
                    {!! $achat->produit->description !!}
                </div>
            </div>
            @endif

            {{-- Historique téléchargements --}}
            <div class="dl-history">
                <div class="section-header">
                    <i class="fas fa-history"></i>
                    <h3>
                        Historique des téléchargements
                        @if($dlCount > 0)
                        <span class="dl-count-badge">{{ $dlCount }}</span>
                        @endif
                    </h3>
                </div>
                <div class="section-body">
                    @if($achat->telechargements->count() > 0)
                        @foreach($achat->telechargements as $dl)
                        <div class="dl-row">
                            <div class="dl-date">
                                <i class="fas fa-download" style="color:#2563eb;margin-right:6px;font-size:0.75rem;"></i>
                                {{ $dl->created_at->format('d/m/Y à H:i:s') }}
                            </div>
                            <div class="dl-ip">{{ $dl->ip_adresse }}</div>
                        </div>
                        @endforeach
                    @else
                        <div class="dl-empty">
                            <i class="fas fa-download" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:0.5rem;"></i>
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

                <a href="{{ route('boutique.produit.show', $achat->produit->slug) }}" class="btn-product-page" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Voir la page produit
                </a>

                @if(!$achat->avis)
                <a href="{{ route('boutique.avis.create', $achat->produit) }}" class="btn-avis">
                    <i class="fas fa-star"></i> Laisser un avis
                </a>
                @else
                <div style="text-align:center;font-size:0.8rem;color:#22c55e;padding:0.5rem;font-weight:600;">
                    <i class="fas fa-check-circle"></i> Avis déjà soumis — Merci !
                </div>
                @endif

                <div style="border-top:1px solid #f1f5f9;margin-top:1.25rem;padding-top:1.25rem;">
                    <div style="font-size:0.72rem;color:#94a3b8;text-align:center;line-height:1.6;">
                        <i class="fas fa-shield-alt" style="color:#22c55e;"></i>
                        Fichier sécurisé — protégé par filigrane numérique
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
