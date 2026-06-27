@extends('layouts.boutique')

@section('title', $produit->nom)

@push('styles')
<style>
body { background:#f8f9fa; }
.fm-wrap { max-width:1280px; margin:0 auto; padding:1.5rem 1.25rem 4rem; display:grid; grid-template-columns:340px 1fr; gap:1.5rem; align-items:start; }
@media(max-width:900px){ .fm-wrap{ grid-template-columns:1fr; } }

/* Sidebar */
.fm-side { background:#fff; border:1px solid #e9eaeb; border-radius:14px; overflow:hidden; position:sticky; top:80px; }
.fm-side-head { padding:1.1rem 1.25rem; border-bottom:1px solid #eee; }
.fm-side-head h1 { font-size:1.05rem; font-weight:800; color:#111827; margin:0 0 0.6rem; line-height:1.3; }
.fm-progress { height:8px; background:#eef2ff; border-radius:20px; overflow:hidden; }
.fm-progress > div { height:100%; background:linear-gradient(90deg,#6366f1,#4f46e5); width:0; transition:width .3s; }
.fm-progress-txt { font-size:0.75rem; color:#6b7280; margin-top:6px; }

.fm-modules { max-height:65vh; overflow-y:auto; }
.fm-mod-title { padding:0.85rem 1.25rem 0.4rem; font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:#9ca3af; }
.fm-lec { display:flex; align-items:center; gap:10px; padding:0.6rem 1.25rem; cursor:pointer; border-left:3px solid transparent; transition:background .12s; }
.fm-lec:hover { background:#f8fafc; }
.fm-lec.active { background:#eef2ff; border-left-color:#4f46e5; }
.fm-lec .chk { width:22px; height:22px; border-radius:50%; border:2px solid #d1d5db; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.6rem; color:#fff; }
.fm-lec.done .chk { background:#16a34a; border-color:#16a34a; }
.fm-lec .l-t { flex:1; font-size:0.85rem; color:#374151; font-weight:500; }
.fm-lec.active .l-t { color:#4f46e5; font-weight:700; }
.fm-lec .l-d { font-size:0.7rem; color:#9ca3af; }

/* Content */
.fm-main { background:#fff; border:1px solid #e9eaeb; border-radius:14px; overflow:hidden; }
.fm-video { background:#000; aspect-ratio:16/9; width:100%; }
.fm-video iframe, .fm-video video { width:100%; height:100%; border:none; display:block; }
.fm-novideo { aspect-ratio:16/9; background:linear-gradient(135deg,#1e1b4b,#4f46e5); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.5); font-size:3rem; }
.fm-body { padding:1.5rem 1.75rem 2rem; }
.fm-body h2 { font-size:1.25rem; font-weight:800; color:#111827; margin:0 0 0.5rem; }
.fm-meta { font-size:0.8rem; color:#9ca3af; margin-bottom:1.25rem; }
.fm-content { color:#374151; line-height:1.8; font-size:0.95rem; white-space:pre-wrap; }
.fm-res { display:inline-flex; align-items:center; gap:8px; margin-top:1.25rem; background:#eef2ff; color:#4f46e5; padding:0.7rem 1.1rem; border-radius:10px; text-decoration:none; font-weight:600; font-size:0.85rem; }
.fm-res:hover { background:#e0e7ff; }

.fm-actions { display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:1.25rem 1.75rem; border-top:1px solid #eee; flex-wrap:wrap; }
.fm-btn-done { background:#16a34a; color:#fff; border:none; border-radius:10px; padding:0.7rem 1.25rem; font-weight:700; font-size:0.875rem; cursor:pointer; display:inline-flex; align-items:center; gap:8px; }
.fm-btn-done.is-done { background:#fff; color:#16a34a; border:1.5px solid #16a34a; }
.fm-nav { display:flex; gap:0.5rem; }
.fm-nav button { background:#fff; border:1px solid #d1d5db; border-radius:9px; padding:0.6rem 1rem; font-weight:600; font-size:0.85rem; cursor:pointer; color:#374151; }
.fm-nav button:hover:not(:disabled){ background:#f3f4f6; }
.fm-nav button:disabled { opacity:0.4; cursor:not-allowed; }
</style>
@endpush

@section('content')
@php
    $lecons = $produit->modules->flatMap->lecons->values();
    $total  = $lecons->count();
@endphp

<div class="fm-wrap">

    {{-- Sidebar : programme --}}
    <aside class="fm-side">
        <div class="fm-side-head">
            <h1>{{ $produit->nom }}</h1>
            <div class="fm-progress"><div id="fm-bar"></div></div>
            <div class="fm-progress-txt"><span id="fm-done">0</span> / {{ $total }} leçons terminées</div>
        </div>
        <div class="fm-modules">
            @foreach($produit->modules as $module)
                <div class="fm-mod-title">{{ $module->titre }}</div>
                @foreach($module->lecons as $lecon)
                <div class="fm-lec {{ in_array($lecon->id, $terminees) ? 'done' : '' }}"
                     data-lecon="{{ $lecon->id }}" onclick="fmOpen({{ $lecon->id }})">
                    <span class="chk"><i class="fas fa-check"></i></span>
                    <span class="l-t">{{ $lecon->titre }}</span>
                    @if($lecon->duree)<span class="l-d">{{ $lecon->duree }}m</span>@endif
                </div>
                @endforeach
            @endforeach
        </div>
    </aside>

    {{-- Contenu : leçon courante --}}
    <main class="fm-main">
        @foreach($lecons as $index => $lecon)
        <div class="fm-lecon-panel" data-panel="{{ $lecon->id }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
            <div>
                @if($lecon->typeVideo() === 'lien')
                    <div class="fm-video"><iframe src="{{ $lecon->videoEmbedUrl() }}" allowfullscreen allow="autoplay; encrypted-media"></iframe></div>
                @elseif($lecon->typeVideo() === 'upload')
                    <div class="fm-video"><video controls preload="metadata" src="{{ route('client.formation.lecon.video', $lecon) }}"></video></div>
                @else
                    <div class="fm-novideo"><i class="fas fa-align-left"></i></div>
                @endif
            </div>
            <div class="fm-body">
                <h2>{{ $lecon->titre }}</h2>
                <div class="fm-meta">
                    Leçon {{ $index + 1 }} / {{ $total }}
                    @if($lecon->duree) · {{ $lecon->duree }} min @endif
                </div>
                @if($lecon->contenu)<div class="fm-content">{{ $lecon->contenu }}</div>@endif
                @if($lecon->ressource_fichier)
                    <a class="fm-res" href="{{ route('client.formation.lecon.ressource', $lecon) }}">
                        <i class="fas fa-download"></i> Télécharger la ressource
                    </a>
                @endif
            </div>
            <div class="fm-actions">
                <button class="fm-btn-done {{ in_array($lecon->id, $terminees) ? 'is-done' : '' }}"
                        data-done-btn="{{ $lecon->id }}" onclick="fmToggle({{ $lecon->id }})">
                    <i class="fas fa-check"></i>
                    <span>{{ in_array($lecon->id, $terminees) ? 'Terminée' : 'Marquer comme terminée' }}</span>
                </button>
                <div class="fm-nav">
                    <button onclick="fmPrev()" {{ $index === 0 ? 'disabled' : '' }} data-prev="{{ $lecon->id }}">← Précédent</button>
                    <button onclick="fmNext()" {{ $index === $total - 1 ? 'disabled' : '' }} data-next="{{ $lecon->id }}">Suivant →</button>
                </div>
            </div>
        </div>
        @endforeach
    </main>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const ORDER = @json($lecons->pluck('id')->values());
    const DONE  = new Set(@json($terminees));
    const TOTAL = {{ $total }};
    const CSRF  = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
    let current = ORDER[0];

    function refreshProgress(){
        const n = DONE.size;
        document.getElementById('fm-done').textContent = n;
        document.getElementById('fm-bar').style.width = TOTAL ? (n/TOTAL*100)+'%' : '0%';
    }

    window.fmOpen = function(id){
        current = id;
        document.querySelectorAll('.fm-lecon-panel').forEach(p => p.style.display = (p.dataset.panel == id ? '' : 'none'));
        document.querySelectorAll('.fm-lec').forEach(l => l.classList.toggle('active', l.dataset.lecon == id));
        const top = document.querySelector('.fm-main');
        if (top) window.scrollTo({ top: top.offsetTop - 80, behavior:'smooth' });
    };
    window.fmNext = function(){ const i = ORDER.indexOf(current); if(i < ORDER.length-1) fmOpen(ORDER[i+1]); };
    window.fmPrev = function(){ const i = ORDER.indexOf(current); if(i > 0) fmOpen(ORDER[i-1]); };

    window.fmToggle = function(id){
        const terminee = !DONE.has(id);
        fetch('{{ url("/client/lecon") }}/' + id + '/terminer', {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
            body: JSON.stringify({ terminee })
        }).then(r => r.json()).then(d => {
            if(!d.success) return;
            if(terminee) DONE.add(id); else DONE.delete(id);
            // maj sidebar
            const lec = document.querySelector('.fm-lec[data-lecon="'+id+'"]');
            if(lec) lec.classList.toggle('done', terminee);
            // maj bouton
            const btn = document.querySelector('.fm-btn-done[data-done-btn="'+id+'"]');
            if(btn){ btn.classList.toggle('is-done', terminee); btn.querySelector('span').textContent = terminee ? 'Terminée' : 'Marquer comme terminée'; }
            refreshProgress();
            if(terminee) setTimeout(window.fmNext, 400);
        }).catch(()=>{});
    };

    // init : ouvre la 1re leçon + barre
    if(ORDER.length){ fmOpen(ORDER[0]); }
    refreshProgress();
})();
</script>
@endpush
