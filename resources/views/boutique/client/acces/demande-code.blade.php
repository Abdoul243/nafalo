@extends('layouts.boutique')

@section('title', 'Accès client')

@push('styles')
<style>
.auth-wrap {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1.25rem 5rem;
}
.auth-inner { width: 100%; max-width: 460px; }

.auth-logo {
    text-align: center;
    margin-bottom: 2rem;
}
.auth-logo-icon {
    width: 60px; height: 60px;
    background: rgba(124,58,237,0.15);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.6rem; color: var(--accent);
    border: 1px solid rgba(124,58,237,0.25);
}
.auth-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.75rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.35rem;
}
.auth-subtitle {
    font-size: 0.875rem; color: var(--text-3); line-height: 1.6;
}

.auth-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
}

.auth-label {
    display: block;
    font-size: 0.8rem; font-weight: 600;
    color: var(--text-2); margin-bottom: 0.4rem;
}
.auth-input {
    width: 100%;
    background: rgba(0,0,0,0.03);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 0.8rem 1rem;
    font-size: 0.9rem; color: var(--text-1);
    font-family: inherit; outline: none;
    transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
}
.auth-input::placeholder { color: var(--text-3); }
.auth-input:focus {
    border-color: var(--accent);
    background: rgba(124,58,237,0.06);
}
.auth-input.is-invalid {
    border-color: rgba(239,68,68,0.6);
}
.auth-error {
    font-size: 0.78rem; color: #dc2626;
    margin-top: 0.35rem; display: block;
}

.btn-auth {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 0.95rem;
    background: var(--accent); color: white;
    border: none; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.25s; margin-top: 1.25rem;
    position: relative; overflow: hidden;
}
.btn-auth::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
    pointer-events: none;
}
.btn-auth:hover {
    background: var(--accent-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(124,58,237,0.4);
}

.auth-divider {
    display: flex; align-items: center; gap: 0.75rem;
    margin: 1.5rem 0;
    font-size: 0.75rem; color: var(--text-3);
}
.auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* How it works */
.how-card {
    background: rgba(0,0,0,0.02);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-top: 1.5rem;
}
.how-title {
    font-size: 0.78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text-3); margin-bottom: 1rem;
    display: flex; align-items: center; gap: 7px;
}
.how-steps { list-style: none; padding: 0; margin: 0; }
.how-step {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 0.5rem 0;
}
.how-step-num {
    width: 22px; height: 22px;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.68rem; font-weight: 800; color: var(--accent);
    flex-shrink: 0; margin-top: 1px;
}
.how-step-text { font-size: 0.82rem; color: var(--text-2); line-height: 1.5; }

.privacy-note {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: 0.75rem; color: var(--text-3);
    margin-top: 1.25rem;
}
.privacy-note i { color: #22c55e; font-size: 0.7rem; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-inner">

        <div class="auth-logo">
            <div class="auth-logo-icon"><i class="fas fa-user-circle"></i></div>
            <h1 class="auth-title">Accéder à mes achats</h1>
            <p class="auth-subtitle">Saisissez votre email pour recevoir un code d'accès à vos achats.</p>
        </div>

        <div class="auth-card">
            <form action="{{ route('client.acces.envoyer-code') }}" method="POST">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="auth-label" for="auth-email">Adresse email *</label>
                    <input
                        type="email"
                        class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        id="auth-email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="votre@email.com"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <span class="auth-error"><i class="fas fa-exclamation-circle" style="font-size:0.7rem;margin-right:3px;"></i>{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer le code d'accès
                </button>
            </form>

            <div class="privacy-note">
                <i class="fas fa-lock"></i> Vos données sont confidentielles et sécurisées
            </div>
        </div>

        <div class="how-card">
            <div class="how-title"><i class="fas fa-info-circle" style="color:var(--accent);"></i> Comment ça marche ?</div>
            <ul class="how-steps">
                <li class="how-step">
                    <div class="how-step-num">1</div>
                    <div class="how-step-text">Saisissez l'email utilisé lors de vos achats</div>
                </li>
                <li class="how-step">
                    <div class="how-step-num">2</div>
                    <div class="how-step-text">Vous recevez un code à 6 chiffres par email</div>
                </li>
                <li class="how-step">
                    <div class="how-step-num">3</div>
                    <div class="how-step-text">Entrez le code pour accéder à tous vos achats</div>
                </li>
                <li class="how-step">
                    <div class="how-step-num">4</div>
                    <div class="how-step-text">Téléchargez vos produits à volonté</div>
                </li>
            </ul>
        </div>

    </div>
</div>
@endsection
