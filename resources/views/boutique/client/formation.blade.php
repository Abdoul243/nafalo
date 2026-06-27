@extends('layouts.boutique')

@section('title', $produit->nom)

@php
    $lecons = $produit->modules->flatMap->lecons->values();
    $total  = $lecons->count();
    $nbTermine = collect($terminees)->count();
    $pct = $total ? round($nbTermine / $total * 100) : 0;
@endphp

@push('styles')
<style>
body { background:#f6f7f9; }
.lms { max-width:1320px; margin:0 auto; padding:1.25rem 1rem 4rem; display:grid; grid-template-columns:1fr 380px; gap:1.5rem; align-items:start; }
@media(max-width:980px){ .lms{ grid-template-columns:1fr; } .lms-side{ order:-1; } }

/* ── Header cours ── */
.lms-top { grid-column:1 / -1; background:#0f172a; color:#fff; border-radius:16px; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
.lms-top .t { flex:1; min-width:200px; }
.lms-top h1 { font-size:1.25rem; font-weight:800; margin:0 0 4px; line-height:1.3; }
.lms-top .sub { font-size:0.8rem; color:#94a3b8; }
.lms-ring { display:flex; align-items:center; gap:12px; }
.lms-ring svg { transform:rotate(-90deg); }
.lms-ring .pct { font-size:1.1rem; font-weight:800; }
.lms-ring .lbl { font-size:0.72rem; color:#94a3b8; }
.lms-back { color:#cbd5e1; text-decoration:none; font-size:0.8rem; display:inline-flex; align-items:center; gap:6px; }
.lms-back:hover { color:#fff; }

/* ── Main : lecteur ── */
.lms-main { background:#fff; border:1px solid #e9eaeb; border-radius:16px; overflow:hidden; }
.lms-video { background:#000; aspect-ratio:16/9; width:100%; position:relative; }
.lms-video iframe, .lms-video video { width:100%; height:100%; border:none; display:block; }
.lms-novideo { aspect-ratio:16/9; background:linear-gradient(135deg,#1e1b4b,#4f46e5); display:flex; flex-direction:column; align-items:center; justify-content:center; color:rgba(255,255,255,0.85); gap:10px; }
.lms-novideo i { font-size:2.5rem; opacity:0.7; }
.lms-body { padding:1.5rem 1.85rem 2rem; }
.lms-crumb { font-size:0.75rem; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px; }
.lms-body h2 { font-size:1.4rem; font-weight:800; color:#111827; margin:0 0 1rem; line-height:1.3; }
.lms-tabs { display:flex; gap:4px; border-bottom:1px solid #eee; margin-bottom:1.1rem; }
.lms-tab { padding:0.6rem 1rem; font-size:0.85rem; font-weight:700; color:#9ca3af; cursor:pointer; border-bottom:2px solid transparent; background:none; border-top:none; border-left:none; border-right:none; }
.lms-tab.active { color:#4f46e5; border-bottom-color:#4f46e5; }
.lms-pane { display:none; }
.lms-pane.active { display:block; }
.lms-content { color:#374151; line-height:1.85; font-size:0.96rem; white-space:pre-wrap; }
.lms-empty-txt { color:#9ca3af; font-style:italic; }
.lms-res { display:inline-flex; align-items:center; gap:10px; background:#eef2ff; color:#4f46e5; padding:0.85rem 1.2rem; border-radius:12px; text-decoration:none; font-weight:700; font-size:0.88rem; }
.lms-res:hover { background:#e0e7ff; }
.lms-res-none { color:#9ca3af; font-size:0.88rem; }

.lms-actions { display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:1.1rem 1.85rem; border-top:1px solid #eee; flex-wrap:wrap; background:#fafafa; }
.lms-done { background:#16a34a; color:#fff; border:none; border-radius:11px; padding:0.75rem 1.4rem; font-weight:800; font-size:0.9rem; cursor:pointer; display:inline-flex; align-items:center; gap:9px; }
.lms-done.is-done { background:#fff; color:#16a34a; border:1.5px solid #16a34a; }
.lms-nav { display:flex; gap:0.5rem; }
.lms-nav button { background:#fff; border:1px solid #d1d5db; border-radius:10px; padding:0.65rem 1.1rem; font-weight:700; font-size:0.85rem; cursor:pointer; color:#374151; }
.lms-nav button:hover:not(:disabled){ background:#f3f4f6; border-color:#9ca3af; }
.lms-nav button:disabled { opacity:0.4; cursor:not-allowed; }

/* ── Sidebar curriculum ── */
.lms-side { background:#fff; border:1px solid #e9eaeb; border-radius:16px; overflow:hidden; position:sticky; top:80px; }
.lms-side-head { padding:1.1rem 1.25rem; border-bottom:1px solid #eee; }
.lms-side-head .h { font-size:0.95rem; font-weight:800; color:#111827; }
.lms-side-head .m { font-size:0.78rem; color:#9ca3af; margin-top:2px; }
.lms-bar { height:8px; background:#eef2ff; border-radius:20px; overflow:hidden; margin-top:10px; }
.lms-bar > div { height:100%; background:linear-gradient(90deg,#6366f1,#4f46e5); width:0; transition:width .35s; }

.lms-mods { max-height:68vh; overflow-y:auto; }
.lms-mod { border-bottom:1px solid #f1f5f9; }
.lms-mod-head { display:flex; align-items:center; gap:10px; padding:0.9rem 1.25rem; cursor:pointer; user-select:none; background:#fcfcfd; }
.lms-mod-head:hover { background:#f8fafc; }
.lms-mod-head .ix { width:24px; height:24px; border-radius:7px; background:#eef2ff; color:#4f46e5; font-size:0.72rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.lms-mod-head .tt { flex:1; min-width:0; }
.lms-mod-head .tt .nm { font-weight:700; font-size:0.88rem; color:#111827; }
.lms-mod-head .tt .me { font-size:0.72rem; color:#9ca3af; margin-top:1px; }
.lms-mod-head .chev { color:#9ca3af; font-size:0.75rem; transition:transform .2s; }
.lms-mod.collapsed .chev { transform:rotate(-90deg); }
.lms-mod.collapsed .lms-mod-lecons { display:none; }

.lms-lec { display:flex; align-items:center; gap:11px; padding:0.6rem 1.25rem 0.6rem 1.4rem; cursor:pointer; border-left:3px solid transparent; }
.lms-lec:hover { background:#f8fafc; }
.lms-lec.active { background:#eef2ff; border-left-color:#4f46e5; }
.lms-lec .ck { width:20px; height:20px; border-radius:50%; border:2px solid #d1d5db; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.55rem; color:#fff; }
.lms-lec.done .ck { background:#16a34a; border-color:#16a34a; }
.lms-lec .lt { flex:1; min-width:0; font-size:0.84rem; color:#374151; font-weight:500; line-height:1.35; }
.lms-lec.active .lt { color:#4f46e5; font-weight:700; }
.lms-lec .lm { font-size:0.7rem; color:#9ca3af; display:flex; align-items:center; gap:4px; flex-shrink:0; }

/* ── Bannière complétion ── */
.lms-complete { grid-column:1 / -1; display:none; background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; border-radius:16px; padding:1.25rem 1.5rem; align-items:center; gap:14px; }
.lms-complete.show { display:flex; }
.lms-complete i { font-size:1.8rem; }
.lms-complete strong { font-size:1.05rem; }
</style>
@endpush

@section('content')
<div class="lms">

    {{-- ── Header ── --}}
    <div class="lms-top">
        <div class="t">
            <a href="{{ route('client.mes-achats.index') }}" class="lms-back"><i class="fas fa-arrow-left"></i> Mes formations</a>
            <h1>{{ $produit->nom }}</h1>
            <div class="sub">{{ $produit->modules->count() }} module(s) · {{ $total }} leçon(s)</div>
        </div>
        <div class="lms-ring">
            <svg width="56" height="56" viewBox="0 0 56 56">
                <circle cx="28" cy="28" r="24" fill="none" stroke="#1e293b" stroke-width="6"/>
                <circle id="ring-fg" cx="28" cy="28" r="24" fill="none" stroke="#22c55e" stroke-width="6"
                        stroke-linecap="round" stroke-dasharray="150.8" stroke-dashoffset="150.8"/>
            </svg>
            <div>
                <div class="pct"><span id="pct-num">{{ $pct }}</span>%</div>
                <div class="lbl"><span id="done-num">{{ $nbTermine }}</span>/{{ $total }} terminées</div>
            </div>
        </div>
    </div>

    {{-- ── Bannière complétion ── --}}
    <div class="lms-complete" id="lms-complete">
        <i class="fas fa-trophy"></i>
        <div><strong>Félicitations ! Formation terminée 🎉</strong><br>
            <span style="font-size:0.85rem;opacity:0.9;">Vous avez complété toutes les leçons.</span></div>
    </div>

    {{-- ── Lecteur ── --}}
    <main class="lms-main">
        @foreach($lecons as $index => $lecon)
        <div class="lms-panel" data-panel="{{ $lecon->id }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
            @if($lecon->typeVideo() === 'lien')
                <div class="lms-video"><iframe src="{{ $lecon->videoEmbedUrl() }}" allowfullscreen allow="autoplay; encrypted-media"></iframe></div>
            @elseif($lecon->typeVideo() === 'upload')
                <div class="lms-video"><video controls preload="metadata" src="{{ route('client.formation.lecon.video', $lecon) }}"></video></div>
            @else
                <div class="lms-novideo"><i class="fas fa-book-open"></i><span>Leçon en lecture</span></div>
            @endif

            <div class="lms-body">
                <div class="lms-crumb">{{ $lecon->module->titre }}</div>
                <h2>{{ $lecon->titre }}</h2>

                <div class="lms-tabs">
                    <button class="lms-tab active" onclick="lmsTab(this,'desc-{{ $lecon->id }}')">Description</button>
                    <button class="lms-tab" onclick="lmsTab(this,'res-{{ $lecon->id }}')">Ressources</button>
                </div>
                <div class="lms-pane active" id="desc-{{ $lecon->id }}">
                    @if($lecon->contenu)
                        <div class="lms-content">{{ $lecon->contenu }}</div>
                    @else
                        <div class="lms-empty-txt">Pas de description pour cette leçon.</div>
                    @endif
                </div>
                <div class="lms-pane" id="res-{{ $lecon->id }}">
                    @if($lecon->ressource_fichier)
                        <a class="lms-res" href="{{ route('client.formation.lecon.ressource', $lecon) }}">
                            <i class="fas fa-download"></i> Télécharger la ressource
                        </a>
                    @else
                        <div class="lms-res-none">Aucune ressource pour cette leçon.</div>
                    @endif
                </div>
            </div>

            <div class="lms-actions">
                <button class="lms-done {{ in_array($lecon->id, $terminees) ? 'is-done' : '' }}"
                        data-done-btn="{{ $lecon->id }}" onclick="lmsToggle({{ $lecon->id }})">
                    <i class="fas fa-check"></i>
                    <span>{{ in_array($lecon->id, $terminees) ? 'Leçon terminée' : 'Marquer comme terminée' }}</span>
                </button>
                <div class="lms-nav">
                    <button onclick="lmsPrev()" {{ $index === 0 ? 'disabled' : '' }}>← Précédent</button>
                    <button onclick="lmsNext()" {{ $index === $total - 1 ? 'disabled' : '' }}>Suivant →</button>
                </div>
            </div>
        </div>
        @endforeach
    </main>

    {{-- ── Curriculum ── --}}
    <aside class="lms-side">
        <div class="lms-side-head">
            <div class="h">Contenu de la formation</div>
            <div class="m"><span id="pct-num2">{{ $pct }}</span>% terminé</div>
            <div class="lms-bar"><div id="lms-bar-fg"></div></div>
        </div>
        <div class="lms-mods">
            @foreach($produit->modules as $mi => $module)
            @php
                $mLecons = $module->lecons;
                $mDone = $mLecons->whereIn('id', $terminees)->count();
                $mDuree = $mLecons->sum('duree');
            @endphp
            <div class="lms-mod" data-mod="{{ $module->id }}">
                <div class="lms-mod-head" onclick="lmsToggleMod({{ $module->id }})">
                    <span class="ix">{{ $mi + 1 }}</span>
                    <div class="tt">
                        <div class="nm">{{ $module->titre }}</div>
                        <div class="me">{{ $mDone }}/{{ $mLecons->count() }} leçons@if($mDuree) · {{ $mDuree }} min @endif</div>
                    </div>
                    <i class="fas fa-chevron-down chev"></i>
                </div>
                <div class="lms-mod-lecons">
                    @foreach($mLecons as $lecon)
                    <div class="lms-lec {{ in_array($lecon->id, $terminees) ? 'done' : '' }}"
                         data-lecon="{{ $lecon->id }}" data-mod="{{ $module->id }}" onclick="lmsOpen({{ $lecon->id }})">
                        <span class="ck"><i class="fas fa-check"></i></span>
                        <span class="lt">{{ $lecon->titre }}</span>
                        <span class="lm">
                            @if($lecon->typeVideo())<i class="fas fa-play-circle"></i>@else<i class="fas fa-file-lines"></i>@endif
                            @if($lecon->duree){{ $lecon->duree }}m @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const ORDER = @json($lecons->pluck('id')->values());
    const LEC_MOD = @json($lecons->mapWithKeys(fn($l)=>[$l->id => $l->module_id]));
    const DONE  = new Set(@json(collect($terminees)->values()));
    const TOTAL = {{ $total }};
    const CSRF  = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
    const RING_LEN = 150.8;
    let current = ORDER.length ? ORDER[0] : null;

    function refresh(){
        const n = DONE.size;
        const pct = TOTAL ? Math.round(n/TOTAL*100) : 0;
        ['pct-num','pct-num2'].forEach(id=>{ const e=document.getElementById(id); if(e) e.textContent=pct; });
        const dn = document.getElementById('done-num'); if(dn) dn.textContent = n;
        const bar = document.getElementById('lms-bar-fg'); if(bar) bar.style.width = pct+'%';
        const ring = document.getElementById('ring-fg'); if(ring) ring.style.strokeDashoffset = RING_LEN*(1-pct/100);
        const comp = document.getElementById('lms-complete'); if(comp) comp.classList.toggle('show', TOTAL>0 && n===TOTAL);
        // compteurs par module
        document.querySelectorAll('.lms-mod').forEach(mod=>{
            const id = mod.dataset.mod;
            const lecs = mod.querySelectorAll('.lms-lec');
            let done=0; lecs.forEach(l=>{ if(DONE.has(parseInt(l.dataset.lecon))) done++; });
            const me = mod.querySelector('.me');
            if(me){ me.textContent = me.textContent.replace(/^\d+\/\d+/, done+'/'+lecs.length); }
        });
    }

    window.lmsOpen = function(id){
        current = id;
        document.querySelectorAll('.lms-panel').forEach(p=> p.style.display = (p.dataset.panel==id?'':'none'));
        document.querySelectorAll('.lms-lec').forEach(l=> l.classList.toggle('active', l.dataset.lecon==id));
        // ouvre le module de la leçon
        const modId = LEC_MOD[id];
        const mod = document.querySelector('.lms-mod[data-mod="'+modId+'"]');
        if(mod) mod.classList.remove('collapsed');
        const main = document.querySelector('.lms-main');
        if(main && window.innerWidth < 980) window.scrollTo({ top: main.offsetTop-70, behavior:'smooth' });
    };
    window.lmsNext = function(){ const i=ORDER.indexOf(current); if(i<ORDER.length-1) lmsOpen(ORDER[i+1]); };
    window.lmsPrev = function(){ const i=ORDER.indexOf(current); if(i>0) lmsOpen(ORDER[i-1]); };
    window.lmsToggleMod = function(id){ const m=document.querySelector('.lms-mod[data-mod="'+id+'"]'); if(m) m.classList.toggle('collapsed'); };
    window.lmsTab = function(btn, paneId){
        const body = btn.closest('.lms-body');
        body.querySelectorAll('.lms-tab').forEach(t=>t.classList.remove('active'));
        body.querySelectorAll('.lms-pane').forEach(p=>p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(paneId)?.classList.add('active');
    };

    window.lmsToggle = function(id){
        const terminee = !DONE.has(id);
        fetch('{{ url("/client/lecon") }}/'+id+'/terminer', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify({ terminee })
        }).then(r=>r.json()).then(d=>{
            if(!d.success) return;
            if(terminee) DONE.add(id); else DONE.delete(id);
            const lec = document.querySelector('.lms-lec[data-lecon="'+id+'"]');
            if(lec) lec.classList.toggle('done', terminee);
            const btn = document.querySelector('.lms-done[data-done-btn="'+id+'"]');
            if(btn){ btn.classList.toggle('is-done', terminee); btn.querySelector('span').textContent = terminee?'Leçon terminée':'Marquer comme terminée'; }
            refresh();
            if(terminee) setTimeout(window.lmsNext, 500);
        }).catch(()=>{});
    };

    // init
    if(current){ lmsOpen(current); }
    refresh();
})();
</script>
@endpush
