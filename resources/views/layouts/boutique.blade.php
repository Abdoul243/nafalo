<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $boutique->nom) — {{ $boutique->nom }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════════════════════════
       DESIGN TOKENS — Light Mode Nafalo (style Chariow)
    ═══════════════════════════════════════════════════════════ */
    :root {
        --bg:          #ffffff;
        --bg-surface:  #f8f9fa;
        --bg-card:     #ffffff;
        --bg-elevated: #ffffff;
        --border:      #e9eaeb;
        --border-hover:#d1d5db;
        --accent:      #7c3aed;
        --accent-hover:#6d28d9;
        --accent-light:rgba(124,58,237,0.10);
        --accent-glow: rgba(124,58,237,0.25);
        --text-1:      #111827;
        --text-2:      #4b5563;
        --text-3:      #9ca3af;
        --green:       #16a34a;
        --green-dim:   rgba(22,163,74,0.12);
        --red:         #dc2626;
        /* overlays adaptés au fond clair */
        --hover-soft:  rgba(0,0,0,0.04);
        --hover-soft2: rgba(0,0,0,0.06);
        --shadow-soft: rgba(0,0,0,0.08);
    }

    /* ═══════════════════════════════════════════════════════════
       BASE
    ═══════════════════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; min-width: 0; }
    html { overflow-x: hidden; scroll-behavior: smooth; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        color: var(--text-1);
        margin: 0;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
    }
    img, video, iframe { max-width: 100%; height: auto; }
    a { text-decoration: none; color: inherit; }
    .serif { font-family: 'Playfair Display', Georgia, serif; }

    /* ═══════════════════════════════════════════════════════════
       NAVBAR
    ═══════════════════════════════════════════════════════════ */
    .store-nav {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .store-nav.scrolled {
        border-bottom-color: var(--border);
        background: rgba(255,255,255,0.97);
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        height: 64px;
        gap: 1.25rem;
    }

    /* Logo */
    .store-brand {
        display: flex; align-items: center; gap: 9px;
        text-decoration: none; flex-shrink: 0;
    }
    .brand-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 900; font-size: 0.9rem;
        flex-shrink: 0; overflow: hidden;
    }
    .brand-icon img {
        width: 100%; height: 100%; object-fit: cover;
        filter: brightness(0) invert(1);
    }
    .brand-name {
        font-weight: 800; font-size: 1rem;
        color: var(--text-1); letter-spacing: -0.01em;
    }

    /* Nav links */
    .nav-links {
        display: flex; align-items: center;
        gap: 0.1rem; flex: 1;
    }
    .nav-lnk {
        padding: 0.42rem 0.85rem;
        border-radius: 8px;
        color: var(--text-2);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .nav-lnk:hover { background: var(--hover-soft); color: var(--text-1); }
    .nav-lnk.active { color: var(--text-1); font-weight: 600; }

    /* Search */
    .nav-search {
        display: flex; align-items: center; gap: 8px;
        background: var(--hover-soft);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.45rem 0.85rem;
        cursor: pointer;
        transition: all 0.15s;
        color: var(--text-3);
        font-size: 0.82rem;
        white-space: nowrap;
    }
    .nav-search:hover {
        background: var(--hover-soft2);
        border-color: var(--border-hover);
        color: var(--text-2);
    }
    .nav-search input {
        background: none; border: none; outline: none;
        color: var(--text-1); font-size: 0.82rem;
        font-family: inherit; width: 120px;
    }
    .nav-search input::placeholder { color: var(--text-3); }

    /* Nav right */
    .nav-right {
        display: flex; align-items: center;
        gap: 0.6rem; flex-shrink: 0;
    }

    /* Connexion btn */
    .btn-connexion {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0.45rem 1rem;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-2);
        font-size: 0.855rem; font-weight: 600;
        cursor: pointer; font-family: inherit;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-connexion:hover {
        border-color: var(--border-hover);
        color: var(--text-1);
        background: var(--hover-soft);
    }
    .btn-connexion .avatar {
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.65rem; font-weight: 800;
    }

    /* Bouton Mes achats (style Chariow) */
    .btn-mes-achats {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 0.5rem 0.95rem;
        background: transparent;
        border: none;
        color: var(--text-1);
        font-size: 0.875rem; font-weight: 600;
        text-decoration: none;
        transition: color 0.15s;
        white-space: nowrap;
    }
    .btn-mes-achats i { font-size: 0.9rem; }
    .btn-mes-achats:hover { color: var(--accent); }

    /* Pilule pays / devise (style Chariow) */
    .nav-currency {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0.5rem 0.9rem;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-1);
        font-size: 0.82rem; font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        font-family: inherit;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .nav-currency:hover { border-color: var(--border-hover); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .nav-currency-flag {
        width: 22px; height: 16px;
        border-radius: 3px; object-fit: cover;
        display: block; flex-shrink: 0;
        box-shadow: 0 0 0 1px rgba(0,0,0,0.06);
    }

    /* ── Modale Choisir un pays ── */
    .country-modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(17,24,39,0.5);
        backdrop-filter: blur(4px);
        z-index: 10000;
        align-items: center; justify-content: center;
        padding: 1rem;
    }
    .country-modal-overlay.open { display: flex; }
    .country-modal {
        background: #fff;
        border-radius: 18px;
        width: 100%; max-width: 460px;
        box-shadow: 0 24px 80px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: cmSlideUp 0.25s ease;
    }
    @keyframes cmSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .country-modal-head {
        display: flex; align-items: center; gap: 10px;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .country-modal-head i { font-size: 1.25rem; color: var(--text-1); }
    .country-modal-head h3 { margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--text-1); flex: 1; }
    .country-modal-close {
        background: none; border: none; cursor: pointer;
        color: var(--text-3); font-size: 1.1rem;
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s;
    }
    .country-modal-close:hover { background: var(--bg-surface); color: var(--text-1); }
    .country-modal-body { padding: 1.25rem 1.5rem 1.5rem; }
    .country-search {
        display: flex; align-items: center; gap: 9px;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.7rem 1rem;
        margin-bottom: 0.9rem;
    }
    .country-search:focus-within { border-color: var(--accent); }
    .country-search i { color: var(--text-3); font-size: 0.85rem; }
    .country-search input {
        border: none; outline: none; flex: 1;
        font-size: 0.92rem; color: var(--text-1);
        background: transparent; font-family: inherit;
    }
    .country-list {
        max-height: 320px;
        overflow-y: auto;
        margin: 0 -0.4rem;
        padding: 0 0.4rem;
    }
    .country-row {
        display: flex; align-items: center; gap: 11px;
        padding: 0.6rem 0.7rem;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.12s;
    }
    .country-row:hover { background: var(--bg-surface); }
    .country-row.active { background: rgba(124,58,237,0.08); }
    .country-row img {
        width: 26px; height: 19px;
        border-radius: 3px; object-fit: cover;
        flex-shrink: 0; box-shadow: 0 0 0 1px rgba(0,0,0,0.06);
    }
    .country-row .cr-name { flex: 1; font-size: 0.9rem; font-weight: 500; color: var(--text-1); }
    .country-row .cr-cur {
        font-size: 0.72rem; font-weight: 700;
        color: var(--text-3);
        background: var(--bg-surface);
        padding: 2px 8px; border-radius: 20px;
    }
    .country-row.active .cr-cur { color: var(--accent); background: rgba(124,58,237,0.12); }
    .country-empty { text-align: center; padding: 2rem; color: var(--text-3); font-size: 0.875rem; }

    /* CTA devenir vendeur */
    .btn-cta-nav {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0.5rem 1.1rem;
        background: var(--accent);
        border: none; border-radius: 10px;
        color: white; font-size: 0.855rem; font-weight: 700;
        cursor: pointer; font-family: inherit;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-cta-nav:hover {
        background: var(--accent-hover);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px var(--accent-glow);
    }

    /* Mobile hamburger */
    .nav-hamburger {
        display: none;
        background: none; border: none; cursor: pointer;
        padding: 0.4rem; color: var(--text-2); font-size: 1.2rem;
        margin-left: auto;
    }

    /* Menu mobile */
    .mobile-menu {
        display: none;
        position: fixed; top: 64px; left: 0; right: 0;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        z-index: 999;
        padding: 0.75rem 1rem 1.25rem;
        flex-direction: column; gap: 0.25rem;
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu .nav-lnk {
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        border-radius: 10px;
        display: flex; align-items: center; gap: 0.6rem;
        color: var(--text-2);
    }
    .mobile-menu .nav-lnk:hover {
        background: var(--hover-soft);
        color: var(--text-1);
    }
    .mobile-menu-divider {
        border: none; border-top: 1px solid var(--border);
        margin: 0.5rem 0;
    }
    .mobile-cta {
        background: var(--accent);
        color: white !important;
        justify-content: center;
        margin-top: 0.25rem;
        font-weight: 700 !important;
    }

    @media (max-width: 900px) {
        .nav-links { display: none; }
        .nav-search { display: none; }
        .nav-hamburger { display: flex; align-items: center; }
        .btn-cta-nav { display: none; }
        .btn-mes-achats span { display: none; }
        #navCurrencyLabel { display: none; }
        .nav-currency { padding: 0.5rem 0.7rem; }
        .btn-connexion span { display: none; }
    }
    @media (max-width: 768px) {
        .nav-inner { padding: 0 1rem; }
    }
    @media (max-width: 480px) {
        .nav-inner { height: 58px; }
        .mobile-menu { top: 58px; }
        .brand-name { font-size: 0.95rem; }
    }

    /* ═══════════════════════════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════════════════════════ */
    .store-alerts {
        max-width: 1280px; margin: 1rem auto 0; padding: 0 2rem;
    }
    .alert-dark {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: var(--text-1);
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 0.75rem;
    }
    .alert-dark.success { border-color: rgba(22,163,74,0.35); background: rgba(22,163,74,0.07); color: #166534; }
    .alert-dark.danger  { border-color: rgba(220,38,38,0.35);  background: rgba(220,38,38,0.07);  color: #991b1b; }
    .alert-dark.info    { border-color: rgba(37,99,235,0.35); background: rgba(37,99,235,0.07); color: #1e40af; }
    .alert-dark i { flex-shrink: 0; }
    .alert-close {
        margin-left: auto; background: none; border: none;
        color: var(--text-3); cursor: pointer; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ═══════════════════════════════════════════════════════════
       CONTENT WRAP
    ═══════════════════════════════════════════════════════════ */
    .store-wrap { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
    @media (max-width: 768px) { .store-wrap { padding: 0 1rem; } }

    /* ═══════════════════════════════════════════════════════════
       DROPDOWN (Bootstrap dark override)
    ═══════════════════════════════════════════════════════════ */
    .dropdown-menu {
        background: var(--bg-elevated) !important;
        border: 1px solid var(--border) !important;
        border-radius: 14px !important;
        padding: 0.5rem !important;
        box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
        min-width: 200px !important;
    }
    .dropdown-item {
        border-radius: 9px !important;
        font-size: 0.875rem !important;
        padding: 0.55rem 0.85rem !important;
        font-weight: 500;
        color: var(--text-2) !important;
    }
    .dropdown-item:hover {
        background: var(--hover-soft) !important;
        color: var(--text-1) !important;
    }
    .dropdown-divider {
        border-color: var(--border) !important;
    }

    /* ═══════════════════════════════════════════════════════════
       WHATSAPP FLOAT
    ═══════════════════════════════════════════════════════════ */
    .wa-float {
        position: fixed; bottom: 24px; right: 24px;
        z-index: 9999;
        display: flex; flex-direction: column; align-items: flex-end; gap: 10px;
    }
    .wa-float-label {
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        color: var(--text-1);
        font-size: 0.75rem; font-weight: 600;
        padding: 0.35rem 0.85rem; border-radius: 20px;
        white-space: nowrap;
        opacity: 0; transform: translateX(8px);
        transition: all 0.25s;
        pointer-events: none;
    }
    .wa-float:hover .wa-float-label { opacity: 1; transform: translateX(0); }
    .wa-float-btn {
        width: 54px; height: 54px; border-radius: 50%;
        background: #25d366;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.5rem;
        text-decoration: none;
        box-shadow: 0 4px 20px rgba(37,211,102,0.45);
        transition: transform 0.2s, box-shadow 0.2s;
        animation: wa-pulse 2.5s infinite;
    }
    .wa-float-btn:hover { transform: scale(1.1); box-shadow: 0 8px 28px rgba(37,211,102,0.6); color: white; }
    @keyframes wa-pulse {
        0%, 100% { box-shadow: 0 4px 20px rgba(37,211,102,0.45); }
        50%       { box-shadow: 0 4px 32px rgba(37,211,102,0.7);  }
    }
    @media (max-width: 480px) {
        .wa-float { bottom: 16px; right: 16px; }
        .wa-float-btn { width: 48px; height: 48px; font-size: 1.3rem; }
    }

    /* ═══════════════════════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════════════════════ */
    .site-footer {
        background: var(--bg-surface);
        border-top: 1px solid var(--border);
        padding: 4rem 0 0;
        margin-top: 0;
    }
    .footer-inner { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
    .footer-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr 1fr;
        gap: 3rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid var(--border);
    }
    .footer-section-label {
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: var(--text-3); margin: 0 0 1.25rem;
    }
    .footer-link {
        display: flex; align-items: center; gap: 9px;
        color: var(--text-3); font-size: 0.84rem;
        text-decoration: none; margin-bottom: 0.65rem;
        transition: color 0.15s;
    }
    .footer-link:hover { color: var(--text-1); }
    .footer-link i { width: 15px; font-size: 0.78rem; color: var(--text-3); }
    .footer-social {
        width: 36px; height: 36px; border-radius: 9px;
        background: var(--hover-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-3); font-size: 1rem; text-decoration: none;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }
    .footer-social:hover { color: var(--accent); border-color: var(--accent); }
    .footer-guarantee {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 0.7rem;
    }
    .footer-guarantee i { color: var(--accent); width: 15px; font-size: 0.78rem; flex-shrink: 0; }
    .footer-guarantee span { font-size: 0.83rem; color: var(--text-3); }
    .footer-bottom {
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 1.4rem 0; flex-wrap: wrap; gap: 0.75rem;
    }
    .footer-bottom span { font-size: 0.77rem; color: var(--text-3); }
    @media (max-width: 900px) {
        .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
    }
    @media (max-width: 480px) {
        .footer-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        .footer-inner { padding: 0 1rem; }
        .footer-bottom { flex-direction: column; text-align: center; }
    }

    /* ── Filet de sécurité responsive : tableaux scrollables sur petit écran ── */
    @media (max-width: 768px) {
        table { display: block; width: max-content; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    }
    </style>

    @stack('styles')

    {{-- ── PIXELS MARKETING (head) ── --}}
    @php
        $pixelsHeader = $boutique->pixels->where('est_actif', true)->where('emplacement', 'header');
        $pixelsFooter = $boutique->pixels->where('est_actif', true)->where('emplacement', 'footer');
        $pixelsBody   = $boutique->pixels->where('est_actif', true)->where('emplacement', 'body');
    @endphp
    @foreach($pixelsHeader as $pixel){!! $pixel->code_pixel !!}@endforeach
</head>

<body>

{{-- ═══════════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════════ --}}
<nav class="store-nav" id="store-nav">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ route('boutique.accueil') }}" class="store-brand">
            <div class="brand-icon">
                @if($boutique->logo)
                    <img src="{{ $boutique->logo_url }}" alt="{{ $boutique->nom }}">
                @else
                    {{ strtoupper(substr($boutique->nom, 0, 1)) }}
                @endif
            </div>
            <span class="brand-name">{{ $boutique->nom }}</span>
        </a>

        {{-- Liens desktop --}}
        <div class="nav-links">
            <a href="{{ route('boutique.accueil') }}" class="nav-lnk {{ request()->routeIs('boutique.accueil') || request()->routeIs('boutique.produit.index') ? 'active' : '' }}">
                Produits
            </a>
            <a href="{{ route('boutique.accueil') }}#apropos" class="nav-lnk">
                À propos
            </a>
            @if($boutique->email)
            <a href="mailto:{{ $boutique->email }}" class="nav-lnk">
                Contact
            </a>
            @endif
        </div>

        {{-- Droite --}}
        <div class="nav-right">
            <a href="{{ route('client.mes-achats.index') }}" class="btn-mes-achats">
                <i class="fas fa-shopping-bag"></i>
                <span>Mes achats</span>
            </a>

            @if(session('client_acces_' . $boutique->id))
                @php $clientEmail = session('client_acces_' . $boutique->id); @endphp
                <div class="dropdown">
                    <button class="btn-connexion" data-bs-toggle="dropdown">
                        <div class="avatar">{{ strtoupper(substr($clientEmail, 0, 1)) }}</div>
                        <i class="fas fa-chevron-down" style="font-size:0.6rem;opacity:0.5;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('client.mes-achats.index') }}">
                                <i class="fas fa-shopping-bag me-2" style="color:var(--accent);"></i>
                                Mes achats
                            </a>
                        </li>
                        <li><hr class="dropdown-divider mx-2 my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('client.acces.deconnexion') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color:var(--red) !important;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif

            {{-- Sélecteur pays / devise (ouvre la modale) --}}
            <button type="button" class="nav-currency" id="navCurrencyBtn" onclick="openCountryModal()">
                <img class="nav-currency-flag" id="navCurrencyFlag" src="https://flagcdn.com/48x36/ci.png" alt="">
                <span id="navCurrencyLabel">Côte d'Ivoire (FCFA)</span>
                <i class="fas fa-chevron-down" style="font-size:0.6rem;opacity:0.5;"></i>
            </button>

            <button class="nav-hamburger" id="nav-hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</nav>

{{-- ── MENU MOBILE ── --}}
<div class="mobile-menu" id="mobile-menu">
    <a href="{{ route('boutique.accueil') }}" class="nav-lnk">
        <i class="fas fa-box" style="font-size:0.85rem;"></i> Produits
    </a>
    <a href="{{ route('client.mes-achats.index') }}" class="nav-lnk">
        <i class="fas fa-shopping-bag" style="font-size:0.85rem;"></i> Mes achats
    </a>
    @if($boutique->email)
    <a href="mailto:{{ $boutique->email }}" class="nav-lnk">
        <i class="fas fa-envelope" style="font-size:0.85rem;"></i> Contact
    </a>
    @endif
    <hr class="mobile-menu-divider">
    @if(session('client_acces_' . $boutique->id))
    <a href="{{ route('client.mes-achats.index') }}" class="nav-lnk">
        <i class="fas fa-shopping-bag" style="font-size:0.85rem;"></i> Mes achats
    </a>
    @else
    <a href="{{ route('client.acces.demande') }}" class="nav-lnk">
        <i class="fas fa-user" style="font-size:0.85rem;"></i> Connexion
    </a>
    <a href="{{ route('boutique.produit.index') }}" class="nav-lnk mobile-cta">
        Explorer la boutique →
    </a>
    @endif
</div>

{{-- ── ALERTES ── --}}
<div class="store-alerts">
    @if(session('success'))
    <div class="alert-dark success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert-dark danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif
    @if(session('info'))
    <div class="alert-dark info">
        <i class="fas fa-info-circle"></i>
        {{ session('info') }}
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif
</div>

{{-- ── CONTENU ── --}}
<main>@yield('content')</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">

            {{-- Brand --}}
            <div id="apropos">
                <a href="{{ route('boutique.accueil') }}" style="display:flex;align-items:center;gap:10px;margin-bottom:1.25rem;text-decoration:none;">
                    <div style="width:38px;height:38px;border-radius:10px;background:var(--accent);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                        @if($boutique->logo)
                            <img src="{{ $boutique->logo_url }}" alt="" style="width:100%;height:100%;object-fit:cover;filter:brightness(0) invert(1);">
                        @else
                            <span style="color:white;font-weight:900;font-size:1rem;">{{ strtoupper(substr($boutique->nom, 0, 1)) }}</span>
                        @endif
                    </div>
                    <span style="font-weight:800;font-size:1rem;color:var(--text-1);">{{ $boutique->nom }}</span>
                </a>
                <p style="font-size:0.83rem;color:var(--text-3);line-height:1.75;margin:0 0 1.5rem;max-width:260px;">
                    {{ $boutique->description ?: 'Produits digitaux de qualité — téléchargement instantané, accès à vie.' }}
                </p>
                {{-- Réseaux sociaux --}}
                @php $rs = $boutique->reseaux_sociaux ?? []; @endphp
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                    @if(!empty($rs['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rs['whatsapp']) }}" target="_blank"
                       class="footer-social" style="color:#25d366 !important;" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                    @if(!empty($rs['instagram']))
                    <a href="{{ $rs['instagram'] }}" target="_blank"
                       class="footer-social" title="Instagram"
                       onmouseover="this.style.background='#e1306c'" onmouseout="this.style.background='rgba(0,0,0,0.04)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if(!empty($rs['facebook']))
                    <a href="{{ $rs['facebook'] }}" target="_blank"
                       class="footer-social" title="Facebook"
                       onmouseover="this.style.background='#1877f2'" onmouseout="this.style.background='rgba(0,0,0,0.04)'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if(!empty($rs['tiktok']))
                    <a href="{{ $rs['tiktok'] }}" target="_blank"
                       class="footer-social" title="TikTok"
                       onmouseover="this.style.background='#111'" onmouseout="this.style.background='rgba(0,0,0,0.04)'">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <p class="footer-section-label">Navigation</p>
                <a href="{{ route('boutique.accueil') }}" class="footer-link">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="{{ route('boutique.produit.index') }}" class="footer-link">
                    <i class="fas fa-box"></i> Produits
                </a>
                <a href="{{ route('client.mes-achats.index') }}" class="footer-link">
                    <i class="fas fa-shopping-bag"></i> Mes achats
                </a>
                @if($boutique->email)
                <a href="mailto:{{ $boutique->email }}" class="footer-link">
                    <i class="fas fa-envelope"></i> Contact
                </a>
                @endif
            </div>

            {{-- Légal --}}
            <div>
                <p class="footer-section-label">Légal</p>
                <a href="{{ route('legal.conditions') }}" class="footer-link">Conditions d'utilisation</a>
                <a href="{{ route('legal.confidentialite') }}" class="footer-link">Confidentialité</a>
                <a href="{{ route('legal.mentions') }}" class="footer-link">Mentions légales</a>
                <a href="{{ route('legal.remboursement') }}" class="footer-link">Remboursement</a>
            </div>

            {{-- Garanties --}}
            <div>
                <p class="footer-section-label">Garanties</p>
                @foreach([
                    ['bolt',     'Livraison instantanée'],
                    ['lock',     'Paiement sécurisé'],
                    ['infinity', 'Accès à vie'],
                    ['medal',    'Satisfait ou remboursé'],
                    ['headset',  'Support disponible'],
                ] as [$icon, $text])
                <div class="footer-guarantee">
                    <i class="fas fa-{{ $icon }}"></i>
                    <span>{{ $text }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <span>© {{ date('Y') }} <strong style="color:var(--text-2);">{{ $boutique->nom }}</strong> · Tous droits réservés · Propulsé par <strong style="color:var(--accent);">Nafalo</strong></span>
            <span>Ce site n'est pas affilié à Facebook, Instagram ou Meta.</span>
        </div>
    </div>
</footer>

{{-- ── SCRIPTS ── --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Navbar scroll
window.addEventListener('scroll', function() {
    document.getElementById('store-nav').classList.toggle('scrolled', window.scrollY > 10);
});

// Hamburger
const hamburger = document.getElementById('nav-hamburger');
const mobileMenu = document.getElementById('mobile-menu');
if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function() {
        const isOpen = mobileMenu.classList.toggle('open');
        hamburger.innerHTML = isOpen
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    });
    document.addEventListener('click', function(e) {
        if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.remove('open');
            hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        }
    });
}

// Search: focus on ⌘K / Ctrl+K
document.addEventListener('keydown', function(e) {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const inp = document.querySelector('.nav-search input');
        if (inp) inp.focus();
    }
});
</script>

@stack('scripts')

{{-- ── PIXELS MARKETING (footer/body) ── --}}
@foreach($pixelsFooter as $pixel){!! $pixel->code_pixel !!}@endforeach
@foreach($pixelsBody as $pixel){!! $pixel->code_pixel !!}@endforeach

{{-- ══ BOUTON WHATSAPP FLOTTANT ══ --}}
@php
    $waNumber = $boutique->reseaux_sociaux['whatsapp'] ?? null;
@endphp
@if($waNumber)
<div class="wa-float">
    <span class="wa-float-label">💬 Besoin d'aide ?</span>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}"
       target="_blank" rel="noopener noreferrer"
       class="wa-float-btn" title="Nous contacter sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
@endif

{{-- ══ MODALE CHOISIR UN PAYS ══ --}}
<div class="country-modal-overlay" id="countryModal" onclick="if(event.target===this) closeCountryModal()">
    <div class="country-modal">
        <div class="country-modal-head">
            <i class="fas fa-globe"></i>
            <h3>Choisir un pays</h3>
            <button type="button" class="country-modal-close" onclick="closeCountryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="country-modal-body">
            <div class="country-search">
                <i class="fas fa-search"></i>
                <input type="text" id="countrySearchInput" placeholder="Rechercher un pays..." autocomplete="off">
            </div>
            <div class="country-list" id="countryList"></div>
        </div>
    </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════════
   DEVISE GLOBALE — tous les pays du monde, drapeaux réels
   Chaque pays affiche SA devise locale (taux de change en direct),
   pour que le client ne quitte jamais le site. Les pays GeniusPay
   sont marqués (paiement natif). Prix base XOF → conversion data-xof.
═══════════════════════════════════════════════════════════ */
(function () {
    // ── Pays disponibles sur GeniusPay (paiement natif) ──
    const GP = ['CI','SN','ML','BF','BJ','TG','NE','GW','CM','GA','CG','CD','KE','RW','SL','UG','ZM','GH','NG','TZ'];

    // ── Devise locale (ISO 4217) par pays — USD par défaut si absent ──
    const CUR = {
        CI:'XOF',SN:'XOF',ML:'XOF',BF:'XOF',BJ:'XOF',TG:'XOF',NE:'XOF',GW:'XOF',
        CM:'XAF',GA:'XAF',CG:'XAF',TD:'XAF',GQ:'XAF',
        CD:'CDF',KE:'KES',RW:'RWF',SL:'SLE',UG:'UGX',ZM:'ZMW',GH:'GHS',NG:'NGN',TZ:'TZS',
        ZA:'ZAR',ET:'ETB',MA:'MAD',DZ:'DZD',TN:'TND',EG:'EGP',AO:'AOA',MZ:'MZN',BW:'BWP',
        NA:'NAD',MW:'MWK',MU:'MUR',MR:'MRU',GM:'GMD',GN:'GNF',LR:'LRD',LY:'LYD',SD:'SDG',
        SS:'SSP',SO:'SOS',DJ:'DJF',ER:'ERN',BI:'BIF',KM:'KMF',CV:'CVE',ST:'STN',SC:'SCR',
        SZ:'SZL',LS:'LSL',ZW:'ZWL',MG:'MGA',
        US:'USD',CA:'CAD',MX:'MXN',BR:'BRL',AR:'ARS',CL:'CLP',CO:'COP',PE:'PEN',VE:'VES',
        UY:'UYU',PY:'PYG',BO:'BOB',GT:'GTQ',HN:'HNL',NI:'NIO',CR:'CRC',PA:'PAB',
        CU:'CUP',HT:'HTG',JM:'JMD',TT:'TTD',BS:'BSD',BB:'BBD',BZ:'BZD',GY:'GYD',SR:'SRD',
        EC:'USD',SV:'USD',
        FR:'EUR',DE:'EUR',ES:'EUR',IT:'EUR',BE:'EUR',NL:'EUR',PT:'EUR',IE:'EUR',AT:'EUR',
        FI:'EUR',GR:'EUR',LU:'EUR',CY:'EUR',MT:'EUR',SK:'EUR',SI:'EUR',EE:'EUR',LV:'EUR',
        LT:'EUR',HR:'EUR',MC:'EUR',SM:'EUR',VA:'EUR',AD:'EUR',ME:'EUR',
        GB:'GBP',CH:'CHF',LI:'CHF',NO:'NOK',SE:'SEK',DK:'DKK',PL:'PLN',CZ:'CZK',HU:'HUF',
        RO:'RON',BG:'BGN',RS:'RSD',RU:'RUB',UA:'UAH',BY:'BYN',MD:'MDL',AL:'ALL',MK:'MKD',
        BA:'BAM',IS:'ISK',GE:'GEL',AM:'AMD',AZ:'AZN',
        CN:'CNY',JP:'JPY',IN:'INR',ID:'IDR',PK:'PKR',BD:'BDT',TH:'THB',VN:'VND',PH:'PHP',
        MY:'MYR',SG:'SGD',KR:'KRW',KP:'KPW',LK:'LKR',NP:'NPR',MM:'MMK',KH:'KHR',LA:'LAK',
        MN:'MNT',KZ:'KZT',UZ:'UZS',TJ:'TJS',TM:'TMT',KG:'KGS',BT:'BTN',BN:'BND',TL:'USD',MV:'MVR',
        SA:'SAR',AE:'AED',QA:'QAR',KW:'KWD',BH:'BHD',OM:'OMR',JO:'JOD',LB:'LBP',IL:'ILS',
        TR:'TRY',IR:'IRR',IQ:'IQD',SY:'SYP',YE:'YER',AF:'AFN',
        AU:'AUD',NZ:'NZD',FJ:'FJD',PG:'PGK',SB:'SBD',VU:'VUV',WS:'WST',TO:'TOP',
        KI:'AUD',NR:'AUD',TV:'AUD',FM:'USD',MH:'USD',PW:'USD',
        AG:'XCD',DM:'XCD',GD:'XCD',KN:'XCD',LC:'XCD',VC:'XCD',
    };

    // ── Taux de secours (base XOF) si l'API de change est indisponible ──
    const FALLBACK = {
        XOF:1, XAF:1, USD:1/600, EUR:1/656, GBP:1/765, CAD:1/440, CHF:1/680, AUD:1/400,
        NGN:2.55, GHS:0.024, KES:0.215, ZAR:0.030, MAD:0.0165, CDF:4.7, TZS:4.3, UGX:6.2,
        RWF:2.3, XCD:0.0045, CNY:0.012, INR:0.14, JPY:0.25, MGA:7.5, EGP:0.082,
    };
    let RATES = Object.assign({}, FALLBACK);

    // [code ISO, nom français] — tous les pays
    const RAW = [
        ['CI',"Côte d'Ivoire"],['SN','Sénégal'],['ML','Mali'],['BF','Burkina Faso'],
        ['BJ','Bénin'],['TG','Togo'],['NE','Niger'],['GW','Guinée-Bissau'],
        ['AF','Afghanistan'],['ZA','Afrique du Sud'],['AL','Albanie'],['DZ','Algérie'],
        ['DE','Allemagne'],['AD','Andorre'],['AO','Angola'],['AG','Antigua-et-Barbuda'],
        ['SA','Arabie saoudite'],['AR','Argentine'],['AM','Arménie'],['AU','Australie'],
        ['AT','Autriche'],['AZ','Azerbaïdjan'],['BS','Bahamas'],['BH','Bahreïn'],
        ['BD','Bangladesh'],['BB','Barbade'],['BE','Belgique'],['BZ','Belize'],
        ['BT','Bhoutan'],['BY','Biélorussie'],['MM','Birmanie'],['BO','Bolivie'],
        ['BA','Bosnie-Herzégovine'],['BW','Botswana'],['BR','Brésil'],['BN','Brunei'],
        ['BG','Bulgarie'],['BI','Burundi'],['KH','Cambodge'],['CM','Cameroun'],
        ['CA','Canada'],['CV','Cap-Vert'],['CL','Chili'],['CN','Chine'],['CY','Chypre'],
        ['CO','Colombie'],['KM','Comores'],['CG','Congo'],['CD','RD Congo'],
        ['KR','Corée du Sud'],['KP','Corée du Nord'],['CR','Costa Rica'],['HR','Croatie'],
        ['CU','Cuba'],['DK','Danemark'],['DJ','Djibouti'],['DM','Dominique'],
        ['EG','Égypte'],['AE','Émirats arabes unis'],['EC','Équateur'],['ER','Érythrée'],
        ['ES','Espagne'],['EE','Estonie'],['SZ','Eswatini'],['US','États-Unis'],
        ['ET','Éthiopie'],['FJ','Fidji'],['FI','Finlande'],['FR','France'],['GA','Gabon'],
        ['GM','Gambie'],['GE','Géorgie'],['GH','Ghana'],['GR','Grèce'],['GD','Grenade'],
        ['GT','Guatemala'],['GN','Guinée'],['GQ','Guinée équatoriale'],['GY','Guyana'],
        ['HT','Haïti'],['HN','Honduras'],['HU','Hongrie'],['IN','Inde'],['ID','Indonésie'],
        ['IQ','Irak'],['IR','Iran'],['IE','Irlande'],['IS','Islande'],['IL','Israël'],
        ['IT','Italie'],['JM','Jamaïque'],['JP','Japon'],['JO','Jordanie'],
        ['KZ','Kazakhstan'],['KE','Kenya'],['KG','Kirghizistan'],['KI','Kiribati'],
        ['KW','Koweït'],['LA','Laos'],['LS','Lesotho'],['LV','Lettonie'],['LB','Liban'],
        ['LR','Liberia'],['LY','Libye'],['LI','Liechtenstein'],['LT','Lituanie'],
        ['LU','Luxembourg'],['MK','Macédoine du Nord'],['MG','Madagascar'],['MY','Malaisie'],
        ['MW','Malawi'],['MV','Maldives'],['MT','Malte'],['MA','Maroc'],
        ['MH','Îles Marshall'],['MU','Maurice'],['MR','Mauritanie'],['MX','Mexique'],
        ['FM','Micronésie'],['MD','Moldavie'],['MC','Monaco'],['MN','Mongolie'],
        ['ME','Monténégro'],['MZ','Mozambique'],['NA','Namibie'],['NR','Nauru'],
        ['NP','Népal'],['NI','Nicaragua'],['NG','Nigeria'],['NO','Norvège'],
        ['NZ','Nouvelle-Zélande'],['OM','Oman'],['UG','Ouganda'],['UZ','Ouzbékistan'],
        ['PK','Pakistan'],['PW','Palaos'],['PA','Panama'],['PG','Papouasie-Nouvelle-Guinée'],
        ['PY','Paraguay'],['NL','Pays-Bas'],['PE','Pérou'],['PH','Philippines'],
        ['PL','Pologne'],['PT','Portugal'],['QA','Qatar'],['RO','Roumanie'],
        ['GB','Royaume-Uni'],['RU','Russie'],['RW','Rwanda'],['KN','Saint-Kitts-et-Nevis'],
        ['SM','Saint-Marin'],['VC','Saint-Vincent-et-les-Grenadines'],['LC','Sainte-Lucie'],
        ['SB','Îles Salomon'],['SV','Salvador'],['WS','Samoa'],['ST','Sao Tomé-et-Principe'],
        ['RS','Serbie'],['SC','Seychelles'],['SL','Sierra Leone'],['SG','Singapour'],
        ['SK','Slovaquie'],['SI','Slovénie'],['SO','Somalie'],['SD','Soudan'],
        ['SS','Soudan du Sud'],['LK','Sri Lanka'],['SE','Suède'],['CH','Suisse'],
        ['SR','Suriname'],['SY','Syrie'],['TJ','Tadjikistan'],['TZ','Tanzanie'],
        ['TD','Tchad'],['CZ','Tchéquie'],['TH','Thaïlande'],['TL','Timor oriental'],
        ['TO','Tonga'],['TT','Trinité-et-Tobago'],['TN','Tunisie'],['TM','Turkménistan'],
        ['TR','Turquie'],['TV','Tuvalu'],['UA','Ukraine'],['UY','Uruguay'],['VU','Vanuatu'],
        ['VA','Vatican'],['VE','Venezuela'],['VN','Vietnam'],['YE','Yémen'],['ZM','Zambie'],
        ['ZW','Zimbabwe'],
    ];

    const COUNTRIES = RAW.map(([code, name]) => ({
        code, name,
        cur:  CUR[code] || 'USD',
        gp:   GP.includes(code),
        flag: 'https://flagcdn.com/48x36/' + code.toLowerCase() + '.png',
    })).sort((a, b) => {
        if (a.gp !== b.gp) return a.gp ? -1 : 1;   // GeniusPay en premier
        return a.name.localeCompare(b.name, 'fr');
    });

    const findCountry = code => COUNTRIES.find(c => c.code === code);
    let current = findCountry('CI') || COUNTRIES[0];

    // Libellé convivial de la devise (XOF/XAF → FCFA)
    const curLabel = cur => (cur === 'XOF' || cur === 'XAF') ? 'FCFA' : cur;

    function formatPrice(xof, c) {
        const rate = (RATES[c.cur] != null) ? RATES[c.cur] : (RATES.USD || 1 / 600);
        const val = xof * rate;
        try {
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: c.cur }).format(val);
        } catch (e) {
            return Math.round(val).toLocaleString('fr-FR') + ' ' + c.cur;
        }
    }

    // Convertit tous les prix présents sur la page
    window.applyCurrency = function (c) {
        current = c;
        document.querySelectorAll('[data-xof]').forEach(el => {
            const xof = parseFloat(el.getAttribute('data-xof'));
            if (!isNaN(xof) && xof > 0) el.textContent = formatPrice(xof, c);
        });
        const flag = document.getElementById('navCurrencyFlag');
        const label = document.getElementById('navCurrencyLabel');
        if (flag)  flag.src = c.flag;
        if (label) label.textContent = c.name + ' (' + curLabel(c.cur) + ')';
    };

    function renderList(filter) {
        const list = document.getElementById('countryList');
        const q = (filter || '').trim().toLowerCase();
        const items = COUNTRIES.filter(c => !q || c.name.toLowerCase().includes(q));
        if (!items.length) {
            list.innerHTML = '<div class="country-empty">Aucun pays trouvé</div>';
            return;
        }
        list.innerHTML = items.map(c =>
            `<div class="country-row ${c.code === current.code ? 'active' : ''}" data-code="${c.code}">
                <img src="${c.flag}" alt="" loading="lazy">
                <span class="cr-name">${c.name}${c.gp ? ' <i class="fas fa-circle-check" title="Paiement disponible" style="color:#16a34a;font-size:0.7rem;"></i>' : ''}</span>
                <span class="cr-cur">${curLabel(c.cur)}</span>
            </div>`
        ).join('');
        list.querySelectorAll('.country-row').forEach(row => {
            row.onclick = () => selectCountry(row.getAttribute('data-code'));
        });
    }

    function selectCountry(code) {
        // Choix manuel = temporaire (vue en cours). On ne le mémorise PAS :
        // à la prochaine actualisation, la détection automatique reprend la main
        // (pays réel ou pays du VPN, en temps réel).
        const c = findCountry(code) || COUNTRIES[0];
        applyCurrency(c);
        closeCountryModal();
    }

    window.openCountryModal = function () {
        document.getElementById('countrySearchInput').value = '';
        renderList('');
        document.getElementById('countryModal').classList.add('open');
        setTimeout(() => document.getElementById('countrySearchInput').focus(), 50);
    };
    window.closeCountryModal = function () {
        document.getElementById('countryModal').classList.remove('open');
    };

    // Charge les taux de change en direct (cache 12h, repli hors-ligne)
    function loadRates(done) {
        const KEY = 'nafalo_rates';
        try {
            const cached = JSON.parse(localStorage.getItem(KEY) || 'null');
            if (cached && cached.ts && (Date.now() - cached.ts < 12 * 3600 * 1000) && cached.rates) {
                RATES = cached.rates;
                return done();
            }
        } catch (e) {}
        fetch('https://open.er-api.com/v6/latest/XOF')
            .then(r => r.json())
            .then(d => {
                if (d && d.rates) {
                    RATES = d.rates;
                    try { localStorage.setItem(KEY, JSON.stringify({ ts: Date.now(), rates: d.rates })); } catch (e) {}
                }
                done();
            })
            .catch(() => done());
    }

    // Détection automatique du pays par géolocalisation IP (avec repli).
    function detectCountry(cb) {
        fetch('https://ipapi.co/json/')
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(d => {
                const c = findCountry(d && d.country_code);
                if (c) return cb(c);
                return Promise.reject();
            })
            .catch(() => {
                // Repli sur un second service si le premier échoue/est saturé
                fetch('https://ipwho.is/')
                    .then(r => r.json())
                    .then(d => cb(findCountry(d && d.country_code) || null))
                    .catch(() => cb(null));
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('countrySearchInput');
        if (search) search.addEventListener('input', e => renderList(e.target.value));

        loadRates(function () {
            // Détection automatique du pays à CHAQUE chargement (temps réel).
            // Affiche toujours le pays réel du visiteur — ou celui de son VPN.
            // Aucun choix n'est mémorisé : une actualisation reprend le pays détecté.
            applyCurrency(current); // affichage par défaut le temps de détecter
            detectCountry(function (c) {
                if (c) applyCurrency(c);
            });
        });
    });
})();
</script>

</body>
</html>
