@extends('layouts.admin')
@section('title', 'Créer un produit')

@php
$types = [
    ['key'=>'fichier','nom'=>'Fichiers','icone'=>'fa-file-arrow-down','grad'=>'#f59e0b,#d97706',
     'desc'=>'E-books, templates, fichiers audio : vos clients téléchargent instantanément après achat.',
     'features'=>['Livraison automatique','Tous formats acceptés (PDF, ZIP, MP3…)','Protection : fichier sur stockage privé']],
    ['key'=>'formation','nom'=>'Formation','icone'=>'fa-graduation-cap','grad'=>'#6366f1,#4f46e5',
     'desc'=>'Un véritable espace e-learning avec modules, leçons vidéo et suivi de progression.',
     'features'=>['Modules & leçons (vidéo lien ou upload)','Espace membre + progression','Achat unique, accès à vie']],
    ['key'=>'licence','nom'=>'Licences','icone'=>'fa-key','grad'=>'#a855f7,#7c3aed',
     'desc'=>'Vendez des clés de licence uniques (logiciels), livrées automatiquement à chaque vente.',
     'features'=>['Génération auto (alphanumérique ou UUID)','Ou importez votre propre stock','1 clé unique livrée par achat']],
    ['key'=>'bundle','nom'=>'Bundle','icone'=>'fa-layer-group','grad'=>'#14b8a6,#0d9488',
     'desc'=>'Regroupez plusieurs produits en un pack à prix réduit. L’achat débloque tout.',
     'features'=>['Composez le pack avec vos produits','Affiche l’économie au client','Livraison de chaque produit inclus']],
    ['key'=>'communaute','nom'=>'Communauté','icone'=>'fa-users','grad'=>'#f43f5e,#e11d48',
     'desc'=>'Un espace membre avec fil de discussion, réservé à vos acheteurs ou abonnés.',
     'features'=>['Annonces de l’organisateur','Discussions entre membres','Accès unique ou par abonnement']],
    ['key'=>'coaching','nom'=>'Coaching','icone'=>'fa-video','grad'=>'#ec4899,#db2777',
     'desc'=>'Vendez des séances : le client réserve un créneau selon vos disponibilités.',
     'features'=>['Disponibilité hebdomadaire (jours/heures)','Réservation dans de vrais créneaux','Confirmation + lien visio']],
];
@endphp

@push('styles')
<style>
.ct-wrap { max-width:980px; margin:0 auto; padding:1.5rem 1.25rem 5rem; }
.ct-step { font-size:0.72rem; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em; }
.ct-title { font-size:1.7rem; font-weight:800; color:#111827; margin:0.3rem 0 0.3rem; }
.ct-sub { color:#6b7280; font-size:0.9rem; margin-bottom:1.75rem; }

.ct-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.9rem; margin-bottom:1.5rem; }
@media(max-width:820px){ .ct-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:520px){ .ct-grid{ grid-template-columns:1fr; } }

.ct-card { position:relative; background:#fff; border:1.5px solid #e9eaeb; border-radius:16px; padding:1.25rem 1.2rem; cursor:pointer; transition:all .15s; }
.ct-card:hover { border-color:#c7d2fe; }
.ct-card.sel { border-color:#4f46e5; box-shadow:0 6px 22px rgba(79,70,229,0.12); }
.ct-icon { width:48px; height:48px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; color:#fff; margin-bottom:0.9rem; }
.ct-name { font-size:1rem; font-weight:800; color:#111827; }
.ct-radio { position:absolute; top:14px; right:14px; width:22px; height:22px; border-radius:50%; border:2px solid #d1d5db; display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.65rem; }
.ct-card.sel .ct-radio { background:#4f46e5; border-color:#4f46e5; }

/* Panneau description */
.ct-detail { background:#f8f9fb; border:1px solid #e9eaeb; border-radius:16px; padding:1.5rem 1.75rem; }
.ct-detail h2 { font-size:1.25rem; font-weight:800; color:#111827; margin:0 0 0.4rem; }
.ct-detail p { color:#6b7280; font-size:0.92rem; margin:0 0 1.1rem; }
.ct-feat { display:flex; align-items:center; gap:11px; padding:0.45rem 0; color:#374151; font-size:0.9rem; }
.ct-feat i { width:30px; height:30px; border-radius:8px; background:#eef2ff; color:#4f46e5; display:flex; align-items:center; justify-content:center; font-size:0.8rem; flex-shrink:0; }

.ct-continue { display:flex; justify-content:center; margin-top:1.75rem; }
.ct-btn { background:#111827; color:#fff; border:none; border-radius:12px; padding:0.95rem 3rem; font-weight:800; font-size:1rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:9px; transition:background .15s; }
.ct-btn:hover { background:#1f2937; color:#fff; }
</style>
@endpush

@section('content')
<div class="ct-wrap">
    <div class="ct-step">Étape 1 sur 3 · Type de produit</div>
    <h1 class="ct-title">Quel type de produit voulez-vous créer ?</h1>
    <p class="ct-sub">Choisissez le format qui correspond à ce que vous vendez, puis cliquez sur Continuer.</p>

    <div class="ct-grid">
        @foreach($types as $i => $t)
        <div class="ct-card {{ $i === 0 ? 'sel' : '' }}" data-key="{{ $t['key'] }}" onclick="ctSelect('{{ $t['key'] }}')">
            <div class="ct-radio"><i class="fas fa-check"></i></div>
            <div class="ct-icon" style="background:linear-gradient(135deg,{{ $t['grad'] }});"><i class="fas {{ $t['icone'] }}"></i></div>
            <div class="ct-name">{{ $t['nom'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Description du type sélectionné --}}
    @foreach($types as $i => $t)
    <div class="ct-detail" id="detail-{{ $t['key'] }}" style="{{ $i === 0 ? '' : 'display:none;' }}">
        <h2>{{ $t['nom'] }}</h2>
        <p>{{ $t['desc'] }}</p>
        @foreach($t['features'] as $f)
        <div class="ct-feat"><i class="fas fa-check"></i> {{ $f }}</div>
        @endforeach
    </div>
    @endforeach

    <div class="ct-continue">
        <a href="{{ route('admin.produits.create', ['format' => 'fichier']) }}" id="ct-continue-btn" class="ct-btn">
            Continuer <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<script>
var CT_ROUTES = {
    coaching:   "{{ route('admin.produits.create-coaching') }}",
    fichier:    "{{ route('admin.produits.create-fichier') }}",
    formation:  "{{ route('admin.produits.create-formation') }}",
    licence:    "{{ route('admin.produits.create-licence') }}",
    bundle:     "{{ route('admin.produits.create-bundle') }}",
    communaute: "{{ route('admin.produits.create-communaute') }}"
};
function ctSelect(key){
    document.querySelectorAll('.ct-card').forEach(c => c.classList.toggle('sel', c.dataset.key === key));
    document.querySelectorAll('.ct-detail').forEach(d => d.style.display = 'none');
    var det = document.getElementById('detail-' + key);
    if(det) det.style.display = '';
    var btn = document.getElementById('ct-continue-btn');
    if(btn) btn.href = CT_ROUTES[key] || ("{{ route('admin.produits.create') }}?format=" + key);
}
</script>
@endsection
