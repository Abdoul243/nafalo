@extends('layouts.boutique')

@section('title', 'Paiement réussi !')

@push('styles')
<style>
.succes-page { max-width: 680px; margin: 0 auto; padding: 3rem 1.5rem 5rem; }

/* Confetti animation */
@keyframes confetti-fall {
    0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
    100% { transform: translateY(120px) rotate(720deg); opacity: 0; }
}
.confetti-wrap {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    pointer-events: none; z-index: 9999; overflow: hidden;
}
.confetti-piece {
    position: absolute; top: -20px;
    width: 10px; height: 10px; border-radius: 2px;
    animation: confetti-fall 3s ease-in forwards;
}

/* Hero succès */
.success-hero {
    text-align: center;
    padding: 2.5rem 2rem;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 24px;
    border: 1px solid #bbf7d0;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
}
.success-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(34,197,94,0.12) 0%, transparent 70%);
}
.success-icon {
    width: 80px; height: 80px;
    background: #22c55e;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 0 0 12px rgba(34,197,94,0.15), 0 0 0 24px rgba(34,197,94,0.07);
    position: relative; z-index: 1;
}
.success-icon i { font-size: 2rem; color: white; }
.success-hero h1 {
    font-size: 1.9rem; font-weight: 900; color: #0f172a;
    margin-bottom: 0.5rem; position: relative; z-index: 1;
}
.success-hero p {
    color: #475569; font-size: 0.95rem; line-height: 1.7;
    margin: 0; position: relative; z-index: 1;
}

