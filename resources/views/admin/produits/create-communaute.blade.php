@extends('layouts.admin')
@section('title', 'Nouvelle communauté')
@push('styles') @include('admin.produits.partials.wizard-css') @endpush

@section('content')
<div class="wz" style="--wz-accent:#e11d48;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-users"></i></div>
        <div><h1>Créez une communauté</h1><p>Un espace membre avec fil de discussion.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>

    <form action="{{ route('admin.produits.store-communaute') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details', ['placeholderNom' => 'Ex: Cercle privé Entrepreneurs'])

        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Type d'accès</h2>
            <div style="display:flex;gap:0.7rem;flex-wrap:wrap;margin-bottom:1.25rem;">
                <label class="wz-opt sel" id="o-uni"><input type="radio" name="acces_type" value="unique" checked onchange="acA()"> <span><strong>Paiement unique</strong><br><span style="color:#9ca3af;font-size:0.8rem;">Accès à vie</span></span></label>
                <label class="wz-opt" id="o-abo"><input type="radio" name="acces_type" value="abonnement" onchange="acA()"> <span><strong>Abonnement</strong><br><span style="color:#9ca3af;font-size:0.8rem;">Accès récurrent</span></span></label>
            </div>
            <div class="wz-field" id="abowrap" style="display:none;"><label>Périodicité</label><select name="abonnement_intervalle"><option value="mensuel">Mensuel (30 jours)</option><option value="annuel">Annuel (12 mois)</option></select></div>
        </div>

        @include('admin.produits.partials.wizard-presentation', ['step' => 3, 'descPlaceholder' => "À qui s'adresse la communauté, ce qu'on y trouve…"])

        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Accès</span><span class="v" id="r-acc">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script>
function acA(){var t=(document.querySelector('input[name=acces_type]:checked')||{}).value;document.getElementById('abowrap').style.display=t==='abonnement'?'':'none';document.getElementById('o-uni').classList.toggle('sel',t==='unique');document.getElementById('o-abo').classList.toggle('sel',t==='abonnement');}
window.WZ = {
    createLabel: 'Créer la communauté',
    recap: function(){ var t=(document.querySelector('input[name=acces_type]:checked')||{}).value; document.getElementById('r-acc').textContent = t==='abonnement' ? ('Abonnement '+document.querySelector('select[name=abonnement_intervalle]').value) : 'Paiement unique'; }
};
</script>
@include('admin.produits.partials.wizard-engine')
@endsection
