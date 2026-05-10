@extends('superadmin.layouts.superadmin')
@section('title', 'Détection de fraude')
@section('page_title', '🔍 Détection de fraude')

@push('styles')
<style>
.fraud-card { border-left: 4px solid #ef4444 !important; }
.raison-badge { background:#fee2e2; color:#991b1b; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:6px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">🔍 Détection de fraude</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Transactions suspectes détectées automatiquement et manuellement</p>
    </div>
    <span class="badge bg-danger fs-6 px-3 py-2">{{ $stats['total_suspectes'] }} suspecte(s)</span>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card fraud-card">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Transactions suspectes</div>
                <div class="fw-black" style="font-size:1.8rem;color:#ef4444;">{{ number_format($stats['total_suspectes']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left:4px solid #f97316 !important;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Montant suspect total</div>
                <div class="fw-black" style="font-size:1.8rem;color:#f97316;">{{ number_format($stats['montant_suspecte'], 0, ',', ' ') }} F</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left:4px solid #f59e0b !important;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Échecs (7 derniers jours)</div>
                <div class="fw-black" style="font-size:1.8rem;color:#f59e0b;">{{ number_format($stats['echecs_recents']) }}</div>
            </div>
        </div>
    </div>
</div>

@if($suspectes->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <div style="font-size:3rem;margin-bottom:1rem;">✅</div>
            <h5 class="fw-bold text-success">Aucune fraude détectée</h5>
            <p class="text-muted">Aucune transaction suspecte n'a été identifiée pour le moment.</p>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h6 class="fw-bold mb-0">Transactions suspectes</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Boutique / Client</th>
                        <th>Montant</th>
                        <th>Raison suspicion</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suspectes as $t)
                    <tr>
                        <td>
                            <a href="{{ route('superadmin.transactions.show', $t) }}" class="fw-semibold text-danger">
                                #{{ $t->id }}
                            </a>
                            <div style="font-size:0.72rem;color:#94a3b8;">{{ $t->reference ?? '—' }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.85rem;font-weight:600;">{{ $t->boutique?->nom ?? '—' }}</div>
                            <div style="font-size:0.75rem;color:#64748b;">{{ $t->client?->email ?? '—' }}</div>
                            @if($t->ip_client)
                            <div style="font-size:0.7rem;color:#94a3b8;">IP: {{ $t->ip_client }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold">{{ number_format($t->montant_total, 0, ',', ' ') }} F</span>
                            <div style="font-size:0.72rem;color:#64748b;">{{ ucfirst($t->statut) }}</div>
                        </td>
                        <td>
                            <span class="raison-badge">{{ $t->raison_suspicion }}</span>
                        </td>
                        <td style="font-size:0.8rem;color:#64748b;white-space:nowrap;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('superadmin.fraudes.blanchir', $t) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" style="font-size:0.75rem;border-radius:8px;"
                                    onclick="return confirm('Blanchir cette transaction ?')">
                                    <i class="fas fa-check me-1"></i> Blanchir
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($suspectes->hasPages())
        <div class="card-footer bg-transparent">{{ $suspectes->links() }}</div>
        @endif
    </div>
@endif

{{-- Formulaire pour marquer manuellement --}}
<div class="card mt-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h6 class="fw-bold mb-0">⚠️ Marquer une transaction manuellement</h6>
    </div>
    <div class="card-body">
        <form action="#" method="POST" class="row g-3 align-items-end" id="form-marquer">
            @csrf
            <div class="col-md-4">
                <label class="form-label" style="font-size:0.82rem;">ID de la transaction</label>
                <input type="number" class="form-control" id="transaction_id_input" placeholder="Ex: 123" style="border-radius:10px;">
            </div>
            <div class="col-md-5">
                <label class="form-label" style="font-size:0.82rem;">Raison</label>
                <input type="text" class="form-control" id="raison_input" placeholder="Décrivez la raison..." style="border-radius:10px;">
            </div>
            <div class="col-md-3">
                <button type="button" onclick="soumettreMarquer()" class="btn btn-danger w-100" style="border-radius:10px;">
                    <i class="fas fa-flag me-1"></i> Marquer suspecte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function soumettreMarquer() {
    const id = document.getElementById('transaction_id_input').value;
    const raison = document.getElementById('raison_input').value;
    if (!id || !raison) { alert('Remplissez les deux champs.'); return; }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/superadmin/fraudes/${id}/marquer`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="raison" value="${raison}">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
