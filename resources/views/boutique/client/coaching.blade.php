@extends('layouts.boutique')

@section('title', $produit->nom)

@push('styles')
<style>
body { background:#f6f7f9; }
.cc { max-width:680px; margin:0 auto; padding:1.5rem 1rem 4rem; }
.cc-top { background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:14px; }
.cc-ic { width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,#ec4899,#db2777);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.25rem;flex-shrink:0; }
.cc-top h1 { font-size:1.2rem;font-weight:800;color:#111827;margin:0; }
.cc-back { font-size:0.78rem;color:#6b7280;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:4px; }
.cc-back:hover { color:#111827; }
.cc-sub { font-size:0.8rem;color:#9ca3af; }
.cc-alert { background:#dcfce7;border:1px solid #86efac;color:#166534;padding:0.7rem 1rem;border-radius:10px;font-size:0.85rem;margin-bottom:1rem; }

.cc-card { background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.25rem 1.4rem;margin-bottom:1.25rem; }
.cc-card h3 { font-size:1rem;font-weight:800;color:#111827;margin:0 0 0.8rem; }
.cc-card label { font-size:0.82rem;font-weight:600;color:#374151;display:block;margin-bottom:5px; }
.cc-card input, .cc-card textarea { width:100%;border:1px solid #d1d5db;border-radius:10px;padding:0.7rem 0.9rem;font-size:0.9rem;font-family:inherit;outline:none; }
.cc-card input:focus, .cc-card textarea:focus { border-color:#db2777; }
.cc-btn { background:#db2777;color:#fff;border:none;border-radius:11px;padding:0.75rem 1.5rem;font-weight:700;font-size:0.9rem;cursor:pointer;margin-top:0.9rem; }
.cc-btn:hover { background:#be185d; }

.cc-res { border:1px solid #e9eaeb;border-radius:12px;padding:0.9rem 1.1rem;margin-bottom:0.7rem; }
.cc-tag { font-size:0.68rem;font-weight:800;padding:3px 9px;border-radius:20px; }
.cc-tag.en_attente { background:#fef3c7;color:#92400e; }
.cc-tag.confirmee { background:#dcfce7;color:#166534; }
.cc-tag.annulee { background:#fee2e2;color:#991b1b; }
.cc-res .dt { font-size:0.83rem;color:#374151;margin-top:6px; }
.cc-visio { display:inline-flex;align-items:center;gap:7px;background:#16a34a;color:#fff;text-decoration:none;padding:0.5rem 1rem;border-radius:9px;font-weight:700;font-size:0.82rem;margin-top:8px; }
</style>
@endpush

@section('content')
<div class="cc">
    <div class="cc-top">
        <div class="cc-ic"><i class="fas fa-video"></i></div>
        <div>
            <a href="{{ route('client.mes-achats.index') }}" class="cc-back"><i class="fas fa-arrow-left"></i> Mes achats</a>
            <h1>{{ $produit->nom }}</h1>
            <div class="cc-sub">Séance de coaching@if($produit->coaching_duree) · {{ $produit->coaching_duree }} min @endif</div>
        </div>
    </div>

    @if(session('success'))<div class="cc-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Demander un créneau --}}
    <div class="cc-card">
        <h3>📅 Réserver un créneau</h3>
        <form action="{{ route('client.coaching.store', $produit) }}" method="POST">
            @csrf
            <label>Date et heure souhaitées</label>
            <input type="datetime-local" name="date_souhaitee" required>
            <div style="margin-top:0.8rem;">
                <label>Message au coach (optionnel)</label>
                <textarea name="message" rows="3" maxlength="1000" placeholder="Votre objectif, vos questions…"></textarea>
            </div>
            <button class="cc-btn"><i class="fas fa-paper-plane"></i> Envoyer ma demande</button>
        </form>
    </div>

    {{-- Mes réservations --}}
    @if($reservations->count() > 0)
    <div class="cc-card">
        <h3>Mes réservations</h3>
        @foreach($reservations as $r)
        <div class="cc-res">
            <span class="cc-tag {{ $r->statut }}">
                {{ ['en_attente'=>'En attente de confirmation','confirmee'=>'Confirmée','annulee'=>'Annulée'][$r->statut] }}
            </span>
            <div class="dt">
                @if($r->statut === 'confirmee' && $r->date_confirmee)
                    <i class="fas fa-calendar-check"></i> {{ $r->date_confirmee->format('d/m/Y à H:i') }}
                @else
                    <i class="fas fa-clock"></i> Souhaité : {{ $r->date_souhaitee->format('d/m/Y à H:i') }}
                @endif
            </div>
            @if($r->statut === 'confirmee' && $r->lien_visio)
                <a href="{{ $r->lien_visio }}" target="_blank" class="cc-visio"><i class="fas fa-video"></i> Rejoindre la visio</a>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
