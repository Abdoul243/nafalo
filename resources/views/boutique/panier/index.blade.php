@extends('layouts.boutique')

@section('title', 'Mon panier')

@push('styles')
<style>
.panier-wrap {
    max-width: 960px;
    margin: 0 auto;
    padding: 2.5rem 1.25rem 5rem;
}

.panier-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.35rem;
}
.panier-count {
    font-size: 0.875rem;
    color: var(--text-3);
    margin-bottom: 2rem;
}

.panier-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.75rem;
    align-items: start;
}
@media (max-width: 800px) {
    .panier-grid { grid-template-columns: 1fr; }
    .panier-title { font-size: 1.6rem; }
}

/* Card container */
.panier-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
}

/* Section label */
.panier-section {
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--text-3);
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Item row */
.panier-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    transition: background 0.2s;
}
.panier-item:last-child { border-bottom: none; }
.panier-item:hover { background: rgba(255,255,255,0.02); }

.panier-img {
    width: 60px; height: 60px;
    border-radius: 12px;
    background: rgba(255,255,255,0.06);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
}
.panier-img img { width: 100%; height: 100%; object-fit: cover; }
.panier-img i { color: var(--text-3); font-size: 1.3rem; }

.panier-item-info { flex: 1; min-width: 0; }
.panier-item-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-1);
    margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.panier-item-desc {
    font-size: 0.77rem;
    color: var(--text-3);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.panier-item-price {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--accent);
    white-space: nowrap;
}

.btn-remove {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.15);
    color: #ef4444;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s;
    flex-shrink: 0;
}
.btn-remove:hover {
    background: rgba(239,68,68,0.2);
    border-color: rgba(239,68,68,0.3);
    transform: scale(1.1);
}

/* Continue shopping */
.btn-continue {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.65rem 1.1rem;
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.82rem; font-weight: 600;
    color: var(--text-2);
    text-decoration: none;
    transition: all 0.2s;
    margin: 1.1rem 1.5rem;
    font-family: inherit;
    cursor: pointer;
}
.btn-continue:hover { color: var(--text-1); border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); }

/* Summary sidebar */
.summary-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
}

.summary-section { padding: 1.5rem; }

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
    padding: 0.5rem 0;
}
.summary-row-label { color: var(--text-2); }
.summary-row-val { font-weight: 700; color: var(--text-1); }
.summary-row-discount { color: #22c55e; font-weight: 700; }

.summary-divider { height: 1px; background: var(--border); margin: 0.75rem 0; }

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
}
.summary-total-label { font-weight: 700; font-size: 0.9rem; color: var(--text-2); }
.summary-total-val {
    font-weight: 900;
    font-size: 1.4rem;
    color: var(--text-1);
}

/* Promo code */
.promo-wrap { display: flex; gap: 0.6rem; margin: 1rem 0; }
.promo-input {
    flex: 1;
    background: rgba(255,255,255,0.04);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 0.65rem 0.875rem;
    font-size: 0.85rem;
    color: var(--text-1);
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
    min-width: 0;
}
.promo-input::placeholder { color: var(--text-3); }
.promo-input:focus { border-color: var(--accent); }
.btn-promo {
    padding: 0.65rem 1rem;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.3);
    border-radius: 10px;
    color: var(--accent);
    font-weight: 700; font-size: 0.82rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.2s; white-space: nowrap;
}
.btn-promo:hover { background: rgba(124,58,237,0.25); }

.btn-promo-remove {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.78rem; color: #ef4444;
    background: none; border: none; cursor: pointer;
    font-family: inherit; padding: 0.25rem 0;
    transition: color 0.2s;
}
.btn-promo-remove:hover { color: #dc2626; }

/* Checkout button */
.btn-checkout {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%;
    background: var(--accent);
    color: white; border: none; border-radius: 14px;
    padding: 1.1rem;
    font-weight: 700; font-size: 1rem;
    cursor: pointer; font-family: inherit;
    transition: all 0.25s;
    text-decoration: none;
    position: relative; overflow: hidden;
}
.btn-checkout::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
    pointer-events: none;
}
.btn-checkout:hover {
    background: var(--accent-hover);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(124,58,237,0.4);
}

