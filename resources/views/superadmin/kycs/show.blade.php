@extends('superadmin.layouts.superadmin')
@section('title', 'Dossier KYC — ' . ($kyc->utilisateur?->nom ?? 'Marchand'))
@section('page_title', '🪪 Examen KYC')

@push('styles')
<style>
.doc-preview { border: 2px solid #e2e8f0; border-radius: 14px; overflow: hidden; text-align: center; padding: 2rem; background: #f8fafc; }
.doc-preview i { font-size: 3rem; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">🪪 Examen du dossier KYC</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Marchand : <strong>{{ $kyc->utilisateur?->nom }}</strong></p>
    </div>
    <a href="{{ route('superadmin.kycs.index') }}" class="btn btn-light" style="border-radius:10px;">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-4">
    {{-- Info marchand --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">👤 Informations marchand</h6>
                <dl>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Nom</dt>
                    <dd class="fw-semibold mb-3">{{ $kyc->utilisateur?->nom ?? '—' }}</dd>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Email</dt>
                    <dd class="mb-3">{{ $kyc->utilisateur?->email ?? '—' }}</dd>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Inscrit le</dt>
                    <dd class="mb-3">{{ $kyc->utilisateur?->created_at?->format('d/m/Y') ?? '—' }}</dd>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Type de document</dt>
                    <dd class="mb-3">{{ \App\Models\Kyc::TYPES_DOCUMENT[$kyc->type_document] ?? $kyc->type_document }}</dd>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Soumis le</dt>
                    <dd class="mb-3">{{ $kyc->soumis_le?->format('d/m/Y H:i') ?? '—' }}</dd>
                    <dt style="font-size:0.75rem;color:#94a3b8;text-transform:uppercase;">Statut actuel</dt>
                    <dd>{!! $kyc->badgeHtml() !!}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">📄 Documents soumis</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="fw-semibold mb-2" style="font-size:0.85rem;">Recto</div>
                        <div class="doc-preview">
                            @if($kyc->document_recto)
                            <i class="fas fa-id-card-alt"></i>
                            <div class="mt-2">
                                <a href="{{ route('superadmin.kycs.doc', ['kyc' => $kyc->id, 'cote' => 'recto']) }}"
                                   class="btn btn-sm btn-primary mt-2" style="border-radius:8px;font-size:0.78rem;">
                                    <i class="fas fa-download me-1"></i> Télécharger le recto
                                </a>
                            </div>
                            @else
                            <i class="fas fa-times-circle" style="color:#ef4444;"></i>
                            <div class="mt-2 text-muted" style="font-size:0.82rem;">Aucun document</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fw-semibold mb-2" style="font-size:0.85rem;">Verso <span class="text-muted">(optionnel)</span></div>
                        <div class="doc-preview">
                            @if($kyc->document_verso)
                            <i class="fas fa-id-card"></i>
                            <div class="mt-2">
                                <a href="{{ route('superadmin.kycs.doc', ['kyc' => $kyc->id, 'cote' => 'verso']) }}"
                                   class="btn btn-sm btn-primary mt-2" style="border-radius:8px;font-size:0.78rem;">
                                    <i class="fas fa-download me-1"></i> Télécharger le verso
                                </a>
                            </div>
                            @else
                            <div class="text-muted" style="font-size:0.82rem;"><i class="fas fa-minus-circle me-1"></i>Non fourni</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        @if($kyc->statut === \App\Models\Kyc::STATUT_EN_ATTENTE)
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">⚖️ Décision</h6>
                <div class="row g-3">
                    {{-- Approuver --}}
                    <div class="col-md-5">
                        <form action="{{ route('superadmin.kycs.approuver', $kyc) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" style="border-radius:10px;"
                                onclick="return confirm('Approuver ce dossier KYC ?')">
                                <i class="fas fa-check-circle me-2"></i> Approuver le KYC
                            </button>
                        </form>
                    </div>
                    {{-- Rejeter --}}
                    <div class="col-md-7">
                        <form action="{{ route('superadmin.kycs.rejeter', $kyc) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="note_admin" class="form-control" placeholder="Motif du rejet..." required style="border-radius:10px 0 0 10px;">
                                <button type="submit" class="btn btn-danger" style="border-radius:0 10px 10px 0;"
                                    onclick="return confirm('Rejeter ce dossier ?')">
                                    <i class="fas fa-times me-1"></i> Rejeter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @elseif($kyc->note_admin)
        <div class="card" style="border-left:4px solid #ef4444 !important;">
            <div class="card-body">
                <div class="fw-bold text-danger mb-1">Motif du rejet :</div>
                <p class="mb-0">{{ $kyc->note_admin }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
