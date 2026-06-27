@extends('layouts.admin')

@section('title', 'Modifier — ' . $produit->nom)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
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
    .section-block { display: none; }
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

    /* ── Override dark-theme form styles inside the light editor ── */
    .editor-main .form-control,
    .editor-main .form-select,
    .editor-sidebar .form-control,
    .editor-sidebar .form-select {
        color: #1a1a2e !important;
        background: #ffffff !important;
        border: 1.5px solid #e0e0e0 !important;
    }
    .editor-main .form-control:focus,
    .editor-main .form-select:focus {
        border-color: #0f172a !important;
        box-shadow: 0 0 0 3px rgba(15,23,42,0.15) !important;
        background: #ffffff !important;
        color: #1a1a2e !important;
    }
    .editor-main .form-control::placeholder { color: #bbb !important; }
    .editor-main .form-label { color: #444 !important; }
    .editor-main .form-text { color: #888 !important; }
    .editor-main .input-group-text {
        background: #f8fafc !important;
        border-color: #e0e0e0 !important;
        color: #888 !important;
    }
    .editor-main .form-check-input {
        background-color: #fff !important;
        border-color: #ccc !important;
    }
    .editor-main .form-check-label { color: #444 !important; }
    .editor-main .badge.bg-success { background: #dcfce7 !important; color: #16a34a !important; }
    .editor-main .badge.bg-secondary { background: #e2e8f0 !important; color: #475569 !important; }
    .editor-main .text-muted { color: #888 !important; }

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
        background: #1e293b;
    }
    .btn-voir {
        background: white;
        color: #0f172a;
        border: 1.5px solid #0f172a;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-voir:hover { background: #0f172a; color: white; }
    .btn-toggle-publish {
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        border: 1.5px solid #e0e0e0;
        background: white;
        color: #555;
        cursor: pointer;
    }
    .btn-toggle-publish:hover { border-color: #94a3b8; color: #334155; }
    .btn-toggle-publish.is-published { border-color: #ef4444; color: #dc2626; }
    .btn-toggle-publish.is-published:hover { background: #fef2f2; }
    .btn-toggle-publish.is-draft { border-color: #16a34a; color: #15803d; }
    .btn-toggle-publish.is-draft:hover { background: #f0fdf4; }

    /* ── Existing file/image pill ── */
    .existing-file-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-radius: 10px; padding: 0.6rem 1rem;
        font-size: 0.85rem; font-weight: 500; color: #475569;
        margin-bottom: 1rem;
    }
    .existing-file-pill i { color: #64748b; }

    /* ── Advanced cards ── */
    .advanced-card {
        background: white;
        border: 1.5px solid #e8e8e8;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .advanced-card-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 1.2rem;
    }
    .advanced-card-body { flex: 1; }
    .advanced-card-title { font-weight: 700; color: #1a1a2e; font-size: 0.95rem; margin-bottom: 2px; }
    .advanced-card-sub { font-size: 0.82rem; color: #64748b; }
    .btn-advanced {
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1.5px solid #e0e0e0;
        background: white;
        color: #334155;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-advanced:hover { border-color: #0f172a; color: #0f172a; }

    /* ── Link display ── */
    .link-display {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }
    .link-display input {
        flex: 1;
        border: none;
        border-radius: 0;
        background: #f8fafc;
        font-size: 0.85rem;
        color: #475569;
        padding: 0.65rem 1rem;
    }
    .link-display input:focus { box-shadow: none; border: none; }
    .link-display .link-btn {
        border: none;
        border-left: 1.5px solid #e0e0e0;
        background: white;
        color: #555;
        padding: 0.65rem 0.85rem;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    .link-display .link-btn:hover { background: #f8fafc; color: #0f172a; }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .product-editor-layout {
            flex-direction: column;
            border-radius: 14px;
            min-height: auto;
        }
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
        .editor-main { padding: 1rem; }
    }
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
        .btn-save, .btn-toggle-publish, .btn-voir { flex: 1; text-align: center; justify-content: center; }
        .section-title { font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')

@php $copub = $produit->copublicationActive; @endphp

<form action="{{ route('admin.produits.update', $produit) }}" method="POST" enctype="multipart/form-data" id="produit-form">
@csrf
@method('PUT')

{{-- Barre supérieure --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.produits.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <h1 class="h5 mb-0 fw-bold" id="page-title">{{ $produit->nom }}</h1>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <a href="{{ url('/boutique/produits/' . $produit->slug) }}" target="_blank" class="btn-voir">
            <i class="fas fa-eye me-1"></i> Voir
        </a>

        {{-- Toggle Publier / Dépublier --}}
        <input type="hidden" name="est_publie" id="est_publie_hidden" value="{{ old('est_publie', $produit->est_publie) ? '1' : '0' }}">
        <button type="button" id="btn-toggle-publish"
            class="btn-toggle-publish {{ $produit->est_publie ? 'is-published' : 'is-draft' }}"
            onclick="togglePublish()">
            @if($produit->est_publie)
                <i class="fas fa-eye-slash me-1"></i> Dépublier
            @else
                <i class="fas fa-check-circle me-1"></i> Publier
            @endif
        </button>

        <button type="submit" class="btn btn-save">
            <i class="fas fa-save me-1"></i> Enregistrer
        </button>
    </div>
</div>

<div class="product-editor-layout">

    {{-- Sidebar --}}
    <div class="editor-sidebar">
        <div class="product-title-preview">
            <div class="text-muted small mb-1">Produit</div>
            <h6 id="sidebar-title">{{ $produit->nom }}</h6>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="#" class="active" data-section="informations">
                    <i class="fas fa-info-circle"></i>
                    <span>Informations</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="tarification">
                    <i class="fas fa-tag"></i>
                    <span>Tarification</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="fichiers">
                    <i class="fas fa-file-alt"></i>
                    <span>Fichiers</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="description">
                    <i class="fas fa-align-left"></i>
                    <span>Description</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="visuel">
                    <i class="fas fa-image"></i>
                    <span>Visuel &amp; Design</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="avance">
                    <i class="fas fa-sliders-h"></i>
                    <span>Avancé</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- Contenu principal --}}
    <div class="editor-main">

        {{-- ═══ SECTION : Informations ═══ --}}
        <div class="section-block active" id="section-informations">
            <div class="section-title">Informations</div>
            <div class="section-subtitle">Les informations essentielles de votre produit</div>

            {{-- Nom --}}
            <div class="mb-4">
                <label class="form-label">Nom du produit *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                       name="nom" id="nom-input"
                       placeholder="Ex: Formation Excel Complète"
                       value="{{ old('nom', $produit->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catégorie --}}
            <div class="mb-4">
                <label class="form-label">Catégorie *</label>
                <p class="text-muted small mb-3">Choisissez la catégorie qui correspond le mieux à votre produit</p>
                @php
                $icons = [
                    'Formation & Éducation' => 'fas fa-graduation-cap',
                    'Livres & Ebooks'       => 'fas fa-book',
                    'Templates & Modèles'   => 'fas fa-file-alt',
                    'Audio & Musique'       => 'fas fa-music',
                    'Vidéo & Film'          => 'fas fa-film',
                    'Logiciels & Outils'    => 'fas fa-tools',
                    'Art & Design'          => 'fas fa-paint-brush',
                    'Business & Marketing'  => 'fas fa-chart-line',
                    'Santé & Bien-être'     => 'fas fa-heartbeat',
                    'Autre'                 => 'fas fa-ellipsis-h',
                ];
                $selectedCat = old('categorie_id', $produit->categorie_id);
                @endphp
                <div class="category-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem;">
                    @foreach($categories as $categorie)
                    <label class="category-item {{ $selectedCat == $categorie->id ? 'selected' : '' }}"
                           style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:1rem;border:2px solid {{ $selectedCat == $categorie->id ? '#0f172a' : '#e8e8e8' }};border-radius:14px;cursor:pointer;transition:all .2s;text-align:center;background:{{ $selectedCat == $categorie->id ? 'rgba(15,23,42,0.07)' : 'white' }};">
                        <input type="radio" name="categorie_id" value="{{ $categorie->id }}"
                               {{ $selectedCat == $categorie->id ? 'checked' : '' }}
                               style="display:none;">
                        <i class="{{ $icons[$categorie->nom] ?? 'fas fa-tag' }}"
                           style="font-size:1.5rem;color:{{ $selectedCat == $categorie->id ? '#0f172a' : '#888' }};"></i>
                        <span style="font-size:.8rem;font-weight:600;color:{{ $selectedCat == $categorie->id ? '#0f172a' : '#444' }};line-height:1.2;">{{ $categorie->nom }}</span>
                    </label>
                    @endforeach
                </div>
                @error('categorie_id')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            {{-- Statut --}}
            <div class="mb-4">
                <label class="form-label">Statut de publication</label>
                <div class="d-flex gap-3 align-items-center">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="est_publie_check"
                               {{ old('est_publie', $produit->est_publie) ? 'checked' : '' }}
                               onchange="syncPublishToggle(this.checked)">
                        <label class="form-check-label fw-semibold" for="est_publie_check">
                            {{ $produit->est_publie ? 'Publié' : 'Brouillon' }}
                        </label>
                    </div>
                    @if($produit->est_publie)
                        <span class="badge bg-success">En ligne</span>
                    @else
                        <span class="badge bg-secondary">Brouillon</span>
                    @endif
                </div>
            </div>

            {{-- Lien partageable --}}
            <div class="mb-4">
                <label class="form-label">Lien de la page produit</label>
                <div class="link-display">
                    <input type="text" id="produit-link"
                           value="{{ url('/boutique/produits/' . $produit->slug) }}"
                           readonly>
                    <button type="button" class="link-btn" onclick="copyLink()" title="Copier le lien">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="{{ url('/boutique/produits/' . $produit->slug) }}" target="_blank"
                       class="link-btn" title="Ouvrir dans un nouvel onglet">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ═══ SECTION : Tarification ═══ --}}
        <div class="section-block" id="section-tarification">
            <div class="section-title">Tarification</div>
            <div class="section-subtitle">Mode de distribution et prix de votre produit</div>

            {{-- Choix du type --}}
            <div class="mb-4">
                <div class="row g-3" style="max-width:560px;">
                    {{-- Payant --}}
                    <div class="col-6">
                        <label class="type-card {{ old('type', $produit->type) === 'payant' ? 'selected' : '' }}" id="card-payant">
                            <input type="radio" name="type" value="payant"
                                   {{ old('type', $produit->type) === 'payant' ? 'checked' : '' }}
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
                        <label class="type-card {{ old('type', $produit->type) === 'gratuit' ? 'selected' : '' }}" id="card-gratuit">
                            <input type="radio" name="type" value="gratuit"
                                   {{ old('type', $produit->type) === 'gratuit' ? 'checked' : '' }}
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
            <div id="bloc-payant" style="{{ old('type', $produit->type) === 'gratuit' ? 'display:none;' : '' }}">
                <div class="mb-4" style="max-width:300px;">
                    <label class="form-label">Prix *</label>
                    <div class="price-input-wrapper">
                        <input type="number" step="1" id="prix-input"
                               class="form-control @error('prix') is-invalid @enderror"
                               name="prix" placeholder="0"
                               value="{{ old('prix', $produit->prix) }}">
                        <span class="currency">FCFA</span>
                    </div>
                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4" style="max-width:300px;">
                    <label class="form-label">Prix promotionnel <span class="text-muted fw-normal">(optionnel)</span></label>
                    <div class="price-input-wrapper">
                        <input type="number" step="1"
                               class="form-control @error('prix_promo') is-invalid @enderror"
                               name="prix_promo" placeholder="0"
                               value="{{ old('prix_promo', $produit->prix_promo ?? '') }}">
                        <span class="currency">FCFA</span>
                    </div>
                    @error('prix_promo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Laissez vide pour ne pas afficher de promo.</div>
                </div>
            </div>

            {{-- Bloc Gratuit / Lead Magnet --}}
            <div id="bloc-gratuit" style="{{ old('type', $produit->type) !== 'gratuit' ? 'display:none;' : '' }}">

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
                        @php $champActifs = old('lead_champs_requis', $produit->lead_champs_requis ?? []); @endphp
                        <div class="col-6">
                            <label class="champ-toggle {{ in_array($champ, $champActifs) ? 'active' : '' }}">
                                <input type="checkbox" name="lead_champs_requis[]" value="{{ $champ }}"
                                       {{ in_array($champ, $champActifs) ? 'checked' : '' }}>
                                <i class="fas fa-{{ $champ === 'telephone' ? 'phone' : ($champ === 'ville' ? 'map-marker-alt' : ($champ === 'profession' ? 'briefcase' : 'globe')) }} me-2"></i>
                                {{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Limite de téléchargements --}}
                <div class="mb-4">
                    <label class="form-label">Limite de téléchargements <span class="text-muted fw-normal">(optionnel)</span></label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggle-limite"
                                   onchange="toggleLimite(this)"
                                   {{ old('lead_limite_dl', $produit->lead_limite_dl) ? 'checked' : '' }}>
                            <label class="form-check-label" for="toggle-limite">Activer une limite</label>
                        </div>
                    </div>
                    <div id="bloc-limite" class="mt-3"
                         style="{{ old('lead_limite_dl', $produit->lead_limite_dl) ? '' : 'display:none;' }}">
                        <div class="input-group" style="max-width:250px;">
                            <input type="number" name="lead_limite_dl" min="1"
                                   class="form-control rounded-start-3"
                                   placeholder="Ex: 200"
                                   value="{{ old('lead_limite_dl', $produit->lead_limite_dl) }}">
                            <span class="input-group-text">téléchargements</span>
                        </div>
                        <div class="form-text">Exemple : "200 téléchargements seulement" — après ça le produit se ferme automatiquement.</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══ SECTION : Fichiers ═══ --}}
        <div class="section-block" id="section-fichiers">
            <div class="section-title">Livraison du produit</div>
            <div class="section-subtitle">Un simple fichier à télécharger, ou une formation avec espace membre</div>

            {{-- Formation : espace membre --}}
            <div style="border:1px solid {{ $produit->estFormation() ? '#c7d2fe' : '#e9eaeb' }};background:{{ $produit->estFormation() ? '#eef2ff' : '#fafafa' }};border-radius:14px;padding:1.1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <div style="width:44px;height:44px;border-radius:11px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;flex-shrink:0;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div style="flex:1;min-width:180px;">
                    <div style="font-weight:700;color:#111827;">Formation en ligne (espace membre)</div>
                    <div style="font-size:0.82rem;color:#6b7280;">
                        @if($produit->estFormation())
                            Ce produit est une formation — {{ $produit->modules()->count() }} module(s), {{ $produit->nbLecons() }} leçon(s).
                        @else
                            Proposez des vidéos (lien ou upload), modules et suivi de progression au lieu d'un simple fichier.
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.produits.formation.programme', $produit) }}"
                   style="background:#4f46e5;color:#fff;text-decoration:none;padding:0.6rem 1.1rem;border-radius:10px;font-weight:700;font-size:0.85rem;white-space:nowrap;">
                    <i class="fas fa-{{ $produit->estFormation() ? 'pen' : 'plus' }}"></i>
                    {{ $produit->estFormation() ? 'Gérer le programme' : 'Créer une formation' }}
                </a>
            </div>

            {{-- Type d'accès : paiement unique ou abonnement --}}
            <div style="border:1px solid #e9eaeb;border-radius:14px;padding:1.1rem 1.25rem;margin-bottom:1.25rem;">
                <label class="form-label" style="font-weight:700;">💳 Type d'accès</label>
                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:0.4rem;">
                    <label style="flex:1;min-width:200px;border:1.5px solid {{ ($produit->acces_type ?? 'unique')==='unique' ? '#4f46e5':'#e5e7eb' }};border-radius:10px;padding:0.7rem 0.9rem;cursor:pointer;display:flex;align-items:center;gap:9px;">
                        <input type="radio" name="acces_type" value="unique" onchange="toggleAbo()" {{ ($produit->acces_type ?? 'unique')==='unique' ? 'checked':'' }}>
                        <span><strong>Paiement unique</strong><br><span class="text-muted small">Le client paie une fois, accès à vie</span></span>
                    </label>
                    <label style="flex:1;min-width:200px;border:1.5px solid {{ ($produit->acces_type ?? '')==='abonnement' ? '#4f46e5':'#e5e7eb' }};border-radius:10px;padding:0.7rem 0.9rem;cursor:pointer;display:flex;align-items:center;gap:9px;">
                        <input type="radio" name="acces_type" value="abonnement" onchange="toggleAbo()" {{ ($produit->acces_type ?? '')==='abonnement' ? 'checked':'' }}>
                        <span><strong>Abonnement</strong><br><span class="text-muted small">Accès récurrent, à renouveler</span></span>
                    </label>
                </div>
                <div id="abo-intervalle" style="margin-top:0.8rem;{{ ($produit->acces_type ?? '')==='abonnement' ? '':'display:none;' }}">
                    <label class="form-label">Périodicité de l'abonnement</label>
                    <select name="abonnement_intervalle" class="form-select" style="max-width:240px;">
                        <option value="mensuel" {{ ($produit->abonnement_intervalle ?? 'mensuel')==='mensuel' ? 'selected':'' }}>Mensuel (30 jours)</option>
                        <option value="annuel"  {{ ($produit->abonnement_intervalle ?? '')==='annuel' ? 'selected':'' }}>Annuel (12 mois)</option>
                    </select>
                    <div class="text-muted small mt-1">Le prix ci-dessus est facturé à chaque période. Le client reçoit un rappel avant l'échéance.</div>
                </div>
            </div>
            <script>
                function toggleAbo(){
                    var abo = document.querySelector('input[name=acces_type]:checked')?.value === 'abonnement';
                    document.getElementById('abo-intervalle').style.display = abo ? '' : 'none';
                }
            </script>

            <div class="mb-4" @if($produit->estFormation()) style="opacity:0.6;" @endif>
                <label class="form-label">Fichier numérique (PDF, ZIP, MP4...) <span class="text-muted small">— optionnel si formation</span></label>

                @if($produit->fichier)
                    <div class="existing-file-pill">
                        <i class="fas fa-file-check"></i>
                        <span>Fichier actuel :</span>
                        <a href="{{ route('admin.produits.fichier', $produit) }}" target="_blank"
                           class="text-primary fw-semibold" style="font-size:0.82rem;">
                            {{ basename($produit->fichier) }}
                        </a>
                        <span class="text-muted" style="font-size:0.75rem;">— remplacez-le ci-dessous si besoin</span>
                    </div>
                @endif

                <div class="image-upload-area" onclick="document.getElementById('fichier-input').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <div class="fw-semibold">{{ $produit->fichier ? 'Remplacer le fichier' : 'Cliquez pour téléverser' }}</div>
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
        </div>

        {{-- ═══ SECTION : Description ═══ --}}
        <div class="section-block" id="section-description">
            <div class="section-title">Description</div>
            <div class="section-subtitle">Décrivez votre produit pour convaincre vos clients</div>

            {{-- Feature 6 : Traduction & adaptation culturelle --}}
            <div style="background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(59,130,246,.05));border:1.5px solid rgba(16,185,129,.25);border-radius:16px;padding:1.1rem 1.25rem;margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:.75rem;">
                    <span style="font-size:1.4rem;">🌍</span>
                    <div>
                        <div style="font-weight:700;font-size:.88rem;color:#10b981;">Traduire & adapter culturellement</div>
                        <div style="font-size:.73rem;color:var(--text-3);">Adaptez votre produit à d'autres marchés africains en 1 clic</div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <select id="trad-langue" style="border:1.5px solid rgba(16,185,129,.3);border-radius:10px;padding:7px 12px;font-size:.82rem;background:var(--bg-elevated);color:var(--text-1);outline:none;cursor:pointer;">
                        <option value="en">🇬🇧 Anglais africain</option>
                        <option value="sw">🇰🇪 Swahili</option>
                        <option value="ha">🇳🇬 Haoussa</option>
                        <option value="pt">🇦🇴 Portugais africain</option>
                        <option value="ar">🇲🇦 Arabe (Maghreb)</option>
                    </select>
                    <button type="button" id="trad-btn" onclick="lancerTraduction()"
                        style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:700;font-size:.82rem;cursor:pointer;display:flex;align-items:center;gap:7px;">
                        <span id="trad-btn-text">🌍 Traduire</span>
                        <span id="trad-btn-loader" class="d-none"><span class="spinner-border spinner-border-sm"></span></span>
                    </button>
                </div>
                <div id="trad-result" class="d-none" style="margin-top:.875rem;padding:.875rem;background:var(--bg-elevated);border-radius:12px;border:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;">
                        <span style="font-size:.78rem;font-weight:700;color:#10b981;">✅ Traduction prête</span>
                        <button type="button" onclick="appliquerTraduction()" style="background:#10b981;color:#fff;border:none;border-radius:8px;padding:4px 14px;font-weight:700;font-size:.75rem;cursor:pointer;">Appliquer</button>
                    </div>
                    <div style="font-size:.8rem;color:var(--text-2);">
                        <strong>Nom :</strong> <span id="trad-nom-preview"></span><br>
                        <strong>Note :</strong> <span id="trad-notes-preview" style="color:var(--text-3);"></span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <div id="quill-editor"></div>
                <textarea name="description" id="description-hidden" class="d-none">{{ old('description', $produit->description) }}</textarea>
            </div>
        </div>

        {{-- ═══ SECTION : Visuel & Design ═══ --}}
        <div class="section-block" id="section-visuel">
            <div class="section-title">Visuel &amp; Design</div>
            <div class="section-subtitle">L'image de couverture de votre produit</div>

            <div class="mb-4">
                <label class="form-label">Image de couverture</label>

                @if($produit->image)
                    <div class="mb-3">
                        <div class="text-muted small mb-2">Image actuelle :</div>
                        <img src="{{ route('media.produits.image', $produit) }}"
                             alt="{{ $produit->nom }}"
                             style="max-height:180px;border-radius:12px;border:1px solid #eee;object-fit:cover;">
                    </div>
                @endif

                <div class="image-upload-area" onclick="document.getElementById('image-input').click()">
                    <div id="upload-placeholder">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <div class="fw-semibold">{{ $produit->image ? 'Remplacer l\'image' : 'Cliquez pour téléverser une image' }}</div>
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
        </div>

        {{-- ═══ SECTION : Avancé ═══ --}}
        <div class="section-block" id="section-avance">
            <div class="section-title">Avancé</div>
            <div class="section-subtitle">Upsells et co-publication de ce produit</div>

            {{-- Upsells --}}
            <div class="advanced-card">
                <div class="advanced-card-icon" style="background:#fff4ed;color:#f97316;">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="advanced-card-body">
                    <div class="advanced-card-title">Upsells</div>
                    <div class="advanced-card-sub">
                        {{ $produit->upsells()->count() }} offre(s) configurée(s) — proposées après l'achat
                    </div>
                </div>
                <a href="{{ route('admin.produits.upsells.index', $produit) }}" class="btn-advanced">
                    Gérer
                </a>
            </div>

            {{-- Co-publication --}}
            <div class="advanced-card">
                <div class="advanced-card-icon" style="background:#f0f9ff;color:#0369a1;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="advanced-card-body">
                    <div class="advanced-card-title">Co-publication</div>
                    <div class="advanced-card-sub">
                        @if($copub)
                            Partenaire actif : <strong>{{ $copub->copublicateur->nom ?? '—' }}</strong>
                            — {{ $copub->pourcentage_copublicateur }}% reversé
                        @else
                            Aucun partenaire actif — invitez un co-éditeur
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.copublications.create', ['produit_id' => $produit->id]) }}"
                   class="btn-advanced">
                    {{ $copub ? 'Gérer' : 'Inviter' }}
                </a>
            </div>
        </div>

    </div>{{-- /.editor-main --}}
</div>{{-- /.product-editor-layout --}}

</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function () {
    'use strict';

    /* ── Tab navigation ── */
    function showSection(target) {
        document.querySelectorAll('.sidebar-nav a').forEach(function(l) { l.classList.remove('active'); });
        var navEl = document.querySelector('.sidebar-nav a[data-section="' + target + '"]');
        if (navEl) navEl.classList.add('active');
        document.querySelectorAll('.section-block').forEach(function(s) { s.classList.remove('active'); });
        var sectionEl = document.getElementById('section-' + target);
        if (sectionEl) sectionEl.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showSection(this.dataset.section);
        });
    });

    /* ── Category selection ── */
    document.querySelectorAll('.category-item').forEach(function(item) {
        item.addEventListener('click', function() {
            document.querySelectorAll('.category-item').forEach(function(i) {
                i.classList.remove('selected');
                i.style.border = '2px solid #e8e8e8';
                i.style.background = 'white';
                var icon = i.querySelector('i');
                var span = i.querySelector('span');
                if (icon) icon.style.color = '#888';
                if (span) span.style.color = '#444';
            });
            this.classList.add('selected');
            this.style.border = '2px solid #0f172a';
            this.style.background = 'rgba(15,23,42,0.07)';
            var icon = this.querySelector('i');
            var span = this.querySelector('span');
            if (icon) icon.style.color = '#0f172a';
            if (span) span.style.color = '#0f172a';
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    /* ── Nom input live ── */
    var nomInput = document.getElementById('nom-input');
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            var sidebarTitle = document.getElementById('sidebar-title');
            var pageTitle    = document.getElementById('page-title');
            var val = this.value || '{{ $produit->nom }}';
            if (sidebarTitle) sidebarTitle.textContent = val;
            if (pageTitle)    pageTitle.textContent    = val;
        });
    }

    /* ── Publish toggle ── */
    var publishState = {{ $produit->est_publie ? 'true' : 'false' }};

    window.togglePublish = function() {
        publishState = !publishState;
        var hidden = document.getElementById('est_publie_hidden');
        var check  = document.getElementById('est_publie_check');
        var btn    = document.getElementById('btn-toggle-publish');
        if (hidden) hidden.value = publishState ? '1' : '0';
        if (check)  check.checked = publishState;
        if (btn) {
            if (publishState) {
                btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Dépublier';
                btn.className = 'btn-toggle-publish is-published';
            } else {
                btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Publier';
                btn.className = 'btn-toggle-publish is-draft';
            }
        }
    };

    window.syncPublishToggle = function(checked) {
        publishState = checked;
        var hidden = document.getElementById('est_publie_hidden');
        var btn    = document.getElementById('btn-toggle-publish');
        if (hidden) hidden.value = checked ? '1' : '0';
        if (btn) {
            if (checked) {
                btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Dépublier';
                btn.className = 'btn-toggle-publish is-published';
            } else {
                btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Publier';
                btn.className = 'btn-toggle-publish is-draft';
            }
        }
    };

    /* ── Type Payant / Gratuit ── */
    window.switchType = function(type) {
        document.querySelectorAll('.type-card').forEach(function(c) { c.classList.remove('selected'); });
        document.getElementById('card-' + type).classList.add('selected');
        if (type === 'gratuit') {
            document.getElementById('bloc-payant').style.display = 'none';
            document.getElementById('bloc-gratuit').style.display = '';
        } else {
            document.getElementById('bloc-payant').style.display = '';
            document.getElementById('bloc-gratuit').style.display = 'none';
        }
    };

    /* ── Limite téléchargements ── */
    window.toggleLimite = function(checkbox) {
        document.getElementById('bloc-limite').style.display = checkbox.checked ? '' : 'none';
    };

    /* ── Champ toggle checkboxes ── */
    document.querySelectorAll('.champ-toggle').forEach(function(label) {
        label.addEventListener('click', function() {
            var cb = this.querySelector('input[type="checkbox"]');
            cb.checked = !cb.checked;
            this.classList.toggle('active', cb.checked);
        });
    });

    /* ── Copy link ── */
    window.copyLink = function() {
        var input = document.getElementById('produit-link');
        if (input) {
            navigator.clipboard.writeText(input.value).then(function() {
                var btn = document.querySelector('.link-display .link-btn');
                if (btn) {
                    var orig = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check text-success"></i>';
                    setTimeout(function() { btn.innerHTML = orig; }, 1500);
                }
            });
        }
    };

    /* ── Image preview ── */
    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    /* ── File name display ── */
    window.showFileName = function(input) {
        if (input.files && input.files[0]) {
            var el = document.getElementById('fichier-name');
            if (el) {
                el.querySelector('span').textContent = input.files[0].name;
                el.classList.remove('d-none');
            }
        }
    };

    /* ── Sync est_publie checkbox + prix → on form submit ── */
    document.getElementById('produit-form').addEventListener('submit', function() {
        var check  = document.getElementById('est_publie_check');
        var hidden = document.getElementById('est_publie_hidden');
        if (check && hidden) hidden.value = check.checked ? '1' : '0';

        // Si type gratuit → forcer prix à 0 (évite le doublon d'input qui écrase la valeur)
        var typeChecked = document.querySelector('input[name="type"]:checked');
        var prixInput   = document.getElementById('prix-input');
        if (typeChecked && typeChecked.value === 'gratuit' && prixInput) {
            prixInput.value = '0';
        }

        // sync description from quill
        if (typeof quill !== 'undefined') {
            document.getElementById('description-hidden').value = quill.root.innerHTML;
        }
    });

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

    // Load existing description
    var existing = document.getElementById('description-hidden').value;
    if (existing) quill.root.innerHTML = existing;

    quill.on('text-change', function() {
        document.getElementById('description-hidden').value = quill.root.innerHTML;
    });

})();

/* ── Feature 6 : Traduction ── */
const TRAD_URL  = "{{ route('admin.ia.traduire') }}";
const TRAD_CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
let tradResultat = null;

async function lancerTraduction() {
    const langue = document.getElementById('trad-langue').value;
    const nom    = document.querySelector('input[name="nom"]')?.value?.trim() || '';
    const desc   = document.getElementById('description-hidden')?.value || '';

    if (!nom) { alert('Veuillez d\'abord saisir le nom du produit.'); return; }

    const btnText   = document.getElementById('trad-btn-text');
    const btnLoader = document.getElementById('trad-btn-loader');
    const btn       = document.getElementById('trad-btn');
    btnText.classList.add('d-none'); btnLoader.classList.remove('d-none'); btn.disabled = true;

    try {
        const res  = await fetch(TRAD_URL, {
            method: 'POST',
            headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':TRAD_CSRF,'Accept':'application/json' },
            body: JSON.stringify({ nom, description: desc, langue }),
        });
        const data = await res.json();
        tradResultat = data;

        document.getElementById('trad-nom-preview').textContent   = data.nom || '';
        document.getElementById('trad-notes-preview').textContent = data.notes_adaptation || '';
        document.getElementById('trad-result').classList.remove('d-none');
    } catch(e) {
        alert('Erreur traduction : ' + e.message);
    } finally {
        btnText.classList.remove('d-none'); btnLoader.classList.add('d-none'); btn.disabled = false;
    }
}

function appliquerTraduction() {
    if (!tradResultat) return;
    const nomInput = document.querySelector('input[name="nom"]');
    if (nomInput && tradResultat.nom) nomInput.value = tradResultat.nom;
    if (quill && tradResultat.description) {
        quill.root.innerHTML = tradResultat.description;
        document.getElementById('description-hidden').value = tradResultat.description;
    }
    document.getElementById('trad-result').classList.add('d-none');
}
</script>
@endpush
