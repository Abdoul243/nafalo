@extends('layouts.admin')
@section('title', 'Nouveau produit fichier')

@push('styles')
<style>
.wz { max-width:760px; margin:0 auto; padding:1.5rem 1.25rem 5rem; }
.wz-top { display:flex; align-items:center; gap:12px; margin-bottom:1.5rem; }
.wz-ic { width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;flex-shrink:0; }
.wz-top h1 { font-size:1.2rem;font-weight:800;color:#111827;margin:0; }
.wz-top .s { font-size:0.82rem;color:#9ca3af; }
.wz-bar { display:flex; gap:6px; margin-bottom:0.5rem; }
.wz-bar span { flex:1; height:6px; border-radius:6px; background:#e9eaeb; transition:background .25s; }
.wz-bar span.done { background:#d97706; }
.wz-steplabel { font-size:0.72rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1.5rem; }
.wz-card { background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.75rem 1.85rem; }
.wz-card h2 { font-size:1.3rem;font-weight:800;color:#111827;margin:0 0 0.3rem; }
.wz-card .desc { color:#6b7280;font-size:0.9rem;margin-bottom:1.5rem; }
.wz-field { margin-bottom:1.1rem; }
.wz-field label { display:block;font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:6px; }
.wz-field input, .wz-field select, .wz-field textarea {
    width:100%;border:1px solid #d1d5db;border-radius:11px;padding:0.75rem 0.95rem;font-size:0.92rem;font-family:inherit;outline:none;
}
.wz-field input:focus, .wz-field select:focus, .wz-field textarea:focus { border-color:#d97706; }
.wz-err { color:#dc2626;font-size:0.78rem;margin-top:4px;display:none; }
.wz-upload { border:2px dashed #d1d5db;border-radius:12px;padding:2rem;text-align:center;cursor:pointer;color:#9ca3af; }
.wz-upload:hover { border-color:#d97706; }
.wz-upload i { font-size:2rem; }
.wz-recap-row { display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid #f3f4f6;font-size:0.9rem; }
.wz-recap-row .k { color:#6b7280; } .wz-recap-row .v { font-weight:700;color:#111827; }
.wz-nav { display:flex;justify-content:space-between;margin-top:1.5rem; }
.wz-btn { border:none;border-radius:12px;padding:0.85rem 2rem;font-weight:800;font-size:0.95rem;cursor:pointer; }
.wz-btn.next { background:#d97706;color:#fff; } .wz-btn.next:hover { background:#b45309; }
.wz-btn.back { background:#fff;color:#6b7280;border:1px solid #d1d5db; } .wz-btn.back:hover { background:#f3f4f6; }
</style>
@endpush

@section('content')
<div class="wz">
    <div class="wz-top">
        <div class="wz-ic"><i class="fas fa-file-arrow-down"></i></div>
        <div>
            <h1>Nouveau produit téléchargeable</h1>
            <div class="s">Vendez des fichiers numériques livrés instantanément après l'achat</div>
        </div>
    </div>

    <div class="wz-bar">
        <span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span>
    </div>
    <div class="wz-steplabel"><span id="wz-step-num">Étape 1 sur 4</span> · <span id="wz-step-name">Détails</span></div>

    <form action="{{ route('admin.produits.store-fichier') }}" method="POST" enctype="multipart/form-data" id="wz-form">
        @csrf

        {{-- ÉTAPE 1 : Détails --}}
        <div class="wz-card wz-step" data-step="1">
            <h2>Détails du produit</h2>
            <div class="desc">Le nom, la catégorie et le prix.</div>
            <div class="wz-field">
                <label>Nom du produit *</label>
                <input type="text" name="nom" id="wz-nom" value="{{ old('nom') }}" placeholder="Ex : Guide complet Facebook Ads 2026" required>
                <div class="wz-err" id="err-nom">Le nom est requis.</div>
            </div>
            <div class="wz-field">
                <label>Catégorie *</label>
                <select name="categorie_id" id="wz-cat" required>
                    <option value="">Dans quelle catégorie classer ce produit ?</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <div class="wz-err" id="err-cat">Choisissez une catégorie.</div>
            </div>
            <div class="wz-field">
                <label>Prix (FCFA) *</label>
                <input type="number" name="prix" id="wz-prix" min="0" value="{{ old('prix') }}" placeholder="Ex : 5000" required>
                <div class="wz-err" id="err-prix">Indiquez un prix.</div>
            </div>
        </div>

        {{-- ÉTAPE 2 : Fichier --}}
        <div class="wz-card wz-step" data-step="2" style="display:none;">
            <h2>Le fichier à livrer</h2>
            <div class="desc">PDF, ZIP, audio, vidéo… stocké en privé, livré uniquement après paiement.</div>
            <div class="wz-upload" onclick="document.getElementById('wz-fichier').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <div id="wz-fichier-name" style="margin-top:10px;font-weight:600;">Cliquez pour téléverser</div>
                <div style="font-size:0.78rem;margin-top:4px;">PDF, ZIP, MP3, MP4 — Max 100 Mo</div>
            </div>
            <input type="file" id="wz-fichier" name="fichier" accept=".pdf,.zip,.mp3,.mp4,.docx,.xlsx,.png,.jpg" style="display:none;"
                   onchange="document.getElementById('wz-fichier-name').textContent = this.files[0] ? this.files[0].name : 'Cliquez pour téléverser';">
            <div class="wz-err" id="err-fichier" style="margin-top:10px;">Veuillez ajouter un fichier.</div>
        </div>

        {{-- ÉTAPE 3 : Présentation --}}
        <div class="wz-card wz-step" data-step="3" style="display:none;">
            <h2>Présentation</h2>
            <div class="desc">Décrivez votre produit et ajoutez une image (optionnel).</div>
            <div class="wz-field">
                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Ce que contient le produit, à qui il s'adresse, ses bénéfices…">{{ old('description') }}</textarea>
            </div>
            <div class="wz-field">
                <label>Image de couverture <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <div class="wz-upload" style="padding:1.25rem;" onclick="document.getElementById('wz-image').click()">
                    <i class="fas fa-image" style="font-size:1.5rem;"></i>
                    <div id="wz-image-name" style="margin-top:8px;font-size:0.85rem;">Cliquez pour ajouter une image</div>
                </div>
                <input type="file" id="wz-image" name="image" accept="image/*" style="display:none;"
                       onchange="document.getElementById('wz-image-name').textContent = this.files[0] ? this.files[0].name : 'Cliquez pour ajouter une image';">
            </div>
        </div>

        {{-- ÉTAPE 4 : Récap --}}
        <div class="wz-card wz-step" data-step="4" style="display:none;">
            <h2>Récapitulatif</h2>
            <div class="desc">Vérifiez puis créez votre produit.</div>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="rc-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="rc-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="rc-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Fichier</span><span class="v" id="rc-fic">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;">
                <input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;">
                Publier immédiatement
            </label>
        </div>

        <div class="wz-nav">
            <button type="button" class="wz-btn back" id="wz-back" onclick="wzPrev()" style="visibility:hidden;">← Retour</button>
            <button type="button" class="wz-btn next" id="wz-next" onclick="wzNext()">Continuer →</button>
        </div>
    </form>
</div>

<script>
(function(){
    var step = 1, TOTAL = 4;
    var NAMES = {1:'Détails',2:'Fichier',3:'Présentation',4:'Récapitulatif'};
    function show(){
        document.querySelectorAll('.wz-step').forEach(s => s.style.display = (s.dataset.step==step)?'':'none');
        document.querySelectorAll('.wz-bar span').forEach(b => b.classList.toggle('done', b.dataset.seg<=step));
        document.getElementById('wz-step-num').textContent = 'Étape '+step+' sur '+TOTAL;
        document.getElementById('wz-step-name').textContent = NAMES[step];
        document.getElementById('wz-back').style.visibility = step>1 ? 'visible' : 'hidden';
        document.getElementById('wz-next').textContent = step==TOTAL ? 'Créer le produit' : 'Continuer →';
        window.scrollTo({top:0,behavior:'smooth'});
    }
    function valid(){
        document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');
        if(step===1){
            var ok=true;
            if(!document.getElementById('wz-nom').value.trim()){ document.getElementById('err-nom').style.display='block'; ok=false; }
            if(!document.getElementById('wz-cat').value){ document.getElementById('err-cat').style.display='block'; ok=false; }
            var p=document.getElementById('wz-prix').value;
            if(p===''||parseFloat(p)<0){ document.getElementById('err-prix').style.display='block'; ok=false; }
            return ok;
        }
        if(step===2){
            if(!document.getElementById('wz-fichier').files.length){ document.getElementById('err-fichier').style.display='block'; return false; }
        }
        return true;
    }
    function recap(){
        document.getElementById('rc-nom').textContent = document.getElementById('wz-nom').value||'—';
        var sel = document.getElementById('wz-cat'); document.getElementById('rc-cat').textContent = sel.options[sel.selectedIndex]?.text || '—';
        document.getElementById('rc-prix').textContent = (document.getElementById('wz-prix').value||'0')+' FCFA';
        var f = document.getElementById('wz-fichier').files[0];
        document.getElementById('rc-fic').textContent = f ? f.name : '—';
    }
    window.wzNext = function(){
        if(!valid()) return;
        if(step===TOTAL){ document.getElementById('wz-form').submit(); return; }
        step++; if(step===TOTAL) recap(); show();
    };
    window.wzPrev = function(){ if(step>1){ step--; show(); } };
    show();
})();
</script>
@endsection