.btn-abandon {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 0.75rem;
    background: none; border: none;
    font-size: 0.8rem; color: var(--text-3);
    cursor: pointer; font-family: inherit;
    transition: color 0.2s; margin-top: 0.5rem;
}
.btn-abandon:hover { color: #ef4444; }

/* Security badges */
.security-strip {
    display: flex; align-items: center; justify-content: center; gap: 0.75rem;
    flex-wrap: wrap; padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
}
.sec-badge {
    display: flex; align-items: center; gap: 5px;
    font-size: 0.7rem; color: var(--text-3);
}
.sec-badge i { color: #22c55e; font-size: 0.65rem; }

/* Empty state */
.panier-empty {
    text-align: center;
    padding: 5rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
}
.panier-empty-icon {
    width: 80px; height: 80px;
    background: rgba(124,58,237,0.1);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem; color: var(--accent);
}
.panier-empty h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.5rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.5rem;
}
.panier-empty p { color: var(--text-3); font-size: 0.9rem; margin-bottom: 1.75rem; }
.btn-browse {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 0.875rem 1.75rem;
    background: var(--accent); color: white;
    border-radius: 14px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
    box-shadow: 0 4px 18px rgba(124,58,237,0.3);
}
.btn-browse:hover { background: var(--accent-hover); color: white; transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="panier-wrap">

    @if(empty($panier))

    {{-- Empty state --}}
    <div class="panier-empty">
        <div class="panier-empty-icon"><i class="fas fa-shopping-bag"></i></div>
        <h2>Votre panier est vide</h2>
        <p>Découvrez nos produits numériques et commencez vos achats.</p>
        <a href="{{ route('boutique.produit.index') }}" class="btn-browse">
            <i class="fas fa-store"></i> Explorer la boutique
        </a>
    </div>

    @else

    <h1 class="panier-title">Mon panier</h1>
    <p class="panier-count">{{ count($panier) }} article{{ count($panier) > 1 ? 's' : '' }}</p>

    <div class="panier-grid">

        {{-- Cart items --}}
        <div>
            <div class="panier-card">
                <div class="panier-section">
                    <i class="fas fa-shopping-cart" style="color:var(--accent);"></i>
                    Articles
                </div>

                @foreach($produits as $produit)
                <div class="panier-item">
                    <div class="panier-img">
                        @if($produit->image)
                            <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}">
                        @else
                            <i class="fas fa-file-download"></i>
                        @endif
                    </div>
                    <div class="panier-item-info">
                        <div class="panier-item-name" title="{{ $produit->nom }}">{{ $produit->nom }}</div>
                        <div class="panier-item-desc">{{ Str::limit(strip_tags($produit->description), 55) }}</div>
                    </div>
                    <div class="panier-item-price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                    <form action="{{ route('boutique.panier.supprimer', $produit) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-remove" title="Retirer du panier">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
                @endforeach

                <div>
                    <a href="{{ route('boutique.produit.index') }}" class="btn-continue">
                        <i class="fas fa-arrow-left"></i> Continuer les achats
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div>
            <div class="summary-card">
                <div class="summary-section">
                    <div class="panier-section" style="padding:0;border:none;margin-bottom:1rem;">
                        <i class="fas fa-receipt" style="color:var(--accent);"></i> Récapitulatif
                    </div>

                    <div class="summary-row">
                        <span class="summary-row-label">Sous-total</span>
                        <span class="summary-row-val">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>

                    @if(session('code_promo_' . $boutique->id))
                        @php
                            $codePromo = \App\Models\CodePromo::find(session('code_promo_' . $boutique->id));
                            $reduction = $codePromo ? $codePromo->calculerReduction($total) : 0;
                            $totalApresReduction = $total - $reduction;
                        @endphp
                        <div class="summary-row">
                            <span class="summary-row-label">Réduction</span>
                            <span class="summary-row-discount">− {{ number_format($reduction, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <span class="summary-total-label">Total</span>
                            <span class="summary-total-val">{{ number_format($totalApresReduction, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @else
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <span class="summary-total-label">Total</span>
                            <span class="summary-total-val">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @endif

                    {{-- Promo code --}}
                    <form action="{{ route('boutique.panier.code-promo') }}" method="POST">
                        @csrf
                        <div class="promo-wrap">
                            <input type="text" class="promo-input" name="code" placeholder="Code promo">
                            <button type="submit" class="btn-promo">Appliquer</button>
                        </div>
                    </form>

                    @if(session('code_promo_' . $boutique->id))
                    <form action="{{ route('boutique.panier.supprimer-code-promo') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-promo-remove">
                            <i class="fas fa-times-circle"></i> Retirer le code promo
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('boutique.checkout.informations') }}" class="btn-checkout">
                        <i class="fas fa-lock"></i> Procéder au paiement
                    </a>

                    <form action="{{ route('boutique.panier.abandonner') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-abandon">
                            <i class="fas fa-trash"></i> Vider le panier
                        </button>
                    </form>
                </div>

                <div class="security-strip">
                    <span class="sec-badge"><i class="fas fa-shield-alt"></i> Paiement sécurisé</span>
                    <span class="sec-badge"><i class="fas fa-bolt"></i> Livraison immédiate</span>
                    <span class="sec-badge"><i class="fas fa-undo"></i> Remboursement 7j</span>
                </div>
            </div>
        </div>

    </div>
    @endif

</div>
@endsection
