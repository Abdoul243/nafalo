@extends('layouts.admin')
@section('title', 'Nouvelle séance de coaching')
@push('styles')
@include('admin.produits.partials.wizard-css')
<style>
.wz-day { display:flex;align-items:center;gap:10px;padding:0.55rem 0;border-bottom:1px solid #f3f4f6; }
.wz-day label.d { display:flex;align-items:center;gap:8px;width:120px;cursor:pointer;font-weight:600;color:#111827;font-size:0.88rem; }
.wz-day input[type=time] { padding:0.45rem 0.6rem;border:1px solid #e5e7eb;border-radius:9px;width:auto;font-family:inherit; }
.wz-day input[type=checkbox]{ width:18px;height:18px;accent-color:var(--wz-accent); }
.wz-presets { display:flex;gap:0.5rem;flex-wrap:wrap; }
</style>
@endpush

@section('content')
<div class="wz" style="--wz-accent:#db2777;">
    <div class="wz-hd">
        <div class="ic"><i class="fas fa-video"></i></div>
        <div><h1>Créez une séance de coaching</h1><p>Le client réserve un créneau dans vos disponibilités.</p></div>
    </div>
    <div class="wz-bar"><span class="done" data-seg="1"></span><span data-seg="2"></span><span data-seg="3"></span><span data-seg="4"></span></div>

    <form action="{{ route('admin.produits.store-coaching') }}" method="POST" enctype="multipart/form-data" id="wzf">
        @csrf
        @include('admin.produits.partials.wizard-details', ['placeholderNom' => 'Ex: Appel stratégique 1-à-1'])

        {{-- ÉTAPE 2 : Paramètres --}}
        <div class="wz-step" data-step="2" style="display:none;">
            <h2 class="wz-h2">Paramètres de la séance</h2>
            <div class="wz-field">
                <label>Durée de la séance</label>
                <div class="wz-presets" id="duree-presets">
                    @foreach([15=>'15 min',30=>'30 min',45=>'45 min',60=>'1 h',90=>'1 h 30',120=>'2 h'] as $v=>$lbl)
                    <button type="button" class="wz-preset {{ $v==60?'sel':'' }}" data-val="{{ $v }}" onclick="cwPreset('duree',{{ $v }})">{{ $lbl }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="coaching_duree" id="cw-duree" value="60">
            </div>
            <div class="wz-field">
                <label>Pause entre les séances</label>
                <div class="wz-presets" id="pause-presets">
                    @foreach([0=>'Aucune',5=>'5 min',15=>'15 min',30=>'30 min',60=>'1 h'] as $v=>$lbl)
                    <button type="button" class="wz-preset {{ $v==0?'sel':'' }}" data-val="{{ $v }}" onclick="cwPreset('pause',{{ $v }})">{{ $lbl }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="coaching_pause" id="cw-pause" value="0">
            </div>
        </div>

        {{-- ÉTAPE 3 : Disponibilité --}}
        <div class="wz-step" data-step="3" style="display:none;">
            <h2 class="wz-h2">Votre disponibilité</h2>
            <p style="color:#6b7280;font-size:0.9rem;margin:-1rem 0 1.5rem;">Cochez vos jours et définissez les plages horaires.</p>
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
        <div class="wz-step" data-step="4" style="display:none;">
            <h2 class="wz-h2">Récapitulatif</h2>
            <div class="wz-field">
                <label>Description <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <textarea name="description" rows="3" placeholder="À qui s'adresse cette séance, ce qu'elle apporte…">{{ old('description') }}</textarea>
            </div>
            <div class="wz-field">
                <label>Image de couverture <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <div class="wz-upload" style="padding:1.25rem;" onclick="document.getElementById('f-img').click()"><i class="fas fa-image" style="font-size:1.5rem;"></i><div id="f-img-n" style="margin-top:8px;font-size:0.85rem;">Cliquez pour ajouter une image</div></div>
                <input type="file" id="f-img" name="image" accept="image/*" style="display:none;" onchange="document.getElementById('f-img-n').textContent=this.files[0]?this.files[0].name:'Cliquez pour ajouter une image'">
            </div>
            <div class="wz-recap-row"><span class="k">Nom</span><span class="v" id="r-nom">—</span></div>
            <div class="wz-recap-row"><span class="k">Prix</span><span class="v" id="r-prix">—</span></div>
            <div class="wz-recap-row"><span class="k">Durée</span><span class="v" id="r-duree">—</span></div>
            <div class="wz-recap-row"><span class="k">Jours</span><span class="v" id="r-jours">—</span></div>
            <label style="display:flex;align-items:center;gap:9px;font-size:0.9rem;color:#374151;cursor:pointer;margin-top:1.25rem;"><input type="checkbox" name="est_publie" value="1" checked style="width:18px;height:18px;accent-color:#16a34a;"> Publier immédiatement</label>
        </div>

        @include('admin.produits.partials.wizard-nav')
    </form>
</div>

<script>
function cwPreset(grp,val){ document.getElementById('cw-'+grp).value=val; document.querySelectorAll('#'+grp+'-presets .wz-preset').forEach(function(b){ b.classList.toggle('sel', b.dataset.val==val); }); }
window.WZ = {
    createLabel: 'Créer la séance',
    recap: function(){
        document.getElementById('r-duree').textContent = document.getElementById('cw-duree').value + ' min';
        var j=[]; document.querySelectorAll('.wz-day').forEach(function(d){ if(d.querySelector('input[type=checkbox]').checked) j.push(d.querySelector('label.d').textContent.trim()); });
        document.getElementById('r-jours').textContent = j.length ? j.join(', ') : 'Aucun';
    }
};
</script>
@include('admin.produits.partials.wizard-engine')
@endsection
