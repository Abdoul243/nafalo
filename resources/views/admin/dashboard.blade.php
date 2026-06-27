@extends('layouts.admin')
@section('title', 'Tableau de bord')

@push('styles')
<style>
.db-kpi { display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:20px; }
@media(min-width:768px){ .db-kpi { grid-template-columns:repeat(4,1fr); } }
.db-kpi-card { background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:20px 22px; }
.db-kpi-label { font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;margin-bottom:6px; }
.db-kpi-value { font-size:1.7rem;font-weight:900;color:#111827;letter-spacing:-.04em;line-height:1;font-variant-numeric:tabular-nums; }
.db-kpi-sub { font-size:.7rem;color:#9ca3af;margin-top:5px; }

.db-revenue { background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;margin-bottom:20px; }
.db-rev-inner { display:grid;grid-template-columns:1fr; }
@media(min-width:768px){ .db-rev-inner { grid-template-columns:1fr 320px; } }
.db-rev-left { padding:28px 28px; }
.db-rev-right { border-top:1px solid #f1f5f9;padding:8px; }
@media(min-width:768px){ .db-rev-right { border-top:none;border-left:1px solid #f1f5f9; } }

.db-grid2 { display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:20px; }
@media(min-width:768px){ .db-grid2 { grid-template-columns:1fr 260px; } }
.db-chart-grid { display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:20px; }
@media(min-width:768px){ .db-chart-grid { grid-template-columns:2fr 1fr; } }

.db-card { background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden; }
.db-card-head { padding:14px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between; }
.db-card-title { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;display:flex;align-items:center;gap:8px; }
.db-card-title-icon { width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.72rem; }
.db-card-body { padding:20px; }

.seller-row { display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f8fafc; }
.seller-row:last-child { border-bottom:none;padding-bottom:0; }
.seller-rank { width:24px;height:24px;border-radius:7px;background:#f3f4f6;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;color:#6b7280;flex-shrink:0; }
.prod-thumb { width:48px;height:48px;flex-shrink:0;border-radius:11px;overflow:hidden;border:1px solid #e5e7eb;position:relative; }
.prod-thumb-bg { position:absolute;inset:0;z-index:1;display:flex;align-items:center;justify-content:center; }
.prod-thumb img { position:absolute;inset:0;z-index:2;width:100%;height:100%;object-fit:cover;display:block; }
.seller-info { flex:1;min-width:0; }
.seller-name { font-size:.83rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:5px; }
.seller-bar-wrap { background:#f1f5f9;border-radius:4px;height:3px;overflow:hidden; }
.seller-bar { height:100%;border-radius:4px;width:0%;background:linear-gradient(90deg,#f59e0b,#fbbf24);transition:width 1.2s cubic-bezier(.4,0,.2,1); }
.seller-stat { text-align:right;flex-shrink:0; }
.seller-revenue { font-size:.83rem;font-weight:800;color:#111827;white-space:nowrap; }
.seller-count { font-size:.67rem;color:#9ca3af;margin-top:2px; }

.tx-row { display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f8fafc; }
.tx-row:last-child { border-bottom:none; }
.tx-avatar { width:38px;height:38px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800;color:#fff; }
.tx-info { flex:1;min-width:0; }
.tx-name { font-size:.83rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.tx-ref  { font-size:.67rem;color:#9ca3af;font-family:monospace; }
.tx-amount { font-size:.86rem;font-weight:800;color:#111827;text-align:right;white-space:nowrap; }

.period-tabs { display:flex;gap:2px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:10px;padding:3px; }
.period-tab { padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:700;color:#6b7280;text-decoration:none;transition:all .15s;white-space:nowrap; }
.period-tab.active { background:#fff;color:#111827;box-shadow:0 1px 4px rgba(0,0,0,.1); }

.summary-row { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f5f9; }
.summary-row:last-child { border-bottom:none; }
.summary-row-label { font-size:.78rem;color:#6b7280; }
.summary-row-value { font-size:.83rem;font-weight:700;color:#111827; }
</style>
@endpush

@section('content')
<div class="cw-page">

{{-- Header --}}
@php $boutiqueCourante = \App\Models\Boutique::find(session('boutique_id')); @endphp
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:14px;">
        @if($boutiqueCourante)
        <div style="width:52px;height:52px;border-radius:14px;overflow:hidden;border:1.5px solid #e5e7eb;background:#f9fafb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            @if($boutiqueCourante->logo)
                <img src="{{ $boutiqueCourante->logo_url }}" alt="{{ $boutiqueCourante->nom }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
            @endif
            <span style="font-size:1.3rem;font-weight:900;color:#111827;">{{ strtoupper(substr($boutiqueCourante->nom, 0, 1)) }}</span>
        </div>
        @endif
        <div>
            <div style="font-size:1.25rem;font-weight:800;color:#111827;letter-spacing:-.02em;">Bonjour, {{ Str::of(Auth::user()->nom)->before(' ') }} 👋</div>
            <div style="font-size:.8rem;color:#9ca3af;">{{ $boutiqueCourante?->nom ?? 'Votre boutique' }} — {{ now()->translatedFormat('l d F Y') }}</div>
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <div class="period-tabs">
            <a href="?periode=7jours"  class="period-tab {{ $periode === '7jours'  ? 'active' : '' }}">7J</a>
            <a href="?periode=30jours" class="period-tab {{ ($periode === '30jours' || !isset($periode)) ? 'active' : '' }}">30J</a>
            <a href="?periode=90jours" class="period-tab {{ $periode === '90jours' ? 'active' : '' }}">90J</a>
            <a href="?periode=12mois"  class="period-tab {{ $periode === '12mois'  ? 'active' : '' }}">12M</a>
            <a href="?periode=tout"    class="period-tab {{ $periode === 'tout'    ? 'active' : '' }}">Tout</a>
        </div>
        <a href="{{ route('admin.produits.create') }}" class="cw-btn-primary">
            <i class="fas fa-plus"></i> Nouveau produit
        </a>
    </div>
</div>

{{-- Revenue hero --}}
<div class="db-revenue">
    <div class="db-rev-inner">
        <div class="db-rev-left">
            <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;margin-bottom:10px;display:flex;align-items:center;gap:7px;">
                <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                Chiffre d'affaires brut
            </div>
            <div style="font-size:2.8rem;font-weight:900;color:#111827;letter-spacing:-.05em;line-height:1;font-variant-numeric:tabular-nums;" id="counter-ca" data-target="{{ $chiffreAffaires }}">
                {{ number_format($chiffreAffaires, 0, ',', ' ') }}<span style="font-size:.95rem;color:#9ca3af;font-weight:600;margin-left:6px;">FCFA</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap;">
                <span style="display:inline-flex;align-items:center;gap:5px;background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;border-radius:20px;padding:4px 12px;font-size:.75rem;font-weight:700;">
                    <i class="fas fa-arrow-up" style="font-size:.55rem;"></i>
                    {{ number_format($gainsNets, 0, ',', ' ') }} FCFA nets
                </span>
                <span style="font-size:.72rem;color:#9ca3af;">après commission plateforme</span>
            </div>
        </div>
        <div class="db-rev-right">
            <canvas id="ventesChart" height="120"></canvas>
        </div>
    </div>
</div>

{{-- KPIs --}}
@php $panierMoyen = $totalVentes > 0 ? round($chiffreAffaires / $totalVentes) : 0; @endphp
<div class="db-kpi">
    <div class="db-kpi-card">
        <div style="width:38px;height:38px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fas fa-shopping-bag" style="color:#d97706;"></i>
        </div>
        <div class="db-kpi-label">Ventes</div>
        <div class="db-kpi-value" data-counter="{{ $totalVentes }}">{{ number_format($totalVentes) }}</div>
        <div class="db-kpi-sub">Transactions réussies</div>
    </div>
    <div class="db-kpi-card">
        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fas fa-receipt" style="color:#16a34a;"></i>
        </div>
        <div class="db-kpi-label">Panier moyen</div>
        <div class="db-kpi-value" style="font-size:1.4rem;" data-counter="{{ $panierMoyen }}">{{ number_format($panierMoyen) }}</div>
        <div class="db-kpi-sub">FCFA / commande</div>
    </div>
    <div class="db-kpi-card">
        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fas fa-percent" style="color:#3b82f6;"></i>
        </div>
        <div class="db-kpi-label">Conversion</div>
        <div class="db-kpi-value" data-counter="{{ $tauxConversion }}">{{ $tauxConversion }}%</div>
        <div class="db-kpi-sub">Visites → achats</div>
    </div>
    <div class="db-kpi-card">
        <div style="width:38px;height:38px;border-radius:10px;background:#fdf4ff;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fas fa-users" style="color:#9333ea;"></i>
        </div>
        <div class="db-kpi-label">Clients</div>
        <div class="db-kpi-value" data-counter="{{ $totalClients }}">{{ number_format($totalClients) }}</div>
        <div class="db-kpi-sub" style="color:#16a34a;font-weight:700;">+{{ $nouveauxClients }} ce mois</div>
    </div>
</div>

{{-- ── Abonnements / revenus récurrents ── --}}
@if($aDesAbonnements)
<div style="display:grid;grid-template-columns:repeat(1,1fr);gap:12px;margin-bottom:20px;" class="db-abo-grid">
    <div class="db-kpi-card" style="border-color:#c7d2fe;background:linear-gradient(135deg,#eef2ff,#fff);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <div style="width:38px;height:38px;border-radius:10px;background:#4f46e5;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-sync-alt" style="color:#fff;"></i>
            </div>
            <div class="db-kpi-label" style="margin:0;">Revenus récurrents (MRR)</div>
        </div>
        <div class="db-kpi-value">{{ number_format($mrr, 0, ',', ' ') }}<span style="font-size:.9rem;color:#9ca3af;font-weight:600;margin-left:5px;">FCFA/mois</span></div>
        <div class="db-kpi-sub">Estimation mensuelle des abonnements actifs</div>
    </div>
    <div class="db-kpi-card">
        <div class="db-kpi-label">Abonnés actifs</div>
        <div class="db-kpi-value" data-counter="{{ $abonnesActifs }}">{{ number_format($abonnesActifs) }}</div>
        <div class="db-kpi-sub">Abonnements en cours</div>
    </div>
    <div class="db-kpi-card">
        <div class="db-kpi-label">Échéances à venir</div>
        <div class="db-kpi-value" style="{{ $abonnementsExpirant > 0 ? 'color:#d97706;' : '' }}" data-counter="{{ $abonnementsExpirant }}">{{ number_format($abonnementsExpirant) }}</div>
        <div class="db-kpi-sub">Expirent sous 7 jours</div>
    </div>
</div>
<style>@media(min-width:768px){ .db-abo-grid { grid-template-columns:1.4fr 1fr 1fr !important; } }</style>
@endif

{{-- Best sellers + résumé --}}
<div class="db-grid2">
    <div class="db-card">
        <div class="db-card-head">
            <div class="db-card-title">
                <div class="db-card-title-icon" style="background:#fff7ed;color:#f97316;"><i class="fas fa-fire"></i></div>
                Meilleures ventes
            </div>
            <a href="{{ route('admin.produits.index') }}" style="font-size:.72rem;font-weight:700;color:#9ca3af;text-decoration:none;padding:4px 10px;border:1px solid #e5e7eb;border-radius:8px;">Voir tout</a>
        </div>
        <div class="db-card-body">
            @php $maxRev = $topProduits->isNotEmpty() ? max($topProduits->map(fn($p) => $p->achats_count * $p->prix)->toArray()) : 1; @endphp
            @forelse($topProduits as $i => $produit)
            @php
                $revenue  = $produit->achats_count * $produit->prix;
                $pct      = $maxRev > 0 ? round(($revenue / $maxRev) * 100) : 0;
                $colors   = ['#f59e0b','#10b981','#3b82f6','#ef4444','#8b5cf6','#ec4899'];
                $col      = $colors[abs(crc32($produit->nom)) % count($colors)];
                $initials = strtoupper(implode('', array_map(fn($w)=>substr($w,0,1), array_slice(explode(' ',$produit->nom),0,2))));
            @endphp
            <div class="seller-row">
                <div class="seller-rank">{{ $i + 1 }}</div>
                <div class="prod-thumb">
                    <div class="prod-thumb-bg" style="background:{{ $col }}18;">
                        <span style="font-size:.68rem;font-weight:800;color:{{ $col }};opacity:.7;">{{ $initials }}</span>
                    </div>
                    @if($produit->image)
                        <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy" onerror="this.remove()">
                    @endif
                </div>
                <div class="seller-info">
                    <div class="seller-name">{{ $produit->nom }}</div>
                    <div class="seller-bar-wrap"><div class="seller-bar" data-width="{{ $pct }}"></div></div>
                </div>
                <div class="seller-stat">
                    <div class="seller-revenue">{{ number_format($revenue, 0, ',', ' ') }} <span style="font-size:.6rem;color:#9ca3af;">F</span></div>
                    <div class="seller-count">{{ $produit->achats_count }} vente{{ $produit->achats_count > 1 ? 's' : '' }}</div>
                </div>
            </div>
            @empty
            <div class="cw-empty"><i class="fas fa-box-open"></i><p>Aucune vente pour le moment</p></div>
            @endforelse
        </div>
    </div>

    <div class="db-card">
        <div class="db-card-head">
            <div class="db-card-title">
                <div class="db-card-title-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-wallet"></i></div>
                Résumé
            </div>
        </div>
        <div class="db-card-body">
            <div style="font-size:1.6rem;font-weight:900;color:#16a34a;letter-spacing:-.04em;margin-bottom:4px;">{{ number_format($gainsNets, 0, ',', ' ') }}</div>
            <div style="font-size:.78rem;color:#9ca3af;margin-bottom:16px;">FCFA disponibles (nets)</div>
            <div class="summary-row">
                <span class="summary-row-label">CA brut</span>
                <span class="summary-row-value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-row">
                <span class="summary-row-label">Ventes</span>
                <span class="summary-row-value">{{ $totalVentes }} transactions</span>
            </div>
            <div class="summary-row">
                <span class="summary-row-label">Produits actifs</span>
                <span class="summary-row-value">{{ $produitPublies }} / {{ $totalProduits }}</span>
            </div>
            @if(($totalLeads ?? 0) > 0)
            <div class="summary-row">
                <span class="summary-row-label">Leads capturés</span>
                <span class="summary-row-value">{{ $totalLeads }}</span>
            </div>
            @endif
            <div style="margin-top:14px;">
                <span style="display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;border-radius:20px;padding:5px 12px;font-size:.72rem;font-weight:700;">
                    <i class="fas fa-check-circle" style="font-size:.65rem;"></i> Compte actif
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="db-chart-grid">
    <div class="db-card">
        <div class="db-card-head">
            <div class="db-card-title">
                <div class="db-card-title-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-chart-area"></i></div>
                Évolution des ventes
            </div>
        </div>
        <div class="db-card-body" style="padding-top:10px;">
            <canvas id="evolutionChart" height="110"></canvas>
        </div>
    </div>
    <div class="db-card">
        <div class="db-card-head">
            <div class="db-card-title">
                <div class="db-card-title-icon" style="background:#fdf4ff;color:#9333ea;"><i class="fas fa-chart-pie"></i></div>
                Par catégorie
            </div>
        </div>
        <div class="db-card-body">
            @if($ventesParCategorie->isEmpty())
                <div class="cw-empty" style="padding:40px 0;"><i class="fas fa-chart-pie"></i><p>Pas encore de ventes</p></div>
            @else
                <canvas id="categorieChart" height="170"></canvas>
            @endif
        </div>
    </div>
</div>

{{-- Transactions récentes --}}
<div class="db-card" style="margin-bottom:16px;">
    <div class="db-card-head">
        <div class="db-card-title">
            <div class="db-card-title-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-clock"></i></div>
            Activité récente
        </div>
        <a href="{{ route('admin.transactions.index') }}" style="font-size:.72rem;font-weight:700;color:#9ca3af;text-decoration:none;padding:4px 10px;border:1px solid #e5e7eb;border-radius:8px;">Voir tout</a>
    </div>
    <div class="db-card-body">
        @forelse($dernieresTransactions as $tx)
        @php
            $txNom   = $tx->client->nom ?? 'Anonyme';
            $txColors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#f97316','#14b8a6'];
            $txColor  = $txColors[abs(crc32($txNom)) % count($txColors)];
            $txInit   = strtoupper(implode('', array_map(fn($w)=>substr($w,0,1), array_slice(explode(' ',$txNom),0,2))));
        @endphp
        <div class="tx-row">
            <div class="tx-avatar" style="background:{{ $txColor }};">{{ $txInit }}</div>
            <div class="tx-info">
                <div class="tx-name">{{ $tx->client->nom ?? 'Client anonyme' }}</div>
                <div class="tx-ref">{{ $tx->reference }}</div>
            </div>
            <div>
                <div class="tx-amount">{{ number_format($tx->montant_total, 0, ',', ' ') }} <span style="font-size:.62rem;color:#9ca3af;">FCFA</span></div>
                @if($tx->statut === 'reussi')
                    <span class="cw-badge cw-badge-green" style="font-size:.62rem;float:right;margin-top:2px;">Réussie</span>
                @elseif($tx->statut === 'en_attente')
                    <span class="cw-badge cw-badge-amber" style="font-size:.62rem;float:right;margin-top:2px;">En attente</span>
                @else
                    <span class="cw-badge cw-badge-red" style="font-size:.62rem;float:right;margin-top:2px;">Échouée</span>
                @endif
            </div>
        </div>
        @empty
        <div class="cw-empty"><i class="fas fa-receipt"></i><p>Aucune transaction pour le moment</p></div>
        @endforelse
    </div>
</div>

{{-- Lead Magnets --}}
@if(!empty($produitsLeadMagnet) && $produitsLeadMagnet->isNotEmpty())
<div class="db-card">
    <div class="db-card-head">
        <div class="db-card-title">
            <div class="db-card-title-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-magnet"></i></div>
            Lead Magnets actifs
        </div>
    </div>
    <div class="db-card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;">
            @foreach($produitsLeadMagnet as $lm)
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:12px 14px;">
                <div style="font-size:.83rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:6px;">{{ $lm->nom }}</div>
                <span class="cw-badge cw-badge-green">{{ $lm->nb_leads }} lead{{ $lm->nb_leads > 1 ? 's' : '' }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fmt = new Intl.NumberFormat('fr-FR');
    function animCounter(el, target, duration) {
        const start = performance.now();
        function step(ts) {
            const p = Math.min((ts - start) / duration, 1);
            const ease = 1 - Math.pow(1 - p, 3);
            el.textContent = fmt.format(Math.round(target * ease));
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const v = parseInt(el.dataset.counter ?? el.dataset.target ?? '0');
            animCounter(el, v, 1200);
            io.unobserve(el);
        });
    }, { threshold: 0.4 });
    document.querySelectorAll('[data-counter],[data-target]').forEach(el => io.observe(el));

    const barIO = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            e.target.style.width = e.target.dataset.width + '%';
            barIO.unobserve(e.target);
        });
    }, { threshold: 0.2 });
    document.querySelectorAll('.seller-bar[data-width]').forEach(b => barIO.observe(b));

    const ventesData = @json($ventesParJour);
    const gridColor  = 'rgba(0,0,0,0.06)';
    const tickColor  = 'rgba(0,0,0,0.4)';

    Chart.defaults.font.family = 'Inter, sans-serif';

    /* Sparkline */
    const sparkCtx = document.getElementById('ventesChart');
    if (sparkCtx && ventesData.length) {
        new Chart(sparkCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ventesData.map(i => i.date),
                datasets: [{ data: ventesData.map(i => i.total_montant), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,.12)', borderWidth: 2.5, tension: 0.45, fill: true, pointRadius: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false, beginAtZero: true } } }
        });
    }

    /* Evolution */
    const evoCtx = document.getElementById('evolutionChart');
    if (evoCtx && ventesData.length) {
        new Chart(evoCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ventesData.map(i => i.date),
                datasets: [{ data: ventesData.map(i => i.total_montant), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,.1)', borderWidth: 2.5, tension: 0.4, fill: true, pointRadius: 3, pointBackgroundColor: '#f59e0b', pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 5 }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + Number(ctx.raw).toLocaleString('fr-FR') + ' FCFA' }, backgroundColor: '#fff', borderColor: 'rgba(0,0,0,.1)', borderWidth: 1, titleColor: '#111827', bodyColor: '#6b7280', padding: 10, cornerRadius: 10 } },
                scales: { x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 11 } } }, y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 11 } }, beginAtZero: true } }
            }
        });
    }

    /* Donut */
    const catData = @json($ventesParCategorie);
    const catCtx  = document.getElementById('categorieChart');
    if (catCtx && catData.length) {
        const palette = ['#f59e0b','#10b981','#3b82f6','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];
        new Chart(catCtx.getContext('2d'), {
            type: 'doughnut',
            data: { labels: catData.map(c => c.nom), datasets: [{ data: catData.map(c => c.nb_ventes), backgroundColor: palette.slice(0, catData.length), borderWidth: 3, borderColor: '#fff' }] },
            options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12, color: tickColor, boxWidth: 10, borderRadius: 4 } }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ' : ' + ctx.raw + ' vente(s)' }, backgroundColor: '#fff', borderColor: 'rgba(0,0,0,.1)', borderWidth: 1, titleColor: '#111827', bodyColor: '#6b7280', padding: 10, cornerRadius: 10 } } }
        });
    }
});
</script>
@endpush
