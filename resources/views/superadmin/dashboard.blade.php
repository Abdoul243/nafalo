@extends('superadmin.layouts.superadmin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@push('styles')
<style>
.growth-up { color: #16a34a; font-weight: 700; }
.growth-down { color: #dc2626; font-weight: 700; }
.top-marchand-row { display: flex; align-items: center; gap: 10px; padding: 0.65rem 1rem; border-bottom: 1px solid #f1f5f9; }
.top-rank { width: 24px; height: 24px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; color: #64748b; flex-shrink: 0; }
.top-rank.gold { background: #fef9c3; color: #b45309; }
.top-rank.silver { background: #f1f5f9; color: #475569; }
.top-rank.bronze { background: #fff7ed; color: #c2410c; }
.alert-fraude { background: #fff1f2; border: 1px solid #fecdd3; border-left: 4px solid #ef4444; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
.kyc-pending { background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #f59e0b; border-radius: 12px; padding: 0.875rem 1.25rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between; }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-3">
    <div class="col-md-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label">Marchands</div>
                <div class="icon" style="background:#eff6ff;color:#2563eb;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="value">{{ $stats['total_marchands'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label">Boutiques actives</div>
                <div class="icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="fas fa-store"></i>
                </div>
            </div>
            <div class="value">{{ $stats['boutiques_actives'] }} <span style="font-size:0.9rem;color:#64748b;">/ {{ $stats['total_boutiques'] }}</span></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label">Transactions réussies</div>
                <div class="icon" style="background:#fef9c3;color:#ca8a04;">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
            <div class="value">{{ $stats['transactions_reussies'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label">CA global</div>
                <div class="icon" style="background:#fdf4ff;color:#9333ea;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="value" style="font-size:1.1rem;">
                {{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card" style="border:1.5px solid #fde68a;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label" style="color:#92400e;font-weight:700;">Commissions (5%)</div>
                <div class="icon" style="background:#fef9c3;color:#b45309;">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
            <div class="value" style="font-size:1.1rem;color:#b45309;">
                {{ number_format($stats['total_commissions'], 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card" style="border:1.5px solid #bbf7d0;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="label" style="color:#166534;font-weight:700;">Reversé marchands</div>
                <div class="icon" style="background:#dcfce7;color:#16a34a;">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
            <div class="value" style="font-size:1.1rem;color:#16a34a;">
                {{ number_format($stats['total_marchands_nets'], 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>
</div>

{{-- Bannière commissions --}}
<div style="background:linear-gradient(135deg,#1e3a5c,#2d5a8e);border-radius:14px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="background:rgba(255,255,255,0.15);border-radius:10px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-percentage" style="color:#f59e0b;font-size:1.2rem;"></i>
        </div>
        <div>
            <div style="color:white;font-weight:700;font-size:0.95rem;">Revenus de la plateforme</div>
            <div style="color:#94a3b8;font-size:0.8rem;">Commission de 5% prélevée sur chaque vente réussie</div>
        </div>
    </div>
    <div style="text-align:right;">
        <div style="color:#f59e0b;font-weight:800;font-size:1.4rem;">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} FCFA</div>
        <div style="color:#94a3b8;font-size:0.75rem;">sur {{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA de CA total</div>
    </div>
</div>

{{-- ── Feature 11 : Analytics avancés ───────────────────────────────── --}}
<div class="row g-3 mb-3">
    {{-- Croissance mensuelle --}}
    <div class="col-md-4">
        <div class="stat-card" style="{{ $tauxCroissance >= 0 ? 'border-left:4px solid #22c55e;' : 'border-left:4px solid #ef4444;' }}">
            <div class="label">CA ce mois</div>
            <div class="value" style="font-size:1.2rem;">{{ number_format($caMoisCourant, 0, ',', ' ') }} F</div>
            <div class="mt-1" style="font-size:0.8rem;">
                @if($tauxCroissance >= 0)
                    <span class="growth-up">▲ +{{ $tauxCroissance }}%</span>
                @else
                    <span class="growth-down">▼ {{ $tauxCroissance }}%</span>
                @endif
                vs mois dernier ({{ number_format($caMoisDernier, 0, ',', ' ') }} F)
            </div>
        </div>
    </div>

    {{-- Graphique revenus 12 mois --}}
    <div class="col-md-8">
        <div class="sa-table" style="padding:0;">
            <div class="sa-table-header" style="padding:0.875rem 1.25rem;">
                <span><i class="fas fa-chart-area me-2"></i>Revenus mensuels (12 mois)</span>
            </div>
            <div style="padding:1rem;">
                <canvas id="chartMois" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Top marchands --}}
    <div class="col-md-5">
        <div class="sa-table">
            <div class="sa-table-header">
                <span>🏆 Top marchands</span>
                <a href="{{ route('superadmin.marchands.index') }}" style="font-size:0.78rem;color:#2563eb;text-decoration:none;">Voir tous →</a>
            </div>
            @foreach($topMarchands as $i => $m)
            <div class="top-marchand-row">
                <div class="top-rank {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                    {{ $i + 1 }}
                </div>
                <div class="flex-grow-1">
                    <div style="font-size:0.85rem;font-weight:600;color:#0f172a;">{{ $m->nom }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $m->nb_ventes }} vente(s)</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.85rem;font-weight:700;color:#0f172a;">{{ number_format($m->ca_total, 0, ',', ' ') }} F</div>
                    <div style="font-size:0.7rem;color:#16a34a;">{{ number_format($m->commissions, 0, ',', ' ') }} F commissions</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Alertes fraudes + KYC --}}
    <div class="col-md-7">
        {{-- Fraudes --}}
        <div class="sa-table mb-3">
            <div class="sa-table-header">
                <span>🔍 Alertes fraude <span style="background:#ef4444;color:white;border-radius:10px;padding:1px 8px;font-size:0.72rem;margin-left:4px;">{{ $nbSuspects }}</span></span>
                <a href="{{ route('superadmin.fraudes.index') }}" style="font-size:0.78rem;color:#ef4444;text-decoration:none;">Voir tout →</a>
            </div>
            @if($transactionsSuspectes->isEmpty())
            <div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:0.85rem;">✅ Aucune fraude détectée</div>
            @else
            @foreach($transactionsSuspectes->take(3) as $t)
            <div class="alert-fraude">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <span style="font-weight:700;color:#991b1b;">#{{ $t->id }}</span>
                        <span style="font-size:0.78rem;color:#64748b;margin-left:8px;">{{ $t->boutique?->nom ?? '—' }}</span>
                    </div>
                    <div style="font-weight:700;font-size:0.88rem;">{{ number_format($t->montant_total, 0, ',', ' ') }} F</div>
                </div>
                <div style="font-size:0.75rem;color:#991b1b;margin-top:4px;">⚠️ {{ $t->raison_suspicion }}</div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- KYC en attente --}}
        <div class="sa-table">
            <div class="sa-table-header">
                <span>🪪 KYC en attente <span style="background:#f59e0b;color:white;border-radius:10px;padding:1px 8px;font-size:0.72rem;margin-left:4px;">{{ $kycsEnAttente->count() }}</span></span>
                <a href="{{ route('superadmin.kycs.index') }}" style="font-size:0.78rem;color:#d97706;text-decoration:none;">Traiter →</a>
            </div>
            @if($kycsEnAttente->isEmpty())
            <div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:0.85rem;">✅ Aucun KYC en attente</div>
            @else
            @foreach($kycsEnAttente->take(3) as $kyc)
            <div class="kyc-pending">
                <div>
                    <div style="font-weight:600;font-size:0.85rem;">{{ $kyc->utilisateur?->nom ?? '—' }}</div>
                    <div style="font-size:0.72rem;color:#92400e;">{{ \App\Models\Kyc::TYPES_DOCUMENT[$kyc->type_document] ?? '—' }} · Soumis {{ $kyc->soumis_le?->diffForHumans() }}</div>
                </div>
                <a href="{{ route('superadmin.kycs.show', $kyc) }}" class="btn btn-sm btn-warning" style="border-radius:8px;font-size:0.75rem;">
                    Examiner
                </a>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Dernières transactions --}}
    <div class="col-md-8">
        <div class="sa-table">
            <div class="sa-table-header">
                <span><i class="fas fa-credit-card me-2"></i>Dernières transactions</span>
                <a href="{{ route('superadmin.transactions.index') }}" style="font-size:0.8rem;color:#2563eb;text-decoration:none;">
                    Voir tout →
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Boutique</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieres_transactions as $t)
                    <tr>
                        <td><a href="{{ route('superadmin.transactions.show', $t) }}" style="color:#2563eb;text-decoration:none;font-weight:600;">{{ $t->reference }}</a></td>
                        <td>{{ $t->boutique->nom ?? '—' }}</td>
                        <td>{{ $t->client->email ?? '—' }}</td>
                        <td style="font-weight:700;">{{ number_format($t->montant_total, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($t->statut === 'reussi') <span class="badge-reussi">Réussi</span>
                            @elseif($t->statut === 'en_attente') <span class="badge-attente">En attente</span>
                            @elseif($t->statut === 'echoue') <span class="badge-echoue">Échoué</span>
                            @else <span class="badge-abandonne">Abandonné</span>
                            @endif
                        </td>
                        <td style="color:#64748b;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:2rem;">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Derniers marchands --}}
    <div class="col-md-4">
        <div class="sa-table">
            <div class="sa-table-header">
                <span><i class="fas fa-users me-2"></i>Derniers marchands</span>
                <a href="{{ route('superadmin.marchands.index') }}" style="font-size:0.8rem;color:#2563eb;text-decoration:none;">
                    Voir tout →
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($derniers_marchands as $m)
                    <tr>
                        <td>
                            <a href="{{ route('superadmin.marchands.show', $m) }}" style="color:#0f172a;text-decoration:none;font-weight:600;">
                                {{ $m->nom }}
                            </a>
                            <div style="font-size:0.75rem;color:#94a3b8;">{{ $m->email }}</div>
                        </td>
                        <td style="color:#64748b;font-size:0.8rem;">{{ $m->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;color:#94a3b8;padding:2rem;">Aucun marchand</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    const mois = @json($revenusParMois->pluck('mois'));
    const totaux = @json($revenusParMois->pluck('total'));
    const commissions = @json($revenusParMois->pluck('commissions'));

    // Formater les labels mois (2026-01 → Jan 26)
    const moisFr = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    const labels = mois.map(m => {
        const [y, mo] = m.split('-');
        return moisFr[parseInt(mo) - 1] + ' ' + y.slice(2);
    });

    const ctx = document.getElementById('chartMois');
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'CA total (FCFA)',
                    data: totaux,
                    backgroundColor: 'rgba(37,99,235,0.7)',
                    borderRadius: 6,
                    order: 2,
                },
                {
                    label: 'Commissions Nafalo',
                    data: commissions,
                    backgroundColor: 'rgba(245,158,11,0.7)',
                    borderRadius: 6,
                    order: 1,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + parseInt(ctx.raw).toLocaleString('fr') + ' F'
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        callback: v => (v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v)) + ' F'
                    }
                }
            }
        }
    });
})();
</script>
@endpush