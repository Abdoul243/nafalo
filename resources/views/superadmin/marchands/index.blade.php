@extends('superadmin.layouts.superadmin')

@section('title', 'Marchands')
@section('page_title', 'Gestion des marchands')

@section('content')

{{-- Recherche --}}
<div class="sa-table mb-4" style="border-radius:14px;overflow:visible;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="recherche" value="{{ request('recherche') }}"
                   class="form-control" placeholder="Rechercher par nom ou email..."
                   style="border-radius:10px;max-width:350px;">
            <button type="submit" class="btn-sa">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

{{-- Liste marchands --}}
<div class="sa-table">
    <div class="sa-table-header">
        <span><i class="fas fa-users me-2"></i>Marchands ({{ $marchands->total() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Marchand</th>
                <th>Email</th>
                <th>Boutiques</th>
                <th>Inscrit le</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($marchands as $marchand)
            <tr>
                <td>
                    <div style="font-weight:600;color:#0f172a;">{{ $marchand->nom }}</div>
                </td>
                <td style="color:#64748b;">{{ $marchand->email }}</td>
                <td>
                    <span style="font-weight:700;">{{ $marchand->boutiques_count }}</span>
                    boutique(s)
                </td>
                <td style="color:#64748b;">{{ $marchand->created_at->format('d/m/Y') }}</td>
                <td>
                    @if($marchand->boutiques->where('est_active', true)->count() > 0)
                        <span class="badge-active">Actif</span>
                    @else
                        <span class="badge-inactive">Inactif</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('superadmin.marchands.show', $marchand) }}"
                           class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('superadmin.marchands.toggle', $marchand) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-warning" style="border-radius:8px;"
                                    onclick="return confirm('Confirmer ?')">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#94a3b8;padding:2rem;">
                    Aucun marchand trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;">
        {{ $marchands->links() }}
    </div>
</div>

@endsection
