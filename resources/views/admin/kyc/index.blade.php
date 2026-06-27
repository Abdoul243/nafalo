@extends('layouts.admin')
@section('title', 'Vérification d\'identité (KYC)')

@push('styles')
<style>
.doc-zone { border:2px dashed #e5e7eb;border-radius:14px;padding:1.5rem;text-align:center;cursor:pointer;transition:all .2s;position:relative; }
.doc-zone:hover { border-color:#f59e0b;background:#fffbeb; }
.doc-zone input[type=file] { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
.doc-zone i { font-size:2rem;color:#9ca3af;margin-bottom:.5rem;display:block; }
.doc-zone .doc-label { font-size:.83rem;color:#6b7280; }
.doc-zone.has-file { border-color:#22c55e;background:#f0fdf4; }
.doc-zone.has-file i { color:#22c55e; }
</style>
@endpush

@section('content')
<div class="cw-page">

@php
$statut = $kyc->statut ?? 'non_soumis';
$configs = [
    'approuve'   => ['bg' => '#f0fdf4','border' => '#bbf7d0','iconBg' => '#dcfce7','iconColor' => '#16a34a','icon' => 'fas fa-shield-alt','titre' => 'Identité vérifiée','desc' => 'Votre dossier KYC a été approuvé. Vous bénéficiez de toutes les fonctionnalités Nafalo.'],
    'en_attente' => ['bg' => '#fffbeb','border' => '#fde68a','iconBg' => '#fef9c3','iconColor' => '#d97706','icon' => 'fas fa-clock','titre' => 'Dossier en cours de vérification','desc' => 'Nous avons bien reçu votre dossier. Notre équipe le vérifie sous 24 à 48h ouvrées.'],
    'rejete'     => ['bg' => '#fff1f2','border' => '#fecdd3','iconBg' => '#fee2e2','iconColor' => '#dc2626','icon' => 'fas fa-times-circle','titre' => 'Dossier rejeté','desc' => 'Votre dossier a été rejeté. Veuillez soumettre un nouveau dossier avec des documents lisibles.'],
    'non_soumis' => ['bg' => '#f8fafc','border' => '#e5e7eb','iconBg' => '#f1f5f9','iconColor' => '#6b7280','icon' => 'fas fa-id-card','titre' => 'Identité non vérifiée','desc' => 'Soumettez vos documents pour être vérifié et débloquer toutes les fonctionnalités.'],
];
$cfg = $configs[$statut];
@endphp

{{-- Statut --}}
<div style="display:flex;align-items:center;gap:16px;background:{{ $cfg['bg'] }};border:1px solid {{ $cfg['border'] }};border-radius:14px;padding:20px 24px;margin-bottom:24px;">
    <div style="width:56px;height:56px;border-radius:50%;background:{{ $cfg['iconBg'] }};color:{{ $cfg['iconColor'] }};display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
        <i class="{{ $cfg['icon'] }}"></i>
    </div>
    <div>
        <div style="font-weight:700;color:#111827;font-size:1rem;">{{ $cfg['titre'] }}</div>
        <div style="font-size:.83rem;color:#6b7280;margin-top:3px;">{{ $cfg['desc'] }}</div>
        @if($statut === 'rejete' && ($kyc->note_admin ?? null))
        <div style="margin-top:8px;padding:6px 12px;background:#fee2e2;color:#991b1b;font-size:.8rem;border-radius:8px;">
            <strong>Motif :</strong> {{ $kyc->note_admin }}
        </div>
        @endif
    </div>
</div>

@if(in_array($statut, ['non_soumis', 'rejete']))

{{-- Avantages --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px 24px;margin-bottom:24px;">
    <div style="font-weight:700;color:#111827;font-size:.9rem;margin-bottom:16px;">Pourquoi vérifier mon identité ?</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        @foreach([
            ['fas fa-shield-alt', '#3b82f6', '#eff6ff', 'Plateforme sécurisée', 'Protégez votre compte et vos revenus'],
            ['fas fa-money-bill-wave', '#16a34a', '#f0fdf4', 'Retraits sans limite', 'Accédez aux retraits sans restriction'],
            ['fas fa-star', '#d97706', '#fffbeb', 'Badge vérifié', 'Inspirez confiance à vos clients'],
            ['fas fa-headset', '#8b5cf6', '#f5f3ff', 'Support prioritaire', 'Accédez au support client dédié'],
        ] as [$icon, $color, $bg, $titre, $desc])
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:10px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="{{ $icon }}"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:.83rem;color:#111827;">{{ $titre }}</div>
                <div style="font-size:.78rem;color:#9ca3af;">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Formulaire --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:24px;">
    <div style="font-weight:700;color:#111827;font-size:.9rem;margin-bottom:4px;">Soumettre votre dossier KYC</div>
    <div style="font-size:.78rem;color:#9ca3af;margin-bottom:20px;">Documents acceptés : JPEG, PNG ou PDF — 5 Mo max par fichier</div>

    <form action="{{ route('admin.kyc.soumettre') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="font-size:.83rem;font-weight:600;color:#374151;display:block;margin-bottom:10px;">Type de document *</label>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                @foreach(\App\Models\Kyc::TYPES_DOCUMENT as $val => $label)
                <div>
                    <input type="radio" class="btn-check" name="type_document" id="doc_{{ $val }}" value="{{ $val }}"
                           {{ old('type_document', $kyc->type_document ?? '') == $val ? 'checked' : '' }} required>
                    <label class="btn btn-outline-secondary w-100" for="doc_{{ $val }}" style="border-radius:10px;padding:10px;font-size:.83rem;">
                        {{ $label }}
                    </label>
                </div>
                @endforeach
            </div>
            @error('type_document')<div style="color:#dc2626;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
                <label style="font-size:.83rem;font-weight:600;color:#374151;display:block;margin-bottom:8px;">Recto *</label>
                <div class="doc-zone" id="zone-recto">
                    <input type="file" name="document_recto" accept=".jpg,.jpeg,.png,.pdf" onchange="previewDoc(this,'zone-recto','prev-recto')">
                    <i class="fas fa-id-card-alt"></i>
                    <div class="doc-label">Glissez ou cliquez</div>
                    <div style="font-size:.7rem;color:#9ca3af;margin-top:4px;">JPG, PNG ou PDF — 5 Mo max</div>
                </div>
                <div id="prev-recto" style="font-size:.76rem;color:#16a34a;margin-top:6px;"></div>
                @error('document_recto')<div style="color:#dc2626;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="font-size:.83rem;font-weight:600;color:#374151;display:block;margin-bottom:8px;">Verso <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
                <div class="doc-zone" id="zone-verso">
                    <input type="file" name="document_verso" accept=".jpg,.jpeg,.png,.pdf" onchange="previewDoc(this,'zone-verso','prev-verso')">
                    <i class="fas fa-id-card"></i>
                    <div class="doc-label">Verso si applicable</div>
                    <div style="font-size:.7rem;color:#9ca3af;margin-top:4px;">JPG, PNG ou PDF — 5 Mo max</div>
                </div>
                <div id="prev-verso" style="font-size:.76rem;color:#16a34a;margin-top:6px;"></div>
            </div>
        </div>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;font-size:.8rem;color:#1e40af;margin-bottom:20px;">
            <i class="fas fa-lock me-2"></i>
            Vos documents sont <strong>chiffrés et stockés de manière sécurisée</strong>. Accessibles uniquement à l'équipe Nafalo pour vérification.
        </div>

        <button type="submit" class="cw-btn-primary">
            <i class="fas fa-paper-plane"></i> Soumettre mon dossier KYC
        </button>
    </form>
</div>

@elseif($statut === 'approuve')
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:48px;text-align:center;">
    <div style="font-size:3rem;margin-bottom:12px;">✅</div>
    <div style="font-weight:700;font-size:1.1rem;color:#111827;margin-bottom:8px;">Vous êtes vérifié !</div>
    <div style="font-size:.85rem;color:#9ca3af;margin-bottom:20px;">Votre identité a été confirmée. Profitez de toutes les fonctionnalités Nafalo.</div>
    <a href="{{ route('admin.dashboard') }}" class="cw-btn-primary">
        <i class="fas fa-home"></i> Retour au tableau de bord
    </a>
</div>
@endif

</div>
@endsection

@push('scripts')
<script>
function previewDoc(input, zoneId, previewId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const zone = document.getElementById(zoneId);
        zone.classList.add('has-file');
        zone.querySelector('i').className = 'fas fa-check-circle';
        zone.querySelector('.doc-label').textContent = file.name;
        document.getElementById(previewId).textContent = file.name + ' (' + (file.size/1024/1024).toFixed(2) + ' Mo)';
    }
}
</script>
@endpush
