@extends('layouts.boutique')

@section('title', 'Paiement en cours...')

@push('styles')
<style>
.attente-page {
    max-width: 560px;
    margin: 0 auto;
    padding: 4rem 1.25rem 5rem;
    text-align: center;
}

/* Spinner animé */
.spinner-ring {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 5px solid rgba(124,58,237,0.15);
    border-top-color: #7c3aed;
    animation: spin 1s linear infinite;
    margin: 0 auto 2rem;
}
@keyframes spin { to { transform: rotate(360deg); } }

.attente-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
}
.attente-icon.success { background: rgba(34,197,94,0.15); color: #22c55e; animation: pulse-success 1s ease-out; }
.attente-icon.failed  { background: rgba(239,68,68,0.15); color: #ef4444; }
@keyframes pulse-success { 0% { transform: scale(0.5); opacity: 0; } 70% { transform: scale(1.1); } 100% { transform: scale(1); opacity: 1; } }

.attente-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.75rem;
}
.attente-subtitle {
    font-size: 0.95rem;
    color: var(--text-2);
    line-height: 1.6;
    margin-bottom: 2rem;
}

/* Card d'état */
.attente-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 2rem;
    margin-bottom: 2rem;
}

/* Steps Wave */
.wave-steps { display: flex; flex-direction: column; gap: 1rem; text-align: left; }
.wave-step {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    background: rgba(0,0,0,0.02);
    border: 1px solid var(--border);
}
.wave-step-num {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 800; flex-shrink: 0;
    background: rgba(124,58,237,0.12); color: #7c3aed;
}
.wave-step.done .wave-step-num { background: rgba(34,197,94,0.15); color: #22c55e; }
.wave-step-label { font-size: 0.875rem; font-weight: 600; color: var(--text-1); }
.wave-step-sub   { font-size: 0.78rem; color: var(--text-3); margin-top: 2px; }

/* Statut badges */
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.35rem 0.875rem;
    border-radius: 30px;
    font-size: 0.78rem; font-weight: 600;
    margin-bottom: 1.5rem;
}
.status-badge.pending { background: rgba(234,179,8,0.12); color: #92400e; border: 1px solid rgba(234,179,8,0.25); }
.status-badge.success { background: rgba(34,197,94,0.12); color: #166534; border: 1px solid rgba(34,197,94,0.25); }
.status-badge.failed  { background: rgba(239,68,68,0.12); color: #991b1b; border: 1px solid rgba(239,68,68,0.25); }
.pulse-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #d97706;
    animation: pulse-dot 1.2s ease infinite;
}
@keyframes pulse-dot { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(0.7); } }

.btn-retry {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.75rem 1.75rem;
    background: var(--accent);
    color: white; font-weight: 700; font-size: 0.9rem;
    border-radius: 12px; border: none;
    text-decoration: none; cursor: pointer;
    transition: all 0.2s;
}
.btn-retry:hover { background: var(--accent-hover); transform: translateY(-2px); box-shadow: 0 8px 24px var(--accent-glow); color: white; }
.btn-back {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.75rem 1.5rem;
    background: rgba(0,0,0,0.04);
    color: var(--text-2); font-weight: 600; font-size: 0.9rem;
    border-radius: 12px; border: 1px solid var(--border);
    text-decoration: none; transition: all 0.2s; margin-left: 0.75rem;
}
.btn-back:hover { background: rgba(0,0,0,0.05); color: var(--text-1); }
</style>
@endpush

@section('content')
<div class="attente-page">

    {{-- Statut --}}
    <div id="attente-view">
        <div class="spinner-ring" id="spinner"></div>

        <div class="status-badge pending" id="status-badge">
            <div class="pulse-dot"></div>
            <span id="status-text">Paiement en cours de confirmation…</span>
        </div>

        <h1 class="attente-title">Finalise ton paiement</h1>
        <p class="attente-subtitle" id="attente-subtitle">
            Nous attendons la confirmation de ton opérateur mobile.<br>
            <strong>Ne ferme pas cette page.</strong>
        </p>

        <div class="attente-card">
            <div class="wave-steps">
                <div class="wave-step done">
                    <div class="wave-step-num"><i class="fas fa-check"></i></div>
                    <div>
                        <div class="wave-step-label">Paiement initié</div>
                        <div class="wave-step-sub">Ta demande a bien été envoyée à l'opérateur</div>
                    </div>
                </div>
                <div class="wave-step" id="step-confirm">
                    <div class="wave-step-num">2</div>
                    <div>
                        <div class="wave-step-label">Confirmation de l'opérateur</div>
                        <div class="wave-step-sub" id="step-confirm-sub">
                            Ouvre ton application Wave / Orange Money et valide le paiement
                        </div>
                    </div>
                </div>
                <div class="wave-step" id="step-livraison">
                    <div class="wave-step-num">3</div>
                    <div>
                        <div class="wave-step-label">Livraison de ton produit</div>
                        <div class="wave-step-sub">Tu recevras ton fichier par email</div>
                    </div>
                </div>
            </div>
        </div>

        <p style="font-size:0.8rem;color:var(--text-3);">
            <i class="fas fa-sync-alt me-1"></i>
            Vérification automatique toutes les 5 secondes…
            <span id="check-count">(1)</span>
        </p>
    </div>

    {{-- Vue succès (cachée initialement) --}}
    <div id="success-view" style="display:none;">
        <div class="attente-icon success"><i class="fas fa-check"></i></div>
        <h1 class="attente-title">Paiement confirmé ! 🎉</h1>
        <p class="attente-subtitle">Ton paiement a été validé. Tu vas être redirigé vers ta page de confirmation.</p>
    </div>

    {{-- Vue échec (cachée initialement) --}}
    <div id="failed-view" style="display:none;">
        <div class="attente-icon failed"><i class="fas fa-times"></i></div>
        <div class="status-badge failed"><i class="fas fa-times-circle"></i> Paiement échoué</div>
        <h1 class="attente-title">Le paiement n'a pas abouti</h1>
        <p class="attente-subtitle">
            Le paiement a été annulé ou a expiré.<br>
            Tu peux réessayer avec un autre moyen de paiement.
        </p>
        <div>
            <a href="{{ route('boutique.panier.index') }}" class="btn-retry">
                <i class="fas fa-redo"></i> Réessayer le paiement
            </a>
            <a href="{{ url('/boutique') }}" class="btn-back">
                <i class="fas fa-home"></i> Accueil
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var paymentId  = {{ json_encode($paymentReference ?? '') }};
    var pollUrl    = '{{ route("boutique.checkout.verifier-statut") }}';
    var successUrl = '{{ route("boutique.checkout.succes") }}';
    var checks     = 1;
    var maxChecks  = 72; // 6 minutes max (72 × 5s)
    var timer;

    function poll() {
        if (!paymentId) return;
        checks++;
        document.getElementById('check-count').textContent = '(' + checks + ')';

        fetch(pollUrl + '?payment_id=' + encodeURIComponent(paymentId), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                clearInterval(timer);
                showSuccess();
            } else if (data.status === 'failed') {
                clearInterval(timer);
                showFailed();
            } else if (checks >= maxChecks) {
                clearInterval(timer);
                showFailed();
            }
        })
        .catch(function() {
            // Erreur réseau — on continue
        });
    }

    function showSuccess() {
        document.getElementById('attente-view').style.display = 'none';
        document.getElementById('success-view').style.display = '';
        // Rediriger vers la page succès après 2 secondes
        setTimeout(function() {
            window.location.href = successUrl;
        }, 2000);
    }

    function showFailed() {
        document.getElementById('attente-view').style.display = 'none';
        document.getElementById('failed-view').style.display  = '';
    }

    // Démarrer le polling toutes les 5s
    if (paymentId) {
        timer = setInterval(poll, 5000);
    } else {
        showFailed();
    }
})();
</script>
@endpush
