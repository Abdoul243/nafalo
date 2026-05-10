<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        * { box-sizing: border-box; min-width: 0; }
        html { overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; color: #0f172a; overflow-x: hidden; }
        #wrapper { display: flex; min-height: 100vh; overflow-x: hidden; max-width: 100vw; }
        img, video, iframe, canvas, svg { max-width: 100%; height: auto; }

        /* SIDEBAR */
        #sidebar-wrapper {
            width: 220px; min-width: 220px;
            background: white; border-right: 1px solid #f1f5f9;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 1000; transition: width 0.25s ease; overflow: hidden;
        }
        #wrapper.toggled #sidebar-wrapper { width: 64px; min-width: 64px; }
        #wrapper.toggled .sidebar-text, #wrapper.toggled .sidebar-logo-text,
        #wrapper.toggled .sidebar-section-label, #wrapper.toggled .sidebar-footer-info,
        #wrapper.toggled .boutique-chevron { display: none !important; }
        #wrapper.toggled .nav-item a { justify-content: center; padding: 0.75rem; }
        #wrapper.toggled .nav-item a i { margin: 0; }
        #wrapper.toggled .sidebar-logo { justify-content: center; padding: 1.25rem 0; }
        #wrapper.toggled .sidebar-boutique { justify-content: center; padding: 0.75rem 0; }
        #wrapper.toggled .sidebar-footer { justify-content: center; }

        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 1.2rem 1rem; border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
        }
        .logo-icon { width: 120px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
        .logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .sidebar-logo-text { display: none; }

        .sidebar-boutique {
            display: flex; align-items: center; gap: 8px;
            padding: 0.7rem 1rem; border-bottom: 1px solid #f1f5f9;
            cursor: pointer; transition: background 0.15s;
        }
        .sidebar-boutique:hover { background: #f8fafc; }
        .b-avatar { width: 28px; height: 28px; border-radius: 7px; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #2563eb; font-weight: 700; font-size: 0.7rem; flex-shrink: 0; overflow: hidden; }
        .b-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .b-name { font-size: 0.8rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
        .boutique-chevron { color: #94a3b8; font-size: 0.6rem; }

        .sidebar-nav { flex: 1; padding: 0.5rem 0; overflow-y: auto; overflow-x: hidden; }
        .sidebar-section-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #94a3b8; padding: 0.8rem 1rem 0.2rem; }
        .nav-item a {
            display: flex; align-items: center; gap: 9px;
            padding: 0.55rem 0.6rem 0.55rem 1rem;
            border-radius: 9px; margin: 1px 0.5rem;
            color: #64748b; text-decoration: none;
            font-size: 0.855rem; font-weight: 500;
            transition: all 0.15s; white-space: nowrap;
        }
        .nav-item a i { width: 16px; text-align: center; font-size: 0.875rem; flex-shrink: 0; }
        .nav-item a:hover { background: #f1f5f9; color: #0f172a; }
        .nav-item a.active { background: #eff6ff; color: #2563eb; font-weight: 600; }
        .nav-item a.active i { color: #2563eb; }

        .sidebar-footer { padding: 0.875rem 1rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; }
        .sf-avatar { width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #64748b; flex-shrink: 0; }
        .sidebar-footer-info { overflow: hidden; }
        .sf-name { font-size: 0.78rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sf-email { font-size: 0.68rem; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* MAIN */
        #page-content-wrapper { flex: 1; margin-left: 220px; transition: margin-left 0.25s ease; display: flex; flex-direction: column; }
        #wrapper.toggled #page-content-wrapper { margin-left: 64px; }

        /* TOPBAR */
        .topbar { background: white; border-bottom: 1px solid #f1f5f9; padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 999; }
        .topbar-left, .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
        .toggle-btn { width: 34px; height: 34px; border-radius: 8px; background: #f1f5f9; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; color: #64748b; font-size: 0.875rem; }
        .toggle-btn:hover { background: #e2e8f0; color: #0f172a; }

        .tb-boutique { display: flex; align-items: center; gap: 7px; padding: 0.4rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 9px; cursor: pointer; background: white; transition: all 0.15s; }
        .tb-boutique:hover { border-color: #2563eb; }
        .tb-b-name { font-weight: 600; font-size: 0.8rem; color: #0f172a; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .btn-visit-store { display: flex; align-items: center; gap: 6px; padding: 0.4rem 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: 0.78rem; font-weight: 600; color: #64748b; background: white; text-decoration: none; transition: all 0.15s; }
        .btn-visit-store:hover { border-color: #2563eb; color: #2563eb; }

        .user-menu-btn { display: flex; align-items: center; gap: 7px; padding: 0.4rem 0.75rem; border-radius: 9px; cursor: pointer; background: none; border: none; transition: all 0.15s; }
        .user-menu-btn:hover { background: #f1f5f9; }
        .u-avatar { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1d4ed8); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem; }
        .u-name { font-size: 0.8rem; font-weight: 600; color: #0f172a; }

        /* NOTIFICATION BELL */
        .notif-btn { position: relative; width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; font-size: 0.95rem; transition: all 0.15s; }
        .notif-btn:hover { background: #e2e8f0; color: #0f172a; }
        .notif-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 700; min-width: 16px; height: 16px; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0 3px; border: 2px solid white; }
        .notif-dropdown { width: 360px; max-height: 480px; overflow-y: auto; padding: 0 !important; }
        .notif-header { display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1rem 0.5rem; border-bottom: 1px solid #f1f5f9; }
        .notif-header h6 { margin: 0; font-size: 0.875rem; font-weight: 700; color: #0f172a; }
        .notif-item { display: flex; gap: 10px; padding: 0.75rem 1rem; border-bottom: 1px solid #f8fafc; transition: background 0.1s; cursor: pointer; text-decoration: none; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.non-lue { background: #fafbff; }
        .notif-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; flex-shrink: 0; }
        .notif-content { flex: 1; min-width: 0; }
        .notif-titre { font-size: 0.82rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
        .notif-msg { font-size: 0.76rem; color: #64748b; line-height: 1.4; margin-top: 2px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .notif-temps { font-size: 0.68rem; color: #94a3b8; margin-top: 3px; }
        .notif-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563eb; flex-shrink: 0; margin-top: 5px; }
        .notif-empty { padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.85rem; }
        @media (max-width: 480px) { .notif-dropdown { width: 300px; } }

        /* CONTENT */
        .main-content { padding: 1.75rem; flex: 1; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* CARDS */
        .card { border: 1px solid #f1f5f9 !important; border-radius: 16px !important; box-shadow: 0 1px 6px rgba(0,0,0,0.04) !important; }

        /* TABLES */
        .table { background: white; }
        .table thead th { background: #f8fafc; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .table tbody tr:hover { background: #fafbfc; }

        /* BUTTONS */
        .btn-primary { background: #2563eb !important; border: none !important; border-radius: 10px; font-weight: 600; }
        .btn-primary:hover { background: #1d4ed8 !important; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }

        /* TABLES RESPONSIVES — toutes les pages */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        @media (max-width: 768px) {
            /* Titres de page */
            h1.h3, h1 { font-size: 1.2rem !important; }
            /* Page header (titre + bouton) empilés */
            .d-flex.justify-content-between.align-items-center.mb-4 {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem;
            }
            .d-flex.justify-content-between.align-items-center.mb-4 .d-flex {
                width: 100%;
            }
            .d-flex.justify-content-between.align-items-center.mb-4 .btn {
                flex: 1;
                justify-content: center;
                text-align: center;
            }
            /* Filtres en colonne */
            .card .row.g-2 > [class*="col-"],
            .card .row.g-3 > [class*="col-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            /* Toutes les tables dans un wrapper scrollable */
            .card .card-body { padding: 0.75rem !important; }
            /* Marges */
            .mb-4 { margin-bottom: 1rem !important; }
        }
        /* FORMULAIRES */
        .form-control, .form-select { font-size: 0.9rem; }

        /* ALERTS */
        .alert { border: none; border-radius: 12px; font-size: 0.875rem; }

        /* DROPDOWN */
        .dropdown-menu { border: none !important; box-shadow: 0 8px 30px rgba(0,0,0,0.1) !important; border-radius: 14px !important; padding: 0.4rem !important; }
        .dropdown-item { border-radius: 8px; padding: 0.55rem 0.875rem; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; color: #374151; }
        .dropdown-item:hover { background: #f1f5f9; color: #0f172a; }
        .dropdown-item i { width: 14px; color: #94a3b8; font-size: 0.8rem; }

        /* ══════════════════════════════════════════
           RESPONSIVE COMPLET — toutes les pages
           ══════════════════════════════════════════ */

        /* ── Tablette (769px–1024px) : sidebar icônes ── */
        @media (max-width: 1024px) and (min-width: 769px) {
            #sidebar-wrapper { width: 64px; min-width: 64px; }
            #sidebar-wrapper .sidebar-text,
            #sidebar-wrapper .sidebar-logo-text,
            #sidebar-wrapper .sidebar-section-label,
            #sidebar-wrapper .sidebar-footer-info,
            #sidebar-wrapper .boutique-chevron { display: none !important; }
            #sidebar-wrapper .nav-item a { justify-content: center; padding: 0.75rem; }
            #sidebar-wrapper .nav-item a i { margin: 0; }
            #sidebar-wrapper .sidebar-logo { justify-content: center; padding: 1.25rem 0; }
            #sidebar-wrapper .sidebar-boutique { justify-content: center; padding: 0.75rem 0; }
            #sidebar-wrapper .sidebar-footer { justify-content: center; }
            #page-content-wrapper { margin-left: 64px; }
        }

        /* ── Mobile (≤ 768px) : sidebar off-screen, contenu pleine largeur ── */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                width: 260px; min-width: 260px;
                transform: translateX(-100%);
                transition: transform 0.28s ease;
                box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            }
            #wrapper.mobile-open #sidebar-wrapper { transform: translateX(0); }
            #sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(15,23,42,0.45); z-index: 999;
                backdrop-filter: blur(2px);
            }
            #wrapper.mobile-open #sidebar-overlay { display: block; }
            #page-content-wrapper { margin-left: 0 !important; width: 100%; }
            #wrapper.toggled #page-content-wrapper { margin-left: 0 !important; }

            .main-content { padding: 1rem; }
            .topbar { padding: 0.65rem 1rem; }
            .tb-b-name { max-width: 80px; }
            .btn-visit-store span { display: none; }
            .btn-visit-store { padding: 0.4rem 0.6rem; }

            /* ── En-têtes de page ── */
            h1 { font-size: 1.25rem !important; }
            h1.h3, h1.h4, h1.h5 { font-size: 1.1rem !important; }

            /* ── Page header titre + bouton ── */
            .d-flex.justify-content-between.align-items-center,
            .d-flex.align-items-center.justify-content-between {
                flex-wrap: wrap; gap: 0.75rem;
            }

            /* ── Toutes les colonnes Bootstrap col-md-* → 100% ── */
            .col-md-1, .col-md-2, .col-md-3, .col-md-4,
            .col-md-5, .col-md-6, .col-md-7, .col-md-8,
            .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            /* ── Filtres en colonne ── */
            .row.g-2 > [class*="col-"],
            .row.g-3 > [class*="col-"] {
                flex: 0 0 100% !important; max-width: 100% !important;
            }

            /* ── Cards padding ── */
            .card-body { padding: 0.875rem !important; }
            .card-body.p-0 { padding: 0 !important; }

            /* ── Tables ── */
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

            /* ── Formulaires ── */
            .form-control, .form-select, .input-group { width: 100%; }

            /* ── Boutons ── */
            .d-flex.gap-2 { flex-wrap: wrap; }
            .btn { white-space: nowrap; }
        }

        /* ── Stat cards : 2 colonnes sur téléphone ── */
        @media (max-width: 768px) and (min-width: 400px) {
            /* Stat cards dans row.mb-4 : 2 par ligne */
            .row.g-2.mb-4 > .col-md-3,
            .row.g-2.mb-4 > .col-md-4,
            .row.mb-4 > .col-md-3,
            .row.mb-4 > .col-md-4 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }

        /* ── Très petit écran (< 480px) ── */
        @media (max-width: 480px) {
            .topbar-right .btn-visit-store { display: none; }
            .topbar-right .tb-b-name { display: none; }
            .main-content { padding: 0.6rem; }
            .topbar { padding: 0.5rem 0.75rem; gap: 0.5rem; }
            .toggle-btn { width: 30px; height: 30px; font-size: 0.8rem; }
            .btn { font-size: 0.82rem; padding: 0.4rem 0.75rem; }
            /* Stat cards 1 par ligne en dessous de 400px */
            .row.mb-4 > .col-md-3,
            .row.mb-4 > .col-md-4 {
                flex: 0 0 100% !important; max-width: 100% !important;
            }
        }

        /* ── col-xl / col-lg toujours en colonne sur mobile ── */
        @media (max-width: 1199px) {
            .col-xl-4, .col-xl-8 { flex: 0 0 100%; max-width: 100%; }
        }
        @media (max-width: 991px) {
            .col-lg-4, .col-lg-8, .col-lg-3, .col-lg-6 { flex: 0 0 100%; max-width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div id="wrapper">
<div id="sidebar-overlay" onclick="closeMobileSidebar()"></div>

{{-- SIDEBAR --}}
<div id="sidebar-wrapper">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="logo-icon"><img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo"></div>
        <span class="sidebar-logo-text">{{ config('app.name', 'Nafalo') }}</span>
    </a>

    @php
        $boutiques = \App\Models\Boutique::orderBy('nom')->get();
        $boutiqueCourante = \App\Models\Boutique::find(session('boutique_id'));
        function isActiveRoute($route) { return request()->routeIs($route) ? 'active' : ''; }
    @endphp

    <div class="sidebar-boutique dropdown" data-bs-toggle="dropdown">
        <div class="b-avatar">
            @if($boutiqueCourante?->logo)
                <img src="{{ asset('storage/' . $boutiqueCourante->logo) }}" alt="">
            @else
                {{ strtoupper(substr($boutiqueCourante?->nom ?? 'B', 0, 1)) }}
            @endif
        </div>
        <span class="b-name sidebar-text">{{ $boutiqueCourante?->nom ?? 'Ma boutique' }}</span>
        <i class="fas fa-chevron-down boutique-chevron sidebar-text"></i>
    </div>
    <ul class="dropdown-menu ms-2" style="min-width:210px;">
        @foreach($boutiques as $b)
        <li><a class="dropdown-item" href="{{ route('admin.boutiques.select', $b->id) }}">
            <div style="width:22px;height:22px;border-radius:5px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:#2563eb;flex-shrink:0;">{{ strtoupper(substr($b->nom,0,1)) }}</div>
            {{ $b->nom }}
            @if(session('boutique_id')==$b->id)<i class="fas fa-check ms-auto" style="color:#22c55e;"></i>@endif
        </a></li>
        @endforeach
        <li><hr class="dropdown-divider my-1"></li>
        <li><a class="dropdown-item" href="{{ route('admin.boutiques.create') }}"><i class="fas fa-plus" style="color:#2563eb;"></i> Créer une boutique</a></li>
    </ul>

    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="{{ isActiveRoute('admin.dashboard') }}">
                <i class="fas fa-home"></i><span class="sidebar-text">Accueil</span>
            </a>
        </div>
        <div class="sidebar-section-label sidebar-text">Boutique</div>
        <div class="nav-item">
            <a href="{{ route('admin.produits.index') }}" class="{{ isActiveRoute('admin.produits.*') }}">
                <i class="fas fa-box"></i><span class="sidebar-text">Produits</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.clients.index') }}" class="{{ isActiveRoute('admin.clients.*') }}">
                <i class="fas fa-users"></i><span class="sidebar-text">Clients</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.transactions.index') }}" class="{{ isActiveRoute('admin.transactions.*') }}">
                <i class="fas fa-wallet"></i><span class="sidebar-text">Revenus</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.statistiques.ventes') }}" class="{{ isActiveRoute('admin.statistiques.*') }}">
                <i class="fas fa-chart-line"></i><span class="sidebar-text">Analytiques</span>
            </a>
        </div>
        <div class="sidebar-section-label sidebar-text">Marketing</div>
        <div class="nav-item">
            <a href="{{ route('admin.codes-promo.index') }}" class="{{ isActiveRoute('admin.codes-promo.*') }}">
                <i class="fas fa-percent"></i><span class="sidebar-text">Réductions</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.pixels.index') }}" class="{{ isActiveRoute('admin.pixels.*') }}">
                <i class="fas fa-bullseye"></i><span class="sidebar-text">Pixels</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.avis.index') }}" class="{{ isActiveRoute('admin.avis.*') }}">
                <i class="fas fa-star"></i><span class="sidebar-text">Avis</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.copublications.index') }}" class="{{ isActiveRoute('admin.copublications.*') }}"
               style="position:relative;">
                <i class="fas fa-handshake"></i>
                <span class="sidebar-text">Co-publication</span>
                @php
                    try {
                        $nbInvitCopub = auth()->user()?->invitationsCopublicationEnAttente()?->count() ?? 0;
                    } catch (\Exception $e) {
                        $nbInvitCopub = 0;
                    }
                @endphp
                @if($nbInvitCopub > 0)
                    <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;padding:2px 6px;">{{ $nbInvitCopub }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.kyc.index') }}" class="{{ isActiveRoute('admin.kyc.*') }}" style="position:relative;">
                <i class="fas fa-id-card"></i><span class="sidebar-text">Vérification KYC</span>
                @php
                    try {
                        $kycStatut = auth()->user()?->kyc?->statut ?? 'non_soumis';
                        $showKycBadge = in_array($kycStatut, ['non_soumis', 'rejete']);
                    } catch(\Throwable $e) { $showKycBadge = false; }
                @endphp
                @if($showKycBadge)
                    <span class="badge bg-danger ms-1" style="font-size:0.6rem;padding:2px 5px;">!</span>
                @endif
            </a>
        </div>
        <div class="sidebar-section-label sidebar-text">Paramètres</div>
        <div class="nav-item">
            <a href="{{ route('admin.configurations.general') }}" class="{{ isActiveRoute('admin.configurations.general') ?: isActiveRoute('admin.configurations.paiement') ?: isActiveRoute('admin.configurations.email') ?: '' }}">
                <i class="fas fa-cog"></i><span class="sidebar-text">Paramètres</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.configurations.apparence') }}" class="{{ isActiveRoute('admin.configurations.apparence') }}">
                <i class="fas fa-palette"></i><span class="sidebar-text">Apparence</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="sf-avatar">{{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}</div>
        <div class="sidebar-footer-info sidebar-text">
            <div class="sf-name">{{ Auth::user()->nom }}</div>
            <div class="sf-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div id="page-content-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="toggle-btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="dropdown">
                <div class="tb-boutique" data-bs-toggle="dropdown">
                    <div class="b-avatar" style="width:22px;height:22px;border-radius:5px;">
                        @if($boutiqueCourante?->logo)
                            <img src="{{ asset('storage/' . $boutiqueCourante->logo) }}" alt="">
                        @else
                            {{ strtoupper(substr($boutiqueCourante?->nom ?? 'B', 0, 1)) }}
                        @endif
                    </div>
                    <span class="tb-b-name">{{ $boutiqueCourante?->nom ?? 'Ma boutique' }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.6rem;color:#94a3b8;"></i>
                </div>
                <ul class="dropdown-menu" style="min-width:210px;">
                    @foreach($boutiques as $b)
                    <li><a class="dropdown-item" href="{{ route('admin.boutiques.select', $b->id) }}">
                        <div style="width:22px;height:22px;border-radius:5px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:#2563eb;flex-shrink:0;">{{ strtoupper(substr($b->nom,0,1)) }}</div>
                        {{ $b->nom }}
                        @if(session('boutique_id')==$b->id)<i class="fas fa-check ms-auto" style="color:#22c55e;"></i>@endif
                    </a></li>
                    @endforeach
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.boutiques.choisir') }}"><i class="fas fa-exchange-alt" style="color:#2563eb;"></i> Changer de boutique</a></li>
                </ul>
            </div>
        </div>
        <div class="topbar-right">
            @if($boutiqueCourante)
            <a href="{{ url('/boutique') }}" target="_blank" class="btn-visit-store">
                <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i> Visiter ma boutique
            </a>
            @endif
            {{-- Cloche notifications --}}
            <div class="dropdown">
                <button class="notif-btn" id="notif-bell-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-badge" id="notif-count" style="display:none;">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown" id="notif-dropdown">
                    <div class="notif-header">
                        <h6>Notifications</h6>
                        <button onclick="marquerToutesLues()" class="btn btn-sm btn-light" style="font-size:0.72rem;padding:2px 8px;border-radius:6px;">Tout lire</button>
                    </div>
                    <div id="notif-list">
                        <div class="notif-empty"><i class="fas fa-bell-slash mb-2 d-block" style="font-size:1.5rem;"></i>Aucune notification</div>
                    </div>
                    <div style="padding:0.6rem 1rem;border-top:1px solid #f1f5f9;text-align:center;">
                        <a href="{{ route('admin.notifications.index') }}" style="font-size:0.78rem;color:#2563eb;text-decoration:none;font-weight:600;">Voir toutes les notifications</a>
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button class="user-menu-btn" data-bs-toggle="dropdown">
                    <div class="u-avatar">{{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}</div>
                    <span class="u-name d-none d-md-inline">{{ Auth::user()->nom }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.6rem;color:#94a3b8;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:190px;">
                    <li><div class="px-3 py-2" style="font-size:0.72rem;color:#94a3b8;">{{ Auth::user()->email }}</div></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.configurations.general') }}"><i class="fas fa-cog"></i> Paramètres</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" style="background:#f0fdf4;color:#166534;border-left:4px solid #22c55e;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>
</div>

{{-- Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">Confirmation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"></div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger rounded-3" id="confirmModalOk">Confirmer</button>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
<script>
/* ── Notifications Bell ──────────────────────────────────────────── */
(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function chargerNotifications() {
        fetch('{{ route("admin.notifications.recentes") }}')
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                const badge = document.getElementById('notif-count');
                const list  = document.getElementById('notif-list');

                // Badge
                if (data.nonLues > 0) {
                    badge.textContent = data.nonLues > 9 ? '9+' : data.nonLues;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }

                // Liste
                if (data.notifications.length === 0) {
                    list.innerHTML = '<div class="notif-empty"><i class="fas fa-bell-slash mb-2 d-block" style="font-size:1.5rem;"></i>Aucune notification</div>';
                    return;
                }

                list.innerHTML = data.notifications.map(n => `
                    <div class="notif-item ${n.lue ? '' : 'non-lue'}" onclick="ouvrirNotif(${n.id}, '${n.lien ?? ''}')">
                        <div class="notif-icon" style="background:${n.couleurBg};color:${n.couleur};">
                            <i class="${n.icone}"></i>
                        </div>
                        <div class="notif-content">
                            <div class="notif-titre">${n.titre}</div>
                            <div class="notif-msg">${n.message}</div>
                            <div class="notif-temps">${n.temps}</div>
                        </div>
                        ${!n.lue ? '<div class="notif-dot"></div>' : ''}
                    </div>
                `).join('');
            })
            .catch(() => {});
    }

    window.ouvrirNotif = function(id, lien) {
        fetch(`/admin/notifications/${id}/lue`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => {
            chargerNotifications();
            if (lien) window.location.href = lien;
        });
    };

    window.marquerToutesLues = function() {
        fetch('{{ route("admin.notifications.toutes-lues") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => chargerNotifications());
    };

    // Charger au démarrage et toutes les 60 secondes
    chargerNotifications();
    setInterval(chargerNotifications, 60000);

    // Charger aussi quand on ouvre le dropdown
    document.getElementById('notif-bell-btn')?.addEventListener('show.bs.dropdown', chargerNotifications);
})();

/* ── Sidebar Toggle ─────────────────────────────────────────────── */
const wrapper = document.getElementById('wrapper');

function isMobile() { return window.innerWidth <= 768; }

function closeMobileSidebar() {
    wrapper.classList.remove('mobile-open');
}

// Restaurer l'état sidebar sur desktop
if (!isMobile() && localStorage.getItem('sidebarToggled') === 'true') {
    wrapper.classList.add('toggled');
}

document.getElementById('menu-toggle')?.addEventListener('click', () => {
    if (isMobile()) {
        wrapper.classList.toggle('mobile-open');
    } else {
        wrapper.classList.toggle('toggled');
        localStorage.setItem('sidebarToggled', wrapper.classList.contains('toggled'));
    }
});

// Fermer la sidebar mobile si on redimensionne vers desktop
window.addEventListener('resize', () => {
    if (!isMobile()) {
        wrapper.classList.remove('mobile-open');
    }
});
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-confirm-message]');
    if (!btn) return;
    e.preventDefault();
    const modalEl = document.getElementById('confirmModal');
    modalEl.querySelector('.modal-body').textContent = btn.getAttribute('data-confirm-message');
    const okBtn = modalEl.querySelector('#confirmModalOk');
    okBtn.replaceWith(okBtn.cloneNode(true));
    modalEl.querySelector('#confirmModalOk').addEventListener('click', () => {
        const f = btn.getAttribute('data-target-form');
        if (f) document.getElementById(f)?.submit();
        bootstrap.Modal.getInstance(modalEl).hide();
    });
    new bootstrap.Modal(modalEl).show();
});
document.querySelectorAll('.alert').forEach(a => setTimeout(() => { try { bootstrap.Alert.getOrCreateInstance(a).close(); } catch(e){} }, 5000));
</script>
</body>
</html>