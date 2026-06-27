@extends('layouts.admin')
@section('title', 'Programme — ' . $produit->nom)

@push('styles')
<style>
.fb-wrap { max-width: 920px; margin: 0 auto; padding: 1.5rem; }
.fb-head { display:flex; align-items:center; gap:12px; margin-bottom:1.5rem; }
.fb-back { width:38px; height:38px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; display:flex; align-items:center; justify-content:center; color:#374151; text-decoration:none; }
.fb-back:hover { background:#f3f4f6; }
.fb-title { font-size:1.3rem; font-weight:800; color:#111827; margin:0; }
.fb-sub { font-size:0.85rem; color:#6b7280; margin:2px 0 0; }

.fb-module { background:#fff; border:1px solid #e9eaeb; border-radius:14px; margin-bottom:1.1rem; overflow:hidden; }
.fb-module-head { display:flex; align-items:center; gap:10px; padding:1rem 1.25rem; background:#fafafa; border-bottom:1px solid #eee; }
.fb-module-head .num { width:26px; height:26px; border-radius:7px; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:800; flex-shrink:0; }
.fb-module-head .titre { flex:1; font-weight:700; color:#111827; font-size:1rem; }
.fb-icon-btn { background:none; border:none; cursor:pointer; color:#9ca3af; padding:6px; border-radius:6px; font-size:0.85rem; }
.fb-icon-btn:hover { background:#f3f4f6; color:#111827; }
.fb-icon-btn.danger:hover { color:#dc2626; }

.fb-lecons { padding:0.5rem 1.25rem; }
.fb-lecon { display:flex; align-items:center; gap:10px; padding:0.6rem 0; border-bottom:1px solid #f3f4f6; }
.fb-lecon:last-child { border-bottom:none; }
.fb-lecon .ico { width:30px; height:30px; border-radius:8px; background:#eef2ff; color:#4f46e5; display:flex; align-items:center; justify-content:center; font-size:0.8rem; flex-shrink:0; }
.fb-lecon .info { flex:1; min-width:0; }
.fb-lecon .info .t { font-weight:600; color:#111827; font-size:0.9rem; }
.fb-lecon .info .m { font-size:0.75rem; color:#9ca3af; }
.fb-badge { font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:20px; }
.fb-badge.apercu { background:#dcfce7; color:#166534; }

.fb-add { padding:0.75rem 1.25rem 1rem; }
details.fb-form { border:1px dashed #d1d5db; border-radius:10px; padding:0; margin-top:0.5rem; }
details.fb-form summary { padding:0.7rem 1rem; cursor:pointer; color:#4f46e5; font-weight:600; font-size:0.85rem; list-style:none; }
details.fb-form summary::-webkit-details-marker { display:none; }
details.fb-form[open] summary { border-bottom:1px solid #eee; }
.fb-form-body { padding:1rem; display:flex; flex-direction:column; gap:0.7rem; }
.fb-field label { display:block; font-size:0.8rem; font-weight:600; color:#374151; margin-bottom:4px; }
.fb-field input[type=text], .fb-field input[type=url], .fb-field input[type=number], .fb-field textarea, .fb-field input[type=file] {
    width:100%; border:1px solid #d1d5db; border-radius:9px; padding:0.55rem 0.75rem; font-size:0.875rem; font-family:inherit; outline:none;
}
.fb-field input:focus, .fb-field textarea:focus { border-color:#4f46e5; }
.fb-row { display:flex; gap:0.7rem; flex-wrap:wrap; }
.fb-row > * { flex:1; min-width:140px; }
.fb-hint { font-size:0.72rem; color:#9ca3af; margin-top:3px; }
.fb-check { display:flex; align-items:center; gap:7px; font-size:0.83rem; color:#374151; }
.fb-btn { background:#4f46e5; color:#fff; border:none; border-radius:9px; padding:0.6rem 1.1rem; font-weight:700; font-size:0.85rem; cursor:pointer; align-self:flex-start; }
.fb-btn:hover { background:#4338ca; }
.fb-btn-add-module { width:100%; background:#fff; border:1.5px dashed #c7d2fe; color:#4f46e5; border-radius:12px; padding:0.9rem; font-weight:700; cursor:pointer; font-size:0.9rem; }
.fb-btn-add-module:hover { background:#eef2ff; }
.fb-empty { text-align:center; padding:2.5rem 1rem; color:#9ca3af; }
.fb-alert { background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.7rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1rem; }
</style>
@endpush

@section('content')
<div class="fb-wrap">

    <div class="fb-head">
        <a href="{{ route('admin.produits.edit', $produit) }}" class="fb-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="fb-title">{{ $produit->nom }}</h1>
            <p class="fb-sub">Programme de la formation · {{ $produit->modules->count() }} module(s) · {{ $produit->nbLecons() }} leçon(s)</p>
        </div>
    </div>

    @if(session('success'))<div class="fb-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

    {{-- Modules --}}
    @forelse($produit->modules as $i => $module)
    <div class="fb-module">
        <div class="fb-module-head">
            <span class="num">{{ $i + 1 }}</span>
            <span class="titre">{{ $module->titre }}</span>
            <form action="{{ route('admin.produits.formation.modules.destroy', $module) }}" method="POST"
                  onsubmit="return confirm('Supprimer ce module et ses leçons ?')">
                @csrf @method('DELETE')
                <button class="fb-icon-btn danger" title="Supprimer le module"><i class="fas fa-trash"></i></button>
            </form>
        </div>

        <div class="fb-lecons">
            @forelse($module->lecons as $lecon)
            <div class="fb-lecon">
                <span class="ico">
                    @if($lecon->typeVideo())<i class="fas fa-play"></i>
                    @elseif($lecon->ressource_fichier)<i class="fas fa-file"></i>
                    @else<i class="fas fa-align-left"></i>@endif
                </span>
                <div class="info">
                    <div class="t">{{ $lecon->titre }}
                        @if($lecon->est_apercu)<span class="fb-badge apercu">Aperçu gratuit</span>@endif
                    </div>
                    <div class="m">
                        @if($lecon->typeVideo() === 'lien')Vidéo (lien)
                        @elseif($lecon->typeVideo() === 'upload')Vidéo (fichier)
                        @else Texte @endif
                        @if($lecon->duree) · {{ $lecon->duree }} min @endif
                        @if($lecon->ressource_fichier) · ressource @endif
                    </div>
                </div>
                <form action="{{ route('admin.produits.formation.lecons.destroy', $lecon) }}" method="POST"
                      onsubmit="return confirm('Supprimer cette leçon ?')">
                    @csrf @method('DELETE')
                    <button class="fb-icon-btn danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @empty
            <p style="color:#9ca3af;font-size:0.85rem;padding:0.5rem 0;">Aucune leçon dans ce module.</p>
            @endforelse
        </div>

        {{-- Ajouter une leçon --}}
        <div class="fb-add">
            <details class="fb-form">
                <summary><i class="fas fa-plus"></i> Ajouter une leçon</summary>
                <form class="fb-form-body" action="{{ route('admin.produits.formation.lecons.store', $module) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="fb-field">
                        <label>Titre de la leçon *</label>
                        <input type="text" name="titre" required placeholder="Ex : Introduction au module">
                    </div>
                    <div class="fb-field">
                        <label>Lien vidéo (YouTube / Vimeo)</label>
                        <input type="url" name="video_url" placeholder="https://youtu.be/...">
                        <div class="fb-hint">Collez un lien YouTube/Vimeo (non répertorié recommandé), OU téléversez un fichier ci-dessous.</div>
                    </div>
                    <div class="fb-field">
                        <label>… ou fichier vidéo (mp4, webm — max 500 Mo)</label>
                        <input type="file" name="video_fichier" accept="video/*">
                    </div>
                    <div class="fb-field">
                        <label>Contenu / description (texte)</label>
                        <textarea name="contenu" rows="3" placeholder="Notes, transcription, points clés…"></textarea>
                    </div>
                    <div class="fb-row">
                        <div class="fb-field">
                            <label>Ressource à télécharger (PDF, ZIP…)</label>
                            <input type="file" name="ressource_fichier">
                        </div>
                        <div class="fb-field">
                            <label>Durée (minutes)</label>
                            <input type="number" name="duree" min="0" placeholder="Ex : 12">
                        </div>
                    </div>
                    <label class="fb-check">
                        <input type="checkbox" name="est_apercu" value="1"> Leçon visible en aperçu gratuit (sans achat)
                    </label>
                    <button class="fb-btn"><i class="fas fa-plus"></i> Ajouter la leçon</button>
                </form>
            </details>
        </div>
    </div>
    @empty
    <div class="fb-empty">
        <i class="fas fa-graduation-cap" style="font-size:2.5rem;color:#d1d5db;"></i>
        <p>Aucun module pour l'instant. Créez votre premier module ci-dessous.</p>
    </div>
    @endforelse

    {{-- Ajouter un module --}}
    <form action="{{ route('admin.produits.formation.modules.store', $produit) }}" method="POST"
          style="display:flex;gap:0.6rem;margin-top:0.5rem;">
        @csrf
        <input type="text" name="titre" required placeholder="Titre du nouveau module (ex : Module 1 — Les bases)"
               style="flex:1;border:1px solid #d1d5db;border-radius:10px;padding:0.8rem 1rem;font-size:0.9rem;outline:none;">
        <button class="fb-btn" style="white-space:nowrap;"><i class="fas fa-plus"></i> Ajouter le module</button>
    </form>

</div>
@endsection
