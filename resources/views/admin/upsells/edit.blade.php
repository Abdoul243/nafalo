@extends('layouts.admin')
@section('title', 'Modifier l\'upsell')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.produits.upsells.index', $produit) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
    <h1 class="h4 fw-bold mb-0">✏️ Modifier l'upsell</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:20px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.produits.upsells.update', [$produit, $upsell]) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Produit proposé</label>
                        <input type="text" class="form-control rounded-3 bg-light"
                               value="{{ $upsell->produitUpsell->nom ?? '—' }}" disabled>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Titre de l'offre *</label>
                        <input type="text" name="titre_offre"
                               class="form-control rounded-3 @error('titre_offre') is-invalid @enderror"
                               value="{{ old('titre_offre', $upsell->titre_offre) }}" required>
                        @error('titre_offre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description_offre" rows="3" class="form-control rounded-3">{{ old('description_offre', $upsell->description_offre) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Prix spécial (F CFA)</label>
                        <div class="input-group" style="max-width:250px;">
                            <input type="number" name="prix_special" step="1" min="0"
                                   class="form-control rounded-start-3"
                                   value="{{ old('prix_special', $upsell->prix_special) }}">
                            <span class="input-group-text">F CFA</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ordre</label>
                        <input type="number" name="ordre" min="0"
                               class="form-control rounded-3" style="max-width:120px;"
                               value="{{ old('ordre', $upsell->ordre) }}">
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="est_actif" value="1"
                                   id="est_actif" {{ $upsell->est_actif ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="est_actif">Upsell actif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.produits.upsells.index', $produit) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
