@extends('layouts.admin')
@section('title', 'Nouveau pack')
@push('styles') @include('admin.produits.partials.wizard-css') @endpush

@section('content')
<div class="wz" style="--wz-accent:#0d9488;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-layer-group"></i></div>
        <div><h1>Créez un pack (bundle)</h1><p>Plusieurs produits réunis à prix réduit.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>

    <form action="{{ route('admin.produits.store-bundle') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details', ['placeholderNom' => 'Ex: Pack Complet Marketing'])

        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Produits inclus</h2>
            <p style="color:#6b7280;font-size:0.9rem;margin:-1rem 0 1.5rem;">Cochez les produits débloqués à l'achat du pack.</p>
            @forelse($disponibles as $p)
            <label class="wz-bi"><input type="checkbox" name="produits[]" value="{{ $p->id }}"><span class="n">{{ $p->nom }}</span><span class="p">{{ number_format($p->prix, 0, ',', ' ') }} F</span></label>
            @empty
            <div class="wz-info">Aucun autre produit à inclure pour l'instant. Vous pourrez composer le pack plus tard.</div>
            @endforelse
        </div>

        @include('admin.produits.partials.wizard-presentation', ['step' => 3, 'descPlaceholder' => 'Ce que contient le pack…'])

        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Catégorie</span><span class="v" id="r-cat">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Produits inclus</span><span class="v" id="r-inc">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script>
window.WZ = {
    createLabel: 'Créer le pack',
    recap: function(){ document.getElementById('r-inc').textContent = document.querySelectorAll('input[name="produits[]"]:checked').length; }
};
</script>
@include('admin.produits.partials.wizard-engine')
@endsection
