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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    /* ─────────────────────────────────────────────────────────
       BASE
    ───────────────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; min-width: 0; }
    html { overflow-x: hidden; }
    body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #111; margin: 0; overflow-x: hidden; }
    img, video, iframe { max-width: 100%; height: auto; }
    a { text-decoration: none; }

    /* ─────────────────────────────────────────────────────────
       NAVBAR
    ───────────────────────────────────────────────────────── */
    .store-nav {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #f1f5f9;
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: box-shadow 0.3s;
    }
    .store-nav.scrolled { box-shadow: 0 2px 20px rgba(0,0,0,0.08); }
    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        height: 66px;
        gap: 1.5rem;
    }

    /* Brand */
    .store-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; }
    .brand-icon {
        width: 38px; height: 38px;
        border-radius: 11px;
        background: #0f172a;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 900; font-size: 1rem;
        flex-shrink: 0; overflow: hidden;
    }
    .brand-icon img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0) invert(1); }
    .brand-name { font-weight: 800; font-size: 1.05rem; color: #0f172a; letter-spacing: -0.01em; }

    /* Nav links */
    .nav-links { display: flex; align-items: center; gap: 0.1rem; flex: 1; }
    .nav-lnk {
        padding: 0.45rem 0.85rem;
        border-radius: 9px;
        color: #475569;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s;
    }
    .nav-lnk:hover { background: #f1f5f9; color: #0f172a; }
    .nav-lnk.active { background: #eff6ff; color: #2563eb; font-weight: 600; }

    /* Nav right */
    .nav-right { display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; }

    .nav-cta {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 0.5rem 1.1rem;
        background: #0f172a; color: white;
        border: none; border-radius: 10px;
        font-size: 0.855rem; font-weight: 700;
        cursor: pointer; font-family: inherit;
        text-decoration: none;
        transition: all 0.2s;
    }
    .nav-cta:hover { background: #1e293b; color: white; transform: translateY(-1px); }

    .nav-account-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 0.45rem 0.9rem;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        background: white; color: #374151;
        font-size: 0.855rem; font-weight: 600;
        cursor: pointer; font-family: inherit;
        text-decoration: none; transition: all 0.2s;
    }
    .nav-account-btn:hover { border-color: #2563eb; color: #2563eb; }
    .nav-account-btn .avatar {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.65rem; font-weight: 800;
    }

    /* Mobile hamburger */
    .nav-hamburger {
        display: none;
        background: none; border: none; cursor: pointer;
        padding: 0.4rem; color: #374151; font-size: 1.2rem;
        margin-left: auto;
    }

    /* Menu mobile déroulant */
    .mobile-menu {
        display: none;
        position: fixed; top: 66px; left: 0; right: 0;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        z-index: 999;
        padding: 0.75rem 1rem;
        flex-direction: column; gap: 0.25rem;
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu .nav-lnk {
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        border-radius: 10px;
        display: flex; align-items: center; gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .nav-links { display: none; }
        .nav-hamburger { display: flex; align-items: center; }
        .nav-inner { padding: 0 1rem; }
        .store-alerts { padding: 0 1rem; }
        .store-wrap { padding: 0 1rem; }
    }

    @media (max-width: 480px) {
        .nav-inner { height: 58px; }
        .mobile-menu { top: 58px; }
        .brand-name { font-size: 0.95rem; }
        .wa-float { bottom: 16px; right: 16px; }
        .wa-float-btn { width: 50px; height: 50px; font-size: 1.4rem; }
    }

    /* Dropdown */
    .dropdown-menu {
        border: none !important;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1) !important;
        border-radius: 14px !important;
        padding: 0.5rem !important;
        min-width: 200px !important;
    }
    .dropdown-item {
        border-radius: 9px !important;
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        font-weight: 500;
    }

    /* ─────────────────────────────────────────────────────────
       ALERTS
    ───────────────────────────────────────────────────────── */
    .store-alerts { max-width: 1280px; margin: 1rem auto 0; padding: 0 2rem; }
    .alert { border: none !important; border-radius: 12px !important; font-size: 0.875rem !important; }

    /* ─────────────────────────────────────────────────────────
       CONTENT
    ───────────────────────────────────────────────────────── */
    .store-wrap { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }

    /* ─────────────────────────────────────────────────────────
       WHATSAPP FLOAT
    ───────────────────────────────────────────────────────── */
    .wa-float {
        position: fixed; bottom: 24px; right: 24px;
        z-index: 9999;
        display: flex; flex-direction: column; align-items: flex-end; gap: 10px;
    }
    .wa-float-label {
        background: #0f172a; color: white;
        font-size: 0.75rem; font-weight: 700;
        padding: 0.35rem 0.75rem; border-radius: 20px;
        white-space: nowrap;
        opacity: 0; transform: translateX(8px);
        transition: all 0.25s;
        pointer-events: none;
    }
    .wa-float:hover .wa-float-label { opacity: 1; transform: translateX(0); }
    .wa-float-btn {
        width: 58px; height: 58px; border-radius: 50%;
        background: #25d366;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.65rem;
        text-decoration: none;
        box-shadow: 0 4px 20px rgba(37,211,102,0.45);
        transition: transform 0.2s, box-shadow 0.2s;
        animation: wa-pulse 2.5s infinite;
    }
    .wa-float-btn:hover { transform: scale(1.1); box-shadow: 0 8px 28px rgba(37,211,102,0.6); color: white; }
    @keyframes wa-pulse {
        0%, 100% { box-shadow: 0 4px 20px rgba(37,211,102,0.45); }
        50% { box-shadow: 0 4px 32px rgba(37,211,102,0.75); }
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
                    <img src="{{ asset('storage/' . $boutique->logo) }}" alt="{{ $boutique->nom }}">
                @else
                    {{ strtoupper(substr($boutique->nom, 0, 1)) }}
                @endif
            </div>
            <span class="brand-name">{{ $boutique->nom }}</span>
        </a>

        {{-- Liens --}}
        <div class="nav-links">
            <a href="{{ route('boutique.accueil') }}" class="nav-lnk">
                <i class="fas fa-home" style="font-size:0.8rem;margin-right:4px;"></i> Accueil
            </a>
            <a href="{{ route('boutique.produit.index') }}" class="nav-lnk">
                <i class="fas fa-box" style="font-size:0.8rem;margin-right:4px;"></i> Produits
            </a>
            @if($boutique->email)
            <a href="mailto:{{ $boutique->email }}" class="nav-lnk">
                <i class="fas fa-envelope" style="font-size:0.8rem;margin-right:4px;"></i> Contact
            </a>
            @endif
        </div>

        {{-- Droite --}}
        <div class="nav-right">
            @if(session('client_acces_' . $boutique->id))
                @php $clientEmail = session('client_acces_' . $boutique->id); @endphp
                <div class="dropdown">
                    <button class="nav-account-btn" data-bs-toggle="dropdown">
                        <div class="avatar">{{ strtoupper(substr($clientEmail, 0, 1)) }}</div>
                        <span>Mon compte</span>
                        <i class="fas fa-chevron-down" style="font-size:0.65rem;color:#94a3b8;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('client.mes-achats.index') }}">
                                <i class="fas fa-shopping-bag me-2" style="color:#2563eb;"></i> Mes achats
                            </a>
                        </li>
                        <li><hr class="dropdown-divider mx-2 my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('client.acces.deconnexion') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('client.acces.demande') }}" class="nav-account-btn">
                    <i class="fas fa-user" style="font-size:0.8rem;"></i>
                    <span>Mes achats</span>
                </a>
            @endif

            <button class="nav-hamburger" id="nav-hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</nav>

{{-- ── MENU MOBILE ── --}}
<div class="mobile-menu" id="mobile-menu">
    <a href="{{ route('boutique.accueil') }}" class="nav-lnk">
        <i class="fas fa-home" style="font-size:0.85rem;"></i> Accueil
    </a>
    <a href="{{ route('boutique.produit.index') }}" class="nav-lnk">
        <i class="fas fa-box" style="font-size:0.85rem;"></i> Produits
    </a>
    @if($boutique->email)
    <a href="mailto:{{ $boutique->email }}" class="nav-lnk">
        <i class="fas fa-envelope" style="font-size:0.85rem;"></i> Contact
    </a>
    @endif
    @if(session('client_acces_' . $boutique->id))
    <a href="{{ route('client.mes-achats.index') }}" class="nav-lnk">
        <i class="fas fa-shopping-bag" style="font-size:0.85rem;"></i> Mes achats
    </a>
    @else
    <a href="{{ route('client.acces.demande') }}" class="nav-lnk">
        <i class="fas fa-user" style="font-size:0.85rem;"></i> Mes achats
    </a>
    @endif
</div>

{{-- ── ALERTES ── --}}
<div class="store-alerts">
    @foreach(['success' => 'check-circle', 'error' => 'exclamation-circle', 'info' => 'info-circle', 'warning' => 'exclamation-triangle'] as $type => $icon)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mt-2">
                <i class="fas fa-{{ $icon }} me-2"></i>{{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
</div>

{{-- ── CONTENU ── --}}
<main>@yield('content')</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<style>
@media (max-width: 768px) {
    .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 2rem !important; }
}
@media (max-width: 480px) {
    .footer-grid { grid-template-columns: 1fr !important; gap: 1.5rem !important; }
    .footer-bottom-bar { flex-direction: column !important; text-align: center; }
}
</style>
<footer style="background:#0f172a;padding:3.5rem 0 0;margin-top:0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 2rem;">
        <div class="footer-grid" style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,0.07);">

            {{-- Brand --}}
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                    <div style="width:40px;height:40px;border-radius:11px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;border:1px solid rgba(255,255,255,0.1);">
                        @if($boutique->logo)
                            <img src="{{ asset('storage/' . $boutique->logo) }}" alt="" style="width:100%;height:100%;object-fit:cover;filter:brightness(0) invert(1);">
                        @else
                            <span style="color:white;font-weight:900;font-size:1rem;">{{ strtoupper(substr($boutique->nom, 0, 1)) }}</span>
                        @endif
                    </div>
                    <span style="font-weight:800;font-size:1rem;color:white;letter-spacing:-0.01em;">{{ $boutique->nom }}</span>
                </div>
                <p style="font-size:0.83rem;color:rgba(255,255,255,0.4);line-height:1.75;margin:0 0 1.5rem;max-width:220px;">
                    Produits digitaux de qualité — téléchargement instantané, accès à vie.
                </p>
                {{-- Réseaux sociaux --}}
                @php $rs = $boutique->reseaux_sociaux ?? []; @endphp
                <div style="display:flex;gap:0.5rem;">
                    @if(!empty($rs['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rs['whatsapp']) }}" target="_blank" title="WhatsApp"
                       style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);font-size:1rem;text-decoration:none;border:1px solid rgba(255,255,255,0.08);transition:all 0.2s;"
                       onmouseover="this.style.background='#25d366';this.style.color='white';this.style.borderColor='transparent';"
                       onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.55)';this.style.borderColor='rgba(255,255,255,0.08)';">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                    @if(!empty($rs['instagram']))
                    <a href="{{ $rs['instagram'] }}" target="_blank" title="Instagram"
                       style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);font-size:1rem;text-decoration:none;border:1px solid rgba(255,255,255,0.08);transition:all 0.2s;"
                       onmouseover="this.style.background='#e1306c';this.style.color='white';"
                       onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.55)';">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if(!empty($rs['facebook']))
                    <a href="{{ $rs['facebook'] }}" target="_blank" title="Facebook"
                       style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);font-size:1rem;text-decoration:none;border:1px solid rgba(255,255,255,0.08);transition:all 0.2s;"
                       onmouseover="this.style.background='#1877f2';this.style.color='white';"
                       onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.55)';">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if(!empty($rs['tiktok']))
                    <a href="{{ $rs['tiktok'] }}" target="_blank" title="TikTok"
                       style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);font-size:1rem;text-decoration:none;border:1px solid rgba(255,255,255,0.08);transition:all 0.2s;"
                       onmouseover="this.style.background='#111';this.style.color='white';"
                       onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.55)';">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <h6 style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.3);margin:0 0 1.25rem;">Navigation</h6>
                @foreach([
                    ['route' => 'boutique.accueil',         'icon' => 'home',         'label' => 'Accueil'],
                    ['route' => 'boutique.produit.index',   'icon' => 'box',          'label' => 'Produits'],
                    ['route' => 'client.mes-achats.index',  'icon' => 'shopping-bag', 'label' => 'Mes achats'],
                ] as $lnk)
                <a href="{{ route($lnk['route']) }}" style="display:flex;align-items:center;gap:9px;color:rgba(255,255,255,0.5);font-size:0.84rem;text-decoration:none;margin-bottom:0.65rem;transition:color 0.15s;"
                   onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                    <i class="fas fa-{{ $lnk['icon'] }}" style="width:15px;font-size:0.78rem;color:rgba(255,255,255,0.25);"></i>
                    {{ $lnk['label'] }}
                </a>
                @endforeach
                @if($boutique->email)
                <a href="mailto:{{ $boutique->email }}" style="display:flex;align-items:center;gap:9px;color:rgba(255,255,255,0.5);font-size:0.84rem;text-decoration:none;transition:color 0.15s;"
                   onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                    <i class="fas fa-envelope" style="width:15px;font-size:0.78rem;color:rgba(255,255,255,0.25);"></i>
                    Contact
                </a>
                @endif
            </div>

            {{-- Légal --}}
            <div>
                <h6 style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.3);margin:0 0 1.25rem;">Légal</h6>
                @foreach([
                    ['route' => 'legal.conditions',     'label' => "Conditions d'utilisation"],
                    ['route' => 'legal.confidentialite','label' => 'Confidentialité'],
                    ['route' => 'legal.mentions',       'label' => 'Mentions légales'],
                    ['route' => 'legal.remboursement',  'label' => 'Remboursement'],
                ] as $lnk)
                <a href="{{ route($lnk['route']) }}" style="display:block;color:rgba(255,255,255,0.5);font-size:0.84rem;text-decoration:none;margin-bottom:0.65rem;transition:color 0.15s;"
                   onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                    {{ $lnk['label'] }}
                </a>
                @endforeach
            </div>

            {{-- Garanties --}}
            <div>
                <h6 style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.3);margin:0 0 1.25rem;">Garanties</h6>
                @foreach([
                    ['icon' => 'bolt',      'text' => 'Livraison instantanée'],
                    ['icon' => 'lock',      'text' => 'Paiement sécurisé'],
                    ['icon' => 'infinity',  'text' => 'Accès à vie'],
                    ['icon' => 'medal',     'text' => 'Satisfait ou remboursé'],
                    ['icon' => 'headset',   'text' => 'Support disponible'],
                ] as $g)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.7rem;">
                    <i class="fas fa-{{ $g['icon'] }}" style="color:#60a5fa;width:15px;font-size:0.78rem;flex-shrink:0;"></i>
                    <span style="font-size:0.83rem;color:rgba(255,255,255,0.5);">{{ $g['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom-bar" style="display:flex;align-items:center;justify-content:space-between;padding:1.4rem 0;flex-wrap:wrap;gap:0.75rem;">
            <div style="font-size:0.77rem;color:rgba(255,255,255,0.25);">
                © {{ date('Y') }} <strong style="color:rgba(255,255,255,0.4);">{{ $boutique->nom }}</strong> · Tous droits réservés · Propulsé par <strong style="color:#60a5fa;">Nafalo</strong>
            </div>
            <div style="font-size:0.72rem;color:rgba(255,255,255,0.2);">
                Ce site n'est pas affilié à Facebook, Instagram ou Meta.
            </div>
        </div>
    </div>
</footer>

{{-- ── SCRIPTS ── --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Navbar shadow on scroll
window.addEventListener('scroll', function() {
    document.getElementById('store-nav').classList.toggle('scrolled', window.scrollY > 10);
});

// Hamburger mobile menu
const hamburger = document.getElementById('nav-hamburger');
const mobileMenu = document.getElementById('mobile-menu');
if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function() {
        const isOpen = mobileMenu.classList.toggle('open');
        hamburger.innerHTML = isOpen
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    });
    // Fermer si on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.remove('open');
            hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        }
    });
}
</script>

@stack('scripts')

{{-- ── PIXELS MARKETING (footer/body) ── --}}
@foreach($pixelsFooter as $pixel){!! $pixel->code_pixel !!}@endforeach
@foreach($pixelsBody as $pixel){!! $pixel->code_pixel !!}@endforeach

{{-- ═══════════════════════════════════════════════════════════
     BOUTON WHATSAPP FLOTTANT
═══════════════════════════════════════════════════════════ --}}
@php
    $configBoutique    = $boutique->configuration ?? null;
    $waNumber          = $boutique->reseaux_sociaux['whatsapp'] ?? null;
    $couleurPrincipale = $configBoutique->couleur ?? null;
@endphp

@if($waNumber)
<div class="wa-float">
    <span class="wa-float-label">💬 Besoin d'aide ?</span>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}"
       target="_blank" rel="noopener noreferrer"
       class="wa-float-btn"
       title="Nous contacter sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
@endif

{{-- Couleur personnalisée --}}
@if($couleurPrincipale && $couleurPrincipale !== '#2563eb')
<style>
    :root { --c: {{ $couleurPrincipale }}; }
    .btn-primary { background-color: var(--c) !important; border-color: var(--c) !important; }
</style>
@endif

</body>
</html>
