@extends('layouts.admin')
@section('title', 'Ventes')

@push('styles')
<style>
/* ══ PAGE VENTES — Chariow Style ══ */
.page-wrap { background: #fff; border-radius: 16px; overflow: hidden; min-height: 80vh; }

/* Toolbar */
.v-toolbar {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
}
.v-search { flex: 1; position: relative; }
.v-search i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: .8rem; pointer-events: none;
}
.v-search input {
    width: 100%; height: 38px; padding: 0 12px 0 34px;
    font-size: .84rem; border: 1px solid #e5e7eb; border-radius: 10px;
    outline: none; background: #f9fafb; color: #111827; transition: border-color .15s, background .15s;
}
.v-search input:focus { border-color: #6d28d9; background: #fff; }
.v-search input::placeholder { color: #9ca3af; }
.v-filter-btn {
    width: 38px; height: 38px; border: 1px solid #e5e7eb; border-radius: 10px;
    background: #f9fafb; color: #6b7280; font-size: .82rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; transition: all .15s;
}
.v-filter-btn:hover { border-color: #374151; color: #111827; background: #fff; }
.btn-revenus {
    height: 38px; padding: 0 16px; font-size: .84rem; font-weight: 600;
    background: #f59e0b; color: #fff; border: none; border-radius: 10px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
    white-space: nowrap; flex-shrink: 0; transition: background .15s;
}
.btn-revenus:hover { background: #d97706; color: #fff; }
.btn-sync {
    height: 38px; padding: 0 14px; font-size: .83rem; font-weight: 600;
    background: #fff; color: #d97706; border: 1px solid #fcd34d; border-radius: 10px;
    display: inline-flex; align-items: center; gap: 6px;
    white-space: nowrap; flex-shrink: 0; cursor: pointer; transition: all .15s;
}
.btn-sync:hover { background: #fffbeb; border-color: #f59e0b; }

/* Filter panel */
.v-filter-panel {
    background: #fafafa; border-bottom: 1px solid #f1f5f9;
    padding: .875rem 20px; display: none;
}
.v-filter-panel.open { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
.v-filter-panel select, .v-filter-panel input[type=date] {
    height: 36px; font-size: .82rem; border: 1px solid #e5e7eb;
    border-radius: 8px; padding: 0 10px; color: #374151; background: #fff;
    outline: none;
}
.v-filter-panel .btn-apply {
    height: 36px; padding: 0 14px; font-size: .82rem; font-weight: 600;
    background: #111827; color: #fff; border: none; border-radius: 8px; cursor: pointer;
}

/* Tabs */
.v-tabs {
    display: flex; gap: 4px; padding: 12px 20px 0;
    border-bottom: 1px solid #f1f5f9;
}
.v-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; font-size: .83rem; font-weight: 500;
    color: #6b7280; border: none; background: none; cursor: pointer;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    text-decoration: none; transition: all .15s; white-space: nowrap;
}
.v-tab:hover { color: #111827; }
.v-tab.active { color: #111827; font-weight: 700; border-bottom-color: #111827; }
.v-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* Table */
.v-table { width: 100%; border-collapse: collapse; }
.v-table thead th {
    padding: 10px 16px; font-size: .71rem; font-weight: 700;
    color: #9ca3af; text-transform: uppercase; letter-spacing: .06em;
    background: #fafafa; white-space: nowrap;
    border-bottom: 1px solid #f1f5f9;
}
.v-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background .1s; }
.v-table tbody tr:last-child { border-bottom: none; }
.v-table tbody tr:hover { background: #fafafa; }
.v-table td { padding: 12px 16px; vertical-align: middle; font-size: .84rem; color: #374151; }

/* Produit cell */
.v-prod { display: flex; align-items: center; gap: 10px; }
.v-thumb {
    width: 36px; height: 36px; border-radius: 8px;
    object-fit: cover; flex-shrink: 0;
}
.v-thumb-empty {
    width: 36px; height: 36px; border-radius: 8px;
    background: #f3f4f6; display: flex; align-items: center;
    justify-content: center; color: #d1d5db; font-size: .8rem; flex-shrink: 0;
}
.v-prod-name { font-weight: 600; color: #111827; font-size: .84rem; line-height: 1.2; }
.v-prod-ref  { font-size: .7rem; color: #9ca3af; font-family: monospace; }

/* Client cell */
.v-client-name { font-weight: 600; color: #111827; font-size: .84rem; }
.v-client-email { font-size: .72rem; color: #9ca3af; }

/* Status */
.v-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .73rem; font-weight: 600; white-space: nowrap;
}
.v-badge.success  { background: #ecfdf5; color: #059669; }
.v-badge.pending  { background: #fffbeb; color: #d97706; }
.v-badge.failed   { background: #fef2f2; color: #dc2626; }
.v-badge.abandoned{ background: #f3f4f6; color: #6b7280; }
.v-dot-sm { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

/* Sync button per row */
.btn-row-sync {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1px solid #fcd34d; background: #fff; color: #d97706;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer; transition: all .15s;
}
.btn-row-sync:hover { background: #fffbeb; }

/* View btn */
.btn-view {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .72rem; text-decoration: none; transition: all .15s;
}
.btn-view:hover { background: #111827; color: #fff; border-color: #111827; }

/* Empty */
.v-empty { text-align: center; padding: 4rem 1rem; color: #9ca3af; }
.v-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; color: #e5e7eb; }

/* Pagination */
.v-pages { padding: 1rem; border-top: 1px solid #f1f5f9; }
.pagination { justify-content: center; gap: 4px; margin: 0; }
.pagination .page-link {
    border-radius: 8px !important; border: 1px solid #e5e7eb;
    color: #374151; font-size: .8rem; padding: 5px 11px; background: #fff;
}
.pagination .page-item.active .page-link { background: #111827; border-color: #111827; color: #fff; }
.pagination .page-link:hover { background: #f9fafb; }
</style>
@endpush

@section('content')

<div class="page-wrap">

    {{-- Toolbar --}}
    <form method="GET" id="vsf">
        <div class="v-toolbar">
            <div class="v-search">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Rechercher une vente, un client..."
                       onchange="document.getElementById('vsf').submit()">
                @if(request('statut'))<input type="hidden" name="statut" value="{{ request('statut') }}">@endif
                @if(request('date_debut'))<input type="hidden" name="date_debut" value="{{ request('date_debut') }}">@endif
                @if(request('date_fin'))<input type="hidden" name="date_fin" value="{{ request('date_fin') }}">@endif
            </div>
            <button type="button" class="v-filter-btn" onclick="toggleFilter()" title="Filtres avancés">
                <i class="fas fa-sliders-h"></i>
            </button>
            @if($stats['en_attente'] > 0)
            <button type="button" id="btn-sync" class="btn-sync" onclick="syncToutesMoneroo()">
                <i class="fas fa-sync-alt"></i> Sync ({{ $stats['en_attente'] }})
            </button>
            @endif
            <a href="{{ route('admin.exports.transactions') }}" class="btn-revenus">
                <i class="fas fa-chart-bar"></i> Mes revenus
            </a>
        </div>
    </form>

    {{-- Filter panel --}}
    <div class="v-filter-panel" id="fp">
        <form method="GET" style="display:contents;">
            @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
            <select name="statut">
                <option value="">Tous les statuts</option>
                <option value="reussi"     {{ request('statut') == 'reussi'     ? 'selected' : '' }}>Terminé</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="echoue"     {{ request('statut') == 'echoue'     ? 'selected' : '' }}>Échouée</option>
                <option value="abandonne"  {{ request('statut') == 'abandonne'  ? 'selected' : '' }}>Abandonné</option>
            </select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" placeholder="Du">
            <input type="date" name="date_fin"   value="{{ request('date_fin') }}"   placeholder="Au">
            <button type="submit" class="btn-apply">Appliquer</button>
            @if(request('statut') || request('date_debut') || request('date_fin'))
                <a href="{{ route('admin.transactions.index') }}" style="font-size:.82rem;color:#6b7280;align-self:center;">Réinitialiser</a>
            @endif
        </form>
    </div>

    {{-- Tabs --}}
    @php $cs = request('statut', ''); @endphp
    <div class="v-tabs">
        <a href="{{ route('admin.transactions.index', array_merge(request()->except('statut','page'), [])) }}"
           class="v-tab {{ $cs === '' ? 'active' : '' }}">
            Tout <span style="font-size:.7rem;background:#f1f5f9;color:#6b7280;padding:1px 7px;border-radius:10px;font-weight:700;margin-left:2px;">{{ $stats['total'] }}</span>
        </a>
        <a href="{{ route('admin.transactions.index', array_merge(request()->except('statut','page'), ['statut'=>'en_attente'])) }}"
           class="v-tab {{ $cs === 'en_attente' ? 'active' : '' }}">
            <span class="v-dot" style="background:#f59e0b;"></span> En attente
        </a>
        <a href="{{ route('admin.transactions.index', array_merge(request()->except('statut','page'), ['statut'=>'reussi'])) }}"
           class="v-tab {{ $cs === 'reussi' ? 'active' : '' }}">
            <span class="v-dot" style="background:#059669;"></span> Terminé
        </a>
        <a href="{{ route('admin.transactions.index', array_merge(request()->except('statut','page'), ['statut'=>'echoue'])) }}"
           class="v-tab {{ $cs === 'echoue' ? 'active' : '' }}">
            <span class="v-dot" style="background:#dc2626;"></span> Échouée
        </a>
        <a href="{{ route('admin.transactions.index', array_merge(request()->except('statut','page'), ['statut'=>'abandonne'])) }}"
           class="v-tab {{ $cs === 'abandonne' ? 'active' : '' }}">
            <span class="v-dot" style="background:#9ca3af;"></span> Abandonné
        </a>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="v-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Client</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($transactions as $t)
            @php
                $premier = $t->achats->first();
                $produit = $premier?->produit;
                $autres  = $t->achats->count() - 1;
            @endphp
            <tr>
                {{-- Produit --}}
                <td>
                    <div class="v-prod">
                        @if($produit?->image_url)
                            <img src="{{ $produit->image_url }}" class="v-thumb" alt="{{ $produit->nom }}">
                        @else
                            <div class="v-thumb-empty"><i class="fas fa-box"></i></div>
                        @endif
                        <div>
                            <div class="v-prod-name">{{ $produit?->nom ?? 'Produit supprimé' }}{{ $autres > 0 ? ' +'.($autres) : '' }}</div>
                            <div class="v-prod-ref">{{ $t->reference }}</div>
                        </div>
                    </div>
                </td>

                {{-- Client --}}
                <td>
                    <div class="v-client-name">{{ $t->client->nom ?? 'Anonyme' }}</div>
                    <div class="v-client-email">{{ $t->client->email ?? '' }}</div>
                </td>

                {{-- Prix --}}
                <td style="font-weight:600;color:#111827;white-space:nowrap;">
                    {{ number_format($t->montant_total, 0, ',', ' ') }}
                    <span style="font-size:.72rem;font-weight:400;color:#9ca3af;">FCFA</span>
                </td>

                {{-- Statut --}}
                <td>
                    @if($t->statut === 'reussi')
                        <span class="v-badge success"><span class="v-dot-sm" style="background:#059669;"></span> Terminé</span>
                    @elseif($t->statut === 'en_attente')
                        <span class="v-badge pending"><span class="v-dot-sm" style="background:#f59e0b;"></span> En attente</span>
                    @elseif($t->statut === 'echoue')
                        <span class="v-badge failed"><span class="v-dot-sm" style="background:#dc2626;"></span> Échouée</span>
                    @else
                        <span class="v-badge abandoned"><span class="v-dot-sm" style="background:#9ca3af;"></span> Abandonné</span>
                    @endif
                </td>

                {{-- Date --}}
                <td style="color:#6b7280;font-size:.78rem;white-space:nowrap;">
                    {{ $t->created_at->format('d M Y') }}
                </td>

                {{-- Actions --}}
                <td>
                    <div style="display:flex;align-items:center;gap:5px;">
                        @if($t->statut === 'en_attente' && $t->reference_paiement)
                        <button class="btn-row-sync btn-sync-une"
                                data-url="{{ route('admin.transactions.sync-une', $t) }}"
                                title="Sync Moneroo">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        @endif
                        <a href="{{ route('admin.transactions.show', $t) }}" class="btn-view" title="Détail">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="v-empty">
                        <i class="fas fa-receipt"></i>
                        Aucune vente trouvée
                        @if(request()->hasAny(['q','statut','date_debut','date_fin']))
                            <br><a href="{{ route('admin.transactions.index') }}" style="font-size:.82rem;color:#6b7280;margin-top:.5rem;display:inline-block;">Effacer les filtres</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="v-pages">{{ $transactions->withQueryString()->links() }}</div>
    @endif

</div>

@endsection

@push('scripts')
<script>
function toggleFilter() {
    document.getElementById('fp').classList.toggle('open');
}
@if(request('statut') || request('date_debut') || request('date_fin'))
document.getElementById('fp').classList.add('open');
@endif

async function syncToutesMoneroo() {
    const btn = document.getElementById('btn-sync');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sync...';
    try {
        const res  = await fetch('{{ route('admin.transactions.sync-moneroo') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.updated > 0) {
            btn.innerHTML = '<i class="fas fa-check"></i> ' + data.updated + ' mis à jour';
            setTimeout(() => location.reload(), 1500);
        } else {
            btn.innerHTML = '<i class="fas fa-info-circle"></i> Aucun changement';
            setTimeout(() => btn.disabled = false, 3000);
        }
    } catch(e) {
        btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Erreur';
        btn.disabled = false;
    }
}

document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.btn-sync-une');
    if (!btn) return;
    btn.disabled = true;
    const icon = btn.querySelector('i');
    if (icon) { icon.className = 'fas fa-spinner fa-spin'; }
    try {
        const res  = await fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.statut) {
            const row = btn.closest('tr');
            const cell = row?.querySelector('td:nth-child(4)');
            if (cell) {
                if (data.statut === 'reussi')
                    cell.innerHTML = '<span class="v-badge success"><span class="v-dot-sm" style="background:#059669;"></span> Terminé</span>';
                else if (data.statut === 'echoue')
                    cell.innerHTML = '<span class="v-badge failed"><span class="v-dot-sm" style="background:#dc2626;"></span> Échouée</span>';
            }
            btn.remove();
        } else {
            if (icon) icon.className = 'fas fa-sync-alt';
            btn.disabled = false;
        }
    } catch(e) {
        if (icon) icon.className = 'fas fa-sync-alt';
        btn.disabled = false;
    }
});
</script>
@endpush
