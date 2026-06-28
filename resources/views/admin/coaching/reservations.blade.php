@extends('layouts.admin')
@section('title', 'Coaching — ' . $produit->nom)

@push('styles')
<style>
.co-wrap { max-width:820px; margin:0 auto; padding:1.5rem 1.25rem 4rem; }
.co-head { display:flex; align-items:center; gap:12px; margin-bottom:1.25rem; }
.co-back { width:38px;height:38px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none; }
.co-title { font-size:1.3rem; font-weight:800; color:#111827; margin:0; }
.co-sub { font-size:0.85rem; color:#6b7280; margin-top:2px; }
.co-alert { background:#dcfce7;border:1px solid #86efac;color:#166534;padding:0.7rem 1rem;border-radius:10px;font-size:0.85rem;margin-bottom:1rem; }
.co-card { background:#fff;border:1px solid #e9eaeb;border-radius:14px;padding:1.1rem 1.25rem;margin-bottom:1.25rem; }
.co-card h3 { font-size:0.95rem;font-weight:800;color:#111827;margin:0 0 0.7rem; }
.co-card input, .co-card select { border:1px solid #d1d5db;border-radius:9px;padding:0.55rem 0.75rem;font-size:0.875rem;font-family:inherit;outline:none; }
.co-card input:focus { border-color:#db2777; }
.co-btn { background:#db2777;color:#fff;border:none;border-radius:9px;padding:0.55rem 1.1rem;font-weight:700;font-size:0.83rem;cursor:pointer; }
.co-btn:hover { background:#be185d; }
.co-btn.ghost { background:#fff;color:#6b7280;border:1px solid #d1d5db; }
.co-btn.ghost:hover { background:#f3f4f6; }

.co-res { background:#fff;border:1px solid #e9eaeb;border-radius:14px;padding:1rem 1.2rem;margin-bottom:0.8rem; }
.co-res-top { display:flex;align-items:center;gap:10px;flex-wrap:wrap; }
.co-res .who { font-weight:700;color:#111827;font-size:0.9rem;flex:1; }
.co-tag { font-size:0.68rem;font-weight:800;padding:3px 9px;border-radius:20px; }
.co-tag.en_attente { background:#fef3c7;color:#92400e; }
.co-tag.confirmee { background:#dcfce7;color:#166534; }
.co-tag.annulee { background:#fee2e2;color:#991b1b; }
.co-meta { font-size:0.8rem;color:#6b7280;margin-top:6px; }
.co-msg { background:#f9fafb;border-radius:9px;padding:0.6rem 0.8rem;font-size:0.83rem;color:#374151;margin-top:8px; }
.co-confirm { display:flex;gap:0.6rem;flex-wrap:wrap;align-items:flex-end;margin-top:10px;padding-top:10px;border-top:1px solid #f3f4f6; }
.co-confirm label { font-size:0.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:3px; }
.co-empty { text-align:center;padding:2.5rem;color:#9ca3af; }
</style>
@endpush

@section('content')
<div class="co-wrap">
    <div class="co-head">
        <a href="{{ route('admin.produits.edit', $produit) }}" class="co-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="co-title">{{ $produit->nom }}</h1>
            <div class="co-sub">Coaching · {{ $reservations->total() }} réservation(s)</div>
        </div>
    </div>

    @if(session('success'))<div class="co-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Réglages --}}
    <div class="co-card">
        <h3>⚙️ Réglages de la séance</h3>
        <form action="{{ route('admin.produits.coaching.reglages', $produit) }}" method="POST" style="display:flex;gap:0.7rem;align-items:flex-end;flex-wrap:wrap;">
            @csrf
            <div>
                <label style="font-size:0.78rem;font-weight:600;color:#374151;display:block;margin-bottom:4px;">Durée (minutes)</label>
                <input type="number" name="coaching_duree" min="5" max="600" value="{{ $produit->coaching_duree }}" placeholder="Ex : 60" style="width:140px;">
            </div>
            <button class="co-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </form>
    </div>

    {{-- Réservations --}}
    @forelse($reservations as $r)
    <div class="co-res">
        <div class="co-res-top">
            <span class="who">{{ $r->client->nom ?? $r->client->email ?? 'Client' }}</span>
            <span class="co-tag {{ $r->statut }}">
                {{ ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée'][$r->statut] }}
            </span>
        </div>
        <div class="co-meta">
            <i class="fas fa-clock"></i> Souhaité : {{ $r->date_souhaitee->format('d/m/Y à H:i') }}
            @if($r->date_confirmee) · <strong>Confirmé : {{ $r->date_confirmee->format('d/m/Y à H:i') }}</strong>@endif
            @if($r->lien_visio) · <a href="{{ $r->lien_visio }}" target="_blank">lien visio</a>@endif
        </div>
        @if($r->message)<div class="co-msg">💬 {{ $r->message }}</div>@endif

        @if($r->statut !== 'annulee')
        <form action="{{ route('admin.produits.coaching.confirmer', $r) }}" method="POST" class="co-confirm">
            @csrf
            <div>
                <label>Date confirmée</label>
                <input type="datetime-local" name="date_confirmee" required
                       value="{{ optional($r->date_confirmee ?? $r->date_souhaitee)->format('Y-m-d\TH:i') }}">
            </div>
            <div style="flex:1;min-width:160px;">
                <label>Lien visio (optionnel)</label>
                <input type="url" name="lien_visio" value="{{ $r->lien_visio }}" placeholder="https://meet…" style="width:100%;">
            </div>
            <button class="co-btn"><i class="fas fa-check"></i> {{ $r->statut === 'confirmee' ? 'Mettre à jour' : 'Confirmer' }}</button>
        </form>
        <form action="{{ route('admin.produits.coaching.annuler', $r) }}" method="POST" style="margin-top:8px;" onsubmit="return confirm('Annuler cette réservation ?')">
            @csrf @method('DELETE')
            <button class="co-btn ghost"><i class="fas fa-times"></i> Annuler</button>
        </form>
        @endif
    </div>
    @empty
    <div class="co-res"><div class="co-empty">Aucune réservation pour l'instant.</div></div>
    @endforelse

    <div style="margin-top:1rem;">{{ $reservations->links() }}</div>
</div>
@endsection
