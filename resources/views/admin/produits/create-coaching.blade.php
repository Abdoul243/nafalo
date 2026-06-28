@extends('layouts.admin')
@section('title', 'Nouvelle séance de coaching')

@push('styles')
<style>
.wz { max-width:760px; margin:0 auto; padding:1.5rem 1.25rem 5rem; }
.wz-top { display:flex; align-items:center; gap:12px; margin-bottom:1.5rem; }
.wz-ic { width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#ec4899,#db2777);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;flex-shrink:0; }
.wz-top h1 { font-size:1.2rem;font-weight:800;color:#111827;margin:0; }
.wz-top .s { font-size:0.82rem;color:#9ca3af; }

/* Barre de progression horizontale */
.wz-bar { display:flex; gap:6px; margin-bottom:0.5rem; }
.wz-bar span { flex:1; height:6px; border-radius:6px; background:#e9eaeb; transition:background .25s; }
.wz-bar span.done { background:#db2777; }
.wz-steplabel { font-size:0.72rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1.5rem; }

.wz-card { background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.75rem 1.85rem; }
.wz-card h2 { font-size:1.3rem;font-weight:800;color:#111827;margin:0 0 0.3rem; }
.wz-card .desc { color:#6b7280;font-size:0.9rem;margin-bottom:1.5rem; }
.wz-field { margin-bottom:1.1rem; }
.wz-field label { display:block;font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:6px; }
.wz-field input, .wz-field select, .wz-field textarea {
    width:100%;border:1px solid #d1d5db;border-radius:11px;padding:0.75rem 0.95rem;font-size:0.92rem;font-family:inherit;outline:none;
}
.wz-field input:focus, .wz-field select:focus, .wz-field textarea:focus { border-color:#db2777; }
.wz-row { display:flex;gap:0.8rem;flex-wrap:wrap; }
.wz-row > * { flex:1;min-width:140px; }
.wz-err { color:#dc2626;font-size:0.78rem;margin-top:4px;display:none; }

/* Boutons préréglés (durée/pause) */
.wz-presets { display:flex;gap:0.5rem;flex-wrap:wrap; }
.wz-preset { border:1.5px solid #e5e7eb;background:#fff;border-radius:10px;padding:0.55rem 1.1rem;font-weight:700;font-size:0.85rem;color:#374151;cursor:pointer; }
.wz-preset.sel { border-color:#db2777;background:#db2777;color:#fff; }

/* Jours */
.wz-day { display:flex;align-items:center;gap:10px;padding:0.55rem 0;border-bottom:1px solid #f3f4f6; }
.wz-day label.d { display:flex;align-items:center;gap:8px;width:120px;cursor:pointer;font-weight:600;color:#111827;font-size:0.88rem; }
.wz-day input[type=time] { padding:0.4rem 0.6rem;border:1px solid #d1d5db;border-radius:8px;width:auto; }
.wz-day input[type=checkbox]{ width:18px;height:18px;accent-color:#db2777; }

/* Récap */
.wz-recap-row { display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid #f3f4f6;font-size:0.9rem; }
.wz-recap-row .k { color:#6b7280; }
.wz-recap-row .v { font-weight:700;color:#111827; }
.wz-upload { border:2px dashed #d1d5db;border-radius:12px;padding:1.5rem;text-align:center;cursor:pointer;color:#9ca3af; }
.wz-upload:hover { border-color:#db2777; }

.wz-nav { display:flex;justify-content:space-between;margin-top:1.5rem; }
.wz-btn { border:none;border-radius:12px;padding:0.85rem 2rem;font-weight:800;font-size:0.95rem;cursor:pointer; }
.wz-btn.next { background:#db2777;color:#fff; }
.wz-btn.next:hover { background:#be185d; }
.wz-btn.back { background:#fff;color:#6b7280;border:1px solid #d1d5db; }
.wz-btn.back:hover { background:#f3f4f6; }
</style>
@endpush

@section('content')
<div class="wz">
    <div class="wz-top">
        <div class="wz-ic"><i class="fas fa-video"></i></div>
        <div>
            <h1>Nouvelle séance de coaching</h1>
            <div class="s">Configurez votre offre étape par étape</div>
        </div>
    </div>

    <div class="wz-bar">
        <span class="done" data-seg="1"></span>
        <span data-seg="2"></span>
        <span data-seg="3"></span>
        <span data-seg="4"></span>
    </div>
    <div class="wz-steplabel"><span id="wz-step-num">Étape 1 sur 4</span> · <span id="wz-step-name">Détails</span></div>

    <form action="{{ route('admin.produits.store-coaching') }}" method="POST" enctype="multipart/form-data" id="wz-form">
        @csrf

        {{-- ÉTAPE 1 : Détails --}}
        <div class="wz-card wz-step" data-step="1">
            <h2>Détails de l'offre</h2>
            <div class="desc">Le nom, la catégorie et le prix de votre séance.</div>
            <div class="wz-field">
                <label>Nom de la séance *</label>
                <input type="text" name="nom" id="wz-nom" value="{{ old('nom') }}" placeholder="Ex : Appel stratégique 1-à-1" required>
                <div class="wz-err" id="err-nom">Le nom est requis.</div>
            </div>
            <div class="wz-field">
                <label>Catégorie *</label>
                <select name="categorie_id" id="wz-cat" required>
                    <option value="">Choisir…</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <div class="wz-err" id="err-cat">Choisissez une catégorie.</div>
            </div>
            <div class="wz-row">
                <div class="wz-field">
                    <label>Prix (FCFA) *</label>
                    <input type="number" name="prix" id="wz-prix" min="0" value="{{ old('prix') }}" placeholder="Ex : 15000" required>
                    <div class="wz-err" id="err-prix">Indiquez un prix.</div>
                </div>
            </div>
            <div class="wz-field">
                <label>Description <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <textarea name="description" rows="3" placeholder="À qui s'adresse cette séance, ce qu'elle apporte…">{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- ÉTAPE 2 : Paramètres --}}
        <div class="wz-card wz-step" data-step="2" style="display:none;">
            <h2>Paramètres de la séance</h2>
            <div class="desc">Durée et temps de battement entre deux séances.</div>
            <div class="wz-field">
                <label>Durée de la séance</label>
                <div class="wz-presets" id="duree-presets">
                    @foreach([15=>'15 min',30=>'30 min',45=>'45 min',60=>'1 h',90=>'1 h 30',120=>'2 h'] as $v=>$lbl)
                    <button type="button" class="wz-preset {{ $v==60?'sel':'' }}" data-val="{{ $v }}" onclick="wzPreset('duree',{{ $v }})">{{ $lbl }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="coaching_duree" id="wz-duree" value="60">
            </div>
            <div class="wz-field">
                <label>Pause entre les séances</label>
                <div class="wz-presets" id="pause-presets">
                    @foreach([0=>'Aucune',5=>'5 min',15=>'15 min',30=>'30 min',60=>'1 h'] as $v=>$lbl)
                    <button type="button" class="wz-preset {{ $v==0?'sel':'' }}" data-val="{{ $v }}" onclick="wzPreset('pause',{{ $v }})">{{ $lbl }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="coaching_pause" id="wz-pause" value="0">
            </div>
        </div>

        {{-- ÉTAPE 3 : Disponibilité --}}
        <div class="wz-card wz-step" data-step="3" style="display:none;">
            <h2>Votre disponibilité</h2>
            <div class="desc">Cochez vos jours et définissez les plages horaires. Le client réservera dans ces créneaux.</div>
            @foreach(['lundi'=>'Lundi','mardi'=>'Mardi','mercredi'=>'Mercredi','jeudi'=>'Jeudi','vendredi'=>'Vendredi','samedi'=>'Samedi','dimanche'=>'Dimanche'] as $key=>$label)
            <div class="wz-day">
                <label class="d"><input type="checkbox" name="jours[{{ $key }}][actif]" value="1" {{ in_array($key,['lundi','mardi','mercredi','jeudi','vendredi']) ? 'checked' : '' }}> {{ $label }}</label>
                <input type="time" name="jours[{{ $key }}][debut]" value="09:00">
                <span style="color:#9ca3af;">→</span>
                <input type="time" name="jours[{{ $key }}][fin]" value="17:00">
            </div>
            @endforeach
        </div>

        {{-- ÉTAPE 4 : Récap --}}
        <div class="wz-card wz-step" data-step="4" style="display:none;">
            <h2>Récapitulatif</h2>
            <div class="desc">Vérifiez puis créez votre séance.</div>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="rc-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="rc-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Durée</span><span class="v" id="rc-duree">—</span></div>
            <div class="wz-recap-row"><span class="k">Pause</span><span class="v" id="rc-pause">—</span></div>
            <div class="wz-recap-row"><span class="k">Jours disponibles</span><span class="v" id="rc-jours">—</span></div>

            <div class="wz-field" style="margin-top:1.25rem;">
                <label>Image de couverture <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <div class="wz-upload" onclick="document.getElementById('wz-image').click()">
                    <i class="fas fa-image fa-2x"></i>
                    <div id="wz-image-name" style="margin-top:8px;font-size:0.85rem;">Cliquez pour ajouter une image</div>
                </div>
                <input type="file" id="wz-image" name="image" accept="image/*" style="display:none;"
                       onchange="document.getElementById('wz-image-name').textContent = this.files[0] ? this.files[0].name : 'Cliquez pour ajouter une image';">
            </div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;">
                <input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;">
                Publier immédiatement
            </label>
        </div>

        {{-- Navigation --}}
        <div class="wz-nav">
            <button type="button" class="wz-btn back" id="wz-back" onclick="wzPrev()" style="visibility:hidden;">← Retour</button>
            <button type="button" class="wz-btn next" id="wz-next" onclick="wzNext()">Continuer →</button>
        </div>
    </form>
</div>

<script>
(function(){
    var step = 1, TOTAL = 4;
    var NAMES = {1:'Détails',2:'Paramètres',3:'Disponibilité',4:'Récapitulatif'};

    function show(){
        document.querySelectorAll('.wz-step').forEach(function(s){ s.style.display = (s.dataset.step==step)?'':'none'; });
        document.querySelectorAll('.wz-bar span').forEach(function(b){ b.classList.toggle('done', b.dataset.seg<=step); });
        document.getElementById('wz-step-num').textContent = 'Étape '+step+' sur '+TOTAL;
        document.getElementById('wz-step-name').textContent = NAMES[step];
        document.getElementById('wz-back').style.visibility = step>1 ? 'visible' : 'hidden';
        document.getElementById('wz-next').textContent = step==TOTAL ? 'Créer la séance' : 'Continuer →';
        window.scrollTo({top:0,behavior:'smooth'});
    }

    function validStep(){
        document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');
        if(step===1){
            var ok = true;
            if(!document.getElementById('wz-nom').value.trim()){ document.getElementById('err-nom').style.display='block'; ok=false; }
            if(!document.getElementById('wz-cat').value){ document.getElementById('err-cat').style.display='block'; ok=false; }
            var p = document.getElementById('wz-prix').value;
            if(p===''||parseFloat(p)<0){ document.getElementById('err-prix').style.display='block'; ok=false; }
            return ok;
        }
        return true;
    }

    function fillRecap(){
        document.getElementById('rc-nom').textContent = document.getElementById('wz-nom').value || '—';
        document.getElementById('rc-prix').textContent = (document.getElementById('wz-prix').value||'0')+' FCFA';
        document.getElementById('rc-duree').textContent = document.getElementById('wz-duree').value+' min';
        var pa = document.getElementById('wz-pause').value;
        document.getElementById('rc-pause').textContent = pa==0 ? 'Aucune' : pa+' min';
        var jours = [];
        document.querySelectorAll('.wz-day').forEach(function(d){
            var cb = d.querySelector('input[type=checkbox]');
            if(cb.checked){ jours.push(d.querySelector('label.d').textContent.trim()); }
        });
        document.getElementById('rc-jours').textContent = jours.length ? jours.join(', ') : 'Aucun';
    }

    window.wzNext = function(){
        if(!validStep()) return;
        if(step===TOTAL){ document.getElementById('wz-form').submit(); return; }
        step++; if(step===TOTAL) fillRecap(); show();
    };
    window.wzPrev = function(){ if(step>1){ step--; show(); } };
    window.wzPreset = function(grp,val){
        document.getElementById('wz-'+grp).value = val;
        document.querySelectorAll('#'+grp+'-presets .wz-preset').forEach(function(b){ b.classList.toggle('sel', b.dataset.val==val); });
    };

    show();
})();
</script>
@endsection
