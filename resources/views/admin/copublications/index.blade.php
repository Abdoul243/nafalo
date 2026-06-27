@extends('layouts.admin')
@section('title', 'Co-publications')

@section('content')
<div class="cw-page">

    {{-- Toolbar --}}
    <div class="cw-toolbar">
        <div style="display:flex;gap:16px;align-items:center;flex:1;flex-wrap:wrap;">
            @php
                $partenairesActifs  = $enTantQueProprietaire->where('statut', 'accepte')->count();
                $invitationsAttente = $invitationsRecues->where('statut', 'en_attente')->count();
                $totalInvitations   = $enTantQueProprietaire->count();
            @endphp
            <div style="display:flex;gap:12px;align-items:center;">
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#111827;">{{ $partenairesActifs }}</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Actifs</div>
                </div>
                <div style="width:1px;height:30px;background:#f1f5f9;"></div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#111827;">{{ $totalInvitations }}</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Envoyées</div>
                </div>
                @if($invitationsAttente > 0)
                <div style="width:1px;height:30px;background:#f1f5f9;"></div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#f59e0b;">{{ $invitationsAttente }}</div>
                    <div style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">En attente</div>
                </div>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.copublications.rechercher') }}" class="cw-btn-secondary">
                <i class="fas fa-search"></i> Trouver un partenaire
            </a>
            <a href="{{ route('admin.copublications.create') }}" class="cw-btn-primary">
                <i class="fas fa-plus"></i> Nouvelle invitation
            </a>
        </div>
    </div>

    @php $invAttente = $invitationsRecues->where('statut', 'en_attente')->first(); @endphp

    @if($invAttente)
    {{-- Invitation reçue --}}
    @php
        $sendNom   = $invAttente->proprietaire->nom ?? 'Anonyme';
        $sendColors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6'];
        $sendColor  = $sendColors[crc32($sendNom) % count($sendColors)];
        $sendInit   = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ',$sendNom),0,2))));
    @endphp
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:20px 24px;margin-bottom:16px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:200px;">
            <div class="cw-avatar" style="background:{{ $sendColor }};width:44px;height:44px;font-size:.8rem;">{{ $sendInit }}</div>
            <div>
                <div style="font-weight:700;color:#111827;font-size:.9rem;">Invitation de {{ $sendNom }}</div>
                <div style="font-size:.78rem;color:#92400e;">Produit : <strong>{{ Str::limit($invAttente->produit->nom ?? '—', 30) }}</strong> — Votre part : <strong>{{ $invAttente->pourcentage_copublicateur }}%</strong></div>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <form action="{{ route('admin.copublications.refuser', $invAttente) }}" method="POST">
                @csrf
                <button type="submit" class="cw-btn-secondary" style="color:#dc2626;border-color:#fecaca;">
                    <i class="fas fa-times"></i> Refuser
                </button>
            </form>
            <form action="{{ route('admin.copublications.accepter', $invAttente) }}" method="POST">
                @csrf
                <button type="submit" class="cw-btn-primary">
                    <i class="fas fa-check"></i> Accepter
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Table partenariats --}}
    <div class="cw-table-wrap">
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Partenaire</th>
                    <th>Produit</th>
                    <th>Répartition</th>
                    <th>Ventes</th>
                    <th>Statut</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($enTantQueProprietaire as $cop)
            @php
                $nom    = $cop->copublicateur->nom ?? 'Anonyme';
                $colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#14b8a6'];
                $color  = $colors[crc32($nom) % count($colors)];
                $init   = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ',$nom),0,2))));
                $nbVentes = $cop->produit?->achats()->whereHas('transaction', fn($q) => $q->where('statut','reussi'))->count() ?? 0;
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div class="cw-avatar" style="background:{{ $color }};font-size:.72rem;">{{ $init }}</div>
                        <div>
                            <div style="font-weight:600;color:#111827;font-size:.83rem;">{{ $nom }}</div>
                            <div style="font-size:.71rem;color:#9ca3af;">{{ $cop->copublicateur->email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-size:.8rem;">
                    <div style="font-weight:600;color:#111827;">{{ Str::limit($cop->produit->nom ?? '—', 28) }}</div>
                    <div style="color:#9ca3af;font-size:.72rem;">{{ number_format($cop->produit->prix ?? 0, 0, ',', ' ') }} FCFA</div>
                </td>
                <td>
                    <span style="font-size:.8rem;font-weight:700;color:#6366f1;background:#eef2ff;padding:3px 10px;border-radius:20px;">
                        {{ $cop->pourcentage_proprietaire }}% / {{ $cop->pourcentage_copublicateur }}%
                    </span>
                </td>
                <td>
                    <span style="font-weight:700;color:#111827;font-size:.83rem;">{{ $nbVentes }}</span>
                    <span style="font-size:.7rem;color:#9ca3af;"> vente{{ $nbVentes > 1 ? 's' : '' }}</span>
                </td>
                <td>
                    @if($cop->statut === 'accepte')
                        <span class="cw-badge cw-badge-green">Actif</span>
                    @elseif($cop->statut === 'en_attente')
                        <span class="cw-badge cw-badge-amber">En attente</span>
                    @else
                        <span class="cw-badge cw-badge-red">Refusé</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.copublications.destroy', $cop) }}" method="POST"
                          onsubmit="return confirm('Annuler cette co-publication ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;" title="Annuler">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="cw-empty">
                        <i class="fas fa-handshake"></i>
                        <p>Vous n'avez encore invité aucun partenaire</p>
                        <a href="{{ route('admin.copublications.create') }}" class="cw-btn-primary" style="display:inline-flex;">
                            <i class="fas fa-plus"></i> Inviter un partenaire
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
