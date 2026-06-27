@extends('layouts.admin')
@section('title', 'Transaction #' . $transaction->reference)

@section('content')
<div class="cw-page">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('admin.transactions.index') }}" class="cw-btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div style="font-weight:700;color:#111827;">Transaction #{{ $transaction->reference }}</div>
                <div style="font-size:.75rem;color:#9ca3af;">{{ $transaction->created_at->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($transaction->est_suspicieux ?? false)
                <span class="cw-badge cw-badge-red"><i class="fas fa-exclamation-triangle"></i> Suspecte</span>
            @endif
            @if($transaction->statut === 'reussi')
                <span class="cw-badge cw-badge-green">Réussie</span>
            @elseif($transaction->statut === 'en_attente')
                <span class="cw-badge cw-badge-amber">En attente</span>
            @elseif($transaction->statut === 'echoue')
                <span class="cw-badge cw-badge-red">Échouée</span>
            @else
                <span class="cw-badge cw-badge-gray">Abandonnée</span>
            @endif
        </div>
    </div>

    {{-- Répartition des gains --}}
    @if($transaction->statut === 'reussi')
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px 24px;margin-bottom:16px;">
        <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px;">Répartition des gains</div>
        <div style="display:flex;justify-content:space-between;font-size:.72rem;color:#6b7280;margin-bottom:4px;">
            <span>Vous (95%)</span><span>Nafalo (5%)</span>
        </div>
        <div style="height:8px;border-radius:20px;overflow:hidden;display:flex;margin-bottom:6px;">
            <div style="width:95%;background:linear-gradient(90deg,#16a34a,#22c55e);"></div>
            <div style="width:5%;background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
        </div>
        <div style="text-align:center;font-size:.72rem;color:#9ca3af;margin-bottom:16px;">
            Total encaissé : <strong style="color:#111827;">{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</strong>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #bbf7d0;border-radius:12px;padding:16px;">
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#166534;margin-bottom:4px;"><i class="fas fa-wallet me-1"></i> Votre gain net</div>
                <div style="font-size:1.4rem;font-weight:900;color:#15803d;line-height:1;">{{ number_format($transaction->montant_marchand, 0, ',', ' ') }} FCFA</div>
                <div style="font-size:.78rem;color:#16a34a;margin-top:4px;">95% du montant total</div>
            </div>
            <div style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border:1.5px solid #fde68a;border-radius:12px;padding:16px;">
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#92400e;margin-bottom:4px;"><i class="fas fa-percentage me-1"></i> Commission Nafalo</div>
                <div style="font-size:1.4rem;font-weight:900;color:#b45309;line-height:1;">{{ number_format($transaction->commission, 0, ',', ' ') }} FCFA</div>
                <div style="font-size:.78rem;color:#d97706;margin-top:4px;">5% du montant total</div>
            </div>
        </div>
        @php
            $produitPrincipal = $transaction->achats->first()?->produit;
            $copub = $produitPrincipal?->copublicationActive;
        @endphp
        @if($copub && $copub->estAccepte())
        <div style="margin-top:12px;padding:12px 16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;font-size:.83rem;">
            <div style="font-weight:700;color:#1e40af;margin-bottom:8px;"><i class="fas fa-handshake me-2"></i> Partage co-publication</div>
            <div style="display:flex;gap:20px;flex-wrap:wrap;">
                <div>
                    <div style="font-size:.72rem;color:#64748b;">Votre part ({{ $copub->pourcentage_proprietaire }}%)</div>
                    <div style="font-weight:700;color:#111827;">{{ number_format($copub->gainProprietaire((float) $transaction->montant_marchand), 0, ',', ' ') }} FCFA</div>
                </div>
                <div style="border-left:2px solid #bfdbfe;padding-left:16px;">
                    <div style="font-size:.72rem;color:#64748b;">{{ $copub->copublicateur?->nom }} ({{ $copub->pourcentage_copublicateur }}%)</div>
                    <div style="font-weight:700;color:#111827;">{{ number_format($copub->gainCopublicateur((float) $transaction->montant_marchand), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Infos générales + Client --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px 24px;">
            <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px;">Informations générales</div>
            @php
                $pm = $transaction->moyen_paiement ?? $transaction->mode_paiement ?? null;
                $pmMap = [
                    'wave_ci'   => ['label'=>'Wave','color'=>'#00B9F1','bg'=>'rgba(0,185,241,0.1)'],
                    'wave_sn'   => ['label'=>'Wave','color'=>'#00B9F1','bg'=>'rgba(0,185,241,0.1)'],
                    'orange_ci' => ['label'=>'Orange Money','color'=>'#FF6600','bg'=>'rgba(255,102,0,0.1)'],
                    'orange_sn' => ['label'=>'Orange Money','color'=>'#FF6600','bg'=>'rgba(255,102,0,0.1)'],
                    'mtn_ci'    => ['label'=>'MTN MoMo','color'=>'#d97706','bg'=>'rgba(217,119,6,0.1)'],
                    'mtn_ng'    => ['label'=>'MTN','color'=>'#d97706','bg'=>'rgba(217,119,6,0.1)'],
                    'moov_ci'   => ['label'=>'Moov Money','color'=>'#003087','bg'=>'rgba(0,48,135,0.1)'],
                    'moneroo'   => ['label'=>'Moneroo','color'=>'#7c3aed','bg'=>'rgba(124,58,237,0.1)'],
                ];
                $pmInfo = $pm ? ($pmMap[$pm] ?? ['label'=>ucfirst(str_replace('_',' ',$pm)),'color'=>'#6b7280','bg'=>'#f3f4f6']) : null;
            @endphp
            <div style="display:flex;flex-direction:column;gap:0;">
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.83rem;color:#6b7280;">Référence</span>
                    <span style="font-size:.83rem;font-weight:600;color:#111827;font-family:monospace;">{{ $transaction->reference }}</span>
                </div>
                @if($pmInfo)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.83rem;color:#6b7280;">Paiement</span>
                    <span style="display:inline-flex;align-items:center;gap:6px;background:{{ $pmInfo['bg'] }};border:1px solid {{ $pmInfo['color'] }}33;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;color:{{ $pmInfo['color'] }};">
                        {{ $pmInfo['label'] }}
                    </span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.83rem;color:#6b7280;">Réf. paiement</span>
                    <span style="font-size:.78rem;color:#374151;max-width:160px;overflow:hidden;text-overflow:ellipsis;">{{ $transaction->reference_paiement ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.83rem;color:#6b7280;">IP client</span>
                    <span style="font-size:.78rem;color:#374151;">{{ $transaction->ip_client ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:9px 0;">
                    <span style="font-size:.83rem;color:#6b7280;">Date</span>
                    <span style="font-size:.83rem;color:#374151;">{{ $transaction->created_at->format('d/m/Y à H:i:s') }}</span>
                </div>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px 24px;">
            <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px;">Client</div>
            @if($transaction->client)
            @php
                $cNom    = $transaction->client->nom ?? 'Anonyme';
                $cColors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6'];
                $cColor  = $cColors[crc32($cNom) % count($cColors)];
                $cInit   = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ',$cNom),0,2))));
            @endphp
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div class="cw-avatar" style="background:{{ $cColor }};width:44px;height:44px;font-size:.8rem;">{{ $cInit }}</div>
                <div>
                    <div style="font-weight:700;color:#111827;font-size:.9rem;">{{ $cNom }}</div>
                    <div style="font-size:.75rem;color:#9ca3af;">{{ $transaction->client->email }}</div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:0;">
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:.83rem;color:#6b7280;">Téléphone</span>
                    <span style="font-size:.83rem;color:#374151;">{{ $transaction->client->telephone ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:7px 0;">
                    <span style="font-size:.83rem;color:#6b7280;">Client depuis</span>
                    <span style="font-size:.83rem;color:#374151;">{{ $transaction->client->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <div style="margin-top:14px;">
                <a href="{{ route('admin.clients.show', $transaction->client) }}" class="cw-btn-secondary" style="font-size:.78rem;">
                    <i class="fas fa-user"></i> Voir le profil
                </a>
            </div>
            @else
                <span style="color:#9ca3af;font-size:.83rem;font-style:italic;">Client non identifié</span>
            @endif
        </div>
    </div>

    {{-- Produits --}}
    <div class="cw-table-wrap">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
            <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;">Produits achetés</span>
        </div>
        <table class="cw-table">
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
                    <td style="font-weight:600;color:#111827;">{{ $achat->produit->nom ?? '—' }}</td>
                    <td class="text-end" style="color:#6b7280;">{{ number_format($achat->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="text-center">{{ $achat->quantite }}</td>
                    <td class="text-end" style="color:#9ca3af;">{{ number_format($achat->reduction_appliquee, 0, ',', ' ') }} FCFA</td>
                    <td class="text-end" style="font-weight:700;color:#111827;">{{ number_format($achat->total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="background:#f9fafb;">
                <tr>
                    <td colspan="4" style="text-align:right;font-weight:600;padding:12px 16px;font-size:.83rem;">Total encaissé</td>
                    <td style="text-align:right;font-weight:800;color:#111827;padding:12px 16px;">{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @if($transaction->statut === 'reussi')
                <tr>
                    <td colspan="4" style="text-align:right;color:#16a34a;font-size:.82rem;padding:8px 16px;"><i class="fas fa-wallet me-1"></i> Votre gain net (95%)</td>
                    <td style="text-align:right;font-weight:700;color:#16a34a;padding:8px 16px;">{{ number_format($transaction->montant_marchand, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;color:#d97706;font-size:.82rem;padding:8px 16px;"><i class="fas fa-percentage me-1"></i> Commission Nafalo (5%)</td>
                    <td style="text-align:right;color:#d97706;padding:8px 16px;">{{ number_format($transaction->commission, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

</div>
@endsection
