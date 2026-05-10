@extends('layouts.boutique')

@section('title', 'Paiement')

@push('styles')
<style>
    .checkout-wrap { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
    .checkout-title { font-size: 1.8rem; font-weight: 900; color: #0f172a; margin-bottom: 2rem; }
    .checkout-grid { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; }
    @media (max-width: 768px) { .checkout-grid { grid-template-columns: 1fr; } }

    .checkout-card {
        background: white; border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        padding: 1.75rem;
    }
    .checkout-card h5 { font-weight: 800; font-size: 1rem; color: #0f172a; margin-bottom: 1.25rem; }
    .form-label { font-weight: 600; font-size: 0.82rem; color: #374151; }
    .form-control {
        border-radius: 10px; border: 1.5px solid #e5e7eb;
        padding: 0.7rem 1rem; font-size: 0.9rem;
        transition: border-color 0.15s;
    }
    .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    .order-item {
        display: flex; align-items: center; gap: 12px;
        padding: 0.875rem 0; border-bottom: 1px solid #f3f4f6;
    }
    .order-item:last-child { border: none; }
    .order-img {
        width: 50px; height: 50px; border-radius: 10px;
        object-fit: cover; background: #f3f4f6;
        flex-shrink: 0;
    }
    .order-name { font-weight: 600; font-size: 0.875rem; color: #0f172a; }
    .order-price { font-weight: 700; font-size: 0.875rem; color: #2563eb; margin-left: auto; white-space: nowrap; }

    .total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 0; border-top: 2px solid #f3f4f6; margin-top: 0.5rem;
    }
    .total-label { font-weight: 700; font-size: 1rem; color: #0f172a; }
    .total-amount { font-weight: 900; font-size: 1.3rem; color: #0f172a; }

    .btn-pay {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; background: #2563eb; color: white;
        border: none; border-radius: 12px; padding: 1rem;
        font-weight: 700; font-size: 1rem; cursor: pointer;
        transition: all 0.2s; margin-top: 1rem;
    }
    .btn-pay:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.3); }
    .btn-pay:disabled { background: #94a3b8; transform: none; cursor: not-allowed; }

    .secure-badges {
        display: flex; align-items: center; justify-content: center;
        gap: 1rem; margin-top: 1rem; flex-wrap: wrap;
    }
    .secure-badge { display: flex; align-items: center; gap: 5px; font-size: 0.75rem; color: #94a3b8; }
    .secure-badge i { color: #22c55e; }

    .payment-methods {
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap; margin-top: 0.75rem;
    }
    .pm-badge {
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 8px; padding: 4px 10px;
        font-size: 0.75rem; font-weight: 600; color: #374151;
    }
    .steps { display: flex; gap: 0; margin-bottom: 2rem; }
    .step {
        flex: 1; display: flex; align-items: center; gap: 8px;
        padding: 0.75rem 1rem; background: #f8fafc;
        border-bottom: 2px solid #e2e8f0; font-size: 0.82rem; font-weight: 500; color: #94a3b8;
    }
    .step.active { background: white; border-bottom-color: #2563eb; color: #2563eb; font-weight: 700; }
    .step-num { width: 22px; height: 22px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; }
    .step.active .step-num { background: #2563eb; color: white; }
</style>
@endpush

@section('content')
<div class="checkout-wrap">

    {{-- Étapes --}}
    <div class="steps mb-4">
        <div class="step"><div class="step-num">1</div> Panier</div>
        <div class="step active"><div class="step-num">2</div> Informations</div>
        <div class="step"><div class="step-num">3</div> Paiement</div>
    </div>

    <h1 class="checkout-title">Finaliser ma commande</h1>

    @if($errors->any())
        <div class="alert alert-danger mb-3 rounded-3">{{ $errors->first() }}</div>
    @endif

    <div class="checkout-grid">

        {{-- Formulaire infos client --}}
        <div class="checkout-card">
            <h5><i class="fas fa-user me-2 text-primary"></i> Vos informations</h5>
            <form action="{{ route('boutique.checkout.payer') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" class="form-control" name="nom"
                           value="{{ old('nom') }}" placeholder="Votre nom complet" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse email *</label>
                    <input type="email" class="form-control" name="email"
                           value="{{ old('email') }}" placeholder="vous@exemple.com" required>
                    <small class="text-muted" style="font-size:0.75rem;">Votre lien de téléchargement sera envoyé à cette adresse.</small>
                </div>
                <div class="mb-4">
                    <label class="form-label">Téléphone (optionnel)</label>
                    <input type="text" class="form-control" name="telephone"
                           value="{{ old('telephone') }}" placeholder="+225 07 00 00 00 00">
                </div>

                <h5 class="mt-4"><i class="fas fa-credit-card me-2 text-primary"></i> Moyen de paiement</h5>
                <p style="font-size:0.875rem;color:#64748b;margin-bottom:0.75rem;">
                    Vous serez redirigé vers la page sécurisée GeniusPay pour choisir votre moyen de paiement.
                </p>
                <div class="payment-methods">
                    <span class="pm-badge">📱 Wave</span>
                    <span class="pm-badge">🟠 Orange Money</span>
                    <span class="pm-badge">💛 MTN MoMo</span>
                    <span class="pm-badge">💳 Carte bancaire</span>
                </div>

                <button type="submit" class="btn-pay" id="pay-btn">
                    <i class="fas fa-lock"></i>
                    Payer {{ number_format($total, 0, ',', ' ') }} FCFA
                </button>

                <div class="secure-badges">
                    <span class="secure-badge"><i class="fas fa-lock"></i> Paiement sécurisé</span>
                    <span class="secure-badge"><i class="fas fa-check-circle"></i> Livraison instantanée</span>
                    <span class="secure-badge"><i class="fas fa-shield-alt"></i> Données protégées</span>
                </div>
            </form>
        </div>

        {{-- Récapitulatif commande --}}
        <div>
            <div class="checkout-card">
                <h5><i class="fas fa-shopping-bag me-2 text-primary"></i> Récapitulatif</h5>

                @foreach($produits as $produit)
                <div class="order-item">
                    @if($produit->image)
                        <img src="{{ asset('storage/' . $produit->image) }}"
                             alt="{{ $produit->nom }}" class="order-img">
                    @else
                        <div class="order-img d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-muted" style="font-size:1.2rem;"></i>
                        </div>
                    @endif
                    <span class="order-name">{{ Str::limit($produit->nom, 35) }}</span>
                    <span class="order-price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                </div>
                @endforeach

                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-amount">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            {{-- Garantie --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:1rem;margin-top:1rem;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-medal" style="color:#22c55e;font-size:1.3rem;"></i>
                    <div>
                        <div style="font-weight:700;font-size:0.875rem;color:#0f172a;">Satisfait ou remboursé</div>
                        <div style="font-size:0.78rem;color:#64748b;">7 jours pour changer d'avis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('checkout-form')?.addEventListener('submit', function() {
    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirection en cours...';
});
</script>
@endpush
@endsection
