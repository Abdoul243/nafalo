@extends('layouts.admin')
@section('title', 'Pack — ' . $produit->nom)

@push('styles')
<style>
.bd-wrap { max-width:820px; margin:0 auto; padding:1.5rem 1.25rem 4rem; }
.bd-head { display:flex; align-items:center; gap:12px; margin-bottom:1.25rem; }
.bd-back { width:38px;height:38px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none; }
.bd-title { font-size:1.3rem; font-weight:800; color:#111827; margin:0; }
.bd-sub { font-size:0.85rem; color:#6b7280; margin-top:2px; }
.bd-alert { background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.7rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1rem; }

.bd-summary { display:flex; gap:12px; margin-bottom:1.25rem; flex-wrap:wrap; }
.bd-s { flex:1; min-width:130px; background:#fff; border:1px solid #e9eaeb; border-radius:12px; padding:0.9rem 1.1rem; }
.bd-s .v { font-size:1.2rem; font-weight:900; color:#111827; }
.bd-s .l { font-size:0.72rem; color:#9ca3af; font-weight:700; text-transform:uppercase; margin-top:3px; }
.bd-s.save .v { color:#0d9488; }

.bd-list { background:#fff; border:1px solid #e9eaeb; border-radius:14px; overflow:hidden; }
.bd-item { display:flex; align-items:center; gap:12px; padding:0.8rem 1.1rem; border-bottom:1px solid #f3f4f6; cursor:pointer; }
.bd-item:last-child { border-bottom:none; }
.bd-item:hover { background:#f8fafc; }
.bd-item input { width:18px; height:18px; accent-color:#0d9488; }
.bd-thumb { width:42px; height:42px; border-radius:9px; background:#f3f4f6; overflow:hidden; display:flex; align-items:center; justify-content:center; color:#9ca3af; flex-shrink:0; }
.bd-thumb img { width:100%; height:100%; object-fit:cover; }
.bd-info { flex:1; min-width:0; }
.bd-info .n { font-weight:700; color:#111827; font-size:0.9rem; }
.bd-info .p { font-size:0.78rem; color:#9ca3af; }
.bd-fmt { font-size:0.68rem; font-weight:700; padding:2px 8px; border-radius:20px; background:#eef2ff; color:#4f46e5; }
.bd-save { position:sticky; bottom:0; background:#fff; border-top:1px solid #eee; padding:1rem; margin-top:1rem; display:flex; justify-content:flex-end; }
.bd-btn { background:#0d9488; color:#fff; border:none; border-radius:10px; padding:0.7rem 1.4rem; font-weight:800; font-size:0.9rem; cursor:pointer; }
.bd-btn:hover { background:#0f766e; }
.bd-empty { text-align:center; padding:2.5rem; color:#9ca3af; }
</style>
@endpush

@section('content')
<div class="bd-wrap">
    <div class="bd-head">
        <a href="{{ route('admin.produits.edit', $produit) }}" class="bd-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="bd-title">{{ $produit->nom }}</h1>
            <div class="bd-sub">Composez votre pack — l'achat débloque tous les produits cochés</div>
        </div>
    </div>

    @if(session('success'))<div class="bd-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    @php $valeur = $produit->valeurInclus(); $eco = max(0, $valeur - $produit->prix); @endphp
    <div class="bd-summary">
        <div class="bd-s"><div class="v">{{ number_format($produit->prix, 0, ',', ' ') }} F</div><div class="l">Prix du pack</div></div>
        <div class="bd-s"><div class="v">{{ number_format($valeur, 0, ',', ' ') }} F</div><div class="l">Valeur cumulée</div></div>
        <div class="bd-s save"><div class="v">-{{ number_format($eco, 0, ',', ' ') }} F</div><div class="l">Économie client</div></div>
    </div>

    <form action="{{ route('admin.produits.bundle.enregistrer', $produit) }}" method="POST">
        @csrf
        @if($disponibles->isEmpty())
            <div class="bd-list"><div class="bd-empty">Vous n'avez pas encore d'autres produits à inclure. Créez d'abord des produits, puis revenez composer le pack.</div></div>
        @else
        <div class="bd-list">
            @foreach($disponibles as $p)
            <label class="bd-item">
                <input type="checkbox" name="produits[]" value="{{ $p->id }}" {{ in_array($p->id, $inclusIds) ? 'checked' : '' }}>
                <div class="bd-thumb">
                    @if($p->image)<img src="{{ $p->image_url }}" alt="">@else<i class="fas fa-box"></i>@endif
                </div>
                <div class="bd-info">
                    <div class="n">{{ $p->nom }}</div>
                    <div class="p">{{ number_format($p->prix_promo ?? $p->prix, 0, ',', ' ') }} FCFA</div>
                </div>
                <span class="bd-fmt">
                    @switch($p->format)
                        @case('formation') Formation @break
                        @case('licence') Licence @break
                        @default Fichier
                    @endswitch
                </span>
            </label>
            @endforeach
        </div>
        <div class="bd-save"><button class="bd-btn"><i class="fas fa-save"></i> Enregistrer le pack</button></div>
        @endif
    </form>
</div>
@endsection
