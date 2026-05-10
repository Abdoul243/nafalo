@extends('layouts.admin')

@section('title', 'Modifier le produit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier le produit</h1>
    <div class="d-flex gap-2">
        <a href="{{ url('/boutique/produits/' . $produit->slug) }}" target="_blank" class="btn btn-outline-success">
            <i class="fas fa-eye me-1"></i> Voir la page
        </a>
        <a href="{{ route('admin.produits.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.produits.update', $produit) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Colonne gauche --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-semibold">Nom *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                               id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="8">{{ old('description', $produit->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lien partageable --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lien de la page produit</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light"
                                   id="produit-link"
                                   value="{{ url('/boutique/produits/' . $produit->slug) }}"
                                   readonly>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="copyLinkEdit()">
                                <i class="fas fa-copy"></i> Copier
                            </button>
                            <a href="{{ url('/boutique/produits/' . $produit->slug) }}"
                               target="_blank" class="btn btn-outline-success">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="categorie_id" class="form-label fw-semibold">Catégorie</label>
                        <select class="form-select @error('categorie_id') is-invalid @enderror"
                                id="categorie_id" name="categorie_id">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prix" class="form-label fw-semibold">Prix (FCFA) *</label>
                        <input type="number" step="0.01" class="form-control @error('prix') is-invalid @enderror"
                               id="prix" name="prix" value="{{ old('prix', $produit->prix) }}" required>
                        @error('prix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">Image</label>
                        @if($produit->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $produit->image) }}"
                                     alt="{{ $produit->nom }}"
                                     class="rounded border"
                                     style="width:100%;max-height:150px;object-fit:cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fichier --}}
                    <div class="mb-3">
                        <label for="fichier" class="form-label fw-semibold">Fichier numérique</label>
                        @if($produit->fichier)
                            <div class="mb-2">
                                <a href="{{ Storage::url($produit->fichier) }}" target="_blank"
                                   class="btn btn-sm btn-outline-info w-100">
                                    <i class="fas fa-download me-1"></i> Voir le fichier actuel
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('fichier') is-invalid @enderror"
                               id="fichier" name="fichier" accept=".pdf,.zip,.mp3,.mp4">
                        <small class="text-muted">Laissez vide pour conserver le fichier actuel</small>
                        @error('fichier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Statut</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="est_publie" name="est_publie" value="1"
                                   {{ old('est_publie', $produit->est_publie) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_publie">Produit publié</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Outils avancés du produit ────────────────────────────────────────────────── --}}
<div class="row g-3 mt-2">

    {{-- Upsells --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,#f97316,#ef4444);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-fire text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Upsells</div>
                    <div class="text-muted small">
                        {{ $produit->upsells()->count() }} offre(s) configurée(s)
                    </div>
                </div>
                <a href="{{ route('admin.produits.upsells.index', $produit) }}"
                   class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    Gérer
                </a>
            </div>
        </div>
    </div>

    {{-- Co-publication --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-handshake text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Co-publication</div>
                    <div class="text-muted small">
                        @php $copub = $produit->copublicationActive; @endphp
                        @if($copub)
                            Partenaire : <strong>{{ $copub->copublicateur->nom ?? '—' }}</strong>
                            ({{ $copub->pourcentage_copublicateur }} %)
                        @else
                            Aucun partenaire actif
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.copublications.create', ['produit_id' => $produit->id]) }}"
                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    {{ $copub ? 'Gérer' : 'Inviter' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Page IA --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;background:linear-gradient(135deg,#f5f3ff,#ede9fe);">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#4f46e5);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-wand-magic-sparkles text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Page de vente IA ✨</div>
                    <div class="text-muted small">
                        @php $pageIa = \App\Models\PageIa::where('produit_id', $produit->id)->first(); @endphp
                        {{ $pageIa ? ($pageIa->est_publiee ? '🟢 Page publiée' : '⚪ Brouillon généré') : 'Générez avec Claude AI' }}
                    </div>
                </div>
                <a href="{{ route('admin.pages-ia.create', $produit) }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color:#7c3aed;color:#7c3aed;">
                    {{ $pageIa ? 'Modifier' : 'Créer' }}
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyLinkEdit() {
    const input = document.getElementById('produit-link');
    navigator.clipboard.writeText(input.value).then(function() {
        alert('Lien copié !');
    });
}
</script>
@endpush