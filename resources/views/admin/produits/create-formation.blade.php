@extends('layouts.admin')
@section('title', 'Nouvelle formation')
@php $accent='#4f46e5'; $accentD='#4338ca'; @endphp

@push('styles')
<style>
.wz{max-width:760px;margin:0 auto;padding:1.5rem 1.25rem 5rem}
.wz-top{display:flex;align-items:center;gap:12px;margin-bottom:1.5rem}
.wz-ic{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#6366f1,{{$accent}});display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem}
.wz-top h1{font-size:1.2rem;font-weight:800;color:#111827;margin:0}.wz-top .s{font-size:.82rem;color:#9ca3af}
.wz-bar{display:flex;gap:6px;margin-bottom:.5rem}.wz-bar span{flex:1;height:6px;border-radius:6px;background:#e9eaeb;transition:.25s}.wz-bar span.done{background:{{$accent}}}
.wz-steplabel{font-size:.72rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.5rem}
.wz-card{background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.75rem 1.85rem}
.wz-card h2{font-size:1.3rem;font-weight:800;color:#111827;margin:0 0 .3rem}.wz-card .desc{color:#6b7280;font-size:.9rem;margin-bottom:1.5rem}
.wz-field{margin-bottom:1.1rem}.wz-field label{display:block;font-size:.85rem;font-weight:700;color:#374151;margin-bottom:6px}
.wz-field input,.wz-field select,.wz-field textarea{width:100%;border:1px solid #d1d5db;border-radius:11px;padding:.75rem .95rem;font-size:.92rem;font-family:inherit;outline:none}
.wz-field input:focus,.wz-field select:focus,.wz-field textarea:focus{border-color:{{$accent}}}
.wz-err{color:#dc2626;font-size:.78rem;margin-top:4px;display:none}
.wz-upload{border:2px dashed #d1d5db;border-radius:12px;padding:1.25rem;text-align:center;cursor:pointer;color:#9ca3af}.wz-upload:hover{border-color:{{$accent}}}
.wz-recap-row{display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid #f3f4f6;font-size:.9rem}.wz-recap-row .k{color:#6b7280}.wz-recap-row .v{font-weight:700;color:#111827}
.wz-info{background:#eef2ff;border:1px solid #c7d2fe;border-radius:12px;padding:1rem 1.25rem;color:{{$accentD}};font-size:.88rem}
.wz-nav{display:flex;justify-content:space-between;margin-top:1.5rem}
.wz-btn{border:none;border-radius:12px;padding:.85rem 2rem;font-weight:800;font-size:.95rem;cursor:pointer}
.wz-btn.next{background:{{$accent}};color:#fff}.wz-btn.next:hover{background:{{$accentD}}}
.wz-btn.back{background:#fff;color:#6b7280;border:1px solid #d1d5db}.wz-btn.back:hover{background:#f3f4f6}
</style>
@endpush

@section('content')
<div class="wz">
    <div class="wz-top"><div class="wz-ic"><i class="fas fa-graduation-cap"></i></div>
        <div><h1>Nouvelle formation</h1><div class="s">Un espace e-learning avec modules et leçons</div></div></div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span></div>
    <div class="wz-steplabel"><span id="sn">Étape 1 sur 3</span> · <span id="snm">Détails</span></div>

    <form action="{{ route('admin.produits.store-formation') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        <div class="wz-card wz-step" data-step="1">
            <h2>Détails de la formation</h2><div class="desc">Nom, catégorie et prix (achat unique).</div>
            <div class="wz-field"><label>Nom *</label><input type="text" name="nom" id="f-nom" placeholder="Ex : Maîtriser Excel de A à Z" required><div class="wz-err" id="e-nom">Le nom est requis.</div></div>
            <div class="wz-field"><label>Catégorie *</label><select name="categorie_id" id="f-cat" required><option value="">Choisir…</option>@foreach($categories as $c)<option value="{{$c->id}}">{{$c->nom}}</option>@endforeach</select><div class="wz-err" id="e-cat">Choisissez une catégorie.</div></div>
            <div class="wz-field"><label>Prix (FCFA) *</label><input type="number" name="prix" id="f-prix" min="0" placeholder="Ex : 25000" required><div class="wz-err" id="e-prix">Indiquez un prix.</div></div>
        </div>
        <div class="wz-card wz-step" data-step="2" style="display:none">
            <h2>Présentation</h2><div class="desc">Décrivez la formation et ajoutez une couverture.</div>
            <div class="wz-field"><label>Description</label><textarea name="description" rows="5" placeholder="Programme, objectifs, public visé…"></textarea></div>
            <div class="wz-field"><label>Image de couverture <span style="color:#9ca3af;font-weight:400">(optionnel)</span></label>
                <div class="wz-upload" onclick="document.getElementById('f-img').click()"><i class="fas fa-image" style="font-size:1.5rem"></i><div id="f-img-n" style="margin-top:8px;font-size:.85rem">Cliquez pour ajouter une image</div></div>
                <input type="file" id="f-img" name="image" accept="image/*" style="display:none" onchange="document.getElementById('f-img-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour ajouter une image'"></div>
        </div>
        <div class="wz-card wz-step" data-step="3" style="display:none">
            <h2>Récapitulatif</h2><div class="desc">Créez la formation, puis construisez le programme.</div>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-info" style="margin-top:1.1rem"><i class="fas fa-graduation-cap"></i> Après création, vous arriverez sur le <strong>constructeur de programme</strong> (modules + leçons).</div>
            <label style="display:flex;align-items:center;gap:9px;font-size:.9rem;color:#374151;cursor:pointer;margin-top:1rem"><input type="checkbox" name="est_publie" value="1" style="width:18px;height:18px;accent-color:#16a34a"> Publier immédiatement</label>
        </div>
        <div class="wz-nav"><button type="button" class="wz-btn back" id="bk" onclick="P()" style="visibility:hidden">← Retour</button><button type="button" class="wz-btn next" id="nx" onclick="N()">Continuer →</button></div>
    </form>
</div>
<script>
(function(){var s=1,T=3,NM={1:'Détails',2:'Présentation',3:'Récapitulatif'};
function show(){document.querySelectorAll('.wz-step').forEach(x=>x.style.display=(x.dataset.step==s)?'':'none');document.querySelectorAll('.wz-bar span').forEach(b=>b.classList.toggle('done',b.dataset.seg<=s));document.getElementById('sn').textContent='Étape '+s+' sur '+T;document.getElementById('snm').textContent=NM[s];document.getElementById('bk').style.visibility=s>1?'visible':'hidden';document.getElementById('nx').textContent=s==T?'Créer la formation':'Continuer →';window.scrollTo({top:0,behavior:'smooth'})}
function V(){document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');if(s===1){var o=true;if(!document.getElementById('f-nom').value.trim()){document.getElementById('e-nom').style.display='block';o=false}if(!document.getElementById('f-cat').value){document.getElementById('e-cat').style.display='block';o=false}var p=document.getElementById('f-prix').value;if(p===''||parseFloat(p)<0){document.getElementById('e-prix').style.display='block';o=false}return o}return true}
function R(){document.getElementById('r-nom').textContent=document.getElementById('f-nom').value||'—';var sel=document.getElementById('f-cat');document.getElementById('r-cat').textContent=sel.options[sel.selectedIndex]?.text||'—';document.getElementById('r-prix').textContent=(document.getElementById('f-prix').value||'0')+' FCFA'}
window.N=function(){if(!V())return;if(s===T){document.getElementById('wzf').submit();return}s++;if(s===T)R();show()};window.P=function(){if(s>1){s--;show()}};show();})();
</script>
@endsection
