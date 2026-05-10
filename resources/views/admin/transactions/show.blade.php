@extends('layouts.admin')
@section('title', 'Transaction #' . $transaction->reference)

@push('styles')
<style>
.split-bar { height: 10px; border-radius: 20px; overflow: hidden; display: flex; margin: 0.5rem 0 0.25rem; }
.split-bar .part-marchand { background: linear-gradient(90deg, #16a34a, #22c55e); transition: width 0.4s; }
.split-bar .part-nafalo   { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.gain-card { border-radius: 16px; padding: 1.25rem; }
.gain-card.marchand { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1.5px solid #bbf7d0; }
.gain-card.nafalo   { background: linear-gradient(135deg, #fffbeb, #fef9c3); border: 1.5px solid #fde68a; }
.gain-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
.gain-value { font-size: 1.5rem; font-weight: 900; line-height: 1; }
.gain-pct   { font-size: 0.8rem; font-weight: 600; opacity: 0.75; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Transaction #{{ $transaction->reference }}</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">
            {{ $transaction->created_at->format('d/m/Y à H:i') }}
        </p>
    </div>
    <div class="d-flex gap-2">
        @if($transaction->est_suspicieux ?? false)
            <span class="badge bg-danger px-3 py-2" style="border-radius:8px;font-size:0.8rem;">
                <i class="fas fa-exclamation-triangle me-1"></i> Suspecte
            </span>
        @endif
        @if($transaction->statut === 'reussi')
            <span class="badge bg-success px-3 py-2" style="border-radius:8px;font-size:0.8rem;">✅ Réussie</span>
        @elseif($transaction->statut === 'en_attente')
            <span class="badge bg-warning text-dark px-3 py-2" style="border-radius:8px;font-size:0.8rem;">⏳ En attente</span>
        @elseif($transaction->statut === 'echoue')
            <span class="badge bg-danger px-3 py-2" style="border-radius:8px;font-size:0.8rem;">❌ Échouée</span>
        @else
            <span class="badge bg-secondary px-3 py-2" style="border-radius:8px;font-size:0.8rem;">Abandonnée</span>
        @endif
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-light" style="border-radius:10px;">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

{{-- ── Répartition des gains ─────────────────────────────────────────── --}}
@if($transaction->statut === 'reussi')
<div class="card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">💰 Répartition des gains</h6>

        {{-- Barre visuelle --}}
        <div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;color:#64748b;">
            <span>Vous (95%)</span>
            <span>Nafalo (5%)</span>
        </div>
        <div class="split-bar">
            <div class="part-marchand" style="width:95%;"></div>
            <div class="part-nafalo"   style="width:5%;"></div>
        </div>
        <div class="text-muted mb-3" style="font-size:0.72rem;text-align:center;">
            Montant total encaissé : <strong>{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</strong>
        </div>

        <div class="row g-3">
            {{-- Gain marchand --}}
            <div class="col-md-6">
                <div class="gain-card marchand">
                    <div class="gain-label" style="color:#166534;">
                        <i class="fas fa-wallet me-1"></i> Votre gain net
                    </div>
                    <div class="gain-value" style="color:#15803d;">
                        {{ number_format($transaction->montant_marchand, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="gain-pct" style="color:#16a34a;">95% du montant total</div>
                </div>
            </div>
            {{-- Commission Nafalo --}}
            <div class="col-md-6">
                <div class="gain-card nafalo">
                    <div class="gain-label" style="color:#92400e;">
                        <i class="fas fa-percentage me-1"></i> Commission Nafalo
                    </div>
                    <div class="gain-value" style="color:#b45309;">
                        {{ number_format($transaction->commission, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="gain-pct" style="color:#d97706;">5% du montant total</div>
                </div>
            </div>
        </div>

        {{-- Co-publication (si applicable) --}}
        @php
            $produitPrincipal = $transaction->achats->first()?->produit;
            $copub = $produitPrincipal?->copublicationActive;
        @endphp
        @if($copub && $copub->estAccepte())
        <div class="mt-3 p-3 rounded" style="background:#f0f7ff;border:1px solid #bfdbfe;">
            <div class="fw-semibold mb-2" style="font-size:0.85rem;color:#1e40af;">
                <i class="fas fa-handshake me-2"></i> Partage co-publication
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <div style="font-size:0.72rem;color:#64748b;">Votre part ({{ $copub->pourcentage_proprietaire }}%)</div>
                    <div class="fw-bold" style="color:#0f172a;">
                        {{ number_format($copub->gainProprietaire((float) $transaction->montant_marchand), 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div style="border-left:2px solid #bfdbfe;padding-left:1rem;">
                    <div style="font-size:0.72rem;color:#64748b;">{{ $copub->copublicateur?->nom }} ({{ $copub->pourcentage_copublicateur }}%)</div>
                    <div class="fw-bold" style="color:#0f172a;">
                        {{ number_format($copub->gainCopublicateur((float) $transaction->montant_marchand), 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div class="text-muted" style="font-size:0.72rem;align-self:flex-end;">
                    Ces parts s'appliquent sur vos <strong>97% nets</strong>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<div class="row g-4">
    {{-- Infos générales --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">📋 Informations générales</h6>
            </div>
            <div class="card-body px-4">
                <dl class="mb-0">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Référence</dt>
                        <dd class="mb-0 fw-semibold">{{ $transaction->reference }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Moyen de paiement</dt>
                        <dd class="mb-0">{{ $transaction->moyen_paiement ?? $transaction->mode_paiement ?? '-' }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Réf. paiement</dt>
                        <dd class="mb-0 text-truncate" style="max-width:180px;">{{ $transaction->reference_paiement ?? '-' }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">IP client</dt>
                        <dd class="mb-0" style="font-size:0.82rem;">{{ $transaction->ip_client ?? '-' }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Date</dt>
                        <dd class="mb-0">{{ $transaction->created_at->format('d/m/Y à H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Infos client --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">👤 Client</h6>
            </div>
            <div class="card-body px-4">
                @if($transaction->client)
                <dl class="mb-0">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Nom</dt>
                        <dd class="mb-0 fw-semibold">{{ $transaction->client->nom ?? '-' }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Email</dt>
                        <dd class="mb-0">{{ $transaction->client->email }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Téléphone</dt>
                        <dd class="mb-0">{{ $transaction->client->telephone ?? '-' }}</dd>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <dt class="text-muted fw-normal" style="font-size:0.85rem;">Client depuis</dt>
                        <dd class="mb-0">{{ $transaction->client->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
                <div class="mt-3">
                    <a href="{{ route('admin.clients.show', $transaction->client) }}"
                       class="btn btn-sm btn-light" style="border-radius:8px;font-size:0.8rem;">
                        <i class="fas fa-user me-1"></i> Voir le profil client
                    </a>
                </div>
                @else
                    <p class="text-muted">Client non identifié</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Produits --}}
<div class="card mt-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h6 class="fw-bold mb-0">🛒 Produits achetés</h6>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="text-end">Prix unitaire</th>
                    <th class="text-center">Qté</th>
                    <th class="text-end">Réduction</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->achats as $achat)
                <tr>
                    <td class="fw-semibold">{{ $achat->produit->nom }}</td>
                    <td class="text-end">{{ number_format($achat->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="text-center">{{ $achat->quantite }}</td>
                    <td class="text-end text-muted">{{ number_format($achat->reduction_appliquee, 0, ',', ' ') }} FCFA</td>
                    <td class="text-end fw-bold">{{ number_format($achat->total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="background:#f8fafc;">
                <tr>
                    <th colspan="4" class="text-end py-3">Total encaissé</th>
                    <th class="text-end py-3">{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</th>
                </tr>
                @if($transaction->statut === 'reussi')
                <tr>
                    <td colspan="4" class="text-end text-success" style="font-size:0.85rem;">
                        <i class="fas fa-wallet me-1"></i> Votre gain net (97%)
                    </td>
                    <td class="text-end fw-bold text-success">
                        {{ number_format($transaction->montant_marchand, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end text-warning" style="font-size:0.85rem;">
                        <i class="fas fa-percentage me-1"></i> Commission Nafalo (5%)
                    </td>
                    <td class="text-end text-warning">
                        {{ number_format($transaction->commission, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>
</div>
@endsection
