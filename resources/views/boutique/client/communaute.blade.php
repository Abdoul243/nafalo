@extends('layouts.boutique')

@section('title', $produit->nom)

@push('styles')
<style>
body { background:#f6f7f9; }
.cf { max-width:720px; margin:0 auto; padding:1.5rem 1rem 4rem; }
.cf-top { background:#fff; border:1px solid #e9eaeb; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:14px; }
.cf-ic { width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,#f43f5e,#e11d48);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.25rem;flex-shrink:0; }
.cf-top .t { flex:1; }
.cf-top h1 { font-size:1.2rem; font-weight:800; color:#111827; margin:0; }
.cf-back { font-size:0.78rem; color:#6b7280; text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-bottom:4px; }
.cf-back:hover { color:#111827; }

.cf-post { background:#fff; border:1px solid #e9eaeb; border-radius:16px; padding:1rem 1.25rem; margin-bottom:1.25rem; }
.cf-post textarea { width:100%; border:1px solid #e5e7eb; border-radius:12px; padding:0.8rem 1rem; font-size:0.92rem; font-family:inherit; outline:none; resize:vertical; }
.cf-post textarea:focus { border-color:#e11d48; }
.cf-post .row { display:flex; justify-content:flex-end; margin-top:0.6rem; }
.cf-btn { background:#e11d48; color:#fff; border:none; border-radius:10px; padding:0.6rem 1.4rem; font-weight:700; font-size:0.88rem; cursor:pointer; }
.cf-btn:hover { background:#be123c; }

.cf-msg { background:#fff; border:1px solid #e9eaeb; border-radius:16px; padding:1.1rem 1.25rem; margin-bottom:0.9rem; display:flex; gap:12px; }
.cf-msg.owner { border-color:#fecdd3; background:#fff7f8; }
.cf-av { width:42px;height:42px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1rem; }
.cf-msg .b { flex:1;min-width:0; }
.cf-msg .au { font-weight:700;color:#111827;font-size:0.92rem; }
.cf-badge { font-size:0.62rem;font-weight:800;background:#fce7f3;color:#be123c;padding:2px 8px;border-radius:20px;margin-left:6px; }
.cf-msg .dt { font-size:0.72rem;color:#9ca3af;margin-top:1px; }
.cf-msg .ct { color:#374151;font-size:0.92rem;line-height:1.65;margin-top:6px;white-space:pre-wrap;word-break:break-word; }
.cf-empty { text-align:center;padding:3rem 1rem;color:#9ca3af; }
.cf-alert { background:#dcfce7;border:1px solid #86efac;color:#166534;padding:0.7rem 1rem;border-radius:10px;font-size:0.85rem;margin-bottom:1rem; }
</style>
@endpush

@section('content')
<div class="cf">

    <div class="cf-top">
        <div class="cf-ic"><i class="fas fa-users"></i></div>
        <div class="t">
            <a href="{{ route('client.mes-achats.index') }}" class="cf-back"><i class="fas fa-arrow-left"></i> Mes achats</a>
            <h1>{{ $produit->nom }}</h1>
        </div>
    </div>

    @if(session('success'))<div class="cf-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Poster --}}
    <div class="cf-post">
        <form action="{{ route('client.communaute.poster', $produit) }}" method="POST">
            @csrf
            <textarea name="contenu" rows="3" maxlength="2000" placeholder="Écrivez un message à la communauté…" required></textarea>
            <div class="row"><button class="cf-btn"><i class="fas fa-paper-plane"></i> Publier</button></div>
        </form>
    </div>

    {{-- Fil --}}
    @forelse($messages as $msg)
    <div class="cf-msg {{ $msg->est_marchand ? 'owner' : '' }}">
        <div class="cf-av" style="background:{{ $msg->est_marchand ? '#e11d48' : '#6366f1' }};">
            {{ strtoupper(substr($msg->nom_auteur, 0, 1)) }}
        </div>
        <div class="b">
            <span class="au">{{ $msg->nom_auteur }}</span>
            @if($msg->est_marchand)<span class="cf-badge">Organisateur</span>@endif
            <div class="dt">{{ $msg->created_at->diffForHumans() }}</div>
            <div class="ct">{{ $msg->contenu }}</div>
        </div>
    </div>
    @empty
    <div class="cf-empty"><i class="fas fa-comments" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.5;"></i>Soyez le premier à écrire dans la communauté !</div>
    @endforelse

    <div style="margin-top:1rem;">{{ $messages->links() }}</div>
</div>
@endsection
