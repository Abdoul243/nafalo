@extends('layouts.admin')

@section('title', 'Nouveau produit')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
/* ── Générateur IA ── */
.ia-generator-box {
    background: linear-gradient(135deg,rgba(124,58,237,.08),rgba(168,85,247,.05));
    border: 1.5px solid rgba(124,58,237,.25);
    border-radius: 16px; padding: 1.1rem 1.25rem;
}
.ia-gen-header { display:flex;align-items:flex-start;gap:12px; }
.ia-gen-icon { font-size:1.5rem;flex-shrink:0;line-height:1; }
.ia-gen-title { font-weight:700;font-size:.88rem;color:var(--accent); }
.ia-gen-sub { font-size:.75rem;color:var(--text-3);margin-top:2px; }
.ia-gen-toggle {
    margin-left:auto;background:none;border:none;color:var(--text-3);
    cursor:pointer;font-size:.85rem;padding:4px;transition:transform .2s;
}
.ia-gen-toggle.open { transform:rotate(180deg); }
</style>
<style>
    .product-editor-layout {
        display: flex;
        gap: 0;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        min-height: 80vh;
    }
    .editor-sidebar {
        width: 240px;
        min-width: 240px;
        background: #fafafa;
        border-right: 1px solid #eeeeee;
        padding: 1.5rem 0;
    }
    .editor-sidebar .product-title-preview {
        padding: 0 1.5rem 1.5rem;
        border-bottom: 1px solid #eee;
        margin-bottom: 1rem;
    }
    .editor-sidebar .product-title-preview h6 {
        font-weight: 800;
        color: #1a1a2e;
        font-size: 0.95rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sidebar-nav { list-style: none; padding: 0; margin: 0; }
    .sidebar-nav li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.75rem 1.5rem;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .sidebar-nav li a:hover {
        background: #f0f0f0;
        color: #333;
    }
    .sidebar-nav li a.active {
        background: rgba(15,23,42,0.08);
        color: #0f172a;
        border-left-color: #0f172a;
        font-weight: 600;
    }
    .sidebar-nav li a i { width: 18px; text-align: center; }

    /* ── Type cards (Payant / Gratuit) ── */
    .type-card {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        padding: 1.25rem 1rem; border: 2px solid #e8e8e8; border-radius: 16px;
        cursor: pointer; text-align: center; transition: all 0.2s; background: white;
        height: 100%;
    }
    .type-card:hover { border-color: #0f172a; background: rgba(15,23,42,0.04); }
    .type-card.selected { border-color: #0f172a; background: rgba(15,23,42,0.06); }
    .type-card-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.2rem;
    }
    .type-card-label { font-weight: 700; font-size: 0.9rem; color: #1a1a2e; }
    .type-card-sub { font-size: 0.75rem; color: #888; line-height: 1.3; }

    /* ── Champs lead toggle ── */
    .champ-toggle {
        display: flex; align-items: center; padding: 0.6rem 1rem;
        border: 1.5px solid #e0e0e0; border-radius: 10px; cursor: pointer;
        font-size: 0.85rem; font-weight: 500; color: #555; transition: all 0.2s;
        background: white; width: 100%;
    }
    .champ-toggle input { display: none; }
    .champ-toggle:hover { border-color: #16a34a; color: #16a34a; }
    .champ-toggle.active { border-color: #16a34a; background: #f0fdf4; color: #16a34a; font-weight: 600; }
    .editor-main {
        flex: 1;
        padding: 2.5rem;
        overflow-y: auto;
    }
    .section-block {
        display: none;
    }
    .section-block.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    .section-subtitle {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }
    .form-label { font-weight: 600; color: #333; font-size: 0.9rem; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 3px rgba(15,23,42,0.15);
    }
    .ql-toolbar { border-radius: 10px 10px 0 0 !important; border-color: #e0e0e0 !important; }
    .ql-container { border-radius: 0 0 10px 10px !important; border-color: #e0e0e0 !important; min-height: 300px; font-size: 0.95rem; }
    .ql-editor { min-height: 280px; }
    .image-upload-area {
        border: 2px dashed #e0e0e0;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafafa;
    }
    .image-upload-area:hover { border-color: #0f172a; background: rgba(15,23,42,0.04); }
    .image-preview { display: none; margin-top: 1rem; }
    .image-preview img { max-height: 200px; border-radius: 10px; border: 1px solid #eee; }
    .price-input-wrapper { position: relative; }
    .price-input-wrapper .currency {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }
    .top-bar .product-name-display {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a1a2e;
    }
    .btn-save {
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(15,23,42,0.3);
        color: white;
    }
    .btn-publish {
        background: #1a1a2e;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-publish:hover { background: #2d2f45; color: white; }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }
    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 1rem;
        border: 2px solid #e8e8e8;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        background: white;
    }
    .category-item:hover {
        border-color: #0f172a;
        background: rgba(15,23,42,0.04);
    }
    .category-item.selected {
        border-color: #0f172a;
        background: rgba(15,23,42,0.07);
        color: #0f172a;
    }
    .category-item i {
        font-size: 1.5rem;
        color: #888;
    }
    .category-item.selected i { color: #0f172a; }
    .category-item span {
        font-size: 0.8rem;
        font-weight: 600;
        color: #444;
        line-height: 1.2;
    }
    .category-item.selected span { color: #0f172a; }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        /* Layout vertical */
        .product-editor-layout {
            flex-direction: column;
            border-radius: 14px;
            min-height: auto;
        }
        /* Sidebar → bandeau de tabs horizontal en haut */
        .editor-sidebar {
            width: 100% !important;
            min-width: 0 !important;
            border-right: none;
            border-bottom: 1px solid #eee;
            padding: 0;
        }
        .product-title-preview { display: none; }
        .sidebar-nav {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 0;
        }
        .sidebar-nav li { flex-shrink: 0; }
        .sidebar-nav li a {
            padding: 0.75rem 1rem;
            border-left: none !important;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            white-space: nowrap;
            flex-direction: column;
            gap: 4px;
            font-size: 0.75rem;
        }
        .sidebar-nav li a.active {
            border-bottom-color: #0f172a;
            background: rgba(15,23,42,0.06);
        }
        /* Contenu principal */
        .editor-main { padding: 1rem; }
        /* Grille catégories : 2 colonnes sur mobile */
        .category-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* Barre supérieure responsive */
    @media (max-width: 480px) {
        .d-flex.align-items-center.justify-content-between.mb-3 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }
        .d-flex.align-items-center.justify-content-between.mb-3 .d-flex.gap-2 {
            width: 100%;
            justify-content: stretch;
        }
        .btn-save, .btn-publish { flex: 1; text-align: center; justify-content: center; }
        .section-title { font-size: 1.1rem; }
    }

    /* ── Wizard : badges d'étape ── */
    .step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        font-size: 0.65rem;
        font-weight: 800;
        margin-left: auto;
        flex-shrink: 0;
    }
    .step-badge.valid  { background: #22c55e; color: white; }
    .step-badge.invalid{ background: #ef4444; color: white; }
    .step-icon.valid  i { color: #22c55e !important; }
    .step-icon.invalid i{ color: #ef4444 !important; }

    /* ── Wizard : navigation Suivant / Précédent ── */
    .wizard-nav {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }
    .wizard-nav-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
    .btn-wizard-prev {
        background: white;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-wizard-prev:hover { border-color: #94a3b8; color: #334155; }
    .btn-wizard-next {
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-wizard-next:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(15,23,42,0.3); }

    /* ── Wizard : états d'erreur ── */
    .is-invalid-wizard {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.15) !important;
    }
    .grid-invalid {
        border: 2px solid #ef4444;
        border-radius: 14px;
        padding: 0.5rem;
        animation: shake 0.4s ease;
    }
    .upload-invalid {
        border-color: #ef4444 !important;
        background: #fef2f2 !important;
        animation: shake 0.4s ease;
    }
    .wizard-error-msg {
        display: none;
        color: #dc2626;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        background: #fef2f2;
        border-radius: 8px;
        border-left: 3px solid #ef4444;
    }
    .wizard-error-msg.show { display: block; }
    @keyframes shake {
        0%,100%{ transform:translateX(0); }
        25%{ transform:translateX(-6px); }
        75%{ transform:translateX(6px); }
    }
    /* Publier disabled */
    .btn-publish:disabled { opacity: .45; cursor: not-allowed; }
    .btn-publish:disabled:hover { transform: none; box-shadow: none; background: #1a1a2e; }
</style>
@endpush

@section('content')

<form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data" id="produit-form">
@csrf

{{-- Barre supérieure --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.produits.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <h1 class="h5 mb-0 fw-bold">Nouveau produit</h1>
    </div>
    <div class="d-flex gap-2">
        <input type="hidden" name="est_publie" id="est_publie_hidden" value="0">
        <button type="button" class="btn btn-save" onclick="saveDraft()">
            <i class="fas fa-save me-1"></i> Enregistrer
        </button>
        <button type="button" id="btn-publish" class="btn btn-publish" onclick="publish()" disabled title="Complétez les 5 sections requises pour publier">
            <i class="fas fa-check-circle me-1"></i> Publier
        </button>
    </div>
</div>

<div class="product-editor-layout">

    {{-- Sidebar --}}
    <div class="editor-sidebar">
        <div class="product-title-preview">
            <div class="text-muted small mb-1">Produit</div>
            <h6 id="sidebar-title">Sans titre</h6>
        </div>
        {{-- Barre de progression globale --}}
        <div style="padding:0 1.25rem 1rem;">
            <div style="font-size:.7rem;color:#94a3b8;margin-bottom:5px;font-weight:600;">PROGRESSION</div>
            <div style="height:5px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                <div id="progress-bar" style="height:100%;background:#0f172a;border-radius:10px;transition:width .4s;width:0%"></div>
            </div>
            <div id="progress-label" style="font-size:.7rem;color:#64748b;margin-top:4px;text-align:right;">0 / 5 requis</div>
        </div>

        <ul class="sidebar-nav" id="sidebar-nav-list">
            <li>
                <a href="#" class="active" data-section="informations" id="nav-informations">
                    <span class="step-icon" id="icon-informations">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="sidebar-text"> Informations</span>
                    <span class="step-badge" id="badge-informations" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="tarification" id="nav-tarification">
                    <span class="step-icon" id="icon-tarification">
                        <i class="fas fa-tag"></i>
                    </span>
                    <span class="sidebar-text"> Tarification</span>
                    <span class="step-badge" id="badge-tarification" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="fichiers" id="nav-fichiers">
                    <span class="step-icon" id="icon-fichiers">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <span class="sidebar-text"> Fichiers</span>
                    <span class="step-badge" id="badge-fichiers" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="description" id="nav-description">
                    <span class="step-icon" id="icon-description">
                        <i class="fas fa-align-left"></i>
                    </span>
                    <span class="sidebar-text"> Description</span>
                    <span class="step-badge" id="badge-description" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="visuel" id="nav-visuel">
                    <span class="step-icon" id="icon-visuel">
                        <i class="fas fa-image"></i>
                    </span>
                    <span class="sidebar-text"> Visuel & Design</span>
                    <span class="step-badge" id="badge-visuel" style="display:none;"></span>
                </a>
            </li>
        </ul>
    </div>

    {{-- Contenu principal --}}
    <div class="editor-main">

        {{-- SECTION : Informations --}}
        <div class="section-block active" id="section-informations">
            <div class="section-title">Détails du produit</div>
            <div class="section-subtitle">Les informations essentielles de votre produit</div>

            <div class="mb-4">
                <label class="form-label">Nom du produit *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                       name="nom" id="nom-input"
                       placeholder="Ex: Formation Excel Complète"
                       value="{{ old('nom') }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Catégorie <span class="text-muted fw-normal">(optionnel)</span></label>
                <p class="text-muted small mb-3">Choisissez la catégorie qui correspond le mieux à votre produit</p>
                @if($categories->isEmpty())
                <div style="background:#f8fafc;border:1px dashed #d1d5db;border-radius:10px;padding:0.9rem 1.1rem;font-size:0.85rem;color:#6b7280;">
                    <i class="fas fa-info-circle"></i> Aucune catégorie pour l'instant — ce n'est pas obligatoire, vous pouvez continuer. (Vous pourrez en créer plus tard.)
                </div>
                @endif
                <div class="category-grid">
                    @php
                    $icons = [
                        'Formation & Éducation' => 'fas fa-graduation-cap',
                        'Livres & Ebooks' => 'fas fa-book',
                        'Templates & Modèles' => 'fas fa-file-alt',
                        'Audio & Musique' => 'fas fa-music',
                        'Vidéo & Film' => 'fas fa-film',
                        'Logiciels & Outils' => 'fas fa-tools',
                        'Art & Design' => 'fas fa-paint-brush',
                        'Business & Marketing' => 'fas fa-chart-line',
                        'Santé & Bien-être' => 'fas fa-heartbeat',
                        'Autre' => 'fas fa-ellipsis-h',
                    ];
                    @endphp
                    @foreach($categories as $categorie)
                    <label class="category-item {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}">
                        <input type="radio" name="categorie_id" value="{{ $categorie->id }}"
                               {{ old('categorie_id') == $categorie->id ? 'checked' : '' }}
                               style="display:none;">
                        <i class="{{ $icons[$categorie->nom] ?? 'fas fa-tag' }}"></i>
                        <span>{{ $categorie->nom }}</span>
                    </label>
                    @endforeach
                </div>
                @error('categorie_id')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="est_publie" value="1" id="est_publie_check"
                           {{ old('est_publie') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="est_publie_check">Publier immédiatement</label>
                </div>
            </div>

            {{-- Wizard Nav --}}
            <div class="wizard-nav">
                <div></div>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-informations">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez renseigner le nom du produit.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Tarification &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION : Tarification --}}
        <div class="section-block" id="section-tarification">
            <div class="section-title">Tarification</div>
            <div class="section-subtitle">Définissez le mode de distribution de votre produit</div>

            {{-- Choix du type --}}
            <div class="mb-4">
                <div class="row g-3" style="max-width:560px;">
                    {{-- Payant --}}
                    <div class="col-6">
                        <label class="type-card {{ old('type','payant') === 'payant' ? 'selected' : '' }}" id="card-payant">
                            <input type="radio" name="type" value="payant"
                                   {{ old('type','payant') === 'payant' ? 'checked' : '' }}
                                   onchange="switchType('payant')" style="display:none;">
                            <div class="type-card-icon" style="background:#0f172a;">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="type-card-label">Payant</div>
                            <div class="type-card-sub">Le client paie pour télécharger</div>
                        </label>
                    </div>
                    {{-- Gratuit / Lead Magnet --}}
                    <div class="col-6">
                        <label class="type-card {{ old('type') === 'gratuit' ? 'selected' : '' }}" id="card-gratuit">
                            <input type="radio" name="type" value="gratuit"
                                   {{ old('type') === 'gratuit' ? 'checked' : '' }}
                                   onchange="switchType('gratuit')" style="display:none;">
                            <div class="type-card-icon" style="background:linear-gradient(135deg,#16a34a,#15803d);">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="type-card-label">Gratuit <span class="badge bg-success ms-1" style="font-size:0.6rem;">Lead Magnet</span></div>
                            <div class="type-card-sub">Capture l'email du prospect</div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Bloc Payant --}}
            <div id="bloc-payant" style="{{ old('type') === 'gratuit' ? 'display:none;' : '' }}">
                <div class="mb-4" style="max-width:300px;">
                    <label class="form-label">Prix *</label>
                    <div class="price-input-wrapper">
                        <input type="number" step="1" id="prix-input"
                               class="form-control @error('prix') is-invalid @enderror"
                               name="prix" placeholder="0" value="{{ old('prix') }}">
                        <span class="currency">FCFA</span>
                    </div>
                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bloc Gratuit / Lead Magnet --}}
            <div id="bloc-gratuit" style="{{ old('type') !== 'gratuit' ? 'display:none;' : '' }}">

                {{-- Alerte explication --}}
                <div class="alert alert-success border-0 rounded-3 mb-4" style="background:#f0fdf4;">
                    <div class="d-flex gap-2">
                        <span style="font-size:1.4rem;">🎁</span>
                        <div>
                            <strong>Lead Magnet activé</strong><br>
                            <small class="text-muted">
                                Le client remplit un formulaire → reçoit le fichier gratuitement → vous gagnez un prospect qualifié.
                                La page de remerciement affichera automatiquement vos upsells payants.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Champs à collecter --}}
                <div class="mb-4">
                    <label class="form-label">Champs à collecter <span class="text-muted fw-normal">(Nom + Email toujours requis)</span></label>
                    <div class="row g-2">
                        @foreach(\App\Models\Produit::CHAMPS_LEAD_DISPONIBLES as $champ => $label)
                        <div class="col-6">
                            <label class="champ-toggle {{ in_array($champ, old('lead_champs_requis', [])) ? 'active' : '' }}">
                                <input type="checkbox" name="lead_champs_requis[]" value="{{ $champ }}"
                                       {{ in_array($champ, old('lead_champs_requis', [])) ? 'checked' : '' }}>
                                <i class="fas fa-{{ $champ === 'telephone' ? 'phone' : ($champ === 'ville' ? 'map-marker-alt' : ($champ === 'profession' ? 'briefcase' : 'globe')) }} me-2"></i>
                                {{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Limite de téléchargements --}}
                <div class="mb-4">
                    <label class="form-label">Limite de téléchargements <span class="text-muted fw-normal">(optionnel — crée de la rareté)</span></label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggle-limite"
                                   onchange="toggleLimite(this)" {{ old('lead_limite_dl') ? 'checked' : '' }}>
                            <label class="form-check-label" for="toggle-limite">Activer une limite</label>
                        </div>
                    </div>
                    <div id="bloc-limite" class="mt-3" style="{{ old('lead_limite_dl') ? '' : 'display:none;' }}">
                        <div class="input-group" style="max-width:250px;">
                            <input type="number" name="lead_limite_dl" min="1"
                                   class="form-control rounded-start-3"
                                   placeholder="Ex: 200"
                                   value="{{ old('lead_limite_dl') }}">
                            <span class="input-group-text">téléchargements</span>
                        </div>
                        <div class="form-text">Exemple : "200 téléchargements seulement" — après ça le produit se ferme automatiquement.</div>
                    </div>
                </div>

                {{-- Le prix à 0 est forcé par JS au submit (voir saveDraft/publish) --}}
            </div>

            {{-- Wizard Nav --}}
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-tarification">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez entrer un prix supérieur à 0 FCFA.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Fichiers &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION : Fichiers --}}
        <div class="section-block" id="section-fichiers">
            <div class="section-title">Livraison du produit</div>
            <div class="section-subtitle">Un fichier à télécharger, ou une formation avec espace membre</div>

            {{-- Format de livraison --}}
            <div class="mb-3" style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                @php $fmtInit = $formatInitial ?? 'fichier'; @endphp
                <label style="flex:1;min-width:200px;border-radius:10px;padding:0.7rem 0.9rem;cursor:pointer;display:flex;align-items:center;gap:9px;" id="opt-fichier">
                    <input type="radio" name="format" value="fichier" onchange="toggleFormat()" {{ $fmtInit === 'fichier' ? 'checked' : '' }}>
                    <span><strong>Fichier téléchargeable</strong><br><span class="text-muted small">PDF, ZIP, vidéo…</span></span>
                </label>
                <label style="flex:1;min-width:200px;border-radius:10px;padding:0.7rem 0.9rem;cursor:pointer;display:flex;align-items:center;gap:9px;" id="opt-formation">
                    <input type="radio" name="format" value="formation" onchange="toggleFormat()" {{ $fmtInit === 'formation' ? 'checked' : '' }}>
                    <span><strong>Formation (espace membre)</strong><br><span class="text-muted small">Modules, leçons, vidéos</span></span>
                </label>
                <label style="flex:1;min-width:200px;border-radius:10px;padding:0.7rem 0.9rem;cursor:pointer;display:flex;align-items:center;gap:9px;" id="opt-licence">
                    <input type="radio" name="format" value="licence" onchange="toggleFormat()" {{ $fmtInit === 'licence' ? 'checked' : '' }}>
                    <span><strong>Licences (clés)</strong><br><span class="text-muted small">Clés uniques livrées auto.</span></span>
                </label>
            </div>

            {{-- Note formation --}}
            <div id="bloc-formation" style="display:none;background:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;color:#4338ca;font-size:0.88rem;">
                <i class="fas fa-graduation-cap"></i> Après avoir enregistré le produit, vous pourrez <strong>construire le programme</strong> (modules + leçons, vidéos par lien ou upload). Aucun fichier requis ici.
            </div>

            {{-- Note licence --}}
            <div id="bloc-licence" style="display:none;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;color:#6d28d9;font-size:0.88rem;">
                <i class="fas fa-key"></i> Après avoir enregistré le produit, vous pourrez <strong>ajouter ou générer vos clés de licence</strong>. Une clé unique sera livrée automatiquement à chaque acheteur.
            </div>

            <div class="mb-4" id="bloc-fichier">
                <label class="form-label">Fichier numérique (PDF, ZIP, MP4...)</label>
                <div class="image-upload-area" onclick="document.getElementById('fichier-input').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <div class="fw-semibold">Cliquez pour téléverser</div>
                    <div class="text-muted small mt-1">PDF, ZIP, MP3, MP4 — Max 100MB</div>
                    <input type="file" id="fichier-input" name="fichier"
                           accept=".pdf,.zip,.mp3,.mp4" class="d-none"
                           onchange="showFileName(this)">
                </div>
                <div id="fichier-name" class="mt-2 text-success small d-none">
                    <i class="fas fa-check-circle me-1"></i> <span></span>
                </div>
                @error('fichier')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <script>
                function toggleFormat(){
                    var fmt = (document.querySelector('input[name=format]:checked')||{}).value || 'fichier';
                    var sansFichier = (fmt === 'formation' || fmt === 'licence');
                    var bf = document.getElementById('bloc-fichier');
                    if(bf) bf.style.display = sansFichier ? 'none' : '';
                    document.getElementById('bloc-formation').style.display = (fmt === 'formation') ? '' : 'none';
                    document.getElementById('bloc-licence').style.display   = (fmt === 'licence')   ? '' : 'none';
                    document.getElementById('opt-fichier').style.borderColor   = (fmt === 'fichier')   ? '#4f46e5' : '#e5e7eb';
                    document.getElementById('opt-formation').style.borderColor = (fmt === 'formation') ? '#4f46e5' : '#e5e7eb';
                    document.getElementById('opt-licence').style.borderColor   = (fmt === 'licence')   ? '#4f46e5' : '#e5e7eb';
                }
                // Synchronise l'UI avec le type pré-sélectionné (depuis l'écran de choix)
                document.addEventListener('DOMContentLoaded', toggleFormat);
            </script>

            {{-- Wizard Nav --}}
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-fichiers">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez sélectionner un fichier numérique à téléverser.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Description &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION : Description --}}
        <div class="section-block" id="section-description">
            <div class="section-title">Description</div>
            <div class="section-subtitle">Décrivez votre produit pour convaincre vos clients</div>

            {{-- Générateur IA --}}
            <div class="ia-generator-box mb-4" id="ia-generator-box">
                <div class="ia-gen-header">
                    <span class="ia-gen-icon">✨</span>
                    <div>
                        <div class="ia-gen-title">Générer avec l'IA</div>
                        <div class="ia-gen-sub">Décrivez votre produit en quelques mots — l'IA rédige votre page de vente complète</div>
                    </div>
                    <button type="button" class="ia-gen-toggle" id="ia-gen-toggle-btn" onclick="toggleIaGenerator()">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div id="ia-gen-body" style="display:none;margin-top:1rem;">
                    <div class="mb-3">
                        <textarea id="ia-brief" rows="3"
                            placeholder="Ex: Formation complète sur l'élevage de poulets de chair pour générer 500 000 FCFA par mois..."
                            style="width:100%;border:1.5px solid var(--border);border-radius:12px;padding:10px 14px;font-size:.875rem;background:var(--bg-elevated);color:var(--text-1);resize:vertical;font-family:inherit;outline:none;"></textarea>
                    </div>
                    <button type="button" id="ia-gen-btn" onclick="lancerGenerationIA()"
                        style="background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border:none;border-radius:12px;padding:10px 20px;font-weight:700;font-size:.875rem;cursor:pointer;display:flex;align-items:center;gap:8px;transition:opacity .2s;">
                        <span id="ia-gen-btn-text">✨ Générer la page de vente</span>
                        <span id="ia-gen-btn-loader" class="d-none"><span class="spinner-border spinner-border-sm"></span> Génération en cours…</span>
                    </button>
                    <div id="ia-gen-result" class="d-none" style="margin-top:1rem;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                            <span style="font-weight:700;font-size:.82rem;color:var(--accent);">✅ Page générée — cliquez sur Appliquer pour l'utiliser</span>
                            <button type="button" onclick="appliquerResultatIA()"
                                style="background:var(--accent);color:#fff;border:none;border-radius:10px;padding:6px 16px;font-weight:700;font-size:.8rem;cursor:pointer;">
                                Appliquer tout
                            </button>
                        </div>
                        <div id="ia-preview-content" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:12px;padding:1rem;font-size:.82rem;color:var(--text-2);max-height:300px;overflow-y:auto;"></div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description *</label>
                <div id="quill-editor"></div>
                <textarea name="description" id="description-hidden" class="d-none">{{ old('description') }}</textarea>
            </div>

            {{-- Wizard Nav --}}
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-description">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez écrire une description d'au moins 20 caractères.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Visuel &amp; Design &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- SECTION : Visuel --}}
        <div class="section-block" id="section-visuel">
            <div class="section-title">Visuel & Design</div>
            <div class="section-subtitle">L'image de couverture de votre produit</div>

            <div class="mb-4">
                <label class="form-label">Image de couverture</label>
                <div class="image-upload-area" onclick="document.getElementById('image-input').click()">
                    <div id="upload-placeholder">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <div class="fw-semibold">Cliquez pour téléverser une image</div>
                        <div class="text-muted small mt-1">JPG, PNG, WEBP — Recommandé: 1200×800px</div>
                    </div>
                    <div class="image-preview" id="image-preview">
                        <img id="preview-img" src="" alt="Aperçu">
                    </div>
                    <input type="file" id="image-input" name="image"
                           accept="image/*" class="d-none"
                           onchange="previewImage(this)">
                </div>
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Wizard Nav --}}
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-visuel">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez téléverser une image de couverture.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Enregistrer &nbsp;<i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════
       WIZARD – configuration
    ═══════════════════════════════════════════════ */
    var SECTIONS = ['informations','tarification','fichiers','description','visuel'];
    var REQUIRED  = ['informations','tarification','fichiers','description','visuel'];
    var currentSection = 'informations';
    var sectionStatus  = {};           // 'pending' | 'valid' | 'invalid'
    SECTIONS.forEach(function(s){ sectionStatus[s] = 'pending'; });

    /* ── validation rules ── */
    function validateSection(section) {
        if (section === 'informations') {
            // Catégorie facultative (une boutique peut ne pas en avoir).
            var nom = (document.getElementById('nom-input') || {}).value || '';
            return nom.trim().length > 0;
        }
        if (section === 'tarification') {
            var type = (document.querySelector('input[name="type"]:checked') || {}).value || 'payant';
            if (type === 'gratuit') return true;
            var prix = parseFloat((document.getElementById('prix-input') || {}).value);
            return !isNaN(prix) && prix > 0;
        }
        if (section === 'fichiers') {
            // Une formation ou une licence n'exige pas de fichier téléchargeable.
            var fmt = (document.querySelector('input[name="format"]:checked') || {}).value || 'fichier';
            if (fmt === 'formation' || fmt === 'licence') return true;
            var fi = document.getElementById('fichier-input');
            return fi && fi.files.length > 0;
        }
        if (section === 'description') {
            var txt = (document.getElementById('description-hidden') || {}).value || '';
            // strip HTML tags and check there's actual text
            var plain = txt.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, '').trim();
            return plain.length >= 20;
        }
        if (section === 'visuel') {
            var img = document.getElementById('image-input');
            return img && img.files.length > 0;
        }
        return true;
    }

    /* ── show/hide section ── */
    function showSection(target) {
        currentSection = target;
        document.querySelectorAll('.sidebar-nav a').forEach(function(l){ l.classList.remove('active'); });
        var navEl = document.getElementById('nav-' + target);
        if (navEl) navEl.classList.add('active');
        document.querySelectorAll('.section-block').forEach(function(s){ s.classList.remove('active'); });
        var sectionEl = document.getElementById('section-' + target);
        if (sectionEl) sectionEl.classList.add('active');
        // scroll top on mobile
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ── clear error states ── */
    function clearErrors(section) {
        var errEl = document.getElementById('error-' + section);
        if (errEl) errEl.classList.remove('show');
        if (section === 'informations') {
            var nomEl = document.getElementById('nom-input');
            if (nomEl) nomEl.classList.remove('is-invalid-wizard');
            var grid = document.querySelector('.category-grid');
            if (grid) grid.classList.remove('grid-invalid');
        }
        if (section === 'tarification') {
            var prixEl = document.getElementById('prix-input');
            if (prixEl) prixEl.classList.remove('is-invalid-wizard');
        }
        if (section === 'fichiers') {
            var area = document.querySelector('#section-fichiers .image-upload-area');
            if (area) area.classList.remove('upload-invalid');
        }
        if (section === 'description') {
            var qlContainer = document.querySelector('.ql-container');
            if (qlContainer) qlContainer.classList.remove('is-invalid-wizard');
        }
        if (section === 'visuel') {
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.remove('upload-invalid');
        }
    }

    /* ── highlight missing fields ── */
    function showErrors(section) {
        var errEl = document.getElementById('error-' + section);
        if (errEl) errEl.classList.add('show');
        if (section === 'informations') {
            var nom = document.getElementById('nom-input');
            if (nom && !nom.value.trim()) { nom.classList.add('is-invalid-wizard'); nom.focus(); }
        }
        if (section === 'tarification') {
            var type = (document.querySelector('input[name="type"]:checked') || {}).value || 'payant';
            if (type === 'payant') {
                var prixEl = document.getElementById('prix-input');
                if (prixEl) { prixEl.classList.add('is-invalid-wizard'); prixEl.focus(); }
            }
        }
        if (section === 'fichiers') {
            var area = document.querySelector('#section-fichiers .image-upload-area');
            if (area) area.classList.add('upload-invalid');
        }
        if (section === 'description') {
            var qlContainer = document.querySelector('.ql-container');
            if (qlContainer) { qlContainer.classList.add('is-invalid-wizard'); quill.focus(); }
        }
        if (section === 'visuel') {
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.add('upload-invalid');
        }
    }

    /* ── progress bar & badges ── */
    function updateProgress() {
        var valid = 0;
        REQUIRED.forEach(function(s) {
            var status = sectionStatus[s];
            var badge  = document.getElementById('badge-' + s);
            var icon   = document.getElementById('icon-' + s);
            if (status === 'valid') {
                valid++;
                if (badge) { badge.textContent = '✓'; badge.style.display = ''; badge.className = 'step-badge valid'; }
                if (icon)  { icon.className = 'step-icon valid'; }
            } else if (status === 'invalid') {
                if (badge) { badge.textContent = '!'; badge.style.display = ''; badge.className = 'step-badge invalid'; }
                if (icon)  { icon.className = 'step-icon invalid'; }
            } else {
                if (badge) { badge.style.display = 'none'; }
                if (icon)  { icon.className = 'step-icon'; }
            }
        });
        var pct = Math.round((valid / REQUIRED.length) * 100);
        var bar = document.getElementById('progress-bar');
        if (bar) bar.style.width = pct + '%';
        var lbl = document.getElementById('progress-label');
        if (lbl) lbl.textContent = valid + ' / ' + REQUIRED.length + ' requis';

        // enable/disable Publier
        var btnPublish = document.getElementById('btn-publish');
        if (btnPublish) {
            var allValid = REQUIRED.every(function(s){ return sectionStatus[s] === 'valid'; });
            btnPublish.disabled = !allValid;
            btnPublish.title    = allValid ? '' : 'Complétez les 5 sections requises pour publier';
        }
    }

    /* ── public nav functions ── */
    window.nextSection = function () {
        var idx = SECTIONS.indexOf(currentSection);
        clearErrors(currentSection);

        if (REQUIRED.indexOf(currentSection) !== -1) {
            var ok = validateSection(currentSection);
            sectionStatus[currentSection] = ok ? 'valid' : 'invalid';
            updateProgress();
            if (!ok) { showErrors(currentSection); return; }
        }
        if (idx < SECTIONS.length - 1) showSection(SECTIONS[idx + 1]);
    };

    window.prevSection = function () {
        var idx = SECTIONS.indexOf(currentSection);
        if (idx > 0) showSection(SECTIONS[idx - 1]);
    };

    /* ── sidebar nav click ── */
    document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = this.dataset.section;
            // Save current required status before leaving
            if (REQUIRED.indexOf(currentSection) !== -1 && sectionStatus[currentSection] === 'pending') {
                if (validateSection(currentSection)) {
                    sectionStatus[currentSection] = 'valid';
                    clearErrors(currentSection);
                    updateProgress();
                }
            }
            showSection(target);
        });
    });

    /* ── category selection ── */
    document.querySelectorAll('.category-item').forEach(function(item) {
        item.addEventListener('click', function() {
            document.querySelectorAll('.category-item').forEach(function(i){ i.classList.remove('selected'); });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            // clear error if was invalid
            var grid = document.querySelector('.category-grid');
            if (grid) grid.classList.remove('grid-invalid');
            // live recheck
            if (currentSection === 'informations' && sectionStatus['informations'] === 'invalid') {
                if (validateSection('informations')) {
                    sectionStatus['informations'] = 'valid';
                    clearErrors('informations');
                    updateProgress();
                }
            }
        });
    });

    /* ── nom input live recheck ── */
    var nomInput = document.getElementById('nom-input');
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            document.getElementById('sidebar-title').textContent = this.value || 'Sans titre';
            this.classList.remove('is-invalid-wizard');
            if (sectionStatus['informations'] === 'invalid' && validateSection('informations')) {
                sectionStatus['informations'] = 'valid';
                clearErrors('informations');
                updateProgress();
            }
        });
    }

    /* ── prix input live recheck ── */
    var prixInput = document.getElementById('prix-input');
    if (prixInput) {
        prixInput.addEventListener('input', function() {
            this.classList.remove('is-invalid-wizard');
            if (sectionStatus['tarification'] === 'invalid' && validateSection('tarification')) {
                sectionStatus['tarification'] = 'valid';
                clearErrors('tarification');
                updateProgress();
            }
        });
    }

    /* ── fichier input live recheck ── */
    var fichierInput = document.getElementById('fichier-input');
    if (fichierInput) {
        fichierInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                var area = document.querySelector('#section-fichiers .image-upload-area');
                if (area) area.classList.remove('upload-invalid');
                sectionStatus['fichiers'] = 'valid';
                clearErrors('fichiers');
                updateProgress();
            }
            // show file name
            if (this.files && this.files[0]) {
                var el = document.getElementById('fichier-name');
                if (el) { el.querySelector('span').textContent = this.files[0].name; el.classList.remove('d-none'); }
            }
        });
    }

    /* ── champ toggle checkboxes ── */
    document.querySelectorAll('.champ-toggle').forEach(function(label) {
        label.addEventListener('click', function() {
            var cb = this.querySelector('input[type="checkbox"]');
            cb.checked = !cb.checked;
            this.classList.toggle('active', cb.checked);
        });
    });

    /* ── initialize ── */
    updateProgress();

    /* ═══════════════════════════════════════════════
       QUILL EDITOR
    ═══════════════════════════════════════════════ */
    function imageHandler() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        input.onchange = function() {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', e.target.result);
                quill.setSelection(range.index + 1);
            };
            reader.readAsDataURL(file);
        };
    }

    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Décrivez votre produit ici...',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['blockquote'],
                    ['clean']
                ],
                handlers: { image: imageHandler }
            }
        }
    });

    quill.on('text-change', function() {
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        // live recheck
        var qlC = document.querySelector('.ql-container');
        if (qlC) qlC.classList.remove('is-invalid-wizard');
        if (sectionStatus['description'] === 'invalid' && validateSection('description')) {
            sectionStatus['description'] = 'valid';
            clearErrors('description');
            updateProgress();
        }
    });

    var existing = document.getElementById('description-hidden').value;
    if (existing) quill.root.innerHTML = existing;

    /* ═══════════════════════════════════════════════
       IMAGE & FILE HELPERS
    ═══════════════════════════════════════════════ */
    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
            // live recheck
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.remove('upload-invalid');
            sectionStatus['visuel'] = 'valid';
            clearErrors('visuel');
            updateProgress();
        }
    };

    window.showFileName = function(input) {
        if (input.files && input.files[0]) {
            var el = document.getElementById('fichier-name');
            if (el) { el.querySelector('span').textContent = input.files[0].name; el.classList.remove('d-none'); }
        }
    };

    /* ═══════════════════════════════════════════════
       TYPE PAYANT / GRATUIT
    ═══════════════════════════════════════════════ */
    window.switchType = function(type) {
        document.querySelectorAll('.type-card').forEach(function(c){ c.classList.remove('selected'); });
        document.getElementById('card-' + type).classList.add('selected');
        if (type === 'gratuit') {
            document.getElementById('bloc-payant').style.display = 'none';
            document.getElementById('bloc-gratuit').style.display = '';
            document.getElementById('prix-input').removeAttribute('required');
            // gratuit → tarification always valid
            sectionStatus['tarification'] = 'valid';
            updateProgress();
        } else {
            document.getElementById('bloc-payant').style.display = '';
            document.getElementById('bloc-gratuit').style.display = 'none';
            document.getElementById('prix-input').setAttribute('required', 'required');
            // re-evaluate
            if (sectionStatus['tarification'] !== 'pending') {
                sectionStatus['tarification'] = validateSection('tarification') ? 'valid' : 'invalid';
                updateProgress();
            }
        }
    };

    /* ── Limite de téléchargements ── */
    window.toggleLimite = function(checkbox) {
        document.getElementById('bloc-limite').style.display = checkbox.checked ? '' : 'none';
    };

    /* ═══════════════════════════════════════════════
       SAVE / PUBLISH
    ═══════════════════════════════════════════════ */
    function forcePrixGratuit() {
        var typeChecked = document.querySelector('input[name="type"]:checked');
        var prixInput   = document.getElementById('prix-input');
        if (typeChecked && typeChecked.value === 'gratuit' && prixInput) {
            prixInput.value = '0';
        }
    }

    window.saveDraft = function() {
        forcePrixGratuit();
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        document.getElementById('est_publie_check').checked = false;
        document.getElementById('produit-form').submit();
    };

    window.publish = function() {
        // Final validation check
        var missing = [];
        REQUIRED.forEach(function(s){
            if (!validateSection(s)) {
                sectionStatus[s] = 'invalid';
                missing.push(s);
            } else {
                sectionStatus[s] = 'valid';
            }
        });
        updateProgress();
        if (missing.length > 0) {
            showSection(missing[0]);
            showErrors(missing[0]);
            return;
        }
        forcePrixGratuit();
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        document.getElementById('est_publie_check').checked = true;
        document.getElementById('produit-form').submit();
    };

})();

