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
       DESIGN TOKENS — Dark Mode Nafalo
    ═══════════════════════════════════════════════════════════ */
    :root {
        --bg:          #0a0a0f;
        --bg-surface:  #111118;
        --bg-card:     #16161f;
        --bg-elevated: #1e1e2c;
        --border:      rgba(255,255,255,0.07);
        --border-hover:rgba(255,255,255,0.15);
        --accent:      #7c3aed;
        --accent-hover:#6d28d9;
        --accent-light:rgba(124,58,237,0.15);
        --accent-glow: rgba(124,58,237,0.35);
        --text-1:      #ffffff;
        --text-2:      rgba(255,255,255,0.6);
        --text-3:      rgba(255,255,255,0.35);
        --green:       #22c55e;
        --green-dim:   rgba(34,197,94,0.15);
        --red:         #ef4444;
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
        background: rgba(10,10,15,0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: border-color 0.3s;
    }
    .store-nav.scrolled {
        border-bottom-color: rgba(255,255,255,0.1);
        background: rgba(10,10,15,0.96);
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
    .nav-lnk:hover { background: rgba(255,255,255,0.07); color: var(--text-1); }
    .nav-lnk.active { color: var(--text-1); font-weight: 600; }

    /* Search */
    .nav-search {
        display: flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.05);
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
        background: rgba(255,255,255,0.08);
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
        background: rgba(255,255,255,0.05);
    }
    .btn-connexion .avatar {
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.65rem; font-weight: 800;
    }

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
        background: rgba(14,14,20,0.98);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        box-shadow: 0 8px 30px rgba(0,0,0,0.5);
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
        background: rgba(255,255,255,0.06);
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
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: var(--text-1);
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 0.75rem;
    }
    .alert-dark.success { border-color: rgba(34,197,94,0.4); background: rgba(34,197,94,0.08); color: #86efac; }
    .alert-dark.danger  { border-color: rgba(239,68,68,0.4);  background: rgba(239,68,68,0.08);  color: #fca5a5; }
    .alert-dark.info    { border-color: rgba(96,165,250,0.4); background: rgba(96,165,250,0.08); color: #93c5fd; }
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
        box-shadow: 0 12px 40px rgba(0,0,0,0.5) !important;
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
        background: rgba(255,255,255,0.07) !important;
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
        background: rgba(255,255,255,0.06);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-3); font-size: 1rem; text-decoration: none;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }
    .footer-social:hover { color: white; }
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
            <a href="{{ route('boutique.accueil') }}" class="nav-lnk {{ request()->routeIs('boutique.accueil') ? 'active' : '' }}">
                Découvrir
            </a>
            <a href="{{ route('boutique.produit.index') }}" class="nav-lnk {{ request()->routeIs('boutique.produit.*') ? 'active' : '' }}">
                Produits
            </a>
            @if($boutique->email)
            <a href="mailto:{{ $boutique->email }}" class="nav-lnk">
                Contact
            </a>
            @endif
        </div>

        {{-- Recherche --}}
        <form action="{{ route('boutique.accueil') }}" method="GET" style="flex:1;max-width:260px;display:flex;">
            <div class="nav-search" style="width:100%;">
                <i class="fas fa-search" style="font-size:0.8rem;"></i>
                <input type="text" name="recherche"
                       placeholder="Rechercher..."
                       value="{{ request('recherche') }}">
                <span style="font-size:0.7rem;opacity:0.4;margin-left:auto;">⌘K</span>
            </div>
        </form>

        {{-- Droite --}}
        <div class="nav-right">
            @if(session('client_acces_' . $boutique->id))
                @php $clientEmail = session('client_acces_' . $boutique->id); @endphp
                <div class="dropdown">
                    <button class="btn-connexion" data-bs-toggle="dropdown">
                        <div class="avatar">{{ strtoupper(substr($clientEmail, 0, 1)) }}</div>
                        <span>Mon compte</span>
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
            @else
                <a href="{{ route('client.acces.demande') }}" class="btn-connexion">
                    <i class="fas fa-user" style="font-size:0.8rem;"></i>
                    <span>Connexion</span>
                </a>
            @endif

            <a href="{{ route('boutique.produit.index') }}" class="btn-cta-nav">
                Explorer →
            </a>

            <button class="nav-hamburger" id="nav-hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</nav>

{{-- ── MENU MOBILE ── --}}
<div class="mobile-menu" id="mobile-menu">
    <a href="{{ route('boutique.accueil') }}" class="nav-lnk">
        <i class="fas fa-compass" style="font-size:0.85rem;"></i> Découvrir
    </a>
    <a href="{{ route('boutique.produit.index') }}" class="nav-lnk">
        <i class="fas fa-box" style="font-size:0.85rem;"></i> Produits
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
            <div>
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
                <p style="font-size:0.83rem;color:var(--text-3);line-height:1.75;margin:0 0 1.5rem;max-width:220px;">
                    Produits digitaux de qualité — téléchargement instantané, accès à vie.
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
                       onmouseover="this.style.background='#e1306c'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if(!empty($rs['facebook']))
                    <a href="{{ $rs['facebook'] }}" target="_blank"
                       class="footer-social" title="Facebook"
                       onmouseover="this.style.background='#1877f2'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if(!empty($rs['tiktok']))
                    <a href="{{ $rs['tiktok'] }}" target="_blank"
                       class="footer-social" title="TikTok"
                       onmouseover="this.style.background='#111'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
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
            <span>© {{ date('Y') }} <strong style="color:rgba(255,255,255,0.5);">{{ $boutique->nom }}</strong> · Tous droits réservés · Propulsé par <strong style="color:var(--accent);">Nafalo</strong></span>
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

</body>
</html>
