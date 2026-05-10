@extends('layouts.admin')
@section('title', 'Co-publications')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">🤝 Co-publications</h1>
        <p class="text-muted small mb-0">Gérez vos partenariats de vente partagée</p>
    </div>
    <a href="{{ route('admin.copublications.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-1"></i> Inviter un partenaire
    </a>
</div>

{{-- Alertes --}}
@foreach(['success','error','info'] as $type)
    @if(session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : ($type === 'info' ? 'info' : 'success') }} alert-dismissible fade show rounded-3">
            {{ session($type) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

{{-- ── INVITATIONS REÇUES ─────────────────────────────────────────────────────── --}}
@if($invitationsRecues->isNotEmpty())
<div class="mb-5">
    <h5 class="fw-bold mb-3">
        <span class="badge bg-warning text-dark me-2">{{ $invitationsRecues->where('statut','en_attente')->count() }}</span>
        Invitations reçues
    </h5>
    <div class="row g-3">
        @foreach($invitationsRecues as $inv)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px;overflow:hidden;">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $inv->produit->nom ?? '—' }}</div>
                            <div class="text-muted small">Boutique : <strong>{{ $inv->produit->boutique->nom ?? '—' }}</strong></div>
                            <div class="text-muted small">Proposé par : <strong>{{ $inv->proprietaire->nom ?? '—' }}</strong></div>
                            @if($inv->message)
                                <div class="mt-2 p-2 bg-light rounded small fst-italic">"{{ $inv->message }}"</div>
                            @endif
                            <div class="mt-2">
                                <span class="badge bg-primary">Votre part : {{ $inv->pourcentage_copublicateur }} %</span>
                                <span class="badge bg-secondary ms-1">Propriétaire : {{ $inv->pourcentage_proprietaire }} %</span>
                            </div>
                        </div>
                        <div>
                            @if($inv->statut === 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($inv->statut === 'accepte')
                                <span class="badge bg-success">Accepté</span>
                            @else
                                <span class="badge bg-danger">Refusé</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($inv->statut === 'en_attente')
                <div class="card-footer bg-transparent d-flex gap-2 border-top-0 pt-0 px-3 pb-3">
                    <form action="{{ route('admin.copublications.accepter', $inv) }}" method="POST" class="flex-fill">
                        @csrf
                        <button class="btn btn-success w-100 rounded-pill btn-sm">
                            <i class="fas fa-check me-1"></i> Accepter
                        </button>
                    </form>
                    <form action="{{ route('admin.copublications.refuser', $inv) }}" method="POST" class="flex-fill">
                        @csrf
                        <button class="btn btn-outline-danger w-100 rounded-pill btn-sm">
                            <i class="fas fa-times me-1"></i> Refuser
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── CO-PUBLICATIONS INITIÉES ───────────────────────────────────────────────── --}}
<div>
    <h5 class="fw-bold mb-3">Mes co-publications (propriétaire)</h5>

    @if($enTantQueProprietaire->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-handshake fa-3x mb-3 opacity-25"></i>
            <p>Vous n'avez encore invité aucun partenaire.</p>
            <a href="{{ route('admin.copublications.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-1"></i> Inviter un partenaire
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="px-4 py-3">Produit</th>
                            <th>Partenaire</th>
                            <th>Répartition</th>
                            <th>Statut</th>
                            <th class="text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enTantQueProprietaire as $cop)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $cop->produit->nom ?? '—' }}</div>
                                <div class="text-muted small">{{ number_format($cop->produit->prix ?? 0, 0, ',', ' ') }} F CFA</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $cop->copublicateur->nom ?? '—' }}</div>
                                <div class="text-muted small">{{ $cop->copublicateur->email ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary">Vous : {{ $cop->pourcentage_proprietaire }} %</span>
                                <span class="badge bg-secondary">Partenaire : {{ $cop->pourcentage_copublicateur }} %</span>
                            </td>
                            <td>
                                @if($cop->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>En attente</span>
                                @elseif($cop->statut === 'accepte')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Accepté</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Refusé</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <form action="{{ route('admin.copublications.destroy', $cop) }}" method="POST"
                                      onsubmit="return confirm('Annuler cette co-publication ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
