<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nafalo — La plateforme N°1 pour vendre vos produits digitaux</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background:#fff; color:#0f172a; overflow-x:hidden; }

        /* ── NAVBAR ── */
        nav {
            position: fixed; top:0; left:0; right:0; z-index:1000;
            height: 72px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #f1f5f9;
            transition: box-shadow 0.3s;
        }
        nav.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .nav-logo img { height: 90px; width: auto; mix-blend-mode: multiply; }
        .nav-links { display:flex; align-items:center; gap:0.25rem; }
        .nav-link {
            padding: 0.5rem 0.9rem; border-radius:8px;
            color:#64748b; font-size:0.9rem; font-weight:500;
            text-decoration:none; transition:all 0.15s;
        }
        .nav-link:hover { color:#0f172a; background:#f1f5f9; }
        .nav-actions { display:flex; align-items:center; gap:0.75rem; }
        .btn-login {
            padding:0.55rem 1.25rem; border-radius:10px;
            border:1.5px solid #e2e8f0; color:#0f172a;
            font-size:0.875rem; font-weight:600;
            text-decoration:none; transition:all 0.2s;
        }
        .btn-login:hover { border-color:#2563eb; color:#2563eb; }
        .btn-create {
            padding:0.55rem 1.25rem; border-radius:10px;
            background:#2563eb; color:#fff;
            font-size:0.875rem; font-weight:600;
            text-decoration:none; transition:all 0.2s; border:none; cursor:pointer;
        }
        .btn-create:hover { background:#1d4ed8; transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,0.3); }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 9rem 2rem 5rem;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(37,99,235,0.08) 0%, transparent 70%);
            text-align: center; position: relative; overflow: hidden;
        }
        .hero-inner { max-width: 820px; margin: 0 auto; }
        .hero-badge {
            display: inline-flex; align-items:center; gap:8px;
            padding:0.4rem 1rem; border-radius:50px;
            background:#eff6ff; border:1px solid #bfdbfe;
            color:#2563eb; font-size:0.8rem; font-weight:600;
            margin-bottom: 2rem;
        }
        .hero-badge .dot { width:7px; height:7px; border-radius:50%; background:#22c55e; animation:blink 1.8s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
        .hero-title {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 900; line-height: 1.05; letter-spacing:-2px;
            color: #0f172a; margin-bottom: 1.5rem;
        }
        .hero-title span { color:#2563eb; }
        .hero-subtitle {
            font-size:1.15rem; color:#64748b; line-height:1.8;
            max-width:620px; margin: 0 auto 2.5rem;
        }
        .hero-ctas { display:flex; align-items:center; justify-content:center; gap:1rem; margin-bottom:3.5rem; flex-wrap:wrap; }
        .btn-hero-primary {
            display:inline-flex; align-items:center; gap:8px;
            padding:0.9rem 2rem; border-radius:12px;
            background:#2563eb; color:white; font-weight:700; font-size:1rem;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer;
        }
        .btn-hero-primary:hover { background:#1d4ed8; transform:translateY(-2px); box-shadow:0 12px 30px rgba(37,99,235,0.35); color:white; }
        .btn-hero-secondary {
            display:inline-flex; align-items:center; gap:8px;
            padding:0.9rem 1.75rem; border-radius:12px;
            border:1.5px solid #e2e8f0; color:#0f172a; font-weight:600; font-size:1rem;
            text-decoration:none; transition:all 0.2s; background:white;
        }
        .btn-hero-secondary:hover { border-color:#2563eb; color:#2563eb; }

        /* Avatars créateurs */
        .hero-social-proof { display:flex; align-items:center; justify-content:center; gap:1rem; }
        .avatars { display:flex; }
        .avatars span {
            width:36px; height:36px; border-radius:50%; border:2.5px solid white;
            margin-left:-10px; display:flex; align-items:center; justify-content:center;
            font-size:0.8rem; font-weight:700; color:white;
        }
        .avatars span:first-child { margin-left:0; }
        .proof-text { font-size:0.85rem; color:#64748b; }
        .proof-text strong { color:#0f172a; }

        /* Hero dashboard mockup */
        .hero-mockup {
            max-width:900px; margin:4rem auto 0;
            background:#f8fafc; border-radius:20px; padding:1.5rem;
            border:1px solid #e2e8f0; box-shadow:0 40px 80px rgba(0,0,0,0.1);
            position:relative;
        }
        .mockup-bar { display:flex; align-items:center; gap:6px; margin-bottom:1rem; }
        .mockup-dot { width:10px; height:10px; border-radius:50%; }
        .mockup-inner {
            background:white; border-radius:12px; padding:1.5rem;
            display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;
        }
        .mockup-card {
            border-radius:10px; padding:1rem;
            background:#f8fafc; border:1px solid #f1f5f9;
        }
        .mockup-card .mc-label { font-size:0.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem; }
        .mockup-card .mc-value { font-size:1.4rem; font-weight:800; color:#0f172a; }
        .mockup-card .mc-change { font-size:0.75rem; color:#22c55e; font-weight:600; margin-top:2px; }

        /* ── SECTION COMMUNE ── */
        section { padding:6rem 2rem; }
        .section-inner { max-width:1200px; margin:0 auto; }
        .section-tag { display:inline-block; padding:0.35rem 0.9rem; border-radius:50px; background:#eff6ff; color:#2563eb; font-size:0.78rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:1rem; }
        .section-title { font-size:clamp(1.8rem,3.5vw,2.8rem); font-weight:900; line-height:1.15; letter-spacing:-1px; margin-bottom:1rem; }
        .section-sub { font-size:1rem; color:#64748b; line-height:1.8; max-width:540px; }

        /* ── PRODUITS ── */
        .products { background:#fff; }
        .products-header { text-align:center; margin-bottom:3.5rem; }
        .products-header .section-sub { margin:0 auto; }
        .products-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; }
        @media(max-width:768px){ .products-grid { grid-template-columns:1fr 1fr; } }
        @media(max-width:480px){ .products-grid { grid-template-columns:1fr; } }
        .product-card {
            padding:1.75rem; border-radius:18px; border:1.5px solid #f1f5f9;
            transition:all 0.25s; cursor:default; background:white;
        }
        .product-card:hover { border-color:#bfdbfe; transform:translateY(-4px); box-shadow:0 12px 40px rgba(37,99,235,0.08); }
        .product-icon {
            width:52px; height:52px; border-radius:14px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.4rem; margin-bottom:1.25rem;
        }
        .product-card h3 { font-size:1rem; font-weight:700; margin-bottom:0.4rem; }
        .product-card p { font-size:0.875rem; color:#64748b; line-height:1.6; }

        /* ── TESTIMONIALS CAROUSEL ── */
        .testimonials-section { background:#f8fafc; overflow:hidden; padding:6rem 0; }
        .testimonials-header { text-align:center; padding:0 2rem; margin-bottom:3rem; }
        .testimonials-header .section-sub { margin:0 auto; }
        .carousel-track-wrapper { overflow:hidden; position:relative; }
        .carousel-track-wrapper::before,
        .carousel-track-wrapper::after {
            content:''; position:absolute; top:0; bottom:0; width:120px; z-index:2;
        }
        .carousel-track-wrapper::before { left:0; background:linear-gradient(to right, #f8fafc, transparent); }
        .carousel-track-wrapper::after  { right:0; background:linear-gradient(to left, #f8fafc, transparent); }
        .carousel-track {
            display:flex; gap:1.25rem;
            animation:scrollLeft 40s linear infinite;
            width:max-content;
        }
        .carousel-track:hover { animation-play-state:paused; }
        @keyframes scrollLeft { from{transform:translateX(0)} to{transform:translateX(-50%)} }
        .tcard {
            width:320px; flex-shrink:0;
            background:white; border-radius:18px; padding:1.5rem;
            border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.04);
        }
        .tcard-stars { color:#f59e0b; font-size:0.8rem; margin-bottom:0.75rem; }
        .tcard-text { font-size:0.875rem; color:#374151; line-height:1.7; margin-bottom:1.25rem; }
        .tcard-author { display:flex; align-items:center; gap:10px; }
        .tcard-avatar {
            width:38px; height:38px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-weight:800; font-size:0.9rem; color:white; flex-shrink:0;
        }
        .tcard-name { font-weight:700; font-size:0.85rem; }
        .tcard-role { font-size:0.75rem; color:#94a3b8; }

        /* ── FEATURES ALTERNÉES ── */
        .features-section { background:white; }
        .feature-row {
            display:grid; grid-template-columns:1fr 1fr; gap:5rem;
            align-items:center; margin-bottom:6rem;
        }
        .feature-row:last-child { margin-bottom:0; }
        .feature-row.reverse { direction:rtl; }
        .feature-row.reverse > * { direction:ltr; }
        .feature-content {}
        .feature-list { list-style:none; margin-top:1.5rem; }
        .feature-list li {
            display:flex; align-items:flex-start; gap:10px;
            font-size:0.9rem; color:#374151; margin-bottom:0.75rem; line-height:1.5;
        }
        .feature-list li::before {
            content:'✓'; color:#2563eb; font-weight:800;
            flex-shrink:0; margin-top:1px;
        }
        .feature-visual {
            border-radius:24px; overflow:hidden;
            background:#f8fafc; border:1px solid #e2e8f0;
            padding:2rem; min-height:320px;
            display:flex; align-items:center; justify-content:center;
        }
        .feature-mockup { width:100%; }
        .fmock-header {
            background:#2563eb; border-radius:12px; padding:1rem 1.25rem;
            color:white; font-weight:700; font-size:0.9rem; margin-bottom:1rem;
            display:flex; align-items:center; gap:8px;
        }
        .fmock-row {
            background:white; border-radius:10px; padding:0.875rem 1rem;
            margin-bottom:0.6rem; display:flex; align-items:center;
            justify-content:space-between; border:1px solid #f1f5f9;
            font-size:0.85rem;
        }
        .fmock-badge {
            padding:0.25rem 0.6rem; border-radius:20px;
            font-size:0.72rem; font-weight:600;
        }
        .badge-green { background:#dcfce7; color:#166534; }
        .badge-blue  { background:#dbeafe; color:#1e40af; }

        /* ── PRICING ── */
        .pricing-section { background:#f8fafc; text-align:center; }
        .pricing-header { margin-bottom:3.5rem; }
        .pricing-card {
            max-width:440px; margin:0 auto;
            background:white; border-radius:24px; padding:2.5rem;
            border:2px solid #2563eb; box-shadow:0 20px 60px rgba(37,99,235,0.1);
        }
        .pricing-plan { font-weight:700; font-size:0.85rem; color:#2563eb; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1rem; }
        .pricing-price { font-size:4rem; font-weight:900; letter-spacing:-2px; }
        .pricing-price span { font-size:1.2rem; font-weight:500; color:#64748b; }
        .pricing-desc { color:#64748b; margin:0.75rem 0 2rem; font-size:0.95rem; }
        .pricing-features { list-style:none; text-align:left; margin-bottom:2rem; }
        .pricing-features li { display:flex; align-items:center; gap:10px; padding:0.6rem 0; border-bottom:1px solid #f1f5f9; font-size:0.9rem; }
        .pricing-features li i { color:#2563eb; width:16px; }

        /* ── CTA FINAL ── */
        .cta-final {
            background:linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            text-align:center; padding:8rem 2rem;
            position:relative; overflow:hidden;
        }
        .cta-final::before {
            content:''; position:absolute; inset:0;
            background:radial-gradient(ellipse 60% 50% at 50% 0%, rgba(37,99,235,0.3), transparent);
        }
        .cta-final-inner { position:relative; z-index:1; max-width:640px; margin:0 auto; }
        .cta-final h2 { font-size:clamp(2rem,4vw,3.5rem); font-weight:900; color:white; letter-spacing:-1.5px; margin-bottom:1rem; line-height:1.1; }
        .cta-final p { color:#94a3b8; font-size:1.05rem; margin-bottom:2.5rem; }

        /* ── MODAL LOGIN ── */
        .modal-overlay {
            display:none; position:fixed; inset:0; z-index:2000;
            background:rgba(15,23,42,0.6); backdrop-filter:blur(8px);
            align-items:center; justify-content:center;
        }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background:white; border-radius:24px; padding:2.5rem;
            width:100%; max-width:420px; margin:1rem;
            box-shadow:0 40px 80px rgba(0,0,0,0.2);
            animation:modalIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes modalIn { from{opacity:0;transform:scale(0.9)translateY(20px)} to{opacity:1;transform:scale(1)translateY(0)} }
        .modal-close {
            position:absolute; top:1.25rem; right:1.25rem;
            width:32px; height:32px; border-radius:50%;
            background:#f1f5f9; border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            font-size:0.9rem; color:#64748b;
        }
        .modal-close:hover { background:#e2e8f0; }
        .modal-logo { text-align:center; margin-bottom:1.75rem; }
        .modal-logo img { height:60px; }
        .modal-title { font-size:1.3rem; font-weight:800; text-align:center; margin-bottom:0.35rem; }
        .modal-sub { text-align:center; color:#94a3b8; font-size:0.875rem; margin-bottom:1.75rem; }
        .auth-tabs { display:flex; background:#f1f5f9; border-radius:12px; padding:4px; margin-bottom:1.5rem; }
        .auth-tab { flex:1; padding:0.6rem; text-align:center; border-radius:9px; border:none; background:none; font-weight:600; font-size:0.875rem; color:#94a3b8; cursor:pointer; transition:all 0.2s; }
        .auth-tab.active { background:white; color:#0f172a; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        .auth-form { display:none; }
        .auth-form.active { display:block; }
        .field { margin-bottom:1rem; }
        .field label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .field input {
            width:100%; padding:0.75rem 1rem; border-radius:11px;
            border:1.5px solid #e2e8f0; font-size:0.9rem; outline:none;
            font-family:'Inter',sans-serif; transition:all 0.2s;
        }
        .field input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .field-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; }
        .check-label { display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#64748b; cursor:pointer; }
        .check-label input[type=checkbox] { width:auto; }
        .forgot { font-size:0.8rem; color:#2563eb; text-decoration:none; font-weight:500; }
        .btn-submit { width:100%; padding:0.875rem; border-radius:11px; background:#2563eb; color:white; font-weight:700; font-size:0.95rem; border:none; cursor:pointer; transition:all 0.2s; font-family:'Inter',sans-serif; }
        .btn-submit:hover { background:#1d4ed8; box-shadow:0 8px 20px rgba(37,99,235,0.3); }
        .divider { display:flex; align-items:center; gap:1rem; margin:1.25rem 0; color:#cbd5e1; font-size:0.8rem; }
        .divider::before,.divider::after { content:''; flex:1; height:1px; background:#f1f5f9; }
        .switch-link { text-align:center; font-size:0.82rem; color:#94a3b8; }
        .switch-link a { color:#0f172a; font-weight:700; text-decoration:none; }
        .alert-box { padding:0.7rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1rem; }
        .alert-success { background:#f0fdf4; color:#166534; border-left:3px solid #22c55e; }
        .alert-danger  { background:#fef2f2; color:#991b1b; border-left:3px solid #ef4444; }

        /* ── FOOTER ── */
        footer { background:#0f172a; padding:4rem 2rem 2rem; }
        .footer-inner { max-width:1200px; margin:0 auto; }
        .footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:3rem; margin-bottom:3rem; }
        @media(max-width:768px){ .footer-grid { grid-template-columns:1fr 1fr; gap:2rem; } }
        .footer-brand img { height:60px; filter:brightness(0) invert(1); margin-bottom:1rem; }
        .footer-brand p { color:#475569; font-size:0.875rem; line-height:1.7; max-width:280px; }
        .footer-col h5 { color:white; font-weight:700; font-size:0.875rem; margin-bottom:1.25rem; }
        .footer-col a { display:block; color:#475569; font-size:0.875rem; text-decoration:none; margin-bottom:0.6rem; transition:color 0.15s; }
        .footer-col a:hover { color:#94a3b8; }
        .footer-bottom { border-top:1px solid #1e293b; padding-top:1.75rem; display:flex; align-items:center; justify-content:space-between; }
        .footer-bottom p { color:#475569; font-size:0.8rem; }
        .footer-socials { display:flex; gap:0.75rem; }
        .social-btn { width:36px; height:36px; border-radius:9px; background:#1e293b; display:flex; align-items:center; justify-content:center; color:#64748b; text-decoration:none; transition:all 0.2s; font-size:0.9rem; }
        .social-btn:hover { background:#2563eb; color:white; }

        @media(max-width:900px) {
            .feature-row { grid-template-columns:1fr; gap:2rem; }
            .feature-row.reverse { direction:ltr; }
            nav .nav-links { display:none; }
        }

        /* ── RESPONSIVE MOBILE ── */
        @media(max-width:768px) {
            nav { padding:0 1.25rem; height:62px; }
            .nav-logo img { height:70px; }
            .btn-create { padding:0.5rem 1rem; font-size:0.82rem; }
            .btn-login { display:none; }

            .hero { padding:8rem 1.25rem 4rem; }
            .hero-ctas { flex-direction:column; align-items:stretch; }
            .btn-hero-primary, .btn-hero-secondary { justify-content:center; width:100%; }
            .hero-social-proof { flex-direction:column; gap:0.5rem; }

            .products-grid { grid-template-columns:1fr 1fr; gap:1rem; }
            section { padding:4rem 1.25rem; }
            .footer-grid { grid-template-columns:1fr 1fr; gap:2rem; }
        }

        @media(max-width:480px) {
            .hero-title { font-size:2rem; letter-spacing:-1px; }
            .products-grid { grid-template-columns:1fr; }
            .footer-grid { grid-template-columns:1fr; }
            .modal-box { padding:1.75rem 1.25rem; margin:0.5rem; border-radius:18px; }
            .hero { padding:7rem 1rem 3rem; }
            nav { padding:0 1rem; }
            .btn-create { display:none; }
            .nav-actions::after {
                content:'Menu';
                background:#2563eb; color:white; border-radius:10px;
                padding:0.5rem 1rem; font-size:0.82rem; font-weight:700; cursor:pointer;
            }
            .pricing-card { padding:1.75rem 1.25rem; }
        }
    </style>
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav id="navbar">
    <a href="/" class="nav-logo">
        <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo">
    </a>
    <div class="nav-links">
        <a href="#produits"      class="nav-link">Produits</a>
        <a href="#fonctionnalites" class="nav-link">Fonctionnalités</a>
        <a href="#temoignages"   class="nav-link">Témoignages</a>
        <a href="#tarifs"        class="nav-link">Tarifs</a>
    </div>
    <div class="nav-actions">
        <a href="#" class="btn-login" onclick="openModal('login'); return false;">Connexion</a>
        <a href="#" class="btn-create" onclick="openModal('register'); return false;">Créer une boutique</a>
    </div>
</nav>

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <span class="dot"></span>
            Plateforme N°1 en Afrique francophone
        </div>
        <h1 class="hero-title">
            Vendez vos produits<br>digitaux <span>partout</span><br>dans le monde
        </h1>
        <p class="hero-subtitle">
            Créez votre boutique en 5 minutes, publiez vos e-books, formations et fichiers, et encaissez automatiquement — 24h/24, 7j/7.
        </p>
        <div class="hero-ctas">
            <a href="#" class="btn-hero-primary" onclick="openModal('register'); return false;">
                <i class="fas fa-store"></i> Créer ma boutique gratuitement
            </a>
            <a href="#fonctionnalites" class="btn-hero-secondary">
                <i class="fas fa-play-circle"></i> Voir comment ça marche
            </a>
        </div>
        <div class="hero-social-proof">
            <div class="avatars">
                <span style="background:linear-gradient(135deg,#667eea,#764ba2)">A</span>
                <span style="background:linear-gradient(135deg,#f093fb,#f5576c)">K</span>
                <span style="background:linear-gradient(135deg,#4facfe,#00f2fe)">S</span>
                <span style="background:linear-gradient(135deg,#43e97b,#38f9d7)">M</span>
                <span style="background:linear-gradient(135deg,#fa709a,#fee140)">D</span>
            </div>
            <div class="proof-text">
                Rejoignez <strong>+10 000 créateurs</strong> qui vendent avec Nafalo
            </div>
        </div>

        {{-- Dashboard Mockup --}}
        <div class="hero-mockup">
            <div class="mockup-bar">
                <div class="mockup-dot" style="background:#ff5f57"></div>
                <div class="mockup-dot" style="background:#febc2e"></div>
                <div class="mockup-dot" style="background:#28c840"></div>
            </div>
            <div class="mockup-inner">
                <div class="mockup-card">
                    <div class="mc-label">Revenus du mois</div>
                    <div class="mc-value" id="rev-counter">0 FCFA</div>
                    <div class="mc-change">↑ +23% ce mois</div>
                </div>
                <div class="mockup-card">
                    <div class="mc-label">Ventes</div>
                    <div class="mc-value" id="sales-counter">0</div>
                    <div class="mc-change">↑ +41 aujourd'hui</div>
                </div>
                <div class="mockup-card">
                    <div class="mc-label">Clients actifs</div>
                    <div class="mc-value" id="clients-counter">0</div>
                    <div class="mc-change">↑ +8 nouveaux</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TYPES DE PRODUITS ══ --}}
<section class="products" id="produits">
    <div class="section-inner">
        <div class="products-header">
            <span class="section-tag">Produits supportés</span>
            <h2 class="section-title">Vendez n'importe quel<br>produit digital</h2>
            <p class="section-sub">Nafalo supporte tous les formats de produits numériques. Publiez en quelques clics.</p>
        </div>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-icon" style="background:#eff6ff;">📄</div>
                <h3>E-books & PDF</h3>
                <p>Vendez vos guides, livres numériques, rapports et documents avec livraison instantanée.</p>
            </div>
            <div class="product-card">
                <div class="product-icon" style="background:#f0fdf4;">🎓</div>
                <h3>Formations en ligne</h3>
                <p>Hébergez et vendez vos cours vidéo, tutoriels et programmes de formation complets.</p>
            </div>
            <div class="product-card">
                <div class="product-icon" style="background:#fdf4ff;">🔑</div>
                <h3>Licences & Codes</h3>
                <p>Distribuez automatiquement des licences logicielles, codes d'activation et clés produit.</p>
            </div>
            <div class="product-card">
                <div class="product-icon" style="background:#fff7ed;">📦</div>
                <h3>Packs & Bundles</h3>
                <p>Regroupez plusieurs produits en offres groupées pour augmenter votre panier moyen.</p>
            </div>
            <div class="product-card">
                <div class="product-icon" style="background:#fef2f2;">🎨</div>
                <h3>Templates & Design</h3>
                <p>Vendez vos templates Figma, Canva, PowerPoint, Notion et ressources créatives.</p>
            </div>
            <div class="product-card">
                <div class="product-icon" style="background:#f0fdf4;">💬</div>
                <h3>Coaching & Sessions</h3>
                <p>Proposez des sessions de coaching, consultations et accompagnements personnalisés.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ TÉMOIGNAGES ══ --}}
<section class="testimonials-section" id="temoignages">
    <div class="testimonials-header section-inner">
        <span class="section-tag">Témoignages</span>
        <h2 class="section-title">Ils vendent déjà avec Nafalo</h2>
        <p class="section-sub" style="margin:0 auto;">Des créateurs de toute l'Afrique et du monde font confiance à Nafalo pour vendre leurs produits digitaux.</p>
    </div>
    <div class="carousel-track-wrapper">
        <div class="carousel-track" id="carousel">
            @php
            $temoignages = [
                ['A','Aminata D.','🇨🇮 Formatrice','linear-gradient(135deg,#667eea,#764ba2)','J\'ai lancé ma boutique en moins d\'une heure. Mes e-books se vendent 24h/24 automatiquement. Nafalo a changé ma vie !'],
                ['K','Kofi M.','🇬🇭 Entrepreneur','linear-gradient(135deg,#f093fb,#f5576c)','Mon CA a triplé depuis Nafalo. Les paiements Mobile Money marchent parfaitement et mes clients adorent l\'expérience.'],
                ['S','Seydou B.','🇸🇳 Consultant','linear-gradient(135deg,#4facfe,#00f2fe)','Fini les envois manuels par email. Tout est automatisé. Je me concentre sur la création, Nafalo gère le reste.'],
                ['M','Moussa T.','🇲🇱 Développeur','linear-gradient(135deg,#43e97b,#38f9d7)','Je vends mes templates et formations depuis Bamako vers le monde entier. Nafalo m\'a ouvert des marchés inimaginables.'],
                ['F','Fatima O.','🇲🇦 Coach','linear-gradient(135deg,#fa709a,#fee140)','Le dashboard est ultra simple. En quelques clics j\'ai ma boutique, mes produits et mes paiements configurés.'],
                ['D','David A.','🇧🇯 Créateur','linear-gradient(135deg,#a18cd1,#fbc2eb)','Nafalo c\'est la meilleure décision que j\'ai prise pour mon business digital. Simple, rapide, efficace.'],
                ['N','Nadia L.','🇨🇲 Influenceuse','linear-gradient(135deg,#fda085,#f6d365)','J\'ai vendu 200 copies de mon guide en 48h grâce à Nafalo. La livraison automatique est parfaite.'],
                ['R','Rachid M.','🇩🇿 Formateur','linear-gradient(135deg,#89f7fe,#66a6ff)','Je recommande Nafalo à tous mes élèves qui veulent se lancer dans la vente de produits digitaux.'],
            ];
            @endphp
            @foreach(array_merge($temoignages, $temoignages) as $t)
            <div class="tcard">
                <div class="tcard-stars">★★★★★</div>
                <p class="tcard-text">"{{ $t[4] }}"</p>
                <div class="tcard-author">
                    <div class="tcard-avatar" style="background:{{ $t[3] }}">{{ $t[0] }}</div>
                    <div>
                        <div class="tcard-name">{{ $t[1] }}</div>
                        <div class="tcard-role">{{ $t[2] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ FONCTIONNALITÉS ALTERNÉES ══ --}}
<section class="features-section" id="fonctionnalites">
    <div class="section-inner">

        <div class="feature-row">
            <div class="feature-content">
                <span class="section-tag">Ventes automatiques</span>
                <h2 class="section-title">Votre boutique vend<br>même quand vous dormez</h2>
                <p class="section-sub">Configurez une fois, vendez indéfiniment. Vos clients reçoivent leurs produits instantanément après le paiement.</p>
                <ul class="feature-list">
                    <li>Livraison instantanée par email après chaque vente</li>
                    <li>Paiements via Mobile Money, Wave, carte bancaire</li>
                    <li>Notifications en temps réel pour chaque vente</li>
                    <li>Pas besoin d'intervention manuelle</li>
                </ul>
            </div>
            <div class="feature-visual">
                <div class="feature-mockup">
                    <div class="fmock-header"><i class="fas fa-bolt"></i> Ventes récentes</div>
                    <div class="fmock-row">
                        <span>📘 Guide Marketing Digital</span>
                        <span class="fmock-badge badge-green">+12 500 FCFA</span>
                    </div>
                    <div class="fmock-row">
                        <span>🎓 Formation Dropshipping</span>
                        <span class="fmock-badge badge-green">+35 000 FCFA</span>
                    </div>
                    <div class="fmock-row">
                        <span>🎨 Pack Templates Canva</span>
                        <span class="fmock-badge badge-green">+8 500 FCFA</span>
                    </div>
                    <div class="fmock-row">
                        <span>🔑 Licence Logiciel Pro</span>
                        <span class="fmock-badge badge-green">+22 000 FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="feature-row reverse">
            <div class="feature-content">
                <span class="section-tag">Analytics puissants</span>
                <h2 class="section-title">Suivez chaque vente<br>en temps réel</h2>
                <p class="section-sub">Dashboard complet pour piloter votre business. Revenus, clients, produits populaires — tout en un coup d'œil.</p>
                <ul class="feature-list">
                    <li>Graphiques de revenus et ventes en temps réel</li>
                    <li>Analyse par produit, pays et période</li>
                    <li>Taux de conversion et panier moyen</li>
                    <li>Export des données en CSV</li>
                </ul>
            </div>
            <div class="feature-visual">
                <div class="feature-mockup">
                    <div class="fmock-header"><i class="fas fa-chart-bar"></i> Analytics du mois</div>
                    <div class="fmock-row">
                        <span>🇨🇮 Côte d'Ivoire</span>
                        <span class="fmock-badge badge-blue">42% des ventes</span>
                    </div>
                    <div class="fmock-row">
                        <span>🇸🇳 Sénégal</span>
                        <span class="fmock-badge badge-blue">28% des ventes</span>
                    </div>
                    <div class="fmock-row">
                        <span>🇬🇭 Ghana</span>
                        <span class="fmock-badge badge-blue">15% des ventes</span>
                    </div>
                    <div class="fmock-row">
                        <span>🌍 Autres pays</span>
                        <span class="fmock-badge badge-blue">15% des ventes</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="feature-row">
            <div class="feature-content">
                <span class="section-tag">Marketing intégré</span>
                <h2 class="section-title">Boostez vos ventes<br>avec des outils marketing</h2>
                <p class="section-sub">Codes promo, pixels de tracking, avis clients, upsells — tout ce qu'il faut pour maximiser vos revenus.</p>
                <ul class="feature-list">
                    <li>Codes promo et réductions personnalisés</li>
                    <li>Intégration Facebook Pixel & Google Analytics</li>
                    <li>Système d'avis clients automatique</li>
                    <li>Email marketing post-achat</li>
                </ul>
            </div>
            <div class="feature-visual">
                <div class="feature-mockup">
                    <div class="fmock-header"><i class="fas fa-bullseye"></i> Campagnes actives</div>
                    <div class="fmock-row">
                        <span>🏷️ Code PROMO20</span>
                        <span class="fmock-badge badge-green">Actif — 23 utilisations</span>
                    </div>
                    <div class="fmock-row">
                        <span>📘 Facebook Pixel</span>
                        <span class="fmock-badge badge-green">Connecté</span>
                    </div>
                    <div class="fmock-row">
                        <span>⭐ Avis automatiques</span>
                        <span class="fmock-badge badge-blue">4.9/5 — 87 avis</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ══ TARIFS ══ --}}
<section class="pricing-section" id="tarifs">
    <div class="section-inner">
        <div class="pricing-header">
            <span class="section-tag">Tarification</span>
            <h2 class="section-title">Simple et transparent</h2>
            <p class="section-sub" style="margin:0 auto;">Démarrez gratuitement. Nafalo prend uniquement une commission sur vos ventes — pas de frais fixes.</p>
        </div>
        <div class="pricing-card">
            <div class="pricing-plan">Pour tous les créateurs</div>
            <div class="pricing-price">Gratuit <span>pour commencer</span></div>
            <div class="pricing-desc">Seulement 5% de commission par vente. Aucun abonnement mensuel.</div>
            <ul class="pricing-features">
                <li><i class="fas fa-check"></i> Boutique en ligne illimitée</li>
                <li><i class="fas fa-check"></i> Produits illimités</li>
                <li><i class="fas fa-check"></i> Livraison automatique</li>
                <li><i class="fas fa-check"></i> Paiements Mobile Money & Carte</li>
                <li><i class="fas fa-check"></i> Dashboard analytics complet</li>
                <li><i class="fas fa-check"></i> Support par email</li>
            </ul>
            <a href="#" class="btn-hero-primary" style="display:flex;justify-content:center;" onclick="openModal('register'); return false;">
                <i class="fas fa-rocket"></i> Créer ma boutique gratuitement
            </a>
        </div>
    </div>
</section>

{{-- ══ CTA FINAL ══ --}}
<section class="cta-final">
    <div class="cta-final-inner">
        <h2>Prêt à générer vos<br>premiers revenus ?</h2>
        <p>Rejoignez +10 000 créateurs qui vendent leurs produits digitaux avec Nafalo. Gratuit pour démarrer.</p>
        <a href="#" class="btn-hero-primary" onclick="openModal('register'); return false;">
            <i class="fas fa-store"></i> Créer ma boutique maintenant
        </a>
    </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo" style="height:80px;width:auto;mix-blend-mode:screen;filter:brightness(1.5);">
                <p>La plateforme N°1 pour vendre vos produits digitaux en Afrique et dans le monde.</p>
            </div>
            <div class="footer-col">
                <h5>Produits</h5>
                <a href="#">E-books</a>
                <a href="#">Formations</a>
                <a href="#">Templates</a>
                <a href="#">Licences</a>
                <a href="#">Coaching</a>
            </div>
            <div class="footer-col">
                <h5>Plateforme</h5>
                <a href="#">Fonctionnalités</a>
                <a href="#">Tarifs</a>
                <a href="#">Témoignages</a>
                <a href="#">Blog</a>
            </div>
            <div class="footer-col">
                <h5>Légal</h5>
                <a href="{{ route('legal.conditions') }}">Conditions d'utilisation</a>
                <a href="{{ route('legal.confidentialite') }}">Politique de confidentialité</a>
                <a href="{{ route('legal.mentions') }}">Mentions légales</a>
                <a href="{{ route('legal.remboursement') }}">Remboursements</a>
                <a href="{{ route('legal.contact') }}">Contact</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Nafalo. Tous droits réservés.</p>
            <div class="footer-socials">
                <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
    </div>
</footer>

{{-- ══ MODAL CONNEXION/INSCRIPTION ══ --}}
<div class="modal-overlay" id="modal" onclick="closeModalOutside(event)">
    <div class="modal-box" style="position:relative;">
        <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal-logo"><img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo"></div>
        <div class="modal-title">Bienvenue sur Nafalo</div>
        <div class="modal-sub">Connectez-vous ou créez votre boutique</div>

        @if(session('status'))
            <div class="alert-box alert-success"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-box alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <div class="auth-tabs">
            <button class="auth-tab active" id="tab-login" onclick="showTab('login')">Connexion</button>
            <button class="auth-tab" id="tab-register" onclick="showTab('register')">Inscription</button>
        </div>

        <div class="auth-form active" id="form-login">
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" required autofocus></div>
                <div class="field"><label>Mot de passe</label><input type="password" name="password" placeholder="••••••••" required></div>
                <div class="field-row">
                    <label class="check-label"><input type="checkbox" name="remember"> Se souvenir de moi</label>
                    <a href="{{ route('admin.password.request') }}" class="forgot">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
            <div class="divider">ou</div>
            <div class="switch-link">Pas encore de compte ? <a href="#" onclick="showTab('register')">Créer une boutique</a></div>
        </div>

        <div class="auth-form" id="form-register">
            <form method="POST" action="{{ route('admin.register') }}">
                @csrf
                <div class="field"><label>Nom complet</label><input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex : Aminata Diallo" required></div>
                <div class="field"><label>Adresse email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" required></div>
                <div class="field"><label>Nom de votre boutique</label><input type="text" name="nom_boutique" value="{{ old('nom_boutique') }}" placeholder="Ex : Ma Boutique Digitale" required></div>
                <div class="field"><label>Mot de passe</label><input type="password" name="password" placeholder="Minimum 8 caractères" required></div>
                <div class="field"><label>Confirmer le mot de passe</label><input type="password" name="password_confirmation" placeholder="Répétez le mot de passe" required></div>
                <button type="submit" class="btn-submit"><i class="fas fa-store"></i> Créer ma boutique gratuitement</button>
            </form>
            <div class="divider">ou</div>
            <div class="switch-link">Déjà un compte ? <a href="#" onclick="showTab('login')">Se connecter</a></div>
        </div>
    </div>
</div>

<script>
// Modal
function openModal(tab) {
    document.getElementById('modal').classList.add('open');
    document.body.style.overflow = 'hidden';
    showTab(tab);
}
function closeModal() {
    document.getElementById('modal').classList.remove('open');
    document.body.style.overflow = '';
}
function closeModalOutside(e) {
    if (e.target === document.getElementById('modal')) closeModal();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// Tabs
function showTab(tab) {
    ['login','register'].forEach(t => {
        document.getElementById('tab-'+t).classList.toggle('active', t===tab);
        document.getElementById('form-'+t).classList.toggle('active', t===tab);
    });
}

// Ouvre le modal si erreur de validation ou message
@if($errors->any() || session('status') || session('success'))
    document.addEventListener('DOMContentLoaded', () => {
        @if(old('nom') || old('nom_boutique'))
            openModal('register');
        @else
            openModal('login');
        @endif
    });
@endif

// Navbar scroll
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
});

// Compteurs animés hero
function animCount(id, target, suffix='') {
    const el = document.getElementById(id);
    if (!el) return;
    let cur = 0, step = target / 80;
    const t = setInterval(() => {
        cur += step;
        if (cur >= target) { cur = target; clearInterval(t); }
        if (id === 'rev-counter') el.textContent = Math.floor(cur).toLocaleString('fr-FR') + ' FCFA';
        else el.textContent = Math.floor(cur).toLocaleString('fr-FR') + suffix;
    }, 20);
}
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animCount('rev-counter', 1845000);
            animCount('sales-counter', 342);
            animCount('clients-counter', 1204);
            obs.disconnect();
        }
    });
});
const mockup = document.querySelector('.hero-mockup');
if (mockup) obs.observe(mockup);
</script>
</body>
</html>
