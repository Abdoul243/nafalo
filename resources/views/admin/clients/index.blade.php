@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Clients</h1>
    <a href="{{ route('admin.exports.clients') }}" class="btn btn-outline-success">
        <i class="fas fa-file-csv me-1"></i> Exporter CSV
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-8 col-12">
                <input type="text" class="form-control" name="recherche" 
                       placeholder="Rechercher par nom ou email..." value="{{ request('recherche') }}">
            </div>
            <div class="col-md-2 col-12">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Rechercher
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
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Achats</th>
                        <th>Total dépensé</th>
                        <th>Inscrit le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->nom ?? '-' }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->telephone ?? '-' }}</td>
                        <td>{{ $client->achats_count }}</td>
                        <td>{{ number_format($client->transactions()->where('statut', 'reussi')->sum('montant_total'), 2) }} FCFA</td>
                        <td>{{ $client->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.clients.historique', $client) }}" class="btn btn-sm btn-outline-primary" title="Historique">
                                <i class="fas fa-history"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucun client trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $clients->withQueryString()->links() }}
    </div>
</div>
@endsection