/* ══════════════════════════════════════════════════
   FEATURE 1 — Générateur de page de vente IA
══════════════════════════════════════════════════ */
const IA_GEN_URL    = "{{ route('admin.ia.generer-page-vente') }}";
const IA_CSRF       = document.querySelector('meta[name="csrf-token"]')?.content;
let iaResultatData  = null;

function toggleIaGenerator() {
    const body = document.getElementById('ia-gen-body');
    const btn  = document.getElementById('ia-gen-toggle-btn');
    const open = body.style.display === 'none';
    body.style.display = open ? 'block' : 'none';
    btn.classList.toggle('open', open);
}

async function lancerGenerationIA() {
    const brief = document.getElementById('ia-brief').value.trim();
    if (!brief || brief.length < 10) {
        document.getElementById('ia-brief').focus();
        return;
    }

    const categorie = document.querySelector('input[name="categorie_id"]:checked')
        ?.closest('label')?.querySelector('span')?.textContent || '';
    const type = document.querySelector('#type_gratuit')?.checked ? 'gratuit' : 'payant';

    const btnText   = document.getElementById('ia-gen-btn-text');
    const btnLoader = document.getElementById('ia-gen-btn-loader');
    const btn       = document.getElementById('ia-gen-btn');

    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    btn.disabled = true;

    try {
        const res = await fetch(IA_GEN_URL, {
            method: 'POST',
            headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':IA_CSRF,'Accept':'application/json' },
            body: JSON.stringify({ description: brief, categorie, type }),
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);

        iaResultatData = data;
        afficherPreviewIA(data);
        document.getElementById('ia-gen-result').classList.remove('d-none');

    } catch (e) {
        alert('Erreur lors de la génération : ' + e.message);
    } finally {
        btnText.classList.remove('d-none');
        btnLoader.classList.add('d-none');
        btn.disabled = false;
    }
}

function afficherPreviewIA(data) {
    const preview = document.getElementById('ia-preview-content');
    const bullets = (data.bullets || []).map(b => `<li>${b}</li>`).join('');
    const faq = (data.faq || []).map(f =>
        `<div style="margin-bottom:8px;"><strong style="color:var(--text-1);">${f.question}</strong><br><span style="color:var(--text-3);">${f.reponse}</span></div>`
    ).join('');

    preview.innerHTML = `
        <div style="margin-bottom:8px;"><strong style="color:var(--accent);">Titre :</strong> ${data.titre}</div>
        <div style="margin-bottom:8px;"><strong style="color:var(--accent);">Sous-titre :</strong> ${data.sous_titre}</div>
        <div style="margin-bottom:8px;"><strong style="color:var(--accent);">Bénéfices :</strong><ul style="margin:4px 0 0 16px;">${bullets}</ul></div>
        <div style="margin-bottom:8px;"><strong style="color:var(--accent);">FAQ :</strong><div style="margin-top:6px;">${faq}</div></div>
        <div style="margin-bottom:4px;"><strong style="color:var(--accent);">CTA :</strong> ${data.cta}</div>
        <div><strong style="color:var(--accent);">Urgence :</strong> ${data.urgence}</div>
    `;
}

function appliquerResultatIA() {
    if (!iaResultatData) return;

    // Remplir le nom du produit si vide
    const nomInput = document.getElementById('nom-input');
    if (nomInput && !nomInput.value.trim()) {
        nomInput.value = iaResultatData.titre;
        nomInput.dispatchEvent(new Event('input'));
    }

    // Injecter la description complète dans Quill
    if (quill && iaResultatData.description_html) {
        const bulletsHtml = (iaResultatData.bullets || []).map(b => `<li>${b}</li>`).join('');
        const faqHtml = (iaResultatData.faq || []).map(f =>
            `<p><strong>${f.question}</strong><br>${f.reponse}</p>`
        ).join('');

        const html = `
            <h3>${iaResultatData.titre}</h3>
            <p><em>${iaResultatData.sous_titre}</em></p>
            ${iaResultatData.description_html}
            <h3>Ce que vous allez obtenir</h3>
            <ul>${bulletsHtml}</ul>
            <h3>Questions fréquentes</h3>
            ${faqHtml}
            <p><strong>⚠️ ${iaResultatData.urgence}</strong></p>
        `;
        quill.root.innerHTML = html;
        document.getElementById('description-hidden').value = html;
    }

    // Fermer le générateur
    document.getElementById('ia-gen-body').style.display = 'none';
    document.getElementById('ia-gen-toggle-btn').classList.remove('open');

    // Naviguer vers description pour voir le résultat
    if (typeof showSection === 'function') showSection('description');
}
</script>
@endpush