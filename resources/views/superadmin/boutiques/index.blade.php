@extends('superadmin.layouts.superadmin')

@section('title', 'Boutiques')
@section('page_title', 'Gestion des boutiques')

@section('content')

{{-- Filtres --}}
<div class="sa-table mb-4">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="recherche" value="{{ request('recherche') }}"
                   class="form-control" placeholder="Rechercher une boutique..."
                   style="border-radius:10px;max-width:300px;">
            <select name="statut" class="form-select" style="border-radius:10px;max-width:160px;">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('statut') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-sa"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<div class="sa-table">
    <div class="sa-table-header">
        <span><i class="fas fa-store me-2"></i>Boutiques ({{ $boutiques->total() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Boutique</th>
                <th>Marchand</th>
                <th>Domaine</th>
                <th>Produits</th>
                <th>Clients</th>
                <th>Transactions</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boutiques as $boutique)
            <tr>
                <td style="font-weight:600;color:#0f172a;">{{ $boutique->nom }}</td>
                <td style="color:#64748b;">{{ $boutique->utilisateur->nom ?? '—' }}</td>
                <td style="color:#64748b;font-size:0.8rem;">{{ $boutique->domaine_personnalise }}</td>
                <td>{{ $boutique->produits_count }}</td>
                <td>{{ $boutique->clients_count }}</td>
                <td>{{ $boutique->transactions_count }}</td>
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
                            <button type="submit"
                                    class="btn btn-sm {{ $boutique->est_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                    style="border-radius:8px;"
                                    onclick="return confirm('Confirmer ?')">
                                <i class="fas fa-toggle-{{ $boutique->est_active ? 'on' : 'off' }}"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#94a3b8;padding:2rem;">Aucune boutique trouvée</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;">
        {{ $boutiques->links() }}
    </div>
</div>

@endsection
