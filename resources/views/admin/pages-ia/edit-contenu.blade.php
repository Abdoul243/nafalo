@extends('layouts.admin')
@section('title', 'Éditer le HTML — ' . $produit->nom)

@push('styles')
<style>
.editor-wrap {
    display: flex; height: calc(100vh - 160px); gap: 0; border-radius: 16px;
    overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.10); border: 1px solid #e2e8f0;
}
.editor-panel {
    flex: 1; display: flex; flex-direction: column; min-width: 0;
    background: #1e1e2e;
}
.editor-header {
    background: #16162a; padding: 0.65rem 1rem; display: flex; align-items: center;
    justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.editor-header .tab {
    font-size: 0.75rem; color: rgba(255,255,255,0.5); font-family: monospace;
    display: flex; align-items: center; gap: 6px;
}
.editor-header .tab .dot { width: 8px; height: 8px; border-radius: 50%; background: #4ade80; }
#html-editor {
    flex: 1; width: 100%; background: #1e1e2e; color: #cdd6f4;
    font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', 'Courier New', monospace;
    font-size: 0.82rem; line-height: 1.6; border: none; outline: none; resize: none;
    padding: 1rem; tab-size: 2;
}
.preview-panel {
    flex: 1; display: flex; flex-direction: column; min-width: 0;
    background: white; border-left: 1px solid #e2e8f0;
}
.preview-header {
    background: #f8fafc; padding: 0.65rem 1rem;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #e2e8f0; flex-shrink: 0;
}
#preview-iframe {
    flex: 1; width: 100%; border: none;
}
.editor-toolbar {
    background: #16162a; padding: 0.4rem 1rem; display: flex; gap: 6px; flex-shrink: 0;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.tool-btn {
    background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.65);
    border: none; border-radius: 6px; padding: 3px 9px; font-size: 0.7rem;
    font-family: monospace; cursor: pointer; transition: all 0.15s;
}
.tool-btn:hover { background: rgba(255,255,255,0.12); color: white; }
.char-count { font-size: 0.68rem; color: rgba(255,255,255,0.3); margin-left: auto; }
.save-bar {
    background: white; border-top: 1px solid #e2e8f0; padding: 0.75rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    flex-shrink: 0;
}
.save-bar .actions { display: flex; gap: 0.75rem; align-items: center; }
.save-indicator {
    font-size: 0.75rem; color: #64748b; display: flex; align-items: center; gap: 5px;
}
.save-indicator.saved { color: #16a34a; }
.save-indicator.modified { color: #f59e0b; }

/* Syntax highlight hint colors */
#html-editor::selection { background: rgba(99,102,241,0.4); }
</style>
@endpush

@section('content')

{{-- Top bar --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $page->id]) }}"
           class="btn btn-sm btn-light" style="border-radius:8px;">
            <i class="fas fa-arrow-left me-1"></i> Retour aperçu
        </a>
        <div>
            <div class="fw-bold" style="font-size:0.95rem;">{{ $produit->nom }}</div>
            <div style="font-size:0.72rem;color:#94a3b8;">
                Éditeur HTML · {{ number_format(strlen($page->contenu_html)) }} caractères
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        @if($page->est_publiee)
        <a href="{{ route('boutique.landing-page', $page->slug_page) }}" target="_blank"
           class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:0.78rem;">
            <i class="fas fa-external-link-alt me-1"></i> Voir sur la boutique
        </a>
        @endif
        <button type="button" class="btn btn-sm btn-primary" id="save-btn" style="border-radius:8px;font-size:0.78rem;">
            <i class="fas fa-save me-1"></i> Sauvegarder
        </button>
    </div>
</div>

