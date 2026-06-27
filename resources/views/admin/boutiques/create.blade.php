@extends('layouts.admin')

@section('title', 'Créer ma boutique')

@push('styles')
<style>
/* ── Wizard Page ──────────────────────────────────────────── */
.wizard-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 2.5rem 1.25rem 5rem;
    background: var(--bg);
}

.wizard-header {
    text-align: center;
    margin-bottom: 2.5rem;
}
.wizard-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.4rem;
}
.wizard-header p {
    font-size: 0.92rem;
    color: var(--text-3);
}

/* ── Steps indicator ──────────────────────────────────────── */
.wiz-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 2.5rem;
    width: 100%;
    max-width: 560px;
}
.wiz-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    cursor: default;
}
.wiz-step-num {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; font-weight: 700;
    background: var(--bg-card);
    border: 2px solid var(--border);
    color: var(--text-3);
    transition: all 0.3s;
    position: relative; z-index: 2;
}
.wiz-step.active .wiz-step-num {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
    box-shadow: 0 0 0 5px rgba(124,58,237,0.2);
}
.wiz-step.done .wiz-step-num {
    background: #22c55e;
    border-color: #22c55e;
    color: white;
}
.wiz-step-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-3);
    margin-top: 6px;
    text-align: center;
    transition: color 0.3s;
}
.wiz-step.active .wiz-step-label { color: var(--accent); }
.wiz-step.done  .wiz-step-label { color: #22c55e; }

/* connector line */
.wiz-connector {
    flex: 1;
    height: 2px;
    background: var(--border);
    margin-bottom: 24px;
    transition: background 0.4s;
    position: relative; z-index: 1;
}
.wiz-connector.done { background: #22c55e; }

/* ── Card ─────────────────────────────────────────────────── */
.wizard-card {
    width: 100%;
    max-width: 560px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem 2rem 2rem;
    box-shadow: 0 8px 40px rgba(0,0,0,0.25);
}

/* ── Step panels ──────────────────────────────────────────── */
.wiz-panel { display: none; animation: fadeSlideIn 0.35s ease; }
.wiz-panel.active { display: block; }
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateX(18px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeSlideBack {
    from { opacity: 0; transform: translateX(-18px); }
    to   { opacity: 1; transform: translateX(0); }
}
.wiz-panel.back { animation: fadeSlideBack 0.3s ease; }

.wiz-panel-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.25rem;
}
.wiz-panel-sub {
    font-size: 0.82rem;
    color: var(--text-3);
    margin-bottom: 1.75rem;
}

/* ── Form fields ──────────────────────────────────────────── */
.wiz-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-2);
    margin-bottom: 0.4rem;
}
.wiz-input {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    color: var(--text-1);
    font-family: inherit;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
    box-sizing: border-box;
}
.wiz-input::placeholder { color: var(--text-3); }
.wiz-input:focus {
    border-color: var(--accent);
    background: rgba(124,58,237,0.06);
}
.wiz-input.is-invalid { border-color: #ef4444; }
.wiz-field { margin-bottom: 1.1rem; }
.wiz-hint {
    font-size: 0.75rem;
    color: var(--text-3);
    margin-top: 0.3rem;
}
.wiz-error {
    font-size: 0.78rem;
    color: #f87171;
    margin-top: 0.3rem;
    display: none;
}

/* ── Logo Upload ──────────────────────────────────────────── */
.logo-upload-zone {
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 2rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s;
    background: rgba(255,255,255,0.02);
    position: relative;
}
.logo-upload-zone:hover,
.logo-upload-zone.drag-over {
    border-color: var(--accent);
    background: rgba(124,58,237,0.06);
}
.logo-upload-zone input[type="file"] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.logo-preview-wrap {
    display: none;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}
.logo-preview-wrap.visible { display: flex; }
.logo-preview {
    width: 90px; height: 90px;
    border-radius: 14px;
    object-fit: cover;
    border: 2px solid var(--accent);
    box-shadow: 0 4px 20px rgba(124,58,237,0.3);
}
.logo-upload-placeholder { color: var(--text-3); }
.logo-upload-placeholder i { font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-3); }
.logo-upload-placeholder p { font-size: 0.82rem; margin: 0; }
.logo-change-btn {
    font-size: 0.75rem;
    color: var(--accent);
    cursor: pointer;
    text-decoration: underline;
    background: none; border: none;
    font-family: inherit;
}

