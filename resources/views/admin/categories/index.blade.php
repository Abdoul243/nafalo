@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h1 class="mb-0">Catégories</h1>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <form method="GET" class="d-flex">
            <div class="input-group">
                <input type="search" name="q" class="form-control" placeholder="Rechercher..." value="{{ request('q') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="btn" style="background:#0f172a;color:#fff;border:none;border-radius:10px;font-weight:600;">
            <i class="fas fa-plus"></i> Nouvelle catégorie
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Nombre de produits</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                    <tr>
                        <td>{{ $categorie->nom }}</td>
                        <td>{{ $categorie->slug }}</td>
                        <td>{{ $categorie->produits_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', ['category' => $categorie->id]) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategorie({{ $categorie->id }})" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $categorie->id }}" 
                                  action="{{ route('admin.categories.destroy', ['category' => $categorie->id]) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune catégorie trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $categories->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCategorie(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush

