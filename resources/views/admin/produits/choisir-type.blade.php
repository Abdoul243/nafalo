@extends('layouts.admin')
@section('title', 'Créer un produit')

@push('styles')
<style>
.ct-wrap { max-width: 980px; margin: 0 auto; padding: 2rem 1.25rem 4rem; }
.ct-head { display:flex; align-items:center; gap:12px; margin-bottom:0.5rem; }
.ct-back { width:38px;height:38px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none; }
.ct-back:hover { background:#f3f4f6; }
.ct-title { font-size:1.7rem; font-weight:800; color:#111827; margin:1.25rem 0 0.4rem; }
.ct-sub { color:#6b7280; font-size:0.92rem; margin-bottom:2rem; }

.ct-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
@media(max-width:820px){ .ct-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:520px){ .ct-grid{ grid-template-columns:1fr; } }

.ct-card { position:relative; background:#fff; border:1.5px solid #e9eaeb; border-radius:16px; padding:1.4rem 1.3rem; text-decoration:none; display:block; transition:all .18s; cursor:pointer; }
.ct-card:hover { border-color:#4f46e5; box-shadow:0 10px 30px rgba(79,70,229,0.12); transform:translateY(-3px); }
.ct-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:#fff; margin-bottom:1rem; }
.ct-name { font-size:1.05rem; font-weight:800; color:#111827; margin-bottom:4px; }
.ct-desc { font-size:0.82rem; color:#6b7280; line-height:1.5; }
.ct-badge { position:absolute; top:14px; right:14px; font-size:0.65rem; font-weight:800; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:0.03em; }
.ct-badge.new { background:#dcfce7; color:#166534; }
.ct-badge.soon { background:#fef3c7; color:#92400e; }

.ct-card.disabled { opacity:0.65; cursor:not-allowed; pointer-events:none; }
.ct-card.disabled:hover { transform:none; box-shadow:none; border-color:#e9eaeb; }
</style>
@endpush

@section('content')
<div class="ct-wrap">
    <div class="ct-head">
        <a href="{{ route('admin.produits.index') }}" class="ct-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="ct-title">Quel type de produit voulez-vous créer ?</h1>
            <p class="ct-sub">Choisissez le format qui correspond à ce que vous vendez.</p>
        </div>
    </div>

    <div class="ct-grid">

        {{-- Fichiers --}}
        <a href="{{ route('admin.produits.create', ['format' => 'fichier']) }}" class="ct-card">
            <div class="ct-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);"><i class="fas fa-file-arrow-down"></i></div>
            <div class="ct-name">Fichiers</div>
            <div class="ct-desc">PDF, ZIP, audio, vidéo… Le client télécharge le fichier après paiement.</div>
        </a>

        {{-- Formation --}}
        <a href="{{ route('admin.produits.create', ['format' => 'formation']) }}" class="ct-card">
            <span class="ct-badge new">Dispo</span>
            <div class="ct-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);"><i class="fas fa-graduation-cap"></i></div>
            <div class="ct-name">Formation</div>
            <div class="ct-desc">Espace e-learning : modules, leçons vidéo, progression. Achat unique.</div>
        </a>

        {{-- Licences --}}
        <div class="ct-card disabled">
            <span class="ct-badge soon">Bientôt</span>
            <div class="ct-icon" style="background:linear-gradient(135deg,#a855f7,#7c3aed);"><i class="fas fa-key"></i></div>
            <div class="ct-name">Licences</div>
            <div class="ct-desc">Vendez des clés de licence uniques (logiciels), générées et livrées automatiquement.</div>
        </div>

        {{-- Bundle --}}
        <div class="ct-card disabled">
            <span class="ct-badge soon">Bientôt</span>
            <div class="ct-icon" style="background:linear-gradient(135deg,#14b8a6,#0d9488);"><i class="fas fa-layer-group"></i></div>
            <div class="ct-name">Bundle</div>
            <div class="ct-desc">Regroupez plusieurs produits en un pack à prix réduit. L'achat débloque tout.</div>
        </div>

        {{-- Coaching --}}
        <div class="ct-card disabled">
            <span class="ct-badge soon">Bientôt</span>
            <div class="ct-icon" style="background:linear-gradient(135deg,#ec4899,#db2777);"><i class="fas fa-video"></i></div>
            <div class="ct-name">Coaching</div>
            <div class="ct-desc">Vendez des séances : le client réserve un créneau après paiement.</div>
        </div>

        {{-- Communauté --}}
        <div class="ct-card disabled">
            <span class="ct-badge soon">Bientôt</span>
            <div class="ct-icon" style="background:linear-gradient(135deg,#f43f5e,#e11d48);"><i class="fas fa-users"></i></div>
            <div class="ct-name">Communauté</div>
            <div class="ct-desc">Espace membre à accès récurrent (abonnement) avec contenus exclusifs.</div>
        </div>

    </div>
</div>
@endsection
