@extends('layouts.admin')

@section('title', 'Statistiques des produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Statistiques des produits</h1>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Ventes</th>
                    <th>Chiffre d'affaires</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->categorie->nom ?? '-' }}</td>
                    <td>{{ number_format($produit->prix, 2) }} FCFA</td>
                    <td>{{ $produit->achats_count }}</td>
                    <td>{{ number_format($produit->achats_sum_prix_unitaire ?? 0, 2) }} FCFA</td>
                    <td>
                        @php
                            $maxVentes = $produits->max('achats_count');
                            $pourcentage = $maxVentes > 0 ? ($produit->achats_count / $maxVentes) * 100 : 0;
                        @endphp
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $pourcentage }}%">
                                {{ number_format($pourcentage, 1) }}%
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="px-3 pt-2">{{ $produits->links() }}</div>
    </div>
</div>
@endsection
