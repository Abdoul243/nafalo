@extends('layouts.admin')

@section('title', 'Boutiques')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Boutiques</h1>
    <a href="{{ route('admin.boutiques.create') }}" class="btn" style="background:#0f172a;color:#fff;border:none;border-radius:10px;font-weight:600;">
        <i class="fas fa-plus"></i> Nouvelle boutique
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Domaine</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boutiques as $boutique)
                    <tr>
                        <td>
                            @if($boutique->logo)
                                <img src="{{ $boutique->logo_url }}" alt="Logo" style="height: 40px;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $boutique->nom }}</td>
                        <td>{{ $boutique->email }}</td>
                        <td>{{ $boutique->domaine_personnalise ?? '-' }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input toggle-activation" 
                                       data-id="{{ $boutique->id }}"
                                       {{ $boutique->est_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.boutiques.edit', $boutique) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-confirm-message="Êtes-vous sûr de vouloir supprimer cette boutique ?" data-target-form="delete-form-{{ $boutique->id }}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $boutique->id }}" 
                                  action="{{ route('admin.boutiques.destroy', $boutique) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune boutique trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $boutiques->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-activation').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const id = this.dataset.id;
        
        fetch(`{{ url('admin/boutiques') }}/${id}/toggle-activation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    });
});
</script>
@endpush

