@extends('layouts.admin')
@section('title', 'Nouveau produit licence')
@push('styles') @include('admin.produits.partials.wizard-css') @endpush

@section('content')
<div class="wz" style="--wz-accent:#7c3aed;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-key"></i></div>
        <div><h1>Créez un produit licence</h1><p>Des clés uniques livrées automatiquement à chaque vente.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>

    <form action="{{ route('admin.produits.store-licence') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details', ['placeholderNom' => 'Ex: Licence Logiciel Pro'])

        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Configuration des clés</h2>
            <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:14px;padding:1.1rem;text-align:center;margin-bottom:1.25rem;">
                <div style="font-size:0.72rem;color:#9ca3af;font-weight:700;text-transform:uppercase;margin-bottom:6px;">Aperçu d'une clé</div>
                <div id="lp" style="font-family:monospace;font-weight:700;color:#6d28d9;font-size:1.15rem;">XXXX-XXXX-XXXX-XXXX</div>
            </div>
            <div class="wz-field"><label>Type de clé</label>
                <div style="display:flex;gap:0.7rem;flex-wrap:wrap;">
                    <label class="wz-opt sel" id="o-alpha"><input type="radio" name="cle_type" value="alphanumerique" checked onchange="lkU()"> <strong>Alphanumérique</strong></label>
                    <label class="wz-opt" id="o-uuid"><input type="radio" name="cle_type" value="uuid" onchange="lkU()"> <strong>UUID</strong></label>
                </div>
            </div>
            <div class="wz-field" id="lenwrap"><label>Longueur : <span id="lenv">16</span> caractères</label><input type="range" name="cle_longueur" id="len" min="8" max="32" step="8" value="16" oninput="lkU()" style="accent-color:#7c3aed;"></div>
            <div class="wz-2col">
                <div class="wz-field" style="margin-bottom:0;"><label>Préfixe (optionnel)</label><input type="text" name="cle_prefixe" id="pre" maxlength="12" placeholder="Ex: PRO" oninput="lkU()"></div>
                <div class="wz-field" style="margin-bottom:0;"><label>Quantité à générer</label><input type="number" name="cle_quantite" id="qte" min="0" max="1000" value="10"></div>
            </div>
        </div>

        @include('admin.produits.partials.wizard-presentation', ['step' => 3, 'descPlaceholder' => 'À quoi sert cette licence…'])

        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Clés générées</span><span class="v" id="r-qte">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script>
function lkRnd(n){var c='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',o='';for(var i=0;i<n;i++)o+=c[Math.floor(Math.random()*c.length)];return o;}
function lkU(){var t=(document.querySelector('input[name=cle_type]:checked')||{}).value||'alphanumerique';var pre=(document.getElementById('pre').value||'').toUpperCase().replace(/[^A-Z0-9]/g,'');var l=parseInt(document.getElementById('len').value)||16;document.getElementById('lenv').textContent=l;document.getElementById('lenwrap').style.display=t==='uuid'?'none':'';document.getElementById('o-alpha').classList.toggle('sel',t==='alphanumerique');document.getElementById('o-uuid').classList.toggle('sel',t==='uuid');var b;if(t==='uuid'){b='XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'.replace(/X/g,function(){return lkRnd(1);});}else{b=lkRnd(l).match(/.{1,4}/g).join('-');}document.getElementById('lp').textContent=(pre?pre+'-':'')+b;}
window.WZ = {
    createLabel: 'Créer le produit',
    recap: function(){ document.getElementById('r-qte').textContent = document.getElementById('qte').value || '0'; }
};
lkU();
</script>
@include('admin.produits.partials.wizard-engine')
@endsection
