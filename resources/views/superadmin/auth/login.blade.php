<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin — Connexion | Nafalo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #0a0f1e 0%, #0f1f3d 60%, #112244 100%);
        }

        /* Panneau gauche déco */
        .login-left {
            flex: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 3rem;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 40%, rgba(37,99,235,0.25), transparent);
        }
        .left-content { position: relative; z-index: 1; text-align: center; max-width: 380px; }
        .left-logo { height: 80px; width: auto; margin-bottom: 2rem; filter: brightness(0) invert(1); }
        .left-title { font-size: 2rem; font-weight: 900; color: white; line-height: 1.2; margin-bottom: 1rem; letter-spacing: -1px; }
        .left-title span { color: #60a5fa; }
        .left-desc { color: #64748b; font-size: 0.9rem; line-height: 1.7; margin-bottom: 2.5rem; }
        .left-stats { display: flex; gap: 2rem; justify-content: center; }
        .left-stat { text-align: center; }
        .left-stat strong { display: block; font-size: 1.4rem; font-weight: 900; color: #60a5fa; }
        .left-stat span { font-size: 0.75rem; color: #475569; font-weight: 500; }

        @media(max-width: 768px) { .login-left { display: none; } }

        /* Panneau droit — formulaire */
        .login-right {
            width: 440px; flex-shrink: 0;
            background: white;
            display: flex; align-items: center; justify-content: center;
            padding: 3rem 2.5rem;
        }
        @media(max-width: 768px) { .login-right { width: 100%; } }

        .login-form-inner { width: 100%; max-width: 360px; }

        .login-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; color: #1d4ed8;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 5px 12px; border-radius: 20px;
            border: 1px solid #bfdbfe;
            margin-bottom: 1.5rem;
        }
        .login-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; }
        .login-sub { font-size: 0.875rem; color: #64748b; margin-bottom: 2rem; }

        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 0.45rem; }
        .field-wrap { position: relative; }
        .field-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }
        .field input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1.5px solid #e2e8f0; border-radius: 11px;
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            outline: none; transition: all 0.2s; background: #fafafa; color: #0f172a;
        }
        .field input:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .field input.is-invalid { border-color: #ef4444; background: #fff5f5; }
        .invalid-msg { color: #ef4444; font-size: 0.78rem; margin-top: 0.35rem; }

        .btn-login {
            width: 100%; padding: 0.9rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white; font-weight: 700; font-size: 0.95rem;
            border: none; border-radius: 12px; cursor: pointer;
            transition: all 0.2s; font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }

        .alert-box { padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }
        .alert-danger { background: #fef2f2; color: #991b1b; border-left: 3px solid #ef4444; }

        .back-link { display: block; text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: #94a3b8; text-decoration: none; transition: color 0.2s; }
        .back-link:hover { color: #2563eb; }

        .divider-line { height: 1px; background: #f1f5f9; margin: 1.5rem 0; }
        .security-note { display: flex; align-items: center; gap: 8px; background: #f8fafc; border-radius: 10px; padding: 0.75rem 1rem; color: #64748b; font-size: 0.78rem; }
        .security-note i { color: #2563eb; flex-shrink: 0; }
    </style>
</head>
<body>

{{-- Panneau gauche --}}
<div class="login-left">
    <div class="left-content">
        <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo" class="left-logo">
        <h1 class="left-title">Panneau de<br>contrôle <span>global</span></h1>
        <p class="left-desc">Gérez l'ensemble de la plateforme Nafalo : marchands, boutiques, transactions et revenus en temps réel.</p>
        <div class="left-stats">
            <div class="left-stat">
                <strong><i class="fas fa-users"></i></strong>
                <span>Marchands</span>
            </div>
            <div class="left-stat">
                <strong><i class="fas fa-store"></i></strong>
                <span>Boutiques</span>
            </div>
            <div class="left-stat">
                <strong><i class="fas fa-credit-card"></i></strong>
                <span>Transactions</span>
            </div>
        </div>
    </div>
</div>

{{-- Panneau droit --}}
<div class="login-right">
    <div class="login-form-inner">

        <div class="login-badge">
            <i class="fas fa-shield-alt"></i> Accès restreint
        </div>
        <div class="login-title">Super Administration</div>
        <div class="login-sub">Nafalo — Panneau de contrôle global</div>

        @if(session('error'))
            <div class="alert-box alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.login.post') }}">
            @csrf

            <div class="field">
                <label>Adresse email</label>
                <div class="field-wrap">
                    <i class="field-icon fas fa-envelope"></i>
                    <input type="email" name="email"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="superadmin@nafalo.com" required autofocus>
                </div>
                @error('email')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:1.5rem;">
                <label>Mot de passe</label>
                <div class="field-wrap">
                    <i class="field-icon fas fa-lock"></i>
                    <input type="password" name="password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="••••••••" required>
                </div>
                @error('password')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="divider-line"></div>

        <div class="security-note">
            <i class="fas fa-lock"></i>
            Connexion sécurisée — Accès réservé aux super administrateurs Nafalo
        </div>

        <a href="{{ route('admin.login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à la connexion marchands
        </a>
    </div>
</div>

</body>
</html>
