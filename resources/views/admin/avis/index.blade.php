@extends('layouts.admin')

@section('title', 'Modération des avis')

@push('styles')
<style>
    .avis-header { margin-bottom:2rem; }
    .avis-header h1 { font-size:1.75rem; font-weight:800; color:#0f172a; margin:0; }
    .avis-header p  { color:#64748b; margin:0.25rem 0 0; font-size:0.9rem; }

    /* Stat pills */
    .avis-stats { display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem; margin-bottom:1.25rem; }
    .avis-stat { background:white; border-radius:14px; border:1px solid #e2e8f0; padding:1rem; display:flex; align-items:center; gap:0.875rem; }
    .avis-stat-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .avis-stat-val  { font-size:1.4rem; font-weight:900; color:#0f172a; line-height:1; }
    .avis-stat-lbl  { font-size:0.75rem; color:#94a3b8; font-weight:500; margin-top:2px; }
    @media(min-width:640px) { .avis-stats { grid-template-columns:repeat(4,1fr); } }

    /* Filters */
    .avis-filters { background:white; border-radius:14px; border:1px solid #e2e8f0; padding:1rem; margin-bottom:1.25rem; display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end; }
    .filter-group { display:flex; flex-direction:column; gap:0.35rem; }
    .filter-group label { font-size:0.78rem; font-weight:600; color:#374151; }
    .filter-group select { padding:0.55rem 1rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; font-family:inherit; outline:none; background:#fafafa; cursor:pointer; }
    .filter-group select:focus { border-color:#2563eb; background:white; }
    .btn-filter { padding:0.6rem 1.5rem; background:#2563eb; color:white; border:none; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; font-family:inherit; }
    .btn-reset  { padding:0.6rem 1.2rem; background:white; color:#64748b; border:1.5px solid #e2e8f0; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; font-family:inherit; text-decoration:none; display:inline-flex; align-items:center; }

    /* Cards */
    .avis-grid { display:flex; flex-direction:column; gap:0.875rem; }
    .avis-card { background:white; border-radius:14px; border:1px solid #e2e8f0; padding:1rem; display:flex; flex-direction:column; gap:0.875rem; transition:box-shadow 0.2s; }
    .avis-card:hover { box-shadow:0 4px 18px rgba(0,0,0,0.06); }
    .avis-card.masque { opacity:0.65; border-style:dashed; }
    .avis-card-top { display:flex; gap:0.875rem; align-items:flex-start; }

    .avis-avatar { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#7c3aed); display:flex; align-items:center; justify-content:center; color:white; font-weight:800; font-size:0.9rem; flex-shrink:0; }
    .avis-body { flex:1; min-width:0; }
    .avis-meta { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.4rem; }
    .avis-author { font-weight:700; font-size:0.875rem; color:#0f172a; }
    .avis-email  { font-size:0.75rem; color:#94a3b8; }
    .avis-date   { font-size:0.72rem; color:#94a3b8; }
    .avis-stars  { display:flex; gap:2px; }
    .avis-stars i { color:#f59e0b; font-size:0.8rem; }
    .avis-stars i.empty { color:#e2e8f0; }
    .avis-produit { display:inline-flex; align-items:center; gap:6px; background:#f1f5f9; color:#475569; font-size:0.75rem; font-weight:600; padding:0.2rem 0.6rem; border-radius:20px; margin-bottom:0.5rem; text-decoration:none; }
    .avis-produit:hover { background:#e2e8f0; color:#1e293b; }
    .avis-commentaire { font-size:0.85rem; color:#374151; line-height:1.6; }
    .avis-badge { display:inline-flex; align-items:center; gap:5px; font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:20px; }
    .badge-visible { background:#f0fdf4; color:#166534; }
    .badge-masque  { background:#f8fafc; color:#94a3b8; }

    .avis-actions { display:flex; flex-direction:row; gap:0.5rem; flex-wrap:wrap; }
    .btn-toggle { display:inline-flex; align-items:center; gap:6px; padding:0.45rem 0.9rem; border-radius:9px; font-size:0.8rem; font-weight:600; border:none; cursor:pointer; font-family:inherit; transition:all 0.2s; }
    .btn-toggle.visible  { background:#f0fdf4; color:#166534; }
    .btn-toggle.visible:hover  { background:#dcfce7; }
    .btn-toggle.masque   { background:#f8fafc; color:#64748b; }
    .btn-toggle.masque:hover   { background:#e2e8f0; }
    .btn-del { display:inline-flex; align-items:center; gap:6px; padding:0.45rem 0.9rem; border-radius:9px; font-size:0.8rem; font-weight:600; background:#fff1f2; color:#ef4444; border:none; cursor:pointer; font-family:inherit; transition:all 0.2s; }
    .btn-del:hover { background:#fee2e2; }

    .empty-state { text-align:center; padding:4rem 2rem; color:#94a3b8; }
    .empty-state i { font-size:3rem; margin-bottom:1rem; display:block; }

    /* Mobile: filtres en pleine largeur */
    @media (max-width: 480px) {
        .avis-filters { flex-direction: column; }
        .filter-group { width: 100%; }
        .filter-group select { width: 100%; }
        .avis-filters > div { width: 100%; }
        .btn-filter, .btn-reset { width: 100%; justify-content: center; }
        .avis-header h1 { font-size: 1.3rem; }
    }
</style>
@endpush

@section('content')

<div class="avis-header">
    <h1>⭐ Modération des avis</h1>
    <p>Gérez la visibilité des avis laissés par vos clients</p>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:0.9rem 1.2rem;border-radius:12px;margin-bottom:1.25rem;color:#166534;font-size:0.875rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="avis-stats">
    <div class="avis-stat">
        <div class="avis-stat-icon" style="background:#eff6ff;"><i class="fas fa-star" style="color:#2563eb;"></i></div>
        <div>
            <div class="avis-stat-val">{{ $stats['total'] }}</div>
            <div class="avis-stat-lbl">Total des avis</div>
        </div>
    </div>
    <div class="avis-stat">
        <div class="avis-stat-icon" style="background:#f0fdf4;"><i class="fas fa-eye" style="color:#22c55e;"></i></div>
        <div>
            <div class="avis-stat-val">{{ $stats['visibles'] }}</div>
            <div class="avis-stat-lbl">Avis visibles</div>
        </div>
    </div>
    <div class="avis-stat">
        <div class="avis-stat-icon" style="background:#f8fafc;"><i class="fas fa-eye-slash" style="color:#94a3b8;"></i></div>
        <div>
            <div class="avis-stat-val">{{ $stats['masques'] }}</div>
            <div class="avis-stat-lbl">Avis masqués</div>
        </div>
    </div>
    <div class="avis-stat">
        <div class="avis-stat-icon" style="background:#fffbeb;"><i class="fas fa-star-half-alt" style="color:#f59e0b;"></i></div>
        <div>
            <div class="avis-stat-val">{{ $stats['moyenne'] }}<span style="font-size:0.9rem;color:#94a3b8;">/5</span></div>
            <div class="avis-stat-lbl">Note moyenne</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="avis-filters">
    <form method="GET" style="display:contents;">
        <div class="filter-group">
            <label>Note</label>
            <select name="note">
                <option value="">⭐ Toutes les notes</option>
                @for($n = 5; $n >= 1; $n--)
                <option value="{{ $n }}" {{ request('note') == $n ? 'selected' : '' }}>
                    {{ str_repeat('★', $n) }}{{ str_repeat('☆', 5-$n) }} {{ $n }} étoile{{ $n > 1 ? 's' : '' }}
                </option>
                @endfor
            </select>
        </div>
        <div class="filter-group">
            <label>Statut</label>
            <select name="est_visible">
                <option value="">Tous les statuts</option>
                <option value="1" {{ request('est_visible') === '1' ? 'selected' : '' }}>Visibles seulement</option>
                <option value="0" {{ request('est_visible') === '0' ? 'selected' : '' }}>Masqués seulement</option>
            </select>
        </div>
        <div style="display:flex;gap:0.5rem;align-items:flex-end;">
            <button type="submit" class="btn-filter"><i class="fas fa-filter me-1"></i> Filtrer</button>
            <a href="{{ route('admin.avis.index') }}" class="btn-reset"><i class="fas fa-times me-1"></i> Réinitialiser</a>
        </div>
    </form>
</div>

{{-- Liste des avis --}}
<div class="avis-grid">
    @forelse($avis as $avi)
    @php
        $initiale = strtoupper(substr($avi->client->nom ?? $avi->client->email ?? 'A', 0, 1));
        $avatarColors = ['#2563eb','#7c3aed','#db2777','#dc2626','#d97706','#059669'];
        $colorIdx = ord($initiale) % count($avatarColors);
    @endphp
    <div class="avis-card {{ $avi->est_visible ? '' : 'masque' }}">
        <div class="avis-card-top">
            <div class="avis-avatar" style="background:{{ $avatarColors[$colorIdx] }};">{{ $initiale }}</div>
            <div class="avis-body">
                <div class="avis-meta">
                    <span class="avis-author">{{ $avi->client->nom ?? 'Anonyme' }}</span>
                    <span class="avis-email">{{ $avi->client->email }}</span>
                    <div class="avis-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $avi->note ? '' : 'empty' }}"></i>
                        @endfor
                    </div>
                    @if($avi->est_visible)
                        <span class="avis-badge badge-visible"><i class="fas fa-eye"></i> Visible</span>
                    @else
                        <span class="avis-badge badge-masque"><i class="fas fa-eye-slash"></i> Masqué</span>
                    @endif
                </div>
                <div class="avis-date" style="margin-bottom:0.4rem;">{{ $avi->created_at->format('d/m/Y') }}</div>
                <a href="{{ route('admin.produits.show', $avi->produit) }}" class="avis-produit">
                    <i class="fas fa-box"></i> {{ Str::limit($avi->produit->nom, 30) }}
                </a>
                @if($avi->commentaire)
                <p class="avis-commentaire">{{ $avi->commentaire }}</p>
                @else
                <p class="avis-commentaire" style="color:#94a3b8;font-style:italic;">Aucun commentaire.</p>
                @endif
            </div>
        </div>
        <div class="avis-actions">
            <button type="button"
                    class="btn-toggle {{ $avi->est_visible ? 'visible' : 'masque' }} toggle-visibilite"
                    data-id="{{ $avi->id }}"
                    data-visible="{{ $avi->est_visible ? '1' : '0' }}">
                @if($avi->est_visible)
                    <i class="fas fa-eye-slash"></i> Masquer
                @else
                    <i class="fas fa-eye"></i> Afficher
                @endif
            </button>
            <button type="button" class="btn-del"
                    data-confirm-message="Supprimer définitivement cet avis ?"
                    data-target-form="del-{{ $avi->id }}">
                <i class="fas fa-trash"></i> Supprimer
            </button>
            <form id="del-{{ $avi->id }}" action="{{ route('admin.avis.destroy', $avi) }}" method="POST" class="d-none">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-star"></i>
        <p style="font-weight:700;color:#475569;font-size:1rem;">Aucun avis trouvé</p>
        <p style="font-size:0.875rem;">Les avis laissés par vos clients apparaîtront ici.</p>
    </div>
    @endforelse
</div>

<div style="margin-top:1.5rem;">
    {{ $avis->withQueryString()->links() }}
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-visibilite').forEach(btn => {
    btn.addEventListener('click', function () {
        const id  = this.dataset.id;
        const btn = this;

        fetch(`{{ url('admin/avis') }}/${id}/toggle-visibilite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            // Refresh page for simplicity (could do DOM update)
            location.reload();
        });
    });
});
</script>
@endpush
