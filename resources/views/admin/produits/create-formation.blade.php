@extends('layouts.admin')
@section('title', 'Nouvelle formation')
@push('styles') @include('admin.produits.partials.wizard-css') @endpush

@section('content')
<div class="wz" style="--wz-accent:#4f46e5;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-graduation-cap"></i></div>
        <div><h1>Créez une formation</h1><p>Un espace e-learning avec modules et leçons, façon Udemy.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span></div>

    <form action="{{ route('admin.produits.store-formation') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details', ['placeholderNom' => 'Ex: Maîtriser Excel de A à Z'])

        @include('admin.produits.partials.wizard-presentation', ['step' => 2, 'descPlaceholder' => 'Programme, objectifs, public visé…'])

        <div class="wz-step" data-step="3" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-info" style="margin-top:1.1rem;"><i class="fas fa-graduation-cap"></i> Après création, vous arriverez sur le <strong>constructeur de programme</strong> (modules + leçons).</div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1rem;"><input type="checkbox" name="est_publie" value="1" style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script> window.WZ = { createLabel: 'Créer la formation' }; </script>
@include('admin.produits.partials.wizard-engine')
@endsection
