@extends('layouts.admin')
@section('title', 'Ajouter un upsell')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.produits.upsells.index', $produit) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <div>
        <h1 class="h4 fw-bold mb-0">🔥 Ajouter un upsell</h1>
        <p class="text-muted small mb-0">Produit principal : <strong>{{ $produit->nom }}</strong></p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:20px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.produits.upsells.store', $produit) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Produit à proposer en upsell *</label>
                        <select name="produit_upsell_id" class="form-select rounded-3 @error('produit_upsell_id') is-invalid @enderror" required>
                            <option value="">— Choisir un produit —</option>
                            @foreach($produitsDisponibles as $p)
                                <option value="{{ $p->id }}" {{ old('produit_upsell_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nom }} — {{ number_format($p->prix, 0, ',', ' ') }} F CFA
                                </option>
                            @endforeach
                        </select>
                        @error('produit_upsell_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Titre de l'offre *</label>
                        <input type="text" name="titre_offre"
                               class="form-control rounded-3 @error('titre_offre') is-invalid @enderror"
                               placeholder="Ex: 🔥 Offre exclusive — Obtenez aussi ce produit !"
                               value="{{ old('titre_offre', '🔥 Offre exclusive pour vous !') }}" required>
                        @error('titre_offre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optionnel)</span></label>
                        <textarea name="description_offre" rows="3"
                                  class="form-control rounded-3"
                                  placeholder="Ex: Complétez votre achat avec ce produit essentiel à prix réduit !">{{ old('description_offre') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Prix spécial <span class="text-muted fw-normal">(laisser vide pour le prix normal)</span></label>
                        <div class="input-group" style="max-width:250px;">
                            <input type="number" name="prix_special" step="1" min="0"
                                   class="form-control rounded-start-3"
                                   placeholder="0"
                                   value="{{ old('prix_special') }}">
                            <span class="input-group-text">F CFA</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ordre d'affichage</label>
                        <input type="number" name="ordre" min="0"
                               class="form-control rounded-3" style="max-width:120px;"
                               value="{{ old('ordre', 0) }}">
                        <div class="form-text">Les upsells avec un ordre plus petit s'affichent en premier.</div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.produits.upsells.index', $produit) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
