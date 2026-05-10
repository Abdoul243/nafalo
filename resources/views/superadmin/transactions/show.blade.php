@extends('superadmin.layouts.superadmin')

@section('title', 'Transaction ' . $transaction->reference)
@section('page_title', 'Détail transaction')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight:800;font-size:1.3rem;color:#0f172a;margin:0;">
            {{ $transaction->reference }}
        </h2>
        <div style="color:#64748b;font-size:0.875rem;">
            Créée le {{ $transaction->created_at->format('d/m/Y à H:i') }}
        </div>
    </div>
    <a href="{{ route('superadmin.transactions.index') }}"
       class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-3">

    {{-- Infos transaction --}}
    <div class="col-md-6">
        <div class="sa-table h-100">
            <div class="sa-table-header">
                <span><i class="fas fa-file-invoice me-2"></i>Informations générales</span>
            </div>
            <div style="padding:1.25rem;">
                @php
                    $rows = [
                        'Référence'          => $transaction->reference,
                        'Boutique'           => $transaction->boutique->nom ?? '—',
                        'Montant total'      => number_format($transaction->montant_total, 0, ',', ' ') . ' FCFA',
                        'Moyen de paiement'  => $transaction->moyen_paiement ?? '—',
                        'Référence paiement' => $transaction->reference_paiement ?? '—',
                        'Date'               => $transaction->created_at->format('d/m/Y H:i:s'),
                    ];
                @endphp
                @foreach($rows as $label => $value)
                <div style="display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.85rem;color:#64748b;font-weight:500;">{{ $label }}</span>
                    <span style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $value }}</span>
                </div>
                @endforeach

                {{-- Statut --}}
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;">
                    <span style="font-size:0.85rem;color:#64748b;font-weight:500;">Statut</span>
                    @if($transaction->statut === 'reussi') <span class="badge-reussi">Réussi</span>
                    @elseif($transaction->statut === 'en_attente') <span class="badge-attente">En attente</span>
                    @elseif($transaction->statut === 'echoue') <span class="badge-echoue">Échoué</span>
                    @else <span class="badge-abandonne">Abandonné</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Infos client --}}
    <div class="col-md-6">
        <div class="sa-table h-100">
            <div class="sa-table-header">
                <span><i class="fas fa-user me-2"></i>Informations client</span>
            </div>
            <div style="padding:1.25rem;">
                @if($transaction->client)
                    @php
                        $client_rows = [
                            'Nom'       => $transaction->client->nom ?? '—',
                            'Email'     => $transaction->client->email,
                            'Téléphone' => $transaction->client->telephone ?? '—',
                            'Client depuis' => $transaction->client->created_at->format('d/m/Y'),
                        ];
                    @endphp
                    @foreach($client_rows as $label => $value)
                    <div style="display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:0.85rem;color:#64748b;font-weight:500;">{{ $label }}</span>
                        <span style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $value }}</span>
                    </div>
                    @endforeach
                @else
                    <p style="color:#94a3b8;text-align:center;padding:2rem 0;">Client non identifié</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Produits achetés --}}
@if($transaction->achats->count() > 0)
<div class="sa-table mt-3">
    <div class="sa-table-header">
        <span><i class="fas fa-box me-2"></i>Produits achetés</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->achats as $achat)
            <tr>
                <td style="font-weight:600;">{{ $achat->produit->nom ?? '—' }}</td>
                <td>{{ number_format($achat->prix_unitaire ?? $achat->montant, 0, ',', ' ') }} FCFA</td>
                <td style="font-weight:700;">{{ number_format($achat->montant, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f8fafc;">
                <td colspan="2" style="text-align:right;font-weight:700;padding:0.875rem 1rem;">Total :</td>
                <td style="font-weight:800;color:#2563eb;padding:0.875rem 1rem;">
                    {{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@endsection
