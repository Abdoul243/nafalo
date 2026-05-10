@extends('layouts.boutique')

@section('title', 'Accès client')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Accéder à mes achats</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Saisissez votre email pour recevoir un code d'accès à vos achats.
                </p>
                
                <form action="{{ route('client.acces.envoyer-code') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required 
                               placeholder="votre@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>
                        Envoyer le code
                    </button>
                </form>
                
                <hr>
                
                <p class="text-center text-muted small mb-0">
                    <i class="fas fa-lock me-1"></i>
                    Vos données sont confidentielles
                </p>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h6>Comment ça marche ?</h6>
                <ol class="mb-0">
                    <li>Saisissez l'email utilisé lors de vos achats</li>
                    <li>Vous recevez un code à 6 chiffres par email</li>
                    <li>Saisissez ce code pour accéder à tous vos achats</li>
                    <li>Téléchargez vos produits à volonté</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection