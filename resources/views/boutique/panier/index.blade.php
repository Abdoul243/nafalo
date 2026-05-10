@extends('layouts.boutique')

@section('title', 'Mon panier')

@push('styles')
<style>
.panier-wrap { max-width:1280px; margin:0 auto; padding:2rem 2rem; }
@media(max-width:768px) {
    .panier-wrap { padding:1.5rem 1rem; }
    .panier-table { min-width:0 !important; }
    .panier-table th.hide-mobile, .panier-table td.hide-mobile { display:none; }
    .panier-table td { vertical-align:middle; }
}
</style>
@endpush

@section('content')
<div class="panier-wrap">
<h1 class="mb-4" style="font-size:1.5rem;font-weight:900;color:#0f172a;">
    <i class="fas fa-shopping-cart me-2" style="color:#2563eb;"></i>Mon panier
</h1>

@if(empty($panier))
    <div class="alert alert-info">
        <i class="fas fa-shopping-cart me-2"></i>
        Votre panier est vide. <a href="{{ route('boutique.produit.index') }}" class="alert-link">Continuer vos achats</a>
    </div>
@else
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table align-middle panier-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-end hide-mobile">Prix</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produits as $produit)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($produit->image)
                                            <img src="{{ asset('storage/' . $produit->image) }}"
                                                 alt="{{ $produit->nom }}"
                                                 style="width:55px;height:55px;object-fit:cover;"
                                                 class="rounded">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                 style="width:55px;height:55px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $produit->nom }}</h6>
                                            <small class="text-muted">{{ Str::limit(strip_tags($produit->description), 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end hide-mobile">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end">
                                    <form action="{{ route('boutique.panier.supprimer', $produit) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle"
                                                title="Retirer du panier">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('boutique.produit.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Continuer les achats
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Récapitulatif</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Sous-total</th>
                            <td class="text-end">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @if(session('code_promo_' . $boutique->id))
                            @php
                                $codePromo = \App\Models\CodePromo::find(session('code_promo_' . $boutique->id));
                                $reduction = $codePromo ? $codePromo->calculerReduction($total) : 0;
                                $totalApresReduction = $total - $reduction;
                            @endphp
                            <tr>
                                <th>Réduction</th>
                                <td class="text-end text-success">- {{ number_format($reduction, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td class="text-end h5">{{ number_format($totalApresReduction, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @else
                            <tr>
                                <th>Total</th>
                                <td class="text-end h5 text-primary">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endif
                    </table>

                    {{-- Code promo --}}
                    <form action="{{ route('boutique.panier.code-promo') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" name="code" placeholder="Code promo">
                            <button type="submit" class="btn btn-outline-primary">Appliquer</button>
                        </div>
                    </form>

                    @if(session('code_promo_' . $boutique->id))
                        <form action="{{ route('boutique.panier.supprimer-code-promo') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0 small">
                                <i class="fas fa-times me-1"></i> Retirer le code promo
                            </button>
                        </form>
                    @endif

                    <hr>

                    <a href="{{ route('boutique.checkout.informations') }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-lock me-2"></i> Procéder au paiement
                    </a>

                    <form action="{{ route('boutique.panier.abandonner') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted w-100 small">
                            <i class="fas fa-times me-1"></i> Abandonner le panier
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body text-center">
                    <p class="mb-2 small fw-semibold">Paiement 100% sécurisé</p>
                    <i class="fab fa-cc-visa fa-2x me-2 text-muted"></i>
                    <i class="fab fa-cc-mastercard fa-2x me-2 text-muted"></i>
                    <i class="fab fa-cc-amex fa-2x me-2 text-muted"></i>
                    <i class="fab fa-cc-paypal fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
@endsection