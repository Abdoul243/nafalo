@extends('superadmin.layouts.superadmin')

@section('title', 'Transactions')
@section('page_title', 'Toutes les transactions')

@section('content')

{{-- Stats rapides --}}
<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-card text-center">
            <div class="label">Total</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card text-center">
            <div class="label">Réussies</div>
            <div class="value" style="color:#16a34a;">{{ $stats['reussies'] }}</div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card text-center">
            <div class="label">En attente</div>
            <div class="value" style="color:#ca8a04;">{{ $stats['en_attente'] }}</div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card text-center">
            <div class="label">Échouées</div>
            <div class="value" style="color:#dc2626;">{{ $stats['echouees'] }}</div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card text-center">
            <div class="label">CA Global</div>
            <div class="value" style="font-size:1rem;">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="sa-table mb-4">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <input type="text" name="recherche" value="{{ request('recherche') }}"
                   class="form-control" placeholder="Référence..."
                   style="border-radius:10px;max-width:200px;">
            <select name="statut" class="form-select" style="border-radius:10px;max-width:160px;">
                <option value="">Tous les statuts</option>
                <option value="reussi" {{ request('statut') === 'reussi' ? 'selected' : '' }}>Réussi</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="echoue" {{ request('statut') === 'echoue' ? 'selected' : '' }}>Échoué</option>
                <option value="abandonne" {{ request('statut') === 'abandonne' ? 'selected' : '' }}>Abandonné</option>
            </select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                   class="form-control" style="border-radius:10px;max-width:160px;">
            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                   class="form-control" style="border-radius:10px;max-width:160px;">
            <button type="submit" class="btn-sa"><i class="fas fa-search"></i> Filtrer</button>
            <a href="{{ route('superadmin.transactions.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                Réinitialiser
            </a>
        </form>
    </div>
</div>

{{-- Liste transactions --}}
<div class="sa-table">
    <div class="sa-table-header">
        <span><i class="fas fa-credit-card me-2"></i>Transactions ({{ $transactions->total() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Boutique</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Moyen paiement</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>
                    <a href="{{ route('superadmin.transactions.show', $t) }}"
                       style="color:#2563eb;text-decoration:none;font-weight:600;">
                        {{ $t->reference }}
                    </a>
                </td>
                <td style="color:#64748b;">{{ $t->boutique->nom ?? '—' }}</td>
                <td>
                    <div style="font-size:0.875rem;">{{ $t->client->nom ?? '—' }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">{{ $t->client->email ?? '' }}</div>
                </td>
                <td style="font-weight:700;">{{ number_format($t->montant_total, 0, ',', ' ') }} FCFA</td>
                <td style="color:#64748b;">{{ $t->moyen_paiement ?? '—' }}</td>
                <td>
                    @if($t->statut === 'reussi') <span class="badge-reussi">Réussi</span>
                    @elseif($t->statut === 'en_attente') <span class="badge-attente">En attente</span>
                    @elseif($t->statut === 'echoue') <span class="badge-echoue">Échoué</span>
                    @else <span class="badge-abandonne">Abandonné</span>
                    @endif
                </td>
                <td style="color:#64748b;font-size:0.8rem;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('superadmin.transactions.show', $t) }}"
                       class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#94a3b8;padding:2rem;">Aucune transaction trouvée</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;">
        {{ $transactions->links() }}
    </div>
</div>

@endsection
