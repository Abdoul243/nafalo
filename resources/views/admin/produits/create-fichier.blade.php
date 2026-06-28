@extends('layouts.admin')
@section('title', 'Nouveau produit téléchargeable')

@push('styles')
<style>
.wz { max-width:820px; margin:0 auto; padding:1.5rem 1.25rem 5rem; }
.wz-hd { display:flex; align-items:center; gap:14px; margin-bottom:1.5rem; }
.wz-hd .ic { width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.35rem;flex-shrink:0; }
.wz-hd h1 { font-size:1.25rem;font-weight:800;color:#111827;margin:0; }
.wz-hd p { font-size:0.9rem;color:#9ca3af;margin:2px 0 0; }

/* Barre de progression (segments) */
.wz-bar { display:flex; gap:8px; margin-bottom:2rem; }
.wz-bar span { flex:1; height:7px; border-radius:7px; background:#e9eaeb; transition:background .25s; }
.wz-bar span.done { background:#111827; }

.wz-h2 { font-family:Georgia,'Times New Roman',serif; font-size:2rem; font-weight:700; color:#111827; margin:0 0 2rem; }

.wz-field { margin-bottom:1.5rem; }
.wz-field > label { display:block; font-size:0.98rem; font-weight:600; color:#1f2937; margin-bottom:0.6rem; }
.wz-field .req { color:#ef4444; }
.wz-field input, .wz-field select, .wz-field textarea {
    width:100%; border:1px solid #e5e7eb; border-radius:14px; padding:0.95rem 1.1rem; font-size:0.95rem; font-family:inherit; outline:none; color:#111827; background:#fff;
}
.wz-field input::placeholder, .wz-field textarea::placeholder { color:#9ca3af; }
.wz-field input:focus, .wz-field select:focus, .wz-field textarea:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
.wz-field select { -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 1.1rem center; }
.wz-2col { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:560px){ .wz-2col{ grid-template-columns:1fr; } }
.wz-err { color:#dc2626; font-size:0.8rem; margin-top:5px; display:none; }
.wz-suffix { position:relative; }
.wz-suffix input { padding-right:3.2rem; }
.wz-suffix .u { position:absolute; right:1.1rem; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.85rem; font-weight:600; pointer-events:none; }

.wz-upload { border:2px dashed #e5e7eb; border-radius:14px; padding:2rem; text-align:center; cursor:pointer; color:#9ca3af; }
.wz-upload:hover { border-color:#f59e0b; }
.wz-upload i { font-size:2rem; }
.wz-recap-row { display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid #f3f4f6; font-size:0.92rem; }
.wz-recap-row .k { color:#6b7280; } .wz-recap-row .v { font-weight:700; color:#111827; }

.wz-nav { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:2rem; }
.wz-btn { border:none; border-radius:14px; padding:0.9rem 2.2rem; font-weight:700; font-size:0.95rem; cursor:pointer; }
.wz-btn.next { background:#f59e0b; color:#fff; } .wz-btn.next:hover { background:#d97706; }
.wz-btn.cancel { background:#fff; color:#374151; border:1px solid #e5e7eb; } .wz-btn.cancel:hover { background:#f9fafb; }
.wz-btn.back { background:#fff; color:#6b7280; border:1px solid #e5e7eb; margin-right:auto; } .wz-btn.back:hover { background:#f9fafb; }
</style>
@endpush

@section('content')
<div class="wz">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-box-open"></i></div>
        <div>
            <h1>Créez un produit téléchargeable</h1>
            <p>Vendez des fichiers numériques livrés instantanément après l'achat.</p>
        </div>
    </div>

    <div class="wz-bar">
        <span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span>
    </div>

    <form action="{{ route('admin.produits.store-fichier') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf

        {{-- ÉTAPE 1 : Détails du produit (réplique Chariow) --}}
        <div class="wz-step" data-step="1">
            <h2 class="wz-h2">Détails du produit</h2>

            <div class="wz-field">
                <label>Nom du produit <span class="req">*</span></label>
                <input type="text" name="nom" id="f-nom" value="{{ old('nom') }}" placeholder="Ex: Guide complet Facebook Ads 2026" required>
                <div class="wz-err" id="e-nom">Le nom est requis.</div>
            </div>

            <div class="wz-field">
                <label>Catégorie <span class="req">*</span></label>
                <select name="categorie_id" id="f-cat" required>
                    <option value="">Dans quelle catégorie classer ce produit ?</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <div class="wz-err" id="e-cat">Choisissez une catégorie.</div>
            </div>

            <div class="wz-field">
                <label>Modèle de tarification <span class="req">*</span></label>
                <select name="type" id="f-type" onchange="majTarif()">
                    <option value="payant">Paiement unique</option>
                    <option value="gratuit">Gratuit</option>
                </select>
            </div>

            <div class="wz-2col" id="f-prix-wrap">
                <div class="wz-field" style="margin-bottom:0;">
                    <label>Prix</label>
                    <div class="wz-suffix">
                        <input type="number" name="prix" id="f-prix" min="0" value="{{ old('prix') }}" placeholder="0">
                        <span class="u">FCFA</span>
                    </div>
                    <div class="wz-err" id="e-prix">Indiquez un prix.</div>
                </div>
                <div class="wz-field" style="margin-bottom:0;">
                    <label>Prix promotionnel</label>
                    <div class="wz-suffix">
                        <input type="number" name="prix_promo" id="f-promo" min="0" value="{{ old('prix_promo') }}" placeholder="0">
                        <span class="u">FCFA</span>
                    </div>
                    <div class="wz-err" id="e-promo">Le prix promo doit être inférieur au prix.</div>
                </div>
            </div>
        </div>

        {{-- ÉTAPE 2 : Fichier --}}
        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Le fichier à livrer</h2>
            <div class="wz-upload" onclick="document.getElementById('f-fichier').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <div id="f-fichier-n" style="margin-top:10px;font-weight:600;">Cliquez pour téléverser</div>
                <div style="font-size:0.78rem;margin-top:4px;">PDF, ZIP, MP3, MP4 — Max 100 Mo · stocké en privé, livré après paiement</div>
            </div>
            <input type="file" id="f-fichier" name="fichier" accept=".pdf,.zip,.mp3,.mp4,.docx,.xlsx,.png,.jpg" style="display:none;"
                   onchange="document.getElementById('f-fichier-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour téléverser'">
            <div class="wz-err" id="e-fichier" style="margin-top:10px;">Veuillez ajouter un fichier.</div>
        </div>

        {{-- ÉTAPE 3 : Présentation --}}
        <div class="wz-step" data-step="3" style="display:none;">
            <h2 class="wz-h2">Présentation</h2>
            <div class="wz-field">
                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Ce que contient le produit, à qui il s'adresse, ses bénéfices…">{{ old('description') }}</textarea>
            </div>
            <div class="wz-field">
                <label>Image de couverture <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <div class="wz-upload" style="padding:1.25rem;" onclick="document.getElementById('f-img').click()">
                    <i class="fas fa-image" style="font-size:1.5rem;"></i>
                    <div id="f-img-n" style="margin-top:8px;font-size:0.85rem;">Cliquez pour ajouter une image</div>
                </div>
                <input type="file" id="f-img" name="image" accept="image/*" style="display:none;"
                       onchange="document.getElementById('f-img-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour ajouter une image'">
            </div>
        </div>

        {{-- ÉTAPE 4 : Récap --}}
        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Fichier</span><span class="v" id="r-fic">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;">
                <input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement
            </label>
        </div>

        <div class="wz-nav">
            <button type="button" class="wz-btn back" id="bk" onclick="P()" style="display:none;">← Retour</button>
            <a href="{{ route('admin.produits.choisir') }}" class="wz-btn cancel" id="cancel">Annuler</a>
            <button type="button" class="wz-btn next" id="nx" onclick="N()">Continuer</button>
        </div>
    </form>
</div>

<script>
function majTarif(){
    var g = document.getElementById('f-type').value === 'gratuit';
    document.getElementById('f-prix-wrap').style.display = g ? 'none' : '';
}
(function(){
    var s=1, T=4;
    function show(){
        document.querySelectorAll('.wz-step').forEach(x=>x.style.display=(x.dataset.step==s)?'':'none');
        document.querySelectorAll('.wz-bar span').forEach(b=>b.classList.toggle('done',b.dataset.seg<=s));
        document.getElementById('bk').style.display = s>1 ? '' : 'none';
        document.getElementById('cancel').style.display = s>1 ? 'none' : '';
        document.getElementById('nx').textContent = s==T ? 'Créer le produit' : 'Continuer';
        window.scrollTo({top:0,behavior:'smooth'});
    }
    function V(){
        document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');
        if(s===1){
            var o=true, g=document.getElementById('f-type').value==='gratuit';
            if(!document.getElementById('f-nom').value.trim()){ document.getElementById('e-nom').style.display='block'; o=false; }
            if(!document.getElementById('f-cat').value){ document.getElementById('e-cat').style.display='block'; o=false; }
            var p=document.getElementById('f-prix').value, pr=document.getElementById('f-promo').value;
            if(!g && (p===''||parseFloat(p)<0)){ document.getElementById('e-prix').style.display='block'; o=false; }
            if(!g && pr!=='' && parseFloat(pr)>=parseFloat(p||0)){ document.getElementById('e-promo').style.display='block'; o=false; }
            return o;
        }
        if(s===2){ if(!document.getElementById('f-fichier').files.length){ document.getElementById('e-fichier').style.display='block'; return false; } }
        return true;
    }
    function R(){
        document.getElementById('r-nom').textContent=document.getElementById('f-nom').value||'—';
        var sel=document.getElementById('f-cat'); document.getElementById('r-cat').textContent=sel.options[sel.selectedIndex]?.text||'—';
        var g=document.getElementById('f-type').value==='gratuit';
        document.getElementById('r-prix').textContent = g ? 'Gratuit' : (document.getElementById('f-prix').value||'0')+' FCFA';
        var f=document.getElementById('f-fichier').files[0]; document.getElementById('r-fic').textContent = f?f.name:'—';
    }
    window.N=function(){ if(!V())return; if(s===T){ document.getElementById('wzf').submit(); return; } s++; if(s===T)R(); show(); };
    window.P=function(){ if(s>1){ s--; show(); } };
    show();
})();
</script>
@endsection
