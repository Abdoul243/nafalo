@extends('superadmin.layouts.superadmin')
@section('title', 'Vérifications KYC')
@section('page_title', '🪪 KYC Marchands')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">🪪 Vérifications KYC</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Gérez les demandes de vérification d'identité des marchands</p>
    </div>
</div>

{{-- Filtres --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['en_attente' => ['⏳', 'En attente', 'warning'], 'approuve' => ['✅', 'Approuvés', 'success'], 'rejete' => ['❌', 'Rejetés', 'danger'], 'tous' => ['📋', 'Tous', 'secondary']] as $s => [$emoji, $label, $color])
    <a href="{{ route('superadmin.kycs.index', ['statut' => $s]) }}"
       class="btn {{ $statut === $s ? "btn-{$color}" : 'btn-outline-'.$color }}" style="border-radius:10px;font-size:0.85rem;">
        {{ $emoji }} {{ $label }}
        @if(isset($counts[$s]))
            <span class="badge bg-white text-{{ $color }} ms-1">{{ $counts[$s] }}</span>
        @endif
    </a>
    @endforeach
</div>

@if($kycs->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
            <h5 class="fw-bold">Aucun dossier KYC</h5>
            <p class="text-muted">Aucun dossier KYC dans cette catégorie.</p>
        </div>
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Marchand</th>
                        <th>Type de document</th>
                        <th>Soumis le</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kycs as $kyc)
                    <tr>
                        <td>
                            <div class="fw-bold" style="font-size:0.88rem;">{{ $kyc->utilisateur?->nom ?? '—' }}</div>
                            <div style="font-size:0.75rem;color:#64748b;">{{ $kyc->utilisateur?->email ?? '—' }}</div>
                        </td>
                        <td>
                            <span style="font-size:0.82rem;">{{ \App\Models\Kyc::TYPES_DOCUMENT[$kyc->type_document] ?? $kyc->type_document }}</span>
                        </td>
                        <td style="font-size:0.8rem;color:#64748b;white-space:nowrap;">
                            {{ $kyc->soumis_le?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td>{!! $kyc->badgeHtml() !!}</td>
                        <td>
                            <a href="{{ route('superadmin.kycs.show', $kyc) }}" class="btn btn-sm btn-primary" style="border-radius:8px;font-size:0.75rem;">
                                <i class="fas fa-eye me-1"></i> Examiner
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($kycs->hasPages())
        <div class="card-footer bg-transparent">{{ $kycs->links() }}</div>
        @endif
    </div>
@endif
@endsection
