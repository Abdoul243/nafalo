@extends('layouts.admin')

@section('title', 'Configuration email')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Configuration email</h1>
    <a href="{{ route('admin.configurations.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.configurations.email') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email_expediteur" class="form-label">Email expéditeur *</label>
                <input type="email" class="form-control @error('email_expediteur') is-invalid @enderror" 
                       id="email_expediteur" name="email_expediteur" 
                       value="{{ old('email_expediteur', $configuration->email_expediteur) }}" required>
                <small class="text-muted">Les emails seront envoyés depuis cette adresse</small>
                @error('email_expediteur')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="relance_delai_jours" class="form-label">Délai de relance (jours) *</label>
                <input type="number" class="form-control @error('relance_delai_jours') is-invalid @enderror" 
                       id="relance_delai_jours" name="relance_delai_jours" 
                       value="{{ old('relance_delai_jours', $configuration->relance_delai_jours) }}" 
                       min="1" max="30" required>
                <small class="text-muted">Nombre de jours avant d'envoyer une relance pour panier abandonné</small>
                @error('relance_delai_jours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email_template_achat" class="form-label">Template email de confirmation d'achat</label>
                <textarea class="form-control @error('email_template_achat') is-invalid @enderror" 
                          id="email_template_achat" name="email_template_achat" rows="8">{{ old('email_template_achat', $configuration->email_template_achat) }}</textarea>
                <small class="text-muted">
                    Variables disponibles: {{nom_client}}, {{reference}}, {{montant}}, {{devise}}, {{boutique_nom}}, {{lien_achats}}
                </small>
                @error('email_template_achat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email_template_relance" class="form-label">Template email de relance</label>
                <textarea class="form-control @error('email_template_relance') is-invalid @enderror" 
                          id="email_template_relance" name="email_template_relance" rows="8">{{ old('email_template_relance', $configuration->email_template_relance) }}</textarea>
                <small class="text-muted">
                    Variables disponibles: {{nom_client}}, {{boutique_nom}}, {{lien_panier}}
                </small>
                @error('email_template_relance')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection