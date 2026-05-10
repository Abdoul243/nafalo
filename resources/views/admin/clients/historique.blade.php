@extends('layouts.admin')

@section('title', 'Historique client')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Historique des achats</h1>
        <p class="text-muted mb-0">{{ $client->nom ?? 'Client' }} - {{ $client->email }}</p>
    </div>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th># Achat</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                    <th>Transaction</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achats as $achat)
                    <tr>
                        <td>{{ $achat->id }}</td>
                        <td>{{ $achat->produit->nom ?? '-' }}</td>
                        <td>{{ $achat->quantite }}</td>
                        <td>{{ number_format((float) $achat->prix_unitaire, 2) }}</td>
                        <td>{{ number_format((float) $achat->total, 2) }}</td>
                        <td>
                            @if($achat->transaction)
                                <a href="{{ route('admin.transactions.show', $achat->transaction) }}">
                                    {{ $achat->transaction->reference }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $achat->transaction->statut ?? '-' }}</td>
                        <td>{{ optional($achat->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Aucun achat trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="px-3 pt-2">{{ $achats->links() }}</div>
    </div>
</div>
@endsection
