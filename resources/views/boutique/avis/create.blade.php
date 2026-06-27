@extends('layouts.boutique')

@section('title', 'Donner mon avis')

@push('styles')
<style>
.avis-wrap {
    max-width: 600px;
    margin: 0 auto;
    padding: 2.5rem 1.25rem 5rem;
}
.avis-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.75rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.35rem;
}
.avis-subtitle {
    font-size: 0.875rem; color: var(--text-3);
    margin-bottom: 2rem; line-height: 1.6;
}

.avis-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
}

.avis-label {
    display: block;
    font-size: 0.8rem; font-weight: 600;
    color: var(--text-2); margin-bottom: 0.5rem;
}
.avis-input {
    width: 100%;
    background: rgba(0,0,0,0.03);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 0.8rem 1rem;
    font-size: 0.9rem; color: var(--text-1);
    font-family: inherit; outline: none;
    transition: border-color 0.2s;
    box-sizing: border-box; resize: vertical;
}
.avis-input::placeholder { color: var(--text-3); }
.avis-input:focus { border-color: var(--accent); background: rgba(124,58,237,0.06); }
.avis-error { font-size: 0.78rem; color: #dc2626; margin-top: 0.35rem; display: block; }

/* Star rating */
.stars-row {
    display: flex; flex-direction: row-reverse;
    justify-content: flex-end; gap: 6px;
    margin-bottom: 0.25rem;
}
.stars-row input[type="radio"] {
    display: none;
}
.stars-row label {
    font-size: 1.8rem; cursor: pointer; color: #d1d5db;
    transition: color 0.15s;
    line-height: 1;
}
.stars-row label:hover,
.stars-row label:hover ~ label,
.stars-row input:checked ~ label {
    color: #f59e0b;
}

.avis-note {
    font-size: 0.78rem; color: var(--text-3);
    background: rgba(124,58,237,0.07);
    border: 1px solid rgba(124,58,237,0.15);
    border-radius: 10px; padding: 0.75rem 1rem;
    margin: 1.25rem 0;
    display: flex; align-items: flex-start; gap: 7px;
}
.avis-note i { color: var(--accent); margin-top: 1px; flex-shrink: 0; }

.btn-submit {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 0.95rem;
    background: var(--accent); color: white;
    border: none; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.25s; margin-bottom: 0.6rem;
    box-shadow: 0 4px 16px rgba(124,58,237,0.3);
}
.btn-submit:hover { background: var(--accent-hover); transform: translateY(-2px); }

.btn-cancel {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 0.75rem;
    background: rgba(0,0,0,0.03);
    border: 1px solid var(--border); border-radius: 11px;
    font-size: 0.875rem; font-weight: 600; color: var(--text-2);
    text-decoration: none; transition: all 0.2s;
}
.btn-cancel:hover { color: var(--text-1); border-color: rgba(0,0,0,0.15); }
</style>
@endpush

@section('content')
<div class="avis-wrap">

    <h1 class="avis-title">Laisser un avis</h1>
    <p class="avis-subtitle">Votre avis sur <strong style="color:var(--text-1);">{{ $produit->nom }}</strong></p>

    <div class="avis-card">
        <form action="{{ route('boutique.avis.store', $produit) }}" method="POST">
            @csrf

            <div style="margin-bottom:1.5rem;">
                <label class="avis-label">Votre note *</label>
                <div class="stars-row">
                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" name="note" id="note{{ $i }}" value="{{ $i }}" required {{ old('note') == $i ? 'checked' : '' }}>
                    <label for="note{{ $i }}">★</label>
                    @endfor
                </div>
                @error('note')
                    <span class="avis-error"><i class="fas fa-exclamation-circle" style="font-size:0.7rem;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom:1.25rem;">
                <label class="avis-label" for="commentaire">Votre commentaire</label>
                <textarea
                    class="avis-input"
                    id="commentaire"
                    name="commentaire"
                    rows="5"
                    placeholder="Partagez votre expérience avec ce produit...">{{ old('commentaire') }}</textarea>
                @error('commentaire')
                    <span class="avis-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="avis-note">
                <i class="fas fa-info-circle"></i>
                Votre avis sera visible après modération par l'équipe.
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-star"></i> Publier mon avis
            </button>

            <a href="{{ route('boutique.produit.show', $produit->slug) }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
        </form>
    </div>
</div>
@endsection
