@extends('layouts.admin')
@section('title', 'Configuration email')

@push('styles')
<style>
/* ── Email config — style Chariow ── */
.config-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.8rem; font-weight: 600; color: #64748b;
    text-decoration: none; margin-bottom: 1.25rem; transition: color .15s;
}
.config-back:hover { color: #0f172a; }
.config-header { margin-bottom: 2rem; }
.config-header h1 {
    font-size: 1.45rem; font-weight: 800; color: #0f172a;
    letter-spacing: -0.02em; margin: 0 0 0.2rem;
}
.config-header p { color: #64748b; font-size: 0.875rem; margin: 0; }

/* Cards */
.config-card {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
}
.config-card-header {
    padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 12px; background: #fafafa;
}
.config-card-header .icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.88rem; color: #0f172a; flex-shrink: 0;
}
.config-card-header h5 { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin: 0; }
.config-card-header p  { font-size: 0.74rem; color: #94a3b8; margin: 0; }
.config-card-body { padding: 1.5rem 1.25rem; }

/* Form */
.form-label { font-size: 0.81rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
.form-control {
    border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important;
    padding: 0.65rem 0.9rem !important; font-size: 0.875rem !important;
    color: #0f172a; transition: border-color .15s;
}
.form-control:focus {
    border-color: #0f172a !important;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.06) !important;
    outline: none;
}
textarea.form-control { resize: vertical; min-height: 140px; font-family: monospace; }
.input-group .input-group-text {
    border: 1.5px solid #e2e8f0; border-right: none;
    border-radius: 10px 0 0 10px; background: #f8fafc;
    color: #64748b; font-size: 0.85rem;
}
.input-group .form-control {
    border-radius: 0 10px 10px 0 !important;
    border-left: none !important;
}
.field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 0.3rem; }

/* Variables chips */
.vars-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 0.6rem; }
.var-chip {
    display: inline-flex; align-items: center;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    border-radius: 6px; padding: 2px 8px; font-size: 0.72rem;
    font-family: monospace; color: #475569; cursor: pointer;
    transition: all .12s;
}
.var-chip:hover { background: #0f172a; color: #fff; border-color: #0f172a; }

/* Delay input */
.delay-wrap { display: flex; align-items: center; gap: 12px; }
.delay-input {
    width: 80px; text-align: center;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    padding: 0.65rem; font-size: 1rem; font-weight: 700; color: #0f172a;
    outline: none; transition: border-color .15s;
}
.delay-input:focus { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.06); }
.delay-unit { font-size: 0.875rem; color: #64748b; }

/* Buttons */
.btn-save {
    height: 40px; padding: 0 20px; font-size: 0.85rem; font-weight: 700;
    background: #0f172a; color: #fff; border: none; border-radius: 10px;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    transition: background .15s;
}
.btn-save:hover { background: #1e293b; }
.btn-cancel {
    height: 40px; padding: 0 16px; font-size: 0.85rem; font-weight: 600;
    background: #f8fafc; color: #64748b; border: 1px solid #e5e7eb;
    border-radius: 10px; text-decoration: none;
    display: inline-flex; align-items: center; gap: 7px; transition: all .15s;
}
.btn-cancel:hover { background: #f1f5f9; color: #0f172a; }

.alert-success-custom {
    background: #f0fdf4; border-left: 3px solid #22c55e;
    padding: 0.875rem 1.1rem; border-radius: 10px;
    margin-bottom: 1.25rem; color: #166534; font-size: 0.85rem;
    display: flex; align-items: center; gap: 8px;
}
</style>
@endpush

@section('content')

<a href="{{ route('admin.configurations.index') }}" class="config-back">
    <i class="fas fa-arrow-left"></i> Paramètres
</a>

<div class="config-header">
    <h1>Emails</h1>
    <p>Configurez l'expéditeur, les templates de confirmation et de relance</p>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.configurations.email') }}" method="POST">
@csrf

{{-- Expéditeur --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-paper-plane"></i></div>
        <div>
            <h5>Email expéditeur</h5>
            <p>Adresse depuis laquelle les emails sont envoyés à vos clients</p>
        </div>
    </div>
    <div class="config-card-body">
        <label class="form-label">Adresse email <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-at"></i></span>
            <input type="email" class="form-control @error('email_expediteur') is-invalid @enderror"
                   name="email_expediteur"
                   value="{{ old('email_expediteur', $configuration->email_expediteur ?? '') }}"
                   placeholder="no-reply@maboutique.com" required>
        </div>
        @error('email_expediteur')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
        <div class="field-hint">Les emails de confirmation et de relance seront envoyés depuis cette adresse</div>
    </div>
</div>

{{-- Relance --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-clock"></i></div>
        <div>
            <h5>Délai de relance panier abandonné</h5>
            <p>Nombre de jours avant d'envoyer une relance automatique</p>
        </div>
    </div>
    <div class="config-card-body">
        <label class="form-label">Délai <span class="text-danger">*</span></label>
        <div class="delay-wrap">
            <input type="number" class="delay-input @error('relance_delai_jours') is-invalid @enderror"
                   name="relance_delai_jours"
                   value="{{ old('relance_delai_jours', $configuration->relance_delai_jours ?? 3) }}"
                   min="1" max="30" required>
            <span class="delay-unit">jour(s) après l'abandon du panier</span>
        </div>
        @error('relance_delai_jours')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
        <div class="field-hint">Entre 1 et 30 jours. Recommandé : 3 jours.</div>
    </div>
</div>

{{-- Template confirmation --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <div>
            <h5>Email de confirmation d'achat</h5>
            <p>Envoyé automatiquement après chaque paiement réussi</p>
        </div>
    </div>
    <div class="config-card-body">
        <label class="form-label">Template</label>
        <textarea class="form-control @error('email_template_achat') is-invalid @enderror"
                  name="email_template_achat" rows="8"
                  placeholder="Bonjour {nom_client},&#10;&#10;Votre commande {reference} a bien été confirmée...">{{ old('email_template_achat', $configuration->email_template_achat ?? '') }}</textarea>
        @error('email_template_achat')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
        <div class="field-hint">Variables disponibles (cliquer pour insérer) :</div>
        @php
            $achatVars = array_map(
                fn($k) => '{' . '{' . $k . '}}',
                ['nom_client','reference','montant','devise','boutique_nom','lien_achats']
            );
        @endphp
        <div class="vars-wrap">
            @foreach($achatVars as $v)
            <span class="var-chip" onclick="insertVar('email_template_achat', '{{ $v }}')">{{ $v }}</span>
            @endforeach
        </div>
    </div>
</div>

{{-- Template relance --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-redo"></i></div>
        <div>
            <h5>Email de relance panier abandonné</h5>
            <p>Envoyé automatiquement aux clients ayant abandonné leur panier</p>
        </div>
    </div>
    <div class="config-card-body">
        <label class="form-label">Template</label>
        <textarea class="form-control @error('email_template_relance') is-invalid @enderror"
                  name="email_template_relance" rows="8"
                  placeholder="Bonjour {nom_client},&#10;&#10;Vous avez laissé des articles dans votre panier...">{{ old('email_template_relance', $configuration->email_template_relance ?? '') }}</textarea>
        @error('email_template_relance')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
        <div class="field-hint">Variables disponibles (cliquer pour insérer) :</div>
        @php
            $relanceVars = array_map(
                fn($k) => '{' . '{' . $k . '}}',
                ['nom_client','boutique_nom','lien_panier']
            );
        @endphp
        <div class="vars-wrap">
            @foreach($relanceVars as $v)
            <span class="var-chip" onclick="insertVar('email_template_relance', '{{ $v }}')">{{ $v }}</span>
            @endforeach
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="d-flex justify-content-end gap-2 mb-5">
    <a href="{{ route('admin.configurations.index') }}" class="btn-cancel">Annuler</a>
    <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Enregistrer
    </button>
</div>

</form>

@push('scripts')
<script>
function insertVar(fieldName, variable) {
    const ta = document.querySelector(`textarea[name="${fieldName}"]`);
    if (!ta) return;
    const start = ta.selectionStart;
    const end = ta.selectionEnd;
    ta.value = ta.value.substring(0, start) + variable + ta.value.substring(end);
    ta.selectionStart = ta.selectionEnd = start + variable.length;
    ta.focus();
}
</script>
@endpush

@endsection
