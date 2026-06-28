@extends('layouts.boutique')

@section('title', 'Mes achats')

@push('styles')
<style>
.espace-wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2.5rem 1.25rem 5rem;
}

/* ── Header ── */
.ec-header {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1.75rem 2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.ec-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 0% 50%, rgba(124,58,237,0.08) 0%, transparent 60%);
    pointer-events: none;
}
.ec-left { display: flex; align-items: center; gap: 1.1rem; position: relative; }
.ec-avatar {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #a855f7);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.4rem; font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 0 0 4px rgba(124,58,237,0.2);
}
.ec-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.2rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 2px;
}
.ec-email { font-size: 0.8rem; color: var(--text-3); }

.ec-stats {
    display: flex; gap: 2rem;
    position: relative;
}
.ec-stat { text-align: center; }
.ec-stat-val {
    font-size: 1.6rem; font-weight: 900;
    color: var(--text-1); line-height: 1;
    margin-bottom: 3px;
}
.ec-stat-lbl { font-size: 0.72rem; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.06em; }

.btn-logout {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.55rem 1.1rem;
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 10px;
    font-size: 0.82rem; font-weight: 600;
    color: #dc2626;
    cursor: pointer; font-family: inherit;
    transition: all 0.2s; text-decoration: none;
    position: relative;
}
.btn-logout:hover {
    background: rgba(239,68,68,0.15);
    border-color: rgba(239,68,68,0.35);
    color: #dc2626;
}

/* ── Toolbar ── */
.ec-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;
}
.ec-toolbar-title {
    font-size: 0.78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.09em;
    color: var(--text-3);
    display: flex; align-items: center; gap: 8px;
}
.ec-toolbar-title i { color: var(--accent); }
.ec-count-badge {
    display: inline-flex; align-items: center;
    background: rgba(124,58,237,0.12);
    border: 1px solid rgba(124,58,237,0.2);
    border-radius: 20px;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem; font-weight: 700;
    color: var(--accent);
}

/* ── Grid ── */
.achats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}
@media (max-width: 700px) { .achats-grid { grid-template-columns: 1fr; } }

/* ── Achat card ── */
.achat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.25s;
    display: flex;
    flex-direction: column;
}
.achat-card:hover {
    border-color: rgba(124,58,237,0.3);
    transform: translateY(-3px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.25);
}

.achat-img {
    position: relative;
    height: 170px;
    overflow: hidden;
    background: rgba(0,0,0,0.03);
    display: flex; align-items: center; justify-content: center;
}
.achat-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.4s;
}
.achat-card:hover .achat-img img { transform: scale(1.05); }
.achat-img-ph { color: var(--text-3); font-size: 2.5rem; }

.achat-paid-badge {
    position: absolute; top: 10px; right: 10px;
    display: inline-flex; align-items: center; gap: 5px;
    background: #16a34a; color: white;
    font-size: 0.67rem; font-weight: 800;
    padding: 0.25rem 0.6rem; border-radius: 20px;
    letter-spacing: 0.04em;
}

.achat-body { padding: 1.1rem 1.25rem 1.25rem; flex: 1; display: flex; flex-direction: column; }
.achat-nom {
    font-weight: 800; font-size: 0.95rem;
    color: var(--text-1); margin-bottom: 0.25rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.achat-date {
    font-size: 0.77rem; color: var(--text-3);
    display: flex; align-items: center; gap: 5px;
    margin-bottom: 1rem;
}

.achat-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto;
}
.achat-prix { font-size: 1.1rem; font-weight: 900; color: var(--accent); }

.achat-actions { display: flex; gap: 0.5rem; }
.btn-dl {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 0.9rem;
    background: var(--accent); color: white;
    border: none; border-radius: 9px;
    font-size: 0.78rem; font-weight: 700;
    text-decoration: none; transition: all 0.2s;
    box-shadow: 0 3px 10px rgba(124,58,237,0.3);
}
.btn-dl:hover { background: var(--accent-hover); color: white; transform: translateY(-1px); }
.btn-detail {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 0.75rem;
    background: rgba(0,0,0,0.04);
    border: 1px solid var(--border);
    border-radius: 9px;
    font-size: 0.78rem; font-weight: 600;
    color: var(--text-2); text-decoration: none;
    transition: all 0.2s;
}
.btn-detail:hover { background: rgba(0,0,0,0.06); color: var(--text-1); border-color: rgba(0,0,0,0.15); }

