@extends('layouts.admin')
@section('title', 'Apparence & Paramètres')

@section('content')
<style>
    .app-header { margin-bottom:2rem; }
    .app-header h1 { font-size:1.75rem; font-weight:800; color:#0f172a; margin:0; }
    .app-header p  { color:#64748b; margin:0.25rem 0 0; font-size:0.9rem; }
    .app-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem; }
    @media(max-width:768px){ .app-grid { grid-template-columns:1fr; } }
    .app-card { background:white; border-radius:16px; border:1px solid #e2e8f0; padding:1.75rem; box-shadow:0 1px 6px rgba(0,0,0,0.04); }
    .app-card-full { grid-column:1/-1; }
    .card-sec-title { display:flex; align-items:center; gap:10px; font-size:0.82rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#2563eb; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9; }
    .field-g { margin-bottom:1.1rem; }
    .field-g label { display:block; font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.45rem; }
    .inp { width:100%; padding:0.7rem 1rem; border:1.5px solid #e2e8f0; border-radius:11px; font-size:0.9rem; font-family:inherit; outline:none; transition:all 0.2s; background:#fafafa; }
    .inp:focus { border-color:#2563eb; background:white; box-shadow:0 0 0 3px rgba(37,99,235,0.08); }
    .inp-icon { position:relative; }
    .inp-icon .ic { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.85rem; }
    .inp-icon .inp { padding-left:2.4rem; }
    .help { color:#94a3b8; font-size:0.78rem; margin-top:0.35rem; }

    /* Thèmes */
    .theme-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:0.75rem; }
    .theme-opt { display:none; }
    .theme-label { display:flex; flex-direction:column; align-items:center; gap:8px; padding:1rem 0.5rem; border-radius:12px; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fafafa; }
    .theme-label:hover { border-color:#93c5fd; }
    .theme-opt:checked + .theme-label { border-color:#2563eb; background:#eff6ff; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    .theme-preview { width:100%; height:40px; border-radius:8px; }
    .theme-name { font-size:0.75rem; font-weight:600; color:#374151; }

    /* Devises */
    .devise-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.6rem; }
    .devise-opt { display:none; }
    .devise-label { display:flex; align-items:center; gap:8px; padding:0.7rem 1rem; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer; font-size:0.85rem; font-weight:600; transition:all 0.2s; background:#fafafa; }
    .devise-label:hover { border-color:#93c5fd; }
    .devise-opt:checked + .devise-label { border-color:#2563eb; background:#eff6ff; color:#1d4ed8; }
    .devise-flag { font-size:1.2rem; }

    /* Langues */
    .lang-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:0.6rem; }
    .lang-opt { display:none; }
    .lang-label { display:flex; flex-direction:column; align-items:center; gap:6px; padding:0.85rem; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer; font-size:0.8rem; font-weight:600; transition:all 0.2s; text-align:center; }
    .lang-label:hover { border-color:#93c5fd; }
    .lang-opt:checked + .lang-label { border-color:#2563eb; background:#eff6ff; color:#1d4ed8; }
    .lang-flag { font-size:1.4rem; }

    /* Preview WhatsApp */
    .wa-preview { display:flex; align-items:center; gap:10px; padding:0.75rem 1rem; background:#f0fdf4; border-radius:11px; border:1px solid #bbf7d0; margin-top:0.75rem; font-size:0.85rem; color:#166534; }
    .wa-btn-preview { display:inline-flex; align-items:center; gap:6px; background:#25d366; color:white; padding:0.4rem 0.9rem; border-radius:20px; font-size:0.78rem; font-weight:700; }

    /* Submit */
    .btn-save { display:inline-flex; align-items:center; gap:8px; padding:0.85rem 2rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:white; border:none; border-radius:12px; font-weight:700; font-size:0.95rem; cursor:pointer; transition:all 0.2s; font-family:inherit; box-shadow:0 4px 14px rgba(37,99,235,0.3); }
    .btn-save:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(37,99,235,0.4); }
</style>

<div class="app-header">
    <h1>🎨 Apparence & Paramètres régionaux</h1>
    <p>Personnalisez l'apparence de votre boutique, la devise, la langue et WhatsApp</p>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;color:#166534;font-size:0.875rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.configurations.apparence') }}" method="POST">
@csrf
@method('POST')

<div class="app-grid">

    {{-- Thème de la boutique (Feature 14) --}}
    <div class="app-card app-card-full">
        <div class="card-sec-title"><i class="fas fa-palette"></i> Thème de la boutique</div>
        <div class="theme-grid">
            @php
            $themes = [
                'light'  => ['nom'=>'Clair',   'bg'=>'linear-gradient(135deg,#ffffff,#f8fafc)', 'txt'=>'#0f172a'],
                'dark'   => ['nom'=>'Sombre',  'bg'=>'linear-gradient(135deg,#0f172a,#1e293b)', 'txt'=>'#ffffff'],
                'blue'   => ['nom'=>'Bleu',    'bg'=>'linear-gradient(135deg,#1e40af,#3b82f6)', 'txt'=>'#ffffff'],
                'green'  => ['nom'=>'Vert',    'bg'=>'linear-gradient(135deg,#14532d,#22c55e)', 'txt'=>'#ffffff'],
                'orange' => ['nom'=>'Orange',  'bg'=>'linear-gradient(135deg,#92400e,#f97316)', 'txt'=>'#ffffff'],
            ];
            $themeActuel = $configuration->theme ?? 'light';
            @endphp
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
        <div class="field-g" style="margin-top:1.25rem;max-width:280px;">
            <label>Couleur principale personnalisée</label>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <input type="color" name="couleur" value="{{ $configuration->couleur ?? '#2563eb' }}"
                       style="width:52px;height:42px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;padding:2px;">
                <span style="font-size:0.85rem;color:#64748b;">Couleur des boutons et liens de votre boutique</span>
            </div>
        </div>
    </div>

    {{-- Devise (Feature 16) --}}
    <div class="app-card">
        <div class="card-sec-title"><i class="fas fa-coins"></i> Devise d'affichage</div>
        <div class="devise-grid">
            @php
            $devises = [
                'XOF' => ['🇨🇮', 'FCFA', 'Franc CFA'],
                'EUR' => ['🇪🇺', 'EUR',  'Euro'],
                'USD' => ['🇺🇸', 'USD',  'Dollar US'],
                'GHS' => ['🇬🇭', 'GHS',  'Cedi Ghana'],
                'NGN' => ['🇳🇬', 'NGN',  'Naira Nigeria'],
                'MAD' => ['🇲🇦', 'MAD',  'Dirham Maroc'],
            ];
            $deviseActuelle = $configuration->devise ?? 'XOF';
            @endphp
            @foreach($devises as $code => $d)
            <div>
                <input type="radio" class="devise-opt" name="devise" value="{{ $code }}"
                       id="devise_{{ $code }}" {{ $deviseActuelle === $code ? 'checked' : '' }}>
                <label class="devise-label" for="devise_{{ $code }}">
                    <span class="devise-flag">{{ $d[0] }}</span>
                    <div>
                        <strong style="display:block;font-size:0.85rem;">{{ $d[1] }}</strong>
                        <span style="font-size:0.72rem;color:#94a3b8;">{{ $d[2] }}</span>
                    </div>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Langue (Feature 23) --}}
    <div class="app-card">
        <div class="card-sec-title"><i class="fas fa-globe"></i> Langue de la boutique</div>
        <div class="lang-grid">
            @php
            $langues = [
                'fr' => ['🇫🇷', 'Français'],
                'en' => ['🇬🇧', 'English'],
                'ar' => ['🇸🇦', 'العربية'],
                'pt' => ['🇵🇹', 'Português'],
            ];
            $langueActuelle = $configuration->langue ?? 'fr';
            @endphp
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
        <div class="help" style="margin-top:0.75rem;"><i class="fas fa-info-circle"></i> La langue s'applique aux textes automatiques de votre boutique (emails, boutons)</div>
    </div>

    {{-- WhatsApp (Feature 24) --}}
    <div class="app-card app-card-full">
        <div class="card-sec-title">
            <i class="fab fa-whatsapp" style="color:#25d366;"></i> Bouton WhatsApp Business
        </div>
        <div style="max-width:480px;">
            <div class="field-g">
                <label>Numéro WhatsApp (avec indicatif pays)</label>
                <div class="inp-icon">
                    <i class="ic fab fa-whatsapp" style="color:#25d366;"></i>
                    <input type="text" class="inp" name="whatsapp"
                           value="{{ $boutique->reseaux_sociaux['whatsapp'] ?? '' }}"
                           placeholder="+225 07 00 00 00 00">
                </div>
                <div class="help">Ex : +22507000000 — Un bouton flottant vert apparaîtra sur votre boutique</div>
            </div>

            @php $wa = $boutique->reseaux_sociaux['whatsapp'] ?? ''; @endphp
            @if($wa)
            <div class="wa-preview">
                <i class="fab fa-whatsapp" style="font-size:1.4rem;color:#25d366;"></i>
                <div>
                    <strong>Bouton actif sur votre boutique</strong><br>
                    <span>{{ $wa }}</span>
                </div>
                <div class="wa-btn-preview"><i class="fab fa-whatsapp"></i> WhatsApp</div>
            </div>
            @else
            <div style="background:#f8fafc;border-radius:11px;padding:1rem;color:#94a3b8;font-size:0.85rem;text-align:center;">
                <i class="fab fa-whatsapp" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
                Entrez votre numéro pour activer le bouton WhatsApp
            </div>
            @endif
        </div>
    </div>

</div>

<button type="submit" class="btn-save">
    <i class="fas fa-save"></i> Enregistrer les paramètres
</button>

</form>
@endsection
