@extends('layouts.admin')
@section('title', 'Nouveau pack')
@php $accent='#0d9488'; $accentD='#0f766e'; @endphp

@push('styles')
<style>
.wz{max-width:760px;margin:0 auto;padding:1.5rem 1.25rem 5rem}
.wz-top{display:flex;align-items:center;gap:12px;margin-bottom:1.5rem}
.wz-ic{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#14b8a6,{{$accent}});display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem}
.wz-top h1{font-size:1.2rem;font-weight:800;color:#111827;margin:0}.wz-top .s{font-size:.82rem;color:#9ca3af}
.wz-bar{display:flex;gap:6px;margin-bottom:.5rem}.wz-bar span{flex:1;height:6px;border-radius:6px;background:#e9eaeb;transition:.25s}.wz-bar span.done{background:{{$accent}}}
.wz-steplabel{font-size:.72rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.5rem}
.wz-card{background:#fff;border:1px solid #e9eaeb;border-radius:16px;padding:1.75rem 1.85rem}
.wz-card h2{font-size:1.3rem;font-weight:800;color:#111827;margin:0 0 .3rem}.wz-card .desc{color:#6b7280;font-size:.9rem;margin-bottom:1.5rem}
.wz-field{margin-bottom:1.1rem}.wz-field label{display:block;font-size:.85rem;font-weight:700;color:#374151;margin-bottom:6px}
.wz-field input,.wz-field select,.wz-field textarea{width:100%;border:1px solid #d1d5db;border-radius:11px;padding:.75rem .95rem;font-size:.92rem;font-family:inherit;outline:none}
.wz-field input:focus,.wz-field textarea:focus{border-color:{{$accent}}}
.wz-err{color:#dc2626;font-size:.78rem;margin-top:4px;display:none}
.wz-upload{border:2px dashed #d1d5db;border-radius:12px;padding:1.25rem;text-align:center;cursor:pointer;color:#9ca3af}.wz-upload:hover{border-color:{{$accent}}}
.wz-recap-row{display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid #f3f4f6;font-size:.9rem}.wz-recap-row .k{color:#6b7280}.wz-recap-row .v{font-weight:700;color:#111827}
.bi{display:flex;align-items:center;gap:11px;padding:.7rem .9rem;border:1px solid #eee;border-radius:11px;margin-bottom:.5rem;cursor:pointer}.bi:hover{background:#f8fafc}.bi input{width:18px;height:18px;accent-color:{{$accent}}}
.bi .n{flex:1;font-weight:600;color:#111827;font-size:.9rem}.bi .p{font-size:.8rem;color:#9ca3af}
.wz-nav{display:flex;justify-content:space-between;margin-top:1.5rem}
.wz-btn{border:none;border-radius:12px;padding:.85rem 2rem;font-weight:800;font-size:.95rem;cursor:pointer}
.wz-btn.next{background:{{$accent}};color:#fff}.wz-btn.next:hover{background:{{$accentD}}}.wz-btn.back{background:#fff;color:#6b7280;border:1px solid #d1d5db}
</style>
@endpush

@section('content')
<div class="wz">
    <div class="wz-top"><div class="wz-ic"><i class="fas fa-layer-group"></i></div><div><h1>Nouveau pack (bundle)</h1><div class="s">Plusieurs produits réunis à prix réduit</div></div></div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>
    <div class="wz-steplabel"><span id="sn">Étape 1 sur 4</span> · <span id="snm">Détails</span></div>

    <form action="{{ route('admin.produits.store-bundle') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        <div class="wz-card wz-step" data-step="1">
            <h2>Détails du pack</h2><div class="desc">Nom, catégorie et prix du pack.</div>
            <div class="wz-field"><label>Nom *</label><input type="text" name="nom" id="f-nom" placeholder="Ex : Pack Complet Marketing" required><div class="wz-err" id="e-nom">Le nom est requis.</div></div>
            <div class="wz-field"><label>Catégorie *</label><select name="categorie_id" id="f-cat" required><option value="">Choisir…</option>@foreach($categories as $c)<option value="{{$c->id}}">{{$c->nom}}</option>@endforeach</select><div class="wz-err" id="e-cat">Choisissez une catégorie.</div></div>
            <div class="wz-field"><label>Prix du pack (FCFA) *</label><input type="number" name="prix" id="f-prix" min="0" placeholder="Ex : 30000" required><div class="wz-err" id="e-prix">Indiquez un prix.</div></div>
        </div>
        <div class="wz-card wz-step" data-step="2" style="display:none">
            <h2>Produits inclus</h2><div class="desc">Cochez les produits débloqués à l'achat du pack.</div>
            @forelse($disponibles as $p)
            <label class="bi"><input type="checkbox" name="produits[]" value="{{$p->id}}"><span class="n">{{$p->nom}}</span><span class="p">{{ number_format($p->prix,0,',',' ') }} F</span></label>
            @empty
            <div style="color:#9ca3af;padding:1rem;text-align:center">Aucun autre produit à inclure pour l'instant. Vous pourrez composer le pack plus tard.</div>
            @endforelse
        </div>
        <div class="wz-card wz-step" data-step="3" style="display:none">
            <h2>Présentation</h2><div class="desc">Description et image (optionnel).</div>
            <div class="wz-field"><label>Description</label><textarea name="description" rows="4" placeholder="Ce que contient le pack…"></textarea></div>
            <div class="wz-field"><label>Image <span style="color:#9ca3af;font-weight:400">(optionnel)</span></label><div class="wz-upload" onclick="document.getElementById('f-img').click()"><i class="fas fa-image" style="font-size:1.5rem"></i><div id="f-img-n" style="margin-top:8px;font-size:.85rem">Cliquez pour ajouter</div></div><input type="file" id="f-img" name="image" accept="image/*" style="display:none" onchange="document.getElementById('f-img-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour ajouter'"></div>
        </div>
        <div class="wz-card wz-step" data-step="4" style="display:none">
            <h2>Récapitulatif</h2><div class="desc">Créez le pack.</div>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Produits inclus</span><span class="v" id="r-inc">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:.9rem;color:#374151;cursor:pointer;margin-top:1.1rem"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a"> Publier immédiatement</label>
        </div>
        <div class="wz-nav"><button type="button" class="wz-btn back" id="bk" onclick="P()" style="visibility:hidden">← Retour</button><button type="button" class="wz-btn next" id="nx" onclick="N()">Continuer →</button></div>
    </form>
</div>
<script>
(function(){var s=1,T=4,NM={1:'Détails',2:'Produits',3:'Présentation',4:'Récapitulatif'};
function show(){document.querySelectorAll('.wz-step').forEach(x=>x.style.display=(x.dataset.step==s)?'':'none');document.querySelectorAll('.wz-bar span').forEach(b=>b.classList.toggle('done',b.dataset.seg<=s));document.getElementById('sn').textContent='Étape '+s+' sur '+T;document.getElementById('snm').textContent=NM[s];document.getElementById('bk').style.visibility=s>1?'visible':'hidden';document.getElementById('nx').textContent=s==T?'Créer le pack':'Continuer →';window.scrollTo({top:0,behavior:'smooth'})}
function V(){document.querySelectorAll('.wz-err').forEach(e=>e.style.display='none');if(s===1){var o=true;if(!document.getElementById('f-nom').value.trim()){document.getElementById('e-nom').style.display='block';o=false}if(!document.getElementById('f-cat').value){document.getElementById('e-cat').style.display='block';o=false}var p=document.getElementById('f-prix').value;if(p===''||parseFloat(p)<0){document.getElementById('e-prix').style.display='block';o=false}return o}return true}
function R(){document.getElementById('r-nom').textContent=document.getElementById('f-nom').value||'—';document.getElementById('r-prix').textContent=(document.getElementById('f-prix').value||'0')+' FCFA';document.getElementById('r-inc').textContent=document.querySelectorAll('input[name="produits[]"]:checked').length}
window.N=function(){if(!V())return;if(s===T){document.getElementById('wzf').submit();return}s++;if(s===T)R();show()};window.P=function(){if(s>1){s--;show()}};show();})();
</script>
@endsection
