<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié — Nafalo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif;
            min-height:100vh; display:flex;
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 50%, #eff6ff 100%);
        }

        /* Panneau gauche décoratif */
        .left {
            width: 45%; background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 50%, #2563eb 100%);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding: 3rem; position:relative; overflow:hidden;
        }
        .left::before {
            content:''; position:absolute; inset:0;
            background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(37,99,235,0.4), transparent);
        }
        .left-content { position:relative; z-index:1; text-align:center; }
        .left-logo img { height:100px; width:auto; mix-blend-mode:lighten; margin-bottom:2rem; }
        .left-title { font-size:1.8rem; font-weight:800; color:white; line-height:1.2; margin-bottom:1rem; }
        .left-sub { color:rgba(255,255,255,0.6); font-size:0.95rem; line-height:1.7; max-width:320px; }
        .left-steps { margin-top:2.5rem; text-align:left; }
        .step { display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.25rem; }
        .step-num {
            width:32px; height:32px; border-radius:50%; flex-shrink:0;
            background: rgba(37,99,235,0.3); border:1px solid rgba(37,99,235,0.5);
            display:flex; align-items:center; justify-content:center;
            font-size:0.8rem; font-weight:700; color:#93c5fd;
        }
        .step-text { color:rgba(255,255,255,0.7); font-size:0.875rem; line-height:1.5; padding-top:6px; }

        /* Panneau droit formulaire */
        .right {
            flex:1; display:flex; align-items:center; justify-content:center;
            padding: 2rem;
        }
        .form-box { width:100%; max-width:420px; }
        .form-logo { text-align:center; margin-bottom:2rem; }
        .form-logo img { height:80px; width:auto; }
        .form-title { font-size:1.5rem; font-weight:800; color:#0f172a; margin-bottom:0.4rem; }
        .form-sub { color:#64748b; font-size:0.9rem; margin-bottom:2rem; line-height:1.6; }

        .field { margin-bottom:1.25rem; }
        .field label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.9rem; }
        .field input {
            width:100%; padding:0.85rem 1rem 0.85rem 2.75rem;
            border:1.5px solid #e2e8f0; border-radius:12px;
            font-size:0.9rem; color:#0f172a; outline:none;
            font-family:'Inter',sans-serif; transition:all 0.2s;
        }
        .field input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }

        .btn-submit {
            width:100%; padding:0.9rem; border-radius:12px;
            background:linear-gradient(135deg,#2563eb,#1d4ed8);
            color:white; font-weight:700; font-size:0.95rem;
            border:none; cursor:pointer; transition:all 0.25s;
            font-family:'Inter',sans-serif; margin-bottom:1.25rem;
        }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(37,99,235,0.35); }

        .back-link {
            display:flex; align-items:center; justify-content:center; gap:8px;
            color:#64748b; text-decoration:none; font-size:0.875rem; font-weight:500;
            transition:color 0.15s;
        }
        .back-link:hover { color:#2563eb; }

        .alert-success {
            background:#f0fdf4; border-left:4px solid #22c55e;
            color:#166534; padding:0.875rem 1rem;
            border-radius:10px; font-size:0.875rem; margin-bottom:1.25rem;
            display:flex; align-items:flex-start; gap:10px;
        }
        .alert-danger {
            background:#fef2f2; border-left:4px solid #ef4444;
            color:#991b1b; padding:0.875rem 1rem;
            border-radius:10px; font-size:0.875rem; margin-bottom:1.25rem;
        }

        @media(max-width:768px) { .left { display:none; } .right { padding:1.5rem; } }
    </style>
</head>
<body>

<div class="left">
    <div class="left-content">
        <div class="left-logo">
            <img src="/images/nafalo-logo.png" alt="Nafalo">
        </div>
        <h2 class="left-title">Récupérez l'accès<br>à votre compte</h2>
        <p class="left-sub">Suivez les étapes simples pour réinitialiser votre mot de passe et retrouver accès à votre boutique.</p>
        <div class="left-steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text">Entrez votre adresse email associée à votre compte Nafalo</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">Recevez un lien sécurisé dans votre boîte mail</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text">Créez un nouveau mot de passe et reconnectez-vous</div>
            </div>
        </div>
    </div>
</div>

<div class="right">
    <div class="form-box">
        <div class="form-logo">
            <img src="/images/nafalo-logo.png" alt="Nafalo">
        </div>
        <h1 class="form-title">Mot de passe oublié ?</h1>
        <p class="form-sub">Pas de panique. Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

        @if(session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle" style="color:#22c55e;margin-top:1px;"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Adresse email</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="vous@exemple.com" required autofocus>
                </div>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane me-2"></i> Envoyer le lien de réinitialisation
            </button>
        </form>

        <a href="{{ route('admin.login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à la connexion
        </a>
    </div>
</div>

</body>
</html>
