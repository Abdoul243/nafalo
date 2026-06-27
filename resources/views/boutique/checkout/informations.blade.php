@extends('layouts.boutique')

@section('title', 'Paiement')

@push('styles')
<style>
.checkout-wrap {
    max-width: 920px;
    margin: 0 auto;
    padding: 2.5rem 1.25rem 5rem;
}

/* Steps */
.ck-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 2.5rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}
.ck-step {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.875rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-3);
    border-right: 1px solid var(--border);
    transition: all 0.2s;
}
.ck-step:last-child { border-right: none; }
.ck-step.active {
    color: var(--accent);
    background: rgba(124,58,237,0.06);
}
.ck-step.done { color: #22c55e; }
.ck-step-num {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 800;
    flex-shrink: 0;
    color: var(--text-3);
}
.ck-step.active .ck-step-num {
    background: var(--accent);
    color: white;
    box-shadow: 0 0 0 4px rgba(124,58,237,0.2);
}
.ck-step.done .ck-step-num {
    background: #22c55e;
    color: white;
}

/* Page title */
.ck-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.35rem;
}
.ck-subtitle {
    font-size: 0.9rem;
    color: var(--text-3);
    margin-bottom: 2rem;
}

/* Grid layout */
.ck-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.75rem;
    align-items: start;
}
@media (max-width: 800px) {
    .ck-grid { grid-template-columns: 1fr; }
    .ck-title { font-size: 1.6rem; }
}

/* Cards */
.ck-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.75rem;
}
.ck-section-title {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--text-3);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ck-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* Form fields */
.ck-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-2);
    margin-bottom: 0.4rem;
}
.ck-input {
    width: 100%;
    background: rgba(0,0,0,0.03);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    color: var(--text-1);
    font-family: inherit;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
    box-sizing: border-box;
}
.ck-input::placeholder { color: var(--text-3); }
.ck-input:focus {
    border-color: var(--accent);
    background: rgba(124,58,237,0.06);
}
.ck-input-hint {
    font-size: 0.75rem;
    color: var(--text-3);
    margin-top: 0.35rem;
}
.ck-row { margin-bottom: 1.1rem; }

/* Payment method tiles */
.pm-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--text-2);
    margin: 1.5rem 0 0.75rem;
    display: flex; align-items: center; gap: 8px;
}
.pm-title::after { content:''; flex:1; height:1px; background:var(--border); }
.pm-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.65rem;
    margin-bottom: 0.5rem;
}
@media (max-width: 480px) { .pm-grid { grid-template-columns: repeat(2, 1fr); } }

.pm-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0.85rem 0.5rem 0.75rem;
    background: rgba(0,0,0,0.02);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    position: relative;
    min-height: 76px;
}
.pm-tile:hover {
    border-color: rgba(124,58,237,0.45);
    background: rgba(124,58,237,0.05);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(124,58,237,0.12);
}
.pm-tile.selected {
    border-color: var(--accent);
    background: rgba(124,58,237,0.08);
    box-shadow: 0 0 0 3px rgba(124,58,237,0.18), 0 4px 16px rgba(124,58,237,0.15);
}
.pm-tile input[type="radio"] {
    position: absolute; opacity: 0; pointer-events: none;
}
/* Logo container */
.pm-tile-logo {
    height: 34px; width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 4px;
}
.pm-tile-logo img {
    max-height: 30px; max-width: 60px;
    width: auto; height: auto;
    object-fit: contain; flex-shrink: 0;
}
.pm-tile-name {
    font-size: 0.68rem;
    font-weight: 800;
    color: var(--text-2);
    line-height: 1.2;
    white-space: nowrap;
}
.pm-tile.selected .pm-tile-name { color: var(--accent); }
.pm-tile-check {
    position: absolute; top: 6px; right: 6px;
    width: 17px; height: 17px;
    background: var(--accent); border-radius: 50%;
    display: none; align-items: center; justify-content: center;
    font-size: 0.52rem; color: white;
    box-shadow: 0 2px 6px rgba(124,58,237,0.4);
}
.pm-tile.selected .pm-tile-check { display: flex; }

.pm-redirect-note {
    font-size: 0.78rem;
    color: var(--text-3);
    display: flex;
    align-items: flex-start;
    gap: 7px;
    padding: 0.75rem;
    background: rgba(0,0,0,0.02);
    border-radius: 10px;
    border: 1px solid var(--border);
    margin-top: 0.75rem;
}
.pm-redirect-note i { color: var(--accent); margin-top: 1px; flex-shrink: 0; }

