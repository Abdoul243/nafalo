@extends('layouts.boutique')

@section('title', 'Mes achats')

@push('styles')
<style>
    .espace-client { padding: 2.5rem 0 4rem; }

    /* Header espace client */
    .ec-header { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-radius: 20px; padding: 2rem 2.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
    .ec-avatar { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 800; flex-shrink: 0; border: 2px solid rgba(255,255,255,0.3); }
    .ec-info h2 { color: white; font-size: 1.3rem; font-weight: 800; margin: 0 0 0.25rem; }
    .ec-info p  { color: rgba(255,255,255,0.65); font-size: 0.875rem; margin: 0; }
    .ec-stats   { display: flex; gap: 1.5rem; }
    .ec-stat    { text-align: center; }
    .ec-stat-val { color: white; font-size: 1.6rem; font-weight: 900; line-height: 1; }
    .ec-stat-lbl { color: rgba(255,255,255,0.55); font-size: 0.75rem; margin-top: 2px; }
    .btn-deconnexion { display: inline-flex; align-items: center; gap: 7px; padding: 0.55rem 1.2rem; background: rgba(255,255,255,0.1); color: white; border: 1.5px solid rgba(255,255,255,0.25); border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; text-decoration: none; }
    .btn-deconnexion:hover { background: rgba(255,255,255,0.2); color: white; }

    /* Achat cards */
    .achats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    @media (max-width: 768px) { .achats-grid { grid-template-columns: 1fr; } }

    .achat-card { background: white; border-radius: 18px; border: 1px solid #f0f4f8; overflow: hidden; transition: all 0.25s; }
    .achat-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.09); transform: translateY(-3px); }

    .achat-img { position: relative; height: 180px; overflow: hidden; background: linear-gradient(135deg, #f8fafc, #e2e8f0); display: flex; align-items: center; justify-content: center; }
    .achat-img img { width: 100%; height: 100%; object-fit: cover; }
    .achat-img-ph { color: #94a3b8; font-size: 2.5rem; }
    .achat-status { position: absolute; top: 12px; right: 12px; display: inline-flex; align-items: center; gap: 5px; background: #22c55e; color: white; font-size: 0.7rem; font-weight: 700; padding: 0.3rem 0.7rem; border-radius: 20px; }

    .achat-body { padding: 1.25rem 1.4rem 1.4rem; }
    .achat-nom { font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 0.35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .achat-date { font-size: 0.8rem; color: #94a3b8; margin-bottom: 1rem; display: flex; align-items: center; gap: 5px; }
    .achat-meta { display: flex; align-items: center; justify-content: space-between; }
    .achat-prix { font-size: 1.15rem; font-weight: 900; color: #2563eb; }

    .achat-actions { display: flex; gap: 0.5rem; }
    .btn-dl { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #2563eb; color: white; border: none; border-radius: 9px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s; }
    .btn-dl:hover { background: #1d4ed8; color: white; transform: translateY(-1px); }
    .btn-detail { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 0.9rem; background: #f1f5f9; color: #475569; border: none; border-radius: 9px; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-detail:hover { background: #e2e8f0; color: #0f172a; }

    .dl-count { font-size: 0.75rem; color: #94a3b8; margin-top: 0.6rem; display: flex; align-items: center; gap: 5px; }

    /* Empty state */
    .empty-achats { text-align: center; padding: 5rem 2rem; }
    .empty-achats i { font-size: 3.5rem; color: #cbd5e1; display: block; margin-bottom: 1rem; }
    .empty-achats h3 { font-size: 1.3rem; font-weight: 800; color: #334155; margin-bottom: 0.5rem; }
    .empty-achats p { color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .btn-browse { display: inline-flex; align-items: center; gap: 8px; padding: 0.75rem 1.75rem; background: #0f172a; color: white; border-radius: 12px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; }
    .btn-browse:hover { background: #1e293b; color: white; }
</style>
@endpush

@section('content')
<div class="store-wrap espace-client">

    {{-- Header --}}
    <div class="ec-header">
        <div style="display:flex;align-items:center;gap:1rem;">
            <div class="ec-avatar">{{ strtoupper(substr($client->nom ?? $client->email, 0, 1)) }}</div>
            <div class="ec-info">
                <h2>{{ $client->nom ?? 'Mon espace' }}</h2>
                <p><i class="fas fa-envelope" style="font-size:0.8rem;"></i> {{ $client->email }}</p>
            </div>
        </div>
        <div class="ec-stats">
            <div class="ec-stat">
                <div class="ec-stat-val">{{ $achats->total() }}</div>
                <div class="ec-stat-lbl">Achat{{ $achats->total() > 1 ? 's' : '' }}</div>
            </div>
            <div class="ec-stat">
                <div class="ec-stat-val">{{ $achats->sum(fn($a) => $a->telechargements()->count()) }}</div>
                <div class="ec-stat-lbl">Téléchargements</div>
            </div>
        </div>
        <form method="POST" action="{{ route('client.acces.deconnexion') }}">
            @csrf
            <button type="submit" class="btn-deconnexion">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>
        </form>
    </div>

    {{-- Section titre --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <h3 style="font-weight:800;font-size:1.15rem;color:#0f172a;margin:0;">
            <i class="fas fa-shopping-bag" style="color:#2563eb;margin-right:0.5rem;"></i>
            Mes produits achetés
        </h3>
        @if($achats->count() > 0)
        <span style="background:#f1f5f9;color:#64748b;font-size:0.8rem;font-weight:600;padding:0.3rem 0.8rem;border-radius:20px;">
            {{ $achats->total() }} achat{{ $achats->total() > 1 ? 's' : '' }}
        </span>
        @endif
    </div>

    @if($achats->count() > 0)
    <div class="achats-grid">
        @foreach($achats as $achat)
        @php $dlCount = $achat->telechargements()->count(); @endphp
        <div class="achat-card">
            <div class="achat-img">
                @if($achat->produit->image)
                    <img src="{{ asset('storage/' . $achat->produit->image) }}" alt="{{ $achat->produit->nom }}">
                @else
                    <i class="fas fa-file-download achat-img-ph"></i>
                @endif
                <span class="achat-status"><i class="fas fa-check"></i> Payé</span>
            </div>
            <div class="achat-body">
                <div class="achat-nom" title="{{ $achat->produit->nom }}">{{ $achat->produit->nom }}</div>
                <div class="achat-date">
                    <i class="fas fa-calendar-alt"></i>
                    Acheté le {{ $achat->created_at->format('d/m/Y à H:i') }}
                </div>
                <div class="achat-meta">
                    <div class="achat-prix">{{ number_format($achat->produit->prix, 0, ',', ' ') }} FCFA</div>
                    <div class="achat-actions">
                        <a href="{{ route('client.mes-achats.show', $achat) }}" class="btn-detail">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        <a href="{{ route('client.telechargement', $achat) }}" class="btn-dl">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    </div>
                </div>
                @if($dlCount > 0)
                <div class="dl-count">
                    <i class="fas fa-download"></i>
                    Téléchargé {{ $dlCount }} fois
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;display:flex;justify-content:center;">
        {{ $achats->links() }}
    </div>

    @else
    <div class="empty-achats">
        <i class="fas fa-shopping-bag"></i>
        <h3>Aucun achat pour le moment</h3>
        <p>Vous n'avez pas encore effectué d'achat dans cette boutique.</p>
        <a href="{{ route('boutique.accueil') }}" class="btn-browse">
            <i class="fas fa-store"></i> Découvrir les produits
        </a>
    </div>
    @endif

</div>
@endsection
