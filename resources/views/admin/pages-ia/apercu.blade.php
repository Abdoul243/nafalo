@extends('layouts.admin')
@section('title', 'Aperçu de la page IA — ' . $produit->nom)

@push('styles')
<style>
.apercu-toolbar { background:white; border:1px solid #f1f5f9; border-radius:16px; padding:1rem 1.25rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; }
.device-btn { width:36px; height:36px; border-radius:9px; border:1.5px solid #e2e8f0; background:white; display:flex; align-items:center; justify-content:center; color:#64748b; cursor:pointer; transition:all 0.15s; }
.device-btn.active, .device-btn:hover { border-color:#4f46e5; color:#4f46e5; background:#f0f0ff; }
.iframe-wrap { background:#e2e8f0; border-radius:16px; padding:1rem; overflow:hidden; transition:all 0.3s; }
#page-frame { border:none; width:100%; height:85vh; border-radius:12px; background:white; transition:all 0.3s; display:block; }
.stat-chip { display:inline-flex; align-items:center; gap:5px; background:#f1f5f9; border-radius:20px; padding:3px 10px; font-size:0.75rem; color:#475569; font-weight:500; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">👁️ Aperçu de la page IA</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Produit : <strong>{{ $produit->nom }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pages-ia.create', $produit) }}" class="btn btn-light" style="border-radius:10px;">
            <i class="fas fa-redo me-1"></i> Régénérer
        </a>
        <form action="{{ route('admin.pages-ia.publier', ['produit' => $produit->id, 'page' => $page->id]) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn {{ $page->est_publiee ? 'btn-warning' : 'btn-success' }}" style="border-radius:10px;">
                <i class="fas {{ $page->est_publiee ? 'fa-eye-slash' : 'fa-globe' }} me-1"></i>
                {{ $page->est_publiee ? 'Dépublier' : 'Publier la page' }}
            </button>
        </form>
    </div>
</div>

{{-- Infos --}}
<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <span class="stat-chip"><i class="fas fa-clock"></i> Générée {{ $page->created_at->diffForHumans() }}</span>
    @if($page->tokens_utilises)
    <span class="stat-chip"><i class="fas fa-bolt"></i> {{ number_format($page->tokens_utilises) }} tokens</span>
    @endif
    <span class="stat-chip {{ $page->est_publiee ? 'text-success' : '' }}" style="{{ $page->est_publiee ? 'background:#f0fdf4;' : '' }}">
        <i class="fas {{ $page->est_publiee ? 'fa-globe' : 'fa-eye-slash' }}"></i>
        {{ $page->est_publiee ? 'Publiée' : 'Non publiée' }}
    </span>
    @if($page->est_publiee && $page->slug_page)
    <a href="{{ route('pages-ia.publique', $page->slug_page) }}" target="_blank" class="stat-chip text-primary" style="background:#eff6ff;text-decoration:none;">
        <i class="fas fa-external-link-alt"></i> Voir en ligne
    </a>
    @endif
</div>

{{-- Toolbar de prévisualisation --}}
<div class="apercu-toolbar">
    <div class="d-flex align-items-center gap-2">
        <span style="font-size:0.82rem;color:#64748b;font-weight:600;">Prévisualisation :</span>
        <div class="device-btn active" id="btn-desktop" onclick="setDevice('desktop')" title="Bureau">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="device-btn" id="btn-tablet" onclick="setDevice('tablet')" title="Tablette">
            <i class="fas fa-tablet-alt"></i>
        </div>
        <div class="device-btn" id="btn-mobile" onclick="setDevice('mobile')" title="Mobile">
            <i class="fas fa-mobile-alt"></i>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button onclick="copyPageUrl()" class="btn btn-sm btn-light" style="border-radius:8px;font-size:0.78rem;" {{ !$page->est_publiee ? 'disabled' : '' }}>
            <i class="fas fa-copy me-1"></i> Copier le lien
        </button>
        <button onclick="downloadHtml()" class="btn btn-sm btn-light" style="border-radius:8px;font-size:0.78rem;">
            <i class="fas fa-download me-1"></i> Télécharger HTML
        </button>
    </div>
</div>

{{-- iframe --}}
<div class="iframe-wrap" id="iframe-wrap">
    <iframe id="page-frame" srcdoc="{{ htmlspecialchars($htmlPreview) }}"
        sandbox="allow-same-origin allow-scripts allow-popups"></iframe>
</div>
@endsection

@push('scripts')
<script>
const devices = {
    desktop: { width: '100%',   label: 'Bureau' },
    tablet:  { width: '768px',  label: 'Tablette' },
    mobile:  { width: '390px',  label: 'Mobile' },
};

function setDevice(type) {
    const frame = document.getElementById('page-frame');
    const wrap  = document.getElementById('iframe-wrap');
    document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-' + type).classList.add('active');

    if (type === 'desktop') {
        frame.style.width = '100%';
        wrap.style.display = 'block';
    } else {
        frame.style.width = devices[type].width;
        wrap.style.display = 'flex';
        wrap.style.justifyContent = 'center';
    }
}

function copyPageUrl() {
    @if($page->est_publiee && $page->slug_page)
    const url = '{{ route("pages-ia.publique", $page->slug_page) }}';
    navigator.clipboard.writeText(url).then(() => alert('Lien copié !'));
    @endif
}

function downloadHtml() {
    const html = `{{ addslashes($page->contenu_html) }}`;
    const blob = new Blob([html.replace(/\\n/g,'\n').replace(/\\t/g,'\t')], { type: 'text/html' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = '{{ Str::slug($produit->nom) }}-page-vente.html';
    a.click();
}
</script>
@endpush
