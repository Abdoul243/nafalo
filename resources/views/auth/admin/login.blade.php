<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nafalo — Vendez vos produits digitaux partout en Afrique</title>
    <meta name="description" content="Créez votre boutique digitale en 5 minutes. Encaissez via Mobile Money, Wave, carte bancaire.">
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    /* ─── RESET ─────────────────────────────────── */
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior:smooth; }
    body { font-family:'Inter',sans-serif; color:#0f172a; background:#fff; overflow-x:hidden; -webkit-font-smoothing:antialiased; }
    a { text-decoration:none; color:inherit; }

    /* ─── VARIABLES ─────────────────────────────── */
    :root {
        --blue:   #0050ff;
        --blue2:  #1e40af;
        --dark:   #0f172a;
        --text:   #374151;
        --muted:  #6b7280;
        --light:  #f5f7ff;
        --border: #e5e7eb;
        --r:      20px;
    }

    /* ─── NAVBAR ────────────────────────────────── */
    nav {
        position:fixed; top:0; left:0; right:0; z-index:999;
        background:rgba(255,255,255,0.95);
        backdrop-filter:blur(20px);
        border-bottom:1px solid var(--border);
        height:70px; display:flex; align-items:center;
        padding:0 4rem; justify-content:space-between;
        transition:box-shadow .3s;
    }
    nav.scrolled { box-shadow:0 2px 24px rgba(0,0,0,0.07); }
    .nav-logo { display:flex; align-items:center; }
    .nav-logo img { height:90px; width:auto; object-fit:contain; }
    .nav-links { display:flex; align-items:center; gap:.25rem; }
    .nav-link { padding:.5rem .95rem; border-radius:10px; font-size:.875rem; font-weight:500; color:var(--muted); transition:all .2s; }
    .nav-link:hover { color:var(--dark); background:#f1f5f9; }
    .nav-right { display:flex; align-items:center; gap:.75rem; }
    .btn-login { padding:.55rem 1.2rem; border-radius:11px; border:1.5px solid var(--border); font-size:.875rem; font-weight:600; color:var(--dark); background:transparent; cursor:pointer; transition:all .2s; font-family:'Inter',sans-serif; }
    .btn-login:hover { border-color:#94a3b8; background:#f8fafc; }
    .btn-cta { padding:.6rem 1.4rem; border-radius:11px; background:var(--blue); color:#fff; font-size:.875rem; font-weight:700; transition:all .2s; display:inline-flex; align-items:center; gap:6px; }
    .btn-cta:hover { background:#0040cc; transform:translateY(-1px); box-shadow:0 6px 20px rgba(0,80,255,.3); color:#fff; }

    /* ─── HERO VIDEO ─────────────────────────────── */
    .hero {
        position:relative; min-height:100vh;
        display:flex; align-items:center; overflow:hidden;
        background:#0a0f1e;
    }
    .hero-video {
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover; opacity:.45; pointer-events:none;
    }
    .hero-overlay {
        position:absolute; inset:0;
        background:linear-gradient(135deg, rgba(0,10,40,.78) 0%, rgba(0,30,90,.55) 60%, rgba(0,10,30,.45) 100%);
    }
    .hero-inner {
        position:relative; z-index:2;
        max-width:1200px; margin:0 auto; width:100%;
        padding:0 2rem;
        display:grid; grid-template-columns:1fr 1fr;
        gap:4rem; align-items:center;
        padding-top:70px;
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:8px;
        padding:.38rem 1rem; border-radius:50px;
        background:rgba(34,197,94,.15); border:1px solid rgba(34,197,94,.3);
        color:#4ade80; font-size:.78rem; font-weight:700;
        margin-bottom:1.5rem;
    }
    .hero-badge .dot { width:7px; height:7px; border-radius:50%; background:#22c55e; animation:blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
    .hero-title { font-size:clamp(2.2rem,4.5vw,4rem); font-weight:900; line-height:1.08; letter-spacing:-2px; color:#fff; margin-bottom:1.4rem; }
    .hero-title span { color:#60a5fa; }
    .hero-sub { font-size:1.05rem; color:rgba(255,255,255,.7); line-height:1.8; margin-bottom:2.5rem; max-width:480px; }
    .hero-btns { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:2.5rem; }
    .btn-primary { display:inline-flex; align-items:center; gap:8px; padding:.9rem 2rem; border-radius:14px; background:var(--blue); color:#fff; font-weight:700; font-size:1rem; transition:all .25s; border:none; cursor:pointer; font-family:'Inter',sans-serif; }
    .btn-primary:hover { background:#0040cc; transform:translateY(-2px); box-shadow:0 12px 32px rgba(0,80,255,.4); color:#fff; }
    .btn-outline { display:inline-flex; align-items:center; gap:8px; padding:.9rem 1.75rem; border-radius:14px; border:1.5px solid rgba(255,255,255,.25); color:#fff; font-weight:600; font-size:1rem; transition:all .2s; background:rgba(255,255,255,.06); }
    .btn-outline:hover { border-color:rgba(255,255,255,.5); background:rgba(255,255,255,.1); }
    .hero-proof { display:flex; align-items:center; gap:1rem; }
    .proof-avatars { display:flex; }
    .proof-avatars span { width:34px; height:34px; border-radius:50%; border:2px solid rgba(255,255,255,.4); margin-left:-10px; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; color:#fff; flex-shrink:0; }
    .proof-avatars span:first-child { margin-left:0; }
    .proof-text { font-size:.82rem; color:rgba(255,255,255,.6); line-height:1.5; }
    .proof-text strong { color:#fff; }

    /* Dashboard mockup in hero */
    .hero-visual { position:relative; }
    .hero-mockup {
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.12);
        backdrop-filter:blur(20px);
        border-radius:24px; overflow:hidden;
        box-shadow:0 40px 80px rgba(0,0,0,.4);
    }
    .hm-bar { padding:.875rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,.08); }
    .hm-dots { display:flex; gap:5px; }
    .hm-dots span { width:10px; height:10px; border-radius:50%; }
    .hm-title { font-size:.72rem; color:rgba(255,255,255,.45); font-weight:600; }
    .hm-live { font-size:.7rem; color:#4ade80; font-weight:700; }
    .hm-body { padding:1.25rem; }
    .hm-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem; margin-bottom:1rem; }
    .hm-kpi { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08); border-radius:14px; padding:.875rem; }
    .hm-kpi .k-label { font-size:.62rem; color:rgba(255,255,255,.4); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem; }
    .hm-kpi .k-val { font-size:1.1rem; font-weight:800; color:#fff; }
    .hm-kpi .k-up { font-size:.65rem; color:#4ade80; font-weight:600; margin-top:2px; }
    .hm-chart { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); border-radius:14px; padding:1rem; margin-bottom:1rem; }
    .hm-chart-label { font-size:.68rem; color:rgba(255,255,255,.4); margin-bottom:.75rem; font-weight:600; }
    .hm-bars { display:flex; align-items:flex-end; gap:5px; height:56px; }
    .hm-bar-item { flex:1; border-radius:4px 4px 0 0; background:linear-gradient(to top, rgba(0,80,255,.6), rgba(0,80,255,.15)); min-height:6px; }
    .hm-bar-item.hi { background:linear-gradient(to top, #22c55e, rgba(34,197,94,.2)); }
    .hm-transactions { display:flex; flex-direction:column; gap:.5rem; }
    .hm-tx { display:flex; align-items:center; justify-content:space-between; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); border-radius:11px; padding:.6rem .875rem; }
    .hm-tx-left { display:flex; align-items:center; gap:8px; }
    .hm-tx-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; }
    .hm-tx-name { font-size:.73rem; font-weight:600; color:rgba(255,255,255,.85); }
    .hm-tx-time { font-size:.62rem; color:rgba(255,255,255,.35); }
    .hm-tx-amount { font-size:.78rem; font-weight:700; color:#4ade80; }

    /* Float cards */
    .fc { position:absolute; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(16px); border-radius:16px; padding:.75rem 1.1rem; box-shadow:0 16px 40px rgba(0,0,0,.3); }
    .fc-1 { top:-24px; right:-16px; animation:float 3.5s ease-in-out infinite alternate; }
    .fc-2 { bottom:-18px; left:-20px; animation:float 4s ease-in-out infinite alternate-reverse; }
    @keyframes float { from{transform:translateY(0)} to{transform:translateY(-14px)} }
    .fc .fc-label { font-size:.62rem; color:rgba(255,255,255,.5); font-weight:600; margin-bottom:3px; }
    .fc .fc-val { font-size:.95rem; font-weight:800; color:#fff; }
    .fc .fc-sub { font-size:.62rem; color:#4ade80; margin-top:2px; font-weight:600; }

    /* ─── STATS BAND ─────────────────────────────── */
    .stats { background:#fff; padding:2.5rem 2rem; border-bottom:1px solid var(--border); }
    .stats-inner { max-width:1100px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:2rem; }
    .stat { text-align:center; }
    .stat-num { font-size:2.2rem; font-weight:900; color:var(--dark); letter-spacing:-1.5px; }
    .stat-num em { color:var(--blue); font-style:normal; }
    .stat-label { font-size:.82rem; color:var(--muted); margin-top:.25rem; }

    /* ─── SECTIONS ───────────────────────────────── */
    .section { padding:7rem 2rem; }
    .section.gray { background:var(--light); }
    .s-inner { max-width:1200px; margin:0 auto; }
    .s-tag { display:inline-block; padding:.35rem .9rem; border-radius:50px; background:#eff6ff; color:var(--blue); font-size:.74rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; margin-bottom:.875rem; }
    .s-title { font-size:clamp(1.8rem,3.5vw,2.8rem); font-weight:900; line-height:1.12; letter-spacing:-1.2px; color:var(--dark); margin-bottom:1rem; }
    .s-sub { font-size:.975rem; color:var(--muted); line-height:1.8; max-width:520px; }

    /* ─── BENTO GRID ─────────────────────────────── */
    .bento { display:grid; grid-template-columns:repeat(12,1fr); gap:1rem; margin-top:3.5rem; }
    .bc { background:#fff; border:1.5px solid var(--border); border-radius:var(--r); padding:1.75rem; overflow:hidden; position:relative; transition:all .3s; }
    .bc:hover { border-color:#bfdbfe; transform:translateY(-4px); box-shadow:0 16px 48px rgba(0,80,255,.08); }
    .bc.c6 { grid-column:span 6; }
    .bc.c4 { grid-column:span 4; }
    .bc.c8 { grid-column:span 8; }
    .bc.c12 { grid-column:span 12; }
    .bc-icon { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; margin-bottom:1.25rem; }
    .bc h3 { font-size:1rem; font-weight:700; color:var(--dark); margin-bottom:.5rem; }
    .bc p { font-size:.875rem; color:var(--muted); line-height:1.7; }
    .bc-badge { display:inline-block; margin-top:.875rem; padding:.28rem .7rem; border-radius:50px; font-size:.72rem; font-weight:700; }

    /* Wide bento card with mockup */
    .bc-wide { display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:center; }
    .bc-mini { background:var(--light); border-radius:14px; padding:1rem; border:1px solid var(--border); }
    .bc-mini-row { display:flex; align-items:center; justify-content:space-between; padding:.45rem 0; border-bottom:1px solid var(--border); font-size:.78rem; }
    .bc-mini-row:last-child { border-bottom:none; }
    .bc-mini-row .lr { display:flex; align-items:center; gap:7px; color:var(--muted); }
    .bc-mini-row .rr { font-weight:700; color:var(--dark); font-size:.75rem; }
    .chip { padding:.2rem .55rem; border-radius:20px; font-size:.65rem; font-weight:700; }
    .chip-g { background:#dcfce7; color:#166534; }
    .chip-b { background:#dbeafe; color:#1e40af; }

    /* ─── VIDEO SECTION ──────────────────────────── */
    .video-section { padding:0; position:relative; overflow:hidden; height:500px; }
    .video-section video { width:100%; height:100%; object-fit:cover; display:block; }
    .video-overlay { position:absolute; inset:0; background:linear-gradient(to right, rgba(0,10,50,.85) 40%, rgba(0,10,50,.3) 100%); display:flex; align-items:center; }
    .video-text { max-width:1200px; margin:0 auto; width:100%; padding:0 2rem; }
    .video-text h2 { font-size:clamp(1.8rem,3.5vw,3rem); font-weight:900; color:#fff; letter-spacing:-1.5px; margin-bottom:1rem; }
    .video-text p { font-size:1rem; color:rgba(255,255,255,.7); max-width:480px; line-height:1.8; margin-bottom:2rem; }

    /* ─── HOW IT WORKS ───────────────────────────── */
    .steps-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:2.5rem; margin-top:3.5rem; position:relative; }
    .steps-grid::before { content:''; position:absolute; top:40px; left:calc(100%/6); width:calc(100%*2/3); height:2px; background:linear-gradient(to right,#bfdbfe,#ddd6fe); }
    .step { text-align:center; position:relative; z-index:1; }
    .step-num { width:80px; height:80px; border-radius:50%; background:#fff; border:2px solid var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:1.5rem; position:relative; box-shadow:0 4px 16px rgba(0,0,0,.06); }
    .step-num .sn { position:absolute; top:-5px; right:-5px; width:22px; height:22px; border-radius:50%; background:var(--blue); color:#fff; font-size:.65rem; font-weight:800; display:flex; align-items:center; justify-content:center; }
    .step h3 { font-size:1rem; font-weight:700; color:var(--dark); margin-bottom:.5rem; }
    .step p { font-size:.875rem; color:var(--muted); line-height:1.7; }

    /* ─── PRODUCTS ───────────────────────────────── */
    .prod-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-top:3.5rem; }
    .prod-card { background:#fff; border:1.5px solid var(--border); border-radius:18px; padding:1.5rem; display:flex; gap:1rem; align-items:flex-start; transition:all .25s; }
    .prod-card:hover { border-color:#bfdbfe; box-shadow:0 8px 30px rgba(0,80,255,.07); transform:translateY(-3px); }
    .prod-emoji { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
    .prod-card h4 { font-size:.95rem; font-weight:700; color:var(--dark); margin-bottom:.3rem; }
    .prod-card p { font-size:.82rem; color:var(--muted); line-height:1.6; }

    /* ─── TESTIMONIALS ───────────────────────────── */
    .testi-header { text-align:center; margin-bottom:3rem; }
    .testi-stars { display:flex; justify-content:center; align-items:center; gap:.75rem; margin-top:1rem; }
    .stars-row { color:#f59e0b; font-size:1.1rem; letter-spacing:1px; }
    .rating-n { font-size:1rem; font-weight:800; color:var(--dark); }
    .rating-c { font-size:.875rem; color:var(--muted); }
    .carousel-wrap { position:relative; overflow:hidden; margin:0 -2rem; }
    .carousel-wrap::before,.carousel-wrap::after { content:''; position:absolute; top:0; bottom:0; width:120px; z-index:2; pointer-events:none; }
    .carousel-wrap::before { left:0; background:linear-gradient(to right, var(--light), transparent); }
    .carousel-wrap::after  { right:0; background:linear-gradient(to left, var(--light), transparent); }
    .carousel-track { display:flex; gap:1.25rem; animation:slide 45s linear infinite; width:max-content; padding:1rem 2rem; }
    .carousel-track:hover { animation-play-state:paused; }
    @keyframes slide { from{transform:translateX(0)} to{transform:translateX(-50%)} }
    .tcard { width:300px; flex-shrink:0; background:#fff; border:1.5px solid var(--border); border-radius:20px; padding:1.5rem; transition:border-color .2s; }
    .tcard:hover { border-color:#bfdbfe; }
    .tcard-stars { color:#f59e0b; font-size:.85rem; margin-bottom:.875rem; }
    .tcard-text { font-size:.875rem; color:var(--text); line-height:1.7; margin-bottom:1.25rem; }
    .tcard-av { display:flex; align-items:center; gap:10px; }
    .tcard-av-img { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; color:#fff; flex-shrink:0; }
    .tcard-name { font-size:.85rem; font-weight:700; color:var(--dark); }
    .tcard-role { font-size:.72rem; color:var(--muted); }

    /* ─── PAIEMENTS ──────────────────────────────── */
    .pay-grid-inner { display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center; }
    .pay-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
    .pay-card { background:#fff; border:1.5px solid var(--border); border-radius:16px; padding:1.25rem 1rem; text-align:center; transition:all .25s; }
    .pay-card:hover { border-color:#bfdbfe; transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,80,255,.07); }
    .pay-card .pm-logo { width:52px; height:52px; border-radius:12px; object-fit:contain; margin:0 auto .6rem; display:block; }
    .pay-card .pm-name { font-size:.78rem; font-weight:700; color:var(--dark); }
    .pay-card .pm-desc { font-size:.68rem; color:var(--muted); margin-top:2px; }
    /* Bande logos inline */
    .pay-logos-strip { display:flex; flex-wrap:wrap; align-items:center; gap:1rem; margin-top:1.5rem; }
    .pay-logo-inline { height:32px; width:auto; border-radius:8px; object-fit:contain; filter:grayscale(20%); transition:filter .2s; }
    .pay-logo-inline:hover { filter:grayscale(0%); }

    /* ─── PRICING ────────────────────────────────── */
    .pricing-wrap { max-width:680px; margin:0 auto; text-align:center; }
    .pricing-card { background:#fff; border:2px solid var(--blue); border-radius:28px; padding:3rem; margin-top:3rem; box-shadow:0 20px 60px rgba(0,80,255,.1); position:relative; overflow:hidden; }
    .pricing-card::before { content:''; position:absolute; top:-80px; left:50%; transform:translateX(-50%); width:300px; height:300px; border-radius:50%; background:radial-gradient(circle, rgba(0,80,255,.06), transparent 70%); pointer-events:none; }
    .plan-label { display:inline-block; padding:.35rem 1rem; border-radius:50px; background:#eff6ff; color:var(--blue); font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:1.25rem; }
    .price-big { font-size:4rem; font-weight:900; color:var(--dark); letter-spacing:-3px; line-height:1; }
    .price-big .sm { font-size:1rem; font-weight:400; color:var(--muted); letter-spacing:0; }
    .price-commission { font-size:1.1rem; color:var(--blue); font-weight:700; margin:.75rem 0 .5rem; }
    .price-desc { color:var(--muted); font-size:.9rem; margin-bottom:2rem; }
    .price-feats { list-style:none; text-align:left; max-width:380px; margin:0 auto 2rem; }
    .price-feats li { display:flex; align-items:center; gap:10px; padding:.6rem 0; border-bottom:1px solid var(--border); font-size:.9rem; color:var(--text); }
    .price-feats li:last-child { border-bottom:none; }
    .price-feats li i { color:#22c55e; width:16px; flex-shrink:0; }

    /* ─── FAQ ────────────────────────────────────── */
    .faq-wrap { max-width:780px; margin:3rem auto 0; display:flex; flex-direction:column; gap:.75rem; }
    .faq-item { background:#fff; border:1.5px solid var(--border); border-radius:16px; overflow:hidden; transition:border-color .2s; }
    .faq-item.open { border-color:#bfdbfe; }
    .faq-q { width:100%; text-align:left; padding:1.25rem 1.5rem; background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:1rem; font-size:.95rem; font-weight:600; color:var(--dark); font-family:'Inter',sans-serif; transition:color .2s; }
    .faq-q:hover { color:var(--blue); }
    .faq-icon { width:28px; height:28px; border-radius:50%; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.75rem; color:var(--muted); transition:all .3s; }
    .faq-item.open .faq-icon { background:#eff6ff; color:var(--blue); transform:rotate(45deg); }
    .faq-a { max-height:0; overflow:hidden; transition:max-height .4s ease, padding .3s; padding:0 1.5rem; font-size:.875rem; color:var(--muted); line-height:1.8; }
    .faq-item.open .faq-a { max-height:300px; padding:0 1.5rem 1.25rem; }

    /* ─── CTA FINAL ──────────────────────────────── */
    .cta-section { position:relative; overflow:hidden; }
    .cta-video { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
    .cta-overlay { position:absolute; inset:0; background:linear-gradient(135deg,rgba(0,10,60,.9) 0%, rgba(0,40,120,.75) 100%); }
    .cta-inner { position:relative; z-index:2; text-align:center; padding:9rem 2rem; max-width:640px; margin:0 auto; }
    .cta-inner h2 { font-size:clamp(2rem,4.5vw,3.5rem); font-weight:900; color:#fff; letter-spacing:-2px; line-height:1.1; margin-bottom:1rem; }
    .cta-inner p { color:rgba(255,255,255,.7); font-size:1.05rem; margin-bottom:2.5rem; line-height:1.7; }
    .cta-btns { display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; }

    /* ─── FOOTER ─────────────────────────────────── */
    footer { background:#0a0f1e; padding:5rem 2rem 2.5rem; border-top:1px solid rgba(255,255,255,.06); }
    .footer-inner { max-width:1200px; margin:0 auto; }
    .footer-top { display:grid; grid-template-columns:2.5fr 1fr 1fr 1fr; gap:3rem; margin-bottom:3.5rem; }
    .footer-brand img { height:80px; width:auto; }
    .footer-brand p { font-size:.875rem; color:#475569; line-height:1.7; max-width:280px; margin-top:1rem; }
    .footer-socials { display:flex; gap:.6rem; margin-top:1.5rem; }
    .fsoc { width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); display:flex; align-items:center; justify-content:center; color:#475569; font-size:.85rem; transition:all .2s; }
    .fsoc:hover { background:var(--blue); border-color:var(--blue); color:#fff; }
    .footer-col h5 { font-size:.75rem; font-weight:700; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:.1em; margin-bottom:1.25rem; }
    .footer-col a { display:block; color:#475569; font-size:.875rem; margin-bottom:.65rem; transition:color .15s; }
    .footer-col a:hover { color:#94a3b8; }
    .footer-bottom { border-top:1px solid rgba(255,255,255,.06); padding-top:1.75rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
    .footer-bottom p { font-size:.8rem; color:#334155; }
    .footer-legal { display:flex; gap:1.5rem; }
    .footer-legal a { font-size:.8rem; color:#334155; transition:color .15s; }
    .footer-legal a:hover { color:#64748b; }

    /* ─── MODAL ──────────────────────────────────── */
    .modal-bg { display:none; position:fixed; inset:0; z-index:2000; background:rgba(0,0,0,.5); backdrop-filter:blur(10px); align-items:center; justify-content:center; }
    .modal-bg.open { display:flex; }
    .modal-box { background:#fff; border-radius:28px; padding:2.5rem; width:100%; max-width:420px; margin:1rem; box-shadow:0 40px 80px rgba(0,0,0,.2); animation:mIn .35s cubic-bezier(.34,1.56,.64,1); position:relative; }
    @keyframes mIn { from{opacity:0;transform:scale(.9) translateY(20px)} to{opacity:1;transform:none} }
    .m-close { position:absolute; top:1.25rem; right:1.25rem; width:32px; height:32px; border-radius:50%; background:#f1f5f9; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.85rem; color:var(--muted); transition:all .2s; }
    .m-close:hover { background:#e2e8f0; }
    .m-logo { text-align:center; margin-bottom:1.75rem; }
    .m-logo img { height:55px; }
    .m-title { font-size:1.2rem; font-weight:800; color:var(--dark); text-align:center; margin-bottom:.3rem; }
    .m-sub { text-align:center; color:var(--muted); font-size:.875rem; margin-bottom:1.75rem; }
    .m-tabs { display:flex; background:#f1f5f9; border-radius:12px; padding:4px; margin-bottom:1.5rem; }
    .m-tab { flex:1; padding:.6rem; text-align:center; border-radius:9px; border:none; background:none; font-weight:600; font-size:.875rem; color:var(--muted); cursor:pointer; transition:all .2s; font-family:'Inter',sans-serif; }
    .m-tab.active { background:#fff; color:var(--dark); box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .m-form { display:none; }
    .m-form.active { display:block; }
    .f { margin-bottom:1rem; }
    .f label { display:block; font-size:.8rem; font-weight:600; color:var(--text); margin-bottom:.4rem; }
    .f input { width:100%; padding:.8rem 1rem; border-radius:12px; border:1.5px solid var(--border); font-size:.9rem; font-family:'Inter',sans-serif; outline:none; transition:all .2s; color:var(--dark); }
    .f input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(0,80,255,.1); }
    .f-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; }
    .f-check { display:flex; align-items:center; gap:6px; font-size:.8rem; color:var(--muted); cursor:pointer; }
    .f-check input { width:auto; padding:0; border:none; accent-color:var(--blue); }
    .forgot { font-size:.8rem; color:var(--blue); font-weight:600; }
    .btn-submit { width:100%; padding:.875rem; border-radius:12px; background:var(--blue); color:#fff; font-weight:700; font-size:.95rem; border:none; cursor:pointer; transition:all .2s; font-family:'Inter',sans-serif; }
    .btn-submit:hover { background:#0040cc; box-shadow:0 8px 20px rgba(0,80,255,.3); }
    .m-divider { display:flex; align-items:center; gap:1rem; margin:1.25rem 0; color:var(--border); font-size:.8rem; }
    .m-divider::before,.m-divider::after { content:''; flex:1; height:1px; background:var(--border); }
    .m-switch { text-align:center; font-size:.82rem; color:var(--muted); }
    .m-switch a { color:var(--dark); font-weight:700; }
    .alert { padding:.7rem 1rem; border-radius:10px; font-size:.84rem; margin-bottom:1rem; }
    .alert-s { background:#f0fdf4; color:#166534; border-left:3px solid #22c55e; }
    .alert-e { background:#fef2f2; color:#991b1b; border-left:3px solid #ef4444; }

    /* ─── ANIMATIONS ON SCROLL ───────────────────── */
    .reveal { opacity:0; transform:translateY(30px); transition:opacity .7s ease, transform .7s ease; }
    .reveal.visible { opacity:1; transform:none; }

    /* ─── RESPONSIVE ─────────────────────────────── */
    @media(max-width:1024px){
        nav { padding:0 1.5rem; }
        .hero-inner { grid-template-columns:1fr; text-align:center; gap:3rem; }
        .hero-btns,.hero-proof { justify-content:center; }
        .hero-sub { margin:0 auto 2.5rem; }
        .hero-visual { max-width:480px; margin:0 auto; }
        .stats-inner { grid-template-columns:repeat(2,1fr); }
        .pay-grid-inner { grid-template-columns:1fr; }
        .footer-top { grid-template-columns:1fr 1fr; gap:2rem; }
        .bc.c6,.bc.c4,.bc.c8 { grid-column:span 12; }
        .bc-wide { grid-template-columns:1fr; }
    }
    @media(max-width:768px){
        nav .nav-links { display:none; }
        nav { padding:0 1.25rem; }
        .hero { min-height:100vh; }
        .hero-inner { padding-top:80px; }
        .section { padding:5rem 1.5rem; }
        .bento { grid-template-columns:1fr; }
        .steps-grid { grid-template-columns:1fr; gap:2rem; }
        .steps-grid::before { display:none; }
        .prod-grid { grid-template-columns:1fr 1fr; }
        .pay-grid { grid-template-columns:repeat(2,1fr); }
        .footer-top { grid-template-columns:1fr; }
        .fc { display:none; }
        .video-section { height:350px; }
    }
    @media(max-width:480px){
        .hero-title { font-size:1.9rem; }
        .btn-primary,.btn-outline { width:100%; justify-content:center; }
        .hero-btns { flex-direction:column; }
        .prod-grid { grid-template-columns:1fr; }
        .stats-inner { gap:1.5rem; }
        .pricing-card { padding:2rem 1.25rem; }
        .cta-btns { flex-direction:column; align-items:center; }
        .modal-box { padding:1.75rem 1.25rem; border-radius:20px; }
        nav { padding:0 1rem; height:62px; }
        .nav-logo img { height:70px; }
    }
    </style>
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav id="navbar">
    <div class="nav-logo">
        <a href="/"><img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo"></a>
    </div>
    <div class="nav-links">
        <a href="#fonctionnalites" class="nav-link">Fonctionnalités</a>
        <a href="#produits"        class="nav-link">Ce que tu vends</a>
        <a href="#paiements"       class="nav-link">Paiements</a>
        <a href="#tarifs"          class="nav-link">Tarifs</a>
    </div>
    <div class="nav-right">
        <button class="btn-login" onclick="openModal()">Se connecter</button>
        <a href="{{ route('admin.register.form') }}" class="btn-cta">
            <i class="fas fa-store"></i> Créer ma boutique
        </a>
    </div>
</nav>

{{-- ══ HERO — VIDÉO RÉELLE ══ --}}
<section class="hero">
    {{-- Vidéo réelle : femme noire faisant un achat en ligne (Pexels #6895604) --}}
    <video class="hero-video" autoplay muted loop playsinline preload="auto">
        <source src="https://videos.pexels.com/video-files/6895604/6895604-hd_1920_1080_25fps.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="dot"></span>
                Plateforme N°1 en Afrique francophone
            </div>
            <h1 class="hero-title">
                Tes produits digitaux<br>méritent <span>mieux</span>
            </h1>
            <p class="hero-sub">
                Crée ta boutique en 5 minutes. Vends tes e-books, formations et templates.
                Encaisse via Mobile Money, Wave et carte — 24h/24, 7j/7, automatiquement.
            </p>
            <div class="hero-btns">
                <a href="{{ route('admin.register.form') }}" class="btn-primary">
                    <i class="fas fa-rocket"></i> Créer ma boutique gratuitement
                </a>
                <a href="#fonctionnalites" class="btn-outline">
                    <i class="fas fa-play-circle"></i> Voir comment ça marche
                </a>
            </div>
            <div class="hero-proof">
                <div class="proof-avatars">
                    <span style="background:linear-gradient(135deg,#667eea,#764ba2)">A</span>
                    <span style="background:linear-gradient(135deg,#f093fb,#f5576c)">K</span>
                    <span style="background:linear-gradient(135deg,#4facfe,#00f2fe)">S</span>
                    <span style="background:linear-gradient(135deg,#43e97b,#38f9d7)">M</span>
                    <span style="background:linear-gradient(135deg,#fa709a,#fee140)">D</span>
                </div>
                <div class="proof-text">
                    <strong>+10 000 créateurs</strong> vendent déjà avec Nafalo<br>
                    ⭐ 4.8/5 — +8 500 avis vérifiés
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="fc fc-1">
                <div class="fc-label">Vente reçue 🎉</div>
                <div class="fc-val">+35 000 FCFA</div>
                <div class="fc-sub">▲ Formation Marketing</div>
            </div>
            <div class="hero-mockup">
                <div class="hm-bar">
                    <div class="hm-dots">
                        <span style="background:#ff5f57"></span>
                        <span style="background:#febc2e"></span>
                        <span style="background:#28c840"></span>
                    </div>
                    <div class="hm-title">nafalo.app — Dashboard</div>
                    <div class="hm-live">● En direct</div>
                </div>
                <div class="hm-body">
                    <div class="hm-kpis">
                        <div class="hm-kpi"><div class="k-label">Revenus</div><div class="k-val">845K</div><div class="k-up">↑ +23%</div></div>
                        <div class="hm-kpi"><div class="k-label">Ventes</div><div class="k-val">247</div><div class="k-up">↑ +41</div></div>
                        <div class="hm-kpi"><div class="k-label">Clients</div><div class="k-val">1.2K</div><div class="k-up">↑ +8</div></div>
                    </div>
                    <div class="hm-chart">
                        <div class="hm-chart-label">Ventes — 7 derniers jours</div>
                        <div class="hm-bars">
                            <div class="hm-bar-item" style="height:35%"></div>
                            <div class="hm-bar-item" style="height:50%"></div>
                            <div class="hm-bar-item" style="height:40%"></div>
                            <div class="hm-bar-item" style="height:65%"></div>
                            <div class="hm-bar-item" style="height:55%"></div>
                            <div class="hm-bar-item" style="height:80%"></div>
                            <div class="hm-bar-item hi" style="height:100%"></div>
                        </div>
                    </div>
                    <div class="hm-transactions">
                        <div class="hm-tx">
                            <div class="hm-tx-left"><div class="hm-tx-icon" style="background:rgba(0,80,255,.15)">📘</div><div><div class="hm-tx-name">Guide Marketing</div><div class="hm-tx-time">Il y a 2 min</div></div></div>
                            <div class="hm-tx-amount">+12 500 F</div>
                        </div>
                        <div class="hm-tx">
                            <div class="hm-tx-left"><div class="hm-tx-icon" style="background:rgba(34,197,94,.12)">🎓</div><div><div class="hm-tx-name">Formation Excel</div><div class="hm-tx-time">Il y a 18 min</div></div></div>
                            <div class="hm-tx-amount">+35 000 F</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fc fc-2">
                <div class="fc-label">Boutique active</div>
                <div class="fc-val">🟢 En ligne</div>
                <div class="fc-sub">5 produits · 0 frais cachés</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ STATS ══ --}}
<div class="stats">
    <div class="stats-inner">
        <div class="stat reveal">
            <div class="stat-num"><em id="s1">0</em>K+</div>
            <div class="stat-label">Créateurs actifs</div>
        </div>
        <div class="stat reveal">
            <div class="stat-num"><em id="s2">0</em>M FCFA</div>
            <div class="stat-label">Encaissés par nos vendeurs</div>
        </div>
        <div class="stat reveal">
            <div class="stat-num"><em id="s3">0</em>K+</div>
            <div class="stat-label">Transactions traitées</div>
        </div>
        <div class="stat reveal">
            <div class="stat-num"><em id="s4">0</em>%</div>
            <div class="stat-label">Livraison automatique</div>
        </div>
    </div>
</div>

{{-- ══ BENTO — FONCTIONNALITÉS ══ --}}
<section class="section" id="fonctionnalites">
    <div class="s-inner">
        <div class="text-center reveal" style="text-align:center">
            <span class="s-tag">Fonctionnalités</span>
            <h2 class="s-title">Tout ce dont tu as besoin<br>pour vendre en ligne</h2>
            <p class="s-sub" style="margin:0 auto">Un seul outil. Zéro frais cachés. Livraison automatique.</p>
        </div>
        <div class="bento">
            <div class="bc c8 bc-wide reveal">
                <div>
                    <div class="bc-icon" style="background:#eff6ff"><i class="fas fa-store" style="color:#0050ff"></i></div>
                    <h3>Ta boutique à ton image</h3>
                    <p>Un sous-domaine personnalisé, ton logo, tes couleurs. Ta boutique est unique et reflète ton identité de marque. Configure en quelques minutes, vends indéfiniment.</p>
                    <span class="bc-badge" style="background:#eff6ff;color:#0050ff">tonnom.nafalo.app</span>
                </div>
                <div class="bc-mini">
                    <div class="bc-mini-row"><span class="lr"><i class="fas fa-globe" style="color:#0050ff"></i> Domaine</span><span class="rr" style="color:#0050ff">ma-boutique.nafalo.app</span></div>
                    <div class="bc-mini-row"><span class="lr"><i class="fas fa-image" style="color:#7c3aed"></i> Logo</span><span class="chip chip-g">Configuré ✓</span></div>
                    <div class="bc-mini-row"><span class="lr"><i class="fas fa-check-circle" style="color:#22c55e"></i> Statut</span><span class="chip chip-g">En ligne</span></div>
                    <div class="bc-mini-row"><span class="lr"><i class="fas fa-chart-line" style="color:#f97316"></i> Revenus</span><span class="rr">845 000 FCFA</span></div>
                </div>
            </div>
            <div class="bc c4 reveal">
                <div class="bc-icon" style="background:#f0fdf4"><i class="fas fa-bolt" style="color:#22c55e"></i></div>
                <h3>Livraison instantanée</h3>
                <p>Après chaque paiement, le client reçoit son produit par email automatiquement. Aucune action de ta part.</p>
                <span class="bc-badge" style="background:#f0fdf4;color:#166534">⚡ 0 seconde de délai</span>
            </div>
            <div class="bc c4 reveal">
                <div class="bc-icon" style="background:#faf5ff"><i class="fas fa-chart-line" style="color:#7c3aed"></i></div>
                <h3>Analytics en temps réel</h3>
                <p>Suis tes ventes, revenus et clients depuis un dashboard clair et intuitif.</p>
                <span class="bc-badge" style="background:#faf5ff;color:#7c3aed">📊 Dashboard complet</span>
            </div>
            <div class="bc c4 reveal">
                <div class="bc-icon" style="background:#eff6ff"><i class="fas fa-credit-card" style="color:#0050ff"></i></div>
                <h3>Tous les paiements africains</h3>
                <p>Mobile Money, Wave, Orange Money, Visa/Mastercard. Tes clients paient comme ils veulent.</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:1rem;align-items:center;">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/wave.svg"       alt="Wave"         style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="Wave">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/orange.svg"     alt="Orange Money" style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="Orange Money">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/mtn.svg"        alt="MTN MoMo"     style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="MTN MoMo">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/visa.svg"       alt="Visa"         style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="Visa">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/mastercard.svg" alt="Mastercard"   style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="Mastercard">
                    <img src="https://dashboard.fedapay.com/storage/channel-logos/moov.svg"       alt="Moov Money"   style="height:28px;width:28px;border-radius:6px;object-fit:contain;" title="Moov Money">
                </div>
            </div>
            <div class="bc c4 reveal">
                <div class="bc-icon" style="background:#fff7ed"><i class="fas fa-tag" style="color:#f97316"></i></div>
                <h3>Codes promo & Upsells</h3>
                <p>Crée des réductions et propose des produits complémentaires pour maximiser tes revenus.</p>
                <span class="bc-badge" style="background:#fff7ed;color:#c2410c">+35% de revenus</span>
            </div>
            <div class="bc c4 reveal">
                <div class="bc-icon" style="background:#fef2f2"><i class="fas fa-star" style="color:#ef4444"></i></div>
                <h3>Avis clients automatiques</h3>
                <p>Collecte des avis après chaque achat et affiche-les pour rassurer de nouveaux clients.</p>
                <span class="bc-badge" style="background:#fef2f2;color:#991b1b">⭐ 4.9/5 en moyenne</span>
            </div>
        </div>
    </div>
</section>

{{-- ══ VIDÉO SECTION — SUCCÈS ══ --}}
<div class="video-section">
    {{-- Vidéo réelle : homme qui célèbre un succès sur son laptop (Pexels #5704045) --}}
    <video autoplay muted loop playsinline preload="auto" style="width:100%;height:100%;object-fit:cover;display:block;">
        <source src="https://videos.pexels.com/video-files/5704045/5704045-uhd_2560_1440_25fps.mp4" type="video/mp4">
    </video>
    <div class="video-overlay">
        <div class="video-text">
            <h2>Vendre en ligne<br>n'a jamais été aussi simple</h2>
            <p>Nafalo automatise tout — de la création de ta boutique jusqu'à la livraison du produit à ton client. Toi, tu crées. Nafalo vend.</p>
            <a href="{{ route('admin.register.form') }}" class="btn-primary">
                <i class="fas fa-arrow-right"></i> Démarrer gratuitement
            </a>
        </div>
    </div>
</div>

{{-- ══ COMMENT ÇA MARCHE ══ --}}
<section class="section gray">
    <div class="s-inner">
        <div class="text-center reveal" style="text-align:center">
            <span class="s-tag">Simple & Rapide</span>
            <h2 class="s-title">Lance-toi en 3 étapes</h2>
            <p class="s-sub" style="margin:0 auto">De zéro à ta première vente en moins de 10 minutes.</p>
        </div>
        <div class="steps-grid">
            <div class="step reveal">
                <div class="step-num">🏪<div class="sn">1</div></div>
                <h3>Crée ta boutique</h3>
                <p>Inscris-toi gratuitement, choisis le nom de ta boutique et personnalise-la avec ton logo et tes couleurs.</p>
            </div>
            <div class="step reveal">
                <div class="step-num">📦<div class="sn">2</div></div>
                <h3>Publie tes produits</h3>
                <p>Ajoute tes e-books, formations ou templates. Fixe tes prix. Nafalo génère ta page de vente automatiquement.</p>
            </div>
            <div class="step reveal">
                <div class="step-num">💰<div class="sn">3</div></div>
                <h3>Encaisse & livre</h3>
                <p>Tes clients paient via Mobile Money ou carte. Nafalo livre instantanément le produit et te vire les fonds.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ PRODUITS ══ --}}
<section class="section" id="produits">
    <div class="s-inner">
        <div style="text-align:center;margin-bottom:3.5rem" class="reveal">
            <span class="s-tag">Ce que tu peux vendre</span>
            <h2 class="s-title">Tous tes produits digitaux<br>en un seul endroit</h2>
            <p class="s-sub" style="margin:0 auto">Nafalo supporte tous les formats numériques. Publie en quelques clics.</p>
        </div>
        <div class="prod-grid">
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#eff6ff">📄</div><div><h4>E-books & PDF</h4><p>Guides, livres numériques, rapports avec livraison instantanée.</p></div></div>
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#f0fdf4">🎓</div><div><h4>Formations en ligne</h4><p>Cours vidéo, tutoriels et programmes de formation complets.</p></div></div>
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#faf5ff">🔑</div><div><h4>Licences & Codes</h4><p>Licences logicielles, codes d'activation et clés produit.</p></div></div>
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#fff7ed">📦</div><div><h4>Packs & Bundles</h4><p>Offres groupées de plusieurs produits pour augmenter ton panier moyen.</p></div></div>
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#fef2f2">🎨</div><div><h4>Templates & Design</h4><p>Templates Figma, Canva, PowerPoint et ressources créatives.</p></div></div>
            <div class="prod-card reveal"><div class="prod-emoji" style="background:#fefce8">💬</div><div><h4>Coaching & Sessions</h4><p>Sessions de coaching, consultations et accompagnements personnalisés.</p></div></div>
        </div>
    </div>
</section>

{{-- ══ TÉMOIGNAGES ══ --}}
<section class="section gray" id="temoignages">
    <div class="s-inner">
        <div class="testi-header reveal">
            <span class="s-tag">Témoignages</span>
            <h2 class="s-title">Ils vendent déjà avec Nafalo</h2>
            <div class="testi-stars">
                <div class="stars-row">★★★★★</div>
                <div class="rating-n">4.8</div>
                <div class="rating-c">sur 5 · +8 500 avis</div>
            </div>
        </div>
    </div>
    <div class="carousel-wrap">
        @php $temoignages = [
            ['A','Aminata D.','🇨🇮 Formatrice, Abidjan','linear-gradient(135deg,#667eea,#764ba2)','J\'ai lancé ma boutique en moins d\'une heure. Mes e-books se vendent 24h/24 automatiquement. Nafalo a changé ma vie !'],
            ['K','Kofi M.','🇬🇭 Entrepreneur, Accra','linear-gradient(135deg,#f093fb,#f5576c)','Mon CA a triplé depuis Nafalo. Les paiements Mobile Money marchent parfaitement et mes clients adorent l\'expérience.'],
            ['S','Seydou B.','🇸🇳 Consultant, Dakar','linear-gradient(135deg,#4facfe,#00f2fe)','Fini les envois manuels par email. Tout est automatisé. Je me concentre sur la création, Nafalo gère le reste.'],
            ['M','Moussa T.','🇲🇱 Développeur, Bamako','linear-gradient(135deg,#43e97b,#38f9d7)','Je vends mes templates depuis Bamako vers le monde entier. Nafalo m\'a ouvert des marchés inimaginables.'],
            ['F','Fatima O.','🇲🇦 Coach, Casablanca','linear-gradient(135deg,#fa709a,#fee140)','Le dashboard est ultra simple. En quelques clics ma boutique, mes produits et paiements sont configurés.'],
            ['D','David A.','🇧🇯 Créateur, Cotonou','linear-gradient(135deg,#a18cd1,#fbc2eb)','Nafalo c\'est la meilleure décision pour mon business digital. Simple, rapide, et vraiment efficace.'],
            ['N','Nadia L.','🇨🇲 Influenceuse, Douala','linear-gradient(135deg,#fda085,#f6d365)','J\'ai vendu 200 copies de mon guide en 48h grâce à Nafalo. La livraison automatique est parfaite.'],
            ['R','Rachid M.','🇩🇿 Formateur, Alger','linear-gradient(135deg,#89f7fe,#66a6ff)','Je recommande Nafalo à tous mes élèves qui veulent vendre des produits digitaux. Révolutionnaire !'],
        ]; @endphp
        <div class="carousel-track">
            @foreach(array_merge($temoignages,$temoignages) as $t)
            <div class="tcard">
                <div class="tcard-stars">★★★★★</div>
                <p class="tcard-text">"{{ $t[4] }}"</p>
                <div class="tcard-av">
                    <div class="tcard-av-img" style="background:{{ $t[3] }}">{{ $t[0] }}</div>
                    <div><div class="tcard-name">{{ $t[1] }}</div><div class="tcard-role">{{ $t[2] }}</div></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ VIDÉO BANDE — ÉQUIPE QUI CÉLÈBRE ══ --}}
<div class="video-section" style="height:420px;">
    {{-- Vidéo réelle : femmes entrepreneures qui font high-five (Pexels #8170608) --}}
    <video autoplay muted loop playsinline preload="auto" style="width:100%;height:100%;object-fit:cover;display:block;">
        <source src="https://videos.pexels.com/video-files/8170608/8170608-uhd_2560_1440_25fps.mp4" type="video/mp4">
    </video>
    <div class="video-overlay" style="background:linear-gradient(135deg,rgba(0,10,60,.82) 0%,rgba(0,40,120,.6) 100%);">
        <div class="video-text" style="text-align:center;padding:0 2rem;">
            <div style="max-width:700px;margin:0 auto;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:.38rem 1rem;border-radius:50px;background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80;font-size:.8rem;font-weight:700;margin-bottom:1.25rem;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    +10 000 vendeurs actifs
                </div>
                <h2 style="font-size:clamp(1.7rem,3.5vw,2.8rem);font-weight:900;color:#fff;letter-spacing:-1.5px;margin-bottom:1rem;line-height:1.15;">
                    Des créateurs comme toi<br>transforment leur passion en revenus
                </h2>
                <p style="color:rgba(255,255,255,.75);font-size:1rem;max-width:520px;margin:0 auto 2rem;line-height:1.8;">
                    Chaque jour, des centaines de vendeurs en Afrique lancent leur boutique et encaissent leurs premières ventes avec Nafalo.
                </p>
                <a href="{{ route('admin.register.form') }}" class="btn-primary" style="font-size:.95rem;">
                    <i class="fas fa-rocket"></i> Rejoins-les gratuitement
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ══ PAIEMENTS ══ --}}
<section class="section" id="paiements">
    <div class="s-inner">
        <div class="pay-grid-inner">
            <div class="reveal">
                <span class="s-tag">Paiements</span>
                <h2 class="s-title">Tous les moyens<br>de paiement africains</h2>
                <p class="s-sub">Tes clients paient avec le mode qu'ils utilisent chaque jour. Aucun compte bancaire requis pour acheter.</p>
                <ul style="list-style:none;margin-top:1.5rem;display:flex;flex-direction:column;gap:.65rem">
                    <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;color:var(--text)"><i class="fas fa-check" style="color:#22c55e"></i> Paiements instantanés et sécurisés</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;color:var(--text)"><i class="fas fa-check" style="color:#22c55e"></i> Confirmation automatique en temps réel</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;color:var(--text)"><i class="fas fa-check" style="color:#22c55e"></i> Aucun frais d'installation</li>
                    <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;color:var(--text)"><i class="fas fa-check" style="color:#22c55e"></i> Disponible dans 10+ pays africains</li>
                </ul>
                <div class="pay-logos-strip">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/wave.svg"       alt="Wave"         title="Wave">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/orange.svg"     alt="Orange Money" title="Orange Money">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/mtn.svg"        alt="MTN MoMo"     title="MTN MoMo">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/moov.svg"       alt="Moov Money"   title="Moov Money">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/free.svg"       alt="Free Money"   title="Free Money">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/visa.svg"       alt="Visa"         title="Visa">
                    <img class="pay-logo-inline" src="https://dashboard.fedapay.com/storage/channel-logos/mastercard.svg" alt="Mastercard"   title="Mastercard">
                </div>
            </div>
            <div class="pay-grid reveal">
                <div class="pay-card">
                    <img class="pm-logo" src="https://dashboard.fedapay.com/storage/channel-logos/wave.svg" alt="Wave" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div style="display:none;width:52px;height:52px;border-radius:12px;background:#0066ff;color:#fff;font-weight:900;font-size:1.1rem;align-items:center;justify-content:center;margin:0 auto .6rem;">W</div>
                    <div class="pm-name">Wave</div><div class="pm-desc">CI, SN, ML, GN…</div>
                </div>
                <div class="pay-card">
                    <img class="pm-logo" src="https://dashboard.fedapay.com/storage/channel-logos/orange.svg" alt="Orange Money" onerror="this.style.display='none'">
                    <div class="pm-name">Orange Money</div><div class="pm-desc">Toute l'Afrique</div>
                </div>
                <div class="pay-card">
                    <img class="pm-logo" src="https://dashboard.fedapay.com/storage/channel-logos/mtn.svg" alt="MTN MoMo" onerror="this.style.display='none'">
                    <div class="pm-name">MTN MoMo</div><div class="pm-desc">GH, CM, UG…</div>
                </div>
                <div class="pay-card" style="grid-column:span 1;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:4px;margin-bottom:.6rem;">
                        <img style="height:28px;width:auto;border-radius:4px;object-fit:contain;" src="https://dashboard.fedapay.com/storage/channel-logos/visa.svg" alt="Visa">
                        <img style="height:28px;width:auto;border-radius:4px;object-fit:contain;" src="https://dashboard.fedapay.com/storage/channel-logos/mastercard.svg" alt="Mastercard">
                    </div>
                    <div class="pm-name">Visa / Mastercard</div><div class="pm-desc">Monde entier</div>
                </div>
                <div class="pay-card">
                    <img class="pm-logo" src="https://dashboard.fedapay.com/storage/channel-logos/moov.svg" alt="Moov Money" onerror="this.style.display='none'">
                    <div class="pm-name">Moov Money</div><div class="pm-desc">CI, TG, BJ…</div>
                </div>
                <div class="pay-card">
                    <img class="pm-logo" src="https://dashboard.fedapay.com/storage/channel-logos/free.svg" alt="Free Money" onerror="this.style.display='none'">
                    <div class="pm-name">Free Money</div><div class="pm-desc">Sénégal</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TARIF ══ --}}
<section class="section gray" id="tarifs">
    <div class="s-inner">
        <div class="pricing-wrap">
            <div class="reveal" style="text-align:center">
                <span class="s-tag">Tarification</span>
                <h2 class="s-title">Transparent. Sans surprise.</h2>
                <p class="s-sub" style="margin:0 auto">Démarre gratuitement. Tu ne paies que quand tu vends.</p>
            </div>
            <div class="pricing-card reveal">
                <div class="plan-label">Pour tous les créateurs</div>
                <div class="price-big">Gratuit <span class="sm">pour démarrer</span></div>
                <div class="price-commission">5% de commission · Aucun abonnement</div>
                <div class="price-desc">Pas de frais cachés. Pas de frais mensuels. Tu encaisses, on prend 5%.</div>
                <ul class="price-feats">
                    <li><i class="fas fa-check"></i> Boutique en ligne illimitée</li>
                    <li><i class="fas fa-check"></i> Produits digitaux illimités</li>
                    <li><i class="fas fa-check"></i> Livraison automatique par email</li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span>Paiements &nbsp;</span>
                        <img src="https://dashboard.fedapay.com/storage/channel-logos/wave.svg"       alt="Wave"       style="height:20px;width:20px;border-radius:4px;object-fit:contain;vertical-align:middle;" title="Wave">
                        <img src="https://dashboard.fedapay.com/storage/channel-logos/orange.svg"     alt="Orange"     style="height:20px;width:20px;border-radius:4px;object-fit:contain;vertical-align:middle;" title="Orange Money">
                        <img src="https://dashboard.fedapay.com/storage/channel-logos/mtn.svg"        alt="MTN"        style="height:20px;width:20px;border-radius:4px;object-fit:contain;vertical-align:middle;" title="MTN MoMo">
                        <img src="https://dashboard.fedapay.com/storage/channel-logos/visa.svg"       alt="Visa"       style="height:20px;width:20px;border-radius:4px;object-fit:contain;vertical-align:middle;" title="Visa">
                        <img src="https://dashboard.fedapay.com/storage/channel-logos/mastercard.svg" alt="Mastercard" style="height:20px;width:20px;border-radius:4px;object-fit:contain;vertical-align:middle;" title="Mastercard">
                        &amp; plus
                    </li>
                    <li><i class="fas fa-check"></i> Dashboard analytics complet</li>
                    <li><i class="fas fa-check"></i> Codes promo & upsells</li>
                    <li><i class="fas fa-check"></i> Intégration Facebook Pixel</li>
                    <li><i class="fas fa-check"></i> Support client 7j/7</li>
                </ul>
                <a href="{{ route('admin.register.form') }}" class="btn-cta" style="display:inline-flex;padding:1rem 2.5rem;border-radius:14px;font-size:1rem;">
                    <i class="fas fa-store"></i> Créer ma boutique gratuitement
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ FAQ ══ --}}
<section class="section" id="faq">
    <div class="s-inner">
        <div style="text-align:center" class="reveal">
            <span class="s-tag">FAQ</span>
            <h2 class="s-title">Questions fréquentes</h2>
        </div>
        <div class="faq-wrap">
            @php $faqs = [
                ['Nafalo est-il vraiment gratuit ?','Oui ! La création de boutique et la mise en ligne de produits sont 100% gratuites. Nafalo prélève uniquement une commission de 5% sur chaque vente réalisée. Aucun abonnement mensuel, aucun frais caché.'],
                ['Comment mes clients reçoivent-ils leurs produits ?','Dès que le paiement est confirmé (en quelques secondes), Nafalo envoie automatiquement le fichier ou l\'accès au produit à l\'adresse email du client. Aucune action de ta part n\'est requise.'],
                ['Quels types de fichiers puis-je vendre ?','Tu peux vendre n\'importe quel fichier numérique : PDF, MP4, MP3, ZIP, ePub, DOCX, et bien d\'autres. Pour les formations, Nafalo génère un lien sécurisé unique par acheteur.'],
                ['Est-ce que je peux accepter des paiements depuis l\'étranger ?','Absolument. Nafalo accepte les cartes Visa et Mastercard du monde entier, en plus des paiements Mobile Money africains. Tes clients peuvent venir de n\'importe quel pays.'],
                ['Combien de temps pour recevoir mon argent ?','Les fonds sont disponibles sur ton compte Nafalo en temps réel après chaque vente. Les virements vers Mobile Money ou compte bancaire se font sous 24-72h.'],
                ['Mon contenu est-il protégé contre le piratage ?','Nafalo génère des liens de téléchargement sécurisés, à usage unique et à durée limitée. Tu peux aussi configurer le nombre maximum de téléchargements par achat.'],
                ['Puis-je avoir plusieurs boutiques ?','Oui ! Tu peux créer et gérer plusieurs boutiques depuis un seul compte Nafalo, chacune avec son propre domaine, ses produits et ses paramètres.'],
            ]; @endphp
            @foreach($faqs as $i => $faq)
            <div class="faq-item reveal" id="faq-{{ $i }}">
                <button class="faq-q" onclick="toggleFaq({{ $i }})">
                    {{ $faq[0] }}
                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="faq-a">{{ $faq[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA FINAL — VIDÉO ══ --}}
<section class="cta-section">
    {{-- Vidéo réelle : équipe diverse qui célèbre au bureau (Pexels #7693470) --}}
    <video class="cta-video" autoplay muted loop playsinline preload="auto">
        <source src="https://videos.pexels.com/video-files/7693470/7693470-hd_1920_1080_25fps.mp4" type="video/mp4">
    </video>
    <div class="cta-overlay"></div>
    <div class="cta-inner">
        <h2>Prêt à générer<br>tes premiers revenus ?</h2>
        <p>Rejoins +10 000 créateurs africains qui vendent leurs produits digitaux avec Nafalo. Gratuit pour démarrer. Résultats dès le premier jour.</p>
        <div class="cta-btns">
            <a href="{{ route('admin.register.form') }}" class="btn-primary" style="font-size:1rem;padding:1rem 2.5rem;">
                <i class="fas fa-rocket"></i> Créer ma boutique maintenant
            </a>
            <button class="btn-outline" onclick="openModal()" style="font-size:1rem;">
                <i class="fas fa-sign-in-alt"></i> Déjà un compte
            </button>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo">
                <p>La plateforme N°1 pour vendre vos produits digitaux en Afrique et dans le monde. Simple, rapide, sans frais cachés.</p>
                <div class="footer-socials">
                    <a href="#" class="fsoc"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="fsoc"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="fsoc"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="fsoc"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="fsoc"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h5>Produits</h5>
                <a href="#">E-books & PDF</a>
                <a href="#">Formations en ligne</a>
                <a href="#">Templates</a>
                <a href="#">Licences & Codes</a>
                <a href="#">Packs & Bundles</a>
            </div>
            <div class="footer-col">
                <h5>Plateforme</h5>
                <a href="{{ route('admin.register.form') }}">Créer une boutique</a>
                <a href="#fonctionnalites">Fonctionnalités</a>
                <a href="#tarifs">Tarifs</a>
                <a href="#temoignages">Témoignages</a>
                <a href="#faq">FAQ</a>
            </div>
            <div class="footer-col">
                <h5>Légal</h5>
                <a href="{{ route('legal.conditions') }}">Conditions d'utilisation</a>
                <a href="{{ route('legal.confidentialite') }}">Confidentialité</a>
                <a href="{{ route('legal.mentions') }}">Mentions légales</a>
                <a href="{{ route('legal.remboursement') }}">Remboursements</a>
                <a href="{{ route('legal.contact') }}">Contact</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Nafalo. Tous droits réservés.</p>
            <div class="footer-legal">
                <a href="{{ route('legal.conditions') }}">CGU</a>
                <a href="{{ route('legal.confidentialite') }}">Confidentialité</a>
                <a href="{{ route('legal.contact') }}">Contact</a>
            </div>
        </div>
    </div>
</footer>

{{-- ══ MODAL LOGIN ══ --}}
<div class="modal-bg" id="modal" onclick="closeOut(event)">
    <div class="modal-box">
        <button class="m-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="m-logo"><img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo" style="height:55px;width:auto;"></div>
        <div class="m-title">Bienvenue sur Nafalo</div>
        <div class="m-sub">Connecte-toi à ton espace vendeur</div>

        @if(session('status'))
            <div class="alert alert-s"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-e"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <div class="m-tabs">
            <button class="m-tab active">Connexion</button>
            <button class="m-tab" onclick="window.location.href='{{ route('admin.register.form') }}'">Inscription</button>
        </div>

        <div class="m-form active">
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="f"><label>Adresse email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" required autofocus></div>
                <div class="f"><label>Mot de passe</label><input type="password" name="password" placeholder="••••••••" required></div>
                <div class="f-row">
                    <label class="f-check"><input type="checkbox" name="remember"> Se souvenir de moi</label>
                    <a href="{{ route('admin.password.request') }}" class="forgot">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
            <div class="m-divider">ou</div>
            <div class="m-switch">Pas encore de compte ? <a href="{{ route('admin.register.form') }}">Créer ma boutique</a></div>
        </div>
    </div>
</div>

<script>
// Modal
function openModal() { document.getElementById('modal').classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal() { document.getElementById('modal').classList.remove('open'); document.body.style.overflow=''; }
function closeOut(e) { if(e.target===document.getElementById('modal')) closeModal(); }
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });
@if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openModal());
@endif

// Navbar scroll
window.addEventListener('scroll', () => document.getElementById('navbar').classList.toggle('scrolled', scrollY > 30));

// FAQ
function toggleFaq(i) {
    const el = document.getElementById('faq-'+i);
    const was = el.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(x => x.classList.remove('open'));
    if (!was) el.classList.add('open');
}

// Animated counters
function animCount(id, target, dur=1800) {
    const el = document.getElementById(id);
    if(!el) return;
    let c=0, step=target/(dur/16);
    const t = setInterval(()=>{ c+=step; if(c>=target){c=target;clearInterval(t);} el.textContent=Math.floor(c); },16);
}
const statsObs = new IntersectionObserver(entries=>{
    if(entries[0].isIntersecting){
        animCount('s1',10); animCount('s2',120); animCount('s3',85); animCount('s4',100);
        statsObs.disconnect();
    }
},{threshold:.3});
const sb = document.querySelector('.stats');
if(sb) statsObs.observe(sb);

// Scroll reveal animations
const revealObs = new IntersectionObserver(entries=>{
    entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
},{threshold:.12, rootMargin:'0px 0px -40px 0px'});
document.querySelectorAll('.reveal').forEach(el=>revealObs.observe(el));
</script>
</body>
</html>
