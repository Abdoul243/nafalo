@extends('layouts.admin')
@section('title', 'Codes promo')

@section('content')
<div class="cw-page">

    <div class="cw-toolbar">
        <div></div>
        <a href="{{ route('admin.codes-promo.create') }}" class="cw-btn-primary">
            <i class="fas fa-plus"></i> Nouveau code
        </a>
    </div>

    <div class="cw-table-wrap">
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Réduction</th>
                    <th>Période</th>
                    <th>Utilisations</th>
                    <th>Statut</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($codesPromo as $code)
            @php
                $estExpire = $code->date_fin && $code->date_fin->isPast();
                $estActif  = $code->estValide();
                $max = $code->utilisation_max ?? 0;
                $pct = $max > 0 ? min(100, round($code->utilisation_actuelle / $max * 100)) : 0;
            @endphp
            <tr>
                <td>
                    <span style="font-family:monospace;font-weight:700;font-size:.88rem;color:#111827;background:#f3f4f6;padding:3px 10px;border-radius:6px;letter-spacing:.05em;">
                        <i class="fas fa-tag" style="font-size:.65rem;color:#9ca3af;margin-right:4px;"></i>{{ $code->code }}
                    </span>
                </td>
                <td style="font-weight:700;color:#111827;">
                    @if($code->type_reduction === 'fixe')
                        {{ number_format($code->valeur_reduction, 0, ',', ' ') }} <span style="font-size:.72rem;color:#9ca3af;font-weight:400;">FCFA</span>
                    @else
                        {{ $code->valeur_reduction }}<span style="font-size:.8rem;color:#9ca3af;">%</span>
                    @endif
                </td>
                <td style="color:#6b7280;font-size:.78rem;">
                    @if($code->date_debut && $code->date_fin)
                        {{ $code->date_debut->format('d/m/Y') }} → {{ $code->date_fin->format('d/m/Y') }}
                    @elseif($code->date_debut)
                        Dès le {{ $code->date_debut->format('d/m/Y') }}
                    @elseif($code->date_fin)
                        Jusqu'au {{ $code->date_fin->format('d/m/Y') }}
                    @else
                        <span style="font-style:italic;color:#9ca3af;">Illimité</span>
                    @endif
                </td>
                <td>
                    <div>
                        <div style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:3px;">
                            {{ $code->utilisation_actuelle }} / {{ $code->utilisation_max ?? '∞' }}
                        </div>
                        @if($max > 0)
                        <div style="height:4px;background:#f1f5f9;border-radius:4px;overflow:hidden;width:80px;">
                            <div style="height:100%;background:#f59e0b;border-radius:4px;width:{{ $pct }}%;"></div>
                        </div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($estExpire)
                        <span class="cw-badge cw-badge-red">Expiré</span>
                    @elseif($estActif)
                        <span class="cw-badge cw-badge-green">Actif</span>
                    @else
                        <span class="cw-badge cw-badge-gray">Inactif</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:5px;justify-content:flex-end;">
                        <a href="{{ route('admin.codes-promo.edit', $code) }}" class="cw-btn-row" title="Modifier">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;"
                                data-confirm-message="Supprimer le code « {{ $code->code }} » ?"
                                data-target-form="del-{{ $code->id }}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="del-{{ $code->id }}" action="{{ route('admin.codes-promo.destroy', $code) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="cw-empty">
                        <i class="fas fa-tag"></i>
                        <p>Aucun code promo créé</p>
                        <a href="{{ route('admin.codes-promo.create') }}" class="cw-btn-primary" style="display:inline-flex;">
                            <i class="fas fa-plus"></i> Créer mon premier code
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($codesPromo->hasPages())
    <div class="cw-pages">{{ $codesPromo->links() }}</div>
    @endif

</div>
@endsection
