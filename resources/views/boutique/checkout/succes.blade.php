@extends('layouts.boutique')

@section('title', 'Paiement réussi !')

@push('styles')
<style>
.succes-page {
    max-width: 720px;
    margin: 0 auto;
    padding: 3rem 1.25rem 5rem;
}

/* ── Particles ── */
.particles-wrap {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none; z-index: 9999; overflow: hidden;
}
@keyframes particle-fall {
    0%   { transform: translateY(-10px) rotate(0deg) scale(1); opacity: 1; }
    100% { transform: translateY(110vh) rotate(720deg) scale(0.5); opacity: 0; }
}
.particle {
    position: absolute; top: -20px;
    border-radius: 2px;
    animation: particle-fall linear forwards;
}

/* ── Hero ── */
.success-hero {
    text-align: center;
    padding: 2.5rem 2rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.success-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% -20%, rgba(34,197,94,0.15) 0%, transparent 65%);
    pointer-events: none;
}
.success-glow {
    width: 88px; height: 88px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 0 0 12px rgba(34,197,94,0.1), 0 0 0 24px rgba(34,197,94,0.05);
    position: relative; z-index: 1;
    animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
}
@keyframes pop-in {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.success-glow i { font-size: 2.1rem; color: white; }

.success-hero h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-1);
    margin-bottom: 0.5rem;
    position: relative; z-index: 1;
}
.success-hero p {
    color: var(--text-3);
    font-size: 0.9rem;
    line-height: 1.7;
    margin: 0;
    position: relative; z-index: 1;
}

/* ── Reference card ── */
.ref-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.ref-label { font-size: 0.72rem; color: var(--text-3); font-weight: 500; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.06em; }
.ref-value { font-weight: 800; font-size: 0.95rem; color: var(--text-1); font-family: 'Courier New', monospace; letter-spacing: 0.08em; }
.ref-amount { font-weight: 900; font-size: 1.5rem; color: var(--accent); }

/* ── Client banner ── */
.client-banner {
    background: rgba(124,58,237,0.08);
    border: 1px solid rgba(124,58,237,0.25);
    border-radius: 14px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.875rem;
}
.client-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #a855f7);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 800; font-size: 1.1rem;
    flex-shrink: 0;
}
.client-banner strong { font-size: 0.875rem; color: var(--text-1); display: block; margin-bottom: 2px; }
.client-banner span { font-size: 0.8rem; color: var(--text-3); }

