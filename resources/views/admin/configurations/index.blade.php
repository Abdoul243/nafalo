@extends('layouts.admin')
@section('title', 'Paramètres')

@push('styles')
<style>
/* ── Paramètres Hub — style Chariow ── */
.settings-header {
    margin-bottom: 2rem;
}
.settings-header h1 {
    font-size: 1.45rem; font-weight: 800; color: #0f172a;
    letter-spacing: -0.02em; margin: 0 0 0.25rem;
}
.settings-header p {
    color: #64748b; font-size: 0.875rem; margin: 0;
}

/* Section */
.settings-section { margin-bottom: 2rem; }
.settings-section-label {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 0.75rem;
    padding-bottom: 0.5rem; border-bottom: 1px solid #f1f5f9;
}

/* Cards grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1rem;
}

/* Setting card */
.setting-card {
    display: flex; align-items: center; gap: 1rem;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 14px;
    padding: 1.1rem 1.25rem; text-decoration: none;
    transition: all .15s; cursor: pointer;
}
.setting-card:hover {
    border-color: #0f172a; box-shadow: 0 4px 16px rgba(15,23,42,0.08);
    transform: translateY(-1px); color: inherit;
}
.setting-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: #f8fafc; border: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #0f172a; flex-shrink: 0;
}
.setting-info { min-width: 0; flex: 1; }
.setting-name {
    font-size: 0.88rem; font-weight: 700; color: #0f172a;
    margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.setting-desc {
    font-size: 0.73rem; color: #94a3b8; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.setting-arrow {
    color: #cbd5e1; font-size: 0.75rem; flex-shrink: 0;
    transition: color .15s;
}
.setting-card:hover .setting-arrow { color: #0f172a; }

/* Status badge */
.setting-badge {
    font-size: 0.68rem; font-weight: 700; padding: 2px 8px;
    border-radius: 20px; flex-shrink: 0;
}
.setting-badge.active { background: #f0fdf4; color: #16a34a; }
.setting-badge.inactive { background: #fef2f2; color: #dc2626; }
</style>
@endpush

@section('content')

<div class="settings-header">
    <h1>Paramètres</h1>
    <p>Configurez et personnalisez votre boutique</p>
</div>

{{-- Boutique --}}
<div class="settings-section">
    <div class="settings-section-label">Boutique</div>
    <div class="settings-grid">
        <a href="{{ route('admin.configurations.general') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-store"></i></div>
            <div class="setting-info">
                <div class="setting-name">Identité générale</div>
                <div class="setting-desc">Nom, logo, contact, domaine</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.configurations.apparence') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-palette"></i></div>
            <div class="setting-info">
                <div class="setting-name">Apparence & Régional</div>
                <div class="setting-desc">Thème, devise, langue, WhatsApp</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.pixels.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-code"></i></div>
            <div class="setting-info">
                <div class="setting-name">Pixels & Tracking</div>
                <div class="setting-desc">Facebook Pixel, Google Analytics</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
    </div>
</div>

{{-- Paiement --}}
<div class="settings-section">
    <div class="settings-section-label">Paiement</div>
    <div class="settings-grid">
        <a href="{{ route('admin.configurations.paiement') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-credit-card"></i></div>
            <div class="setting-info">
                <div class="setting-name">Moyens de paiement</div>
                <div class="setting-desc">Moneroo, Wave, Mobile Money</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.kyc.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-id-card"></i></div>
            <div class="setting-info">
                <div class="setting-name">KYC & Vérification</div>
                <div class="setting-desc">Documents et identité</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
    </div>
</div>

{{-- Marketing --}}
<div class="settings-section">
    <div class="settings-section-label">Marketing</div>
    <div class="settings-grid">
        <a href="{{ route('admin.codes-promo.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-tag"></i></div>
            <div class="setting-info">
                <div class="setting-name">Codes promo</div>
                <div class="setting-desc">Réductions et offres spéciales</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.produits.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-chart-line"></i></div>
            <div class="setting-info">
                <div class="setting-name">Upsells</div>
                <div class="setting-desc">Offres complémentaires par produit</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.copublications.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-handshake"></i></div>
            <div class="setting-info">
                <div class="setting-name">Copublications</div>
                <div class="setting-desc">Partenariats affiliés</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
    </div>
</div>

{{-- Communication --}}
<div class="settings-section">
    <div class="settings-section-label">Communication</div>
    <div class="settings-grid">
        <a href="{{ route('admin.configurations.email') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-envelope"></i></div>
            <div class="setting-info">
                <div class="setting-name">Emails</div>
                <div class="setting-desc">Confirmations, relances, templates</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-bell"></i></div>
            <div class="setting-info">
                <div class="setting-name">Notifications</div>
                <div class="setting-desc">Alertes et préférences</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
    </div>
</div>

{{-- Compte --}}
<div class="settings-section">
    <div class="settings-section-label">Compte</div>
    <div class="settings-grid">
        <a href="{{ route('admin.profil') }}" class="setting-card">
            <div class="setting-icon"><i class="fas fa-user-circle"></i></div>
            <div class="setting-info">
                <div class="setting-name">Profil créateur</div>
                <div class="setting-desc">Informations personnelles</div>
            </div>
            <i class="fas fa-chevron-right setting-arrow"></i>
        </a>
    </div>
</div>

@endsection
