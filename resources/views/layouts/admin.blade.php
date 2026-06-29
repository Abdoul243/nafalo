<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
    {{-- Always light theme --}}
    <script>document.documentElement.classList.add('theme-light');</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
    /* ═══════════════════════════════════════════════════════════
       DESIGN TOKENS — Light (Chariow style)
    ═══════════════════════════════════════════════════════════ */
    :root {
        --bg:           #f1f3f8;
        --bg-surface:   #ffffff;
        --bg-card:      #ffffff;
        --bg-elevated:  #f8f9fc;
        --sidebar-bg:   #111827;
        --border:       rgba(0,0,0,0.08);
        --border-hover: rgba(0,0,0,0.16);
        --accent:       #f59e0b;
        --accent-2:     #d97706;
        --accent-hover: #d97706;
        --accent-glow:  rgba(245,158,11,0.25);
        --text-1:       #111827;
        --text-2:       rgba(17,24,39,0.65);
        --text-3:       rgba(17,24,39,0.38);
        --green:        #16a34a;
        --red:          #dc2626;
        --sidebar-width: 248px;
    }

    /* ── Base ── */
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
    img, video, iframe, canvas, svg { max-width: 100%; height: auto; }
    a { text-decoration: none; color: inherit; }

    #wrapper { display: flex; min-height: 100vh; }

    /* ═══════════════════════════════════════════════════════════
       SIDEBAR — Chariow style (light gray)
    ═══════════════════════════════════════════════════════════ */
    #sidebar-wrapper {
        width: 248px; min-width: 248px;
        background: #f5f6f6;
        border-right: 1px solid #e9eaeb;
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0; bottom: 0;
        z-index: 1000;
        transition: width 0.28s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
    }
    #wrapper.toggled #sidebar-wrapper { width: 68px; min-width: 68px; }
    #wrapper.toggled .sidebar-text,
    #wrapper.toggled .sidebar-logo-text,
    #wrapper.toggled .sidebar-section-label,
    #wrapper.toggled .sidebar-footer-info,
    #wrapper.toggled .boutique-chevron { display: none !important; }
    #wrapper.toggled .logo-icon { width: 40px; }
    #wrapper.toggled .nav-item a { justify-content: center; padding: 0.7rem 0; }
    #wrapper.toggled .nav-item a svg { margin: 0; }
    #wrapper.toggled .sidebar-logo { justify-content: center; padding: 1.25rem 0; }
    #wrapper.toggled .sidebar-boutique { justify-content: center; padding: 0.85rem 0; }
    #wrapper.toggled .sidebar-footer { justify-content: center; padding: 1rem 0; }

    /* ── Logo area ── */
    .sidebar-logo {
        display: flex; align-items: center; gap: 11px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9eaeb;
        text-decoration: none; min-height: 64px;
    }
    .logo-icon {
        width: 36px; height: 36px; flex-shrink: 0;
        overflow: hidden; background: #fff;
        border-radius: 10px; padding: 3px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.06);
        display: flex; align-items: center; justify-content: center;
    }
    .logo-icon img {
        width: 100%; height: 100%;
        object-fit: cover; object-position: 80% 20%;
    }
    .logo-icon-letter { font-size: 1rem; font-weight: 900; color: #f59e0b; display: none; }
    .sidebar-logo-text {
        font-size: 1.22rem; font-weight: 900; letter-spacing: -0.04em;
        color: #111827;
        white-space: nowrap; line-height: 1;
    }

    /* ── Boutique switcher ── */
    .sidebar-boutique {
        display: flex; align-items: center; gap: 10px;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e9eaeb;
        cursor: pointer; transition: background 0.15s;
    }
    .sidebar-boutique:hover { background: rgba(0,0,0,0.03); }
    .b-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 0.82rem; flex-shrink: 0; overflow: hidden;
        position: relative;
    }
    .b-avatar img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .b-name { font-size: 0.82rem; font-weight: 600; color: #242528; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .boutique-chevron { color: #9ca3af; font-size: 0.62rem; }

    /* ── Nav ── */
    .sidebar-nav { flex: 1; padding: 0.5rem 0; overflow-y: auto; overflow-x: hidden; }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 2px; }

    .sidebar-section-label {
        font-size: 0.6rem; font-weight: 800; letter-spacing: 0.14em;
        text-transform: uppercase; color: #9ca3af;
        padding: 1rem 1.25rem 0.3rem;
    }
    .nav-item a {
        display: flex; align-items: center; gap: 10px;
        padding: 0.5rem 0.75rem;
        border-radius: 8px; margin: 1px 0.5rem;
        color: #4a4e54; text-decoration: none;
        font-size: 0.84rem; font-weight: 500;
        transition: all 0.15s; white-space: nowrap;
        position: relative;
    }
    .nav-item a svg { width: 20px; height: 20px; flex-shrink: 0; transition: color 0.15s; }
    .nav-item a:hover { background: #e5e7e8; color: #242528; }
    .nav-item a.active { background: #e5e7e8; color: #242528; font-weight: 600; }
    .nav-item a.active svg { color: #ca8a04; }

    /* ── Footer user ── */
    .sidebar-footer {
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #e9eaeb;
        display: flex; align-items: center; gap: 10px;
    }
    .sf-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 800; color: #fff; flex-shrink: 0;
        overflow: hidden; position: relative;
    }
    .sf-avatar img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .sidebar-footer-info { overflow: hidden; }
    .sf-name { font-size: 0.8rem; font-weight: 700; color: #242528; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sf-email { font-size: 0.68rem; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* ═══════════════════════════════════════════════════════════
       MAIN AREA
    ═══════════════════════════════════════════════════════════ */
    #page-content-wrapper {
        flex: 1;
        margin-left: 248px;
        transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
        display: flex; flex-direction: column;
        min-height: 100vh;
    }
    #wrapper.toggled #page-content-wrapper { margin-left: 68px; }

    /* ── Topbar ── */
    .topbar {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 0 1.5rem;
        height: 60px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 999;
    }
    .topbar-left, .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

    .toggle-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #6b7280; font-size: 0.85rem;
        transition: all 0.15s;
    }
    .toggle-btn:hover { background: #f1f5f9; color: #111827; border-color: #d1d5db; }

    .tb-boutique {
        display: flex; align-items: center; gap: 7px;
        padding: 0.35rem 0.7rem;
        border: 1px solid #e5e7eb;
        border-radius: 9px; cursor: pointer;
        background: #f9fafb;
        transition: all 0.15s;
    }
    .tb-boutique:hover { border-color: #d1d5db; background: #f1f5f9; }
    .tb-b-name { font-weight: 600; font-size: 0.8rem; color: #111827; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .btn-visit-store {
        display: flex; align-items: center; gap: 6px;
        padding: 0.38rem 0.875rem;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        font-size: 0.78rem; font-weight: 600;
        color: #374151;
        background: #f9fafb;
        text-decoration: none; transition: all 0.15s;
    }
    .btn-visit-store:hover { border-color: #d1d5db; color: #111827; background: #f1f5f9; }

    .user-menu-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 0.35rem 0.7rem;
        border-radius: 9px; cursor: pointer;
        background: none; border: none; transition: all 0.15s;
    }
    .user-menu-btn:hover { background: #f3f4f6; }
    .u-avatar {
        width: 30px; height: 30px; border-radius: 50%;
        background: #f59e0b;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 0.72rem;
    }
    .u-name { font-size: 0.8rem; font-weight: 600; color: #111827; }

    /* Notification bell */
    .notif-btn {
        position: relative; width: 34px; height: 34px;
        border-radius: 9px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #6b7280; font-size: 0.9rem;
        transition: all 0.15s;
    }
    .notif-btn:hover { background: #f1f5f9; color: #111827; }
    .notif-badge {
        position: absolute; top: -4px; right: -4px;
        background: #ef4444; color: white;
        font-size: 0.58rem; font-weight: 700;
        min-width: 15px; height: 15px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        padding: 0 3px;
        border: 2px solid #fff;
    }
    .notif-dropdown {
        width: 360px; max-height: 480px;
        overflow-y: auto; padding: 0 !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.12) !important;
    }
    .notif-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1rem 0.6rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .notif-header h6 { margin: 0; font-size: 0.875rem; font-weight: 700; color: #111827; }
    .notif-item {
        display: flex; gap: 10px; padding: 0.75rem 1rem;
        border-bottom: 1px solid #f9fafb;
        transition: background 0.1s; cursor: pointer;
    }
    .notif-item:hover { background: #fafafa; }
    .notif-item.non-lue { background: #fffbeb; }
    .notif-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
    .notif-content { flex: 1; min-width: 0; }
    .notif-titre { font-size: 0.82rem; font-weight: 700; color: #111827; line-height: 1.3; }
    .notif-msg { font-size: 0.75rem; color: #6b7280; line-height: 1.4; margin-top: 2px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .notif-temps { font-size: 0.67rem; color: #9ca3af; margin-top: 3px; }
    .notif-dot { width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; flex-shrink: 0; margin-top: 5px; }
    .notif-empty { padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.85rem; }
    @media (max-width: 480px) { .notif-dropdown { width: 300px; } }

    /* ── Content ── */
    .main-content {
        padding: 1.75rem;
        flex: 1;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Dropdown (light) ── */
    .dropdown-menu {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 14px !important;
        padding: 0.4rem !important;
        box-shadow: 0 16px 48px rgba(0,0,0,0.1) !important;
    }
    .dropdown-item {
        border-radius: 8px; padding: 0.5rem 0.875rem;
        font-size: 0.84rem; display: flex; align-items: center;
        gap: 8px; color: #374151;
        background: none; transition: all 0.12s;
    }
    .dropdown-item:hover { background: #f9fafb !important; color: #111827 !important; }
    .dropdown-item i { width: 14px; color: #9ca3af; font-size: 0.8rem; }
    .dropdown-item.text-danger { color: #dc2626 !important; }
    .dropdown-item.text-danger:hover { background: #fef2f2 !important; }
    .dropdown-divider { border-color: #f1f5f9 !important; margin: 0.25rem 0.5rem !important; }

    /* ── Cards (light) ── */
    .card {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 16px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
    }
    .card-header {
        background: #fafafa !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #111827;
    }
    .card-body { color: #374151; }

    /* ── Tables (light) ── */
    .table { color: #374151; }
    .table thead th {
        background: #fafafa !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #9ca3af; font-weight: 700;
        font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.06em;
    }
    .table tbody td { border-color: #f9fafb; color: #374151; vertical-align: middle; }
    .table tbody tr:hover td { background: #fafafa; }

    /* ── Buttons ── */
    .btn-primary { background: #f59e0b !important; border: none !important; border-radius: 10px; font-weight: 600; color: #fff !important; }
    .btn-primary:hover { background: #d97706 !important; }
    .btn-secondary { background: #f9fafb !important; border: 1px solid #e5e7eb !important; color: #374151 !important; border-radius: 10px; }
    .btn-secondary:hover { background: #f1f5f9 !important; color: #111827 !important; border-color: #d1d5db !important; }
    .btn-outline-secondary { border-color: #e5e7eb !important; color: #6b7280 !important; border-radius: 10px; }
    .btn-outline-secondary:hover { background: #f9fafb !important; color: #111827 !important; }
    .btn-danger { background: #fee2e2 !important; border: 1px solid #fecaca !important; color: #dc2626 !important; border-radius: 10px; }
    .btn-danger:hover { background: #fecaca !important; }
    .btn-success { background: #dcfce7 !important; border: 1px solid #bbf7d0 !important; color: #166534 !important; border-radius: 10px; }
    .btn-light { background: #f9fafb !important; border: 1px solid #e5e7eb !important; color: #374151 !important; border-radius: 8px; }
    .btn-light:hover { background: #f1f5f9 !important; }

    /* ── Forms (light) ── */
    .form-control, .form-select {
        background: #ffffff !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 10px; color: #111827 !important;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #f59e0b !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.1) !important;
        color: #111827 !important;
    }
    .form-control::placeholder { color: #9ca3af !important; }
    .form-label { color: #374151; font-weight: 600; font-size: 0.83rem; }
    .form-text { color: #9ca3af !important; }
    .form-check-input {
        background-color: #fff !important;
        border-color: #d1d5db !important;
    }
    .form-check-input:checked { background-color: #f59e0b !important; border-color: #f59e0b !important; }
    .form-select option { background: #fff; color: #111827; }
    .input-group-text {
        background: #f9fafb !important;
        border: 1.5px solid #e5e7eb !important;
        color: #9ca3af !important;
    }

    /* ── Alerts ── */
    .alert { border: none; border-radius: 12px; font-size: 0.875rem; }
    .alert-success { background: #f0fdf4 !important; color: #166534 !important; border-left: 3px solid #16a34a !important; }
    .alert-danger { background: #fef2f2 !important; color: #991b1b !important; border-left: 3px solid #dc2626 !important; }
    .alert-warning { background: #fffbeb !important; color: #92400e !important; border-left: 3px solid #d97706 !important; }
    .alert-info { background: #eff6ff !important; color: #1e40af !important; border-left: 3px solid #3b82f6 !important; }
    .btn-close { filter: none !important; }

    /* ── Badge ── */
    .badge.bg-warning { background: #fef9c3 !important; color: #713f12 !important; }
    .badge.bg-danger  { background: #fee2e2 !important; color: #991b1b !important; }
    .badge.bg-success { background: #dcfce7 !important; color: #166534 !important; }
    .badge.bg-primary { background: #ede9fe !important; color: #5b21b6 !important; }

    /* ── Modal ── */
    .modal-content { background: #ffffff !important; border: 1px solid #e5e7eb !important; color: #111827 !important; }
    .modal-header, .modal-footer { border-color: #f1f5f9 !important; }
    .modal-backdrop { background: rgba(0,0,0,0.5) !important; }

    /* ── Pagination ── */
    .page-link { background: #ffffff !important; border-color: #e5e7eb !important; color: #374151 !important; border-radius: 8px !important; }
    .page-link:hover { background: #f9fafb !important; color: #111827 !important; }
    .page-item.active .page-link { background: #111827 !important; border-color: #111827 !important; color: white !important; }
    .page-item.disabled .page-link { opacity: 0.4 !important; }

    /* ── Table responsive ── */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    /* Theme toggle button */
    .theme-toggle-btn {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #6b7280; font-size: 0.88rem;
        transition: all 0.2s;
    }
    .theme-toggle-btn:hover { background: #f1f5f9; color: #111827; }

    /* ═══════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════ */
    @media (max-width: 1024px) and (min-width: 769px) {
        #sidebar-wrapper { width: 68px; min-width: 68px; }
        #sidebar-wrapper .sidebar-text,
        #sidebar-wrapper .sidebar-logo-text,
        #sidebar-wrapper .sidebar-section-label,
        #sidebar-wrapper .sidebar-footer-info,
        #sidebar-wrapper .boutique-chevron { display: none !important; }
        #sidebar-wrapper .logo-icon { width: 40px; }
        #sidebar-wrapper .nav-item a { justify-content: center; padding: 0.7rem 0; }
        #sidebar-wrapper .nav-item a i { margin: 0; }
        #sidebar-wrapper .sidebar-logo { justify-content: center; padding: 1.25rem 0; }
        #sidebar-wrapper .sidebar-boutique { justify-content: center; padding: 0.85rem 0; }
        #sidebar-wrapper .sidebar-footer { justify-content: center; padding: 1rem 0; }
        #page-content-wrapper { margin-left: 68px; }
    }

    @media (max-width: 768px) {
        #sidebar-wrapper {
            width: 272px; min-width: 272px;
            transform: translateX(-100%);
            transition: transform 0.28s ease;
            box-shadow: 8px 0 32px rgba(0,0,0,0.4);
        }
        #wrapper.mobile-open #sidebar-wrapper { transform: translateX(0); }
        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); z-index: 999;
            backdrop-filter: blur(4px);
        }
        #wrapper.mobile-open #sidebar-overlay { display: block; }
        #page-content-wrapper { margin-left: 0 !important; width: 100%; }
        #wrapper.toggled #page-content-wrapper { margin-left: 0 !important; }
        .main-content { padding: 1rem; }
        .topbar { padding: 0 1rem; }
        .btn-visit-store span { display: none; }
        .btn-visit-store { padding: 0.38rem 0.6rem; }
        h1 { font-size: 1.25rem !important; }
        .d-flex.justify-content-between.align-items-center { flex-wrap: wrap; gap: 0.75rem; }
        .col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,
        .col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12 { flex: 0 0 100% !important; max-width: 100% !important; }
        .row.g-2 > [class*="col-"], .row.g-3 > [class*="col-"] { flex: 0 0 100% !important; max-width: 100% !important; }
        .card-body { padding: 0.875rem !important; }
        .card-body.p-0 { padding: 0 !important; }
    }
    @media (max-width: 768px) and (min-width: 400px) {
        .row.g-2.mb-4 > .col-md-3, .row.g-2.mb-4 > .col-md-4,
        .row.mb-4 > .col-md-3, .row.mb-4 > .col-md-4 { flex: 0 0 50% !important; max-width: 50% !important; }
    }
    @media (max-width: 480px) {
        .topbar-right .btn-visit-store { display: none; }
        .main-content { padding: 0.75rem; }
        .btn { font-size: 0.82rem; }
    }
    @media (max-width: 1199px) { .col-xl-4, .col-xl-8 { flex: 0 0 100%; max-width: 100%; } }
    @media (max-width: 991px)  { .col-lg-4, .col-lg-8, .col-lg-3, .col-lg-6 { flex: 0 0 100%; max-width: 100%; } }

    /* ── Filet de sécurité responsive : tout tableau devient scrollable sur petit écran ── */
    @media (max-width: 768px) {
        table { display: block; width: max-content; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-responsive, .cw-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .cw-toolbar { flex-wrap: wrap; }
    }

    /* ══════════════════════════════════════════════
       CHARIOW DESIGN SYSTEM — classes globales
    ══════════════════════════════════════════════ */

    /* Carte page blanche */
    .cw-page {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        min-height: 70vh;
    }

    /* Toolbar */
    .cw-toolbar {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .cw-search {
        flex: 1; min-width: 180px; position: relative;
    }
    .cw-search i {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: .78rem; pointer-events: none;
    }
    .cw-search input {
        width: 100%; height: 38px; padding: 0 12px 0 33px;
        font-size: .83rem; border: 1px solid #e5e7eb; border-radius: 10px;
        outline: none; background: #f9fafb; color: #111827;
        transition: border-color .15s, background .15s;
    }
    .cw-search input:focus { border-color: #7c3aed; background: #fff; }
    .cw-search input::placeholder { color: #9ca3af; }

    /* Boutons toolbar */
    .cw-btn-icon {
        width: 38px; height: 38px; border: 1px solid #e5e7eb; border-radius: 10px;
        background: #f9fafb; color: #6b7280; font-size: .82rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; flex-shrink: 0; transition: all .15s; text-decoration: none;
    }
    .cw-btn-icon:hover { border-color: #374151; color: #111827; background: #fff; }

    .cw-btn-primary {
        height: 38px; padding: 0 18px; font-size: .84rem; font-weight: 600;
        background: #f59e0b; color: #fff; border: none; border-radius: 10px;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        white-space: nowrap; flex-shrink: 0; cursor: pointer; transition: background .15s;
    }
    .cw-btn-primary:hover { background: #d97706; color: #fff; }

    .cw-btn-secondary {
        height: 38px; padding: 0 16px; font-size: .83rem; font-weight: 600;
        background: #fff; color: #374151; border: 1px solid #e5e7eb; border-radius: 10px;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        white-space: nowrap; flex-shrink: 0; cursor: pointer; transition: all .15s;
    }
    .cw-btn-secondary:hover { border-color: #374151; color: #111827; }

    .cw-btn-dark {
        height: 38px; padding: 0 18px; font-size: .84rem; font-weight: 600;
        background: #111827; color: #fff; border: none; border-radius: 10px;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        white-space: nowrap; flex-shrink: 0; cursor: pointer; transition: background .15s;
    }
    .cw-btn-dark:hover { background: #1f2937; color: #fff; }

    /* Tabs */
    .cw-tabs {
        display: flex; gap: 4px; padding: 12px 20px 0;
        border-bottom: 1px solid #f1f5f9; flex-wrap: wrap;
    }
    .cw-tab {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; font-size: .83rem; font-weight: 500;
        color: #6b7280; border: none; background: none; cursor: pointer;
        border-bottom: 2px solid transparent; margin-bottom: -1px;
        text-decoration: none; transition: color .15s; white-space: nowrap;
    }
    .cw-tab:hover { color: #111827; }
    .cw-tab.active { color: #111827; font-weight: 700; border-bottom-color: #111827; }
    .cw-tab-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .cw-tab-count {
        font-size: .69rem; font-weight: 700; padding: 1px 6px;
        border-radius: 10px; background: #f1f5f9; color: #6b7280;
    }
    .cw-tab.active .cw-tab-count { background: #111827; color: #fff; }

    /* Table */
    .cw-table-wrap { overflow-x: auto; }
    .cw-table { width: 100%; border-collapse: collapse; }
    .cw-table thead th {
        padding: 10px 16px; font-size: .71rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase; letter-spacing: .06em;
        background: #fafafa; white-space: nowrap;
        border-bottom: 1px solid #f1f5f9;
    }
    .cw-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background .1s; }
    .cw-table tbody tr:last-child { border-bottom: none; }
    .cw-table tbody tr:hover { background: #fafafa; }
    .cw-table td { padding: 12px 16px; vertical-align: middle; font-size: .84rem; color: #374151; }

    /* Thumbnail */
    .cw-thumb {
        width: 36px; height: 36px; border-radius: 8px;
        object-fit: cover; display: block; flex-shrink: 0;
    }
    .cw-thumb-empty {
        width: 36px; height: 36px; border-radius: 8px;
        background: #f3f4f6; display: flex; align-items: center;
        justify-content: center; color: #d1d5db; font-size: .8rem; flex-shrink: 0;
    }
    .cw-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }

    /* Badges */
    .cw-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px;
        font-size: .73rem; font-weight: 600; white-space: nowrap;
    }
    .cw-badge-green   { background: #ecfdf5; color: #059669; }
    .cw-badge-amber   { background: #fffbeb; color: #d97706; }
    .cw-badge-red     { background: #fef2f2; color: #dc2626; }
    .cw-badge-gray    { background: #f3f4f6; color: #6b7280; }
    .cw-badge-blue    { background: #eff6ff; color: #2563eb; }
    .cw-badge-purple  { background: #f5f3ff; color: #7c3aed; }
    .cw-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

    /* Action 3-dot */
    .cw-action { position: relative; display: inline-block; }
    .cw-action-btn {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e5e7eb;
        background: #fff; display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #6b7280; transition: all .15s; padding: 0;
    }
    .cw-action-btn:hover { background: #f9fafb; border-color: #374151; color: #111827; }
    .cw-dropdown {
        position: fixed; background: #fff; border-radius: 12px;
        border: 1px solid #e5e7eb; box-shadow: 0 8px 30px rgba(0,0,0,.1);
        min-width: 185px; z-index: 99999; display: none; padding: .35rem 0;
        animation: cwFade .12s ease;
    }
    .cw-dropdown.open { display: block; }
    @keyframes cwFade { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:none} }
    .cw-dropdown a, .cw-dropdown button {
        display: flex; align-items: center; gap: 9px; padding: .48rem 1rem;
        color: #374151; font-size: .82rem; font-weight: 500;
        text-decoration: none; background: none; border: none;
        width: 100%; text-align: left; cursor: pointer; transition: background .1s;
    }
    .cw-dropdown a:hover, .cw-dropdown button:hover { background: #f9fafb; color: #111827; }
    .cw-dropdown .cw-divider { border: none; border-top: 1px solid #f1f5f9; margin: .3rem 0; }
    .cw-dropdown .cw-danger:hover { background: #fef2f2; color: #dc2626; }

    /* View / edit buttons */
    .cw-btn-row {
        width: 29px; height: 29px; border-radius: 7px;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem; text-decoration: none; cursor: pointer;
        transition: all .15s; padding: 0;
    }
    .cw-btn-row:hover { background: #111827; color: #fff; border-color: #111827; }

    /* Empty state */
    .cw-empty { text-align: center; padding: 4rem 1rem; color: #9ca3af; }
    .cw-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; color: #e5e7eb; }
    .cw-empty p { font-size: .85rem; margin: 0 0 1rem; color: #9ca3af; }

    /* Pagination */
    .cw-pages { padding: 1rem; border-top: 1px solid #f1f5f9; }
    .cw-pages .pagination { justify-content: center; gap: 4px; margin: 0; }
    .cw-pages .pagination .page-link {
        border-radius: 8px !important; border: 1px solid #e5e7eb;
        color: #374151; font-size: .8rem; padding: 5px 11px; background: #fff;
    }
    .cw-pages .pagination .page-item.active .page-link { background: #111827; border-color: #111827; color: #fff; }
    .cw-pages .pagination .page-link:hover { background: #f9fafb; }

    /* Section title inside page */
    .cw-section-title {
        font-size: .72rem; font-weight: 700; color: #9ca3af;
        text-transform: uppercase; letter-spacing: .06em;
        padding: 16px 20px 8px;
    }
    </style>
    @stack('styles')
</head>
<body>
<div id="wrapper">
<div id="sidebar-overlay" onclick="closeMobileSidebar()"></div>

{{-- ═══ SIDEBAR ═══ --}}
<div id="sidebar-wrapper">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="logo-icon">
            <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
            <span class="logo-icon-letter">N</span>
        </div>
        <span class="sidebar-logo-text">Nafalo</span>
    </a>

    @php
        // Uniquement les boutiques de l'utilisateur connecté (cloisonnement).
        $boutiques = auth()->user()->boutiques()->orderBy('nom')->get();
        $boutiqueCourante = $boutiques->firstWhere('id', session('boutique_id'));
        function isActiveRoute($route) { return request()->routeIs($route) ? 'active' : ''; }
    @endphp

    <div class="sidebar-boutique dropdown" data-bs-toggle="dropdown">
        <div class="b-avatar">
            @if($boutiqueCourante?->logo)
                <img src="{{ $boutiqueCourante->logo_url }}" alt="{{ $boutiqueCourante->nom }}">
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
            <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden;border:1.5px solid rgba(245,158,11,0.3);">
                @if($b->logo)
                    <img src="{{ $b->logo_url }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.innerHTML='{{ strtoupper(substr($b->nom,0,1)) }}'">
                @else
                    {{ strtoupper(substr($b->nom,0,1)) }}
                @endif
            </div>
            {{ $b->nom }}
            @if(session('boutique_id')==$b->id)<i class="fas fa-check ms-auto" style="color:#22c55e;font-size:0.75rem;"></i>@endif
        </a></li>
        @endforeach
        <li><hr class="dropdown-divider my-1"></li>
        <li><a class="dropdown-item" href="{{ route('admin.boutiques.create') }}"><i class="fas fa-plus"></i> Créer une boutique</a></li>
    </ul>

    <nav class="sidebar-nav">
        {{-- Accueil --}}
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="{{ isActiveRoute('admin.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17C14.2 17.622 13.15 18 12 18C10.85 18 9.8 17.622 9 17"/><path d="M2.352 13.214C1.999 10.916 1.822 9.768 2.256 8.749C2.691 7.731 3.654 7.034 5.581 5.641L7.021 4.6C9.419 2.867 10.617 2 12 2C13.383 2 14.582 2.867 16.979 4.6L18.419 5.641C20.346 7.034 21.31 7.731 21.744 8.749C22.178 9.768 22.002 10.916 21.649 13.214L21.348 15.172C20.847 18.429 20.597 20.057 19.429 21.029C18.261 22 16.554 22 13.139 22H10.861C7.447 22 5.739 22 4.571 21.029C3.403 20.057 3.153 18.429 2.653 15.172L2.352 13.214Z"/></svg>
                <span class="sidebar-text">Accueil</span>
            </a>
        </div>

        <div class="sidebar-section-label sidebar-text">Boutique</div>

        {{-- Produits --}}
        <div class="nav-item">
            <a href="{{ route('admin.produits.index') }}" class="{{ isActiveRoute('admin.produits.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C11.182 22 10.4 21.67 8.837 21.01C4.946 19.366 3 18.544 3 17.161V7M12 22C12.818 22 13.6 21.67 15.163 21.01C19.054 19.366 21 18.544 21 17.161V7M12 22L12 11.355"/><path d="M8.326 9.691L5.405 8.278C3.802 7.502 3 7.114 3 6.5C3 5.886 3.802 5.498 5.405 4.722L8.326 3.309C10.129 2.436 11.03 2 12 2C12.97 2 13.871 2.436 15.674 3.309L18.595 4.722C20.198 5.498 21 5.886 21 6.5C21 7.114 20.198 7.502 18.595 8.278L15.674 9.691C13.871 10.564 12.97 11 12 11C11.03 11 10.129 10.564 8.326 9.691Z"/><path d="M6 12L8 13"/></svg>
                <span class="sidebar-text">Produits</span>
            </a>
        </div>

        {{-- Clients --}}
        <div class="nav-item">
            <a href="{{ route('admin.clients.index') }}" class="{{ isActiveRoute('admin.clients.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 7C13 9.209 11.209 11 9 11C6.791 11 5 9.209 5 7C5 4.791 6.791 3 9 3C11.209 3 13 4.791 13 7Z"/><path d="M15 11C17.209 11 19 9.209 19 7C19 4.791 17.209 3 15 3" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 14H7C4.239 14 2 16.239 2 19C2 20.105 2.895 21 4 21H14C15.105 21 16 20.105 16 19C16 16.239 13.761 14 11 14Z" stroke-linejoin="round"/><path d="M17 14C19.761 14 22 16.239 22 19C22 20.105 21.105 21 20 21H18.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="sidebar-text">Clients</span>
            </a>
        </div>

        {{-- Transactions / Ventes --}}
        <div class="nav-item">
            <a href="{{ route('admin.transactions.index') }}" class="{{ isActiveRoute('admin.transactions.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 22H9.622C7.274 22 6.1 22 5.286 21.295C4.471 20.589 4.272 19.399 3.873 17.019L2.669 9.84C2.487 8.754 2.396 8.212 2.688 7.856C2.979 7.5 3.515 7.5 4.586 7.5H19.414C20.485 7.5 21.021 7.5 21.312 7.856C21.604 8.212 21.513 8.754 21.331 9.84L21.052 11.5"/><path d="M17.5 7.5C17.5 4.462 15.038 2 12 2C8.962 2 6.5 4.462 6.5 7.5"/><path d="M16.5 16.5C16.992 15.994 18.3 14 19 14M21.5 16.5C21.009 15.994 19.7 14 19 14M19 14V22" stroke-linejoin="round"/></svg>
                <span class="sidebar-text">Ventes</span>
            </a>
        </div>

        {{-- Analytique --}}
        <div class="nav-item">
            <a href="{{ route('admin.statistiques.ventes') }}" class="{{ isActiveRoute('admin.statistiques.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M7 17L7 13"/><path d="M12 17L12 7"/><path d="M17 17L17 11"/><path d="M2.5 12C2.5 7.522 2.5 5.282 3.891 3.891C5.282 2.5 7.522 2.5 12 2.5C16.478 2.5 18.718 2.5 20.109 3.891C21.5 5.282 21.5 7.522 21.5 12C21.5 16.478 21.5 18.718 20.109 20.109C18.718 21.5 16.478 21.5 12 21.5C7.522 21.5 5.282 21.5 3.891 20.109C2.5 18.718 2.5 16.478 2.5 12Z" stroke-linejoin="round"/></svg>
                <span class="sidebar-text">Analytique</span>
            </a>
        </div>

        <div class="sidebar-section-label sidebar-text">Marketing</div>

        {{-- Réductions --}}
        <div class="nav-item">
            <a href="{{ route('admin.codes-promo.index') }}" class="{{ isActiveRoute('admin.codes-promo.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 5H17.5C16.119 5 15 6.119 15 7.5C15 8.881 13.881 10 12.5 10C11.119 10 10 8.881 10 7.5C10 6.119 8.881 5 7.5 5H3C2.448 5 2 5.448 2 6V10C3.105 10 4 10.895 4 12C4 13.105 3.105 14 2 14V18C2 18.552 2.448 19 3 19H7.5C8.881 19 10 17.881 10 16.5C10 15.119 11.119 14 12.5 14C13.881 14 15 15.119 15 16.5C15 17.881 16.119 19 17.5 19H21C21.552 19 22 18.552 22 18V14C20.895 14 20 13.105 20 12C20 10.895 20.895 10 22 10V6C22 5.448 21.552 5 21 5Z"/></svg>
                <span class="sidebar-text">Réductions</span>
            </a>
        </div>

        {{-- Pixels --}}
        <div class="nav-item">
            <a href="{{ route('admin.pixels.index') }}" class="{{ isActiveRoute('admin.pixels.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.034 20.872C11.057 21.008 9.099 11.789 10.444 10.444C11.789 9.099 21.008 11.056 20.872 13.033C20.778 14.328 18.586 14.84 18.65 15.99C18.669 16.327 19.095 16.634 19.946 17.249C20.538 17.675 21.141 18.09 21.722 18.53C21.955 18.706 22.046 19.002 21.978 19.28C21.651 20.619 20.625 21.649 19.281 21.978C19.002 22.046 18.707 21.955 18.531 21.722C18.091 21.141 17.676 20.538 17.249 19.946C16.635 19.094 16.328 18.669 15.991 18.65C14.841 18.586 14.329 20.778 13.034 20.872Z"/><path d="M7.051 16C4.126 15.101 2 12.377 2 9.157C2 5.204 5.204 2 9.157 2C12.377 2 15.101 4.126 16 7.051" stroke-linecap="round"/><path d="M11 6.955C10.475 6.369 9.713 6 8.865 6C7.283 6 6 7.283 6 8.865C6 9.713 6.369 10.475 6.955 11" stroke-linecap="round"/></svg>
                <span class="sidebar-text">Pixels</span>
            </a>
        </div>

        {{-- Avis --}}
        <div class="nav-item">
            <a href="{{ route('admin.avis.index') }}" class="{{ isActiveRoute('admin.avis.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.245 4.174C11.542 3.408 11.69 3.025 12 3.025C12.31 3.025 12.458 3.408 12.755 4.174L14.178 7.887C14.394 8.447 14.502 8.727 14.7 8.927C14.899 9.127 15.179 9.234 15.739 9.449L19.452 10.872C20.218 11.169 20.601 11.318 20.601 11.628C20.601 11.938 20.218 12.086 19.452 12.383L15.739 13.806C15.179 14.022 14.899 14.129 14.7 14.329C14.502 14.529 14.394 14.808 14.178 15.369L12.755 19.082C12.458 19.847 12.31 20.231 12 20.231C11.69 20.231 11.542 19.847 11.245 19.082L9.822 15.369C9.606 14.808 9.498 14.529 9.3 14.329C9.101 14.129 8.821 14.022 8.261 13.806L4.548 12.383C3.782 12.086 3.399 11.938 3.399 11.628C3.399 11.318 3.782 11.169 4.548 10.872L8.261 9.449C8.821 9.234 9.101 9.127 9.3 8.927C9.498 8.727 9.606 8.447 9.822 7.887L11.245 4.174Z"/></svg>
                <span class="sidebar-text">Avis</span>
            </a>
        </div>

        {{-- Co-publication --}}
        <div class="nav-item">
            <a href="{{ route('admin.copublications.index') }}" class="{{ isActiveRoute('admin.copublications.*') }}" style="position:relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 7C15 4.239 12.761 2 10 2C7.239 2 5 4.239 5 7C5 9.761 7.239 12 10 12C12.761 12 15 9.761 15 7Z"/><path d="M21 14.5H16.5C15.672 14.5 15 15.172 15 16C15 16.828 15.672 17.5 16.5 17.5H19.5C20.328 17.5 21 18.172 21 19C21 19.828 20.328 20.5 19.5 20.5H15M18 13V22"/><path d="M3 19C3 15.134 6.134 12 10 12C11.074 12 12.091 12.242 13 12.674"/></svg>
                <span class="sidebar-text">Co-publication</span>
                @php
                    try { $nbInvitCopub = auth()->user()?->invitationsCopublicationEnAttente()?->count() ?? 0; }
                    catch (\Exception $e) { $nbInvitCopub = 0; }
                @endphp
                @if($nbInvitCopub > 0)
                    <span class="badge bg-warning ms-1" style="font-size:0.62rem;padding:2px 6px;">{{ $nbInvitCopub }}</span>
                @endif
            </a>
        </div>

        {{-- Upsells (lié aux produits) --}}
        <div class="nav-item">
            <a href="{{ route('admin.produits.index') }}" class="{{ isActiveRoute('admin.produits.upsells.*') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M8.628 12.674H8.169C6.685 12.674 5.944 12.674 5.627 12.184C5.311 11.695 5.612 11.014 6.215 9.651L8.027 5.553C8.575 4.314 8.849 3.694 9.38 3.347C9.911 3 10.586 3 11.935 3H14.024C15.663 3 16.483 3 16.792 3.535C17.101 4.071 16.694 4.786 15.881 6.216L14.809 8.102C14.405 8.813 14.203 9.168 14.206 9.46C14.209 9.838 14.41 10.186 14.735 10.377C14.985 10.524 15.393 10.524 16.207 10.524C17.237 10.524 17.752 10.524 18.021 10.702C18.369 10.934 18.551 11.348 18.487 11.763C18.438 12.083 18.092 12.466 17.399 13.232L11.864 19.352C10.777 20.555 10.233 21.156 9.868 20.965C9.503 20.775 9.678 19.982 10.029 18.396L10.716 15.29C10.983 14.082 11.116 13.478 10.795 13.076C10.474 12.674 9.859 12.674 8.628 12.674Z"/></svg>
                <span class="sidebar-text">Upsells</span>
            </a>
        </div>

        <div class="sidebar-section-label sidebar-text">Système</div>

        {{-- KYC --}}
        <div class="nav-item">
            <a href="{{ route('admin.kyc.index') }}" class="{{ isActiveRoute('admin.kyc.*') }}" style="position:relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="3"/><circle cx="8.5" cy="12" r="2"/><path d="M13 9.5h4M13 12h3M13 14.5h2"/></svg>
                <span class="sidebar-text">Vérification KYC</span>
                @php
                    try { $kycStatut = auth()->user()?->kyc?->statut ?? 'non_soumis'; $showKycBadge = in_array($kycStatut, ['non_soumis', 'rejete']); }
                    catch(\Throwable $e) { $showKycBadge = false; }
                @endphp
                @if($showKycBadge)
                    <span class="badge bg-danger ms-1" style="font-size:0.58rem;padding:2px 5px;">!</span>
                @endif
            </a>
        </div>

        {{-- Paramètres --}}
        <div class="nav-item">
            <a href="{{ route('admin.configurations.index') }}" class="{{ request()->routeIs('admin.configurations.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.317 7.141L20.824 6.285C20.45 5.637 20.264 5.313 19.946 5.184C19.629 5.055 19.27 5.157 18.551 5.36L17.331 5.704C16.872 5.81 16.391 5.75 15.973 5.535L15.636 5.34C15.277 5.11 15.001 4.771 14.848 4.373L14.514 3.375C14.294 2.715 14.184 2.385 13.923 2.197C13.661 2.008 13.314 2.008 12.62 2.008H11.505C10.811 2.008 10.464 2.008 10.202 2.197C9.941 2.385 9.831 2.715 9.611 3.375L9.278 4.373C9.125 4.771 8.848 5.11 8.489 5.34L8.152 5.535C7.734 5.75 7.253 5.81 6.794 5.704L5.574 5.36C4.855 5.157 4.496 5.055 4.179 5.184C3.861 5.313 3.674 5.637 3.301 6.285L2.808 7.141C2.458 7.749 2.283 8.052 2.317 8.375C2.351 8.699 2.585 8.959 3.053 9.48L4.084 10.633C4.336 10.952 4.515 11.508 4.515 12.008C4.515 12.508 4.336 13.063 4.084 13.383L3.053 14.535C2.585 15.056 2.351 15.317 2.317 15.64C2.283 15.963 2.458 16.266 2.808 16.874L3.301 17.73C3.674 18.378 3.861 18.702 4.179 18.831C4.496 18.96 4.855 18.858 5.574 18.655L6.794 18.311C7.253 18.205 7.734 18.265 8.152 18.48L8.489 18.675C8.848 18.905 9.125 19.244 9.278 19.642L9.611 20.64C9.831 21.3 9.941 21.63 10.202 21.818C10.464 22.007 10.811 22.007 11.505 22.007H12.62C13.314 22.007 13.661 22.007 13.923 21.818C14.184 21.63 14.294 21.3 14.514 20.64L14.848 19.642C15.001 19.244 15.277 18.905 15.636 18.675L15.973 18.48C16.391 18.265 16.872 18.205 17.331 18.311L18.551 18.655C19.27 18.858 19.629 18.96 19.946 18.831C20.264 18.702 20.45 18.378 20.824 17.73L21.317 16.874C21.667 16.266 21.842 15.963 21.808 15.64C21.774 15.317 21.54 15.056 21.072 14.535L20.041 13.383C19.789 13.063 19.61 12.508 19.61 12.008C19.61 11.508 19.789 10.952 20.041 10.633L21.072 9.48C21.54 8.959 21.774 8.699 21.808 8.375C21.842 8.052 21.667 7.749 21.317 7.141Z"/><path d="M15.008 12.008C15.008 13.665 13.664 15.008 12.008 15.008C10.351 15.008 9.008 13.665 9.008 12.008C9.008 10.351 10.351 9.008 12.008 9.008C13.664 9.008 15.008 10.351 15.008 12.008Z"/></svg>
                <span class="sidebar-text">Paramètres</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="sf-avatar">
            @if(Auth::user()->avatar ?? false)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->nom }}"
                     onerror="this.style.display='none'">
            @endif
            {{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}
        </div>
        <div class="sidebar-footer-info sidebar-text">
            <div class="sf-name">{{ Auth::user()->nom }}</div>
            <div class="sf-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
</div>

{{-- ═══ MAIN ═══ --}}
<div id="page-content-wrapper">
    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <button class="toggle-btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="dropdown">
                <div class="tb-boutique" data-bs-toggle="dropdown">
                    <div class="b-avatar" style="width:28px;height:28px;border-radius:7px;font-size:0.68rem;">
                        @if($boutiqueCourante?->logo)
                            <img src="{{ $boutiqueCourante->logo_url }}" alt="{{ $boutiqueCourante->nom }}"
                                 onerror="this.style.display='none'">
                        @endif
                        {{ strtoupper(substr($boutiqueCourante?->nom ?? 'B', 0, 1)) }}
                    </div>
                    <span class="tb-b-name">{{ $boutiqueCourante?->nom ?? 'Ma boutique' }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.58rem;color:var(--text-3);"></i>
                </div>
                <ul class="dropdown-menu" style="min-width:210px;">
                    @foreach($boutiques as $b)
                    <li><a class="dropdown-item" href="{{ route('admin.boutiques.select', $b->id) }}">
                        <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden;border:1.5px solid rgba(245,158,11,0.3);">
                            @if($b->logo)
                                <img src="{{ $b->logo_url }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.innerHTML='{{ strtoupper(substr($b->nom,0,1)) }}'">
                            @else
                                {{ strtoupper(substr($b->nom,0,1)) }}
                            @endif
                        </div>
                        {{ $b->nom }}
                        @if(session('boutique_id')==$b->id)<i class="fas fa-check ms-auto" style="color:#22c55e;font-size:0.75rem;"></i>@endif
                    </a></li>
                    @endforeach
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.boutiques.choisir') }}"><i class="fas fa-exchange-alt"></i> Changer de boutique</a></li>
                </ul>
            </div>
        </div>
        <div class="topbar-right">
            @if($boutiqueCourante)
            <a href="{{ url('/boutique') }}" target="_blank" class="btn-visit-store">
                <i class="fas fa-external-link-alt" style="font-size:0.68rem;"></i>
                <span>Visiter la boutique</span>
            </a>
            @endif

            {{-- Theme toggle --}}
            <button class="theme-toggle-btn" id="theme-toggle-btn" title="Changer de thème">
                <i class="fas fa-sun" id="theme-icon"></i>
            </button>

            {{-- Notifications --}}
            <div class="dropdown">
                <button class="notif-btn" id="notif-bell-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="fas fa-bell"></i>
                    <span class="notif-badge" id="notif-count" style="display:none;">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown" id="notif-dropdown">
                    <div class="notif-header">
                        <h6>Notifications</h6>
                        <button onclick="marquerToutesLues()" class="btn btn-light btn-sm" style="font-size:0.72rem;padding:2px 8px;">Tout lire</button>
                    </div>
                    <div id="notif-list">
                        <div class="notif-empty"><i class="fas fa-bell-slash mb-2 d-block" style="font-size:1.5rem;"></i>Aucune notification</div>
                    </div>
                    <div style="padding:0.6rem 1rem;border-top:1px solid var(--border);text-align:center;">
                        <a href="{{ route('admin.notifications.index') }}" style="font-size:0.78rem;color:var(--accent);text-decoration:none;font-weight:600;">Voir toutes les notifications</a>
                    </div>
                </div>
            </div>

            {{-- User --}}
            <div class="dropdown">
                <button class="user-menu-btn" data-bs-toggle="dropdown">
                    <div class="u-avatar" style="overflow:hidden;position:relative;">
                        @if(Auth::user()->avatar ?? false)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                 style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;"
                                 onerror="this.style.display='none'" alt="">
                        @endif
                        {{ strtoupper(substr(Auth::user()->nom ?? 'A', 0, 1)) }}
                    </div>
                    <span class="u-name d-none d-md-inline">{{ Auth::user()->nom }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.58rem;color:var(--text-3);"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:190px;">
                    <li><div class="px-3 py-2" style="font-size:0.72rem;color:var(--text-3);">{{ Auth::user()->email }}</div></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.configurations.index') }}"><i class="fas fa-cog"></i> Paramètres</a></li>
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

    {{-- Content --}}
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
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

{{-- Modal confirmation --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color:var(--text-2);font-size:0.9rem;"></div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmModalOk">Confirmer</button>
            </div>
        </div>
    </div>
</div>

@stack('scripts')

{{-- ══════════════════════════════════════════════════════
     CHATBOT IA ADMIN — Widget flottant (uniquement si boutique sélectionnée)
══════════════════════════════════════════════════════ --}}
@if(session('boutique_id'))
<style>
/* ── Bouton flottant ── */
#ai-chat-btn {
    position: fixed; bottom: 24px; right: 24px; z-index: 9000;
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none; cursor: pointer; color: #fff; font-size: 1.3rem;
    box-shadow: 0 4px 20px rgba(245,158,11,0.5);
    display: flex; align-items: center; justify-content: center;
    transition: transform .2s, box-shadow .2s;
}
#ai-chat-btn:hover { transform: scale(1.08); box-shadow: 0 6px 28px rgba(245,158,11,0.65); }
#ai-chat-btn .ai-badge {
    position: absolute; top: -3px; right: -3px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #22c55e; border: 2px solid var(--bg);
    display: none;
}

/* ── Fenêtre chat ── */
#ai-chat-window {
    position: fixed; bottom: 88px; right: 24px; z-index: 9000;
    width: 360px; max-height: 520px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.45);
    display: flex; flex-direction: column;
    overflow: hidden;
    transform: scale(0.92) translateY(12px);
    opacity: 0; pointer-events: none;
    transition: all .22s cubic-bezier(.4,0,.2,1);
}
#ai-chat-window.open {
    transform: scale(1) translateY(0);
    opacity: 1; pointer-events: all;
}

/* ── Header ── */
.ai-chat-header {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 16px;
    background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(251,191,36,.04));
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.ai-chat-avatar {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg,#f59e0b,#d97706);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.ai-chat-title { font-weight: 700; font-size: .87rem; color: var(--text-1); }
.ai-chat-subtitle { font-size: .7rem; color: var(--text-3); }
.ai-chat-close {
    margin-left: auto; background: none; border: none;
    color: var(--text-3); font-size: .9rem; cursor: pointer;
    padding: 4px; border-radius: 6px; transition: all .15s;
}
.ai-chat-close:hover { background: #f1f5f9; color: #111827; }

/* ── Messages ── */
.ai-chat-messages {
    flex: 1; overflow-y: auto; padding: 14px;
    display: flex; flex-direction: column; gap: 10px;
    scrollbar-width: thin; scrollbar-color: #e5e7eb transparent;
}
.ai-chat-messages::-webkit-scrollbar { width: 3px; }
.ai-chat-messages::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }

.ai-msg {
    max-width: 82%; padding: 9px 12px;
    border-radius: 14px; font-size: .82rem; line-height: 1.5;
    word-wrap: break-word;
}
.ai-msg.user {
    align-self: flex-end;
    background: var(--accent); color: #fff;
    border-bottom-right-radius: 4px;
}
.ai-msg.assistant {
    align-self: flex-start;
    background: var(--bg-elevated); color: var(--text-1);
    border: 1px solid var(--border);
    border-bottom-left-radius: 4px;
}
.ai-msg.typing {
    align-self: flex-start;
    background: var(--bg-elevated); border: 1px solid var(--border);
    padding: 10px 14px;
}
.typing-dots span {
    display: inline-block; width: 6px; height: 6px;
    border-radius: 50%; background: var(--text-3); margin: 0 2px;
    animation: typing-bounce 1.2s infinite;
}
.typing-dots span:nth-child(2) { animation-delay: .2s; }
.typing-dots span:nth-child(3) { animation-delay: .4s; }
@keyframes typing-bounce {
    0%,60%,100% { transform: translateY(0); }
    30%          { transform: translateY(-5px); }
}

/* ── Input ── */
.ai-chat-input-wrap {
    display: flex; gap: 8px; padding: 12px;
    border-top: 1px solid var(--border); flex-shrink: 0;
}
.ai-chat-input {
    flex: 1; border: 1.5px solid var(--border); border-radius: 12px;
    padding: 8px 12px; font-size: .82rem;
    background: var(--bg-elevated); color: var(--text-1);
    outline: none; resize: none; max-height: 80px;
    transition: border-color .2s;
    font-family: inherit;
}
.ai-chat-input:focus { border-color: var(--accent); }
.ai-chat-input::placeholder { color: var(--text-3); }
.ai-chat-send {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--accent); border: none; color: #fff;
    font-size: .85rem; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    align-self: flex-end; transition: all .15s;
}
.ai-chat-send:hover { background: var(--accent-hover); }
.ai-chat-send:disabled { opacity: .5; cursor: default; }

/* ── Welcome chips ── */
.ai-welcome-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
.ai-chip {
    padding: 4px 10px; border-radius: 20px; font-size: .71rem;
    border: 1px solid var(--border); color: var(--text-2);
    cursor: pointer; background: var(--bg-elevated);
    transition: all .15s;
}
.ai-chip:hover { border-color: #f59e0b; color: #d97706; background: rgba(245,158,11,.06); }

@media (max-width: 480px) {
    #ai-chat-window { width: calc(100vw - 24px); right: 12px; bottom: 80px; }
    #ai-chat-btn { right: 12px; bottom: 12px; }
}
</style>

{{-- Bouton flottant --}}
<button id="ai-chat-btn" title="Assistant IA" onclick="toggleChat()">
    <span id="ai-chat-icon">✨</span>
    <span class="ai-badge" id="ai-badge"></span>
</button>

{{-- Fenêtre de chat --}}
<div id="ai-chat-window">
    <div class="ai-chat-header">
        <div class="ai-chat-avatar">🤖</div>
        <div>
            <div class="ai-chat-title">Assistant Nafalo</div>
            <div class="ai-chat-subtitle">Posez-moi n'importe quelle question sur votre boutique</div>
        </div>
        <button class="ai-chat-close" onclick="toggleChat()"><i class="fas fa-times"></i></button>
    </div>
    <div class="ai-chat-messages" id="ai-messages">
        <div class="ai-msg assistant">
            Bonjour ! Je suis votre assistant IA Nafalo. Je connais votre boutique et je peux vous aider à analyser vos ventes, améliorer vos produits ou trouver des stratégies marketing. Comment puis-je vous aider ?
            <div class="ai-welcome-chips mt-2">
                <span class="ai-chip" onclick="sendChip(this)">📊 Analyse mes ventes</span>
                <span class="ai-chip" onclick="sendChip(this)">💡 Conseils marketing</span>
                <span class="ai-chip" onclick="sendChip(this)">🔝 Comment augmenter mes revenus ?</span>
                <span class="ai-chip" onclick="sendChip(this)">🤝 Trouver un partenaire</span>
            </div>
        </div>
    </div>
    <div class="ai-chat-input-wrap">
        <textarea id="ai-input" class="ai-chat-input" rows="1"
            placeholder="Posez votre question…"
            onkeydown="aiChatKeydown(event)"
            oninput="autoResize(this)"></textarea>
        <button class="ai-chat-send" id="ai-send-btn" onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
(function () {
    const CHAT_URL  = "{{ route('admin.chatbot') }}";
    const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content;
    let history     = [];
    let isOpen      = false;
    let isLoading   = false;

    window.toggleChat = function () {
        isOpen = !isOpen;
        document.getElementById('ai-chat-window').classList.toggle('open', isOpen);
        document.getElementById('ai-chat-icon').textContent = isOpen ? '✕' : '✨';
        if (isOpen) {
            setTimeout(() => document.getElementById('ai-input').focus(), 220);
            document.getElementById('ai-badge').style.display = 'none';
        }
    };

    window.sendChip = function (el) {
        const text = el.textContent.trim();
        document.getElementById('ai-input').value = text;
        sendMessage();
    };

    window.sendMessage = async function () {
        if (isLoading) return;
        const input = document.getElementById('ai-input');
        const text  = input.value.trim();
        if (!text) return;

        input.value = '';
        input.style.height = '';
        appendMsg('user', text);
        const typingId = appendTyping();

        isLoading = true;
        document.getElementById('ai-send-btn').disabled = true;

        try {
            const res = await fetch(CHAT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text, history }),
            });

            const data = await res.json();
            removeTyping(typingId);
            const reply = data.reply || "Je n'ai pas pu répondre.";
            appendMsg('assistant', reply);
            history.push({ role: 'user', content: text });
            history.push({ role: 'assistant', content: reply });
            if (history.length > 20) history = history.slice(-20);

            if (!isOpen) {
                document.getElementById('ai-badge').style.display = 'block';
            }
        } catch (e) {
            removeTyping(typingId);
            appendMsg('assistant', "Une erreur est survenue. Veuillez réessayer.");
        } finally {
            isLoading = false;
            document.getElementById('ai-send-btn').disabled = false;
            document.getElementById('ai-input').focus();
        }
    };

    function appendMsg(role, content) {
        const div = document.createElement('div');
        div.className = 'ai-msg ' + role;
        div.textContent = content;
        const container = document.getElementById('ai-messages');
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return div;
    }

    let typingCounter = 0;
    function appendTyping() {
        const id = 'typing-' + (++typingCounter);
        const div = document.createElement('div');
        div.className = 'ai-msg typing'; div.id = id;
        div.innerHTML = '<span class="typing-dots"><span></span><span></span><span></span></span>';
        const container = document.getElementById('ai-messages');
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return id;
    }

    function removeTyping(id) {
        document.getElementById(id)?.remove();
    }

    window.aiChatKeydown = function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    };

    window.autoResize = function (el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 80) + 'px';
    };
})();
</script>
@endif {{-- session('boutique_id') --}}

<script>
/* ── Theme (always light) ── */
(function () {
    var icon = document.getElementById('theme-icon');
    if (icon) { icon.className = 'fas fa-sun'; }
})();

/* ── Notifications ── */
(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    function chargerNotifications() {
        fetch('{{ route("admin.notifications.recentes") }}')
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                const badge = document.getElementById('notif-count');
                const list  = document.getElementById('notif-list');
                if (data.nonLues > 0) {
                    badge.textContent = data.nonLues > 9 ? '9+' : data.nonLues;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
                if (data.notifications.length === 0) {
                    list.innerHTML = '<div class="notif-empty"><i class="fas fa-bell-slash mb-2 d-block" style="font-size:1.5rem;"></i>Aucune notification</div>';
                    return;
                }
                list.innerHTML = data.notifications.map(n => `
                    <div class="notif-item ${n.lue ? '' : 'non-lue'}" onclick="ouvrirNotif(${n.id}, '${n.lien ?? ''}')">
                        <div class="notif-icon" style="background:${n.couleurBg};color:${n.couleur};"><i class="${n.icone}"></i></div>
                        <div class="notif-content">
                            <div class="notif-titre">${n.titre}</div>
                            <div class="notif-msg">${n.message}</div>
                            <div class="notif-temps">${n.temps}</div>
                        </div>
                        ${!n.lue ? '<div class="notif-dot"></div>' : ''}
                    </div>
                `).join('');
            }).catch(() => {});
    }
    window.ouvrirNotif = function(id, lien) {
        fetch(`/admin/notifications/${id}/lue`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => { chargerNotifications(); if (lien) window.location.href = lien; });
    };
    window.marquerToutesLues = function() {
        fetch('{{ route("admin.notifications.toutes-lues") }}', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => chargerNotifications());
    };
    chargerNotifications();
    setInterval(chargerNotifications, 60000);
    document.getElementById('notif-bell-btn')?.addEventListener('show.bs.dropdown', chargerNotifications);
})();

/* ── Sidebar Toggle ── */
const wrapper = document.getElementById('wrapper');
function isMobile() { return window.innerWidth <= 768; }
function closeMobileSidebar() { wrapper.classList.remove('mobile-open'); }
if (!isMobile() && localStorage.getItem('sidebarToggled') === 'true') { wrapper.classList.add('toggled'); }
document.getElementById('menu-toggle')?.addEventListener('click', () => {
    if (isMobile()) { wrapper.classList.toggle('mobile-open'); }
    else { wrapper.classList.toggle('toggled'); localStorage.setItem('sidebarToggled', wrapper.classList.contains('toggled')); }
});
window.addEventListener('resize', () => { if (!isMobile()) wrapper.classList.remove('mobile-open'); });

/* ── Confirm modal ── */
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

/* ── Auto-close alerts ── */
document.querySelectorAll('.alert').forEach(a => setTimeout(() => { try { bootstrap.Alert.getOrCreateInstance(a).close(); } catch(e){} }, 5000));
</script>
</body>
</html>
