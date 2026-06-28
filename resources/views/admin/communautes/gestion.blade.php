@extends('layouts.admin')
@section('title', 'Communauté — ' . $produit->nom)

@push('styles')
<style>
.cm-wrap { max-width:760px; margin:0 auto; padding:1.5rem 1.25rem 4rem; }
.cm-head { display:flex; align-items:center; gap:12px; margin-bottom:1.25rem; }
.cm-back { width:38px;height:38px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none; }
.cm-title { font-size:1.3rem; font-weight:800; color:#111827; margin:0; }
.cm-sub { font-size:0.85rem; color:#6b7280; margin-top:2px; }
.cm-alert { background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.7rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1rem; }

.cm-post { background:#fff; border:1px solid #e9eaeb; border-radius:14px; padding:1.1rem 1.25rem; margin-bottom:1.5rem; }
.cm-post h3 { font-size:0.95rem; font-weight:800; color:#111827; margin:0 0 0.6rem; }
.cm-post textarea { width:100%; border:1px solid #d1d5db; border-radius:10px; padding:0.7rem 0.9rem; font-size:0.9rem; font-family:inherit; outline:none; resize:vertical; }
.cm-post textarea:focus { border-color:#e11d48; }
.cm-btn { background:#e11d48; color:#fff; border:none; border-radius:10px; padding:0.6rem 1.3rem; font-weight:700; font-size:0.85rem; cursor:pointer; margin-top:0.7rem; }
.cm-btn:hover { background:#be123c; }

.cm-msg { background:#fff; border:1px solid #e9eaeb; border-radius:14px; padding:1rem 1.2rem; margin-bottom:0.8rem; display:flex; gap:12px; }
.cm-av { width:40px;height:40px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:0.95rem; }
.cm-msg .b { flex:1;min-width:0; }
.cm-msg .au { font-weight:700;color:#111827;font-size:0.9rem; }
.cm-badge-m { font-size:0.62rem;font-weight:800;background:#fce7f3;color:#be123c;padding:2px 7px;border-radius:20px;margin-left:6px; }
.cm-msg .dt { font-size:0.72rem;color:#9ca3af; }
.cm-msg .ct { color:#374151;font-size:0.9rem;line-height:1.6;margin-top:5px;white-space:pre-wrap; }
.cm-del { background:none;border:none;color:#cbd5e1;cursor:pointer;font-size:0.8rem; }
.cm-del:hover { color:#dc2626; }
.cm-empty { text-align:center;padding:2.5rem;color:#9ca3af; }
</style>
@endpush

@section('content')
<div class="cm-wrap">
    <div class="cm-head">
        <a href="{{ route('admin.produits.edit', $produit) }}" class="cm-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="cm-title">{{ $produit->nom }}</h1>
            <div class="cm-sub">Communauté · {{ $membres }} membre(s) · {{ $messages->total() }} message(s)</div>
        </div>
    </div>

    @if(session('success'))<div class="cm-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Publier une annonce --}}
    <div class="cm-post">
        <h3>📣 Publier une annonce</h3>
        <form action="{{ route('admin.produits.communaute.poster', $produit) }}" method="POST">
            @csrf
            <textarea name="contenu" rows="3" maxlength="2000" placeholder="Partagez une nouveauté, un conseil, une ressource…" required></textarea>
            <button class="cm-btn"><i class="fas fa-paper-plane"></i> Publier</button>
        </form>
    </div>

    {{-- Fil --}}
    @forelse($messages as $msg)
    <div class="cm-msg">
        <div class="cm-av" style="background:{{ $msg->est_marchand ? '#e11d48' : '#6366f1' }};">
            {{ strtoupper(substr($msg->nom_auteur, 0, 1)) }}
        </div>
        <div class="b">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <span class="au">{{ $msg->nom_auteur }}</span>
                    @if($msg->est_marchand)<span class="cm-badge-m">Vous</span>@endif
                    <div class="dt">{{ $msg->created_at->diffForHumans() }}</div>
                </div>
                <form action="{{ route('admin.produits.communaute.supprimer', $msg) }}" method="POST" onsubmit="return confirm('Supprimer ce message ?')">
                    @csrf @method('DELETE')
                    <button class="cm-del" title="Supprimer"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            <div class="ct">{{ $msg->contenu }}</div>
        </div>
    </div>
    @empty
    <div class="cm-empty">Aucun message pour l'instant. Publiez la première annonce ci-dessus 👆</div>
    @endforelse

    <div style="margin-top:1rem;">{{ $messages->links() }}</div>
</div>
@endsection
