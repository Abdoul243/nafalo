@extends('layouts.admin')
@section('title', 'Détail client')

@section('content')
<div class="cw-page">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('admin.clients.index') }}" class="cw-btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            @php
                $nom    = $client->nom ?? 'Anonyme';
                $colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6'];
                $color  = $colors[crc32($nom) % count($colors)];
                $init   = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ',$nom),0,2))));
            @endphp
            <div class="cw-avatar" style="background:{{ $color }};width:48px;height:48px;font-size:.85rem;">{{ $init }}</div>
            <div>
                <div style="font-weight:700;color:#111827;font-size:.95rem;">{{ $nom }}</div>
                <div style="font-size:.75rem;color:#9ca3af;">{{ $client->email }}</div>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.clients.historique', $client) }}" class="cw-btn-secondary">
                <i class="fas fa-history"></i> Historique
            </a>
            <a href="mailto:{{ $client->email }}" class="cw-btn-primary">
                <i class="fas fa-envelope"></i> Contacter
            </a>
        </div>
    </div>

    {{-- KPIs --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:6px;">Achats</div>
            <div style="font-size:1.6rem;font-weight:900;color:#111827;">{{ $totalAchats }}</div>
        </div>
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:6px;">Total dépensé</div>
            <div style="font-size:1.4rem;font-weight:900;color:#111827;">{{ number_format((float) $totalDepense, 0, ',', ' ') }} <span style="font-size:.75rem;color:#9ca3af;font-weight:400;">FCFA</span></div>
        </div>
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 20px;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:6px;">Inscrit le</div>
            <div style="font-size:1rem;font-weight:700;color:#111827;">{{ optional($client->created_at)->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Infos --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px 24px;margin-bottom:20px;">
        <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px;">Informations du client</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            <div>
                <div style="font-size:.75rem;color:#9ca3af;margin-bottom:3px;">Nom complet</div>
                <div style="font-weight:600;color:#111827;">{{ $client->nom ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:.75rem;color:#9ca3af;margin-bottom:3px;">Email</div>
                <div style="font-weight:600;color:#111827;">{{ $client->email }}</div>
            </div>
            <div>
                <div style="font-size:.75rem;color:#9ca3af;margin-bottom:3px;">Téléphone</div>
                <div style="font-weight:600;color:#111827;">{{ $client->telephone ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Transactions --}}
    <div class="cw-table-wrap">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
            <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;">Dernières transactions</span>
        </div>
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($client->transactions as $transaction)
            <tr>
                <td style="font-family:monospace;font-size:.8rem;color:#374151;">{{ $transaction->reference }}</td>
                <td style="font-weight:700;color:#111827;">{{ number_format((float) $transaction->montant_total, 0, ',', ' ') }} <span style="font-size:.7rem;color:#9ca3af;font-weight:400;">FCFA</span></td>
                <td>
                    @if($transaction->statut === 'reussi')
                        <span class="cw-badge cw-badge-green">Réussie</span>
                    @elseif($transaction->statut === 'en_attente')
                        <span class="cw-badge cw-badge-amber">En attente</span>
                    @elseif($transaction->statut === 'echoue')
                        <span class="cw-badge cw-badge-red">Échouée</span>
                    @else
                        <span class="cw-badge cw-badge-gray">{{ $transaction->statut }}</span>
                    @endif
                </td>
                <td style="color:#6b7280;font-size:.78rem;">{{ optional($transaction->created_at)->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="cw-btn-row" title="Voir le détail">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="cw-empty"><i class="fas fa-receipt"></i><p>Aucune transaction trouvée</p></div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