/* Pay button */
.btn-pay {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 1.1rem;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.25s;
    margin-top: 1.5rem;
    font-family: inherit;
    position: relative;
    overflow: hidden;
}
.btn-pay::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
    pointer-events: none;
}
.btn-pay:hover:not(:disabled) {
    background: var(--accent-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(124,58,237,0.4);
}
.btn-pay:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Trust badges */
.trust-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}
.trust-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    color: var(--text-3);
}
.trust-item i { color: #22c55e; font-size: 0.68rem; }

/* Order summary */
.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--border);
}
.order-item:last-child { border: none; }
.order-thumb {
    width: 48px; height: 48px;
    border-radius: 10px;
    object-fit: cover;
    background: rgba(0,0,0,0.04);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.order-thumb img { width: 100%; height: 100%; object-fit: cover; }
.order-thumb i { color: var(--text-3); font-size: 1.1rem; }
.order-name {
    flex: 1;
    font-weight: 600;
    font-size: 0.855rem;
    color: var(--text-1);
    line-height: 1.35;
    min-width: 0;
}
.order-price {
    font-weight: 800;
    font-size: 0.875rem;
    color: var(--accent);
    white-space: nowrap;
    margin-left: auto;
}

.total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0 0;
    margin-top: 0.25rem;
    border-top: 1px solid var(--border);
}
.total-label { font-weight: 700; font-size: 0.875rem; color: var(--text-2); }
.total-amount {
    font-weight: 900;
    font-size: 1.35rem;
    color: var(--text-1);
}

