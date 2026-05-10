@extends('layouts.admin')
@section('title', 'Pages de vente IA')

@push('styles')
<style>
.page-ia-card {
    background: white; border-radius: 18px; border: 1px solid #f1f5f9;
    overflow: hidden; transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.page-ia-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
.pia-header {
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    padding: 1.25rem; position: relative; overflow: hidden;
}
.pia-header::after {
    content: '✨'; position: absolute; right: 12px; bottom: -8px;
    font-size: 4rem; opacity: 0.12;
}
.pia-status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.pia-status-dot.publiee  { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.2); }
.pia-status-dot.brouillon { background: #94a3b8; }
.url-copy-wrap { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 8px; }
.url-copy-wrap input { border: none; background: none; font-size: 0.75rem; color: #475569; flex: 1; outline: none; font-family: monospace; }
.copy-btn { background: #2563eb; color: white; border: none; border-radius: 6px; padding: 3px 10px; font-size: 0.72rem; font-weight: 600; cursor: pointer; white-space: nowrap; }
.copy-btn:hover { background: #1d4ed8; }
.hero-ia { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4f46e5 100%); border-radius: 20px; padding: 2.5rem 2rem; margin-bottom: 2rem; color: white; text-align: center; position: relative; overflow: hidden; }
.hero-ia::before { content: ''; position: absolute; top: -60px; right: -60px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="hero-ia">
    <div style="font-size:2.5rem;margin-bottom:0.75rem;">✨</div>
    <h1 class="fw-black mb-2" style="font-size:1.6rem;">Pages de vente IA</h1>
    <p class="mb-3" style="opacity:0.85;font-size:0.92rem;max-width:520px;margin:0 auto 1.25rem;">
        Créez des pages de vente ultra-convaincantes générées par Claude AI,
        publiées directement sur votre boutique, avec un bouton d'achat fonctionnel.
    </p>
    <a href="{{ route('admin.produits.index') }}" class="btn"
       style="background:rgba(255,255,255,0.15);color:white;border:1.5px solid rgba(255,255,255,0.3);border-radius:12px;font-weight:600;backdrop-filter:blur(4px);">
        <i class="fas fa-box me-2"></i> Choisir un produit pour créer une page
    </a>
</div>

{{-- Stats --}}
@if($pages->isNotEmpty())
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left:4px solid #4f46e5;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Pages créées</div>
                <div class="fw-black" style="font-size:1.6rem;">{{ $pages->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left:4px solid #22c55e;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Pages publiées</div>
                <div class="fw-black" style="font-size:1.6rem;color:#16a34a;">{{ $pages->where('est_publiee', true)->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left:4px solid #f59e0b;">
            <div class="card-body">
                <div class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:.05em;">Tokens IA utilisés</div>
                <div class="fw-black" style="font-size:1.6rem;color:#d97706;">{{ number_format($pages->sum('tokens_utilises')) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Grille des pages --}}
<div class="row g-4">
    @foreach($pages as $page)
    @php $produit = $page->produit; @endphp
    <div class="col-md-6 col-lg-4">
        <div class="page-ia-card">
            {{-- Header violet avec statut --}}
            <div class="pia-header">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="pia-status-dot {{ $page->est_publiee ? 'publiee' : 'brouillon' }}"></span>
                        <span style="font-size:0.72rem;color:rgba(255,255,255,0.7);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">
                            {{ $page->est_publiee ? '🟢 Publiée' : '⚪ Brouillon' }}
                        </span>
                    </div>
                    <span style="font-size:0.68rem;color:rgba(255,255,255,0.5);">{{ $page->created_at->diffForHumans() }}</span>
                </div>
                <div class="fw-bold text-white" style="font-size:0.95rem;line-height:1.3;">
                    {{ $produit?->nom ?? '(Produit supprimé)' }}
                </div>
                @if($page->tokens_utilises)
                <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);margin-top:4px;">
                    <i class="fas fa-bolt me-1"></i>{{ number_format($page->tokens_utilises) }} tokens
                </div>
                @endif
            </div>

            {{-- Corps de la card --}}
            <div class="p-3">
                {{-- URL boutique --}}
                @if($page->est_publiee && $page->slug_page)
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;margin-bottom:4px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">URL de la page</div>
                    <div class="url-copy-wrap">
                        <input type="text" readonly
                            value="{{ route('boutique.landing-page', $page->slug_page) }}"
                            id="url-{{ $page->id }}" onclick="this.select()">
                        <button class="copy-btn" onclick="copierUrl('url-{{ $page->id }}')">
                            <i class="fas fa-copy me-1"></i> Copier
                        </button>
                    </div>
                </div>
                @endif

                {{-- Prompt utilisé --}}
                @if($page->prompt_original)
                <div class="mb-3" style="font-size:0.78rem;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $page->prompt_original }}
                </div>
                @endif

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-wrap">
                    @if($produit)
                    <a href="{{ route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $page->id]) }}"
                       class="btn btn-sm btn-light flex-grow-1" style="border-radius:8px;font-size:0.78rem;">
                        <i class="fas fa-eye me-1"></i> Aperçu
                    </a>

                    <form action="{{ route('admin.pages-ia.publier', ['produit' => $produit->id, 'page' => $page->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $page->est_publiee ? 'btn-warning' : 'btn-success' }}" style="border-radius:8px;font-size:0.78rem;">
                            <i class="fas {{ $page->est_publiee ? 'fa-eye-slash' : 'fa-globe' }} me-1"></i>
                            {{ $page->est_publiee ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>

                    @if($page->est_publiee && $page->slug_page)
                    <a href="{{ route('boutique.landing-page', $page->slug_page) }}" target="_blank"
                       class="btn btn-sm btn-primary" style="border-radius:8px;font-size:0.78rem;" title="Voir sur la boutique">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    @endif

                    <a href="{{ route('admin.pages-ia.editer', ['produit' => $produit->id, 'page' => $page->id]) }}"
                       class="btn btn-sm btn-light" style="border-radius:8px;font-size:0.78rem;" title="Éditer le HTML">
                        <i class="fas fa-code"></i>
                    </a>

                    <form action="{{ route('admin.pages-ia.destroy', ['produit' => $produit->id, 'page' => $page->id]) }}" method="POST"
                          onsubmit="return confirm('Supprimer cette page de vente ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger" style="border-radius:8px;font-size:0.78rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Lien regénérer --}}
                @if($produit)
                <div class="mt-2 text-center">
                    <a href="{{ route('admin.pages-ia.create', $produit) }}" style="font-size:0.72rem;color:#7c3aed;text-decoration:none;">
                        <i class="fas fa-redo me-1"></i> Regénérer avec l'IA
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@else
{{-- État vide --}}
<div class="card text-center" style="padding:3rem 2rem;">
    <div style="font-size:3.5rem;margin-bottom:1rem;">✨</div>
    <h4 class="fw-bold mb-2">Aucune page de vente créée</h4>
    <p class="text-muted mb-4" style="max-width:420px;margin:0 auto 1.5rem;">
        Choisissez un de vos produits et laissez Claude AI créer une page de vente professionnelle en quelques secondes.
    </p>
    <a href="{{ route('admin.produits.index') }}" class="btn btn-primary" style="border-radius:12px;padding:0.75rem 2rem;">
        <i class="fas fa-box me-2"></i> Voir mes produits
    </a>
</div>
@endif

@endsection

@push('scripts')
<script>
function copierUrl(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    navigator.clipboard.writeText(input.value)
        .then(() => {
            const btn = input.nextElementSibling;
            btn.innerHTML = '<i class="fas fa-check me-1"></i> Copié !';
            btn.style.background = '#16a34a';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy me-1"></i> Copier';
                btn.style.background = '';
            }, 2000);
        });
}
</script>
@endpush
