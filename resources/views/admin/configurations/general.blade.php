@extends('layouts.admin')
@section('title', 'Identité générale')
@push('styles')
<style>
/* ── Configuration générale — style Chariow ── */
.config-header { margin-bottom: 2rem; }
.config-header h1 {
    font-size: 1.45rem; font-weight: 800; color: #0f172a;
    letter-spacing: -0.02em; margin: 0 0 0.2rem;
}
.config-header p { color: #64748b; font-size: 0.875rem; margin: 0; }

/* Breadcrumb / back link */
.config-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.8rem; font-weight: 600; color: #64748b;
    text-decoration: none; margin-bottom: 1.25rem;
    transition: color .15s;
}
.config-back:hover { color: #0f172a; }

/* Cards */
.config-card {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
}
.config-card-header {
    padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 12px;
    background: #fafafa;
}
.config-card-header .icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.88rem; color: #0f172a; flex-shrink: 0;
}
.config-card-header h5 { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin: 0; }
.config-card-header p  { font-size: 0.74rem; color: #94a3b8; margin: 0; }
.config-card-body { padding: 1.5rem 1.25rem; }

/* Form */
.form-label { font-size: 0.81rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
.form-control, .form-select {
    border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important;
    padding: 0.65rem 0.9rem !important; font-size: 0.875rem !important;
    color: #0f172a; background: #fff; transition: border-color .15s;
}
.form-control:focus, .form-select:focus {
    border-color: #0f172a !important;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.06) !important;
    outline: none;
}
.input-group .input-group-text {
    border: 1.5px solid #e2e8f0; border-right: none;
    border-radius: 10px 0 0 10px; background: #f8fafc;
    color: #64748b; font-size: 0.85rem;
}
.input-group .form-control {
    border-radius: 0 10px 10px 0 !important;
    border-left: none !important;
}
.field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 0.3rem; }

/* Logo preview */
.logo-preview {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.875rem 1rem; background: #f8fafc;
    border-radius: 10px; border: 1px solid #e5e7eb; margin-bottom: 0.875rem;
}
.logo-preview img {
    height: 52px; border-radius: 6px;
    object-fit: contain; background: white; padding: 4px;
}
.logo-preview-text { font-size: 0.8rem; color: #64748b; }
.logo-preview-text strong { display: block; color: #0f172a; font-size: 0.82rem; margin-bottom: 2px; }

/* Buttons */
.btn-save {
    height: 40px; padding: 0 20px; font-size: 0.85rem; font-weight: 700;
    background: #0f172a; color: #fff; border: none; border-radius: 10px;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    transition: background .15s;
}
.btn-save:hover { background: #1e293b; }
.btn-cancel {
    height: 40px; padding: 0 16px; font-size: 0.85rem; font-weight: 600;
    background: #f8fafc; color: #64748b; border: 1px solid #e5e7eb;
    border-radius: 10px; text-decoration: none;
    display: inline-flex; align-items: center; gap: 7px; transition: all .15s;
}
.btn-cancel:hover { background: #f1f5f9; color: #0f172a; }

/* Alert success */
.alert-success-custom {
    background: #f0fdf4; border-left: 3px solid #22c55e;
    padding: 0.875rem 1.1rem; border-radius: 10px;
    margin-bottom: 1.25rem; color: #166534; font-size: 0.85rem;
    display: flex; align-items: center; gap: 8px;
}

@media (max-width: 640px) {
    .config-card-body { padding: 1rem; }
    .btn-save, .btn-cancel { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')

<a href="{{ route('admin.configurations.index') }}" class="config-back">
    <i class="fas fa-arrow-left"></i> Paramètres
</a>

<div class="config-header">
    <h1>Identité générale</h1>
    <p>Nom, logo, contact et domaine de votre boutique</p>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.configurations.general') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Identité de la boutique --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-store"></i></div>
        <div>
            <h5>Identité de la boutique</h5>
            <p>Nom et description publics de votre boutique</p>
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
                @error('email')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="telephone" class="form-label">Téléphone</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                           id="telephone" name="telephone" value="{{ old('telephone', $boutique->telephone) }}"
                           placeholder="+225 07 00 00 00 00">
                </div>
                @error('telephone')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
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
            <p>Affiché dans la navigation et le footer de votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        @if($boutique->logo)
        <div class="logo-preview">
            <img src="{{ $boutique->logo_url }}" alt="Logo actuel">
            <div class="logo-preview-text">
                <strong>Logo actuel</strong>
                Uploadez un nouveau fichier pour le remplacer
            </div>
        </div>
        @endif
        <input type="file" class="form-control @error('logo') is-invalid @enderror"
               id="logo" name="logo" accept="image/*">
        <div class="field-hint"><i class="fas fa-info-circle me-1"></i>PNG, JPG ou SVG · Taille recommandée : 200×80px minimum</div>
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Domaine personnalisé --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-globe"></i></div>
        <div>
            <h5>Domaine personnalisé</h5>
            <p>Utilisez votre propre nom de domaine</p>
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
        @error('domaine_personnalise')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
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
                <label class="form-label"><i class="fab fa-tiktok me-1"></i> TikTok</label>
                <input type="url" class="form-control" name="reseaux_sociaux[tiktok]"
                       placeholder="https://tiktok.com/@votreboutique"
                       value="{{ old('reseaux_sociaux.tiktok', $boutique->reseaux_sociaux['tiktok'] ?? '') }}">
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="d-flex justify-content-end gap-2 mb-5">
    <a href="{{ route('admin.configurations.index') }}" class="btn-cancel">Annuler</a>
    <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Enregistrer
    </button>
</div>

</form>
@endsection
