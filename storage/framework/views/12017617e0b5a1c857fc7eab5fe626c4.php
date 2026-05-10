
<a href="<?php echo e(route('admin.produits.edit', $produit)); ?>">
    <i class="fas fa-edit" style="color:#3b82f6;width:16px;"></i> Modifier
</a>


<a href="<?php echo e(url('/boutique/produits/' . $produit->slug)); ?>" target="_blank">
    <i class="fas fa-eye" style="color:#10b981;width:16px;"></i> Voir la page
</a>


<a href="#" onclick="copyLink('<?php echo e(url('/boutique/produits/' . $produit->slug)); ?>'); return false;">
    <i class="fas fa-link" style="color:#06b6d4;width:16px;"></i> Copier le lien
</a>

<hr class="menu-divider">


<a href="<?php echo e(route('admin.produits.upsells.index', $produit)); ?>">
    <i class="fas fa-fire" style="color:#f97316;width:16px;"></i> Upsells
</a>

<hr class="menu-divider">


<button onclick="togglePublier(<?php echo e($produit->id); ?>)">
    <?php if($produit->est_publie): ?>
        <i class="fas fa-eye-slash" style="color:#f59e0b;width:16px;"></i> Dépublier
    <?php else: ?>
        <i class="fas fa-check-circle" style="color:#22c55e;width:16px;"></i> Publier
    <?php endif; ?>
</button>

<hr class="menu-divider">


<button class="danger" onclick="confirmDelete(<?php echo e($produit->id); ?>, '<?php echo e(addslashes($produit->nom)); ?>')">
    <i class="fas fa-trash" style="width:16px;"></i> Supprimer
</button>
<?php /**PATH C:\laragon\www\digital-store\resources\views/admin/produits/_menu_actions.blade.php ENDPATH**/ ?>