/* Guarantee card */
.guarantee-card {
    background: rgba(34,197,94,0.06);
    border: 1px solid rgba(34,197,94,0.2);
    border-radius: 14px;
    padding: 1rem 1.1rem;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 12px;
}
.guarantee-card i { color: #22c55e; font-size: 1.3rem; flex-shrink: 0; }
.guarantee-title { font-weight: 700; font-size: 0.85rem; color: var(--text-1); margin-bottom: 2px; }
.guarantee-sub { font-size: 0.76rem; color: var(--text-3); }
</style>
@endpush

@section('content')
<div class="checkout-wrap">

    {{-- Steps --}}
    <div class="ck-steps">
        <div class="ck-step done">
            <div class="ck-step-num"><i class="fas fa-check" style="font-size:0.6rem;"></i></div>
            Panier
        </div>
        <div class="ck-step active">
            <div class="ck-step-num">2</div>
            Informations
        </div>
        <div class="ck-step">
            <div class="ck-step-num">3</div>
            Paiement
        </div>
    </div>

    <h1 class="ck-title">Finaliser ma commande</h1>
    <p class="ck-subtitle">Renseignez vos informations pour recevoir vos produits instantanément.</p>

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:0.875rem 1.1rem;margin-bottom:1.5rem;color:#dc2626;font-size:0.875rem;">
        <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i> {{ $errors->first() }}
    </div>
    @endif

    <div class="ck-grid">

        {{-- Left: Client info + Payment --}}
        <div>
            <div class="ck-card">
                <div class="ck-section-title"><i class="fas fa-user-circle"></i> Vos informations</div>

                <form action="{{ route('boutique.checkout.payer') }}" method="POST" id="checkout-form">
                    @csrf

                    <div class="ck-row">
                        <label class="ck-label" for="ck-nom">Nom complet *</label>
                        <input class="ck-input" type="text" id="ck-nom" name="nom"
                               value="{{ old('nom') }}" placeholder="Votre nom complet" required>
                    </div>
                    <div class="ck-row">
                        <label class="ck-label" for="ck-email">Adresse email *</label>
                        <input class="ck-input" type="email" id="ck-email" name="email"
                               value="{{ old('email') }}" placeholder="vous@exemple.com" required>
                        <div class="ck-input-hint">
                            <i class="fas fa-envelope" style="font-size:0.7rem;margin-right:4px;"></i>
                            Vos liens de téléchargement seront envoyés ici.
                        </div>
                    </div>
                    <div class="ck-row">
                        <label class="ck-label" for="ck-tel">Téléphone <span style="color:#f87171;font-size:0.7rem;">* requis pour le paiement</span></label>
                        <input class="ck-input" type="tel" id="ck-tel" name="telephone"
                               value="{{ old('telephone') }}" placeholder="+225 07 00 00 00 00"
                               required minlength="8">
                    </div>

                    <div class="ck-section-title" style="margin-top:1.75rem;"><i class="fas fa-credit-card"></i> Moyen de paiement</div>

                    <p class="pm-title"><i class="fas fa-credit-card" style="font-size:0.7rem;color:var(--accent);"></i> Comment voulez-vous payer ?</p>
                    <div class="pm-grid">

                        {{-- Wave --}}
                        <label class="pm-tile selected" data-method="wave">
                            <input type="radio" name="moyen_paiement" value="wave" checked>
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo">
                                <img src="https://dashboard.fedapay.com/storage/channel-logos/wave.svg"
                                     alt="Wave" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">Wave</div>
                        </label>

                        {{-- Orange Money --}}
                        <label class="pm-tile" data-method="orange_money">
                            <input type="radio" name="moyen_paiement" value="orange_money">
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo">
                                <img src="https://dashboard.fedapay.com/storage/channel-logos/orange-money.svg"
                                     alt="Orange Money" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">Orange Money</div>
                        </label>

                        {{-- MTN MoMo --}}
                        <label class="pm-tile" data-method="mtn_momo">
                            <input type="radio" name="moyen_paiement" value="mtn_momo">
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo">
                                <img src="https://dashboard.fedapay.com/storage/channel-logos/mtn.svg"
                                     alt="MTN MoMo" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">MTN MoMo</div>
                        </label>

                        {{-- Moov Money --}}
                        <label class="pm-tile" data-method="moov">
                            <input type="radio" name="moyen_paiement" value="moov">
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo">
                                <img src="https://dashboard.fedapay.com/storage/channel-logos/moov.svg"
                                     alt="Moov Money" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">Moov Money</div>
                        </label>

                        {{-- Carte bancaire --}}
                        <label class="pm-tile" data-method="carte">
                            <input type="radio" name="moyen_paiement" value="carte">
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo" style="gap:3px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"
                                     alt="Visa" style="max-height:16px;" onerror="this.style.display='none'">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                     alt="MC" style="max-height:22px;" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">Carte bancaire</div>
                        </label>

                        {{-- Visa / Mastercard --}}
                        <label class="pm-tile" data-method="visa">
                            <input type="radio" name="moyen_paiement" value="visa">
                            <div class="pm-tile-check"><i class="fas fa-check"></i></div>
                            <div class="pm-tile-logo" style="gap:3px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"
                                     alt="Visa" style="max-height:16px;" onerror="this.style.display='none'">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                     alt="MC" style="max-height:22px;" onerror="this.style.display='none'">
                            </div>
                            <div class="pm-tile-name">Visa / Mastercard</div>
                        </label>

                    </div>

                    <div class="pm-redirect-note">
                        <i class="fas fa-lock"></i>
                        <span>Vous serez redirigé vers la page sécurisée Moneroo. Votre choix ci-dessus sera pré-sélectionné.</span>
                    </div>

                    <button type="submit" class="btn-pay" id="pay-btn">
                        <i class="fas fa-lock"></i>
                        Payer {{ number_format($total, 0, ',', ' ') }} FCFA
                    </button>

                    <div class="trust-row">
                        <span class="trust-item"><i class="fas fa-shield-alt"></i> Paiement sécurisé SSL</span>
                        <span class="trust-item"><i class="fas fa-bolt"></i> Livraison instantanée</span>
                        <span class="trust-item"><i class="fas fa-lock"></i> Données protégées</span>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Order summary --}}
        <div>
            <div class="ck-card">
                <div class="ck-section-title"><i class="fas fa-receipt"></i> Récapitulatif</div>

                @foreach($produits as $produit)
                <div class="order-item">
                    <div class="order-thumb">
                        @if($produit->image)
                            <img src="{{ route('media.produits.image', $produit) }}"
                                 alt="{{ $produit->nom }}"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <i class="fas fa-file-download" style="display:none;"></i>
                        @else
                            <i class="fas fa-file-download"></i>
                        @endif
                    </div>
                    <div class="order-name">{{ Str::limit($produit->nom, 38) }}</div>
                    <div class="order-price">{{ number_format($produit->prix, 0, ',', ' ') }} F</div>
                </div>
                @endforeach

                <div class="total-line">
                    <span class="total-label">Total à payer</span>
                    <span class="total-amount">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="guarantee-card">
                <i class="fas fa-medal"></i>
                <div>
                    <div class="guarantee-title">Satisfait ou remboursé</div>
                    <div class="guarantee-sub">7 jours pour changer d'avis</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Payment method tile selector
document.querySelectorAll('.pm-tile').forEach(function(tile) {
    tile.addEventListener('click', function() {
        document.querySelectorAll('.pm-tile').forEach(t => t.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
    });
});

// Disable button on submit
document.getElementById('checkout-form')?.addEventListener('submit', function() {
    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirection en cours...';
});
</script>
@endpush
