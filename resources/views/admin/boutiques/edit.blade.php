@extends('layouts.admin')

@section('title', 'Modifier la boutique')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier la boutique</h1>
    <a href="{{ route('admin.boutiques.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Détails de la boutique</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.boutiques.update', $boutique) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nom" class="form-label">Nom&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $boutique->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $boutique->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email&nbsp;<span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $boutique->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                           id="telephone" name="telephone" value="{{ old('telephone', $boutique->telephone) }}">
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                @if($boutique->logo)
                    <div class="mb-2">
                        <img src="{{ $boutique->logo_url }}" alt="Logo actuel" style="height: 100px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                       id="logo" name="logo" accept="image/*">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="domaine_personnalise" class="form-label">Domaine personnalisé</label>
                <input type="text" class="form-control @error('domaine_personnalise') is-invalid @enderror" 
                       id="domaine_personnalise" name="domaine_personnalise" 
                       value="{{ old('domaine_personnalise', $boutique->domaine_personnalise) }}" 
                       placeholder="exemple.com">
                @error('domaine_personnalise')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Réseaux sociaux</label>
                <div id="socials-container">
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                        <input type="url" class="form-control" name="reseaux_sociaux[facebook]" 
                               placeholder="URL Facebook" 
                               value="{{ old('reseaux_sociaux.facebook', $boutique->reseaux_sociaux['facebook'] ?? '') }}">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                        <input type="url" class="form-control" name="reseaux_sociaux[twitter]" 
                               placeholder="URL Twitter" 
                               value="{{ old('reseaux_sociaux.twitter', $boutique->reseaux_sociaux['twitter'] ?? '') }}">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                        <input type="url" class="form-control" name="reseaux_sociaux[instagram]" 
                               placeholder="URL Instagram" 
                               value="{{ old('reseaux_sociaux.instagram', $boutique->reseaux_sociaux['instagram'] ?? '') }}">
                    </div>
                </div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="est_active" name="est_active" value="1"
                       {{ old('est_active', $boutique->est_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="est_active">Boutique active</label>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn" style="background:#0f172a;color:#fff;border:none;border-radius:10px;font-weight:600;">
                    <i class="fas fa-save me-1"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

