@extends('layouts.boutique')

@section('title', 'Vérification du code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vérification du code</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Un code à 6 chiffres a été envoyé à <strong>{{ $email }}</strong>.
                    Il est valable 15 minutes.
                </p>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form action="{{ route('client.acces.verifier') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Code de vérification *</label>
                        <input type="text" class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" required 
                               placeholder="123456" maxlength="6" pattern="[0-9]{6}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check me-2"></i>
                        Vérifier le code
                    </button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p class="mb-2">Vous n'avez pas reçu le code ?</p>
                    <form action="{{ route('client.acces.envoyer-code') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="btn btn-link">
                            Renvoyer un code
                        </button>
                    </form>
                    
                    <br>
                    
                    <a href="{{ route('client.acces.demande') }}" class="btn btn-link">
                        Utiliser un autre email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('code').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endpush