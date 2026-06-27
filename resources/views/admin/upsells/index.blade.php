@extends('layouts.admin')
@section('title', 'Upsells — ' . $produit->nom)

@section('content')
<div class="cw-page">

    <div class="cw-toolbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('admin.produits.index') }}" class="cw-btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div style="font-weight:700;color:#111827;font-size:.9rem;">Upsells</div>
                <div style="font-size:.75rem;color:#9ca3af;">{{ $produit->nom }}</div>
            </div>
        </div>
        <a href="{{ route('admin.produits.upsells.create', $produit) }}" class="cw-btn-primary">
            <i class="fas fa-plus"></i> Ajouter un upsell
        </a>
    </div>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:.83rem;color:#1e40af;">
        <i class="fas fa-lightbulb me-2"></i>
        Après l'achat de <strong>{{ $produit->nom }}</strong>, le client verra une offre spéciale pour un autre produit.
    </div>

    <div class="cw-table-wrap">
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Produit proposé</th>
                    <th>Titre de l'offre</th>
                    <th>Prix spécial</th>
                    <th>Ordre</th>
                    <th>Statut</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($upsells as $upsell)
            <tr>
                <td>
                    @if($upsell->produitUpsell)
                        <div style="font-weight:600;color:#111827;font-size:.84rem;">{{ $upsell->produitUpsell->nom }}</div>
                        <div style="font-size:.72rem;color:#9ca3af;">Prix normal : {{ number_format($upsell->produitUpsell->prix, 0, ',', ' ') }} FCFA</div>
                    @else
                        <span style="color:#dc2626;font-size:.8rem;">Produit supprimé</span>
                    @endif
                </td>
                <td style="max-width:180px;">
                    <div style="font-weight:600;color:#111827;font-size:.83rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $upsell->titre_offre }}</div>
                    @if($upsell->description_offre)
                        <div style="font-size:.72rem;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $upsell->description_offre }}</div>
                    @endif
                </td>
                <td>
                    @if($upsell->prix_special !== null)
                        <span style="font-weight:700;color:#059669;">{{ number_format($upsell->prix_special, 0, ',', ' ') }} FCFA</span>
                    @else
                        <span style="color:#9ca3af;font-size:.78rem;font-style:italic;">Prix normal</span>
                    @endif
                </td>
                <td style="color:#6b7280;font-weight:600;">{{ $upsell->ordre }}</td>
                <td>
                    <form action="{{ route('admin.produits.upsells.toggle', [$produit, $upsell]) }}" method="POST">
                        @csrf
                        <button type="submit" class="cw-badge {{ $upsell->est_actif ? 'cw-badge-green' : 'cw-badge-gray' }}"
                                style="border:none;cursor:pointer;padding:4px 12px;">
                            {{ $upsell->est_actif ? 'Actif' : 'Inactif' }}
                        </button>
                    </form>
                </td>
                <td>
                    <div style="display:flex;gap:5px;justify-content:flex-end;">
                        <a href="{{ route('admin.produits.upsells.edit', [$produit, $upsell]) }}" class="cw-btn-row" title="Modifier">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;"
                                data-confirm-message="Supprimer cet upsell ?"
                                data-target-form="del-{{ $upsell->id }}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="del-{{ $upsell->id }}" action="{{ route('admin.produits.upsells.destroy', [$produit, $upsell]) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="cw-empty">
                        <i class="fas fa-fire"></i>
                        <p>Aucun upsell configuré pour ce produit</p>
                        <a href="{{ route('admin.produits.upsells.create', $produit) }}" class="cw-btn-primary" style="display:inline-flex;">
                            <i class="fas fa-plus"></i> Créer mon premier upsell
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
