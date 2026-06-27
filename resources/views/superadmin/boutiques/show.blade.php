@extends('superadmin.layouts.superadmin')

@section('title', $boutique->nom)
@section('page_title', 'Fiche boutique')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight:800;font-size:1.3rem;color:#0f172a;margin:0;">{{ $boutique->nom }}</h2>
        <div style="color:#64748b;font-size:0.875rem;">
            Marchand : {{ $boutique->utilisateur->nom ?? '—' }} —
            Domaine : {{ $boutique->domaine_personnalise }}
        </div>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('superadmin.boutiques.toggle', $boutique) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="btn {{ $boutique->est_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                    style="border-radius:10px;" onclick="return confirm('Confirmer ?')">
                <i class="fas fa-toggle-{{ $boutique->est_active ? 'on' : 'off' }} me-1"></i>
                {{ $boutique->est_active ? 'Désactiver' : 'Activer' }}
            </button>
        </form>
        <a href="{{ route('superadmin.boutiques.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Produits</div>
            <div class="value">{{ $stats['total_produits'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Clients</div>
            <div class="value">{{ $stats['total_clients'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Transactions</div>
            <div class="value">{{ $stats['total_transactions'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Chiffre d'affaires</div>
            <div class="value" style="font-size:1rem;">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
</div>

{{-- Réassigner à un autre marchand --}}
@if($marchands->count() > 0)
<div class="sa-table mb-4">
    <div class="sa-table-header">
        <span><i class="fas fa-exchange-alt me-2"></i>Réassigner la boutique à un autre marchand</span>
    </div>
    <div style="padding:1.25rem;">
        @if(session('success'))
            <div class="alert alert-success mb-3" style="border-radius:10px;font-size:.875rem;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('superadmin.boutiques.reassigner', $boutique) }}"
              onsubmit="return confirm('Confirmer la réassignation de cette boutique ?')">
            @csrf @method('PATCH')
            <div class="d-flex gap-3 align-items-end flex-wrap">
                <div style="flex:1;min-width:220px;">
                    <label style="font-size:.8rem;font-weight:600;color:#64748b;display:block;margin-bottom:6px;">
                        Propriétaire actuel
                    </label>
                    <input type="text" class="form-control" style="border-radius:10px;font-size:.875rem;"
                           value="{{ $boutique->utilisateur->nom }} ({{ $boutique->utilisateur->email }})" disabled>
                </div>
                <div style="flex:2;min-width:240px;">
                    <label style="font-size:.8rem;font-weight:600;color:#64748b;display:block;margin-bottom:6px;">
                        Nouveau propriétaire
                    </label>
                    <select name="nouveau_marchand_id" class="form-select" required style="border-radius:10px;font-size:.875rem;">
                        <option value="">— Sélectionner un marchand —</option>
                        @foreach($marchands as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }} ({{ $m->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-warning"
                            style="border-radius:10px;font-weight:600;white-space:nowrap;">
                        <i class="fas fa-exchange-alt me-1"></i> Réassigner
                    </button>
                </div>
            </div>
            @error('nouveau_marchand_id')
                <div style="color:#dc2626;font-size:.8rem;margin-top:6px;">{{ $message }}</div>
            @enderror
        </form>
    </div>
</div>
@endif

{{-- Dernières transactions --}}
<div class="sa-table">
    <div class="sa-table-header">
        <span><i class="fas fa-credit-card me-2"></i>Dernières transactions</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Moyen paiement</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dernieres_transactions as $t)
            <tr>
                <td><a href="{{ route('superadmin.transactions.show', $t) }}" style="color:#2563eb;text-decoration:none;font-weight:600;">{{ $t->reference }}</a></td>
                <td style="color:#64748b;">{{ $t->client->email ?? '—' }}</td>
                <td style="font-weight:700;">{{ number_format($t->montant_total, 0, ',', ' ') }} FCFA</td>
                <td>{{ $t->moyen_paiement ?? '—' }}</td>
                <td>
                    @if($t->statut === 'reussi') <span class="badge-reussi">Réussi</span>
                    @elseif($t->statut === 'en_attente') <span class="badge-attente">En attente</span>
                    @elseif($t->statut === 'echoue') <span class="badge-echoue">Échoué</span>
                    @else <span class="badge-abandonne">Abandonné</span>
                    @endif
                </td>
                <td style="color:#64748b;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:1rem;">Aucune transaction</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
