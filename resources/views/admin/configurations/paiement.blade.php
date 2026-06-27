@extends('layouts.admin')
@section('title', 'Moyens de paiement')

@push('styles')
<style>
/* ── Paiement config — style Chariow ── */
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
.form-control, .form-select {
    border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important;
    padding: 0.65rem 0.9rem !important; font-size: 0.875rem !important;
    color: #0f172a; transition: border-color .15s;
}
.form-control:focus, .form-select:focus {
    border-color: #0f172a !important;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.06) !important;
    outline: none;
}
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

/* Gateway options */
.gateway-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.75rem; margin-bottom: 1.25rem;
}
.gateway-opt { display: none; }
.gateway-label {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 1rem 0.75rem; border: 1.5px solid #e5e7eb; border-radius: 12px;
    cursor: pointer; transition: all .15s; background: #fafafa;
    font-size: 0.82rem; font-weight: 600; color: #374151; text-align: center;
}
.gateway-label:hover { border-color: #0f172a; background: #fff; }
.gateway-opt:checked + .gateway-label {
    border-color: #0f172a; background: #fff;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.06);
    color: #0f172a;
}
.gateway-icon { font-size: 1.75rem; }

/* Devise pills */
.devise-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 0.6rem;
}
.devise-opt { display: none; }
.devise-label {
    display: flex; align-items: center; gap: 8px;
    padding: 0.65rem 0.9rem; border: 1.5px solid #e5e7eb; border-radius: 10px;
    cursor: pointer; font-size: 0.83rem; font-weight: 600;
    transition: all .15s; background: #fafafa; color: #374151;
}
.devise-label:hover { border-color: #0f172a; }
.devise-opt:checked + .devise-label {
    border-color: #0f172a; background: #0f172a; color: #fff;
}
.devise-flag { font-size: 1.1rem; }

/* Webhook block */
.webhook-block {
    background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 1rem 1.1rem;
}
.webhook-block .wh-label {
    font-size: 0.75rem; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;
}
.webhook-url {
    display: flex; align-items: center; gap: 8px;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
    padding: 0.6rem 0.9rem; font-size: 0.82rem; font-family: monospace;
    color: #374151; word-break: break-all;
}
.btn-copy {
    flex-shrink: 0; height: 30px; padding: 0 10px;
    font-size: 0.75rem; font-weight: 600; background: #0f172a; color: #fff;
    border: none; border-radius: 7px; cursor: pointer; transition: background .15s;
    white-space: nowrap;
}
.btn-copy:hover { background: #1e293b; }

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
    <h1>Moyens de paiement</h1>
    <p>Configurez votre passerelle de paiement et devise principale</p>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.configurations.paiement') }}" method="POST">
@csrf

{{-- Passerelle --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-credit-card"></i></div>
        <div>
            <h5>Passerelle de paiement</h5>
            <p>Choisissez le prestataire de paiement pour votre boutique</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="gateway-grid">
            @php
            $gateways = [
                'moneroo' => ['icon' => '💳', 'label' => 'Moneroo'],
                'stripe'  => ['icon' => '🔵', 'label' => 'Stripe'],
                'paypal'  => ['icon' => '🅿', 'label' => 'PayPal'],
            ];
            $current = old('passerelle_paiement', $configuration->passerelle_paiement ?? 'moneroo');
            @endphp
            @foreach($gateways as $key => $gw)
            <div>
                <input type="radio" class="gateway-opt" name="passerelle_paiement"
                       id="gw_{{ $key }}" value="{{ $key }}" {{ $current === $key ? 'checked' : '' }}>
                <label class="gateway-label" for="gw_{{ $key }}">
                    <span class="gateway-icon">{{ $gw['icon'] }}</span>
                    {{ $gw['label'] }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- API Keys --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-key"></i></div>
        <div>
            <h5>Clés API</h5>
            <p>Identifiants fournis par votre passerelle de paiement</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Clé publique API</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="text" class="form-control @error('cle_api_paiement') is-invalid @enderror"
                           name="cle_api_paiement"
                           value="{{ old('cle_api_paiement', $configuration->cle_api_paiement ?? '') }}"
                           placeholder="pk_live_...">
                </div>
                @error('cle_api_paiement')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Clé secrète API</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control @error('secret_api_paiement') is-invalid @enderror"
                           name="secret_api_paiement"
                           placeholder="Laissez vide pour conserver l'actuelle">
                </div>
                <div class="field-hint">Laissez vide pour ne pas modifier la clé existante</div>
                @error('secret_api_paiement')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Devise --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-coins"></i></div>
        <div>
            <h5>Devise principale</h5>
            <p>Devise utilisée pour les transactions</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="devise-grid">
            @php
            $devises = [
                'XOF' => ['🇨🇮', 'FCFA'],
                'EUR' => ['🇪🇺', 'EUR'],
                'USD' => ['🇺🇸', 'USD'],
                'GHS' => ['🇬🇭', 'GHS'],
                'NGN' => ['🇳🇬', 'NGN'],
                'MAD' => ['🇲🇦', 'MAD'],
            ];
            $deviseActuelle = old('devise', $configuration->devise ?? 'XOF');
            @endphp
            @foreach($devises as $code => $d)
            <div>
                <input type="radio" class="devise-opt" name="devise" value="{{ $code }}"
                       id="devise_{{ $code }}" {{ $deviseActuelle === $code ? 'checked' : '' }}>
                <label class="devise-label" for="devise_{{ $code }}">
                    <span class="devise-flag">{{ $d[0] }}</span>
                    <strong>{{ $d[1] }}</strong>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Webhook --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-webhook" style="font-size:0.8rem;"></i></div>
        <div>
            <h5>URL Webhook</h5>
            <p>À configurer dans votre compte de passerelle</p>
        </div>
    </div>
    <div class="config-card-body">
        <div class="webhook-block">
            <div class="wh-label">URL à copier dans votre tableau de bord</div>
            <div class="webhook-url">
                <span style="flex:1;">{{ url('/paiement/webhook') }}</span>
                <button type="button" class="btn-copy" onclick="copyWebhook(this)">
                    <i class="fas fa-copy me-1"></i> Copier
                </button>
            </div>
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
function copyWebhook(btn) {
    const url = btn.previousElementSibling.textContent.trim();
    navigator.clipboard.writeText(url).then(() => {
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Copié !';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy me-1"></i> Copier'; }, 2000);
    });
}
</script>
@endpush

@endsection
