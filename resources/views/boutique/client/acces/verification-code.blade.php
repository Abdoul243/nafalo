@extends('layouts.boutique')

@section('title', 'Vérification du code')

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
.auth-email-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(124,58,237,0.1);
    border: 1px solid rgba(124,58,237,0.25);
    border-radius: 20px;
    padding: 0.25rem 0.75rem;
    font-size: 0.82rem; font-weight: 600;
    color: var(--accent); margin-top: 0.5rem;
}

.auth-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
}

/* OTP input */
.otp-label {
    display: block;
    font-size: 0.8rem; font-weight: 600;
    color: var(--text-2); margin-bottom: 0.5rem;
}
.otp-input {
    width: 100%;
    background: rgba(0,0,0,0.03);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 1rem 1rem;
    font-size: 2rem; font-weight: 800;
    color: var(--text-1);
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5rem;
    text-align: center;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
}
.otp-input::placeholder { color: var(--text-3); letter-spacing: 0.3rem; font-size: 1.2rem; font-weight: 400; }
.otp-input:focus {
    border-color: var(--accent);
    background: rgba(124,58,237,0.06);
    box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
}
.otp-input.is-invalid {
    border-color: rgba(239,68,68,0.6);
}
.auth-error {
    font-size: 0.78rem; color: #dc2626;
    margin-top: 0.45rem; display: block;
    text-align: center;
}

.otp-hint {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: 0.76rem; color: var(--text-3);
    margin-top: 0.5rem;
}
.otp-hint i { color: var(--accent); font-size: 0.7rem; }

.btn-auth {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 0.95rem;
    background: var(--accent); color: white;
    border: none; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.25s; margin-top: 1.5rem;
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

.link-action {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.82rem; color: var(--accent);
    background: none; border: none; cursor: pointer;
    font-family: inherit; padding: 0; text-decoration: none;
    transition: color 0.2s;
}
.link-action:hover { color: #7c3aed; text-decoration: underline; }

/* Alert messages */
.auth-alert-success {
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.25);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.83rem; color: #16a34a;
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 8px;
}
.auth-alert-error {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.25);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.83rem; color: #dc2626;
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 8px;
}

.validity-badge {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: 0.75rem; color: var(--text-3);
    margin-top: 1rem;
}
.validity-badge i { color: #f59e0b; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-inner">

        <div class="auth-logo">
            <div class="auth-logo-icon"><i class="fas fa-key"></i></div>
            <h1 class="auth-title">Vérification</h1>
            <p class="auth-subtitle">Code envoyé à</p>
            <div class="auth-email-chip"><i class="fas fa-envelope"></i> {{ $email }}</div>
        </div>

        <div class="auth-card">

            @if(session('success'))
            <div class="auth-alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="auth-alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('client.acces.verifier') }}" method="POST">
                @csrf

                <label class="otp-label" for="code-input">Code de vérification à 6 chiffres</label>
                <input
                    type="text"
                    class="otp-input {{ $errors->has('code') ? 'is-invalid' : '' }}"
                    id="code-input"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    autofocus
                    required
                >
                @error('code')
                    <span class="auth-error"><i class="fas fa-exclamation-circle" style="font-size:0.7rem;"></i> {{ $message }}</span>
                @enderror

                <div class="otp-hint">
                    <i class="fas fa-clock"></i> Valable 15 minutes
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-check"></i>
                    Vérifier et accéder
                </button>
            </form>

            <div class="auth-divider">ou</div>

            <div style="display:flex;flex-direction:column;align-items:center;gap:0.625rem;">
                <form action="{{ route('client.acces.envoyer-code') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="link-action">
                        <i class="fas fa-redo" style="font-size:0.7rem;"></i> Renvoyer un nouveau code
                    </button>
                </form>
                <a href="{{ route('client.acces.demande') }}" class="link-action" style="color:var(--text-3);">
                    <i class="fas fa-arrow-left" style="font-size:0.7rem;"></i> Utiliser un autre email
                </a>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const codeInput = document.getElementById('code-input');
if (codeInput) {
    codeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
    // Auto-submit when 6 digits entered
    codeInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            this.closest('form').submit();
        }
    });
}
</script>
@endpush
