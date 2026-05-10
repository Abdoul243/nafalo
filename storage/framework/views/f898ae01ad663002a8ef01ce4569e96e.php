<?php $__env->startSection('title', 'Vérification d\'identité (KYC)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.kyc-step { background:white; border:1px solid #f1f5f9; border-radius:16px; padding:1.5rem; margin-bottom:1rem; }
.kyc-status-card { border-radius:16px; padding:1.5rem 2rem; margin-bottom:1.75rem; display:flex; align-items:center; gap:1.25rem; }
.status-icon { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; }
.doc-zone { border:2px dashed #e2e8f0; border-radius:14px; padding:1.5rem; text-align:center; cursor:pointer; transition:all 0.2s; position:relative; }
.doc-zone:hover { border-color:#2563eb; background:#f0f7ff; }
.doc-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.doc-zone i { font-size:2rem; color:#94a3b8; margin-bottom:0.5rem; display:block; }
.doc-zone .doc-label { font-size:0.85rem; color:#64748b; }
.doc-zone.has-file { border-color:#22c55e; background:#f0fdf4; }
.doc-zone.has-file i { color:#22c55e; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">🪪 Vérification d'identité (KYC)</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Vérifiez votre identité pour débloquer toutes les fonctionnalités de Nafalo</p>
    </div>
</div>


<?php
$statut = $kyc->statut ?? 'non_soumis';
$configs = [
    'approuve'   => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'iconBg' => '#dcfce7', 'iconColor' => '#16a34a', 'icon' => 'fas fa-shield-check', 'titre' => 'Identité vérifiée ✅', 'desc' => 'Votre dossier KYC a été approuvé. Vous bénéficiez de toutes les fonctionnalités Nafalo sans restriction.'],
    'en_attente' => ['bg' => '#fffbeb', 'border' => '#fde68a', 'iconBg' => '#fef9c3', 'iconColor' => '#d97706', 'icon' => 'fas fa-clock', 'titre' => 'Dossier en cours de vérification ⏳', 'desc' => 'Nous avons bien reçu votre dossier. Notre équipe le vérifie sous 24 à 48h ouvrées. Vous serez notifié par email.'],
    'rejete'     => ['bg' => '#fff1f2', 'border' => '#fecdd3', 'iconBg' => '#fee2e2', 'iconColor' => '#dc2626', 'icon' => 'fas fa-times-circle', 'titre' => 'Dossier rejeté ❌', 'desc' => 'Votre dossier a été rejeté. Veuillez soumettre un nouveau dossier avec des documents lisibles.'],
    'non_soumis' => ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'iconBg' => '#f1f5f9', 'iconColor' => '#64748b', 'icon' => 'fas fa-id-card', 'titre' => 'Identité non vérifiée', 'desc' => 'Soumettez vos documents d\'identité pour être vérifié et profiter de toutes les fonctionnalités.'],
];
$cfg = $configs[$statut];
?>

<div class="kyc-status-card" style="background:<?php echo e($cfg['bg']); ?>;border:1px solid <?php echo e($cfg['border']); ?>;">
    <div class="status-icon" style="background:<?php echo e($cfg['iconBg']); ?>;color:<?php echo e($cfg['iconColor']); ?>;">
        <i class="<?php echo e($cfg['icon']); ?>"></i>
    </div>
    <div>
        <div class="fw-bold" style="font-size:1rem;color:#0f172a;"><?php echo e($cfg['titre']); ?></div>
        <div class="text-muted mt-1" style="font-size:0.85rem;"><?php echo e($cfg['desc']); ?></div>
        <?php if($statut === 'rejete' && $kyc->note_admin): ?>
        <div class="mt-2 p-2 rounded" style="background:#fee2e2;color:#991b1b;font-size:0.82rem;">
            <strong>Motif :</strong> <?php echo e($kyc->note_admin); ?>

        </div>
        <?php endif; ?>
    </div>
</div>


<?php if($statut === 'non_soumis' || $statut === 'rejete'): ?>
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">🌟 Pourquoi vérifier mon identité ?</h6>
        <div class="row g-3">
            <?php $__currentLoopData = [
                ['fas fa-shield-alt', '#2563eb', '#eff6ff', 'Plateforme sécurisée', 'Protégez votre compte et vos revenus'],
                ['fas fa-money-bill-wave', '#16a34a', '#f0fdf4', 'Retraits sans limite', 'Accédez aux retraits de gains sans restriction'],
                ['fas fa-star', '#f59e0b', '#fffbeb', 'Badge vérifié', 'Inspirez confiance à vos clients'],
                ['fas fa-headset', '#8b5cf6', '#f5f3ff', 'Support prioritaire', 'Accédez au support client dédié'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $color, $bg, $titre, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6">
                <div class="d-flex gap-3 align-items-start">
                    <div style="width:36px;height:36px;border-radius:10px;background:<?php echo e($bg); ?>;color:<?php echo e($color); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="<?php echo e($icon); ?>"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.88rem;"><?php echo e($titre); ?></div>
                        <div class="text-muted" style="font-size:0.8rem;"><?php echo e($desc); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">📄 Soumettre votre dossier KYC</h5>
        <p class="text-muted mt-1 mb-0" style="font-size:0.83rem;">Documents acceptés : JPEG, PNG ou PDF — 5 Mo maximum par fichier</p>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.kyc.soumettre')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Type de document *</label>
                <div class="row g-2">
                    <?php $__currentLoopData = \App\Models\Kyc::TYPES_DOCUMENT; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="type_document" id="doc_<?php echo e($val); ?>" value="<?php echo e($val); ?>"
                            <?php echo e(old('type_document', $kyc->type_document ?? '') == $val ? 'checked' : ''); ?> required>
                        <label class="btn btn-outline-secondary w-100" for="doc_<?php echo e($val); ?>" style="border-radius:12px;padding:0.75rem;font-size:0.875rem;">
                            <?php if($val === 'cni'): ?> 🪪 <?php elseif($val === 'passeport'): ?> 📕 <?php else: ?> 🚗 <?php endif; ?>
                            <?php echo e($label); ?>

                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['type_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1" style="font-size:0.82rem;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Recto du document *</label>
                    <div class="doc-zone" id="zone-recto">
                        <input type="file" name="document_recto" accept=".jpg,.jpeg,.png,.pdf" onchange="previewDoc(this, 'zone-recto', 'preview-recto')">
                        <i class="fas fa-id-card-alt"></i>
                        <div class="doc-label">Glissez votre fichier ou cliquez</div>
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">JPG, PNG ou PDF · 5 Mo max</div>
                    </div>
                    <div id="preview-recto" style="font-size:0.78rem;color:#16a34a;margin-top:6px;"></div>
                    <?php $__errorArgs = ['document_recto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1" style="font-size:0.82rem;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Verso du document <span class="text-muted">(optionnel)</span></label>
                    <div class="doc-zone" id="zone-verso">
                        <input type="file" name="document_verso" accept=".jpg,.jpeg,.png,.pdf" onchange="previewDoc(this, 'zone-verso', 'preview-verso')">
                        <i class="fas fa-id-card"></i>
                        <div class="doc-label">Verso si applicable</div>
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">JPG, PNG ou PDF · 5 Mo max</div>
                    </div>
                    <div id="preview-verso" style="font-size:0.78rem;color:#16a34a;margin-top:6px;"></div>
                </div>
            </div>

            
            <div class="p-3 rounded mb-4" style="background:#f0f7ff;border:1px solid #bfdbfe;font-size:0.82rem;color:#1e40af;">
                <i class="fas fa-lock me-2"></i>
                Vos documents sont <strong>chiffrés et stockés de manière sécurisée</strong>. Ils ne sont accessibles qu'à l'équipe Nafalo pour vérification, conformément à notre <a href="<?php echo e(route('legal.confidentialite')); ?>" target="_blank" style="color:#2563eb;">politique de confidentialité</a>.
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i> Soumettre mon dossier KYC
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if($statut === 'approuve'): ?>
<div class="card">
    <div class="card-body text-center py-4">
        <div style="font-size:3rem;margin-bottom:0.5rem;">🎉</div>
        <h5 class="fw-bold">Vous êtes vérifié !</h5>
        <p class="text-muted">Votre identité a été confirmée avec succès. Profitez de toutes les fonctionnalités Nafalo.</p>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">
            <i class="fas fa-home me-2"></i> Retour au tableau de bord
        </a>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function previewDoc(input, zoneId, previewId) {
    const zone = document.getElementById(zoneId);
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        zone.classList.add('has-file');
        zone.querySelector('i').className = 'fas fa-check-circle';
        zone.querySelector('.doc-label').textContent = file.name;
        preview.textContent = '✅ ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' Mo)';
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digital-store\resources\views/admin/kyc/index.blade.php ENDPATH**/ ?>