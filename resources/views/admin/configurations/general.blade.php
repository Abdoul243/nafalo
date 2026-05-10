@extends('layouts.admin')
@section('title', 'Configuration générale')
@push('styles')
<style>
    .config-header { margin-bottom:2rem; }
    .config-header h1 { font-size:1.4rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem; }
    .config-header p  { color:#64748b; font-size:0.875rem; }

    .config-card {
        background:white; border-radius:18px; border:1px solid #f1f5f9;
        box-shadow:0 2px 12px rgba(0,0,0,0.04); overflow:hidden; margin-bottom:1.5rem;
    }
    .config-card-header {
        padding:1.25rem 1.5rem; border-bottom:1px solid #f8fafc;
        display:flex; align-items:center; gap:12px;
        background:linear-gradient(90deg,#f8fafc,#fff);
    }
    .config-card-header .icon {
        width:38px; height:38px; border-radius:10px;
        background:#eff6ff; display:flex; align-items:center; justify-content:center;
        color:#2563eb; font-size:1rem; flex-shrink:0;
    }
    .config-card-header h5 { font-size:0.95rem; font-weight:700; color:#0f172a; margin:0; }
    .config-card-header p  { font-size:0.78rem; color:#94a3b8; margin:0; }
    .config-card-body { padding:1.75rem 1.5rem; }

    .form-label { font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.4rem; }
    .form-control, .form-select {
        border:1.5px solid #e2e8f0 !important; border-radius:11px !important;
        padding:0.75rem 1rem !important; font-size:0.9rem !important;
        transition:all 0.2s; color:#0f172a;
    }
    .form-control:focus, .form-select:focus {
        border-color:#2563eb !important;
        box-shadow:0 0 0 3px rgba(37,99,235,0.1) !important;
    }
    .input-group .input-group-text {
        border:1.5px solid #e2e8f0; border-right:none; border-radius:11px 0 0 11px;
        background:#f8fafc; color:#64748b; font-size:0.9rem;
    }
    .input-group .form-control { border-radius:0 11px 11px 0 !important; border-left:none !important; }

    .logo-preview {
        display:flex; align-items:center; gap:1rem;
        padding:1rem; background:#f8fafc; border-radius:12px; margin-bottom:1rem;
    }
    .logo-preview img { height:60px; border-radius:8px; object-fit:contain; background:white; padding:4px; }
    .logo-preview-text { font-size:0.82rem; color:#64748b; }

    .btn-save {
        background:linear-gradient(135deg,#2563eb,#1d4ed8);
        color:white; font-weight:700; border:none;
        border-radius:11px; padding:0.75rem 2rem;
        font-size:0.9rem; cursor:pointer; transition:all 0.2s;
        display:inline-flex; align-items:center; gap:8px;
    }
    .btn-save:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(37,99,235,0.3); }
    .btn-back {
        background:#f1f5f9; color:#64748b; border:none;
        border-radius:11px; padding:0.75rem 1.25rem;
        font-size:0.875rem; font-weight:600; text-decoration:none;
        display:inline-flex; align-items:center; gap:8px; transition:all 0.15s;
    }
    .btn-back:hover { background:#e2e8f0; color:#0f172a; }
    .field-hint { font-size:0.78rem; color:#94a3b8; margin-top:0.35rem; }

    /* ── Responsive ── */
    @media (max-width:640px) {
        .config-card-body { padding:1rem !important; }
        .config-card-header { padding:1rem; }
        .config-header .d-flex { flex-direction:column; align-items:flex-start !important; gap:0.75rem; }
        .btn-save, .btn-back { width:100%; justify-content:center; }
    }
</style>
@endpush

@section('content')

<div class="config-header">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1>Configuration générale</h1>
            <p>Personnalisez les informations et l'apparence de votre boutique</p>
        </div>
        <a href="{{ route('admin.configurations.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<form action="{{ route('admin.configurations.general') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Identité de la boutique --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-store"></i></div>
        <div>
            <h5>Identité de la boutique</h5>
            <p>Nom, description et informations principales</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="nom" class="form-label">Nom de la boutique <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                       id="nom" name="nom" value="{{ old('nom', $boutique->nom) }}"
                       placeholder="Ma Super Boutique" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3"
                          placeholder="Décrivez votre boutique en quelques mots...">{{ old('description', $boutique->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-address-card"></i></div>
        <div>
            <h5>Informations de contact</h5>
            <p>Email et téléphone affichés dans votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="email" class="form-label">Email de contact <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $boutique->email) }}"
                           placeholder="contact@maboutique.com" required>
                </div>
                @error('email')<div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="telephone" class="form-label">Téléphone</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                           id="telephone" name="telephone" value="{{ old('telephone', $boutique->telephone) }}"
                           placeholder="+225 07 00 00 00 00">
                </div>
                @error('telephone')<div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Logo --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-image"></i></div>
        <div>
            <h5>Logo de la boutique</h5>
            <p>Image affichée dans la navigation et le footer de votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        @if($boutique->logo)
        <div class="logo-preview">
            <img src="{{ asset('storage/' . $boutique->logo) }}" alt="Logo actuel">
            <div class="logo-preview-text">
                <div style="font-weight:600;color:#0f172a;margin-bottom:2px;">Logo actuel</div>
                <div>Uploadez un nouveau fichier pour le remplacer</div>
            </div>
        </div>
        @endif
        <input type="file" class="form-control @error('logo') is-invalid @enderror"
               id="logo" name="logo" accept="image/*">
        <div class="field-hint"><i class="fas fa-info-circle me-1"></i>Formats acceptés : PNG, JPG, SVG. Taille recommandée : 200×80px minimum.</div>
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Domaine --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-globe"></i></div>
        <div>
            <h5>Domaine personnalisé</h5>
            <p>Utilisez votre propre nom de domaine pour votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-link"></i></span>
            <input type="text" class="form-control @error('domaine_personnalise') is-invalid @enderror"
                   id="domaine_personnalise" name="domaine_personnalise"
                   value="{{ old('domaine_personnalise', $boutique->domaine_personnalise) }}"
                   placeholder="votre-domaine.com">
        </div>
        <div class="field-hint"><i class="fas fa-info-circle me-1"></i>Configurez d'abord un enregistrement DNS CNAME pointant vers notre serveur.</div>
        @error('domaine_personnalise')<div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Réseaux sociaux --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-share-alt"></i></div>
        <div>
            <h5>Réseaux sociaux</h5>
            <p>Liens affichés dans le footer de votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-facebook me-1" style="color:#1877f2;"></i> Facebook</label>
                <input type="url" class="form-control" name="reseaux_sociaux[facebook]"
                       placeholder="https://facebook.com/votreboutique"
                       value="{{ old('reseaux_sociaux.facebook', $boutique->reseaux_sociaux['facebook'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-twitter me-1" style="color:#1da1f2;"></i> Twitter / X</label>
                <input type="url" class="form-control" name="reseaux_sociaux[twitter]"
                       placeholder="https://twitter.com/votreboutique"
                       value="{{ old('reseaux_sociaux.twitter', $boutique->reseaux_sociaux['twitter'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-instagram me-1" style="color:#e1306c;"></i> Instagram</label>
                <input type="url" class="form-control" name="reseaux_sociaux[instagram]"
                       placeholder="https://instagram.com/votreboutique"
                       value="{{ old('reseaux_sociaux.instagram', $boutique->reseaux_sociaux['instagram'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="fab fa-tiktok me-1" style="color:#010101;"></i> TikTok</label>
                <input type="url" class="form-control" name="reseaux_sociaux[tiktok]"
                       placeholder="https://tiktok.com/@votreboutique"
                       value="{{ old('reseaux_sociaux.tiktok', $boutique->reseaux_sociaux['tiktok'] ?? '') }}">
            </div>
        </div>
    </div>
</div>

{{-- Bouton save --}}
<div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('admin.configurations.index') }}" class="btn-back">Annuler</a>
    <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Enregistrer les modifications
    </button>
</div>

</form>
@endsection
