@extends('layouts.admin')

@section('title', 'Modifier le code promo')

@section('content')

<style>
    .promo-header { margin-bottom: 2rem; }
    .promo-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0; }
    .promo-header p  { color: #64748b; margin: 0.25rem 0 0; font-size: 0.9rem; }
    .promo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    @media(max-width:768px){ .promo-grid { grid-template-columns: 1fr; } }
    .promo-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.75rem; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .promo-card-full { grid-column: 1 / -1; }
    .card-section-title { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #0f172a; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; }
    .card-section-title i { width: 30px; height: 30px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; }
    .field-group { margin-bottom: 1.1rem; }
    .field-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 0.45rem; }
    .field-group label span.required { color: #ef4444; margin-left: 2px; }
    .input-wrap { position: relative; }
    .input-wrap .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .input-wrap .input-suffix { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.8rem; font-weight: 600; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; pointer-events: none; }
    .promo-input { width: 100%; padding: 0.7rem 1rem 0.7rem 2.4rem; border: 1.5px solid #e2e8f0; border-radius: 11px; font-size: 0.9rem; font-family: inherit; outline: none; transition: all 0.2s; background: #fafafa; color: #0f172a; }
    .promo-input:focus { border-color: #0f172a; background: white; box-shadow: 0 0 0 3px rgba(15,23,42,0.06); }
    .promo-input.is-invalid { border-color: #ef4444; }
    .promo-input-with-suffix { padding-right: 70px; }
    .invalid-msg { color: #ef4444; font-size: 0.78rem; margin-top: 0.35rem; }
    .help-text { color: #94a3b8; font-size: 0.78rem; margin-top: 0.35rem; }
    .type-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .type-option { display: none; }
    .type-label { display: flex; align-items: center; gap: 10px; padding: 0.9rem 1rem; border-radius: 12px; border: 1.5px solid #e2e8f0; cursor: pointer; transition: all 0.2s; background: #fafafa; }
    .type-label:hover { border-color: #93c5fd; background: #eff6ff; }
    .type-option:checked + .type-label { border-color: #0f172a; background: #eff6ff; box-shadow: 0 0 0 3px rgba(15,23,42,0.06); }
    .type-icon { width: 36px; height: 36px; border-radius: 10px; background: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .type-option:checked + .type-label .type-icon { background: #0f172a; color: white; }
    .type-text strong { display: block; font-size: 0.88rem; font-weight: 700; color: #0f172a; }
    .type-text span { font-size: 0.75rem; color: #64748b; }
    .produits-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.6rem; }
    .produit-check { display: none; }
    .produit-label { display: flex; align-items: center; gap: 8px; padding: 0.6rem 0.85rem; border-radius: 10px; border: 1.5px solid #e2e8f0; cursor: pointer; font-size: 0.82rem; font-weight: 500; color: #374151; transition: all 0.2s; background: #fafafa; }
    .produit-label:hover { border-color: #93c5fd; background: #eff6ff; color: #1e293b; }
    .produit-check:checked + .produit-label { border-color: #0f172a; background: #eff6ff; color: #1e293b; font-weight: 600; }
    .produit-check:checked + .produit-label::before { content: '?'; color: #0f172a; font-weight: 800; margin-right: 2px; }
    .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: #f8fafc; border-radius: 12px; border: 1.5px solid #e2e8f0; }
    .toggle-info strong { display: block; font-size: 0.9rem; font-weight: 700; color: #0f172a; }
    .toggle-info span { font-size: 0.8rem; color: #64748b; }
    .toggle-switch { position: relative; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; border-radius: 24px; background: #cbd5e1; cursor: pointer; transition: 0.3s; }
    .toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%; background: white; left: 3px; top: 3px; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
    .toggle-switch input:checked + .toggle-slider { background: #0f172a; }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }
    .stat-pill { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 20px; color: #166534; font-size: 0.85rem; font-weight: 600; }
    .btn-promo-submit { display: inline-flex; align-items: center; gap: 8px; padding: 0.85rem 2rem; background: linear-gradient(135deg, #0f172a, #1e293b); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; font-family: inherit; box-shadow: 0 4px 14px rgba(15,23,42,0.15); }
    .btn-promo-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }
    .btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 0.65rem 1.25rem; border-radius: 10px; border: 1.5px solid #e2e8f0; background: white; color: #64748b; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-back:hover { border-color: #94a3b8; color: #0f172a; }
    .promo-preview { background: linear-gradient(135deg, #0f172a, #1e3a5f); border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
    .preview-label { color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .preview-code { color: white; font-size: 1.5rem; font-weight: 900; letter-spacing: 0.1em; font-family: monospace; }
    .preview-badge { padding: 0.4rem 0.9rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
</style>

<div class="d-flex justify-content-between align-items-center promo-header">
    <div>
        <h1>?? Modifier le code promo</h1>
        <p>Mise à jour de <strong>{{ $codePromo->code }}</strong></p>
    </div>
    <a href="{{ route('admin.codes-promo.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

@if($errors->any())
<div style="background:#fef2f2;border-left:4px solid #ef4444;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;color:#991b1b;font-size:0.875rem;">
    <i class="fas fa-exclamation-circle"></i> <strong>Erreur :</strong> {{ $errors->first() }}
</div>
@endif

<form action="{{ route('admin.codes-promo.update', $codePromo) }}" method="POST">
@csrf
@method('PUT')

{{-- Aperçu --}}
<div class="promo-preview">
    <div>
        <div class="preview-label">Code actuel</div>
        <div class="preview-code" id="preview-code">{{ $codePromo->code }}</div>
    </div>
    <div class="preview-badge" id="preview-badge"
         style="background:{{ $codePromo->est_actif ? '#22c55e' : '#94a3b8' }};color:white;">
        {{ $codePromo->est_actif ? 'Actif' : 'Inactif' }}
    </div>
</div>

<div class="promo-grid">

    <div class="promo-card">
        <div class="card-section-title"><i class="fas fa-tag"></i> Informations du code</div>

        <div class="field-group">
            <label for="code">Code promo <span class="required">*</span></label>
            <div class="input-wrap">
                <i class="input-icon fas fa-hashtag"></i>
                <input type="text" class="promo-input @error('code') is-invalid @enderror"
                       id="code" name="code" value="{{ old('code', $codePromo->code) }}" required
                       oninput="document.getElementById('preview-code').textContent = this.value.toUpperCase() || ''"
                       style="text-transform:uppercase;letter-spacing:0.05em;">
            </div>
            @error('code')<div class="invalid-msg">{{ $message }}</div>@enderror
        </div>

        <div class="field-group">
            <label>Type de réduction <span class="required">*</span></label>
            <div class="type-selector">
                <div>
                    <input type="radio" class="type-option" name="type_reduction" value="fixe"
                           id="type_fixe" {{ old('type_reduction', $codePromo->type_reduction) == 'fixe' ? 'checked' : '' }}>
                    <label class="type-label" for="type_fixe">
                        <div class="type-icon">??</div>
                        <div class="type-text"><strong>Montant fixe</strong><span>En FCFA</span></div>
                    </label>
                </div>
                <div>
                    <input type="radio" class="type-option" name="type_reduction" value="pourcentage"
                           id="type_pct" {{ old('type_reduction', $codePromo->type_reduction) == 'pourcentage' ? 'checked' : '' }}>
                    <label class="type-label" for="type_pct">
                        <div class="type-icon">??</div>
                        <div class="type-text"><strong>Pourcentage</strong><span>En %</span></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label for="valeur_reduction">Valeur de la réduction <span class="required">*</span></label>
            <div class="input-wrap">
                <i class="input-icon fas fa-percent"></i>
                <input type="number" step="1" min="0"
                       class="promo-input promo-input-with-suffix @error('valeur_reduction') is-invalid @enderror"
                       id="valeur_reduction" name="valeur_reduction"
                       value="{{ old('valeur_reduction', $codePromo->valeur_reduction) }}" required>
                <span class="input-suffix" id="suffix-label">
                    {{ old('type_reduction', $codePromo->type_reduction) == 'pourcentage' ? '%' : 'FCFA' }}
                </span>
            </div>
            @error('valeur_reduction')<div class="invalid-msg">{{ $message }}</div>@enderror
        </div>

        {{-- Stats --}}
        <div class="field-group" style="margin-bottom:0;">
            <label>Statistiques d'utilisation</label>
            <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.25rem;">
                <span class="stat-pill">
                    <i class="fas fa-chart-bar"></i>
                    {{ $codePromo->utilisation_actuelle }} utilisation{{ $codePromo->utilisation_actuelle > 1 ? 's' : '' }}
                </span>
                @if($codePromo->utilisation_max)
                <span style="color:#94a3b8;font-size:0.8rem;">sur {{ $codePromo->utilisation_max }} max</span>
                @else
                <span style="color:#94a3b8;font-size:0.8rem;">illimité</span>
                @endif
            </div>
        </div>
    </div>

    <div class="promo-card">
        <div class="card-section-title"><i class="fas fa-calendar-alt"></i> Validité & Limites</div>

        <div class="field-group">
            <label for="date_debut">Date de début</label>
            <div class="input-wrap">
                <i class="input-icon fas fa-calendar"></i>
                <input type="date" class="promo-input @error('date_debut') is-invalid @enderror"
                       id="date_debut" name="date_debut"
                       value="{{ old('date_debut', $codePromo->date_debut?->format('Y-m-d')) }}">
            </div>
            @error('date_debut')<div class="invalid-msg">{{ $message }}</div>@enderror
            <div class="help-text">Laissez vide pour une activation immédiate</div>
        </div>

        <div class="field-group">
            <label for="date_fin">Date de fin</label>
            <div class="input-wrap">
                <i class="input-icon fas fa-calendar-times"></i>
                <input type="date" class="promo-input @error('date_fin') is-invalid @enderror"
                       id="date_fin" name="date_fin"
                       value="{{ old('date_fin', $codePromo->date_fin?->format('Y-m-d')) }}">
            </div>
            @error('date_fin')<div class="invalid-msg">{{ $message }}</div>@enderror
            <div class="help-text">Laissez vide pour une durée illimitée</div>
        </div>

        <div class="field-group">
            <label for="utilisation_max">Nombre maximum d'utilisations</label>
            <div class="input-wrap">
                <i class="input-icon fas fa-users"></i>
                <input type="number" min="1"
                       class="promo-input @error('utilisation_max') is-invalid @enderror"
                       id="utilisation_max" name="utilisation_max"
                       value="{{ old('utilisation_max', $codePromo->utilisation_max) }}">
            </div>
            @error('utilisation_max')<div class="invalid-msg">{{ $message }}</div>@enderror
            <div class="help-text">Laissez vide pour un usage illimité</div>
        </div>

        <div class="field-group" style="margin-bottom:0;">
            <label>Statut du code</label>
            <div class="toggle-wrap">
                <div class="toggle-info">
                    <strong>Code actif</strong>
                    <span>Le code peut être utilisé par vos clients</span>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="est_actif" name="est_actif" value="1"
                           {{ old('est_actif', $codePromo->est_actif) ? 'checked' : '' }}
                           onchange="document.getElementById('preview-badge').textContent = this.checked ? 'Actif' : 'Inactif'; document.getElementById('preview-badge').style.background = this.checked ? '#22c55e' : '#94a3b8';">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>

    @if($produits->count() > 0)
    <div class="promo-card promo-card-full">
        <div class="card-section-title"><i class="fas fa-boxes"></i> Produits concernés</div>
        <div class="produits-grid">
            @foreach($produits as $produit)
            <div>
                <input type="checkbox" class="produit-check"
                       name="produits[]" value="{{ $produit->id }}"
                       id="produit_{{ $produit->id }}"
                       {{ in_array($produit->id, old('produits', $codePromo->produits->pluck('id')->toArray())) ? 'checked' : '' }}>
                <label class="produit-label" for="produit_{{ $produit->id }}">
                    <i class="fas fa-box" style="color:#94a3b8;font-size:0.75rem;"></i>
                    {{ $produit->nom }}
                </label>
            </div>
            @endforeach
        </div>
        <div class="help-text" style="margin-top:0.75rem;">
            <i class="fas fa-info-circle"></i> Ne sélectionnez rien pour appliquer le code à <strong>tous les produits</strong>
        </div>
    </div>
    @endif

</div>

<div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <button type="submit" class="btn-promo-submit">
        <i class="fas fa-save"></i> Enregistrer les modifications
    </button>
    <a href="{{ route('admin.codes-promo.index') }}" class="btn-back">Annuler</a>
</div>

</form>

<script>
document.querySelectorAll('input[name="type_reduction"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('suffix-label').textContent = this.value === 'pourcentage' ? '%' : 'FCFA';
    });
});
</script>

@endsection

