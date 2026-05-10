@extends('layouts.admin')
@section('title', 'Upsells — ' . $produit->nom)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.produits.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <div>
            <h1 class="h4 fw-bold mb-0">🔥 Upsells</h1>
            <p class="text-muted small mb-0">Produit : <strong>{{ $produit->nom }}</strong></p>
        </div>
    </div>
    <a href="{{ route('admin.produits.upsells.create', $produit) }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-1"></i> Ajouter un upsell
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Info --}}
<div class="alert alert-info border-0 rounded-3 mb-4" style="background:#eff6ff;">
    <i class="fas fa-lightbulb me-2 text-primary"></i>
    <strong>Qu'est-ce qu'un upsell ?</strong>
    Après l'achat de <strong>{{ $produit->nom }}</strong>, le client voit une offre spéciale
    pour un autre produit. C'est une façon d'augmenter votre chiffre d'affaires automatiquement.
</div>

@if($upsells->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-fire fa-3x mb-3 opacity-25"></i>
        <p>Aucun upsell configuré pour ce produit.</p>
        <a href="{{ route('admin.produits.upsells.create', $produit) }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> Créer mon premier upsell
        </a>
    </div>
@else
    <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3">Produit proposé</th>
                        <th>Titre de l'offre</th>
                        <th>Prix spécial</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upsells as $upsell)
                    <tr>
                        <td class="px-4">
                            @if($upsell->produitUpsell)
                                <div class="fw-semibold">{{ $upsell->produitUpsell->nom }}</div>
                                <div class="text-muted small">
                                    Prix normal : {{ number_format($upsell->produitUpsell->prix, 0, ',', ' ') }} F CFA
                                </div>
                            @else
                                <span class="text-danger">Produit supprimé</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold" style="max-width:200px;">{{ $upsell->titre_offre }}</div>
                            @if($upsell->description_offre)
                                <div class="text-muted small text-truncate" style="max-width:200px;">{{ $upsell->description_offre }}</div>
                            @endif
                        </td>
                        <td>
                            @if($upsell->prix_special !== null)
                                <span class="text-success fw-bold">{{ number_format($upsell->prix_special, 0, ',', ' ') }} F CFA</span>
                            @else
                                <span class="text-muted small">Prix normal</span>
                            @endif
                        </td>
                        <td>{{ $upsell->ordre }}</td>
                        <td>
                            <form action="{{ route('admin.produits.upsells.toggle', [$produit, $upsell]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-pill {{ $upsell->est_actif ? 'btn-success' : 'btn-outline-secondary' }}">
                                    {{ $upsell->est_actif ? '✅ Actif' : '⏸ Inactif' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.produits.upsells.edit', [$produit, $upsell]) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.produits.upsells.destroy', [$produit, $upsell]) }}"
                                      method="POST" onsubmit="return confirm('Supprimer cet upsell ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
