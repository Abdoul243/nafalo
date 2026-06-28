@extends('layouts.admin')
@section('title', 'Nouveau produit téléchargeable')
@push('styles') @include('admin.produits.partials.wizard-css') @endpush

@section('content')
<div class="wz" style="--wz-accent:#f59e0b;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-box-open"></i></div>
        <div><h1>Créez un produit téléchargeable</h1><p>Vendez des fichiers numériques livrés instantanément après l'achat.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>

    <form action="{{ route('admin.produits.store-fichier') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details')

        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Le fichier à livrer</h2>
            <div class="wz-upload" onclick="document.getElementById('f-fic').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <div id="f-fic-n" style="margin-top:10px;font-weight:600;">Cliquez pour téléverser</div>
                <div style="font-size:0.78rem;margin-top:4px;">PDF, ZIP, MP3, MP4 — Max 100 Mo · stocké en privé, livré après paiement</div>
            </div>
            <input type="file" id="f-fic" name="fichier" accept=".pdf,.zip,.mp3,.mp4,.docx,.xlsx,.png,.jpg" style="display:none;"
                   onchange="document.getElementById('f-fic-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour téléverser'">
            <div class="wz-err" id="e-fic" style="margin-top:10px;">Veuillez ajouter un fichier.</div>
        </div>

        @include('admin.produits.partials.wizard-presentation', ['step' => 3])

        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Fichier</span><span class="v" id="r-fic">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script>
window.WZ = {
    createLabel: 'Créer le produit',
    validate: function(s){ if(s===2 && !document.getElementById('f-fic').files.length){ document.getElementById('e-fic').style.display='block'; return false; } return true; },
    recap: function(){ var f=document.getElementById('f-fic').files[0]; document.getElementById('r-fic').textContent = f?f.name:'—'; }
};
</script>
@include('admin.produits.partials.wizard-engine')
@endsection
