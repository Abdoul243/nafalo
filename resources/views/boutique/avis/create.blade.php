@extends('layouts.boutique')

@section('title', 'Donner mon avis')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Donner mon avis sur "{{ $produit->nom }}"</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('boutique.avis.store', $produit) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Votre note *</label>
                        <div class="rating">
                            @for($i = 5; $i >= 1; $i--)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="note" 
                                       id="note{{ $i }}" value="{{ $i }}" required>
                                <label class="form-check-label" for="note{{ $i }}">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </label>
                            </div>
                            @endfor
                        </div>
                        @error('note')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Votre commentaire</label>
                        <textarea class="form-control @error('commentaire') is-invalid @enderror" 
                                  id="commentaire" name="commentaire" rows="5" 
                                  placeholder="Partagez votre expérience avec ce produit...">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Votre avis sera visible après modération par l'équipe.
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Publier mon avis
                    </button>
                    
                    <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="btn btn-link">
                        Annuler
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rating .form-check {
    padding-left: 0;
}
.rating .form-check-input {
    margin-right: 5px;
    float: none;
}
.rating .form-check-label {
    cursor: pointer;
}
</style>
@endpush