.dl-count {
    font-size: 0.72rem; color: var(--text-3);
    display: flex; align-items: center; gap: 4px;
    margin-top: 0.5rem;
}
.dl-count i { color: var(--accent); font-size: 0.65rem; }

/* ── Pagination ── */
.pagination-wrap {
    display: flex; justify-content: center;
    margin-top: 0.5rem;
}

/* ── Empty ── */
.empty-achats {
    text-align: center;
    padding: 5rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
}
.empty-achats-icon {
    width: 80px; height: 80px;
    background: rgba(124,58,237,0.08);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem; color: var(--accent);
}
.empty-achats h3 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.4rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.5rem;
}
.empty-achats p { color: var(--text-3); font-size: 0.875rem; margin-bottom: 1.75rem; }
.btn-browse {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.875rem 1.75rem;
    background: var(--accent); color: white;
    border-radius: 14px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
    box-shadow: 0 4px 18px rgba(124,58,237,0.3);
}
.btn-browse:hover { background: var(--accent-hover); color: white; transform: translateY(-2px); }

@media (max-width: 768px) {
    .ec-header { padding: 1.25rem; }
    .ec-stats { gap: 1.25rem; }
    .ec-stat-val { font-size: 1.3rem; }
}
</style>
@endpush

@section('content')
<div class="espace-wrap">

    {{-- ── HEADER ── --}}
    <div class="ec-header">
        <div class="ec-left">
            <div class="ec-avatar">{{ strtoupper(substr($client->nom ?? $client->email, 0, 1)) }}</div>
            <div>
                <div class="ec-name">{{ $client->nom ?? 'Mon espace' }}</div>
                <div class="ec-email"><i class="fas fa-envelope" style="font-size:0.7rem;margin-right:4px;"></i>{{ $client->email }}</div>
            </div>
        </div>

        <div class="ec-stats">
            <div class="ec-stat">
                <div class="ec-stat-val">{{ $achats->total() }}</div>
                <div class="ec-stat-lbl">Achat{{ $achats->total() > 1 ? 's' : '' }}</div>
            </div>
            <div class="ec-stat">
                <div class="ec-stat-val">{{ $achats->sum(fn($a) => $a->telechargements()->count()) }}</div>
                <div class="ec-stat-lbl">Télécharg.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('client.acces.deconnexion') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>
        </form>
    </div>

    {{-- ── TOOLBAR ── --}}
    <div class="ec-toolbar">
        <div class="ec-toolbar-title">
            <i class="fas fa-shopping-bag"></i> Mes produits achetés
        </div>
        @if($achats->count() > 0)
        <span class="ec-count-badge">{{ $achats->total() }} achat{{ $achats->total() > 1 ? 's' : '' }}</span>
        @endif
    </div>

    @if($achats->count() > 0)

    <div class="achats-grid">
        @foreach($achats as $achat)
        @php $dlCount = $achat->telechargements()->count(); @endphp
        <div class="achat-card">
            <div class="achat-img">
                @if($achat->produit->image)
                    <img src="{{ $achat->produit->image_url }}" alt="{{ $achat->produit->nom }}">
                @else
                    <i class="fas fa-file-download achat-img-ph"></i>
                @endif
                <span class="achat-paid-badge"><i class="fas fa-check"></i> Payé</span>
            </div>

            <div class="achat-body">
                <div class="achat-nom" title="{{ $achat->produit->nom }}">{{ $achat->produit->nom }}</div>
                <div class="achat-date">
                    <i class="fas fa-calendar-alt"></i>
                    Acheté le {{ $achat->created_at->format('d/m/Y à H:i') }}
                </div>

                <div class="achat-footer">
                    <div class="achat-prix">{{ number_format($achat->produit->prix, 0, ',', ' ') }} FCFA</div>
                    @php
                        $abo = ($abonnements ?? collect())->get($achat->produit_id);
                        $cleLic = $achat->produit->estLicence() ? ($clesLicence ?? collect())->get($achat->id) : null;
                    @endphp
                    <div class="achat-actions">
                        <a href="{{ route('client.mes-achats.show', $achat) }}" class="btn-detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($achat->produit->estLicence())
                            @if($cleLic)
                            <button type="button" class="btn-dl" style="background:#7c3aed;border:none;cursor:pointer;" onclick="copierCle(this, @js($cleLic->cle))">
                                <i class="fas fa-key"></i> Copier la clé
                            </button>
                            @else
                            <span class="btn-dl" style="background:#9ca3af;cursor:default;"><i class="fas fa-clock"></i> Clé en attente</span>
                            @endif
                        @elseif($achat->produit->estAbonnement())
                            @if($abo && $abo->estActif())
                                <a href="{{ route('client.formation.show', $achat->produit) }}" class="btn-dl" style="background:#4f46e5;">
                                    <i class="fas fa-graduation-cap"></i> Accéder
                                </a>
                            @else
                                <a href="{{ route('boutique.checkout.produit', ['id' => $achat->produit_id]) }}" class="btn-dl" style="background:#dc2626;">
                                    <i class="fas fa-redo"></i> Renouveler
                                </a>
                            @endif
                        @elseif($achat->produit->estFormation())
                            <a href="{{ route('client.formation.show', $achat->produit) }}" class="btn-dl" style="background:#4f46e5;">
                                <i class="fas fa-graduation-cap"></i> Accéder à la formation
                            </a>
                        @elseif($achat->produit->estCommunaute())
                            <a href="{{ route('client.communaute.show', $achat->produit) }}" class="btn-dl" style="background:#e11d48;">
                                <i class="fas fa-users"></i> Accéder à la communauté
                            </a>
                        @elseif($achat->produit->estCoaching())
                            <a href="{{ route('client.coaching.reserver', $achat->produit) }}" class="btn-dl" style="background:#db2777;">
                                <i class="fas fa-video"></i> Réserver ma séance
                            </a>
                        @elseif($achat->produit->estBundle())
                            <span class="btn-dl" style="background:#0d9488;cursor:default;">
                                <i class="fas fa-layer-group"></i> Pack ({{ $achat->produit->produitsInclus->count() }} produits)
                            </span>
                        @else
                            <a href="{{ route('client.telechargement', $achat) }}" class="btn-dl">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        @endif
                    </div>
                    @if($achat->produit->estAbonnement() && $abo)
                        <div style="font-size:0.72rem;margin-top:6px;{{ $abo->estActif() ? 'color:#16a34a;' : 'color:#dc2626;' }}">
                            <i class="fas fa-{{ $abo->estActif() ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $abo->estActif() ? 'Actif jusqu’au ' . optional($abo->date_fin)->format('d/m/Y') : 'Abonnement expiré' }}
                        </div>
                    @endif
                    @if($cleLic)
                        <div style="margin-top:6px;font-family:monospace;font-size:0.78rem;color:#111827;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:8px;padding:6px 10px;word-break:break-all;">
                            🔑 {{ $cleLic->cle }}
                        </div>
                    @endif
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

    <div class="pagination-wrap">
        {{ $achats->links() }}
    </div>

    @else

    <div class="empty-achats">
        <div class="empty-achats-icon"><i class="fas fa-shopping-bag"></i></div>
        <h3>Aucun achat pour le moment</h3>
        <p>Vous n'avez pas encore effectué d'achat dans cette boutique.</p>
        <a href="{{ route('boutique.accueil') }}" class="btn-browse">
            <i class="fas fa-store"></i> Découvrir les produits
        </a>
    </div>

    @endif

</div>
@endsection

@push('scripts')
<script>
function copierCle(btn, cle){
    navigator.clipboard.writeText(cle).then(function(){
        var span = btn.querySelector('i').nextSibling;
        var old = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copiée !';
        setTimeout(function(){ btn.innerHTML = old; }, 1500);
    });
}
</script>
@endpush
