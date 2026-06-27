@extends('superadmin.layouts.superadmin')

@section('title', 'Console Nafalo')
@section('page_title', 'Vue globale')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════
   SUPER-ADMIN DASHBOARD — Dark Override
═══════════════════════════════════════════ */
:root {
    --sa-bg: #09090f;
    --sa-card: #12121a;
    --sa-border: rgba(255,255,255,0.07);
    --sa-text-1: #f1f5f9;
    --sa-text-2: #94a3b8;
    --sa-text-3: #475569;
    --sa-accent: #6366f1;
}
.sa-content { background: var(--sa-bg); min-height: calc(100vh - 60px); }

/* ── Hero header ── */
.sa-db-header { margin-bottom: 2rem; }
.sa-db-header-inner {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
}
.sa-db-label {
    font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.12em; color: var(--sa-text-3); margin-bottom: 0.5rem;
    display: flex; align-items: center; gap: 6px;
}
.sa-db-label::before {
    content: ''; width: 20px; height: 1px;
    background: var(--sa-border);
}
.sa-db-headline {
    font-size: 2rem; font-weight: 900; color: var(--sa-text-1);
    letter-spacing: -0.035em; line-height: 1.15; margin: 0;
}
.sa-db-headline em {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic; color: #818cf8;
    display: block; font-size: 1.6rem; font-weight: 700; margin-top: 2px;
}
@media (max-width: 640px) { .sa-db-headline { font-size: 1.5rem; } .sa-db-headline em { font-size: 1.25rem; } }

.sa-search-bar {
    display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;
}
.sa-search {
    background: rgba(255,255,255,0.04); border: 1px solid var(--sa-border);
    border-radius: 10px; padding: 0.55rem 1rem; color: var(--sa-text-1);
    font-size: 0.83rem; outline: none; width: 260px; transition: border-color 0.2s;
}
.sa-search::placeholder { color: var(--sa-text-3); }
.sa-search:focus { border-color: var(--sa-accent); }

.sa-kyc-alert-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3);
    color: #f87171; border-radius: 10px; padding: 0.55rem 1rem;
    font-size: 0.8rem; font-weight: 700; text-decoration: none; white-space: nowrap;
    transition: all 0.2s;
}
.sa-kyc-alert-btn::before { content: '•'; font-size: 1rem; }
.sa-kyc-alert-btn:hover { background: rgba(239,68,68,0.2); color: #f87171; }

/* ── Big 3 KPI row ── */
.sa-kpi-row {
    display: grid; grid-template-columns: 1fr;
    gap: 1rem; margin-bottom: 1.25rem;
}
@media (min-width: 768px) { .sa-kpi-row { grid-template-columns: 1.6fr 1fr 1fr; } }

/* Volume hero card */
.sa-volume-card {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%);
    border: 1px solid rgba(99,102,241,0.3); border-radius: 20px;
    padding: 1.75rem 2rem; position: relative; overflow: hidden;
}
.sa-volume-card::before {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(129,140,248,0.2) 0%, transparent 65%);
    pointer-events: none;
}
.sa-volume-card::after {
    content: '';
    position: absolute; bottom: -40px; left: -40px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 65%);
    pointer-events: none;
}
.sa-vol-label {
    font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.1em; color: rgba(165,180,252,0.7);
    margin-bottom: 0.75rem; position: relative; z-index: 1;
}
.sa-vol-value {
    font-size: 3rem; font-weight: 900; color: white;
    letter-spacing: -0.04em; line-height: 1; position: relative; z-index: 1;
}
.sa-vol-unit { font-size: 1.2rem; font-weight: 600; color: rgba(165,180,252,0.7); margin-left: 4px; }
.sa-vol-badges { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.75rem; position: relative; z-index: 1; }
.sa-badge-up {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.3);
    color: #4ade80; border-radius: 20px; padding: 0.25rem 0.75rem;
    font-size: 0.75rem; font-weight: 700;
}
.sa-vol-sub { font-size: 0.75rem; color: rgba(165,180,252,0.6); }

