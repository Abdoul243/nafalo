@extends('layouts.admin')

@section('title', 'Mon profil')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Mon profil</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mon profil</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Colonne de gauche - Photo et infos -->
        <div class="col-xl-4 col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <!-- Photo de profil -->
                    <div class="position-relative d-inline-block mb-4">
                        <div class="avatar-upload">
                            <div class="avatar-preview rounded-circle overflow-hidden border" 
                                 style="width: 150px; height: 150px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                         alt="{{ Auth::user()->nom }}" 
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                        <span class="display-4 fw-bold">
                                            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" 
                                    data-bs-toggle="modal" data-bs-target="#avatarModal"
                                    style="width: 40px; height: 40px;">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-1">{{ Auth::user()->nom }}</h4>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>

                    <!-- Badge de rôle -->
                    <div class="mb-3">
                        @if(Auth::user()->role === 'admin')
                            <span class="badge bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0.5rem 1.5rem;">
                                <i class="fas fa-crown me-2"></i>Administrateur
                            </span>
                        @else
                            <span class="badge bg-info" style="padding: 0.5rem 1.5rem;">
                                <i class="fas fa-user me-2"></i>Gestionnaire
                            </span>
                        @endif
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="row g-2 mt-4">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Membre depuis</small>
                                <strong>{{ Auth::user()->created_at->format('M Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Dernière connexion</small>
                                <strong>{{ Auth::user()->updated_at->diffForHumans() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite - Formulaires -->
        <div class="col-xl-8 col-md-7">
            <!-- Informations personnelles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profil.update') }}" method="POST" id="infoForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom complet</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" name="nom" value="{{ old('nom', Auth::user()->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="3" 
                                          placeholder="Parlez un peu de vous...">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Mettre à jour les informations
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Changer le mot de passe -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-lock me-2 text-primary"></i>Changer le mot de passe
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profil.password') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password" required>
                                </div>
                                @error('new_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="fas fa-check-circle text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0" 
                                           id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                            </div>
                            
                            <!-- Indicateur de force du mot de passe -->
                            <div class="col-12">
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" id="passwordStrength"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block" id="passwordStrengthText">
                                        Entrez un nouveau mot de passe
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-2"></i>Changer le mot de passe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Préférences -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-cog me-2 text-primary"></i>Préférences
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profil.preferences') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="langue" class="form-label">Langue préférée</label>
                                <select class="form-select" id="langue" name="langue">
                                    <option value="fr" {{ (Auth::user()->langue ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ (Auth::user()->langue ?? '') == 'en' ? 'selected' : '' }}>Anglais</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fuseau_horaire" class="form-label">Fuseau horaire</label>
                                <select class="form-select" id="fuseau_horaire" name="fuseau_horaire">
                                    <option value="Africa/Abidjan" {{ (Auth::user()->fuseau_horaire ?? 'Africa/Abidjan') == 'Africa/Abidjan' ? 'selected' : '' }}>Afrique/Abidjan (GMT+0)</option>
                                    <option value="Europe/Paris" {{ (Auth::user()->fuseau_horaire ?? '') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                                    <option value="America/New_York" {{ (Auth::user()->fuseau_horaire ?? '') == 'America/New_York' ? 'selected' : '' }}>Amérique/New York (GMT-5)</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifications_email" 
                                           name="notifications_email" value="1" 
                                           {{ (Auth::user()->notifications_email ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_email">
                                        Recevoir les notifications par email
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les préférences
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer l'avatar -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera me-2 text-primary"></i>Changer la photo de profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profil.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-preview-large rounded-circle overflow-hidden border mx-auto" 
                             style="width: 200px; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                     alt="{{ Auth::user()->nom }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     id="avatarPreview">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                    <span class="display-1 fw-bold">
                                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Choisir une image</label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" name="avatar" accept="image/*" required>
                        <small class="text-muted">Formats acceptés : JPG, PNG, GIF (max 2 Mo)</small>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        L'image sera redimensionnée automatiquement à 500x500 pixels.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la déconnexion -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt me-2 text-danger"></i>Déconnexion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .avatar-upload {
        position: relative;
        max-width: 150px;
        margin: 0 auto;
    }
    
    .avatar-preview {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .avatar-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .password-strength .progress-bar {
        transition: width 0.3s ease;
    }
    
    .progress-bar[style*="width: 0%"] { background-color: #dc3545; }
    .progress-bar[style*="width: 25%"] { background-color: #ffc107; }
    .progress-bar[style*="width: 50%"] { background-color: #ffc107; }
    .progress-bar[style*="width: 75%"] { background-color: #17a2b8; }
    .progress-bar[style*="width: 100%"] { background-color: #28a745; }
    
    .badge.bg-gradient {
        color: white;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: #667eea;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    /* Animation pour les cartes — désactivée sur mobile pour éviter le décalage */
    @media (min-width: 768px) {
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
    }
    
    /* Style pour les champs de formulaire */
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .input-group-text {
        border: 2px solid #e9ecef;
        border-right: none;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group .form-control:focus {
        border-left: none;
        box-shadow: none;
        border-color: #667eea;
    }

    /* ── RESPONSIVE PROFIL ── */
    @media (max-width: 767px) {
        .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }
        /* Colonnes Bootstrap empilées automatiquement sous md */
        /* Mot de passe: 3 champs → 1 colonne */
        .col-md-4 { flex: 0 0 100%; max-width: 100%; }
        /* Préférences: 2 selects → 1 colonne */
        .col-md-6.col-12 { flex: 0 0 100%; max-width: 100%; }
        /* Avatar un peu plus petit */
        .avatar-preview { width: 110px !important; height: 110px !important; }
        .avatar-preview-large { width: 150px !important; height: 150px !important; }
    }
    @media (max-width: 480px) {
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column; align-items: flex-start !important; gap: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Aperçu de l'avatar avant upload
    document.getElementById('avatar')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    // Créer un aperçu si l'élément n'existe pas
                    const previewContainer = document.querySelector('.avatar-preview-large div');
                    if (previewContainer) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover">`;
                    }
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Indicateur de force du mot de passe
    document.getElementById('new_password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('passwordStrengthText');
        
        let strength = 0;
        let message = '';
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/)) strength += 25;
        if (password.match(/[$@#&!]+/)) strength += 25;
        
        // Limiter à 100%
        strength = Math.min(strength, 100);
        
        // Mettre à jour la barre
        strengthBar.style.width = strength + '%';
        
        // Message correspondant
        if (strength === 0) {
            message = 'Entrez un nouveau mot de passe';
            strengthBar.style.backgroundColor = '#dc3545';
        } else if (strength < 50) {
            message = 'Mot de passe faible';
            strengthBar.style.backgroundColor = '#dc3545';
        } else if (strength < 75) {
            message = 'Mot de passe moyen';
            strengthBar.style.backgroundColor = '#ffc107';
        } else if (strength < 100) {
            message = 'Mot de passe fort';
            strengthBar.style.backgroundColor = '#17a2b8';
        } else {
            message = 'Mot de passe très fort';
            strengthBar.style.backgroundColor = '#28a745';
        }
        
        strengthText.textContent = message;
    });

    // Confirmation avant de quitter avec des changements non sauvegardés
    let formChanged = false;
    
    document.querySelectorAll('form input, form select, form textarea').forEach(element => {
        element.addEventListener('change', () => {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Réinitialiser le flag après soumission du formulaire
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            formChanged = false;
        });
    });
</script>
@endpush