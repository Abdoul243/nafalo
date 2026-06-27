@extends('layouts.admin')
@section('title', 'Pages de vente IA')

@push('styles')
<style>
.pia-card { background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.04); }
.pia-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.08);transform:translateY(-2px); }
.pia-head { background:linear-gradient(135deg,#1e1b4b,#4f46e5);padding:16px 20px;position:relative;overflow:hidden; }
.pia-head::after { content:'✦';position:absolute;right:12px;bottom:-8px;font-size:4rem;opacity:.1; }
.url-wrap { background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:6px 12px;display:flex;align-items:center;gap:8px; }
.url-wrap input { border:none;background:none;font-size:.72rem;color:#475569;flex:1;outline:none;font-family:monospace; }
.copy-btn { background:#111827;color:#fff;border:none;border-radius:6px;padding:3px 10px;font-size:.72rem;font-weight:600;cursor:pointer;white-space:nowrap; }
.copy-btn:hover { background:#374151; }
</style>
@endpush

@section('content')
<div class="cw-page">

    {{-- Hero --}}
    <div style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#4f46e5 100%);border-radius:16px;padding:40px 32px;margin-bottom:24px;color:#fff;text-align:center;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
        <div style="font-size:2.5rem;margin-bottom:12px;">✦</div>
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:8px;">Pages de vente IA</h1>
        <p style="opacity:.85;font-size:.88rem;max-width:520px;margin:0 auto 20px;">
            Créez des pages de vente ultra-convaincantes générées par Claude AI, publiées directement sur votre boutique.
        </p>
        <a href="{{ route('admin.produits.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);border-radius:10px;padding:10px 20px;font-size:.83rem;font-weight:600;text-decoration:none;backdrop-filter:blur(4px);">
            <i class="fas fa-box"></i> Choisir un produit pour créer une page
        </a>
    </div>

    @if($pages->isNotEmpty())

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 20px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;margin-bottom:6px;">Pages créées</div>
            <div style="font-size:1.6rem;font-weight:800;color:#111827;">{{ $pages->count() }}</div>
        </div>
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 20px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;margin-bottom:6px;">Pages publiées</div>
            <div style="font-size:1.6rem;font-weight:800;color:#16a34a;">{{ $pages->where('est_publiee', true)->count() }}</div>
        </div>
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 20px;">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;margin-bottom:6px;">Tokens IA utilisés</div>
            <div style="font-size:1.6rem;font-weight:800;color:#d97706;">{{ number_format($pages->sum('tokens_utilises')) }}</div>
        </div>
    </div>

    {{-- Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
        @foreach($pages as $page)
        @php $produit = $page->produit; @endphp
        <div class="pia-card">
            <div class="pia-head">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:7px;">
                        <span style="width:7px;height:7px;border-radius:50%;background:{{ $page->est_publiee ? '#22c55e' : '#9ca3af' }};display:inline-block;"></span>
                        <span style="font-size:.7rem;color:rgba(255,255,255,.7);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">
                            {{ $page->est_publiee ? 'Publiée' : 'Brouillon' }}
                        </span>
                    </div>
                    <span style="font-size:.68rem;color:rgba(255,255,255,.5);">{{ $page->created_at->diffForHumans() }}</span>
                </div>
                <div style="font-weight:700;color:#fff;font-size:.92rem;line-height:1.3;">
                    {{ $produit?->nom ?? '(Produit supprimé)' }}
                </div>
                @if($page->tokens_utilises)
                <div style="font-size:.68rem;color:rgba(255,255,255,.5);margin-top:4px;">
                    <i class="fas fa-bolt me-1"></i>{{ number_format($page->tokens_utilises) }} tokens
                </div>
                @endif
            </div>
            <div style="padding:16px;">
                @if($page->est_publiee && $page->slug_page)
                <div style="margin-bottom:12px;">
                    <div style="font-size:.7rem;color:#9ca3af;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;">URL de la page</div>
                    <div class="url-wrap">
                        <input type="text" readonly value="{{ route('boutique.landing-page', $page->slug_page) }}" id="url-{{ $page->id }}" onclick="this.select()">
                        <button class="copy-btn" onclick="copyUrl('url-{{ $page->id }}')">
                            <i class="fas fa-copy"></i> Copier
                        </button>
                    </div>
                </div>
                @endif
                @if($page->prompt_original)
                <div style="font-size:.76rem;color:#6b7280;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:12px;">
                    {{ $page->prompt_original }}
                </div>
                @endif
                @if($produit)
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <a href="{{ route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $page->id]) }}"
                       class="cw-btn-secondary" style="flex:1;justify-content:center;font-size:.75rem;">
                        <i class="fas fa-eye"></i> Aperçu
                    </a>
                    <form action="{{ route('admin.pages-ia.publier', ['produit' => $produit->id, 'page' => $page->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="{{ $page->est_publiee ? 'cw-btn-secondary' : 'cw-btn-primary' }}" style="font-size:.75rem;">
                            <i class="fas {{ $page->est_publiee ? 'fa-eye-slash' : 'fa-globe' }}"></i>
                            {{ $page->est_publiee ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>
                    @if($page->est_publiee && $page->slug_page)
                    <a href="{{ route('boutique.landing-page', $page->slug_page) }}" target="_blank"
                       class="cw-btn-secondary" style="font-size:.75rem;" title="Voir sur la boutique">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    @endif
                    <a href="{{ route('admin.pages-ia.editer', ['produit' => $produit->id, 'page' => $page->id]) }}"
                       class="cw-btn-secondary" style="font-size:.75rem;" title="Éditer le HTML">
                        <i class="fas fa-code"></i>
                    </a>
                    <button class="cw-btn-row" style="color:#dc2626;border-color:#fecaca;"
                            data-confirm-message="Supprimer cette page de vente ?"
                            data-target-form="del-{{ $page->id }}" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                    <form id="del-{{ $page->id }}" action="{{ route('admin.pages-ia.destroy', ['produit' => $produit->id, 'page' => $page->id]) }}" method="POST" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                </div>
                <div style="margin-top:10px;text-align:center;">
                    <a href="{{ route('admin.pages-ia.create', $produit) }}" style="font-size:.72rem;color:#6366f1;text-decoration:none;">
                        <i class="fas fa-redo me-1"></i> Regénérer avec l'IA
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="cw-empty">
        <i class="fas fa-magic"></i>
        <p>Aucune page de vente créée</p>
        <a href="{{ route('admin.produits.index') }}" class="cw-btn-primary" style="display:inline-flex;">
            <i class="fas fa-box"></i> Voir mes produits
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function copyUrl(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = input.nextElementSibling;
        btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
        btn.style.background = '#16a34a';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy"></i> Copier'; btn.style.background = ''; }, 2000);
    });
}
</script>
@endpush
