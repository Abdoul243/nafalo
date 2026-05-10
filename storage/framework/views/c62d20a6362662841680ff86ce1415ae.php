

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ═══════════════════════════════════════════
       DASHBOARD — Mobile-First
    ═══════════════════════════════════════════ */
    .dashboard-greeting { margin-bottom: 1.5rem; }
    .dashboard-greeting h1 { font-size: 1.4rem; font-weight: 900; color: #111; margin-bottom: 0.25rem; }
    .dashboard-greeting p  { color: #888; font-size: 0.9rem; }

    /* Actions rapides */
    .quick-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .quick-btn {
        display: flex; align-items: center; gap: 7px;
        background: white; border: 1.5px solid #e5e5e5; border-radius: 12px;
        padding: 0.55rem 1rem; font-size: 0.82rem; font-weight: 600;
        color: #333; text-decoration: none; transition: all 0.2s; white-space: nowrap;
    }
    .quick-btn:hover { border-color: #333; color: #111; }

    /* ── Grilles ── */
    /* 4 stat cards */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);   /* mobile : 2 colonnes */
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    /* 3 KPI cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: 1fr;              /* mobile : 1 colonne */
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    /* Charts */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr;              /* mobile : 1 colonne */
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    /* Produits + Transactions */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr;              /* mobile : 1 colonne */
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Tablette ≥ 640px */
    @media (min-width: 640px) {
        .kpi-grid   { grid-template-columns: repeat(3, 1fr); }
        .charts-grid{ grid-template-columns: 2fr 1fr; }
        .content-grid { grid-template-columns: 1fr 1fr; }
        .dashboard-greeting h1 { font-size: 1.8rem; }
    }
    /* Desktop ≥ 1024px */
    @media (min-width: 1024px) {
        .stat-cards { grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.75rem; }
        .quick-actions { margin-bottom: 2rem; }
    }

    /* Cards */
    .stat-card {
        background: white; border-radius: 14px; padding: 1.1rem;
        border: 1px solid #f0f0f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: relative;
    }
    .stat-card-icon {
        width: 36px; height: 36px; border-radius: 10px; background: #f5f5f5;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 0.75rem; font-size: 1rem; color: #555;
    }
    .stat-card-value { font-size: 1.2rem; font-weight: 900; color: #111; line-height: 1; margin-bottom: 0.3rem; }
    .stat-card-label { font-size: 0.78rem; color: #999; font-weight: 500; }

    @media (min-width: 1024px) {
        .stat-card { padding: 1.6rem; }
        .stat-card-icon { width: 42px; height: 42px; margin-bottom: 1rem; }
        .stat-card-value { font-size: 1.6rem; }
        .stat-card-label { font-size: 0.85rem; }
    }

    /* KPI mini card */
    .kpi-card {
        background: white; border-radius: 14px; border: 1px solid #f0f0f0;
        padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.875rem;
    }
    .kpi-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .kpi-value { font-size: 1.3rem; font-weight: 900; color: #111; line-height: 1; }
    .kpi-label { font-size: 0.78rem; color: #999; margin-top: 2px; }

    /* White card */
    .white-card {
        background: white; border-radius: 16px; padding: 1.1rem;
        border: 1px solid #f0f0f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    @media (min-width: 640px) { .white-card { padding: 1.5rem; } }

    /* Section header */
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .section-header h3 { font-size: 0.95rem; font-weight: 800; color: #111; margin: 0; }
    .section-header p  { font-size: 0.75rem; color: #aaa; margin: 0; }
    .voir-tout {
        background: white; border: 1.5px solid #e5e5e5; border-radius: 9px;
        padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 600;
        color: #333; text-decoration: none; white-space: nowrap; transition: all 0.2s;
    }
    .voir-tout:hover { border-color: #333; color: #111; }

    /* Produit item */
    .top-produit-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f5f5f5; }
    .top-produit-item:last-child { border: none; }
    .top-produit-img { width: 38px; height: 38px; border-radius: 9px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .top-produit-img img { width: 100%; height: 100%; object-fit: cover; }
    .top-produit-info { flex: 1; min-width: 0; }
    .top-produit-nom { font-weight: 700; font-size: 0.82rem; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .top-produit-prix { font-size: 0.75rem; color: #aaa; }
    .top-produit-ventes { font-weight: 800; font-size: 0.82rem; color: #111; text-align: right; white-space: nowrap; }
    .top-produit-ventes span { font-size: 0.7rem; color: #aaa; font-weight: 400; }

    /* Transaction item */
    .transaction-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f5f5f5; }
    .transaction-item:last-child { border: none; }
    .transaction-icon { width: 34px; height: 34px; border-radius: 9px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }
    .transaction-info { flex: 1; min-width: 0; }
    .transaction-ref { font-weight: 700; font-size: 0.8rem; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .transaction-client { font-size: 0.75rem; color: #aaa; }
    .transaction-amount { font-weight: 800; font-size: 0.82rem; color: #111; white-space: nowrap; }


</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-greeting">
    <h1>Bonjour <?php echo e(Auth::user()->nom); ?> ! 👋</h1>
    <p>✨ Voici un aperçu de votre activité.</p>
</div>


<div class="quick-actions">
    <a href="<?php echo e(route('admin.produits.create')); ?>" class="quick-btn">
        <i class="fas fa-plus-circle"></i> Ajouter un produit
    </a>
    <a href="<?php echo e(route('admin.codes-promo.create')); ?>" class="quick-btn">
        <i class="fas fa-percent"></i> Créer une réduction
    </a>
    <a href="<?php echo e(route('admin.transactions.index')); ?>" class="quick-btn">
        <i class="fas fa-credit-card"></i> Voir les ventes
    </a>
    <div class="dropdown">
        <button class="quick-btn" data-bs-toggle="dropdown">
            <i class="fas fa-calendar-alt"></i> Période : <?php echo e($periode); ?>

            <i class="fas fa-chevron-down ms-1" style="font-size:0.7rem;"></i>
        </button>
        <ul class="dropdown-menu shadow border-0 rounded-3 mt-1">
            <li><a class="dropdown-item" href="?periode=7jours">7 derniers jours</a></li>
            <li><a class="dropdown-item" href="?periode=30jours">30 derniers jours</a></li>
            <li><a class="dropdown-item" href="?periode=12mois">12 derniers mois</a></li>
        </ul>
    </div>
</div>


<div class="stat-cards">
    <div class="stat-card" style="position:relative;overflow:hidden;">
        <div class="stat-card-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-card-value"><?php echo e(number_format($chiffreAffaires, 0, ',', ' ')); ?> FCFA</div>
        <div class="stat-card-label">CA brut total encaissé</div>
    </div>
    <div class="stat-card" style="border:1.5px solid #bbf7d0;background:linear-gradient(135deg,#f0fdf4,#f7fef9);">
        <div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-wallet"></i></div>
        <div class="stat-card-value" style="color:#15803d;"><?php echo e(number_format($gainsNets, 0, ',', ' ')); ?> FCFA</div>
        <div class="stat-card-label">Votre gain net</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-card-value"><?php echo e($totalVentes); ?></div>
        <div class="stat-card-label">Ventes totales</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-users"></i></div>
        <div class="stat-card-value"><?php echo e($totalClients); ?></div>
        <div class="stat-card-label">Clients <small style="font-size:0.65rem;color:#22c55e;font-weight:700;">+<?php echo e($nouveauxClients); ?> ce mois</small></div>
    </div>
    <div class="stat-card" style="border-left:3px solid #16a34a;">
        <div class="stat-card-icon" style="background:linear-gradient(135deg,#16a34a,#15803d);"><i class="fas fa-gift"></i></div>
        <div class="stat-card-value" style="color:#16a34a;"><?php echo e($totalLeads ?? 0); ?></div>
        <div class="stat-card-label">Leads <small style="font-size:0.65rem;color:#16a34a;font-weight:700;">Lead Magnet</small></div>
    </div>
</div>


<?php if(!empty($produitsLeadMagnet) && $produitsLeadMagnet->isNotEmpty()): ?>
<div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,#16a34a,#15803d);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-gift text-white"></i>
        </div>
        <div>
            <div class="fw-bold" style="color:#14532d;">🎁 Lead Magnets actifs</div>
            <div class="text-muted small">Vos produits gratuits qui capturent des prospects</div>
        </div>
        <a href="<?php echo e(route('admin.produits.create')); ?>" class="btn btn-sm ms-auto rounded-pill px-3" style="background:#16a34a;color:white;font-weight:600;">
            <i class="fas fa-plus me-1"></i> Nouveau
        </a>
    </div>
    <div class="row g-2">
        <?php $__currentLoopData = $produitsLeadMagnet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 col-6">
            <div style="background:white;border-radius:12px;padding:0.75rem 1rem;border:1px solid #bbf7d0;">
                <div class="fw-semibold small text-truncate"><?php echo e($lm->nom); ?></div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <span style="background:#dcfce7;color:#15803d;font-size:0.7rem;font-weight:700;padding:2px 8px;border-radius:10px;">
                        <?php echo e($lm->nb_leads); ?> lead<?php echo e($lm->nb_leads > 1 ? 's' : ''); ?>

                    </span>
                    <?php if($lm->lead_limite_dl): ?>
                    <span class="text-muted" style="font-size:0.7rem;"><?php echo e($lm->lead_compteur); ?>/<?php echo e($lm->lead_limite_dl); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#eff6ff;">
            <i class="fas fa-bullseye" style="color:#2563eb;"></i>
        </div>
        <div>
            <div class="kpi-value" style="color:#2563eb;"><?php echo e($tauxConversion); ?>%</div>
            <div class="kpi-label">Taux de conversion</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f0fdf4;">
            <i class="fas fa-box-open" style="color:#22c55e;"></i>
        </div>
        <div>
            <div class="kpi-value"><?php echo e($produitPublies); ?><span style="font-size:0.85rem;color:#999;font-weight:500;">/<?php echo e($totalProduits); ?></span></div>
            <div class="kpi-label">Produits publiés</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fff7ed;">
            <i class="fas fa-shopping-cart" style="color:#f97316;"></i>
        </div>
        <div>
            <div class="kpi-value"><?php echo e($paniersAbandonnes); ?></div>
            <div class="kpi-label">Paniers abandonnés</div>
        </div>
    </div>
</div>


<div class="charts-grid">
    <div class="white-card">
        <div class="section-header mb-3">
            <div>
                <h3>Évolution des ventes</h3>
                <p>Revenus générés sur la période</p>
            </div>
        </div>
        <canvas id="ventesChart" height="120"></canvas>
    </div>
    <div class="white-card">
        <div class="section-header mb-3">
            <div>
                <h3>Ventes par catégorie</h3>
                <p>Répartition en nombre de ventes</p>
            </div>
        </div>
        <?php if($ventesParCategorie->isEmpty()): ?>
            <div style="display:flex;align-items:center;justify-content:center;height:160px;color:#ccc;flex-direction:column;gap:8px;">
                <i class="fas fa-chart-pie" style="font-size:2rem;"></i>
                <span style="font-size:0.85rem;">Pas encore de ventes</span>
            </div>
        <?php else: ?>
            <canvas id="categorieChart" height="180"></canvas>
        <?php endif; ?>
    </div>
</div>


<div class="content-grid" style="margin-bottom:1.5rem;">
    
    <div class="white-card">
        <div class="section-header">
            <div>
                <h3>Produits les plus vendus</h3>
                <p>Basé sur le total des ventes</p>
            </div>
            <a href="<?php echo e(route('admin.produits.index')); ?>" class="voir-tout">Voir tout</a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $topProduits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="top-produit-item">
            <div class="top-produit-img">
                <?php if($produit->image): ?>
                    <img src="<?php echo e(asset('storage/' . $produit->image)); ?>" alt="<?php echo e($produit->nom); ?>">
                <?php else: ?>
                    <i class="fas fa-box text-muted" style="font-size:1rem;"></i>
                <?php endif; ?>
            </div>
            <div class="top-produit-info">
                <div class="top-produit-nom"><?php echo e($produit->nom); ?></div>
                <div class="top-produit-prix"><?php echo e(number_format($produit->prix, 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="top-produit-ventes">
                <?php echo e(number_format($produit->achats_count * $produit->prix, 0, ',', ' ')); ?> FCFA
                <br><span><?php echo e($produit->achats_count); ?> Ventes</span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted text-center py-3">Aucune vente pour le moment.</p>
        <?php endif; ?>
    </div>

    
    <div class="white-card">
        <div class="section-header">
            <div>
                <h3>Dernières transactions</h3>
                <p>Activité récente</p>
            </div>
            <a href="<?php echo e(route('admin.transactions.index')); ?>" class="voir-tout">Voir tout</a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $dernieresTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="transaction-item">
            <div class="transaction-icon">
                <?php if($transaction->statut === 'reussi'): ?>
                    <i class="fas fa-check text-success"></i>
                <?php elseif($transaction->statut === 'en_attente'): ?>
                    <i class="fas fa-clock text-warning"></i>
                <?php else: ?>
                    <i class="fas fa-times text-danger"></i>
                <?php endif; ?>
            </div>
            <div class="transaction-info">
                <div class="transaction-ref"><?php echo e($transaction->reference); ?></div>
                <div class="transaction-client"><?php echo e($transaction->client->nom ?? 'Anonyme'); ?></div>
            </div>
            <div class="text-end">
                <div class="transaction-amount"><?php echo e(number_format($transaction->montant_total, 0, ',', ' ')); ?> FCFA</div>
                <div>
                    <?php if($transaction->statut === 'reussi'): ?>
                        <span class="badge bg-success" style="font-size:0.7rem;">Réussi</span>
                    <?php elseif($transaction->statut === 'en_attente'): ?>
                        <span class="badge bg-warning" style="font-size:0.7rem;">En attente</span>
                    <?php else: ?>
                        <span class="badge bg-danger" style="font-size:0.7rem;">Échoué</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted text-center py-3">Aucune transaction pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Graphique évolution des ventes ──────────────────────────────────────
    const ventesData = <?php echo json_encode($ventesParJour, 15, 512) ?>;

    new Chart(document.getElementById('ventesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ventesData.map(i => i.date),
            datasets: [{
                label: 'Revenus (FCFA)',
                data: ventesData.map(i => i.total_montant),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.07)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + Number(ctx.raw).toLocaleString('fr-FR') + ' FCFA'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#aaa', font: { size: 11 } } },
                y: { grid: { color: '#f5f5f5' }, ticks: { color: '#aaa', font: { size: 11 } }, beginAtZero: true }
            }
        }
    });

    // ── Graphique donut ventes par catégorie ────────────────────────────────
    const catData = <?php echo json_encode($ventesParCategorie, 15, 512) ?>;
    const catCtx  = document.getElementById('categorieChart');
    if (catCtx && catData.length) {
        const palette = ['#2563eb','#22c55e','#f97316','#a855f7','#ef4444','#06b6d4'];
        new Chart(catCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.nom),
                datasets: [{
                    data: catData.map(c => c.nb_ventes),
                    backgroundColor: palette.slice(0, catData.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 12, color: '#555' }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' ' + ctx.label + ' : ' + ctx.raw + ' vente(s)'
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digital-store\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>