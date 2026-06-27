@extends('layouts.admin')
@section('title', 'Mon profil')

@push('styles')
<style>
/* ── Profil — style Chariow ── */
.profil-header { margin-bottom: 2rem; }
.profil-header h1 {
    font-size: 1.45rem; font-weight: 800; color: #0f172a;
    letter-spacing: -0.02em; margin: 0 0 0.2rem;
}
.profil-header p { color: #64748b; font-size: 0.875rem; margin: 0; }

/* Layout */
.profil-grid {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:768px) { .profil-grid { grid-template-columns: 1fr; } }

/* Sidebar card */
.profil-sidebar {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 14px; padding: 1.75rem 1.5rem;
    text-align: center;
}
.profil-avatar-wrap { position: relative; display: inline-block; margin-bottom: 1rem; }
.profil-avatar {
    width: 96px; height: 96px; border-radius: 50%;
    background: #0f172a;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 2rem; font-weight: 800;
    margin: 0 auto; overflow: hidden;
}
.profil-avatar img { width: 100%; height: 100%; object-fit: cover; }
.profil-avatar-btn {
    position: absolute; bottom: 2px; right: 2px;
    width: 28px; height: 28px; border-radius: 50%;
    background: #0f172a; color: #fff; border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; cursor: pointer;
}
.profil-name { font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
.profil-email { font-size: 0.77rem; color: #94a3b8; margin-bottom: 1rem; }
.role-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f1f5f9; border-radius: 20px; padding: 4px 12px;
    font-size: 0.75rem; font-weight: 700; color: #0f172a; margin-bottom: 1.5rem;
}
.profil-meta {
    display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;
    border-top: 1px solid #f1f5f9; padding-top: 1.25rem;
}
.meta-cell { background: #f8fafc; border-radius: 10px; padding: 0.75rem; }
.meta-label { font-size: 0.7rem; color: #94a3b8; margin-bottom: 2px; }
.meta-val { font-size: 0.8rem; font-weight: 700; color: #0f172a; }

/* Forms card */
.profil-card {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
}
.profil-card-header {
    padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 10px; background: #fafafa;
}
.profil-card-header .icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: #f1f5f9; border: 1px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; color: #0f172a; flex-shrink: 0;
}
.profil-card-header h5 { font-size: 0.88rem; font-weight: 700; color: #0f172a; margin: 0; }
.profil-card-body { padding: 1.5rem 1.25rem; }

/* Form elements */
.form-label { font-size: 0.81rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
.form-control, .form-select {
    border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important;
    padding: 0.65rem 0.9rem !important; font-size: 0.875rem !important;
    color: #0f172a; background: #fff; transition: border-color .15s;
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
    border-radius: 0 10px 10px 0 !important; border-left: none !important;
}

/* Password strength */
.pw-strength-bar {
    height: 4px; background: #f1f5f9; border-radius: 4px;
    overflow: hidden; margin-top: 6px;
}
.pw-strength-fill { height: 100%; border-radius: 4px; transition: width .3s, background .3s; width: 0%; }
.pw-hint { font-size: 0.73rem; color: #94a3b8; margin-top: 4px; }

/* Toggle switch */
.toggle-wrap { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; }
.toggle-label { font-size: 0.85rem; font-weight: 600; color: #374151; }
.toggle-desc { font-size: 0.75rem; color: #94a3b8; margin-top: 1px; }
.toggle-switch { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
.toggle-switch input { display: none; }
.toggle-slider {
    position: absolute; inset: 0; background: #e2e8f0;
    border-radius: 24px; cursor: pointer; transition: background .25s;
}
.toggle-slider:before {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%;
    background: #fff; left: 3px; top: 3px;
    transition: transform .25s; box-shadow: 0 1px 4px rgba(0,0,0,0.15);
}
.toggle-switch input:checked + .toggle-slider { background: #0f172a; }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(18px); }

/* Buttons */
.btn-save {
    height: 38px; padding: 0 18px; font-size: 0.83rem; font-weight: 700;
    background: #0f172a; color: #fff; border: none; border-radius: 10px;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: background .15s;
}
.btn-save:hover { background: #1e293b; }

/* Alert */
.alert-success-custom {
    background: #f0fdf4; border-left: 3px solid #22c55e;
    padding: 0.875rem 1.1rem; border-radius: 10px;
    margin-bottom: 1.25rem; color: #166534; font-size: 0.85rem;
    display: flex; align-items: center; gap: 8px;
}
.alert-error-custom {
    background: #fef2f2; border-left: 3px solid #ef4444;
    padding: 0.875rem 1.1rem; border-radius: 10px;
    margin-bottom: 1.25rem; color: #dc2626; font-size: 0.85rem;
    display: flex; align-items: center; gap: 8px;
}
</style>
@endpush

@section('content')

<div class="profil-header">
    <h1>Mon profil</h1>
    <p>Gérez vos informations personnelles et paramètres de compte</p>
</div>

@if(session('success'))
<div class="alert-success-custom"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert-error-custom"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="profil-grid">

    {{-- Sidebar --}}
    <div class="profil-sidebar">
        <div class="profil-avatar-wrap">
            <div class="profil-avatar">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->nom }}">
                @else
                    {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                @endif
            </div>
            <button type="button" class="profil-avatar-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                <i class="fas fa-camera"></i>
            </button>
        </div>
        <div class="profil-name">{{ Auth::user()->nom }}</div>
        <div class="profil-email">{{ Auth::user()->email }}</div>
        <div class="role-badge">
            <i class="fas fa-{{ Auth::user()->role === 'admin' ? 'crown' : 'user' }}"></i>
            {{ Auth::user()->role === 'admin' ? 'Administrateur' : 'Gestionnaire' }}
        </div>
        <div class="profil-meta">
            <div class="meta-cell">
                <div class="meta-label">Membre depuis</div>
                <div class="meta-val">{{ Auth::user()->created_at->format('M Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Dernière activité</div>
                <div class="meta-val">{{ Auth::user()->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>

    {{-- Forms --}}
    <div>

        {{-- Informations personnelles --}}
        <div class="profil-card">
            <div class="profil-card-header">
                <div class="icon"><i class="fas fa-user"></i></div>
                <h5>Informations personnelles</h5>
            </div>
            <div class="profil-card-body">
                <form action="{{ route('admin.profil.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   name="nom" value="{{ old('nom', Auth::user()->nom) }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="3"
                                      placeholder="Parlez un peu de vous...">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Mot de passe --}}
        <div class="profil-card">
            <div class="profil-card-header">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <h5>Changer le mot de passe</h5>
            </div>
            <div class="profil-card-body">
                <form action="{{ route('admin.profil.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Mot de passe actuel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       name="current_password" required>
                            </div>
                            @error('current_password')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                       name="new_password" id="newPw" required>
                            </div>
                            <div class="pw-strength-bar"><div class="pw-strength-fill" id="pwBar"></div></div>
                            <div class="pw-hint" id="pwHint">Entrez un nouveau mot de passe</div>
                            @error('new_password')<div class="text-danger" style="font-size:0.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Confirmer</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check"></i></span>
                                <input type="password" class="form-control"
                                       name="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-sync-alt"></i> Changer le mot de passe
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Préférences --}}
        <div class="profil-card">
            <div class="profil-card-header">
                <div class="icon"><i class="fas fa-sliders-h"></i></div>
                <h5>Préférences</h5>
            </div>
            <div class="profil-card-body">
                <form action="{{ route('admin.profil.preferences') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Langue préférée</label>
                            <select class="form-select" name="langue">
                                <option value="fr" {{ (Auth::user()->langue ?? 'fr') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                <option value="en" {{ (Auth::user()->langue ?? '') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fuseau horaire</label>
                            <select class="form-select" name="fuseau_horaire">
                                <option value="Africa/Abidjan" {{ (Auth::user()->fuseau_horaire ?? 'Africa/Abidjan') == 'Africa/Abidjan' ? 'selected' : '' }}>Abidjan (GMT+0)</option>
                                <option value="Europe/Paris" {{ (Auth::user()->fuseau_horaire ?? '') == 'Europe/Paris' ? 'selected' : '' }}>Paris (GMT+1)</option>
                                <option value="America/New_York" {{ (Auth::user()->fuseau_horaire ?? '') == 'America/New_York' ? 'selected' : '' }}>New York (GMT-5)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="toggle-wrap" style="border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                                <div>
                                    <div class="toggle-label">Notifications par email</div>
                                    <div class="toggle-desc">Recevoir les alertes de ventes et nouveaux clients</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="notifications_email" value="1"
                                           {{ (Auth::user()->notifications_email ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Enregistrer les préférences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Modal avatar --}}
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e5e7eb;">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1rem 1.25rem;">
                <h5 class="modal-title" style="font-size:0.9rem;font-weight:700;color:#0f172a;">
                    <i class="fas fa-camera me-2"></i>Photo de profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:0.7rem;"></button>
            </div>
            <form action="{{ route('admin.profil.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:1.25rem;">
                    <div style="text-align:center;margin-bottom:1rem;">
                        <div class="profil-avatar" style="margin:0 auto 1rem;width:80px;height:80px;font-size:1.5rem;">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->nom }}" id="avatarPreview">
                            @else
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                           name="avatar" accept="image/*" required id="avatarInput">
                    <div style="font-size:0.73rem;color:#94a3b8;margin-top:0.35rem;">JPG, PNG ou GIF · Max 2 Mo</div>
                    @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:0.875rem 1.25rem;gap:0.5rem;">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal"
                            style="height:36px;padding:0 14px;font-size:0.82rem;font-weight:600;background:#f8fafc;color:#64748b;border:1px solid #e5e7eb;border-radius:9px;cursor:pointer;">
                        Annuler
                    </button>
                    <button type="submit" class="btn-save" style="height:36px;">
                        <i class="fas fa-upload"></i> Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Password strength indicator
document.getElementById('newPw')?.addEventListener('input', function() {
    const pw = this.value;
    const bar = document.getElementById('pwBar');
    const hint = document.getElementById('pwHint');
    let s = 0;
    if (pw.length >= 8) s += 25;
    if (/[a-z]/.test(pw)) s += 25;
    if (/[A-Z]/.test(pw)) s += 25;
    if (/[0-9$@#&!]/.test(pw)) s += 25;
    s = Math.min(100, s);
    bar.style.width = s + '%';
    const colors = ['#ef4444','#f59e0b','#3b82f6','#22c55e'];
    const labels = ['Trop court','Moyen','Fort','Très fort'];
    const idx = Math.floor(s / 30);
    bar.style.background = colors[Math.min(idx, 3)];
    hint.textContent = s === 0 ? 'Entrez un nouveau mot de passe' : labels[Math.min(idx, 3)];
});

// Avatar preview
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        let img = document.getElementById('avatarPreview');
        if (!img) {
            const wrap = document.querySelector('.profil-avatar');
            if (wrap) { wrap.innerHTML = `<img id="avatarPreview" src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`; }
        } else { img.src = e.target.result; }
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