/* ── Domaine preview ──────────────────────────────────────── */
.domain-preview {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.65rem 1rem;
    border-radius: 10px;
    font-size: 0.82rem;
    margin-top: 0.5rem;
    transition: all 0.3s;
    border: 1px solid var(--border);
    background: rgba(255,255,255,0.03);
}
.domain-preview.available { background:rgba(34,197,94,0.08); border-color:rgba(34,197,94,0.25); color:#4ade80; }
.domain-preview.taken     { background:rgba(239,68,68,0.08);  border-color:rgba(239,68,68,0.25);  color:#f87171; }
.domain-preview.checking  { background:rgba(234,179,8,0.08);  border-color:rgba(234,179,8,0.2);   color:#fde68a; }
.domain-preview i { opacity: 0.9; }
.domain-preview span { font-weight: 600; word-break: break-all; }

/* ── Social inputs ────────────────────────────────────────── */
.social-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0.85rem;
}
.social-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.social-icon.fb  { background: rgba(24,119,242,0.15); color: #1877f2; }
.social-icon.tw  { background: rgba(29,161,242,0.15); color: #1da1f2; }
.social-icon.ig  { background: rgba(225,48,108,0.15); color: #e1306c; }
.social-icon.yt  { background: rgba(255,0,0,0.15); color: #ff0000; }
.social-icon.tk  { background: rgba(105,201,208,0.15); color: #69c9d0; }

/* ── Récapitulatif ────────────────────────────────────────── */
.recap-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.25rem;
    margin-bottom: 0.85rem;
}
.recap-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.85rem;
}
.recap-row:last-child { border-bottom: none; padding-bottom: 0; }
.recap-key { color: var(--text-3); min-width: 120px; }
.recap-val { color: var(--text-1); font-weight: 600; }
.recap-logo-preview {
    width: 48px; height: 48px;
    border-radius: 10px;
    object-fit: cover;
    border: 1.5px solid var(--border);
}

/* ── Buttons ──────────────────────────────────────────────── */
.wiz-btn-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.75rem;
    gap: 12px;
}
.btn-wiz-next {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 0.85rem 1.5rem;
    background: var(--accent);
    color: white; font-weight: 700; font-size: 0.9rem;
    border: none; border-radius: 12px;
    cursor: pointer; font-family: inherit;
    transition: all 0.25s;
}
.btn-wiz-next:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 6px 20px var(--accent-glow); }
.btn-wiz-back {
    display: flex; align-items: center; gap: 7px;
    padding: 0.85rem 1.25rem;
    background: rgba(255,255,255,0.06);
    color: var(--text-2); font-weight: 600; font-size: 0.85rem;
    border: 1px solid var(--border); border-radius: 12px;
    cursor: pointer; font-family: inherit;
    transition: all 0.2s;
}
.btn-wiz-back:hover { background: rgba(255,255,255,0.1); color: var(--text-1); }

/* ── Success view ─────────────────────────────────────────── */
.success-view {
    text-align: center;
    padding: 2rem 1rem;
    display: none;
}
.success-view.visible { display: block; animation: fadeSlideIn 0.5s ease; }
.success-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(34,197,94,0.15);
    color: #22c55e;
    font-size: 2rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    animation: popIn 0.5s cubic-bezier(0.175,0.885,0.32,1.275);
}
@keyframes popIn {
    0%   { transform: scale(0); opacity: 0; }
    80%  { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}

/* ── Character counter ───────────────────────────────────── */
.char-count { font-size: 0.72rem; color: var(--text-3); float: right; }

@media (max-width: 600px) {
    .wizard-card { padding: 1.5rem 1rem; }
    .wizard-header h1 { font-size: 1.6rem; }
    .wiz-steps { max-width: 100%; }
}
</style>
@endpush

@section('content')
<div class="wizard-page">

    {{-- Header --}}
    <div class="wizard-header">
        <h1>🏪 Créer ma boutique</h1>
        <p>Quelques minutes suffisent pour lancer ta boutique en ligne.</p>
    </div>

    {{-- Steps --}}
    <div class="wiz-steps" id="wiz-steps">
        <div class="wiz-step active" id="step-ind-1">
            <div class="wiz-step-num" id="snum-1">1</div>
            <div class="wiz-step-label">Identité</div>
        </div>
        <div class="wiz-connector" id="conn-1"></div>
        <div class="wiz-step" id="step-ind-2">
            <div class="wiz-step-num" id="snum-2">2</div>
            <div class="wiz-step-label">Contact</div>
        </div>
        <div class="wiz-connector" id="conn-2"></div>
        <div class="wiz-step" id="step-ind-3">
            <div class="wiz-step-num" id="snum-3">3</div>
            <div class="wiz-step-label">Confirmation</div>
        </div>
    </div>

    {{-- Wizard card --}}
    <div class="wizard-card">

        <form id="wizard-form" action="{{ route('admin.boutiques.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ─── PANEL 1 : Identité ──────────────────── --}}
            <div class="wiz-panel active" id="panel-1">
                <div class="wiz-panel-title">Identité de ta boutique</div>
                <div class="wiz-panel-sub">Comment s'appelle ta boutique et de quoi parle-t-elle ?</div>

                {{-- Nom --}}
                <div class="wiz-field">
                    <label class="wiz-label" for="nom">Nom de la boutique <span style="color:#f87171">*</span></label>
                    <input type="text" class="wiz-input @error('nom') is-invalid @enderror"
                           id="nom" name="nom" value="{{ old('nom') }}"
                           placeholder="Ex : MINORA, NafaloShop…"
                           maxlength="80" autocomplete="off">
                    <div style="display:flex;justify-content:space-between;margin-top:4px;">
                        <span class="wiz-error" id="err-nom">Le nom est requis.</span>
                        <span class="char-count"><span id="cc-nom">0</span>/80</span>
                    </div>
                    @error('nom')<div class="wiz-error" style="display:block">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="wiz-field">
                    <label class="wiz-label" for="description">
                        Description
                        <span style="font-weight:400;color:var(--text-3);font-size:0.75rem;">— optionnel</span>
                    </label>
                    <textarea class="wiz-input @error('description') is-invalid @enderror"
                              id="description" name="description"
                              rows="3" maxlength="300"
                              placeholder="Décris ce que tu vends en quelques mots…">{{ old('description') }}</textarea>
                    <div style="display:flex;justify-content:space-between;margin-top:4px;">
                        <span class="wiz-hint">Visible sur ta page d'accueil boutique.</span>
                        <span class="char-count"><span id="cc-desc">0</span>/300</span>
                    </div>
                </div>

                {{-- Logo --}}
                <div class="wiz-field">
                    <label class="wiz-label">Logo <span style="font-weight:400;color:var(--text-3);font-size:0.75rem;">— optionnel</span></label>
                    <div class="logo-upload-zone" id="logo-zone">
                        <input type="file" name="logo" id="logo" accept="image/*" onchange="handleLogoChange(this)">
                        <div class="logo-upload-placeholder" id="logo-placeholder">
                            <i class="fas fa-image"></i>
                            <p><strong>Clique ou glisse</strong> ton logo ici</p>
                            <p style="font-size:0.72rem;margin-top:4px;color:var(--text-3);">PNG, JPG, SVG — max 2 Mo</p>
                        </div>
                        <div class="logo-preview-wrap" id="logo-preview-wrap">
                            <img src="" alt="Logo preview" class="logo-preview" id="logo-preview-img">
                            <button type="button" class="logo-change-btn" onclick="resetLogo()">Changer le logo</button>
                        </div>
                    </div>
                </div>

                <div class="wiz-btn-row">
                    <a href="{{ route('admin.boutiques.index') }}" class="btn-wiz-back">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" class="btn-wiz-next" onclick="goToStep(2)">
                        Continuer <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ─── PANEL 2 : Contact & Domaine ────────── --}}
            <div class="wiz-panel" id="panel-2">
                <div class="wiz-panel-title">Contact & accès</div>
                <div class="wiz-panel-sub">Comment tes clients peuvent te joindre, et l'adresse de ta boutique.</div>

                {{-- Email --}}
                <div class="wiz-field">
                    <label class="wiz-label" for="email">Email de contact <span style="color:#f87171">*</span></label>
                    <input type="email" class="wiz-input @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}"
                           placeholder="boutique@exemple.com">
                    <span class="wiz-error" id="err-email">Un email valide est requis.</span>
                    @error('email')<div class="wiz-error" style="display:block">{{ $message }}</div>@enderror
                </div>

                {{-- Téléphone --}}
                <div class="wiz-field">
                    <label class="wiz-label" for="telephone">
                        Téléphone
                        <span style="font-weight:400;color:var(--text-3);font-size:0.75rem;">— optionnel</span>
                    </label>
                    <input type="text" class="wiz-input @error('telephone') is-invalid @enderror"
                           id="telephone" name="telephone" value="{{ old('telephone') }}"
                           placeholder="+225 07 00 00 00 00">
                </div>

                {{-- Domaine --}}
                <div class="wiz-field">
                    <label class="wiz-label" for="domaine_personnalise">
                        Domaine / Sous-domaine
                        <span style="font-weight:400;color:var(--text-3);font-size:0.75rem;">— optionnel</span>
                    </label>
                    <input type="text" class="wiz-input @error('domaine_personnalise') is-invalid @enderror"
                           id="domaine_personnalise" name="domaine_personnalise"
                           value="{{ old('domaine_personnalise') }}"
                           placeholder="mon-nom-boutique"
                           autocomplete="off"
                           oninput="checkDomainAvailability(this.value)">
                    <div class="wiz-hint">Identifiant unique de ta boutique (lettres, chiffres, tirets).</div>
                    <div class="domain-preview" id="domain-preview" style="display:none;">
                        <i class="fas fa-circle-notch fa-spin" id="domain-icon"></i>
                        <span id="domain-preview-text"></span>
                    </div>
                    @error('domaine_personnalise')
                        <div class="wiz-error" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Réseaux sociaux --}}
                <div class="wiz-field">
                    <label class="wiz-label">Réseaux sociaux <span style="font-weight:400;color:var(--text-3);font-size:0.75rem;">— optionnel</span></label>
                    <div class="social-row">
                        <div class="social-icon fb"><i class="fab fa-facebook-f"></i></div>
                        <input type="url" class="wiz-input" name="reseaux_sociaux[facebook]"
                               placeholder="https://facebook.com/…" value="{{ old('reseaux_sociaux.facebook') }}">
                    </div>
                    <div class="social-row">
                        <div class="social-icon ig"><i class="fab fa-instagram"></i></div>
                        <input type="url" class="wiz-input" name="reseaux_sociaux[instagram]"
                               placeholder="https://instagram.com/…" value="{{ old('reseaux_sociaux.instagram') }}">
                    </div>
                    <div class="social-row">
                        <div class="social-icon tw"><i class="fab fa-twitter"></i></div>
                        <input type="url" class="wiz-input" name="reseaux_sociaux[twitter]"
                               placeholder="https://twitter.com/…" value="{{ old('reseaux_sociaux.twitter') }}">
                    </div>
                    <div class="social-row">
                        <div class="social-icon yt"><i class="fab fa-youtube"></i></div>
                        <input type="url" class="wiz-input" name="reseaux_sociaux[youtube]"
                               placeholder="https://youtube.com/…" value="{{ old('reseaux_sociaux.youtube') }}">
                    </div>
                    <div class="social-row">
                        <div class="social-icon tk"><i class="fab fa-tiktok"></i></div>
                        <input type="url" class="wiz-input" name="reseaux_sociaux[tiktok]"
                               placeholder="https://tiktok.com/…" value="{{ old('reseaux_sociaux.tiktok') }}">
                    </div>
                </div>

                <div class="wiz-btn-row">
                    <button type="button" class="btn-wiz-back" onclick="goToStep(1)">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <button type="button" class="btn-wiz-next" onclick="goToStep(3)">
                        Continuer <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ─── PANEL 3 : Récapitulatif ─────────────── --}}
            <div class="wiz-panel" id="panel-3">
                <div class="wiz-panel-title">Tout est prêt ! 🎉</div>
                <div class="wiz-panel-sub">Vérifie tes informations avant de créer ta boutique.</div>

                <div class="recap-card" id="recap-body">
                    {{-- Généré en JS --}}
                </div>

                {{-- Active checkbox --}}
                <div style="display:flex;align-items:center;gap:10px;padding:0.75rem 1rem;background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);border-radius:12px;margin-bottom:1rem;">
                    <input type="checkbox" id="est_active" name="est_active" value="1" checked
                           style="width:18px;height:18px;accent-color:#22c55e;cursor:pointer;">
                    <label for="est_active" style="font-size:0.85rem;font-weight:600;color:var(--text-1);cursor:pointer;margin:0;">
                        Activer la boutique immédiatement
                    </label>
                </div>

                <div class="wiz-btn-row">
                    <button type="button" class="btn-wiz-back" onclick="goToStep(2)">
                        <i class="fas fa-arrow-left"></i> Modifier
                    </button>
                    <button type="submit" class="btn-wiz-next" id="btn-submit" onclick="showLoading(this)">
                        <i class="fas fa-rocket"></i> Créer ma boutique
                    </button>
                </div>
            </div>

        </form>

        {{-- Success view (displayed via JS after submit feedback) --}}
        <div class="success-view" id="success-view">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--text-1);margin-bottom:0.5rem;">
                Boutique créée ! 🚀
            </h2>
            <p style="color:var(--text-3);font-size:0.9rem;">Ta boutique est maintenant accessible.</p>
        </div>

    </div>{{-- /wizard-card --}}

    {{-- Laravel errors si retour validation serveur --}}
    @if ($errors->any())
    <div style="margin-top:1rem;max-width:560px;width:100%;">
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:12px;padding:1rem 1.25rem;">
            <p style="font-size:0.85rem;font-weight:600;color:#f87171;margin-bottom:0.5rem;"><i class="fas fa-exclamation-circle me-1"></i>Erreurs de validation :</p>
            <ul style="margin:0;padding-left:1.25rem;font-size:0.82rem;color:#fca5a5;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
