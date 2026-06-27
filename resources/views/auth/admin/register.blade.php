<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer ma boutique — Nafalo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nafalo-logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Inter',sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            padding:2rem 1rem;
            position:relative; overflow-x:hidden;
        }
        /* Animated background */
        body::before {
            content:'';
            position:fixed; inset:0; z-index:0;
            background:radial-gradient(ellipse 80% 60% at 50% -10%, rgba(37,99,235,0.25) 0%, transparent 60%),
                        radial-gradient(ellipse 50% 40% at 90% 90%, rgba(139,92,246,0.15) 0%, transparent 50%);
            pointer-events:none;
        }

        /* ── LAYOUT ── */
        .page-wrapper {
            position:relative; z-index:1;
            width:100%; max-width:680px;
        }

        /* Logo top */
        .top-logo {
            text-align:center; margin-bottom:2rem;
        }
        .top-logo img { height:70px; width:auto; }
        .top-logo .tagline { color:rgba(255,255,255,0.5); font-size:0.82rem; margin-top:0.4rem; }

        /* Already have account */
        .already-account {
            text-align:center; margin-bottom:1.5rem;
            font-size:0.85rem; color:rgba(255,255,255,0.5);
        }
        .already-account a { color:#60a5fa; font-weight:600; text-decoration:none; }
        .already-account a:hover { color:#93c5fd; }

        /* ── CARD ── */
        .wizard-card {
            background:white;
            border-radius:28px;
            box-shadow:0 40px 80px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.05);
            overflow:hidden;
        }

        /* ── STEP PROGRESS ── */
        .wizard-header {
            padding:2rem 2.5rem 0;
            background:white;
        }
        .steps-bar {
            display:flex; align-items:center; gap:0;
            margin-bottom:2rem;
        }
        .step-item {
            display:flex; flex-direction:column; align-items:center;
            flex:1; position:relative; cursor:default;
        }
        .step-item:not(:last-child)::after {
            content:'';
            position:absolute; top:18px; left:calc(50% + 18px);
            width:calc(100% - 36px); height:2px;
            background:#e2e8f0;
            transition:background 0.4s;
        }
        .step-item.done:not(:last-child)::after,
        .step-item.active:not(:last-child)::after { background:#2563eb; }

        .step-circle {
            width:36px; height:36px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:0.85rem; font-weight:700;
            border:2px solid #e2e8f0;
            background:white; color:#94a3b8;
            transition:all 0.3s; z-index:1;
        }
        .step-item.active .step-circle {
            background:#2563eb; border-color:#2563eb; color:white;
            box-shadow:0 0 0 4px rgba(37,99,235,0.15);
        }
        .step-item.done .step-circle {
            background:#22c55e; border-color:#22c55e; color:white;
        }
        .step-label {
            font-size:0.72rem; font-weight:600; color:#94a3b8;
            margin-top:0.4rem; text-align:center; white-space:nowrap;
            transition:color 0.3s;
        }
        .step-item.active .step-label { color:#2563eb; }
        .step-item.done  .step-label { color:#22c55e; }

        /* ── FORM BODY ── */
        .wizard-body { padding:1.75rem 2.5rem 2rem; }

        .step-panel { display:none; animation:fadeSlideIn 0.35s ease; }
        .step-panel.active { display:block; }
        @keyframes fadeSlideIn {
            from { opacity:0; transform:translateX(20px); }
            to   { opacity:1; transform:translateX(0); }
        }

        .panel-title {
            font-size:1.35rem; font-weight:800; color:#0f172a;
            margin-bottom:0.3rem;
        }
        .panel-sub { font-size:0.875rem; color:#64748b; margin-bottom:1.75rem; line-height:1.6; }

        /* ── FIELDS ── */
        .field { margin-bottom:1.1rem; }
        .field label {
            display:block; font-size:0.82rem; font-weight:600;
            color:#374151; margin-bottom:0.4rem;
        }
        .field label .opt { font-weight:400; color:#94a3b8; font-size:0.78rem; }
        .field input,
        .field textarea {
            width:100%; padding:0.8rem 1rem;
            border-radius:12px; border:1.5px solid #e2e8f0;
            font-size:0.9rem; font-family:'Inter',sans-serif;
            outline:none; transition:all 0.2s; color:#0f172a;
            background:white;
        }
        .field input:focus,
        .field textarea:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .field input.error { border-color:#ef4444; }
        .field input.success { border-color:#22c55e; }
        .field textarea { resize:vertical; min-height:90px; }
        .field .hint { font-size:0.75rem; color:#94a3b8; margin-top:0.3rem; }
        .field .char-counter { font-size:0.75rem; color:#94a3b8; text-align:right; margin-top:0.25rem; }

        .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:520px) { .row-2 { grid-template-columns:1fr; } }

        /* Password strength */
        .pwd-wrapper { position:relative; }
        .pwd-toggle {
            position:absolute; right:0.9rem; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer;
            color:#94a3b8; font-size:0.9rem;
        }
        .pwd-toggle:hover { color:#374151; }
        .pwd-strength { margin-top:0.4rem; }
        .pwd-bar { height:4px; border-radius:2px; background:#f1f5f9; overflow:hidden; }
        .pwd-bar-fill { height:100%; width:0; border-radius:2px; transition:all 0.4s; }
        .pwd-text { font-size:0.72rem; margin-top:0.25rem; color:#94a3b8; }

        /* Domain field */
        .domain-field-wrap { position:relative; }
        .domain-field-wrap input { padding-right:2.5rem; }
        .domain-check-icon {
            position:absolute; right:0.85rem; top:50%; transform:translateY(-50%);
            font-size:0.9rem;
        }
        .domain-status {
            margin-top:0.35rem; font-size:0.78rem; font-weight:600;
            display:flex; align-items:center; gap:5px;
        }
        .domain-status.available { color:#16a34a; }
        .domain-status.taken     { color:#dc2626; }
        .domain-status.checking  { color:#2563eb; }
        .domain-preview-text {
            font-size:0.75rem; color:#94a3b8; margin-top:0.2rem;
        }

        /* ── LOGO UPLOAD ── */
        .logo-upload-zone {
            border:2px dashed #e2e8f0; border-radius:16px;
            padding:2rem; text-align:center; cursor:pointer;
            transition:all 0.25s; position:relative;
            background:#f8fafc;
        }
        .logo-upload-zone:hover, .logo-upload-zone.dragover {
            border-color:#2563eb; background:#eff6ff;
        }
        .logo-upload-zone input[type=file] {
            position:absolute; inset:0; opacity:0; cursor:pointer;
            width:100%; height:100%; border:none; padding:0;
        }
        .logo-upload-icon { font-size:2rem; color:#cbd5e1; margin-bottom:0.75rem; }
        .logo-upload-text { font-size:0.875rem; color:#64748b; }
        .logo-upload-text strong { color:#2563eb; }
        .logo-upload-hint { font-size:0.75rem; color:#94a3b8; margin-top:0.3rem; }
        /* Preview */
        .logo-preview-wrap { display:none; flex-direction:column; align-items:center; gap:0.75rem; }
        .logo-preview-wrap.show { display:flex; }
        .logo-preview-img {
            width:80px; height:80px; border-radius:16px;
            object-fit:cover; border:2px solid #e2e8f0;
        }
        .logo-preview-name { font-size:0.8rem; color:#374151; font-weight:600; }
        .logo-remove-btn {
            font-size:0.75rem; color:#ef4444; background:none; border:none;
            cursor:pointer; font-weight:600;
        }
        .logo-remove-btn:hover { text-decoration:underline; }

        /* ── CONFIRMATION STEP ── */
        .confirm-section {
            background:#f8fafc; border-radius:16px;
            padding:1.25rem 1.5rem; margin-bottom:1rem;
        }
        .confirm-section h4 {
            font-size:0.78rem; font-weight:700; color:#94a3b8;
            text-transform:uppercase; letter-spacing:0.05em;
            margin-bottom:1rem;
        }
        .confirm-row {
            display:flex; align-items:flex-start;
            justify-content:space-between; gap:1rem;
            padding:0.5rem 0;
            border-bottom:1px solid #f1f5f9;
            font-size:0.875rem;
        }
        .confirm-row:last-child { border-bottom:none; }
        .confirm-row .label { color:#64748b; font-weight:500; flex-shrink:0; }
        .confirm-row .value { color:#0f172a; font-weight:600; text-align:right; word-break:break-all; }
        .confirm-logo-preview {
            width:48px; height:48px; border-radius:10px;
            object-fit:cover; border:1.5px solid #e2e8f0;
        }
        .confirm-check-row {
            display:flex; align-items:flex-start; gap:10px;
            font-size:0.82rem; color:#374151; margin-bottom:1rem;
        }
        .confirm-check-row input[type=checkbox] { margin-top:2px; width:16px; height:16px; cursor:pointer; accent-color:#2563eb; }
        .confirm-check-row a { color:#2563eb; }

        /* ── NAVIGATION BUTTONS ── */
        .wizard-nav {
            display:flex; justify-content:space-between; align-items:center;
            margin-top:1.75rem; gap:1rem;
        }
        .btn-back {
            display:flex; align-items:center; gap:6px;
            padding:0.75rem 1.5rem; border-radius:12px;
            border:1.5px solid #e2e8f0; background:white;
            color:#374151; font-weight:600; font-size:0.9rem;
            cursor:pointer; transition:all 0.2s; font-family:'Inter',sans-serif;
        }
        .btn-back:hover { border-color:#94a3b8; background:#f8fafc; }
        .btn-next {
            flex:1; display:flex; align-items:center; justify-content:center; gap:8px;
            padding:0.875rem 1.75rem; border-radius:12px;
            background:#2563eb; color:white;
            font-weight:700; font-size:0.95rem;
            border:none; cursor:pointer; transition:all 0.25s; font-family:'Inter',sans-serif;
        }
        .btn-next:hover { background:#1d4ed8; transform:translateY(-1px); box-shadow:0 8px 24px rgba(37,99,235,0.3); }
        .btn-next:disabled { background:#94a3b8; cursor:not-allowed; transform:none; box-shadow:none; }
        .btn-submit-final {
            flex:1; display:flex; align-items:center; justify-content:center; gap:8px;
            padding:0.875rem 1.75rem; border-radius:12px;
            background:linear-gradient(135deg,#2563eb,#7c3aed);
            color:white; font-weight:700; font-size:0.95rem;
            border:none; cursor:pointer; transition:all 0.25s; font-family:'Inter',sans-serif;
        }
        .btn-submit-final:hover { opacity:0.92; transform:translateY(-1px); box-shadow:0 8px 24px rgba(37,99,235,0.35); }
        .btn-submit-final:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

        /* Error messages */
        .alert-errors {
            background:#fef2f2; color:#991b1b; border-left:3px solid #ef4444;
            border-radius:10px; padding:0.85rem 1rem;
            font-size:0.85rem; margin-bottom:1.25rem;
        }
        .alert-errors ul { margin:0.35rem 0 0 1rem; }
        .field-error { font-size:0.78rem; color:#ef4444; margin-top:0.3rem; font-weight:500; }

        /* Spinner */
        @keyframes spin { to { transform:rotate(360deg); } }
        .spinner { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,0.3); border-top-color:white; border-radius:50%; animation:spin 0.7s linear infinite; }

        /* ── RESPONSIVE ── */
        @media(max-width:640px) {
            .wizard-header { padding:1.5rem 1.5rem 0; }
            .wizard-body { padding:1.5rem 1.5rem 1.5rem; }
            .steps-bar { gap:0; }
            .step-label { display:none; }
            .step-circle { width:32px; height:32px; font-size:0.8rem; }
            .step-item:not(:last-child)::after { top:16px; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    {{-- Logo --}}
    <div class="top-logo">
        <a href="{{ route('admin.login') }}">
            <img src="{{ asset('images/nafalo-logo.png') }}" alt="Nafalo">
        </a>
        <div class="tagline">Créez votre boutique digitale en quelques étapes</div>
    </div>

    <div class="already-account">
        Déjà un compte ? <a href="{{ route('admin.login') }}">Se connecter</a>
    </div>

    <div class="wizard-card">

        {{-- Step indicators --}}
        <div class="wizard-header">
            <div class="steps-bar" id="stepsBar">
                <div class="step-item active" id="si-1">
                    <div class="step-circle" id="sc-1">1</div>
                    <div class="step-label">Ton compte</div>
                </div>
                <div class="step-item" id="si-2">
                    <div class="step-circle" id="sc-2">2</div>
                    <div class="step-label">Ta boutique</div>
                </div>
                <div class="step-item" id="si-3">
                    <div class="step-circle" id="sc-3">3</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data" id="wizardForm" novalidate>
            @csrf

            <div class="wizard-body">

                {{-- Server-side errors --}}
                @if($errors->any())
                <div class="alert-errors">
                    <strong><i class="fas fa-exclamation-circle"></i> Veuillez corriger les erreurs :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- ══ ÉTAPE 1 — Compte ══ --}}
                <div class="step-panel active" id="panel-1">
                    <h2 class="panel-title">Crée ton compte</h2>
                    <p class="panel-sub">Ces informations seront utilisées pour accéder à ton tableau de bord Nafalo.</p>

                    <div class="field">
                        <label>Nom complet</label>
                        <input type="text" name="nom" id="inp-nom"
                               value="{{ old('nom') }}"
                               placeholder="Ex : Aminata Koné"
                               autocomplete="name"
                               required>
                        <div class="field-error" id="err-nom"></div>
                    </div>

                    <div class="field">
                        <label>Adresse email</label>
                        <input type="email" name="email" id="inp-email"
                               value="{{ old('email') }}"
                               placeholder="vous@exemple.com"
                               autocomplete="email"
                               required>
                        <div class="field-error" id="err-email"></div>
                    </div>

                    <div class="field">
                        <label>Mot de passe</label>
                        <div class="pwd-wrapper">
                            <input type="password" name="password" id="inp-password"
                                   placeholder="Minimum 8 caractères"
                                   autocomplete="new-password"
                                   required
                                   style="padding-right:2.5rem;">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('inp-password','this')" data-target="inp-password">
                                <i class="fas fa-eye" id="icon-password"></i>
                            </button>
                        </div>
                        <div class="pwd-strength" id="pwd-strength" style="display:none;">
                            <div class="pwd-bar"><div class="pwd-bar-fill" id="pwd-bar-fill"></div></div>
                            <div class="pwd-text" id="pwd-text"></div>
                        </div>
                        <div class="field-error" id="err-password"></div>
                    </div>

                    <div class="field">
                        <label>Confirmer le mot de passe</label>
                        <div class="pwd-wrapper">
                            <input type="password" name="password_confirmation" id="inp-password_confirmation"
                                   placeholder="Répétez le mot de passe"
                                   autocomplete="new-password"
                                   required
                                   style="padding-right:2.5rem;">
                            <button type="button" class="pwd-toggle" data-target="inp-password_confirmation">
                                <i class="fas fa-eye" id="icon-password_confirmation"></i>
                            </button>
                        </div>
                        <div class="field-error" id="err-confirm"></div>
                    </div>

                    <div class="wizard-nav">
                        <div></div>
                        <button type="button" class="btn-next" onclick="goStep(2)">
                            Continuer <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ══ ÉTAPE 2 — Boutique ══ --}}
                <div class="step-panel" id="panel-2">
                    <h2 class="panel-title">Configure ta boutique</h2>
                    <p class="panel-sub">Personalise ta boutique. Tu pourras tout modifier plus tard depuis ton tableau de bord.</p>

                    <div class="field">
                        <label>Nom de ta boutique</label>
                        <input type="text" name="nom_boutique" id="inp-nom_boutique"
                               value="{{ old('nom_boutique') }}"
                               placeholder="Ex : Ma Boutique Digitale"
                               maxlength="100"
                               required>
                        <div class="char-counter field"><span id="cc-boutique">0</span>/100</div>
                        <div class="field-error" id="err-boutique"></div>
                    </div>

                    <div class="field">
                        <label>Description <span class="opt">(optionnel)</span></label>
                        <textarea name="description" id="inp-description"
                                  placeholder="Décris ta boutique en quelques mots…"
                                  maxlength="300">{{ old('description') }}</textarea>
                        <div class="char-counter"><span id="cc-desc">0</span>/300</div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Téléphone <span class="opt">(optionnel)</span></label>
                            <input type="tel" name="telephone" id="inp-telephone"
                                   value="{{ old('telephone') }}"
                                   placeholder="+225 07 00 00 00 00">
                        </div>
                        <div class="field">
                            <label>Domaine personnalisé <span class="opt">(optionnel)</span></label>
                            <div class="domain-field-wrap">
                                <input type="text" name="domaine_personnalise" id="inp-domaine"
                                       value="{{ old('domaine_personnalise') }}"
                                       placeholder="ma-boutique"
                                       autocomplete="off">
                                <span class="domain-check-icon" id="domain-icon"></span>
                            </div>
                            <div class="domain-status" id="domain-status"></div>
                            <div class="domain-preview-text" id="domain-preview"></div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Logo de ta boutique <span class="opt">(optionnel)</span></label>
                        <div class="logo-upload-zone" id="logoZone"
                             ondragover="handleDragOver(event)"
                             ondragleave="handleDragLeave(event)"
                             ondrop="handleDrop(event)">
                            <input type="file" name="logo" id="logoInput"
                                   accept="image/*"
                                   onchange="handleLogoChange(event)">
                            <div id="logoPlaceholder">
                                <div class="logo-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="logo-upload-text">
                                    <strong>Clique pour choisir</strong> ou glisse-dépose ton logo
                                </div>
                                <div class="logo-upload-hint">PNG, JPG, WEBP — max 2 Mo</div>
                            </div>
                            <div class="logo-preview-wrap" id="logoPreviewWrap">
                                <img src="" alt="Logo preview" class="logo-preview-img" id="logoPreviewImg">
                                <span class="logo-preview-name" id="logoPreviewName"></span>
                                <button type="button" class="logo-remove-btn" onclick="removeLogo()">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-nav">
                        <button type="button" class="btn-back" onclick="goStep(1)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="button" class="btn-next" onclick="goStep(3)">
                            Vérifier <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ══ ÉTAPE 3 — Confirmation ══ --}}
                <div class="step-panel" id="panel-3">
                    <h2 class="panel-title">Tout est prêt ! 🎉</h2>
                    <p class="panel-sub">Vérifie tes informations avant de créer ta boutique.</p>

                    {{-- Recap compte --}}
                    <div class="confirm-section">
                        <h4><i class="fas fa-user"></i> Ton compte</h4>
                        <div class="confirm-row">
                            <span class="label">Nom</span>
                            <span class="value" id="recap-nom">—</span>
                        </div>
                        <div class="confirm-row">
                            <span class="label">Email</span>
                            <span class="value" id="recap-email">—</span>
                        </div>
                    </div>

                    {{-- Recap boutique --}}
                    <div class="confirm-section">
                        <h4><i class="fas fa-store"></i> Ta boutique</h4>
                        <div class="confirm-row">
                            <span class="label">Nom</span>
                            <span class="value" id="recap-boutique">—</span>
                        </div>
                        <div class="confirm-row" id="recap-row-desc" style="display:none;">
                            <span class="label">Description</span>
                            <span class="value" id="recap-desc">—</span>
                        </div>
                        <div class="confirm-row" id="recap-row-tel" style="display:none;">
                            <span class="label">Téléphone</span>
                            <span class="value" id="recap-tel">—</span>
                        </div>
                        <div class="confirm-row" id="recap-row-domaine" style="display:none;">
                            <span class="label">Domaine</span>
                            <span class="value" id="recap-domaine">—</span>
                        </div>
                        <div class="confirm-row" id="recap-row-logo" style="display:none;">
                            <span class="label">Logo</span>
                            <img src="" alt="" class="confirm-logo-preview" id="recap-logo">
                        </div>
                    </div>

                    {{-- Checkbox CGU --}}
                    <label class="confirm-check-row">
                        <input type="checkbox" id="chk-cgu" required onchange="toggleSubmit()">
                        <span>J'accepte les <a href="{{ route('legal.conditions') }}" target="_blank">Conditions d'utilisation</a> et la <a href="{{ route('legal.confidentialite') }}" target="_blank">Politique de confidentialité</a> de Nafalo.</span>
                    </label>

                    <div class="wizard-nav">
                        <button type="button" class="btn-back" onclick="goStep(2)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="submit" class="btn-submit-final" id="btnSubmit" disabled>
                            <i class="fas fa-rocket"></i> Créer ma boutique
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>

<script>
// ── State ──────────────────────────────────────────────────────────────────
let currentStep = 1;
let domainOk = true; // empty domain = OK
let domainTimer = null;

// On load: if server errors, show step 1 or 2
document.addEventListener('DOMContentLoaded', () => {
    @if($errors->has('nom') || $errors->has('email') || $errors->has('password'))
        activateStep(1);
    @elseif($errors->has('nom_boutique') || $errors->has('domaine_personnalise'))
        activateStep(2);
    @else
        activateStep(1);
    @endif

    // Init char counters
    updateCharCounter('inp-nom_boutique', 'cc-boutique');
    updateCharCounter('inp-description', 'cc-desc');

    // Domain check on existing value
    const domInput = document.getElementById('inp-domaine');
    if (domInput.value) checkDomain(domInput.value);

    // Password strength on existing
    const pwdInput = document.getElementById('inp-password');
    if (pwdInput.value) updatePwdStrength(pwdInput.value);
});

// ── Step navigation ────────────────────────────────────────────────────────
function activateStep(step) {
    currentStep = step;
    for (let i = 1; i <= 3; i++) {
        const panel = document.getElementById('panel-' + i);
        const item  = document.getElementById('si-' + i);
        const circ  = document.getElementById('sc-' + i);
        panel.classList.toggle('active', i === step);
        item.classList.remove('active', 'done');
        if (i < step) {
            item.classList.add('done');
            circ.innerHTML = '<i class="fas fa-check"></i>';
        } else if (i === step) {
            item.classList.add('active');
            circ.textContent = i;
        } else {
            circ.textContent = i;
        }
    }
}

function goStep(target) {
    if (target > currentStep) {
        if (!validateStep(currentStep)) return;
    }
    if (target === 3) fillRecap();
    activateStep(target);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Validation ─────────────────────────────────────────────────────────────
function validateStep(step) {
    if (step === 1) return validateStep1();
    if (step === 2) return validateStep2();
    return true;
}

function validateStep1() {
    let ok = true;

    const nom = document.getElementById('inp-nom').value.trim();
    if (!nom) { showErr('err-nom', 'Le nom est obligatoire.'); ok = false; }
    else clearErr('err-nom');

    const email = document.getElementById('inp-email').value.trim();
    if (!email) { showErr('err-email', 'L\'email est obligatoire.'); ok = false; }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showErr('err-email', 'Email invalide.'); ok = false; }
    else clearErr('err-email');

    const pwd = document.getElementById('inp-password').value;
    if (!pwd) { showErr('err-password', 'Le mot de passe est obligatoire.'); ok = false; }
    else if (pwd.length < 8) { showErr('err-password', 'Minimum 8 caractères.'); ok = false; }
    else clearErr('err-password');

    const conf = document.getElementById('inp-password_confirmation').value;
    if (conf !== pwd) { showErr('err-confirm', 'Les mots de passe ne correspondent pas.'); ok = false; }
    else clearErr('err-confirm');

    return ok;
}

function validateStep2() {
    let ok = true;

    const nom = document.getElementById('inp-nom_boutique').value.trim();
    if (!nom) { showErr('err-boutique', 'Le nom de la boutique est obligatoire.'); ok = false; }
    else clearErr('err-boutique');

    if (!domainOk) {
        ok = false;
        const statusEl = document.getElementById('domain-status');
        if (!statusEl.textContent) {
            statusEl.textContent = 'Ce domaine est indisponible.';
            statusEl.className = 'domain-status taken';
        }
    }

    return ok;
}

// ── Errors helpers ─────────────────────────────────────────────────────────
function showErr(id, msg) {
    const el = document.getElementById(id);
    if (el) el.textContent = msg;
}
function clearErr(id) {
    const el = document.getElementById(id);
    if (el) el.textContent = '';
}

// ── Password ───────────────────────────────────────────────────────────────
document.getElementById('inp-password').addEventListener('input', function() {
    updatePwdStrength(this.value);
});

function updatePwdStrength(pwd) {
    const bar     = document.getElementById('pwd-bar-fill');
    const text    = document.getElementById('pwd-text');
    const wrapper = document.getElementById('pwd-strength');

    if (!pwd) { wrapper.style.display = 'none'; return; }
    wrapper.style.display = 'block';

    let score = 0;
    if (pwd.length >= 8)  score++;
    if (pwd.length >= 12) score++;
    if (/[A-Z]/.test(pwd)) score++;
    if (/[0-9]/.test(pwd)) score++;
    if (/[^A-Za-z0-9]/.test(pwd)) score++;

    const levels = [
        { pct:'20%', color:'#ef4444', label:'Très faible' },
        { pct:'40%', color:'#f97316', label:'Faible' },
        { pct:'60%', color:'#eab308', label:'Moyen' },
        { pct:'80%', color:'#22c55e', label:'Fort' },
        { pct:'100%',color:'#16a34a', label:'Très fort' },
    ];
    const lv = levels[Math.min(score - 1, 4)] || levels[0];
    bar.style.width = lv.pct;
    bar.style.background = lv.color;
    text.textContent = lv.label;
    text.style.color = lv.color;
}

// Toggle password visibility
document.querySelectorAll('.pwd-toggle').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    });
});

// ── Character counters ─────────────────────────────────────────────────────
function updateCharCounter(inputId, counterId) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    if (input && counter) counter.textContent = input.value.length;
}
document.getElementById('inp-nom_boutique').addEventListener('input', function() {
    updateCharCounter('inp-nom_boutique', 'cc-boutique');
});
document.getElementById('inp-description').addEventListener('input', function() {
    updateCharCounter('inp-description', 'cc-desc');
});

// ── Domain check ───────────────────────────────────────────────────────────
document.getElementById('inp-domaine').addEventListener('input', function() {
    const val = this.value.trim();
    clearTimeout(domainTimer);
    if (!val) {
        domainOk = true;
        document.getElementById('domain-status').textContent  = '';
        document.getElementById('domain-preview').textContent = '';
        document.getElementById('domain-icon').textContent    = '';
        return;
    }
    setDomainStatus('checking', '<i class="fas fa-circle-notch fa-spin"></i> Vérification…');
    domainTimer = setTimeout(() => checkDomain(val), 600);
});

function checkDomain(val) {
    const url = '{{ route("admin.boutiques.check-domain") }}?domaine=' + encodeURIComponent(val);
    fetch(url, { headers:{ 'X-Requested-With':'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            domainOk = data.available;
            if (data.available) {
                setDomainStatus('available', '<i class="fas fa-check-circle"></i> Disponible !');
            } else {
                setDomainStatus('taken', '<i class="fas fa-times-circle"></i> ' + data.message);
            }
            const preview = document.getElementById('domain-preview');
            if (data.domaine) {
                preview.textContent = 'Sera accessible : ' + data.domaine + '.nafalo.test';
            }
        })
        .catch(() => {
            domainOk = true;
            setDomainStatus('', '');
        });
}

function setDomainStatus(cls, html) {
    const el = document.getElementById('domain-status');
    el.className = 'domain-status' + (cls ? ' ' + cls : '');
    el.innerHTML = html;
}

// ── Logo upload ────────────────────────────────────────────────────────────
function handleLogoChange(e) {
    const file = e.target.files[0];
    if (file) showLogoPreview(file);
}
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('logoZone').classList.add('dragover');
}
function handleDragLeave() {
    document.getElementById('logoZone').classList.remove('dragover');
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('logoZone').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const input = document.getElementById('logoInput');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showLogoPreview(file);
    }
}
function showLogoPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logoPreviewImg').src  = e.target.result;
        document.getElementById('logoPreviewName').textContent = file.name;
        document.getElementById('logoPlaceholder').style.display = 'none';
        document.getElementById('logoPreviewWrap').classList.add('show');
        document.getElementById('logoZone').style.border = '2px solid #22c55e';
    };
    reader.readAsDataURL(file);
}
function removeLogo() {
    document.getElementById('logoInput').value = '';
    document.getElementById('logoPreviewImg').src  = '';
    document.getElementById('logoPreviewName').textContent = '';
    document.getElementById('logoPlaceholder').style.display = '';
    document.getElementById('logoPreviewWrap').classList.remove('show');
    document.getElementById('logoZone').style.border = '';
}

// ── Recap / Confirmation ───────────────────────────────────────────────────
function fillRecap() {
    setText('recap-nom',      document.getElementById('inp-nom').value.trim());
    setText('recap-email',    document.getElementById('inp-email').value.trim());
    setText('recap-boutique', document.getElementById('inp-nom_boutique').value.trim());

    const desc = document.getElementById('inp-description').value.trim();
    toggleRecapRow('recap-row-desc', 'recap-desc', desc);

    const tel = document.getElementById('inp-telephone').value.trim();
    toggleRecapRow('recap-row-tel', 'recap-tel', tel);

    const dom = document.getElementById('inp-domaine').value.trim();
    if (dom) {
        document.getElementById('recap-row-domaine').style.display = '';
        document.getElementById('recap-domaine').textContent = dom + '.nafalo.test';
    } else {
        document.getElementById('recap-row-domaine').style.display = 'none';
    }

    const previewImg = document.getElementById('logoPreviewImg').src;
    if (previewImg && previewImg !== window.location.href) {
        document.getElementById('recap-row-logo').style.display = '';
        document.getElementById('recap-logo').src = previewImg;
    } else {
        document.getElementById('recap-row-logo').style.display = 'none';
    }
}

function setText(id, val)    { document.getElementById(id).textContent = val || '—'; }
function toggleRecapRow(rowId, valId, val) {
    const row = document.getElementById(rowId);
    if (val) { row.style.display = ''; document.getElementById(valId).textContent = val; }
    else row.style.display = 'none';
}

// ── CGU toggle submit ──────────────────────────────────────────────────────
function toggleSubmit() {
    document.getElementById('btnSubmit').disabled = !document.getElementById('chk-cgu').checked;
}

// ── Form submit spinner ────────────────────────────────────────────────────
document.getElementById('wizardForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Création en cours…';
});
</script>
</body>
</html>