{{-- Alertes --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:10px;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Éditeur principal --}}
<div class="editor-wrap">

    {{-- Panel gauche : code --}}
    <div class="editor-panel">
        <div class="editor-header">
            <div class="tab">
                <span class="dot"></span>
                <span style="color:rgba(255,255,255,0.7);">page-ia-{{ $page->id }}.html</span>
            </div>
            <div style="font-size:0.65rem;color:rgba(255,255,255,0.3);">HTML</div>
        </div>
        <div class="editor-toolbar">
            <button class="tool-btn" onclick="insererTag('b')"><b>B</b></button>
            <button class="tool-btn" onclick="insererTag('i')"><i>I</i></button>
            <button class="tool-btn" onclick="insererSnippet('<a href=\"\">lien</a>')">lien</button>
            <button class="tool-btn" onclick="insererSnippet('<br>')">br</button>
            <button class="tool-btn" onclick="insererSnippet('<hr style=\"margin:2rem 0;\">')">hr</button>
            <button class="tool-btn" onclick="formaterCode()">⚡ Formater</button>
            <button class="tool-btn" onclick="resetCode()">↩ Réinitialiser</button>
            <span class="char-count" id="char-count">0 chars</span>
        </div>
        <textarea id="html-editor" spellcheck="false">{{ $page->contenu_html }}</textarea>
    </div>

    {{-- Panel droit : aperçu live --}}
    <div class="preview-panel">
        <div class="preview-header">
            <div style="font-size:0.78rem;font-weight:600;color:#475569;">
                <i class="fas fa-eye me-1 text-indigo-500" style="color:#6366f1;"></i>
                Aperçu temps réel
            </div>
            <div style="display:flex;gap:5px;align-items:center;">
                <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></div>
                <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;"></div>
                <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;"></div>
            </div>
        </div>
        <iframe id="preview-iframe" sandbox="allow-scripts allow-same-origin"></iframe>
    </div>

</div>

{{-- Formulaire de sauvegarde (caché) --}}
<form id="save-form"
      action="{{ route('admin.pages-ia.save-contenu', ['produit' => $produit->id, 'page' => $page->id]) }}"
      method="POST" class="d-none">
    @csrf
    @method('PUT')
    <input type="hidden" name="contenu_html" id="form-contenu">
</form>

@endsection

@push('scripts')
<script>
const editor = document.getElementById('html-editor');
const iframe  = document.getElementById('preview-iframe');
const form    = document.getElementById('save-form');
const saveBtn = document.getElementById('save-btn');
const charCount = document.getElementById('char-count');
const originalHtml = editor.value;

// Mise à jour aperçu live
function updatePreview() {
    try {
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        doc.open();
        doc.write(editor.value);
        doc.close();
    } catch(e) {}
    charCount.textContent = editor.value.length.toLocaleString('fr-FR') + ' chars';
}

// Debounce
let previewTimer;
editor.addEventListener('input', function() {
    clearTimeout(previewTimer);
    previewTimer = setTimeout(updatePreview, 300);
});

// Tab key → indent
editor.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        e.preventDefault();
        const start = this.selectionStart;
        const end   = this.selectionEnd;
        this.value  = this.value.substring(0, start) + '  ' + this.value.substring(end);
        this.selectionStart = this.selectionEnd = start + 2;
    }
    // Ctrl+S → save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        sauvegarder();
    }
});

// Sauvegarde
function sauvegarder() {
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sauvegarde...';
    saveBtn.disabled = true;
    document.getElementById('form-contenu').value = editor.value;
    form.submit();
}

document.getElementById('save-btn').addEventListener('click', sauvegarder);

// Insérer tag autour de la sélection
function insererTag(tag) {
    const start = editor.selectionStart;
    const end   = editor.selectionEnd;
    const sel   = editor.value.substring(start, end);
    const repl  = `<${tag}>${sel}</${tag}>`;
    editor.value = editor.value.substring(0, start) + repl + editor.value.substring(end);
    editor.selectionStart = start;
    editor.selectionEnd   = start + repl.length;
    editor.focus();
    updatePreview();
}

// Insérer snippet à la position du curseur
function insererSnippet(snippet) {
    const start = editor.selectionStart;
    editor.value = editor.value.substring(0, start) + snippet + editor.value.substring(editor.selectionEnd);
    editor.selectionStart = editor.selectionEnd = start + snippet.length;
    editor.focus();
    updatePreview();
}

// Formater: ajouter indentation basique (simplifié)
function formaterCode() {
    // Simple: normalise les newlines multiples
    editor.value = editor.value
        .replace(/\n{3,}/g, '\n\n')
        .replace(/>\s+</g, '>\n<')
        .trim();
    updatePreview();
}

// Réinitialiser au code original
function resetCode() {
    if (confirm('Réinitialiser au code d\'origine ? Les modifications non sauvegardées seront perdues.')) {
        editor.value = originalHtml;
        updatePreview();
    }
}

// Init
updatePreview();
</script>
@endpush