var currentStep   = 1;
var logoDataUrl   = null;
var domainTimer   = null;
var domainOk      = true; // true = pas de conflit (vide ou dispo)
var checkDomainUrl = '{{ route("admin.boutiques.check-domain") }}';

// ── Init ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Pre-fill char counters
    charCount('nom', 'cc-nom');
    charCount('description', 'cc-desc');

    // Live char counters
    document.getElementById('nom').addEventListener('input', function () {
        charCount('nom', 'cc-nom');
    });
    document.getElementById('description').addEventListener('input', function () {
        charCount('description', 'cc-desc');
    });

    // Drag & drop logo
    var zone = document.getElementById('logo-zone');
    zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', function ()  { zone.classList.remove('drag-over'); });
    zone.addEventListener('drop', function (e) {
        e.preventDefault();
        zone.classList.remove('drag-over');
        var file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            previewLogo(file);
        }
    });

    // Si erreurs de validation, revenir à l'étape concernée
    @if($errors->has('nom') || $errors->has('description'))
        goToStep(1, true);
    @elseif($errors->has('email') || $errors->has('domaine_personnalise'))
        goToStep(2, true);
    @endif
});

// ── Navigation ────────────────────────────────────────────────
function goToStep(step, back) {
    if (step > currentStep && !validateStep(currentStep)) return;

    // Hide current, show new
    document.getElementById('panel-' + currentStep).classList.remove('active');
    var newPanel = document.getElementById('panel-' + step);
    if (back || step < currentStep) newPanel.classList.add('back');
    newPanel.classList.add('active');
    setTimeout(function () { newPanel.classList.remove('back'); }, 350);

    // Update step indicators
    updateStepIndicators(step);

    if (step === 3) buildRecap();
    currentStep = step;

    // Scroll to top of card
    document.querySelector('.wizard-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function updateStepIndicators(activeStep) {
    for (var i = 1; i <= 3; i++) {
        var ind  = document.getElementById('step-ind-' + i);
        var snum = document.getElementById('snum-' + i);
        ind.classList.remove('active', 'done');
        if (i < activeStep) {
            ind.classList.add('done');
            snum.innerHTML = '<i class="fas fa-check" style="font-size:0.75rem"></i>';
        } else if (i === activeStep) {
            ind.classList.add('active');
            snum.textContent = i;
        } else {
            snum.textContent = i;
        }
        if (i < 3) {
            var conn = document.getElementById('conn-' + i);
            conn.classList.toggle('done', i < activeStep);
        }
    }
}

// ── Validation ────────────────────────────────────────────────
function validateStep(step) {
    if (step === 1) {
        var nom = document.getElementById('nom').value.trim();
        if (!nom) {
            showError('nom', 'err-nom', 'Le nom de la boutique est requis.');
            document.getElementById('nom').focus();
            return false;
        }
        hideError('nom', 'err-nom');
        return true;
    }
    if (step === 2) {
        var email = document.getElementById('email').value.trim();
        if (!email || !email.includes('@')) {
            showError('email', 'err-email', 'Un email valide est requis.');
            document.getElementById('email').focus();
            return false;
        }
        hideError('email', 'err-email');

        // Bloquer si domaine déjà pris (étape 7 du diagramme)
        if (!domainOk) {
            var domainEl = document.getElementById('domaine_personnalise');
            domainEl.classList.add('is-invalid');
            domainEl.focus();
            return false;
        }
        return true;
    }
    return true;
}

function showError(fieldId, errId, msg) {
    document.getElementById(fieldId).classList.add('is-invalid');
    var el = document.getElementById(errId);
    if (el) { el.textContent = msg; el.style.display = 'block'; }
}
function hideError(fieldId, errId) {
    document.getElementById(fieldId).classList.remove('is-invalid');
    var el = document.getElementById(errId);
    if (el) el.style.display = 'none';
}

// ── Logo ──────────────────────────────────────────────────────
function handleLogoChange(input) {
    if (input.files && input.files[0]) {
        previewLogo(input.files[0]);
    }
}
function previewLogo(file) {
    var reader = new FileReader();
    reader.onload = function (e) {
        logoDataUrl = e.target.result;
        document.getElementById('logo-preview-img').src = logoDataUrl;
        document.getElementById('logo-placeholder').style.display = 'none';
        document.getElementById('logo-preview-wrap').classList.add('visible');
    };
    reader.readAsDataURL(file);
}
function resetLogo() {
    logoDataUrl = null;
    document.getElementById('logo').value = '';
    document.getElementById('logo-preview-img').src = '';
    document.getElementById('logo-placeholder').style.display = '';
    document.getElementById('logo-preview-wrap').classList.remove('visible');
}

// ── Domain check AJAX (étapes 4→5→6→7/8 du diagramme) ────────
function checkDomainAvailability(val) {
    var preview = document.getElementById('domain-preview');
    var previewText = document.getElementById('domain-preview-text');
    var icon = document.getElementById('domain-icon');

    clearTimeout(domainTimer);

    var slug = val.trim().toLowerCase().replace(/[^a-z0-9\-]/g, '-').replace(/\-+/g, '-').replace(/^\-|\-$/g, '');

    if (!slug) {
        preview.style.display = 'none';
        domainOk = true;
        return;
    }

    // Afficher "vérification en cours"
    preview.className = 'domain-preview checking';
    preview.style.display = 'flex';
    icon.className = 'fas fa-circle-notch fa-spin';
    previewText.textContent = 'Vérification de "' + slug + '"…';

    // Debounce 600ms
    domainTimer = setTimeout(function () {
        fetch(checkDomainUrl + '?domaine=' + encodeURIComponent(slug), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.available) {
                preview.className = 'domain-preview available';
                icon.className = 'fas fa-check-circle';
                previewText.textContent = '"' + data.domaine + '" est disponible !';
                domainOk = true;
            } else {
                preview.className = 'domain-preview taken';
                icon.className = 'fas fa-times-circle';
                previewText.textContent = '"' + data.domaine + '" est déjà utilisé.';
                domainOk = false;
            }
        })
        .catch(function () {
            preview.style.display = 'none';
            domainOk = true;
        });
    }, 600);
}

