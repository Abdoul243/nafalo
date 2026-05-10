@extends('layouts.admin')

@section('title', 'Ajouter un pixel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ajouter un pixel marketing</h1>
    <a href="{{ route('admin.pixels.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.pixels.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nom" class="form-label">Nom *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom') }}" required 
                       placeholder="Ex: Facebook Pixel, Google Analytics...">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="emplacement" class="form-label">Emplacement *</label>
                <select class="form-select @error('emplacement') is-invalid @enderror" 
                        id="emplacement" name="emplacement" required>
                    <option value="header" {{ old('emplacement') == 'header' ? 'selected' : '' }}>Header (toutes les pages)</option>
                    <option value="footer" {{ old('emplacement') == 'footer' ? 'selected' : '' }}>Footer (toutes les pages)</option>
                    <option value="checkout" {{ old('emplacement') == 'checkout' ? 'selected' : '' }}>Page de paiement</option>
                    <option value="confirmation" {{ old('emplacement') == 'confirmation' ? 'selected' : '' }}>Page de confirmation</option>
                </select>
                @error('emplacement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="code_pixel" class="form-label">Code du pixel *</label>
                <textarea class="form-control @error('code_pixel') is-invalid @enderror" 
                          id="code_pixel" name="code_pixel" rows="5" required 
                          placeholder="<script>...</script>">{{ old('code_pixel') }}</textarea>
                <small class="text-muted">Collez le code JavaScript complet fourni par la plateforme</small>
                @error('code_pixel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="est_actif" name="est_actif" value="1" checked>
                <label class="form-check-label" for="est_actif">Pixel actif</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter le pixel</button>
        </form>
    </div>
</div>
@endsection