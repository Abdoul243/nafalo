@extends('layouts.admin')
@section('title', 'Apparence & Régional')

@push('styles')
<style>
/* ── Apparence config — style Chariow ── */
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
.field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 0.3rem; }

/* Theme grid */
.theme-grid {
    display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem;
}
@media(max-width:600px) { .theme-grid { grid-template-columns: repeat(3,1fr); } }
.theme-opt { display: none; }
.theme-label {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 1rem 0.5rem; border-radius: 12px; border: 1.5px solid #e5e7eb;
    cursor: pointer; transition: all .15s; background: #fafafa;
}
.theme-label:hover { border-color: #94a3b8; }
.theme-opt:checked + .theme-label {
    border-color: #0f172a; background: #fff;
    box-shadow: 0 0 0 3px rgba(15,23,42,0.06);
}
.theme-preview { width: 100%; height: 38px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.06); }
.theme-name { font-size: 0.74rem; font-weight: 600; color: #374151; }

/* Couleur accentuation */
.color-row {
    display: flex; align-items: center; gap: 1rem; margin-top: 1.25rem;
    padding-top: 1.25rem; border-top: 1px solid #f1f5f9;
}
.color-swatch {
    width: 48px; height: 44px; border-radius: 10px;
    border: 1.5px solid #e5e7eb; cursor: pointer; padding: 2px;
}
.color-swatch-label { font-size: 0.83rem; color: #64748b; }
.color-swatch-label strong { display: block; font-size: 0.85rem; color: #0f172a; }

/* Devise grid */
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

/* Langue grid */
.lang-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.6rem;
}
@media(max-width:480px) { .lang-grid { grid-template-columns: repeat(2,1fr); } }
.lang-opt { display: none; }
.lang-label {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 0.875rem; border: 1.5px solid #e5e7eb; border-radius: 10px;
    cursor: pointer; font-size: 0.8rem; font-weight: 600;
    transition: all .15s; text-align: center; color: #374151; background: #fafafa;
}
.lang-label:hover { border-color: #0f172a; }
.lang-opt:checked + .lang-label {
    border-color: #0f172a; background: #0f172a; color: #fff;
}
.lang-flag { font-size: 1.4rem; }

/* WhatsApp */
.inp { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: all .15s; }
.inp:focus { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.06); }
.inp-icon { position: relative; }
.inp-icon .ic { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
.inp-icon .inp { padding-left: 2.4rem; }

.wa-preview {
    display: flex; align-items: center; gap: 10px;
    padding: 0.75rem 1rem; background: #f0fdf4;
    border-radius: 10px; border: 1px solid #bbf7d0;
    margin-top: 0.875rem; font-size: 0.85rem; color: #166534;
}
.wa-btn-preview {
    display: inline-flex; align-items: center; gap: 6px;
    background: #25d366; color: white; padding: 0.4rem 0.9rem;
    border-radius: 20px; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;
}

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
    <h1>Apparence & Régional</h1>
    <p>Thème, couleurs, devise, langue et bouton WhatsApp de votre boutique</p>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.configurations.apparence') }}" method="POST">
@csrf

{{-- Thème --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-palette"></i></div>
        <div>
            <h5>Thème de la boutique</h5>
            <p>Apparence visuelle présentée à vos clients</p>
        </div>
    </div>
    <div class="config-card-body">
        @php
        $themes = [
            'light'  => ['nom'=>'Clair',   'bg'=>'linear-gradient(135deg,#ffffff,#f8fafc)'],
            'dark'   => ['nom'=>'Sombre',  'bg'=>'linear-gradient(135deg,#0f172a,#1e293b)'],
            'blue'   => ['nom'=>'Bleu',    'bg'=>'linear-gradient(135deg,#1e40af,#3b82f6)'],
            'green'  => ['nom'=>'Vert',    'bg'=>'linear-gradient(135deg,#14532d,#22c55e)'],
            'orange' => ['nom'=>'Orange',  'bg'=>'linear-gradient(135deg,#92400e,#f97316)'],
        ];
        $themeActuel = old('theme', $configuration->theme ?? 'light');
        @endphp
        <div class="theme-grid">
            @foreach($themes as $key => $t)
            <div>
                <input type="radio" class="theme-opt" name="theme" value="{{ $key }}"
                       id="theme_{{ $key }}" {{ $themeActuel === $key ? 'checked' : '' }}>
                <label class="theme-label" for="theme_{{ $key }}">
                    <div class="theme-preview" style="background:{{ $t['bg'] }};"></div>
                    <span class="theme-name">{{ $t['nom'] }}</span>
                </label>
            </div>
            @endforeach
        </div>

        <div class="color-row">
            <input type="color" name="couleur"
                   value="{{ old('couleur', $configuration->couleur ?? '#0f172a') }}"
                   class="color-swatch">
            <div class="color-swatch-label">
                <strong>Couleur principale</strong>
                Couleur des boutons et liens de votre boutique
            </div>
        </div>
    </div>
</div>

{{-- Devise --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-coins"></i></div>
        <div>
            <h5>Devise d'affichage</h5>
            <p>Devise utilisée dans votre boutique publique</p>
        </div>
    </div>
    <div class="config-card-body">
        @php
        $devises = [
            'XOF' => ['🇨🇮', 'FCFA', 'Franc CFA'],
            'EUR' => ['🇪🇺', 'EUR',  'Euro'],
            'USD' => ['🇺🇸', 'USD',  'Dollar US'],
            'GHS' => ['🇬🇭', 'GHS',  'Cedi Ghana'],
            'NGN' => ['🇳🇬', 'NGN',  'Naira Nigeria'],
            'MAD' => ['🇲🇦', 'MAD',  'Dirham Maroc'],
        ];
        $deviseActuelle = old('devise', $configuration->devise ?? 'XOF');
        @endphp
        <div class="devise-grid">
            @foreach($devises as $code => $d)
            <div>
                <input type="radio" class="devise-opt" name="devise" value="{{ $code }}"
                       id="devise_{{ $code }}" {{ $deviseActuelle === $code ? 'checked' : '' }}>
                <label class="devise-label" for="devise_{{ $code }}">
                    <span class="devise-flag">{{ $d[0] }}</span>
                    <div>
                        <strong style="display:block;font-size:0.85rem;">{{ $d[1] }}</strong>
                        <span style="font-size:0.72rem;opacity:.7;">{{ $d[2] }}</span>
                    </div>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Langue --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon"><i class="fas fa-globe"></i></div>
        <div>
            <h5>Langue de la boutique</h5>
            <p>Langue des textes automatiques (emails, boutons)</p>
        </div>
    </div>
    <div class="config-card-body">
        @php
        $langues = [
            'fr' => ['🇫🇷', 'Français'],
            'en' => ['🇬🇧', 'English'],
            'ar' => ['🇸🇦', 'العربية'],
            'pt' => ['🇵🇹', 'Português'],
        ];
        $langueActuelle = old('langue', $configuration->langue ?? 'fr');
        @endphp
        <div class="lang-grid">
            @foreach($langues as $code => $l)
            <div>
                <input type="radio" class="lang-opt" name="langue" value="{{ $code }}"
                       id="lang_{{ $code }}" {{ $langueActuelle === $code ? 'checked' : '' }}>
                <label class="lang-label" for="lang_{{ $code }}">
                    <span class="lang-flag">{{ $l[0] }}</span>
                    <span>{{ $l[1] }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- WhatsApp --}}
<div class="config-card">
    <div class="config-card-header">
        <div class="icon" style="color:#25d366;"><i class="fab fa-whatsapp"></i></div>
        <div>
            <h5>Bouton WhatsApp Business</h5>
            <p>Un bouton flottant vert apparaîtra sur votre boutique</p>
        </div>
    </div>
    <div class="config-card-body" style="max-width: 480px;">
        @php $wa = $boutique->reseaux_sociaux['whatsapp'] ?? ''; @endphp
        <label style="font-size:0.81rem;font-weight:600;color:#374151;margin-bottom:0.4rem;display:block;">
            Numéro WhatsApp (avec indicatif pays)
        </label>
        <div class="inp-icon">
            <i class="ic fab fa-whatsapp" style="color:#25d366;"></i>
            <input type="text" class="inp" name="whatsapp"
                   value="{{ old('whatsapp', $wa) }}"
                   placeholder="+225 07 00 00 00 00">
        </div>
        <div class="field-hint">Ex : +22507000000</div>

        @if($wa)
        <div class="wa-preview">
            <i class="fab fa-whatsapp" style="font-size:1.4rem;color:#25d366;"></i>
            <div>
                <strong>Bouton actif</strong><br>
                <span style="font-size:0.82rem;">{{ $wa }}</span>
            </div>
            <div class="wa-btn-preview"><i class="fab fa-whatsapp"></i> WhatsApp</div>
        </div>
        @else
        <div style="background:#f8fafc;border-radius:10px;padding:1rem;color:#94a3b8;font-size:0.84rem;text-align:center;margin-top:0.75rem;border:1px solid #f1f5f9;">
            <i class="fab fa-whatsapp" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
            Entrez votre numéro pour activer le bouton WhatsApp
        </div>
        @endif
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
@endsection
