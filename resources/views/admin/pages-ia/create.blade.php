@extends('layouts.admin')
@section('title', 'Générer une page IA — ' . $produit->nom)

@push('styles')
<style>
.ia-hero { background: linear-gradient(135deg, #1e1b4b, #312e81, #4338ca); border-radius: 20px; padding: 2rem; color: white; margin-bottom: 1.75rem; position: relative; overflow: hidden; }
.ia-hero::before { content: ''; position: absolute; top: -40px; right: -40px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.ia-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 4px 12px; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.75rem; }
.product-data-card { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.75rem; }
.product-data-card .pdc-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 3px; }
.product-data-card .pdc-value { font-size: 0.92rem; font-weight: 600; color: #1e293b; }
.style-card { border: 2px solid #e2e8f0; border-radius: 14px; padding: 1rem; cursor: pointer; transition: all 0.2s; text-align: center; background: white; }
.style-card:hover { border-color: #6366f1; background: #fafafa; }
.style-radio { display: none; }
.style-radio:checked + .style-card { border-color: #4f46e5; background: #f0f0ff; }
.generate-btn { background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; border-radius: 14px; padding: 0.9rem 2rem; font-weight: 700; font-size: 1rem; color: white; cursor: pointer; transition: all 0.2s; width: 100%; }
.generate-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.4); }
.generate-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.existing-page { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.75rem; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">✨ Générer une page de vente IA</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Produit : <strong>{{ $produit->nom }}</strong></p>
    </div>
    <a href="{{ route('admin.produits.index') }}" class="btn btn-light" style="border-radius:10px;">
        <i class="fas fa-arrow-left me-1"></i> Retour aux produits
    </a>
</div>

{{-- Hero IA --}}
<div class="ia-hero">
    <div class="ia-badge"><i class="fas fa-wand-magic-sparkles"></i> Propulsé par Claude AI (Anthropic)</div>
    <h2 class="fw-black mb-2" style="font-size:1.4rem;">L'IA analyse votre produit et crée la page 🚀</h2>
    <p class="mb-0" style="opacity:0.85;font-size:0.88rem;line-height:1.6;">
        Pas besoin de ressaisir quoi que ce soit — l'IA lit directement les informations de votre produit (nom, description, prix, image) et génère une page de vente professionnelle optimisée pour le marché africain en moins de 30 secondes.
    </p>
</div>

{{-- Page existante --}}
@if($pageExistante)
<div class="existing-page">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="fw-bold text-success"><i class="fas fa-check-circle me-2"></i>Page existante générée</div>
            <div class="text-muted mt-1" style="font-size:0.82rem;">
                Créée {{ $pageExistante->created_at->diffForHumans() }} ·
                Statut : {{ $pageExistante->est_publiee ? '🟢 Publiée' : '⚪ Non publiée' }}
                {{ $pageExistante->tokens_utilises ? '· ' . number_format($pageExistante->tokens_utilises) . ' tokens' : '' }}
            </div>
        </div>
        <a href="{{ route('admin.pages-ia.apercu', ['produit' => $produit->id, 'page' => $pageExistante->id]) }}"
           class="btn btn-sm btn-success" style="border-radius:8px;">
            <i class="fas fa-eye me-1"></i> Voir la page
        </a>
    </div>
</div>
@endif

{{-- Données lues depuis le produit --}}
<div class="product-data-card mb-4">
    <div class="fw-bold mb-3" style="font-size:0.88rem;color:#475569;">
        <i class="fas fa-database me-2 text-primary"></i>
        Ce que l'IA va analyser automatiquement :
    </div>
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="pdc-label">Produit</div>
            <div class="pdc-value">{{ Str::limit($produit->nom, 35) }}</div>
        </div>
        <div class="col-6 col-md-2">
            <div class="pdc-label">Prix</div>
            <div class="pdc-value" style="color:#2563eb;">
                @if($produit->type === 'gratuit') GRATUIT
                @else {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                @endif
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="pdc-label">Catégorie</div>
            <div class="pdc-value">{{ $produit->categorie?->nom ?? '—' }}</div>
        </div>
        <div class="col-6 col-md-2">
            <div class="pdc-label">Image</div>
            <div class="pdc-value">
                @if($produit->image)
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Disponible</span>
                @else
                    <span class="text-muted"><i class="fas fa-times-circle me-1"></i>Aucune</span>
                @endif
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pdc-label">Description</div>
            <div class="pdc-value">
                @if($produit->description)
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>{{ str_word_count(strip_tags($produit->description)) }} mots</span>
                @else
                    <span class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Vide</span>
                @endif
            </div>
        </div>
    </div>
    @if($produit->image)
    <div class="mt-3" style="display:flex;align-items:center;gap:1rem;">
        <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}"
             style="width:80px;height:60px;object-fit:cover;border-radius:10px;border:1px solid #e2e8f0;">
        <span class="text-muted" style="font-size:0.8rem;">Cette image apparaîtra dans le hero de votre page de vente</span>
    </div>
    @endif
</div>

{{-- Formulaire --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.pages-ia.generer', $produit) }}" method="POST" id="form-ia">
            @csrf

            {{-- Style --}}
            <div class="mb-4">
                <label class="form-label fw-bold">🖌️ Style de la page</label>
                <div class="row g-2">
                    @foreach([
                        ['moderne',       '⚡', 'Moderne',        'Dynamique et percutant'],
                        ['minimaliste',   '☁️', 'Minimaliste',     'Épuré et élégant'],
                        ['audacieux',     '🔥', 'Audacieux',       'Couleurs vives, impactant'],
                        ['professionnel', '💼', 'Professionnel',   'Sobre et crédible'],
                    ] as [$val, $emoji, $nom, $desc])
                    <div class="col-6 col-md-3">
                        <input type="radio" class="style-radio" name="style" id="style_{{ $val }}" value="{{ $val }}"
                            {{ old('style', 'moderne') === $val ? 'checked' : '' }}>
                        <label for="style_{{ $val }}" class="style-card w-100 d-block">
                            <div style="font-size:1.5rem;margin-bottom:0.25rem;">{{ $emoji }}</div>
                            <div class="fw-bold" style="font-size:0.85rem;">{{ $nom }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $desc }}</div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Couleur --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">🎨 Couleur principale de la page</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="color" name="couleur_theme" id="couleur_theme" value="{{ old('couleur_theme', '#2563eb') }}"
                        style="width:48px;height:42px;border:none;border-radius:10px;cursor:pointer;padding:2px;">
                    <input type="text" id="couleur_hex" value="{{ old('couleur_theme', '#2563eb') }}"
                        class="form-control" style="border-radius:12px;width:110px;" maxlength="7"
                        oninput="document.getElementById('couleur_theme').value=this.value">
                    <span class="text-muted" style="font-size:0.82rem;">Sera utilisée pour les boutons et sections colorées</span>
                </div>
            </div>

            {{-- Instructions optionnelles --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    📝 Instructions supplémentaires <span class="text-muted fw-normal">(optionnel)</span>
                </label>
                <textarea name="instructions" class="form-control" rows="3" maxlength="1000"
                    style="border-radius:12px;resize:vertical;"
                    placeholder="Ex: Mets l'accent sur la rapidité des résultats. Cible les entrepreneurs de 25-40 ans. Mentionne que le support WhatsApp est inclus...">{{ old('instructions') }}</textarea>
                <div class="text-muted mt-1" style="font-size:0.75rem;">Précisions pour affiner la page générée (public cible, angle marketing, éléments à mettre en avant...)</div>
            </div>

            {{-- Bouton --}}
            <button type="submit" class="generate-btn" id="btn-generer">
                <span id="btn-text"><i class="fas fa-wand-magic-sparkles me-2"></i> Générer ma page de vente avec l'IA</span>
                <span id="btn-loading" style="display:none;">
                    <span class="spinner-border spinner-border-sm me-2"></span> Génération en cours (15-30 sec)...
                </span>
            </button>
            <p class="text-center text-muted mt-2 mb-0" style="font-size:0.78rem;">
                ⚡ Claude AI analyse votre produit · Résultat en 15-30 secondes
            </p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sync couleur picker <-> champ texte
document.getElementById('couleur_theme')?.addEventListener('input', function() {
    document.getElementById('couleur_hex').value = this.value;
});

// Loader au submit
document.getElementById('form-ia')?.addEventListener('submit', function() {
    document.getElementById('btn-text').style.display = 'none';
    document.getElementById('btn-loading').style.display = 'inline';
    document.getElementById('btn-generer').disabled = true;
});
</script>
@endpush
