@extends('layouts.admin')

@section('title', 'Configuration paiement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Configuration paiement</h1>
    <a href="{{ route('admin.configurations.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.configurations.paiement') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="passerelle_paiement" class="form-label">Passerelle de paiement *</label>
                <select class="form-select @error('passerelle_paiement') is-invalid @enderror" 
                        id="passerelle_paiement" name="passerelle_paiement" required>
                    <option value="stripe" {{ old('passerelle_paiement', $configuration->passerelle_paiement) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="paypal" {{ old('passerelle_paiement', $configuration->passerelle_paiement) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                </select>
                @error('passerelle_paiement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="cle_api_paiement" class="form-label">Clé publique API</label>
                <input type="text" class="form-control @error('cle_api_paiement') is-invalid @enderror" 
                       id="cle_api_paiement" name="cle_api_paiement" 
                       value="{{ old('cle_api_paiement', $configuration->cle_api_paiement) }}">
                @error('cle_api_paiement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="secret_api_paiement" class="form-label">Clé secrète API</label>
                <input type="password" class="form-control @error('secret_api_paiement') is-invalid @enderror" 
                       id="secret_api_paiement" name="secret_api_paiement" 
                       value="{{ old('secret_api_paiement', $configuration->secret_api_paiement) }}">
                <small class="text-muted">Laissez vide pour ne pas modifier</small>
                @error('secret_api_paiement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="devise" class="form-label">Devise *</label>
                <select class="form-select @error('devise') is-invalid @enderror" 
                        id="devise" name="devise" required>
                    <option value="EUR" {{ old('devise', $configuration->devise) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                    <option value="USD" {{ old('devise', $configuration->devise) == 'USD' ? 'selected' : '' }}>Dollar US (USD)</option>
                    <option value="GBP" {{ old('devise', $configuration->devise) == 'GBP' ? 'selected' : '' }}>Livre sterling (GBP)</option>
                    <option value="CHF" {{ old('devise', $configuration->devise) == 'CHF' ? 'selected' : '' }}>Franc suisse (CHF)</option>
                </select>
                @error('devise')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="alert alert-info">
                <h5>Configuration du webhook</h5>
                <p>URL du webhook à configurer dans votre compte Stripe/PayPal :</p>
                <code>{{ route('paiement.webhook') }}</code>
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection