@extends('layouts.admin')

@section('title', 'Transactions')

@push('styles')
<style>
/* Stat cards: 2 colonnes sur mobile */
@media (max-width: 575px) {
    .trans-stats-row > [class*="col-md-"] {
        flex: 0 0 50%; max-width: 50%;
    }
}
/* Filtres: empilement sur mobile */
@media (max-width: 640px) {
    .trans-filter-row > [class*="col-md-"] {
        flex: 0 0 100%; max-width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Transactions</h1>
    <a href="{{ route('admin.exports.transactions') }}" class="btn btn-outline-success">
        <i class="fas fa-file-csv me-1"></i> Exporter CSV
    </a>
</div>

<div class="row mb-4 trans-stats-row g-2">
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #2563eb;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Total transactions</div>
                <div class="fw-black" style="font-size:1.6rem;">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #16a34a;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Réussies</div>
                <div class="fw-black" style="font-size:1.6rem;color:#16a34a;">{{ $stats['reussies'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #16a34a;background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
            <div class="card-body">
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#166534;">Vos gains nets (97%)</div>
                <div class="fw-black" style="font-size:1.1rem;color:#15803d;">
                    {{ number_format($stats['ca_marchand'] ?? 0, 0, ',', ' ') }} FCFA
                </div>
                <div style="font-size:0.72rem;color:#64748b;">sur {{ number_format($stats['ca_total'] ?? 0, 0, ',', ' ') }} FCFA brut</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left:4px solid #f59e0b;background:linear-gradient(135deg,#fffbeb,#fef9c3);">
            <div class="card-body">
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#92400e;">Commission Nafalo (5%)</div>
                <div class="fw-black" style="font-size:1.1rem;color:#b45309;">
                    {{ number_format($stats['commissions'] ?? 0, 0, ',', ' ') }} FCFA
                </div>
                <div style="font-size:0.72rem;color:#64748b;">prélevé automatiquement</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 trans-filter-row">
            <div class="col-md-3">
                <select class="form-select" name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="reussi" {{ request('statut') == 'reussi' ? 'selected' : '' }}>Réussi</option>
                    <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Échoué</option>
                    <option value="abandonne" {{ request('statut') == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}" placeholder="Date début">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}" placeholder="Date fin">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Référence / Client</th>
                        <th>Brut encaissé</th>
                        <th>Votre gain <span style="font-size:0.68rem;color:#16a34a;font-weight:600;">(97%)</span></th>
                        <th>Commission Nafalo <span style="font-size:0.68rem;color:#f59e0b;font-weight:600;">(5%)</span></th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="fw-semibold text-dark" style="text-decoration:none;">
                                {{ $transaction->reference }}
                            </a>
                            @if($transaction->est_suspicieux ?? false)
                                <span class="badge bg-danger ms-1" style="font-size:0.65rem;">⚠️</span>
                            @endif
                            <div style="font-size:0.75rem;color:#94a3b8;">{{ $transaction->client->nom ?? 'Anonyme' }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8;">{{ $transaction->client->email ?? '' }}</div>
                        </td>
                        <td class="fw-semibold">{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($transaction->statut === 'reussi')
                                <span class="fw-bold" style="color:#16a34a;">
                                    {{ number_format($transaction->montant_marchand, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->statut === 'reussi')
                                <span style="color:#d97706;font-size:0.85rem;">
                                    {{ number_format($transaction->commission, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->statut === 'reussi')
                                <span class="badge" style="background:#dcfce7;color:#166534;border-radius:8px;padding:4px 10px;">✅ Réussie</span>
                            @elseif($transaction->statut === 'en_attente')
                                <span class="badge" style="background:#fef9c3;color:#854d0e;border-radius:8px;padding:4px 10px;">⏳ En attente</span>
                            @elseif($transaction->statut === 'echoue')
                                <span class="badge" style="background:#fee2e2;color:#991b1b;border-radius:8px;padding:4px 10px;">❌ Échouée</span>
                            @else
                                <span class="badge" style="background:#f1f5f9;color:#475569;border-radius:8px;padding:4px 10px;">Abandonnée</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;color:#64748b;white-space:nowrap;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-light" style="border-radius:8px;" title="Voir le détail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Aucune transaction trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
@endsection