// ── Recap ─────────────────────────────────────────────────────
function buildRecap() {
    var nom   = document.getElementById('nom').value.trim();
    var email = document.getElementById('email').value.trim();
    var tel   = document.getElementById('telephone').value.trim();
    var dom   = document.getElementById('domaine_personnalise').value.trim();
    var desc  = document.getElementById('description').value.trim();

    var rows = '';

    // Logo
    if (logoDataUrl) {
        rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-image me-1"></i>Logo</span><span class="recap-val"><img src="' + logoDataUrl + '" class="recap-logo-preview" alt="logo"></span></div>';
    }

    rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-store me-1"></i>Nom</span><span class="recap-val">' + escHtml(nom) + '</span></div>';

    if (desc) {
        rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-align-left me-1"></i>Description</span><span class="recap-val" style="font-size:0.8rem;font-weight:400;">' + escHtml(desc.substring(0, 80)) + (desc.length > 80 ? '…' : '') + '</span></div>';
    }

    rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-envelope me-1"></i>Email</span><span class="recap-val">' + escHtml(email) + '</span></div>';

    if (tel) {
        rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-phone me-1"></i>Téléphone</span><span class="recap-val">' + escHtml(tel) + '</span></div>';
    }

    if (dom) {
        rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-globe me-1"></i>Domaine</span><span class="recap-val" style="color:var(--accent)">' + escHtml(dom) + '</span></div>';
    }

    // Socials
    var fb = document.querySelector('[name="reseaux_sociaux[facebook]"]').value.trim();
    var ig = document.querySelector('[name="reseaux_sociaux[instagram]"]').value.trim();
    var tw = document.querySelector('[name="reseaux_sociaux[twitter]"]').value.trim();
    var yt = document.querySelector('[name="reseaux_sociaux[youtube]"]').value.trim();
    var tk = document.querySelector('[name="reseaux_sociaux[tiktok]"]').value.trim();
    var socials = [fb, ig, tw, yt, tk].filter(Boolean);
    if (socials.length) {
        rows += '<div class="recap-row"><span class="recap-key"><i class="fas fa-share-alt me-1"></i>Réseaux</span><span class="recap-val">' + socials.length + ' réseau(x) renseigné(s)</span></div>';
    }

    document.getElementById('recap-body').innerHTML = rows;
}

// ── Helpers ───────────────────────────────────────────────────
function charCount(fieldId, countId) {
    var el = document.getElementById(fieldId);
    var cc = document.getElementById(countId);
    if (el && cc) cc.textContent = el.value.length;
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showLoading(btn) {
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création…';
    btn.disabled  = true;
}
</script>
@endpush