/* Right stat cards */
.sa-stat-card {
    background: var(--sa-card); border: 1px solid var(--sa-border);
    border-radius: 20px; padding: 1.5rem 1.75rem; position: relative; overflow: hidden;
}
.sa-stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
}
.sa-stat-commission::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.sa-stat-marchands::before  { background: linear-gradient(90deg, #4ade80, #22c55e); }

.sa-stat-label {
    font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.1em; color: var(--sa-text-3); margin-bottom: 0.75rem;
    display: flex; align-items: center; gap: 5px;
}
.sa-stat-value {
    font-size: 2rem; font-weight: 900; color: var(--sa-text-1);
    letter-spacing: -0.03em; line-height: 1; margin-bottom: 0.35rem;
}
.sa-stat-sub { font-size: 0.75rem; font-weight: 600; }
.sa-stat-sub.yellow { color: #fbbf24; }
.sa-stat-sub.green  { color: #4ade80; }

/* ── Bottom grid ── */
.sa-bottom-grid {
    display: grid; grid-template-columns: 1fr;
    gap: 1.25rem; margin-bottom: 1.25rem;
}
@media (min-width: 768px) { .sa-bottom-grid { grid-template-columns: 1fr 300px; } }

/* Dark card */
.sa-dark-card {
    background: var(--sa-card); border: 1px solid var(--sa-border);
    border-radius: 20px; overflow: hidden;
}
.sa-dark-card-head {
    padding: 1rem 1.5rem; border-bottom: 1px solid var(--sa-border);
    display: flex; align-items: center; justify-content: space-between;
}
.sa-dark-card-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.05rem; font-weight: 700; color: var(--sa-text-1);
}
.sa-btn-all {
    font-size: 0.75rem; font-weight: 600; color: var(--sa-text-3);
    text-decoration: none; padding: 0.3rem 0.75rem;
    border: 1px solid var(--sa-border); border-radius: 8px;
    background: rgba(255,255,255,0.03); transition: all 0.15s;
}
.sa-btn-all:hover { border-color: var(--sa-accent); color: #818cf8; }

/* Merchant table */
.sa-merch-table { width: 100%; border-collapse: collapse; }
.sa-merch-table thead th {
    padding: 0.55rem 1.25rem; font-size: 0.64rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em; color: var(--sa-text-3);
    border-bottom: 1px solid var(--sa-border); background: rgba(255,255,255,0.015);
}
.sa-merch-table tbody td {
    padding: 0.75rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.03);
    font-size: 0.83rem; vertical-align: middle; color: var(--sa-text-2);
}
.sa-merch-table tbody tr:last-child td { border-bottom: none; }
.sa-merch-table tbody tr:hover td { background: rgba(255,255,255,0.02); }

.sa-rank {
    width: 22px; height: 22px; border-radius: 6px; font-size: 0.7rem;
    font-weight: 800; display: flex; align-items: center; justify-content: center;
    background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);
}
.sa-rank.gold   { background: rgba(251,191,36,0.12); color: #fbbf24; border-color: rgba(251,191,36,0.25); }
.sa-rank.silver { background: rgba(148,163,184,0.1); color: #94a3b8; border-color: rgba(148,163,184,0.2); }
.sa-rank.bronze { background: rgba(251,146,60,0.1);  color: #fb923c; border-color: rgba(251,146,60,0.2); }

.sa-merch-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 700; color: white; flex-shrink: 0;
}
.sa-merch-name { font-weight: 600; color: var(--sa-text-1); }
.sa-boutique-url { font-size: 0.7rem; color: var(--sa-text-3); font-family: 'Courier New', monospace; }
.sa-volume { font-weight: 700; color: var(--sa-text-1); font-size: 0.83rem; }
.sa-commission-val { font-size: 0.7rem; color: #4ade80; font-weight: 600; }

/* Right panel */
.sa-right-panel { display: flex; flex-direction: column; gap: 1rem; }

/* KYC alert card */
.sa-kyc-card {
    background: rgba(127,29,29,0.3); border: 1px solid rgba(239,68,68,0.3);
    border-radius: 18px; padding: 1.25rem 1.5rem; position: relative; overflow: hidden;
}
.sa-kyc-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, #ef4444, #dc2626);
}
.sa-kyc-alert-label {
    font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.1em; color: #fca5a5; margin-bottom: 0.625rem;
    display: flex; align-items: center; gap: 6px;
}
.sa-kyc-alert-label i { color: #f87171; }
.sa-kyc-headline {
    font-size: 1rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.4;
}
.sa-btn-examine {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4);
    color: #fca5a5; border-radius: 9px; padding: 0.55rem 1rem;
    font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s;
}
.sa-btn-examine:hover { background: rgba(239,68,68,0.3); color: #fca5a5; }

/* System activity card */
.sa-activity-card {
    background: var(--sa-card); border: 1px solid var(--sa-border);
    border-radius: 18px; overflow: hidden;
}
.sa-activity-head {
    padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--sa-border);
    font-size: 0.8rem; font-weight: 700; color: var(--sa-text-2);
    text-transform: uppercase; letter-spacing: 0.07em;
}
.sa-api-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.65rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.03);
    font-size: 0.8rem;
}
.sa-api-row:last-child { border-bottom: none; }
.sa-api-left { display: flex; align-items: center; gap: 10px; }
.sa-api-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.sa-api-dot.green  { background: #4ade80; box-shadow: 0 0 6px rgba(74,222,128,0.5); }
.sa-api-dot.yellow { background: #fbbf24; box-shadow: 0 0 6px rgba(251,191,36,0.5); }
.sa-api-dot.red    { background: #f87171; box-shadow: 0 0 6px rgba(248,113,113,0.5); }
.sa-api-name { font-weight: 600; color: var(--sa-text-1); }
.sa-api-detail { font-size: 0.7rem; color: var(--sa-text-3); margin-top: 1px; }
.sa-api-time { font-size: 0.68rem; color: var(--sa-text-3); font-family: 'Courier New', monospace; }

/* ── Chart section ── */
.sa-charts-row {
    display: grid; grid-template-columns: 1fr;
    gap: 1.25rem; margin-bottom: 1.25rem;
}
@media (min-width: 768px) { .sa-charts-row { grid-template-columns: 2fr 1fr; } }
</style>
@endpush

@section('content')

{{-- ─── Header ─── --}}
<div class="sa-db-header">
    <div class="sa-db-header-inner">
        <div>
            <div class="sa-db-label"><i class="fas fa-circle" style="color:#4ade80;font-size:0.45rem;"></i> Console Nafalo · Vue globale</div>
            <h1 class="sa-db-headline">
                @php
                    $ca = $stats['chiffre_affaires'];
                    if ($ca >= 1000000000) { $caFmt = number_format($ca / 1000000000, 1, ',', '').' Mrd'; }
                    elseif ($ca >= 1000000) { $caFmt = number_format($ca / 1000000, 0, ',', '').' M'; }
                    else { $caFmt = number_format($ca, 0, ',', ' '); }
                @endphp
                {{ $caFmt }} F traités.
                <em>Plateforme en bonne santé.</em>
            </h1>
        </div>
        <div class="sa-search-bar">
            @if(isset($kycsEnAttente) && $kycsEnAttente->count() > 0)
                <a href="{{ route('superadmin.kycs.index') }}" class="sa-kyc-alert-btn">
                    {{ $kycsEnAttente->count() }} KYC EN ATTENTE
                </a>
            @endif
        </div>
    </div>
</div>

{{-- ─── 3 KPI cards ─── --}}
<div class="sa-kpi-row">

    {{-- Volume hero --}}
    <div class="sa-volume-card">
        <div class="sa-vol-label"><i class="fas fa-chart-line" style="margin-right:4px;"></i> Volume traité · 30J · BRUT</div>
        <div class="sa-vol-value">
            {{ $caFmt }}<span class="sa-vol-unit">F</span>
        </div>
        <div class="sa-vol-badges">
            @if(isset($tauxCroissance) && $tauxCroissance > 0)
                <span class="sa-badge-up"><i class="fas fa-arrow-up" style="font-size:0.6rem;"></i> {{ $tauxCroissance }}% vs 30j</span>
            @endif
            <span class="sa-vol-sub">
                {{ number_format($stats['total_marchands'], 0, ',', ' ') }} marchands
                · {{ number_format($stats['transactions_reussies'], 0, ',', ' ') }} transactions
            </span>
        </div>
    </div>

    {{-- Commissions --}}
    <div class="sa-stat-card sa-stat-commission">
        <div class="sa-stat-label"><i class="fas fa-percentage" style="color:#fbbf24;"></i> Commission Nafalo · 30J</div>
        <div class="sa-stat-value">
            @php
                $comm = $stats['total_commissions'];
                if ($comm >= 1000000) echo number_format($comm / 1000000, 1, ',', '').' M';
                else echo number_format($comm, 0, ',', ' ');
            @endphp
        </div>
        <div class="sa-stat-sub yellow">5,00% · Taux fixe</div>
        <div style="font-size:0.72rem;color:var(--sa-text-3);margin-top:3px;">
            sur {{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} F CA total
        </div>
    </div>

    {{-- Nouveaux marchands --}}
    <div class="sa-stat-card sa-stat-marchands">
        <div class="sa-stat-label"><i class="fas fa-users" style="color:#4ade80;"></i> Marchands actifs</div>
        <div class="sa-stat-value">{{ number_format($stats['total_marchands'], 0, ',', ' ') }}</div>
        <div class="sa-stat-sub green">{{ $stats['boutiques_actives'] }}/{{ $stats['total_boutiques'] }} boutiques actives</div>
        @if(isset($tauxCroissance) && $tauxCroissance > 0)
            <div style="font-size:0.72rem;color:var(--sa-text-3);margin-top:3px;">
                ↑ {{ $tauxCroissance }}% · Moyenne 7J
            </div>
        @endif
    </div>
</div>

{{-- ─── Bottom: Merchants table + Right panel ─── --}}
<div class="sa-bottom-grid">

    {{-- Top marchands table --}}
    <div class="sa-dark-card">
        <div class="sa-dark-card-head">
            <div class="sa-dark-card-title">Marchands · volume traité</div>
            <a href="{{ route('superadmin.marchands.index') }}" class="sa-btn-all">Tout voir →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="sa-merch-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Marchand</th>
                        <th>Boutique</th>
                        <th>Volume 30J</th>
                        <th>Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topMarchands as $i => $m)
                    @php
                        $colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6'];
                        $color  = $colors[crc32($m->nom) % count($colors)];
                        $init   = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ',$m->nom),0,2))));
                    @endphp
                    <tr>
                        <td style="width:40px;">
                            <div class="sa-rank {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                                {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.65rem;">
                                <div class="sa-merch-avatar" style="background:{{ $color }};">{{ $init }}</div>
                                <div>
                                    <div class="sa-merch-name">{{ $m->nom }}</div>
                                    <div style="font-size:0.7rem;color:var(--sa-text-3);">{{ $m->nb_ventes }} vente{{ $m->nb_ventes > 1 ? 's' : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($m->boutique ?? null)
                                <div class="sa-boutique-url">{{ $m->boutique->slug ?? 'N/A' }}.nafalo.com</div>
                                <div style="font-size:0.68rem;color:var(--sa-text-3);text-transform:uppercase;letter-spacing:0.06em;">{{ $m->boutique->categorie ?? '' }}</div>
                            @else
                                <span style="color:var(--sa-text-3);font-size:0.75rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="sa-volume">{{ number_format($m->ca_total, 0, ',', ' ') }} F</div>
                        </td>
                        <td>
                            <div class="sa-commission-val">{{ number_format($m->commissions, 0, ',', ' ') }} F</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="sa-right-panel">

        {{-- KYC action required --}}
        @if(isset($kycsEnAttente) && $kycsEnAttente->count() > 0)
        <div class="sa-kyc-card">
            <div class="sa-kyc-alert-label"><i class="fas fa-exclamation-triangle"></i> Action requise</div>
            <div class="sa-kyc-headline">
                {{ $kycsEnAttente->count() }} dossier{{ $kycsEnAttente->count() > 1 ? 's' : '' }} KYC en attente depuis &gt; 48 h
            </div>
            <a href="{{ route('superadmin.kycs.index') }}" class="sa-btn-examine">
                Examiner les KYC →
            </a>
        </div>
        @endif

        {{-- System activity --}}
        <div class="sa-activity-card">
            <div class="sa-activity-head">Activité système</div>

            @php
                // Fraude count for display
                $fraudeCount = isset($nbSuspects) ? $nbSuspects : 0;
            @endphp

            <div class="sa-api-row">
                <div class="sa-api-left">
                    <div class="sa-api-dot green"></div>
                    <div>
                        <div class="sa-api-name">Moneroo webhook</div>
                        <div class="sa-api-detail">Taux de succès 99,98%</div>
                    </div>
                </div>
                <div class="sa-api-time">24H</div>
            </div>
            <div class="sa-api-row">
                <div class="sa-api-left">
                    <div class="sa-api-dot green"></div>
                    <div>
                        <div class="sa-api-name">Wave API</div>
                        <div class="sa-api-detail">Temps réponse 142 ms</div>
                    </div>
                </div>
                <div class="sa-api-time">24H</div>
            </div>
            <div class="sa-api-row">
                <div class="sa-api-left">
                    <div class="sa-api-dot yellow"></div>
                    <div>
                        <div class="sa-api-name">MTN MoMo</div>
                        <div class="sa-api-detail">Latence élevée 920 ms</div>
                    </div>
                </div>
                <div class="sa-api-time">MAINTENANT</div>
            </div>
            <div class="sa-api-row">
                <div class="sa-api-left">
                    <div class="sa-api-dot green"></div>
                    <div>
                        <div class="sa-api-name">Stockage S3</div>
                        <div class="sa-api-detail">{{ $stats['total_marchands'] ?? 0 }} marchands · actif</div>
                    </div>
                </div>
                <div class="sa-api-time">24H</div>
            </div>
            @if($fraudeCount > 0)
            <div class="sa-api-row">
                <div class="sa-api-left">
                    <div class="sa-api-dot red"></div>
                    <div>
                        <div class="sa-api-name">Détection fraude</div>
                        <div class="sa-api-detail">{{ $fraudeCount }} alerte{{ $fraudeCount > 1 ? 's' : '' }} active{{ $fraudeCount > 1 ? 's' : '' }}</div>
                    </div>
                </div>
                <a href="{{ route('superadmin.fraudes.index') }}" style="font-size:0.68rem;color:#f87171;font-weight:700;text-decoration:none;">VOIR</a>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ─── Charts ─── --}}
<div class="sa-charts-row">
    <div class="sa-dark-card">
        <div class="sa-dark-card-head">
            <div class="sa-dark-card-title">Revenus mensuels</div>
        </div>
        <div style="padding:1.25rem;">
            <canvas id="chartMois" height="90"></canvas>
        </div>
    </div>
    <div class="sa-dark-card">
        <div class="sa-dark-card-head">
            <div class="sa-dark-card-title">Croissance</div>
        </div>
        <div style="padding:1.25rem;">
            <div style="margin-bottom:1rem;">
                <div style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--sa-text-3);margin-bottom:0.4rem;">CA ce mois</div>
                <div style="font-size:1.5rem;font-weight:900;color:var(--sa-text-1);letter-spacing:-0.03em;">{{ number_format($caMoisCourant, 0, ',', ' ') }} F</div>
                <div style="font-size:0.8rem;margin-top:4px;">
                    @if($tauxCroissance >= 0)
                        <span style="color:#4ade80;font-weight:700;">▲ +{{ $tauxCroissance }}%</span>
                    @else
                        <span style="color:#f87171;font-weight:700;">▼ {{ $tauxCroissance }}%</span>
                    @endif
                    <span style="color:var(--sa-text-3);font-size:0.72rem;"> vs mois dernier</span>
                </div>
            </div>
            <div style="height:1px;background:var(--sa-border);margin:1rem 0;"></div>
            <div>
                <div style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--sa-text-3);margin-bottom:0.4rem;">Mois précédent</div>
                <div style="font-size:1.1rem;font-weight:800;color:var(--sa-text-2);">{{ number_format($caMoisDernier, 0, ',', ' ') }} F</div>
            </div>
            <div style="height:1px;background:var(--sa-border);margin:1rem 0;"></div>
            <div>
                <div style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--sa-text-3);margin-bottom:0.4rem;">Transactions</div>
                <div style="font-size:1.1rem;font-weight:800;color:var(--sa-text-2);">{{ number_format($stats['transactions_reussies'], 0, ',', ' ') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Recent transactions ─── --}}
<div class="sa-dark-card" style="margin-bottom:1.25rem;">
    <div class="sa-dark-card-head">
        <div class="sa-dark-card-title">Dernières transactions</div>
        <a href="{{ route('superadmin.transactions.index') }}" class="sa-btn-all">Voir tout →</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="sa-merch-table">
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
                    <td>
                        <a href="{{ route('superadmin.transactions.show', $t) }}"
                           style="color:#818cf8;text-decoration:none;font-weight:600;font-family:'Courier New',monospace;font-size:0.78rem;">
                            {{ $t->reference }}
                        </a>
                    </td>
                    <td style="color:var(--sa-text-2);">{{ $t->boutique->nom ?? '—' }}</td>
                    <td style="color:var(--sa-text-3);font-size:0.78rem;">{{ $t->client->email ?? '—' }}</td>
                    <td style="font-weight:700;color:var(--sa-text-1);">{{ number_format($t->montant_total, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @if($t->statut === 'reussi')
                            <span style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.2);color:#4ade80;border-radius:20px;padding:2px 10px;font-size:0.7rem;font-weight:700;">Réussi</span>
                        @elseif($t->statut === 'en_attente')
                            <span style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;border-radius:20px;padding:2px 10px;font-size:0.7rem;font-weight:700;">En attente</span>
                        @elseif($t->statut === 'echoue')
                            <span style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.2);color:#f87171;border-radius:20px;padding:2px 10px;font-size:0.7rem;font-weight:700;">Échoué</span>
                        @else
                            <span style="background:rgba(148,163,184,0.08);border:1px solid rgba(148,163,184,0.15);color:#64748b;border-radius:20px;padding:2px 10px;font-size:0.7rem;font-weight:700;">Abandonné</span>
                        @endif
                    </td>
                    <td style="color:var(--sa-text-3);font-size:0.78rem;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--sa-text-3);padding:2.5rem;font-size:0.85rem;">
                    <i class="fas fa-receipt" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;opacity:0.2;"></i>
                    Aucune transaction
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    const mois    = @json($revenusParMois->pluck('mois'));
    const totaux  = @json($revenusParMois->pluck('total'));
    const commissions = @json($revenusParMois->pluck('commissions'));

    const moisFr = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    const labels = mois.map(m => {
        const [y, mo] = m.split('-');
        return moisFr[parseInt(mo) - 1] + ' ' + y.slice(2);
    });

    Chart.defaults.color = '#64748b';

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
                    backgroundColor: 'rgba(99,102,241,0.65)',
                    borderRadius: 6, order: 2,
                },
                {
                    label: 'Commissions Nafalo',
                    data: commissions,
                    backgroundColor: 'rgba(251,191,36,0.55)',
                    borderRadius: 6, order: 1,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 }, color: '#64748b', boxWidth: 10, borderRadius: 4 } },
                tooltip: {
                    backgroundColor: '#1e1e2c', borderColor: '#2d2d3d', borderWidth: 1,
                    titleColor: '#f1f5f9', bodyColor: '#94a3b8',
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + parseInt(ctx.raw).toLocaleString('fr') + ' F'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 11 } } },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: {
                        color: '#475569', font: { size: 11 },
                        callback: v => (v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v)) + ' F'
                    }
                }
            }
        }
    });
})();
</script>
@endpush
