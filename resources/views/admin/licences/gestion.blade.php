@extends('layouts.admin')
@section('title', 'Clés de licence — ' . $produit->nom)

@push('styles')
<style>
.lc-wrap { max-width:880px; margin:0 auto; padding:1.5rem 1.25rem 4rem; }
.lc-head { display:flex; align-items:center; gap:12px; margin-bottom:1.25rem; }
.lc-back { width:38px;height:38px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none; }
.lc-title { font-size:1.3rem; font-weight:800; color:#111827; margin:0; }
.lc-sub { font-size:0.85rem; color:#6b7280; margin-top:2px; }

.lc-stats { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:1.5rem; }
.lc-stat { background:#fff; border:1px solid #e9eaeb; border-radius:14px; padding:1.1rem 1.25rem; }
.lc-stat .v { font-size:1.8rem; font-weight:900; color:#111827; line-height:1; }
.lc-stat .l { font-size:0.75rem; color:#9ca3af; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-top:6px; }
.lc-stat.warn .v { color:#dc2626; }

.lc-card { background:#fff; border:1px solid #e9eaeb; border-radius:14px; padding:1.25rem 1.4rem; margin-bottom:1.25rem; }
.lc-card h3 { font-size:1rem; font-weight:800; color:#111827; margin:0 0 0.25rem; }
.lc-card p.h { font-size:0.82rem; color:#6b7280; margin:0 0 0.9rem; }
.lc-card textarea, .lc-card input[type=text], .lc-card input[type=number] {
    width:100%; border:1px solid #d1d5db; border-radius:9px; padding:0.6rem 0.8rem; font-size:0.875rem; font-family:inherit; outline:none;
}
.lc-card textarea:focus, .lc-card input:focus { border-color:#7c3aed; }
.lc-btn { background:#7c3aed; color:#fff; border:none; border-radius:9px; padding:0.6rem 1.2rem; font-weight:700; font-size:0.85rem; cursor:pointer; margin-top:0.8rem; }
.lc-btn:hover { background:#6d28d9; }
.lc-row { display:flex; gap:0.7rem; flex-wrap:wrap; align-items:flex-end; }
.lc-row > div { flex:1; min-width:120px; }
.lc-row label { font-size:0.78rem; font-weight:600; color:#374151; display:block; margin-bottom:4px; }

.lc-alert { background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.7rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1rem; }
.lc-assigned table { width:100%; border-collapse:collapse; font-size:0.85rem; }
.lc-assigned th { text-align:left; color:#9ca3af; font-size:0.72rem; text-transform:uppercase; padding:8px; border-bottom:1px solid #eee; }
.lc-assigned td { padding:8px; border-bottom:1px solid #f3f4f6; color:#374151; }
.lc-key { font-family:monospace; font-weight:700; color:#111827; }
</style>
@endpush

@section('content')
<div class="lc-wrap">
    <div class="lc-head">
        <a href="{{ route('admin.produits.edit', $produit) }}" class="lc-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="lc-title">{{ $produit->nom }}</h1>
            <div class="lc-sub">Clés de licence — livrées automatiquement à chaque achat</div>
        </div>
    </div>

    @if(session('success'))<div class="lc-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Stock --}}
    <div class="lc-stats">
        <div class="lc-stat {{ $disponibles === 0 ? 'warn' : '' }}">
            <div class="v">{{ $disponibles }}</div>
            <div class="l">Clés disponibles</div>
        </div>
        <div class="lc-stat">
            <div class="v">{{ $attribuees->count() }}</div>
            <div class="l">Clés attribuées</div>
        </div>
    </div>

    @if($disponibles === 0)
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.8rem 1.1rem;border-radius:10px;font-size:0.85rem;margin-bottom:1.25rem;">
        <i class="fas fa-exclamation-triangle"></i> Stock épuisé — les prochains acheteurs ne recevront pas de clé. Ajoutez-en ci-dessous.
    </div>
    @endif

    {{-- Coller des clés --}}
    <div class="lc-card">
        <h3>Ajouter mes clés</h3>
        <p class="h">Collez vos clés existantes, une par ligne.</p>
        <form action="{{ route('admin.produits.licences.ajouter', $produit) }}" method="POST">
            @csrf
            <textarea name="cles" rows="5" placeholder="ABCD-1234-EFGH-5678&#10;WXYZ-9876-..." required></textarea>
            <button class="lc-btn"><i class="fas fa-plus"></i> Ajouter les clés</button>
        </form>
    </div>

    {{-- Générer --}}
    <div class="lc-card">
        <h3>Générer des clés automatiquement</h3>
        <p class="h">Crée des clés uniques au format <span class="lc-key">PREFIXE-XXXX-XXXX-XXXX-XXXX</span>.</p>
        <form action="{{ route('admin.produits.licences.generer', $produit) }}" method="POST">
            @csrf
            <div class="lc-row">
                <div>
                    <label>Quantité</label>
                    <input type="number" name="quantite" min="1" max="1000" value="10" required>
                </div>
                <div>
                    <label>Préfixe (optionnel)</label>
                    <input type="text" name="prefixe" maxlength="12" placeholder="Ex : PRO">
                </div>
                <div style="flex:0;">
                    <button class="lc-btn" style="margin-top:0;"><i class="fas fa-bolt"></i> Générer</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Clés attribuées --}}
    @if($attribuees->count() > 0)
    <div class="lc-card lc-assigned">
        <h3>Clés attribuées ({{ $attribuees->count() }})</h3>
        <table>
            <thead><tr><th>Clé</th><th>Client</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($attribuees as $cle)
                <tr>
                    <td class="lc-key">{{ $cle->cle }}</td>
                    <td>{{ $cle->client->email ?? '—' }}</td>
                    <td>{{ optional($cle->attribuee_at)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
