@extends('layouts.admin')
@section('title', 'Clients')

@section('content')
<div class="cw-page">

    {{-- Toolbar --}}
    <form method="GET" id="cf">
        <div class="cw-toolbar">
            <div class="cw-search">
                <i class="fas fa-search"></i>
                <input type="text" name="recherche" value="{{ request('recherche') }}"
                       placeholder="Rechercher un client par nom ou email…"
                       onchange="document.getElementById('cf').submit()">
            </div>
            <a href="{{ route('admin.exports.clients') }}" class="cw-btn-secondary">
                <i class="fas fa-download"></i> Exporter
            </a>
        </div>
    </form>

    {{-- Table --}}
    <div class="cw-table-wrap">
        <table class="cw-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Inscrit le</th>
                    <th>Achats</th>
                    <th>Total dépensé</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
            @php
                $nom = $client->nom ?? 'Anonyme';
                $colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#14b8a6'];
                $color = $colors[crc32($nom) % count($colors)];
                $initiales = strtoupper(implode('', array_map(fn($w) => substr($w,0,1), array_slice(explode(' ', $nom), 0, 2))));
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="cw-avatar" style="background:{{ $color }};">{{ $initiales }}</div>
                        <div>
                            <div style="font-weight:600;color:#111827;font-size:.84rem;">{{ $nom }}</div>
                            <div style="font-size:.72rem;color:#9ca3af;">{{ $client->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#6b7280;font-size:.78rem;">{{ $client->created_at->format('d M Y') }}</td>
                <td>
                    <span class="cw-badge cw-badge-gray">{{ $client->achats_count }} achat{{ $client->achats_count > 1 ? 's' : '' }}</span>
                </td>
                <td>
                    <span style="font-weight:700;color:#111827;font-size:.83rem;">{{ number_format($client->transactions()->where('statut', 'reussi')->sum('montant_total'), 0, ',', ' ') }}</span>
                    <span style="font-size:.7rem;color:#9ca3af;">FCFA</span>
                </td>
                <td>
                    <div style="display:flex;gap:5px;justify-content:flex-end;">
                        <a href="{{ route('admin.clients.show', $client) }}" class="cw-btn-row" title="Voir le profil">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="mailto:{{ $client->email }}" class="cw-btn-row" title="Envoyer un email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="cw-empty">
                        <i class="fas fa-users"></i>
                        <p>Aucun client trouvé@if(request('recherche')) pour "{{ request('recherche') }}"@endif</p>
                        @if(request('recherche'))
                            <a href="{{ route('admin.clients.index') }}" class="cw-btn-secondary" style="display:inline-flex;">Effacer</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
    <div class="cw-pages">{{ $clients->withQueryString()->links() }}</div>
    @endif

</div>
@endsection