/* ── Products section ── */
.section-head {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--text-3);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-head::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.product-row {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1rem 1.25rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: border-color 0.2s;
}
.product-row:hover { border-color: rgba(124,58,237,0.3); }
.product-thumb {
    width: 58px; height: 58px;
    border-radius: 12px;
    background: rgba(0,0,0,0.03);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
}
.product-thumb img { width: 100%; height: 100%; object-fit: cover; }
.product-thumb i { color: var(--text-3); font-size: 1.4rem; }
.product-info { flex: 1; min-width: 0; }
.product-name {
    font-weight: 700;
    color: var(--text-1);
    font-size: 0.95rem;
    margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.product-prix { font-size: 0.8rem; color: var(--text-3); }

.btn-download {
    display: inline-flex;
    align-items: center; gap: 7px;
    padding: 0.6rem 1.1rem;
    background: var(--accent);
    color: white; border: none; border-radius: 11px;
    font-weight: 700; font-size: 0.82rem;
    text-decoration: none; cursor: pointer;
    transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(124,58,237,0.3);
}
.btn-download:hover { background: var(--accent-hover); transform: translateY(-2px); color: white; }

/* ── Upsells ── */
.upsell-card {
    background: var(--bg-card);
    border: 1px solid rgba(249,115,22,0.25);
    border-radius: 18px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1.1rem;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.upsell-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #f97316, #ef4444);
}
.upsell-thumb {
    width: 64px; height: 64px;
    border-radius: 12px;
    background: rgba(0,0,0,0.03);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
}
.upsell-thumb img { width: 100%; height: 100%; object-fit: cover; }
.upsell-badge {
    display: inline-block;
    font-size: 0.68rem; font-weight: 800;
    color: #f97316; text-transform: uppercase;
    letter-spacing: 0.07em; margin-bottom: 3px;
}
.upsell-name { font-weight: 800; color: var(--text-1); font-size: 0.95rem; margin-bottom: 2px; }
.upsell-desc { font-size: 0.8rem; color: var(--text-3); margin-bottom: 5px; }
.upsell-price { font-weight: 900; color: #f97316; font-size: 1.05rem; }
.upsell-old { text-decoration: line-through; color: var(--text-3); font-size: 0.82rem; margin-right: 6px; }
.upsell-new {
    background: rgba(34,197,94,0.15);
    color: #22c55e; font-weight: 800; font-size: 0.95rem;
    padding: 2px 10px; border-radius: 20px;
}

.btn-upsell {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; text-decoration: none;
    padding: 0.7rem 1.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.88rem;
    white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(249,115,22,0.35);
    transition: all 0.2s;
}
.btn-upsell:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(249,115,22,0.45); color: white; }

/* Upsell countdown */
.countdown-bar {
    width: 100%;
    background: rgba(249,115,22,0.08);
    border: 1px solid rgba(249,115,22,0.2);
    border-radius: 10px;
    padding: 0.6rem 0.875rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.8rem;
    color: var(--text-3);
}
.countdown-bar i { color: #f97316; margin-right: 5px; }
.countdown-digits {
    font-weight: 800;
    font-size: 1.05rem;
    color: #f97316;
    font-variant-numeric: tabular-nums;
    font-family: 'Courier New', monospace;
}

/* ── Email info ── */
.email-info {
    background: rgba(234,179,8,0.08);
    border: 1px solid rgba(234,179,8,0.2);
    border-radius: 12px;
    padding: 0.9rem 1.1rem;
    font-size: 0.82rem;
    color: var(--text-2);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
}
.email-info i { color: #eab308; margin-top: 1px; flex-shrink: 0; }

/* ── Actions ── */
.actions { display: flex; flex-direction: column; gap: 0.75rem; }
.btn-action-primary {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: var(--accent); color: white;
    border-radius: 14px; font-weight: 700; font-size: 0.95rem;
    text-decoration: none; transition: all 0.2s;
    box-shadow: 0 4px 18px rgba(124,58,237,0.3);
}
.btn-action-primary:hover { background: var(--accent-hover); color: white; transform: translateY(-1px); }
.btn-action-secondary {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: rgba(0,0,0,0.03); color: var(--text-2);
    border: 1px solid var(--border); border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-action-secondary:hover { border-color: rgba(0,0,0,0.15); color: var(--text-1); background: rgba(0,0,0,0.05); }
</style>
@endpush

@section('content')

{{-- Particles --}}
<div class="particles-wrap" id="particles-wrap"></div>

<div class="succes-page">

    {{-- ── HERO ── --}}
    <div class="success-hero">
        <div class="success-glow">
            <i class="fas fa-check"></i>
        </div>
        <h1>Paiement réussi !</h1>
        <p>
            @if($transaction && $transaction->client && $transaction->client->nom)
                Merci <strong style="color:var(--text-1);">{{ $transaction->client->nom }}</strong>. Bon apprentissage !
            @else
                Merci pour votre achat. Vos produits sont disponibles immédiatement.
            @endif
            <br>Un email de confirmation vous a été envoyé.
        </p>
    </div>

    {{-- ── REFERENCE ── --}}
    @if($transaction)
    <div class="ref-card">
        <div>
            <div class="ref-label">Référence de commande</div>
            <div class="ref-value">{{ $transaction->reference }}</div>
        </div>
        <div style="text-align:right;">
            <div class="ref-label">Montant total payé</div>
            <div class="ref-amount">{{ number_format($transaction->montant_total, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    @endif

    {{-- ── CLIENT BANNER ── --}}
    @if($transaction && $transaction->client)
    @php $clientEmail = $transaction->client->email; @endphp
    <div class="client-banner">
        <div class="client-avatar">{{ strtoupper(substr($transaction->client->nom ?? $clientEmail, 0, 1)) }}</div>
        <div>
            <strong><i class="fas fa-check-circle" style="color:#22c55e;margin-right:5px;"></i>Vous êtes connecté automatiquement</strong>
            <span>{{ $clientEmail }} — retrouvez vos achats à tout moment</span>
        </div>
    </div>
    @endif

    {{-- ── UPSELL COUNTDOWN ── --}}
    @if(!empty($upsells) && $upsells->count() > 0)
    <div class="countdown-bar">
        <span><i class="fas fa-clock"></i> Offre exclusive valable encore</span>
        <span class="countdown-digits" id="countdown">15:00</span>
    </div>
    @endif

    {{-- ── PRODUCTS ── --}}
    @if($achats && $achats->count() > 0)
    <div style="margin-bottom:1.5rem;">
        <div class="section-head"><i class="fas fa-download" style="color:var(--accent);"></i> Téléchargement immédiat</div>

        @foreach($achats as $achat)
        <div class="product-row">
            <div class="product-thumb">
                @if($achat->produit->image)
                    <img src="{{ $achat->produit->image_url }}" alt="{{ $achat->produit->nom }}">
                @else
                    <i class="fas fa-file-download"></i>
                @endif
            </div>
            <div class="product-info">
                <div class="product-name">{{ $achat->produit->nom }}</div>
                <div class="product-prix">{{ number_format($achat->produit->prix, 0, ',', ' ') }} FCFA</div>
            </div>
            <a href="{{ route('client.telechargement', $achat) }}" class="btn-download">
                <i class="fas fa-download"></i> Télécharger
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── UPSELLS ── --}}
    @if(!empty($upsells) && $upsells->count() > 0)
    <div style="margin-bottom:1.5rem;">
        <div class="section-head"><i class="fas fa-fire" style="color:#f97316;"></i> Offres exclusives pour vous</div>

        @foreach($upsells as $upsell)
        @php $prix = $upsell->prix_effectif; $produit = $upsell->produitUpsell; @endphp
        @if($produit)
        <div class="upsell-card">
            <div class="upsell-thumb">
                @if($produit->image)
                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}">
                @else
                    <i class="fas fa-box-open" style="color:#f97316;font-size:1.5rem;"></i>
                @endif
            </div>
            <div style="flex:1;min-width:140px;">
                <div class="upsell-badge">{{ $upsell->titre_offre }}</div>
                <div class="upsell-name">{{ $produit->nom }}</div>
                @if($upsell->description_offre)
                    <div class="upsell-desc">{{ $upsell->description_offre }}</div>
                @endif
                <div>
                    @if($upsell->prix_special !== null && $upsell->prix_special < $produit->prix)
                        <span class="upsell-old">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                        <span class="upsell-new">{{ number_format($upsell->prix_special, 0, ',', ' ') }} FCFA</span>
                    @else
                        <span class="upsell-price">{{ number_format($prix, 0, ',', ' ') }} FCFA</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('boutique.checkout.produit', ['id' => $produit->id]) }}" class="btn-upsell">
                <i class="fas fa-bolt"></i> Ajouter
            </a>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ── EMAIL INFO ── --}}
    <div class="email-info">
        <i class="fas fa-envelope"></i>
        <div>
            Un email avec vos liens de téléchargement vous a été envoyé.
            Retrouvez tous vos achats dans votre espace client à tout moment.
        </div>
    </div>

    {{-- ── ACTIONS ── --}}
    <div class="actions">
        <a href="{{ route('client.mes-achats.index') }}" class="btn-action-primary">
            <i class="fas fa-shopping-bag"></i> Voir tous mes achats
        </a>
        <a href="{{ route('boutique.accueil') }}" class="btn-action-secondary">
            <i class="fas fa-store"></i> Retourner à la boutique
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Particles
(function() {
    const colors = ['#7c3aed','#a855f7','#22c55e','#f59e0b','#3b82f6','#ec4899','#f97316'];
    const wrap = document.getElementById('particles-wrap');
    if (!wrap) return;
    for (let i = 0; i < 70; i++) {
        const el = document.createElement('div');
        el.className = 'particle';
        const size = 5 + Math.random() * 9;
        el.style.cssText = [
            `left:${Math.random() * 100}%`,
            `background:${colors[Math.floor(Math.random() * colors.length)]}`,
            `width:${size}px`,
            `height:${size}px`,
            `border-radius:${Math.random() > 0.5 ? '50%' : '3px'}`,
            `animation-delay:${Math.random() * 1.5}s`,
            `animation-duration:${3 + Math.random() * 2.5}s`,
        ].join(';');
        wrap.appendChild(el);
    }
    setTimeout(() => { if (wrap) wrap.remove(); }, 6000);
})();

// ── Countdown timer (15 min)
(function() {
    const el = document.getElementById('countdown');
    if (!el) return;
    let secs = 15 * 60;
    const tick = () => {
        if (secs <= 0) { el.textContent = '00:00'; return; }
        secs--;
        const m = String(Math.floor(secs / 60)).padStart(2, '0');
        const s = String(secs % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
        setTimeout(tick, 1000);
    };
    tick();
})();
</script>
@endpush