/* Référence */
.ref-card {
    background: white; border: 1px solid #e2e8f0; border-radius: 16px;
    padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    flex-wrap: wrap;
}
.ref-label { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-bottom: 2px; }
.ref-value { font-weight: 800; font-size: 0.95rem; color: #0f172a; font-family: monospace; letter-spacing: 0.05em; }
.ref-amount { font-weight: 900; font-size: 1.4rem; color: #2563eb; }

/* Produits */
.products-section { margin-bottom: 1.75rem; }
.section-title {
    font-size: 1rem; font-weight: 800; color: #0f172a;
    margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;
}
.section-title i { color: #2563eb; }

.product-row {
    background: white; border: 1px solid #e2e8f0; border-radius: 16px;
    padding: 1.1rem 1.25rem; margin-bottom: 0.75rem;
    display: flex; align-items: center; gap: 1rem;
    transition: box-shadow 0.2s;
}
.product-row:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
.product-thumb {
    width: 58px; height: 58px; border-radius: 12px;
    object-fit: cover; flex-shrink: 0;
    background: #f1f5f9; display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.product-thumb img { width: 100%; height: 100%; object-fit: cover; }
.product-thumb i { color: #94a3b8; font-size: 1.4rem; }
.product-info { flex: 1; min-width: 0; }
.product-name { font-weight: 700; color: #0f172a; font-size: 0.95rem; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product-prix { font-size: 0.82rem; color: #64748b; }

.btn-download {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.6rem 1.2rem;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white; border: none; border-radius: 11px;
    font-weight: 700; font-size: 0.85rem;
    text-decoration: none; cursor: pointer;
    transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(37,99,235,0.3);
}
.btn-download:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); color: white; }
.btn-download i { font-size: 0.9rem; }

/* Client connecté */
.client-banner {
    background: #eff6ff; border: 1px solid #bfdbfe;
    border-radius: 14px; padding: 1rem 1.25rem;
    margin-bottom: 1.75rem;
    display: flex; align-items: center; gap: 0.85rem;
}
.client-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 800; font-size: 1rem; flex-shrink: 0;
}
.client-banner-info { flex: 1; }
.client-banner-info strong { font-size: 0.875rem; color: #1e40af; display: block; }
.client-banner-info span { font-size: 0.8rem; color: #3b82f6; }

/* Boutons d'action */
.actions { display: flex; flex-direction: column; gap: 0.75rem; }
.btn-primary-action {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: #0f172a; color: white;
    border-radius: 14px; font-weight: 700; font-size: 0.95rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-primary-action:hover { background: #1e293b; color: white; transform: translateY(-1px); }
.btn-secondary-action {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: white; color: #475569;
    border: 1.5px solid #e2e8f0; border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-secondary-action:hover { border-color: #94a3b8; color: #0f172a; background: #f8fafc; }

/* Email info */
.email-info {
    background: #fffbeb; border: 1px solid #fde68a;
    border-radius: 12px; padding: 0.9rem 1.1rem;
    font-size: 0.83rem; color: #92400e; margin-bottom: 1.75rem;
    display: flex; align-items: flex-start; gap: 0.65rem;
}
.email-info i { color: #f59e0b; margin-top: 1px; flex-shrink: 0; }
</style>
@endpush

@section('content')

{{-- Confetti JS (lancé au load) --}}
<div class="confetti-wrap" id="confetti-wrap"></div>

<div class="succes-page">

    {{-- ── HERO SUCCÈS ─── --}}
    <div class="success-hero">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1>Paiement réussi ! 🎉</h1>
        <p>Merci pour votre achat. Vos produits sont disponibles immédiatement ci-dessous.<br>Un email de confirmation vous a été envoyé.</p>
    </div>

    {{-- ── RÉFÉRENCE ─── --}}
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

    {{-- ── CLIENT CONNECTÉ ─── --}}
    @if($transaction && $transaction->client)
    @php $clientEmail = $transaction->client->email; @endphp
    <div class="client-banner">
        <div class="client-avatar">{{ strtoupper(substr($transaction->client->nom ?? $clientEmail, 0, 1)) }}</div>
        <div class="client-banner-info">
            <strong><i class="fas fa-check-circle" style="color:#22c55e;"></i> Vous êtes connecté automatiquement</strong>
            <span>{{ $clientEmail }} — accédez à vos achats à tout moment</span>
        </div>
    </div>
    @endif

    {{-- ── PRODUITS + TÉLÉCHARGEMENT ─── --}}
    @if($achats && $achats->count() > 0)
    <div class="products-section">
        <div class="section-title">
            <i class="fas fa-download"></i>
            Vos produits — téléchargement immédiat
        </div>

        @foreach($achats as $achat)
        <div class="product-row">
            <div class="product-thumb">
                @if($achat->produit->image)
                    <img src="{{ asset('storage/' . $achat->produit->image) }}" alt="{{ $achat->produit->nom }}">
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

    {{-- ── UPSELLS ─── --}}
    @if(!empty($upsells) && $upsells->count() > 0)
    <div class="upsells-section" style="margin-bottom:1.75rem;">
        <div class="section-title">
            <i class="fas fa-fire" style="color:#f97316;"></i>
            Offres exclusives pour vous
        </div>

        @foreach($upsells as $upsell)
        @php $prix = $upsell->prix_effectif; $produit = $upsell->produitUpsell; @endphp
        @if($produit)
        <div style="background:linear-gradient(135deg,#fff7ed,#fff);border:2px solid #fed7aa;border-radius:18px;padding:1.25rem 1.5rem;margin-bottom:1rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">

            {{-- Image --}}
            <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                @if($produit->image)
                    <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i class="fas fa-box-open" style="color:#f97316;font-size:1.5rem;"></i>
                @endif
            </div>

            {{-- Infos --}}
            <div style="flex:1;min-width:150px;">
                <div style="font-size:0.8rem;font-weight:700;color:#ea580c;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:2px;">
                    {{ $upsell->titre_offre }}
                </div>
                <div style="font-weight:800;color:#0f172a;font-size:1rem;">{{ $produit->nom }}</div>
                @if($upsell->description_offre)
                    <div style="font-size:0.82rem;color:#64748b;margin-top:3px;">{{ $upsell->description_offre }}</div>
                @endif
                <div style="margin-top:6px;">
                    @if($upsell->prix_special !== null && $upsell->prix_special < $produit->prix)
                        <span style="text-decoration:line-through;color:#94a3b8;font-size:0.85rem;margin-right:6px;">
                            {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                        </span>
                        <span style="background:#dcfce7;color:#16a34a;font-weight:800;font-size:1rem;padding:2px 10px;border-radius:20px;">
                            {{ number_format($upsell->prix_special, 0, ',', ' ') }} FCFA
                        </span>
                    @else
                        <span style="font-weight:800;color:#f97316;font-size:1rem;">
                            {{ number_format($prix, 0, ',', ' ') }} FCFA
                        </span>
                    @endif
                </div>
            </div>

            {{-- Bouton --}}
            <a href="{{ route('boutique.checkout.produit', ['id' => $produit->id]) }}"
               style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f97316,#ea580c);color:white;text-decoration:none;padding:0.7rem 1.3rem;border-radius:12px;font-weight:700;font-size:0.9rem;white-space:nowrap;box-shadow:0 4px 14px rgba(249,115,22,0.35);transition:all 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-bolt"></i> Ajouter maintenant
            </a>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ── INFO EMAIL ─── --}}
    <div class="email-info">
        <i class="fas fa-envelope"></i>
        <div>
            Un email avec vos liens de téléchargement vous a été envoyé.
            Vous pouvez aussi retrouver tous vos achats dans votre espace client à tout moment.
        </div>
    </div>

    {{-- ── ACTIONS ─── --}}
    <div class="actions">
        <a href="{{ route('client.mes-achats.index') }}" class="btn-primary-action">
            <i class="fas fa-shopping-bag"></i> Voir tous mes achats
        </a>
        <a href="{{ route('boutique.accueil') }}" class="btn-secondary-action">
            <i class="fas fa-store"></i> Retourner à la boutique
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Confetti animation
(function() {
    const colors = ['#2563eb','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316'];
    const wrap   = document.getElementById('confetti-wrap');
    for (let i = 0; i < 60; i++) {
        const el = document.createElement('div');
        el.className = 'confetti-piece';
        el.style.cssText = [
            `left:${Math.random() * 100}%`,
            `background:${colors[Math.floor(Math.random() * colors.length)]}`,
            `width:${6 + Math.random() * 8}px`,
            `height:${6 + Math.random() * 8}px`,
            `border-radius:${Math.random() > 0.5 ? '50%' : '2px'}`,
            `animation-delay:${Math.random() * 2}s`,
            `animation-duration:${2.5 + Math.random() * 2}s`,
        ].join(';');
        wrap.appendChild(el);
    }
    // Supprimer après animation
    setTimeout(() => { if (wrap) wrap.remove(); }, 5000);
})();
</script>
@endpush
