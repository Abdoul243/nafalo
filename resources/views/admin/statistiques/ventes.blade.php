@extends('layouts.admin')
@section('title', 'Statistiques des ventes')

@push('styles')
<style>
.stat-card { background:white; border-radius:14px; border:1px solid #f0f0f0; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:1.25rem; }
.stat-card-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:0.75rem; }
.stat-value { font-size:1.6rem; font-weight:900; color:#111; line-height:1; }
.stat-label { font-size:0.78rem; color:#999; margin-top:4px; }

.top-item { display:flex; align-items:center; gap:10px; padding:0.6rem 0; border-bottom:1px solid #f3f4f6; }
.top-item:last-child { border:none; }
.top-rank { width:24px; height:24px; border-radius:50%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:800; color:#64748b; flex-shrink:0; }
.top-rank.gold   { background:#fef9c3; color:#a16207; }
.top-rank.silver { background:#f1f5f9; color:#475569; }
.top-rank.bronze { background:#fef3c7; color:#92400e; }
.top-name { flex:1; font-size:0.82rem; font-weight:600; color:#111; }
.top-badge { font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.pm-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0; }
.bar-bg { height:6px; background:#f3f4f6; border-radius:10px; overflow:hidden; margin-top:4px; }
.bar-fill { height:100%; border-radius:10px; background:#0f172a; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Statistiques des ventes</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Analyse de vos performances commerciales</p>
    </div>
</div>

{{-- Filtre dates --}}
<div class="card mb-4" style="border-radius:14px;">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">Date début</label>
                <input type="date" class="form-control" name="date_debut" value="{{ $dateDebut }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:0.82rem;">Date fin</label>
                <input type="date" class="form-control" name="date_fin" value="{{ $dateFin }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn w-100" style="background:#0f172a;color:#fff;border-radius:10px;font-weight:600;font-size:0.875rem;">
                    <i class="fas fa-chart-line me-1"></i> Actualiser
                </button>
            </div>
        </form>
    </div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#f1f5f9;color:#0f172a;"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value">{{ $stats['total_ventes'] }}</div>
            <div class="stat-label">Ventes réussies</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-chart-line"></i></div>
            <div class="stat-value" style="font-size:1.1rem;">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }}</div>
            <div class="stat-label">CA brut (FCFA)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fdf4ff;color:#9333ea;"><i class="fas fa-wallet"></i></div>
            <div class="stat-value" style="font-size:1.1rem;">{{ number_format($stats['chiffre_affaires'] * 0.95, 0, ',', ' ') }}</div>
            <div class="stat-label">Vos gains nets (FCFA)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-receipt"></i></div>
            <div class="stat-value" style="font-size:1.1rem;">{{ number_format($stats['panier_moyen'], 0, ',', ' ') }}</div>
            <div class="stat-label">Panier moyen (FCFA)</div>
        </div>
    </div>
</div>

{{-- Graphique + Top produits --}}
<div class="row g-4 mb-4">
    {{-- Graphique évolution --}}
    <div class="col-md-8">
        <div class="card" style="border-radius:14px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">?? Évolution des ventes</h6>
            </div>
            <div class="card-body">
                <canvas id="ventesChart" height="260"></canvas>
            </div>
        </div>
    </div>

    {{-- Top produits --}}
    <div class="col-md-4">
        <div class="card h-100" style="border-radius:14px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">?? Top produits</h6>
            </div>
            <div class="card-body px-4 py-3">
                @forelse($topProduits as $i => $item)
                @php $max = $topProduits->first()?->total_ventes ?: 1; @endphp
                <div class="top-item">
                    <div class="top-rank {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="top-name text-truncate">{{ $item->produit?->nom ?? 'Produit supprimé' }}</div>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ round($item->total_ventes / $max * 100) }}%;background:#0f172a;"></div>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:0.8rem;font-weight:800;color:#111;">{{ $item->total_ventes }}</div>
                        <div style="font-size:0.68rem;color:#94a3b8;">ventes</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:0.85rem;">Aucune vente sur cette période</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Moyens de paiement + Top pays --}}
<div class="row g-4">
    {{-- Moyens de paiement --}}
    <div class="col-md-6">
        <div class="card" style="border-radius:14px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">?? Moyens de paiement utilisés</h6>
            </div>
            <div class="card-body px-4 py-3">
                @php
                    $pmLabels = [
                        'wave_ci'    => ['label'=>'Wave CI',        'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/wave.svg',         'color'=>'#00B9F1','bg'=>'rgba(0,185,241,0.1)'],
                        'wave_sn'    => ['label'=>'Wave SN',        'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/wave.svg',         'color'=>'#00B9F1','bg'=>'rgba(0,185,241,0.1)'],
                        'orange_ci'  => ['label'=>'Orange Money CI','logo'=>'https://dashboard.fedapay.com/storage/channel-logos/orange-money.svg', 'color'=>'#FF6600','bg'=>'rgba(255,102,0,0.1)'],
                        'orange_sn'  => ['label'=>'Orange Money SN','logo'=>'https://dashboard.fedapay.com/storage/channel-logos/orange-money.svg', 'color'=>'#FF6600','bg'=>'rgba(255,102,0,0.1)'],
                        'mtn_ci'     => ['label'=>'MTN MoMo CI',    'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/mtn.svg',          'color'=>'#FFCC00','bg'=>'rgba(255,204,0,0.1)'],
                        'mtn_gh'     => ['label'=>'MTN MoMo GH',    'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/mtn.svg',          'color'=>'#FFCC00','bg'=>'rgba(255,204,0,0.1)'],
                        'mtn_ng'     => ['label'=>'MTN Nigeria',     'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/mtn.svg',          'color'=>'#FFCC00','bg'=>'rgba(255,204,0,0.1)'],
                        'moov_ci'    => ['label'=>'Moov Money CI',  'logo'=>'https://dashboard.fedapay.com/storage/channel-logos/moov.svg',         'color'=>'#003087','bg'=>'rgba(0,48,135,0.1)'],
                        'moneroo'    => ['label'=>'Moneroo',         'logo'=>null,                                                                   'color'=>'#7c3aed','bg'=>'rgba(124,58,237,0.1)'],
                    ];
                    $totalPm = $topMoyensPaiement->sum('total') ?: 1;
                @endphp
                @forelse($topMoyensPaiement as $pm)
                @php
                    $info = $pmLabels[$pm->moyen_paiement] ?? ['label' => ucfirst(str_replace('_', ' ', $pm->moyen_paiement)), 'logo' => null, 'color' => '#64748b', 'bg' => 'rgba(100,116,139,0.1)'];
                    $pct = round($pm->total / $totalPm * 100);
                @endphp
                <div class="top-item">
                    <div class="pm-icon" style="background:{{ $info['bg'] }};width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                        @if($info['logo'])
                            <img src="{{ $info['logo'] }}" alt="{{ $info['label'] }}"
                                 style="max-width:36px;max-height:28px;width:auto;height:auto;object-fit:contain;"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                            <i class="fas fa-credit-card" style="display:none;color:{{ $info['color'] }};font-size:0.9rem;"></i>
                        @else
                            <i class="fas fa-credit-card" style="color:{{ $info['color'] }};font-size:0.9rem;"></i>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.82rem;font-weight:600;color:#111;">{{ $info['label'] }}</div>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $info['color'] }};"></div>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:0.8rem;font-weight:800;color:#111;">{{ $pm->total }} <span style="font-weight:400;color:#94a3b8;font-size:0.7rem;">({{ $pct }}%)</span></div>
                        <div style="font-size:0.68rem;color:#94a3b8;">{{ number_format($pm->ca, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:0.85rem;">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top pays --}}
    <div class="col-md-6">
        <div class="card" style="border-radius:14px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">?? Top pays</h6>
            </div>
            <div class="card-body px-4 py-3">
                @php
                    $paysLabels = [
                        'CI' => ['nom' => 'Côte d\'Ivoire', 'flag' => '????'],
                        'SN' => ['nom' => 'Sénégal',        'flag' => '????'],
                        'ML' => ['nom' => 'Mali',           'flag' => '????'],
                        'BF' => ['nom' => 'Burkina Faso',   'flag' => '????'],
                        'GN' => ['nom' => 'Guinée',         'flag' => '????'],
                        'CM' => ['nom' => 'Cameroun',       'flag' => '????'],
                        'NG' => ['nom' => 'Nigeria',        'flag' => '????'],
                        'GH' => ['nom' => 'Ghana',          'flag' => '????'],
                        'TG' => ['nom' => 'Togo',           'flag' => '????'],
                        'BJ' => ['nom' => 'Bénin',          'flag' => '????'],
                    ];
                    $totalPays = $topPays->sum('total') ?: 1;
                @endphp
                @forelse($topPays as $i => $pays)
                @php
                    $info = $paysLabels[$pays['code']] ?? ['nom' => $pays['code'], 'flag' => '??'];
                    $pct  = round($pays['total'] / $totalPays * 100);
                @endphp
                <div class="top-item">
                    <div class="top-rank {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.82rem;font-weight:600;color:#111;">{{ $info['flag'] }} {{ $info['nom'] }}</div>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ $pct }}%;background:linear-gradient(90deg,#16a34a,#22c55e);"></div>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:0.8rem;font-weight:800;color:#111;">{{ $pays['total'] }} <span style="font-weight:400;color:#94a3b8;font-size:0.7rem;">({{ $pct }}%)</span></div>
                        <div style="font-size:0.68rem;color:#94a3b8;">{{ number_format($pays['ca'], 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:0.85rem;">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ventesChart').getContext('2d');
    const data = @json($ventesParJour);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Ventes',
                data: data.map(d => d.total),
                borderColor: '#0f172a',
                backgroundColor: 'rgba(15,23,42,0.06)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y',
                pointBackgroundColor: '#0f172a',
                pointRadius: 4,
            }, {
                label: 'CA (FCFA)',
                data: data.map(d => d.montant),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.06)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1',
                pointBackgroundColor: '#16a34a',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                y:  { beginAtZero: true, position: 'left',  title: { display: true, text: 'Ventes' } },
                y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'CA (FCFA)' }, grid: { drawOnChartArea: false } }
            }
        }
    });
});
</script>
@endpush

