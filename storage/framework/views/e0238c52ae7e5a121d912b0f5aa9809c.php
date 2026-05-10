<?php $__env->startSection('title', 'Produits'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Layout ── */
.produits-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.produits-header h1 { margin:0; font-size:1.4rem; font-weight:800; }

/* ── Filtres ── */
.filter-card { background:white; border-radius:16px; border:1px solid #f1f5f9; padding:1rem 1.25rem; margin-bottom:1.5rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); }

/* ── Cards produits (mobile) ── */
.produit-card {
    background:white; border-radius:16px; border:1px solid #f1f5f9;
    box-shadow:0 2px 8px rgba(0,0,0,0.05); overflow:visible;
    transition:box-shadow .2s; position:relative;
}
.produit-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.09); }
.produit-img { width:56px; height:56px; border-radius:12px; object-fit:cover; flex-shrink:0; }
.produit-img-placeholder { width:56px; height:56px; border-radius:12px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

/* ── Table desktop ── */
.table-produits { border-collapse:separate; border-spacing:0; }
.table-produits thead th { background:#f8fafc; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#64748b; border-bottom:1px solid #e2e8f0; padding:.85rem 1rem; }
.table-produits tbody tr { transition:background .15s; }
.table-produits tbody tr:hover { background:#fafbff; }
.table-produits tbody td { padding:.85rem 1rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.table-produits tbody tr:last-child td { border-bottom:none; }

/* ── Dropdown fix ── */
.action-menu { position:relative; }
.action-btn {
    width:34px; height:34px; border-radius:8px; border:1px solid #e2e8f0;
    background:white; display:flex; align-items:center; justify-content:center;
    cursor:pointer; color:#64748b; transition:all .15s; padding:0;
}
.action-btn:hover { background:#f1f5f9; border-color:#cbd5e1; color:#0f172a; }
.action-dropdown {
    position:fixed; background:white; border-radius:14px; border:1px solid #e2e8f0;
    box-shadow:0 12px 40px rgba(0,0,0,0.15); min-width:200px; z-index:99999;
    display:none; padding:.4rem 0; animation:dropFade .15s ease;
}
.action-dropdown.open { display:block; }
@keyframes dropFade { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.action-dropdown a, .action-dropdown button {
    display:flex; align-items:center; gap:10px; padding:.55rem 1rem;
    color:#374151; font-size:.85rem; font-weight:500; text-decoration:none;
    background:none; border:none; width:100%; text-align:left; cursor:pointer;
    transition:background .12s;
}
.action-dropdown a:hover, .action-dropdown button:hover { background:#f8fafc; color:#0f172a; }
.action-dropdown .menu-divider { border:none; border-top:1px solid #f1f5f9; margin:.3rem 0; }
.action-dropdown .danger:hover { background:#fef2f2; color:#dc2626; }

/* ── Statut badges ── */
.badge-publie { background:#dcfce7; color:#16a34a; font-size:.72rem; font-weight:700; padding:4px 10px; border-radius:20px; }
.badge-brouillon { background:#f1f5f9; color:#64748b; font-size:.72rem; font-weight:700; padding:4px 10px; border-radius:20px; }
.badge-type { background:#ede9fe; color:#7c3aed; font-size:.7rem; font-weight:600; padding:2px 8px; border-radius:10px; }
.badge-type.gratuit { background:#fef9c3; color:#ca8a04; }

/* ── Responsive ── */
@media(max-width:767px) {
    .desktop-only { display:none !important; }
    .mobile-list { display:flex !important; flex-direction:column; gap:.75rem; }
}
@media(min-width:768px) {
    .mobile-list { display:none !important; }
    .desktop-table { display:block !important; }
}
.desktop-table { display:none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="produits-header">
    <h1>🛍️ Produits</h1>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo e(route('admin.exports.produits')); ?>" class="btn btn-outline-success btn-sm px-3" style="border-radius:10px;">
            <i class="fas fa-file-csv me-1"></i> <span class="d-none d-sm-inline">Exporter</span>
        </a>
        <a href="<?php echo e(route('admin.produits.create')); ?>" class="btn btn-primary btn-sm px-3" style="border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;">
            <i class="fas fa-plus me-1"></i> Nouveau produit
        </a>
    </div>
</div>


<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;">
    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;">
    <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<div class="filter-card">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0" style="border-radius:10px 0 0 10px;">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-0 bg-light" name="recherche"
                       placeholder="Rechercher un produit..." value="<?php echo e(request('recherche')); ?>"
                       style="border-radius:0 10px 10px 0;">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <select class="form-select form-select-sm" name="categorie" style="border-radius:10px;border:1.5px solid #e2e8f0;">
                <option value="">Toutes les catégories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('categorie') == $cat->id ? 'selected' : ''); ?>>
                        <?php echo e($cat->nom); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1" style="border-radius:10px;">
                <i class="fas fa-filter me-1"></i> Filtrer
            </button>
            <?php if(request()->hasAny(['recherche','categorie'])): ?>
            <a href="<?php echo e(route('admin.produits.index')); ?>" class="btn btn-outline-secondary btn-sm" style="border-radius:10px;">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>


<div class="mobile-list">
    <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="produit-card p-3">
        <div class="d-flex align-items-center gap-3">
            
            <?php if($produit->image): ?>
                <img src="<?php echo e(asset('storage/'.$produit->image)); ?>" alt="<?php echo e($produit->nom); ?>" class="produit-img">
            <?php else: ?>
                <div class="produit-img-placeholder">
                    <i class="fas fa-box text-muted" style="font-size:1.2rem;"></i>
                </div>
            <?php endif; ?>

            
            <div class="flex-grow-1 min-width-0">
                <div class="fw-bold text-dark" style="font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?php echo e($produit->nom); ?>

                </div>
                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                    <span class="<?php echo e($produit->est_publie ? 'badge-publie' : 'badge-brouillon'); ?>">
                        <?php echo e($produit->est_publie ? '● Publié' : '○ Brouillon'); ?>

                    </span>
                    <span class="badge-type <?php echo e($produit->type === 'gratuit' ? 'gratuit' : ''); ?>">
                        <?php echo e($produit->type === 'gratuit' ? 'Gratuit' : number_format($produit->prix,0,',',' ').' FCFA'); ?>

                    </span>
                </div>
                <div class="text-muted mt-1" style="font-size:.75rem;">
                    <i class="fas fa-shopping-cart me-1"></i><?php echo e($produit->achats_count ?? 0); ?> vente(s)
                </div>
            </div>

            
            <div class="action-menu">
                <button class="action-btn" onclick="toggleMenu(this)">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="action-dropdown">
                    <?php echo $__env->make('admin.produits._menu_actions', ['produit' => $produit], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5 text-muted">
        <i class="fas fa-box-open fa-3x mb-3 d-block" style="opacity:.3;"></i>
        Aucun produit trouvé.
        <br><a href="<?php echo e(route('admin.produits.create')); ?>" class="btn btn-primary btn-sm mt-3" style="border-radius:10px;">Créer un produit</a>
    </div>
    <?php endif; ?>
</div>


<div class="desktop-table">
    <div style="background:white;border-radius:16px;border:1px solid #f1f5f9;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:visible;">
        <table class="table table-produits mb-0 w-100">
            <thead>
                <tr>
                    <th style="width:56px;border-radius:16px 0 0 0;"></th>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Ventes</th>
                    <th>Statut</th>
                    <th class="text-end pe-3" style="border-radius:0 16px 0 0;width:60px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="padding-left:1.25rem;">
                        <?php if($produit->image): ?>
                            <img src="<?php echo e(asset('storage/'.$produit->image)); ?>"
                                 alt="<?php echo e($produit->nom); ?>"
                                 style="width:44px;height:44px;border-radius:10px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:44px;height:44px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-box text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-semibold" style="color:#0f172a;"><?php echo e($produit->nom); ?></div>
                        <div style="font-size:.72rem;color:#94a3b8;"><?php echo e($produit->slug); ?></div>
                    </td>
                    <td style="color:#64748b;font-size:.85rem;"><?php echo e($produit->categorie->nom ?? '—'); ?></td>
                    <td>
                        <span class="badge-type <?php echo e($produit->type === 'gratuit' ? 'gratuit' : ''); ?>">
                            <?php echo e($produit->type === 'gratuit' ? '🎁 Gratuit' : number_format($produit->prix,0,',',' ').' FCFA'); ?>

                        </span>
                    </td>
                    <td>
                        <span style="font-size:.85rem;font-weight:600;color:#475569;">
                            <?php echo e($produit->achats_count ?? 0); ?>

                        </span>
                    </td>
                    <td>
                        <span class="<?php echo e($produit->est_publie ? 'badge-publie' : 'badge-brouillon'); ?>">
                            <?php echo e($produit->est_publie ? '● Publié' : '○ Brouillon'); ?>

                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="action-menu d-flex justify-content-end">
                            <button class="action-btn" onclick="toggleMenu(this)">
                                <i class="fas fa-ellipsis-v" style="font-size:.8rem;"></i>
                            </button>
                            <div class="action-dropdown">
                                <?php echo $__env->make('admin.produits._menu_actions', ['produit' => $produit], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 d-block" style="opacity:.3;"></i>
                        Aucun produit trouvé.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($produits->hasPages()): ?>
        <div class="p-3 border-top">
            <?php echo e($produits->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>


<div class="position-fixed bottom-0 end-0 p-3" style="z-index:99999;">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fas fa-check me-2"></i> Lien copié !</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>


<?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<form id="delete-form-<?php echo e($produit->id); ?>" action="<?php echo e(route('admin.produits.destroy', $produit)); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
</form>
<form id="toggle-form-<?php echo e($produit->id); ?>" action="<?php echo e(route('admin.produits.update', $produit)); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <input type="hidden" name="nom" value="<?php echo e($produit->nom); ?>">
    <input type="hidden" name="prix" value="<?php echo e($produit->prix); ?>">
    <input type="hidden" name="est_publie" value="<?php echo e($produit->est_publie ? 0 : 1); ?>">
</form>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    'use strict';
    var openMenu = null;

    window.toggleMenu = function(btn) {
        try {
            var container = btn.closest('.action-menu');
            if (!container) return;
            var menu = container.querySelector('.action-dropdown');
            if (!menu) return;

            if (openMenu && openMenu !== menu) closeAllMenus();

            if (menu.classList.contains('open')) {
                closeAllMenus();
                return;
            }
            positionMenu(btn, menu);
            menu.classList.add('open');
            openMenu = menu;
        } catch(e) { console.error('toggleMenu:', e); }
    };

    function positionMenu(btn, menu) {
        var rect = btn.getBoundingClientRect();
        menu.style.top   = (rect.bottom + 4) + 'px';
        menu.style.left  = 'auto';
        menu.style.right = Math.max(0, window.innerWidth - rect.right) + 'px';
    }

    function closeAllMenus() {
        document.querySelectorAll('.action-dropdown.open')
            .forEach(function(m) { m.classList.remove('open'); });
        openMenu = null;
    }

    // Fermer en cliquant ailleurs — ne bloque rien d'autre
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) closeAllMenus();
    }, true); // capture phase pour ne pas interférer avec Bootstrap

    // Repositionner au scroll
    window.addEventListener('scroll', function() {
        if (openMenu) {
            var btn = openMenu.closest('.action-menu').querySelector('.action-btn');
            if (btn) positionMenu(btn, openMenu);
        }
    }, { passive: true });

    window.copyLink = function(url) {
        navigator.clipboard.writeText(url).then(function() {
            closeAllMenus();
            try {
                new bootstrap.Toast(document.getElementById('copyToast')).show();
            } catch(e) {}
        }).catch(function() {
            prompt('Copiez ce lien :', url);
        });
    };

    window.confirmDelete = function(id, nom) {
        closeAllMenus();
        if (confirm('Supprimer "' + nom + '" ? Cette action est irréversible.')) {
            var f = document.getElementById('delete-form-' + id);
            if (f) f.submit();
            else console.error('Formulaire delete-form-' + id + ' introuvable');
        }
    };

    window.togglePublier = function(id) {
        closeAllMenus();
        var f = document.getElementById('toggle-form-' + id);
        if (f) f.submit();
        else console.error('Formulaire toggle-form-' + id + ' introuvable');
    };

})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digital-store\resources\views/admin/produits/index.blade.php ENDPATH**/ ?>