@extends('superadmin.layouts.superadmin')

@section('title', $utilisateur->nom)
@section('page_title', 'Fiche marchand')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight:800;font-size:1.3rem;color:#0f172a;margin:0;">{{ $utilisateur->nom }}</h2>
        <div style="color:#64748b;font-size:0.875rem;">{{ $utilisateur->email }}</div>
    </div>
    <div class="d-flex gap-2">
    <a href="{{ route('superadmin.marchands.contacter', $utilisateur) }}"
       class="btn-sa" style="border-radius:10px;padding:0.5rem 1rem;text-decoration:none;">
        <i class="fas fa-envelope me-1"></i> Contacter
    </a>
    <a href="{{ route('superadmin.marchands.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>sss
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Boutiques</div>
            <div class="value">{{ $utilisateur->boutiques->count() }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Total transactions</div>
            <div class="value">{{ $totalTransactions }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Chiffre d'affaires</div>
            <div class="value" style="font-size:1.1rem;">{{ number_format($totalVentes, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="label">Inscrit le</div>
            <div class="value" style="font-size:1rem;">{{ $utilisateur->created_at->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

{{-- Boutiques du marchand --}}
<div class="sa-table mb-4">
    <div class="sa-table-header">
        <span><i class="fas fa-store me-2"></i>Boutiques</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Domaine</th>
                <th>Produits</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($utilisateur->boutiques as $boutique)
            <tr>
                <td style="font-weight:600;">{{ $boutique->nom }}</td>
                <td style="color:#64748b;">{{ $boutique->domaine_personnalise }}</td>
                <td>{{ $boutique->produits->count() }} produit(s)</td>
                <td>
                    @if($boutique->est_active)
                        <span class="badge-active">Active</span>
                    @else
                        <span class="badge-inactive">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('superadmin.boutiques.show', $boutique) }}"
                           class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('superadmin.boutiques.toggle', $boutique) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-warning" style="border-radius:8px;">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:1rem;">Aucune boutique</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

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
                <th>Boutique</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dernieres_transactions as $t)
            <tr>
                <td><a href="{{ route('superadmin.transactions.show', $t) }}" style="color:#2563eb;text-decoration:none;font-weight:600;">{{ $t->reference }}</a></td>
                <td style="color:#64748b;">{{ $t->client->email ?? '—' }}</td>
                <td>{{ $t->boutique->nom ?? '—' }}</td>
                <td style="font-weight:700;">{{ number_format($t->montant_total, 0, ',', ' ') }} FCFA</td>
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
