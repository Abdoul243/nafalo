<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — Nafalo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; margin: 0; }

        /* SIDEBAR */
        .sa-sidebar {
            position: fixed; top: 0; left: 0;
            width: 240px; height: 100vh;
            background: linear-gradient(180deg, #0a0f1e 0%, #0f1f3d 60%, #112244 100%);
            display: flex; flex-direction: column;
            z-index: 100;
            border-right: 1px solid rgba(37,99,235,0.2);
        }
        .sa-brand {
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(37,99,235,0.15);
        }
        .sa-brand-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2.5px;
            color: #60a5fa; text-transform: uppercase; margin-bottom: 0.6rem;
        }
        .sa-brand-name {
            font-size: 1rem; font-weight: 800; color: white;
        }
        .sa-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .sa-nav-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 1.5px;
            color: rgba(96,165,250,0.5); text-transform: uppercase;
            padding: 0.75rem 1.5rem 0.25rem;
        }
        .sa-nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 1.5rem; margin: 1px 0.75rem;
            border-radius: 10px;
            color: #94a3b8; font-size: 0.875rem; font-weight: 500;
            text-decoration: none; transition: all 0.15s;
        }
        .sa-nav-link:hover { background: rgba(37,99,235,0.15); color: #93c5fd; }
        .sa-nav-link.active { background: rgba(37,99,235,0.25); color: #60a5fa; border-left: 3px solid #2563eb; }
        .sa-nav-link i { width: 18px; text-align: center; font-size: 0.875rem; }
        .sa-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(37,99,235,0.15);
        }
        .sa-user { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
        .sa-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex; align-items: center;
            justify-content: center; font-weight: 700; font-size: 0.8rem; color: white;
        }
        .sa-user-name { font-size: 0.8rem; font-weight: 600; color: white; }
        .sa-user-role { font-size: 0.7rem; color: #60a5fa; }

        /* MAIN */
        .sa-main { margin-left: 240px; min-height: 100vh; }
        .sa-topbar {
            background: white; border-bottom: 1px solid #e2e8f0;
            padding: 0 2rem; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .sa-topbar-title { font-weight: 700; font-size: 1rem; color: #0f172a; }
        .sa-content { padding: 2rem; }

        /* CARDS */
        .stat-card {
            background: white; border-radius: 14px;
            padding: 1.25rem; border: 1px solid #e2e8f0;
        }
        .stat-card .label { font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem; }
        .stat-card .value { font-size: 1.6rem; font-weight: 800; color: #0f172a; }
        .stat-card .icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        /* TABLE */
        .sa-table { background: white; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }
        .sa-table-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700; font-size: 0.9rem; color: #0f172a;
            display: flex; align-items: center; justify-content: space-between;
        }
        .sa-table table { width: 100%; border-collapse: collapse; }
        .sa-table thead th {
            padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 700;
            color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0; background: #f8fafc;
        }
        .sa-table tbody td {
            padding: 0.875rem 1rem; font-size: 0.875rem;
            color: #374151; border-bottom: 1px solid #f1f5f9;
        }
        .sa-table tbody tr:hover { background: #f8fafc; }
        .sa-table tbody tr:last-child td { border-bottom: none; }

        /* BADGES */
        .badge-reussi { background: #dcfce7; color: #166534; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-attente { background: #fef9c3; color: #854d0e; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-echoue { background: #fee2e2; color: #991b1b; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-abandonne { background: #f1f5f9; color: #475569; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-active { background: #dcfce7; color: #166534; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }
        .badge-inactive { background: #fee2e2; color: #991b1b; border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; }

        .alert { border: none; border-radius: 12px; font-size: 0.875rem; }
        .btn-sa { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: white; font-weight: 700; border: none; border-radius: 10px; padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-sa:hover { background: linear-gradient(135deg,#1d4ed8,#1e40af); color: white; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .sa-topbar { background: linear-gradient(90deg,#0a0f1e,#0f1f3d); border-bottom: 1px solid rgba(37,99,235,0.2); }
        .sa-topbar-title { color: white; }
        .sa-topbar span { color: #60a5fa; }

        /* Bouton hamburger SA */
        .sa-toggle-btn {
            display: none; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,0.08); border: none;
            color: white; font-size: 1rem; cursor: pointer;
            margin-right: 0.75rem; transition: background 0.2s;
        }
        .sa-toggle-btn:hover { background: rgba(255,255,255,0.15); }

        /* Overlay mobile */
        #sa-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); z-index: 99;
            backdrop-filter: blur(2px);
        }
        #sa-overlay.open { display: block; }

        /* ── RESPONSIVE SA ── */
        @media (max-width: 1024px) and (min-width: 769px) {
            .sa-sidebar { width: 64px; }
            .sa-brand-label, .sa-brand-name,
            .sa-nav-label, .sa-nav-link span,
            .sa-user-name, .sa-user-role,
            .sa-footer form { display: none !important; }
            .sa-nav-link { justify-content: center; padding: 0.65rem; margin: 2px 0.5rem; }
            .sa-nav-link i { width: auto; }
            .sa-brand { padding: 1rem 0; display: flex; justify-content: center; }
            .sa-user { justify-content: center; margin-bottom: 0; }
            .sa-main { margin-left: 64px; }
        }

        @media (max-width: 768px) {
            .sa-sidebar {
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.28s ease;
                z-index: 100;
            }
            .sa-sidebar.open { transform: translateX(0); }
            .sa-main { margin-left: 0; }
            .sa-toggle-btn { display: flex; }
            .sa-topbar { padding: 0 1rem; }
            .sa-content { padding: 1rem; }
            .sa-topbar > div:last-child { display: none; }
        }

        @media (max-width: 420px) {
            .sa-content { padding: 0.75rem; }
            .sa-table { overflow-x: auto; }
        }

        /* ── Filet de sécurité responsive ── */
        html, body { overflow-x: hidden; }
        img, video, canvas, svg { max-width: 100%; height: auto; }
        @media (max-width: 768px) {
            table { display: block; width: max-content; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .sa-table { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div class="sa-sidebar">
    <div class="sa-brand">
        <div class="sa-brand-label">Super Admin</div>
        <div class="sa-brand-name" style="display:flex;align-items:center;gap:8px;">
            <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo" style="height:56px;width:auto;filter:brightness(0) invert(1);">
        </div>
    </div>

    <nav class="sa-nav">
        <div class="sa-nav-label">Navigation</div>
        <a href="{{ route('superadmin.dashboard') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <div class="sa-nav-label" style="margin-top:0.5rem;">Gestion</div>
        <a href="{{ route('superadmin.marchands.index') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.marchands.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Marchands
        </a>
        <a href="{{ route('superadmin.boutiques.index') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.boutiques.*') ? 'active' : '' }}">
            <i class="fas fa-store"></i> Boutiques
        </a>
        <a href="{{ route('superadmin.transactions.index') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.transactions.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Transactions
        </a>

        <div class="sa-nav-label" style="margin-top:0.5rem;">Sécurité & Conformité</div>
        <a href="{{ route('superadmin.fraudes.index') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.fraudes.*') ? 'active' : '' }}">
            <i class="fas fa-shield-exclamation"></i> <span>Fraudes</span>
            @php try { $nbFraudes = \App\Models\Transaction::where('est_suspicieux', true)->count(); } catch(\Throwable $e) { $nbFraudes = 0; } @endphp
            @if($nbFraudes > 0)
                <span style="margin-left:auto;background:#ef4444;color:white;font-size:0.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">{{ $nbFraudes }}</span>
            @endif
        </a>
        <a href="{{ route('superadmin.kycs.index') }}"
           class="sa-nav-link {{ request()->routeIs('superadmin.kycs.*') ? 'active' : '' }}">
            <i class="fas fa-id-card"></i> <span>KYC</span>
            @php try { $nbKycs = \App\Models\Kyc::where('statut', 'en_attente')->count(); } catch(\Throwable $e) { $nbKycs = 0; } @endphp
            @if($nbKycs > 0)
                <span style="margin-left:auto;background:#f59e0b;color:white;font-size:0.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">{{ $nbKycs }}</span>
            @endif
        </a>
    </nav>

    <div class="sa-footer">
        <div class="sa-user">
            <div class="sa-avatar">{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}</div>
            <div>
                <div class="sa-user-name">{{ Auth::user()->nom }}</div>
                <div class="sa-user-role">Super Administrateur</div>
            </div>
        </div>
        <form method="POST" action="{{ route('superadmin.logout') }}">
            @csrf
            <button type="submit" style="width:100%;background:rgba(255,255,255,0.06);color:#94a3b8;border:none;border-radius:8px;padding:0.5rem;font-size:0.8rem;cursor:pointer;">
                <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
            </button>
        </form>
    </div>
</div>

<div id="sa-overlay" onclick="closeSASidebar()"></div>

{{-- MAIN --}}
<div class="sa-main">
    <div class="sa-topbar">
        <div style="display:flex;align-items:center;">
            <button class="sa-toggle-btn" id="sa-menu-toggle" onclick="toggleSASidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="sa-topbar-title">@yield('page_title', 'Dashboard')</div>
        </div>
        <div style="font-size:0.8rem;color:#64748b;">
            <i class="fas fa-shield-alt me-1" style="color:#60a5fa;"></i>
            <span style="color:#60a5fa;">Espace Super Administrateur</span>
        </div>
    </div>

    <div class="sa-content">
        @foreach(['success' => 'check-circle', 'error' => 'exclamation-circle'] as $type => $icon)
            @if(session($type))
                <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible fade show mb-3">
                    <i class="fas fa-{{ $icon }} me-2"></i>{{ session($type) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSASidebar() {
    const sidebar = document.querySelector('.sa-sidebar');
    const overlay = document.getElementById('sa-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}
function closeSASidebar() {
    document.querySelector('.sa-sidebar').classList.remove('open');
    document.getElementById('sa-overlay').classList.remove('open');
}
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeSASidebar();
});
</script>
@stack('scripts')
</body>
</html>
