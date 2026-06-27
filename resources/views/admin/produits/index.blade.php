@extends('layouts.admin')
@section('title', 'Produits')

@push('styles')
<style>
/* ══ PAGE PRODUITS — Chariow Style ══ */
.page-wrap { background: #fff; border-radius: 16px; overflow: hidden; min-height: 80vh; }

/* Toolbar */
.p-toolbar {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
}
.p-search {
    flex: 1; position: relative;
}
.p-search i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: .8rem; pointer-events: none;
}
.p-search input {
    width: 100%; height: 38px; padding: 0 12px 0 34px;
    font-size: .84rem; border: 1px solid #e5e7eb; border-radius: 10px;
    outline: none; background: #f9fafb; color: #111827;
    transition: border-color .15s, background .15s;
}
.p-search input:focus { border-color: #6d28d9; background: #fff; }
.p-search input::placeholder { color: #9ca3af; }
.p-filter-btn {
    width: 38px; height: 38px; border: 1px solid #e5e7eb; border-radius: 10px;
    background: #f9fafb; color: #6b7280; font-size: .82rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; transition: all .15s;
}
.p-filter-btn:hover { border-color: #374151; color: #111827; background: #fff; }
.btn-add {
    height: 38px; padding: 0 18px; font-size: .84rem; font-weight: 600;
    background: #f59e0b; color: #fff; border: none; border-radius: 10px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
    white-space: nowrap; flex-shrink: 0; transition: background .15s;
}
.btn-add:hover { background: #d97706; color: #fff; }

/* Tabs */
.p-tabs {
    display: flex; gap: 4px; padding: 12px 20px 0;
    border-bottom: 1px solid #f1f5f9;
}
.p-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; font-size: .83rem; font-weight: 500;
    color: #6b7280; border: none; background: none; cursor: pointer;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    text-decoration: none; transition: all .15s; white-space: nowrap;
}
.p-tab:hover { color: #111827; }
.p-tab.active { color: #111827; font-weight: 700; border-bottom-color: #111827; }
.p-tab-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* Table */
.p-table { width: 100%; border-collapse: collapse; }
.p-table thead th {
    padding: 10px 16px; font-size: .71rem; font-weight: 700;
    color: #9ca3af; text-transform: uppercase; letter-spacing: .06em;
    background: #fafafa; white-space: nowrap;
    border-bottom: 1px solid #f1f5f9;
}
.p-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background .1s; }
.p-table tbody tr:last-child { border-bottom: none; }
.p-table tbody tr:hover { background: #fafafa; }
.p-table td { padding: 12px 16px; vertical-align: middle; font-size: .84rem; color: #374151; }

/* Thumbnail */
.p-thumb {
    width: 36px; height: 36px; border-radius: 8px;
    object-fit: cover; display: block; flex-shrink: 0;
}
.p-thumb-empty {
    width: 36px; height: 36px; border-radius: 8px;
    background: #f3f4f6; display: flex; align-items: center;
    justify-content: center; color: #d1d5db; font-size: .8rem;
    flex-shrink: 0;
}

/* Product name */
.p-name { font-weight: 600; color: #111827; font-size: .85rem; }
.p-copy {
    background: none; border: none; padding: 0 3px;
    color: #d1d5db; font-size: .72rem; cursor: pointer;
    transition: color .15s; vertical-align: middle;
}
.p-copy:hover { color: #6b7280; }

/* Price */
.p-price { font-size: .84rem; color: #374151; white-space: nowrap; }
.p-price.free { color: #d97706; font-weight: 600; }

/* Status */
.p-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .73rem; font-weight: 600; white-space: nowrap;
}
.p-badge.pub  { background: #ecfdf5; color: #059669; }
.p-badge.draft{ background: #f3f4f6; color: #6b7280; }
.p-badge i { font-size: .65rem; }

/* 3-dot menu */
.p-action { position: relative; display: inline-block; }
.p-action-btn {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1px solid #e5e7eb; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #6b7280; transition: all .15s; padding: 0;
}
.p-action-btn:hover { background: #f9fafb; border-color: #374151; color: #111827; }
.p-dropdown {
    position: fixed; background: #fff; border-radius: 12px;
    border: 1px solid #e5e7eb; box-shadow: 0 8px 30px rgba(0,0,0,.1);
    min-width: 185px; z-index: 99999; display: none; padding: .35rem 0;
    animation: dFade .12s ease;
}
.p-dropdown.open { display: block; }
@keyframes dFade { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:none} }
.p-dropdown a, .p-dropdown button {
    display: flex; align-items: center; gap: 9px;
    padding: .48rem 1rem; color: #374151; font-size: .82rem; font-weight: 500;
    text-decoration: none; background: none; border: none;
    width: 100%; text-align: left; cursor: pointer; transition: background .1s;
}
.p-dropdown a:hover, .p-dropdown button:hover { background: #f9fafb; color: #111827; }
.p-dropdown .divider { border: none; border-top: 1px solid #f1f5f9; margin: .3rem 0; }
.p-dropdown .danger:hover { background: #fef2f2; color: #dc2626; }

/* Empty */
.p-empty { text-align: center; padding: 4rem 1rem; color: #9ca3af; }
.p-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; color: #e5e7eb; }
.p-empty p { font-size: .85rem; margin: 0 0 1rem; }

/* Pagination */
.p-pages { padding: 1rem; border-top: 1px solid #f1f5f9; }
.pagination { justify-content: center; gap: 4px; margin: 0; }
.pagination .page-link {
    border-radius: 8px !important; border: 1px solid #e5e7eb;
    color: #374151; font-size: .8rem; padding: 5px 11px;
    background: #fff;
}
.pagination .page-item.active .page-link { background: #111827; border-color: #111827; color: #fff; }
.pagination .page-link:hover { background: #f9fafb; }

/* Mobile */
.p-card-mobile {
    background: #fff; border: 1px solid #f1f5f9; border-radius: 12px;
    padding: 12px 14px; display: flex; align-items: center; gap: 12px;
    margin-bottom: .5rem;
}
@media (max-width: 767px) {
    .desk { display: none !important; }
    .mob  { display: block !important; }
    .p-toolbar { flex-wrap: wrap; }
    .btn-add { width: 100%; justify-content: center; }
}
@media (min-width: 768px) {
    .mob  { display: none !important; }
    .desk { display: block !important; }
}
.mob { display: none; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;font-size:.84rem;">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:10px;font-size:.84rem;">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="page-wrap">

    {{-- Toolbar --}}
    <form method="GET" id="sf">
        <input type="hidden" name="statut" value="{{ request('statut') }}">
        <div class="p-toolbar">
            <div class="p-search">
                <i class="fas fa-search"></i>
                <input type="text" name="recherche" value="{{ request('recherche') }}"
                       placeholder="Rechercher un produit..."
                       oninput="document.getElementById('sf').submit()">
            </div>
            <button type="submit" class="p-filter-btn" title="Filtrer">
                <i class="fas fa-sliders-h"></i>
            </button>
            <a href="{{ route('admin.produits.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Ajouter un produit
            </a>
        </div>
    </form>

    {{-- Tabs --}}
    @php $statut = request('statut', ''); @endphp
    <div class="p-tabs">
        <a href="{{ route('admin.produits.index', array_merge(request()->except('statut','page'), [])) }}"
           class="p-tab {{ $statut === '' ? 'active' : '' }}">
            <span class="p-tab-dot" style="background:#9ca3af;"></span> Tout
        </a>
        <a href="{{ route('admin.produits.index', array_merge(request()->except('statut','page'), ['statut'=>'brouillon'])) }}"
           class="p-tab {{ $statut === 'brouillon' ? 'active' : '' }}">
            <span class="p-tab-dot" style="background:#374151;"></span> Brouillon
        </a>
        <a href="{{ route('admin.produits.index', array_merge(request()->except('statut','page'), ['statut'=>'publie'])) }}"
           class="p-tab {{ $statut === 'publie' ? 'active' : '' }}">
            <span class="p-tab-dot" style="background:#059669;"></span> Publié
        </a>
    </div>

    {{-- Mobile list --}}
    <div class="mob" style="padding:12px;">
        @forelse($produits as $produit)
        <div class="p-card-mobile">
            @if($produit->image_url)
                <img src="{{ $produit->image_url }}" class="p-thumb" alt="{{ $produit->nom }}">
            @else
                <div class="p-thumb-empty"><i class="fas fa-box"></i></div>
            @endif
            <div style="flex:1;min-width:0;">
                <div class="p-name text-truncate">{{ $produit->nom }}</div>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                    <span class="p-badge {{ $produit->est_publie ? 'pub' : 'draft' }}">
                        <i class="fas {{ $produit->est_publie ? 'fa-check-circle' : 'fa-circle' }}"></i>
                        {{ $produit->est_publie ? 'Publié' : 'Brouillon' }}
                    </span>
                    <span class="p-price {{ $produit->type === 'gratuit' ? 'free' : '' }}" style="font-size:.78rem;">
                        {{ $produit->type === 'gratuit' ? 'Gratuit' : number_format($produit->prix,0,',',' ').' FCFA' }}
                    </span>
                </div>
            </div>
            <div class="p-action">
                <button class="p-action-btn" onclick="toggleMenu(this)">
                    <i class="fas fa-ellipsis-v" style="font-size:.78rem;"></i>
                </button>
                <div class="p-dropdown">
                    @include('admin.produits._menu_actions', ['produit' => $produit])
                </div>
            </div>
        </div>
        @empty
        <div class="p-empty">
            <i class="fas fa-box-open"></i>
            <p>Aucun produit trouvé.</p>
            <a href="{{ route('admin.produits.create') }}" class="btn-add" style="display:inline-flex;">
                <i class="fas fa-plus"></i> Ajouter un produit
            </a>
        </div>
        @endforelse

        @if($produits->hasPages())
        <div class="p-pages">{{ $produits->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Desktop table --}}
    <div class="desk">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:52px;padding-left:20px;"></th>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Ventes</th>
                    <th>Statut</th>
                    <th style="width:52px;text-align:right;padding-right:20px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($produits as $produit)
            <tr>
                <td style="padding-left:20px;">
                    @if($produit->image_url)
                        <img src="{{ $produit->image_url }}" class="p-thumb" alt="{{ $produit->nom }}">
                    @else
                        <div class="p-thumb-empty"><i class="fas fa-box"></i></div>
                    @endif
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <span class="p-name">{{ $produit->nom }}</span>
                        <button class="p-copy"
                                onclick="copyLink('{{ route('boutique.produit.show', ['slug' => optional($produit->boutique)->slug ?? 'boutique', 'produit' => $produit->slug]) }}')"
                                title="Copier le lien">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                    <div style="font-size:.71rem;color:#9ca3af;margin-top:1px;">{{ $produit->achats_count ?? 0 }} vente(s)</div>
                </td>
                <td>
                    <span class="p-price {{ $produit->type === 'gratuit' ? 'free' : '' }}">
                        {{ $produit->type === 'gratuit' ? 'Gratuit' : number_format($produit->prix,0,',',' ').' FCFA' }}
                    </span>
                </td>
                <td style="color:#6b7280;font-weight:500;">{{ $produit->achats_count ?? 0 }}</td>
                <td>
                    <span class="p-badge {{ $produit->est_publie ? 'pub' : 'draft' }}">
                        <i class="fas {{ $produit->est_publie ? 'fa-check-circle' : 'fa-circle' }}"></i>
                        {{ $produit->est_publie ? 'Publié' : 'Brouillon' }}
                    </span>
                </td>
                <td style="text-align:right;padding-right:20px;">
                    <div class="p-action">
                        <button class="p-action-btn" onclick="toggleMenu(this)">
                            <i class="fas fa-ellipsis-v" style="font-size:.78rem;"></i>
                        </button>
                        <div class="p-dropdown">
                            @include('admin.produits._menu_actions', ['produit' => $produit])
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="p-empty">
                        <i class="fas fa-box-open"></i>
                        <p>Aucun produit trouvé.</p>
                        <a href="{{ route('admin.produits.create') }}" class="btn-add" style="display:inline-flex;">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>

        @if($produits->hasPages())
        <div class="p-pages">{{ $produits->withQueryString()->links() }}</div>
        @endif
    </div>

</div>

{{-- Toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:99999;">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body"><i class="fas fa-check me-2"></i> Lien copié !</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- Formulaires cachés --}}
@foreach($produits as $produit)
<form id="delete-form-{{ $produit->id }}" action="{{ route('admin.produits.destroy', $produit) }}" method="POST" class="d-none">
    @csrf @method('DELETE')
</form>
<form id="toggle-form-{{ $produit->id }}" action="{{ route('admin.produits.update', $produit) }}" method="POST" class="d-none">
    @csrf @method('PUT')
    <input type="hidden" name="nom"        value="{{ $produit->nom }}">
    <input type="hidden" name="prix"       value="{{ $produit->prix }}">
    <input type="hidden" name="est_publie" value="{{ $produit->est_publie ? 0 : 1 }}">
</form>
@endforeach

@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    var openMenu = null;

    window.toggleMenu = function(btn) {
        var container = btn.closest('.p-action');
        if (!container) return;
        var menu = container.querySelector('.p-dropdown');
        if (!menu) return;
        if (openMenu && openMenu !== menu) closeAll();
        if (menu.classList.contains('open')) { closeAll(); return; }
        positionMenu(btn, menu);
        menu.classList.add('open');
        openMenu = menu;
    };

    function positionMenu(btn, menu) {
        var r = btn.getBoundingClientRect();
        menu.style.top   = (r.bottom + 4) + 'px';
        menu.style.right = Math.max(0, window.innerWidth - r.right) + 'px';
        menu.style.left  = 'auto';
    }

    function closeAll() {
        document.querySelectorAll('.p-dropdown.open').forEach(function(m){ m.classList.remove('open'); });
        openMenu = null;
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.p-action')) closeAll();
    }, true);

    window.addEventListener('scroll', function() {
        if (openMenu) {
            var btn = openMenu.closest('.p-action').querySelector('.p-action-btn');
            if (btn) positionMenu(btn, openMenu);
        }
    }, { passive: true });

    window.copyLink = function(url) {
        navigator.clipboard.writeText(url).then(function() {
            closeAll();
            try { new bootstrap.Toast(document.getElementById('copyToast')).show(); } catch(e){}
        }).catch(function() { prompt('Lien :', url); });
    };

    window.confirmDelete = function(id, nom) {
        closeAll();
        if (confirm('Supprimer "' + nom + '" ? Cette action est irréversible.')) {
            var f = document.getElementById('delete-form-' + id);
            if (f) f.submit();
        }
    };

    window.togglePublier = function(id) {
        closeAll();
        var f = document.getElementById('toggle-form-' + id);
        if (f) f.submit();
    };
})();
</script>
@endpush
