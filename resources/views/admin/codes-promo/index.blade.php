@extends('layouts.admin')

@section('title', 'Codes promo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Codes promo</h1>
    <a href="{{ route('admin.codes-promo.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau code promo
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Réduction</th>
                        <th>Période</th>
                        <th>Utilisations</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codesPromo as $code)
                    <tr>
                        <td><strong>{{ $code->code }}</strong></td>
                        <td>
                            @if($code->type_reduction === 'fixe')
                                {{ number_format($code->valeur_reduction, 2) }} €
                            @else
                                {{ $code->valeur_reduction }}%
                            @endif
                        </td>
                        <td>
                            @if($code->date_debut && $code->date_fin)
                                du {{ $code->date_debut->format('d/m/Y') }}<br>
                                au {{ $code->date_fin->format('d/m/Y') }}
                            @elseif($code->date_debut)
                                à partir du {{ $code->date_debut->format('d/m/Y') }}
                            @elseif($code->date_fin)
                                jusqu'au {{ $code->date_fin->format('d/m/Y') }}
                            @else
                                Illimité
                            @endif
                        </td>
                        <td>
                            {{ $code->utilisation_actuelle }} / {{ $code->utilisation_max ?? '∞' }}
                        </td>
                        <td>
                            @if($code->estValide())
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.codes-promo.edit', $code) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-confirm-message="Êtes-vous sûr de vouloir supprimer ce code promo ?" data-target-form="delete-form-{{ $code->id }}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $code->id }}" 
                                  action="{{ route('admin.codes-promo.destroy', $code) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucun code promo trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $codesPromo->links() }}
    </div>
</div>
@endsection

