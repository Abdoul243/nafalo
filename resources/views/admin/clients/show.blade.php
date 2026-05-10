@extends('layouts.admin')

@section('title', 'Détail client')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Détail client</h1>
        <p class="text-muted mb-0">{{ $client->nom ?? 'Client' }} — {{ $client->email }} @if($client->telephone) · {{ $client->telephone }} @endif</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.clients.historique', $client) }}" class="btn btn-primary">
            <i class="fas fa-history"></i> Historique
        </a>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Nombre d'achats</div>
                <div class="fs-4 fw-bold">{{ $totalAchats }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total dépensé</div>
                <div class="fs-4 fw-bold">{{ number_format((float) $totalDepense, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Inscrit le</div>
                <div class="fs-6 fw-semibold">{{ optional($client->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Informations complètes du client --}}
<div class="card mb-4">
    <div class="card-header"><strong>Informations du client</strong></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-muted small">Nom complet</div>
                <div class="fw-semibold">{{ $client->nom ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold">{{ $client->email }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Téléphone</div>
                <div class="fw-semibold">{{ $client->telephone ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
</div>

<div class="card">
    <div class="card-header">
        Dernières transactions
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ number_format((float) $transaction->montant_total, 2) }}</td>
                        <td>{{ $transaction->statut }}</td>
                        <td>{{ optional($transaction->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune transaction trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

{{-- Responsive: stat cards 3→2col on mobile --}}
@push('styles')
<style>
@media(max-width:575px){
    .row.g-3.mb-4 > [class*="col-md-"]{flex:0 0 100%;max-width:100%;}
}
</style>
@endpush
@endsection