<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Nafalo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body { font-family:'Inter',sans-serif; background:#f8fafc; color:#0f172a; }

        /* NAV */
        nav {
            position: sticky; top:0; z-index:100;
            height:64px; background:white;
            border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 2rem;
            box-shadow:0 1px 8px rgba(0,0,0,0.05);
        }
        .nav-logo img { height:50px; width:auto; mix-blend-mode:multiply; }
        .nav-back {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.5rem 1rem; border-radius:9px;
            border:1.5px solid #e2e8f0; background:white;
            color:#64748b; font-size:0.82rem; font-weight:600;
            text-decoration:none; transition:all 0.2s;
        }
        .nav-back:hover { border-color:#2563eb; color:#2563eb; }

        /* HERO */
        .legal-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            padding: 4rem 2rem;
            text-align: center;
            position: relative; overflow: hidden;
        }
        .legal-hero::before {
            content:''; position:absolute; inset:0;
            background: radial-gradient(ellipse 60% 60% at 50% 100%, rgba(37,99,235,0.25), transparent);
        }
        .legal-hero-inner { position:relative; z-index:1; max-width:700px; margin:0 auto; }
        .legal-hero-badge {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.35rem 1rem; border-radius:50px;
            background:rgba(37,99,235,0.2); border:1px solid rgba(96,165,250,0.3);
            color:#93c5fd; font-size:0.78rem; font-weight:600;
            margin-bottom:1.25rem;
        }
        .legal-hero h1 { font-size:clamp(1.8rem,4vw,2.8rem); font-weight:900; color:white; letter-spacing:-1px; margin-bottom:0.75rem; }
        .legal-hero p { color:#94a3b8; font-size:0.95rem; line-height:1.7; }
        .legal-hero .update-date { color:#475569; font-size:0.8rem; margin-top:1rem; }

        /* CONTENU */
        .legal-body { max-width:860px; margin:0 auto; padding:3rem 2rem 5rem; }

        /* Sommaire */
        .toc {
            background:white; border-radius:16px; border:1px solid #e2e8f0;
            padding:1.5rem 2rem; margin-bottom:3rem;
            box-shadow:0 1px 6px rgba(0,0,0,0.04);
        }
        .toc h3 { font-size:0.85rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1rem; }
        .toc ol { margin:0; padding-left:1.25rem; }
        .toc li { margin-bottom:0.4rem; }
        .toc a { color:#374151; font-size:0.875rem; text-decoration:none; font-weight:500; transition:color 0.15s; }
        .toc a:hover { color:#2563eb; }

        /* Sections */
        .legal-section {
            background:white; border-radius:16px; border:1px solid #e2e8f0;
            padding:2rem 2.5rem; margin-bottom:1.5rem;
            box-shadow:0 1px 6px rgba(0,0,0,0.04);
        }
        .section-num {
            display:inline-flex; align-items:center; justify-content:center;
            width:28px; height:28px; border-radius:8px;
            background:#eff6ff; color:#2563eb; font-weight:800; font-size:0.8rem;
            margin-right:10px; flex-shrink:0;
        }
        .legal-section h2 {
            display:flex; align-items:center;
            font-size:1.1rem; font-weight:800; color:#0f172a;
            margin-bottom:1.25rem; padding-bottom:0.75rem;
            border-bottom:1px solid #f1f5f9;
        }
        .legal-section h3 { font-size:0.95rem; font-weight:700; color:#1e40af; margin:1.25rem 0 0.5rem; }
        .legal-section p { font-size:0.9rem; color:#374151; line-height:1.8; margin-bottom:0.75rem; }
        .legal-section p:last-child { margin-bottom:0; }
        .legal-section ul, .legal-section ol { padding-left:1.5rem; margin-bottom:0.75rem; }
        .legal-section li { font-size:0.9rem; color:#374151; line-height:1.7; margin-bottom:0.35rem; }
        .legal-section strong { color:#0f172a; }
        .legal-section a { color:#2563eb; }

        /* Highlight box */
        .highlight-box {
            background:#eff6ff; border-left:4px solid #2563eb;
            border-radius:0 12px 12px 0; padding:1rem 1.25rem;
            margin:1rem 0; font-size:0.875rem; color:#1e40af; line-height:1.6;
        }
        .warn-box {
            background:#fef9c3; border-left:4px solid #f59e0b;
            border-radius:0 12px 12px 0; padding:1rem 1.25rem;
            margin:1rem 0; font-size:0.875rem; color:#92400e; line-height:1.6;
        }

        /* Contact card */
        .contact-card {
            background: linear-gradient(135deg,#0f172a,#1e3a5f);
            border-radius:16px; padding:2rem;
            display:flex; align-items:center; justify-content:space-between;
            gap:1.5rem; margin-top:2rem; flex-wrap:wrap;
        }
        .contact-card p { color:#94a3b8; font-size:0.875rem; line-height:1.6; }
        .contact-card strong { color:white; display:block; font-size:1rem; margin-bottom:0.35rem; }
        .btn-contact {
            display:inline-flex; align-items:center; gap:8px;
            padding:0.75rem 1.5rem; border-radius:11px;
            background:#2563eb; color:white; font-weight:700; font-size:0.875rem;
            text-decoration:none; white-space:nowrap; transition:all 0.2s;
        }
        .btn-contact:hover { background:#1d4ed8; }

        /* Footer */
        .legal-footer {
            background:#0f172a; padding:2rem;
            display:flex; align-items:center; justify-content:space-between;
            flex-wrap:wrap; gap:1rem;
        }
        .legal-footer p { color:#475569; font-size:0.8rem; }
        .legal-footer-links { display:flex; gap:1.5rem; flex-wrap:wrap; }
        .legal-footer-links a { color:#475569; font-size:0.8rem; text-decoration:none; transition:color 0.15s; }
        .legal-footer-links a:hover { color:#94a3b8; }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('admin.login') }}" class="nav-logo">
        <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo">
    </a>
    <a href="javascript:history.back()" class="nav-back">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</nav>

<div class="legal-hero">
    <div class="legal-hero-inner">
        <div class="legal-hero-badge">
            <i class="fas fa-file-alt"></i> @yield('badge', 'Document légal')
        </div>
        <h1>@yield('title')</h1>
        <p>@yield('subtitle')</p>
        <div class="update-date">Dernière mise à jour : {{ date('d F Y') }}</div>
    </div>
</div>

<div class="legal-body">
    @yield('content')

    <div class="contact-card">
        <div>
            <strong>Une question sur ce document ?</strong>
            <p>Notre équipe est disponible pour répondre à toutes vos questions concernant nos politiques et conditions.</p>
        </div>
        <a href="{{ route('legal.contact') }}" class="btn-contact">
            <i class="fas fa-envelope"></i> Nous contacter
        </a>
    </div>
</div>

<footer class="legal-footer">
    <p>&copy; {{ date('Y') }} Nafalo. Tous droits réservés.</p>
    <div class="legal-footer-links">
        <a href="{{ route('legal.conditions') }}">Conditions d'utilisation</a>
        <a href="{{ route('legal.confidentialite') }}">Confidentialité</a>
        <a href="{{ route('legal.mentions') }}">Mentions légales</a>
        <a href="{{ route('legal.remboursement') }}">Remboursements</a>
        <a href="{{ route('legal.contact') }}">Contact</a>
    </div>
</footer>

</body>
</html>
