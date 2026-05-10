

<?php $__env->startSection('title', 'Nouveau produit'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .product-editor-layout {
        display: flex;
        gap: 0;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        min-height: 80vh;
    }
    .editor-sidebar {
        width: 240px;
        min-width: 240px;
        background: #fafafa;
        border-right: 1px solid #eeeeee;
        padding: 1.5rem 0;
    }
    .editor-sidebar .product-title-preview {
        padding: 0 1.5rem 1.5rem;
        border-bottom: 1px solid #eee;
        margin-bottom: 1rem;
    }
    .editor-sidebar .product-title-preview h6 {
        font-weight: 800;
        color: #1a1a2e;
        font-size: 0.95rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sidebar-nav { list-style: none; padding: 0; margin: 0; }
    .sidebar-nav li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.75rem 1.5rem;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .sidebar-nav li a:hover {
        background: #f0f0f0;
        color: #333;
    }
    .sidebar-nav li a.active {
        background: #667eea15;
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }
    .sidebar-nav li a i { width: 18px; text-align: center; }

    /* ── Type cards (Payant / Gratuit) ── */
    .type-card {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        padding: 1.25rem 1rem; border: 2px solid #e8e8e8; border-radius: 16px;
        cursor: pointer; text-align: center; transition: all 0.2s; background: white;
        height: 100%;
    }
    .type-card:hover { border-color: #667eea; background: #667eea08; }
    .type-card.selected { border-color: #667eea; background: #667eea0f; }
    .type-card-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.2rem;
    }
    .type-card-label { font-weight: 700; font-size: 0.9rem; color: #1a1a2e; }
    .type-card-sub { font-size: 0.75rem; color: #888; line-height: 1.3; }

    /* ── Champs lead toggle ── */
    .champ-toggle {
        display: flex; align-items: center; padding: 0.6rem 1rem;
        border: 1.5px solid #e0e0e0; border-radius: 10px; cursor: pointer;
        font-size: 0.85rem; font-weight: 500; color: #555; transition: all 0.2s;
        background: white; width: 100%;
    }
    .champ-toggle input { display: none; }
    .champ-toggle:hover { border-color: #16a34a; color: #16a34a; }
    .champ-toggle.active { border-color: #16a34a; background: #f0fdf4; color: #16a34a; font-weight: 600; }
    .editor-main {
        flex: 1;
        padding: 2.5rem;
        overflow-y: auto;
    }
    .section-block {
        display: none;
    }
    .section-block.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    .section-subtitle {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }
    .form-label { font-weight: 600; color: #333; font-size: 0.9rem; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e0e0e0;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }
    .ql-toolbar { border-radius: 10px 10px 0 0 !important; border-color: #e0e0e0 !important; }
    .ql-container { border-radius: 0 0 10px 10px !important; border-color: #e0e0e0 !important; min-height: 300px; font-size: 0.95rem; }
    .ql-editor { min-height: 280px; }
    .image-upload-area {
        border: 2px dashed #e0e0e0;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafafa;
    }
    .image-upload-area:hover { border-color: #667eea; background: #667eea08; }
    .image-preview { display: none; margin-top: 1rem; }
    .image-preview img { max-height: 200px; border-radius: 10px; border: 1px solid #eee; }
    .price-input-wrapper { position: relative; }
    .price-input-wrapper .currency {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }
    .top-bar .product-name-display {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a1a2e;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102,126,234,0.4);
        color: white;
    }
    .btn-publish {
        background: #1a1a2e;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-publish:hover { background: #2d2f45; color: white; }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }
    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 1rem;
        border: 2px solid #e8e8e8;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        background: white;
    }
    .category-item:hover {
        border-color: #667eea;
        background: #667eea08;
    }
    .category-item.selected {
        border-color: #667eea;
        background: #667eea12;
        color: #667eea;
    }
    .category-item i {
        font-size: 1.5rem;
        color: #888;
    }
    .category-item.selected i { color: #667eea; }
    .category-item span {
        font-size: 0.8rem;
        font-weight: 600;
        color: #444;
        line-height: 1.2;
    }
    .category-item.selected span { color: #667eea; }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        /* Layout vertical */
        .product-editor-layout {
            flex-direction: column;
            border-radius: 14px;
            min-height: auto;
        }
        /* Sidebar → bandeau de tabs horizontal en haut */
        .editor-sidebar {
            width: 100% !important;
            min-width: 0 !important;
            border-right: none;
            border-bottom: 1px solid #eee;
            padding: 0;
        }
        .product-title-preview { display: none; }
        .sidebar-nav {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 0;
        }
        .sidebar-nav li { flex-shrink: 0; }
        .sidebar-nav li a {
            padding: 0.75rem 1rem;
            border-left: none !important;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            white-space: nowrap;
            flex-direction: column;
            gap: 4px;
            font-size: 0.75rem;
        }
        .sidebar-nav li a.active {
            border-bottom-color: #667eea;
            background: #f7f7ff;
        }
        /* Contenu principal */
        .editor-main { padding: 1rem; }
        /* Grille catégories : 2 colonnes sur mobile */
        .category-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* Barre supérieure responsive */
    @media (max-width: 480px) {
        .d-flex.align-items-center.justify-content-between.mb-3 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }
        .d-flex.align-items-center.justify-content-between.mb-3 .d-flex.gap-2 {
            width: 100%;
            justify-content: stretch;
        }
        .btn-save, .btn-publish { flex: 1; text-align: center; justify-content: center; }
        .section-title { font-size: 1.1rem; }
    }

    /* ── Wizard : badges d'étape ── */
    .step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        font-size: 0.65rem;
        font-weight: 800;
        margin-left: auto;
        flex-shrink: 0;
    }
    .step-badge.valid  { background: #22c55e; color: white; }
    .step-badge.invalid{ background: #ef4444; color: white; }
    .step-icon.valid  i { color: #22c55e !important; }
    .step-icon.invalid i{ color: #ef4444 !important; }

    /* ── Wizard : navigation Suivant / Précédent ── */
    .wizard-nav {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }
    .wizard-nav-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
    .btn-wizard-prev {
        background: white;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-wizard-prev:hover { border-color: #94a3b8; color: #334155; }
    .btn-wizard-next {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-wizard-next:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(79,70,229,0.4); }

    /* ── Wizard : états d'erreur ── */
    .is-invalid-wizard {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.15) !important;
    }
    .grid-invalid {
        border: 2px solid #ef4444;
        border-radius: 14px;
        padding: 0.5rem;
        animation: shake 0.4s ease;
    }
    .upload-invalid {
        border-color: #ef4444 !important;
        background: #fef2f2 !important;
        animation: shake 0.4s ease;
    }
    .wizard-error-msg {
        display: none;
        color: #dc2626;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        background: #fef2f2;
        border-radius: 8px;
        border-left: 3px solid #ef4444;
    }
    .wizard-error-msg.show { display: block; }
    @keyframes shake {
        0%,100%{ transform:translateX(0); }
        25%{ transform:translateX(-6px); }
        75%{ transform:translateX(6px); }
    }
    /* Publier disabled */
    .btn-publish:disabled { opacity: .45; cursor: not-allowed; }
    .btn-publish:disabled:hover { transform: none; box-shadow: none; background: #1a1a2e; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<form action="<?php echo e(route('admin.produits.store')); ?>" method="POST" enctype="multipart/form-data" id="produit-form">
<?php echo csrf_field(); ?>


<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo e(route('admin.produits.index')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        <h1 class="h5 mb-0 fw-bold">Nouveau produit</h1>
    </div>
    <div class="d-flex gap-2">
        <input type="hidden" name="est_publie" id="est_publie_hidden" value="0">
        <button type="button" class="btn btn-save" onclick="saveDraft()">
            <i class="fas fa-save me-1"></i> Enregistrer
        </button>
        <button type="button" id="btn-publish" class="btn btn-publish" onclick="publish()" disabled title="Complétez les 5 sections requises pour publier">
            <i class="fas fa-check-circle me-1"></i> Publier
        </button>
    </div>
</div>

<div class="product-editor-layout">

    
    <div class="editor-sidebar">
        <div class="product-title-preview">
            <div class="text-muted small mb-1">Produit</div>
            <h6 id="sidebar-title">Sans titre</h6>
        </div>
        
        <div style="padding:0 1.25rem 1rem;">
            <div style="font-size:.7rem;color:#94a3b8;margin-bottom:5px;font-weight:600;">PROGRESSION</div>
            <div style="height:5px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
                <div id="progress-bar" style="height:100%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:10px;transition:width .4s;width:0%"></div>
            </div>
            <div id="progress-label" style="font-size:.7rem;color:#64748b;margin-top:4px;text-align:right;">0 / 5 requis</div>
        </div>

        <ul class="sidebar-nav" id="sidebar-nav-list">
            <li>
                <a href="#" class="active" data-section="informations" id="nav-informations">
                    <span class="step-icon" id="icon-informations">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="sidebar-text"> Informations</span>
                    <span class="step-badge" id="badge-informations" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="tarification" id="nav-tarification">
                    <span class="step-icon" id="icon-tarification">
                        <i class="fas fa-tag"></i>
                    </span>
                    <span class="sidebar-text"> Tarification</span>
                    <span class="step-badge" id="badge-tarification" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="fichiers" id="nav-fichiers">
                    <span class="step-icon" id="icon-fichiers">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <span class="sidebar-text"> Fichiers</span>
                    <span class="step-badge" id="badge-fichiers" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="description" id="nav-description">
                    <span class="step-icon" id="icon-description">
                        <i class="fas fa-align-left"></i>
                    </span>
                    <span class="sidebar-text"> Description</span>
                    <span class="step-badge" id="badge-description" style="display:none;"></span>
                </a>
            </li>
            <li>
                <a href="#" data-section="visuel" id="nav-visuel">
                    <span class="step-icon" id="icon-visuel">
                        <i class="fas fa-image"></i>
                    </span>
                    <span class="sidebar-text"> Visuel & Design</span>
                    <span class="step-badge" id="badge-visuel" style="display:none;"></span>
                </a>
            </li>
        </ul>
    </div>

    
    <div class="editor-main">

        
        <div class="section-block active" id="section-informations">
            <div class="section-title">Détails du produit</div>
            <div class="section-subtitle">Les informations essentielles de votre produit</div>

            <div class="mb-4">
                <label class="form-label">Nom du produit *</label>
                <input type="text" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       name="nom" id="nom-input"
                       placeholder="Ex: Formation Excel Complète"
                       value="<?php echo e(old('nom')); ?>" required>
                <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="form-label">Catégorie *</label>
                <p class="text-muted small mb-3">Choisissez la catégorie qui correspond le mieux à votre produit</p>
                <div class="category-grid">
                    <?php
                    $icons = [
                        'Formation & Éducation' => 'fas fa-graduation-cap',
                        'Livres & Ebooks' => 'fas fa-book',
                        'Templates & Modèles' => 'fas fa-file-alt',
                        'Audio & Musique' => 'fas fa-music',
                        'Vidéo & Film' => 'fas fa-film',
                        'Logiciels & Outils' => 'fas fa-tools',
                        'Art & Design' => 'fas fa-paint-brush',
                        'Business & Marketing' => 'fas fa-chart-line',
                        'Santé & Bien-être' => 'fas fa-heartbeat',
                        'Autre' => 'fas fa-ellipsis-h',
                    ];
                    ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="category-item <?php echo e(old('categorie_id') == $categorie->id ? 'selected' : ''); ?>">
                        <input type="radio" name="categorie_id" value="<?php echo e($categorie->id); ?>"
                               <?php echo e(old('categorie_id') == $categorie->id ? 'checked' : ''); ?>

                               style="display:none;">
                        <i class="<?php echo e($icons[$categorie->nom] ?? 'fas fa-tag'); ?>"></i>
                        <span><?php echo e($categorie->nom); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['categorie_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="est_publie" value="1" id="est_publie_check"
                           <?php echo e(old('est_publie') ? 'checked' : ''); ?>>
                    <label class="form-check-label fw-semibold" for="est_publie_check">Publier immédiatement</label>
                </div>
            </div>

            
            <div class="wizard-nav">
                <div></div>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-informations">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez renseigner le nom du produit et choisir une catégorie.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Tarification &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        
        <div class="section-block" id="section-tarification">
            <div class="section-title">Tarification</div>
            <div class="section-subtitle">Définissez le mode de distribution de votre produit</div>

            
            <div class="mb-4">
                <div class="row g-3" style="max-width:560px;">
                    
                    <div class="col-6">
                        <label class="type-card <?php echo e(old('type','payant') === 'payant' ? 'selected' : ''); ?>" id="card-payant">
                            <input type="radio" name="type" value="payant"
                                   <?php echo e(old('type','payant') === 'payant' ? 'checked' : ''); ?>

                                   onchange="switchType('payant')" style="display:none;">
                            <div class="type-card-icon" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="type-card-label">Payant</div>
                            <div class="type-card-sub">Le client paie pour télécharger</div>
                        </label>
                    </div>
                    
                    <div class="col-6">
                        <label class="type-card <?php echo e(old('type') === 'gratuit' ? 'selected' : ''); ?>" id="card-gratuit">
                            <input type="radio" name="type" value="gratuit"
                                   <?php echo e(old('type') === 'gratuit' ? 'checked' : ''); ?>

                                   onchange="switchType('gratuit')" style="display:none;">
                            <div class="type-card-icon" style="background:linear-gradient(135deg,#16a34a,#15803d);">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="type-card-label">Gratuit <span class="badge bg-success ms-1" style="font-size:0.6rem;">Lead Magnet</span></div>
                            <div class="type-card-sub">Capture l'email du prospect</div>
                        </label>
                    </div>
                </div>
            </div>

            
            <div id="bloc-payant" style="<?php echo e(old('type') === 'gratuit' ? 'display:none;' : ''); ?>">
                <div class="mb-4" style="max-width:300px;">
                    <label class="form-label">Prix *</label>
                    <div class="price-input-wrapper">
                        <input type="number" step="1" id="prix-input"
                               class="form-control <?php $__errorArgs = ['prix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               name="prix" placeholder="0" value="<?php echo e(old('prix')); ?>">
                        <span class="currency">FCFA</span>
                    </div>
                    <?php $__errorArgs = ['prix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div id="bloc-gratuit" style="<?php echo e(old('type') !== 'gratuit' ? 'display:none;' : ''); ?>">

                
                <div class="alert alert-success border-0 rounded-3 mb-4" style="background:#f0fdf4;">
                    <div class="d-flex gap-2">
                        <span style="font-size:1.4rem;">🎁</span>
                        <div>
                            <strong>Lead Magnet activé</strong><br>
                            <small class="text-muted">
                                Le client remplit un formulaire → reçoit le fichier gratuitement → vous gagnez un prospect qualifié.
                                La page de remerciement affichera automatiquement vos upsells payants.
                            </small>
                        </div>
                    </div>
                </div>

                
                <div class="mb-4">
                    <label class="form-label">Champs à collecter <span class="text-muted fw-normal">(Nom + Email toujours requis)</span></label>
                    <div class="row g-2">
                        <?php $__currentLoopData = \App\Models\Produit::CHAMPS_LEAD_DISPONIBLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $champ => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6">
                            <label class="champ-toggle <?php echo e(in_array($champ, old('lead_champs_requis', [])) ? 'active' : ''); ?>">
                                <input type="checkbox" name="lead_champs_requis[]" value="<?php echo e($champ); ?>"
                                       <?php echo e(in_array($champ, old('lead_champs_requis', [])) ? 'checked' : ''); ?>>
                                <i class="fas fa-<?php echo e($champ === 'telephone' ? 'phone' : ($champ === 'ville' ? 'map-marker-alt' : ($champ === 'profession' ? 'briefcase' : 'globe'))); ?> me-2"></i>
                                <?php echo e($label); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="mb-4">
                    <label class="form-label">Limite de téléchargements <span class="text-muted fw-normal">(optionnel — crée de la rareté)</span></label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggle-limite"
                                   onchange="toggleLimite(this)" <?php echo e(old('lead_limite_dl') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="toggle-limite">Activer une limite</label>
                        </div>
                    </div>
                    <div id="bloc-limite" class="mt-3" style="<?php echo e(old('lead_limite_dl') ? '' : 'display:none;'); ?>">
                        <div class="input-group" style="max-width:250px;">
                            <input type="number" name="lead_limite_dl" min="1"
                                   class="form-control rounded-start-3"
                                   placeholder="Ex: 200"
                                   value="<?php echo e(old('lead_limite_dl')); ?>">
                            <span class="input-group-text">téléchargements</span>
                        </div>
                        <div class="form-text">Exemple : "200 téléchargements seulement" — après ça le produit se ferme automatiquement.</div>
                    </div>
                </div>

                
                <input type="hidden" name="prix" value="0">
            </div>

            
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-tarification">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez entrer un prix supérieur à 0 FCFA.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Fichiers &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        
        <div class="section-block" id="section-fichiers">
            <div class="section-title">Fichiers</div>
            <div class="section-subtitle">Le fichier numérique que vos clients recevront</div>

            <div class="mb-4">
                <label class="form-label">Fichier numérique (PDF, ZIP, MP4...)</label>
                <div class="image-upload-area" onclick="document.getElementById('fichier-input').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <div class="fw-semibold">Cliquez pour téléverser</div>
                    <div class="text-muted small mt-1">PDF, ZIP, MP3, MP4 — Max 100MB</div>
                    <input type="file" id="fichier-input" name="fichier"
                           accept=".pdf,.zip,.mp3,.mp4" class="d-none"
                           onchange="showFileName(this)">
                </div>
                <div id="fichier-name" class="mt-2 text-success small d-none">
                    <i class="fas fa-check-circle me-1"></i> <span></span>
                </div>
                <?php $__errorArgs = ['fichier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-fichiers">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez sélectionner un fichier numérique à téléverser.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Description &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        
        <div class="section-block" id="section-description">
            <div class="section-title">Description</div>
            <div class="section-subtitle">Décrivez votre produit pour convaincre vos clients</div>

            <div class="mb-4">
                <label class="form-label">Description *</label>
                <div id="quill-editor"></div>
                <textarea name="description" id="description-hidden" class="d-none"><?php echo e(old('description')); ?></textarea>
            </div>

            
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-description">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez écrire une description d'au moins 20 caractères.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Visuel &amp; Design &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        
        <div class="section-block" id="section-visuel">
            <div class="section-title">Visuel & Design</div>
            <div class="section-subtitle">L'image de couverture de votre produit</div>

            <div class="mb-4">
                <label class="form-label">Image de couverture</label>
                <div class="image-upload-area" onclick="document.getElementById('image-input').click()">
                    <div id="upload-placeholder">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <div class="fw-semibold">Cliquez pour téléverser une image</div>
                        <div class="text-muted small mt-1">JPG, PNG, WEBP — Recommandé: 1200×800px</div>
                    </div>
                    <div class="image-preview" id="image-preview">
                        <img id="preview-img" src="" alt="Aperçu">
                    </div>
                    <input type="file" id="image-input" name="image"
                           accept="image/*" class="d-none"
                           onchange="previewImage(this)">
                </div>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="wizard-nav">
                <button type="button" class="btn-wizard-prev" onclick="prevSection()">
                    <i class="fas fa-arrow-left me-1"></i> Précédent
                </button>
                <div class="wizard-nav-right">
                    <div class="wizard-error-msg" id="error-visuel">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Veuillez téléverser une image de couverture.
                    </div>
                    <button type="button" class="btn-wizard-next" onclick="nextSection()">
                        Enregistrer &nbsp;<i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════
       WIZARD – configuration
    ═══════════════════════════════════════════════ */
    var SECTIONS = ['informations','tarification','fichiers','description','visuel'];
    var REQUIRED  = ['informations','tarification','fichiers','description','visuel'];
    var currentSection = 'informations';
    var sectionStatus  = {};           // 'pending' | 'valid' | 'invalid'
    SECTIONS.forEach(function(s){ sectionStatus[s] = 'pending'; });

    /* ── validation rules ── */
    function validateSection(section) {
        if (section === 'informations') {
            var nom = (document.getElementById('nom-input') || {}).value || '';
            var catOk = !!document.querySelector('input[name="categorie_id"]:checked');
            return nom.trim().length > 0 && catOk;
        }
        if (section === 'tarification') {
            var type = (document.querySelector('input[name="type"]:checked') || {}).value || 'payant';
            if (type === 'gratuit') return true;
            var prix = parseFloat((document.getElementById('prix-input') || {}).value);
            return !isNaN(prix) && prix > 0;
        }
        if (section === 'fichiers') {
            var fi = document.getElementById('fichier-input');
            return fi && fi.files.length > 0;
        }
        if (section === 'description') {
            var txt = (document.getElementById('description-hidden') || {}).value || '';
            // strip HTML tags and check there's actual text
            var plain = txt.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, '').trim();
            return plain.length >= 20;
        }
        if (section === 'visuel') {
            var img = document.getElementById('image-input');
            return img && img.files.length > 0;
        }
        return true;
    }

    /* ── show/hide section ── */
    function showSection(target) {
        currentSection = target;
        document.querySelectorAll('.sidebar-nav a').forEach(function(l){ l.classList.remove('active'); });
        var navEl = document.getElementById('nav-' + target);
        if (navEl) navEl.classList.add('active');
        document.querySelectorAll('.section-block').forEach(function(s){ s.classList.remove('active'); });
        var sectionEl = document.getElementById('section-' + target);
        if (sectionEl) sectionEl.classList.add('active');
        // scroll top on mobile
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ── clear error states ── */
    function clearErrors(section) {
        var errEl = document.getElementById('error-' + section);
        if (errEl) errEl.classList.remove('show');
        if (section === 'informations') {
            var nomEl = document.getElementById('nom-input');
            if (nomEl) nomEl.classList.remove('is-invalid-wizard');
            var grid = document.querySelector('.category-grid');
            if (grid) grid.classList.remove('grid-invalid');
        }
        if (section === 'tarification') {
            var prixEl = document.getElementById('prix-input');
            if (prixEl) prixEl.classList.remove('is-invalid-wizard');
        }
        if (section === 'fichiers') {
            var area = document.querySelector('#section-fichiers .image-upload-area');
            if (area) area.classList.remove('upload-invalid');
        }
        if (section === 'description') {
            var qlContainer = document.querySelector('.ql-container');
            if (qlContainer) qlContainer.classList.remove('is-invalid-wizard');
        }
        if (section === 'visuel') {
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.remove('upload-invalid');
        }
    }

    /* ── highlight missing fields ── */
    function showErrors(section) {
        var errEl = document.getElementById('error-' + section);
        if (errEl) errEl.classList.add('show');
        if (section === 'informations') {
            var nom = document.getElementById('nom-input');
            if (nom && !nom.value.trim()) { nom.classList.add('is-invalid-wizard'); nom.focus(); }
            if (!document.querySelector('input[name="categorie_id"]:checked')) {
                var grid = document.querySelector('.category-grid');
                if (grid) grid.classList.add('grid-invalid');
            }
        }
        if (section === 'tarification') {
            var type = (document.querySelector('input[name="type"]:checked') || {}).value || 'payant';
            if (type === 'payant') {
                var prixEl = document.getElementById('prix-input');
                if (prixEl) { prixEl.classList.add('is-invalid-wizard'); prixEl.focus(); }
            }
        }
        if (section === 'fichiers') {
            var area = document.querySelector('#section-fichiers .image-upload-area');
            if (area) area.classList.add('upload-invalid');
        }
        if (section === 'description') {
            var qlContainer = document.querySelector('.ql-container');
            if (qlContainer) { qlContainer.classList.add('is-invalid-wizard'); quill.focus(); }
        }
        if (section === 'visuel') {
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.add('upload-invalid');
        }
    }

    /* ── progress bar & badges ── */
    function updateProgress() {
        var valid = 0;
        REQUIRED.forEach(function(s) {
            var status = sectionStatus[s];
            var badge  = document.getElementById('badge-' + s);
            var icon   = document.getElementById('icon-' + s);
            if (status === 'valid') {
                valid++;
                if (badge) { badge.textContent = '✓'; badge.style.display = ''; badge.className = 'step-badge valid'; }
                if (icon)  { icon.className = 'step-icon valid'; }
            } else if (status === 'invalid') {
                if (badge) { badge.textContent = '!'; badge.style.display = ''; badge.className = 'step-badge invalid'; }
                if (icon)  { icon.className = 'step-icon invalid'; }
            } else {
                if (badge) { badge.style.display = 'none'; }
                if (icon)  { icon.className = 'step-icon'; }
            }
        });
        var pct = Math.round((valid / REQUIRED.length) * 100);
        var bar = document.getElementById('progress-bar');
        if (bar) bar.style.width = pct + '%';
        var lbl = document.getElementById('progress-label');
        if (lbl) lbl.textContent = valid + ' / ' + REQUIRED.length + ' requis';

        // enable/disable Publier
        var btnPublish = document.getElementById('btn-publish');
        if (btnPublish) {
            var allValid = REQUIRED.every(function(s){ return sectionStatus[s] === 'valid'; });
            btnPublish.disabled = !allValid;
            btnPublish.title    = allValid ? '' : 'Complétez les 5 sections requises pour publier';
        }
    }

    /* ── public nav functions ── */
    window.nextSection = function () {
        var idx = SECTIONS.indexOf(currentSection);
        clearErrors(currentSection);

        if (REQUIRED.indexOf(currentSection) !== -1) {
            var ok = validateSection(currentSection);
            sectionStatus[currentSection] = ok ? 'valid' : 'invalid';
            updateProgress();
            if (!ok) { showErrors(currentSection); return; }
        }
        if (idx < SECTIONS.length - 1) showSection(SECTIONS[idx + 1]);
    };

    window.prevSection = function () {
        var idx = SECTIONS.indexOf(currentSection);
        if (idx > 0) showSection(SECTIONS[idx - 1]);
    };

    /* ── sidebar nav click ── */
    document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = this.dataset.section;
            // Save current required status before leaving
            if (REQUIRED.indexOf(currentSection) !== -1 && sectionStatus[currentSection] === 'pending') {
                if (validateSection(currentSection)) {
                    sectionStatus[currentSection] = 'valid';
                    clearErrors(currentSection);
                    updateProgress();
                }
            }
            showSection(target);
        });
    });

    /* ── category selection ── */
    document.querySelectorAll('.category-item').forEach(function(item) {
        item.addEventListener('click', function() {
            document.querySelectorAll('.category-item').forEach(function(i){ i.classList.remove('selected'); });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            // clear error if was invalid
            var grid = document.querySelector('.category-grid');
            if (grid) grid.classList.remove('grid-invalid');
            // live recheck
            if (currentSection === 'informations' && sectionStatus['informations'] === 'invalid') {
                if (validateSection('informations')) {
                    sectionStatus['informations'] = 'valid';
                    clearErrors('informations');
                    updateProgress();
                }
            }
        });
    });

    /* ── nom input live recheck ── */
    var nomInput = document.getElementById('nom-input');
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            document.getElementById('sidebar-title').textContent = this.value || 'Sans titre';
            this.classList.remove('is-invalid-wizard');
            if (sectionStatus['informations'] === 'invalid' && validateSection('informations')) {
                sectionStatus['informations'] = 'valid';
                clearErrors('informations');
                updateProgress();
            }
        });
    }

    /* ── prix input live recheck ── */
    var prixInput = document.getElementById('prix-input');
    if (prixInput) {
        prixInput.addEventListener('input', function() {
            this.classList.remove('is-invalid-wizard');
            if (sectionStatus['tarification'] === 'invalid' && validateSection('tarification')) {
                sectionStatus['tarification'] = 'valid';
                clearErrors('tarification');
                updateProgress();
            }
        });
    }

    /* ── fichier input live recheck ── */
    var fichierInput = document.getElementById('fichier-input');
    if (fichierInput) {
        fichierInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                var area = document.querySelector('#section-fichiers .image-upload-area');
                if (area) area.classList.remove('upload-invalid');
                sectionStatus['fichiers'] = 'valid';
                clearErrors('fichiers');
                updateProgress();
            }
            // show file name
            if (this.files && this.files[0]) {
                var el = document.getElementById('fichier-name');
                if (el) { el.querySelector('span').textContent = this.files[0].name; el.classList.remove('d-none'); }
            }
        });
    }

    /* ── champ toggle checkboxes ── */
    document.querySelectorAll('.champ-toggle').forEach(function(label) {
        label.addEventListener('click', function() {
            var cb = this.querySelector('input[type="checkbox"]');
            cb.checked = !cb.checked;
            this.classList.toggle('active', cb.checked);
        });
    });

    /* ── initialize ── */
    updateProgress();

    /* ═══════════════════════════════════════════════
       QUILL EDITOR
    ═══════════════════════════════════════════════ */
    function imageHandler() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        input.onchange = function() {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', e.target.result);
                quill.setSelection(range.index + 1);
            };
            reader.readAsDataURL(file);
        };
    }

    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Décrivez votre produit ici...',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['blockquote'],
                    ['clean']
                ],
                handlers: { image: imageHandler }
            }
        }
    });

    quill.on('text-change', function() {
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        // live recheck
        var qlC = document.querySelector('.ql-container');
        if (qlC) qlC.classList.remove('is-invalid-wizard');
        if (sectionStatus['description'] === 'invalid' && validateSection('description')) {
            sectionStatus['description'] = 'valid';
            clearErrors('description');
            updateProgress();
        }
    });

    var existing = document.getElementById('description-hidden').value;
    if (existing) quill.root.innerHTML = existing;

    /* ═══════════════════════════════════════════════
       IMAGE & FILE HELPERS
    ═══════════════════════════════════════════════ */
    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
            // live recheck
            var imgArea = document.querySelector('#section-visuel .image-upload-area');
            if (imgArea) imgArea.classList.remove('upload-invalid');
            sectionStatus['visuel'] = 'valid';
            clearErrors('visuel');
            updateProgress();
        }
    };

    window.showFileName = function(input) {
        if (input.files && input.files[0]) {
            var el = document.getElementById('fichier-name');
            if (el) { el.querySelector('span').textContent = input.files[0].name; el.classList.remove('d-none'); }
        }
    };

    /* ═══════════════════════════════════════════════
       TYPE PAYANT / GRATUIT
    ═══════════════════════════════════════════════ */
    window.switchType = function(type) {
        document.querySelectorAll('.type-card').forEach(function(c){ c.classList.remove('selected'); });
        document.getElementById('card-' + type).classList.add('selected');
        if (type === 'gratuit') {
            document.getElementById('bloc-payant').style.display = 'none';
            document.getElementById('bloc-gratuit').style.display = '';
            document.getElementById('prix-input').removeAttribute('required');
            // gratuit → tarification always valid
            sectionStatus['tarification'] = 'valid';
            updateProgress();
        } else {
            document.getElementById('bloc-payant').style.display = '';
            document.getElementById('bloc-gratuit').style.display = 'none';
            document.getElementById('prix-input').setAttribute('required', 'required');
            // re-evaluate
            if (sectionStatus['tarification'] !== 'pending') {
                sectionStatus['tarification'] = validateSection('tarification') ? 'valid' : 'invalid';
                updateProgress();
            }
        }
    };

    /* ── Limite de téléchargements ── */
    window.toggleLimite = function(checkbox) {
        document.getElementById('bloc-limite').style.display = checkbox.checked ? '' : 'none';
    };

    /* ═══════════════════════════════════════════════
       SAVE / PUBLISH
    ═══════════════════════════════════════════════ */
    window.saveDraft = function() {
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        document.getElementById('est_publie_check').checked = false;
        document.getElementById('produit-form').submit();
    };

    window.publish = function() {
        // Final validation check
        var missing = [];
        REQUIRED.forEach(function(s){
            if (!validateSection(s)) {
                sectionStatus[s] = 'invalid';
                missing.push(s);
            } else {
                sectionStatus[s] = 'valid';
            }
        });
        updateProgress();
        if (missing.length > 0) {
            showSection(missing[0]);
            showErrors(missing[0]);
            return;
        }
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        document.getElementById('est_publie_check').checked = true;
        document.getElementById('produit-form').submit();
    };

})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digital-store\resources\views/admin/produits/create.blade.php ENDPATH**/ ?>