@extends('layouts.boutique')
@section('title', 'Votre accès est prêt !')

@push('styles')
<style>
.merci-page { max-width: 640px; margin: 0 auto; padding: 3rem 1.25rem 5rem; }

/* Hero */
.merci-hero {
    text-align: center;
    padding: 2.5rem 2rem 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
}
.merci-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 50% -20%, rgba(34,197,94,0.12) 0%, transparent 65%);
    pointer-events: none;
}
.merci-icon {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 0 0 12px rgba(34,197,94,0.1), 0 0 0 24px rgba(34,197,94,0.05);
    font-size: 2.2rem;
    position: relative; z-index: 1;
    animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
}
@keyframes pop-in { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.merci-hero h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.75rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 0.5rem;
    position: relative; z-index: 1;
}
.merci-hero p {
    color: var(--text-3); font-size: 0.9rem; line-height: 1.7;
    margin: 0; position: relative; z-index: 1;
}

/* Download card */
.dl-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1.25rem;
    transition: border-color 0.2s;
}
.dl-card:hover { border-color: rgba(34,197,94,0.3); }
.dl-thumb {
    width: 64px; height: 64px; border-radius: 14px;
    background: rgba(34,197,94,0.1);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
}
.dl-thumb img { width: 100%; height: 100%; object-fit: cover; }
.dl-thumb i { color: #22c55e; font-size: 1.6rem; }
.dl-info { flex: 1; }
.dl-name { font-weight: 800; color: var(--text-1); font-size: 0.95rem; }
.dl-sub { color: var(--text-3); font-size: 0.8rem; margin-top: 3px; }
.dl-free-badge {
    display: inline-block;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.25);
    color: #22c55e; font-size: 0.72rem; font-weight: 800;
    padding: 2px 9px; border-radius: 20px;
    margin-right: 6px;
}
.btn-dl {
    display: inline-flex; align-items: center; gap: 8px;
    background: #16a34a; color: white; text-decoration: none;
    padding: 0.7rem 1.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.88rem;
    white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(22,163,74,0.35);
    transition: all 0.2s;
}
.btn-dl:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 20px rgba(22,163,74,0.45); }

/* Email banner */
.email-banner {
    background: rgba(234,179,8,0.07);
    border: 1px solid rgba(234,179,8,0.2);
    border-radius: 12px; padding: 0.9rem 1.1rem;
    font-size: 0.82rem; color: var(--text-2);
    display: flex; align-items: flex-start; gap: 0.65rem;
    margin-bottom: 1.5rem;
}
.email-banner i { color: #eab308; margin-top: 1px; flex-shrink: 0; }

/* Upsell */
.upsell-card {
    background: var(--bg-card);
    border: 1px solid rgba(249,115,22,0.25);
    border-radius: 20px; padding: 1.5rem;
    position: relative; overflow: hidden; margin-bottom: 1rem;
}
.upsell-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 2px; background: linear-gradient(90deg, #f97316, #ea580c);
}
.upsell-badge {
    display: inline-block;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; font-weight: 700; font-size: 0.72rem;
    padding: 3px 12px; border-radius: 20px; margin-bottom: 12px;
}
.upsell-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.upsell-thumb {
    width: 56px; height: 56px; border-radius: 12px;
    overflow: hidden; flex-shrink: 0;
    background: rgba(255,255,255,0.05);
    display: flex; align-items: center; justify-content: center;
}
.upsell-thumb img { width: 100%; height: 100%; object-fit: cover; }
.upsell-thumb i { color: #f97316; font-size: 1.4rem; }
.upsell-info { flex: 1; min-width: 0; }
.upsell-name { font-weight: 800; color: var(--text-1); font-size: 0.92rem; }
.upsell-desc { font-size: 0.8rem; color: var(--text-3); margin-top: 3px; }
.btn-upsell {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; text-decoration: none;
    padding: 0.65rem 1.2rem; border-radius: 12px;
    font-weight: 700; font-size: 0.85rem;
    white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(249,115,22,0.35); transition: all 0.2s;
}
.btn-upsell:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 20px rgba(249,115,22,0.45); }

/* Actions */
.actions { display: flex; flex-direction: column; gap: 0.75rem; }
.btn-primary-action {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: var(--accent); color: white;
    border-radius: 14px; font-weight: 700; font-size: 0.95rem;
    text-decoration: none; transition: all 0.2s;
    box-shadow: 0 4px 18px rgba(124,58,237,0.3);
}
.btn-primary-action:hover { background: var(--accent-hover); color: white; transform: translateY(-1px); }
.btn-secondary-action {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 1rem; background: rgba(255,255,255,0.04); color: var(--text-2);
    border: 1px solid var(--border); border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-secondary-action:hover { color: var(--text-1); border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.07); }
</style>
@endpush

@section('content')
<div id="confetti-wrap" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;overflow:hidden;"></div>

<div class="merci-page">

    {{-- Hero --}}
    <div class="merci-hero">
        <div class="merci-icon">🎁</div>
        <h1>Votre accès est prêt !</h1>
        <p>
            Merci <strong style="color:var(--text-1);">{{ $client->nom }}</strong> ! Votre fichier est disponible immédiatement.<br>
            Un email avec votre lien de téléchargement vient de vous être envoyé.
        </p>
    </div>

    {{-- Download --}}
    <div class="dl-card">
        <div class="dl-thumb">
            @if($produit->image)
                <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}">
            @else
                <i class="fas fa-file-download"></i>
            @endif
        </div>
        <div class="dl-info">
            <div class="dl-name">{{ $produit->nom }}</div>
            <div class="dl-sub">
                <span class="dl-free-badge">GRATUIT</span>
                Accès immédiat
            </div>
        </div>
        <a href="{{ route('client.telechargement', $achat) }}" class="btn-dl">
            <i class="fas fa-download"></i> Télécharger
        </a>
    </div>

    {{-- Email info --}}
    <div class="email-banner">
        <i class="fas fa-envelope"></i>
        <div>
            Un email avec votre lien de téléchargement a été envoyé à
            <strong>{{ $client->email }}</strong>. Vérifiez aussi vos spams si besoin.
        </div>
    </div>

    {{-- Upsells --}}
    @if($upsells->isNotEmpty())
    <div style="margin-bottom:1.5rem;">
        @foreach($upsells as $upsell)
        @php $pu = $upsell->produitUpsell; @endphp
        @if($pu)
        <div class="upsell-card">
            <div class="upsell-badge">{{ $upsell->titre_offre }}</div>
            <div class="upsell-row">
                <div class="upsell-thumb">
                    @if($pu->image)
                        <img src="{{ $pu->image_url }}" alt="{{ $pu->nom }}">
                    @else
                        <i class="fas fa-box-open"></i>
                    @endif
                </div>
                <div class="upsell-info">
                    <div class="upsell-name">{{ $pu->nom }}</div>
                    @if($upsell->description_offre)
                        <div class="upsell-desc">{{ $upsell->description_offre }}</div>
                    @endif
                    <div style="margin-top:6px;">
                        @if($upsell->prix_special !== null && $upsell->prix_special < $pu->prix)
                            <span style="text-decoration:line-through;color:var(--text-3);font-size:0.8rem;margin-right:4px;">
                                {{ number_format($pu->prix, 0, ',', ' ') }} F CFA
                            </span>
                            <span style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;font-weight:800;padding:2px 10px;border-radius:20px;font-size:0.88rem;">
                                {{ number_format($upsell->prix_special, 0, ',', ' ') }} F CFA
                            </span>
                        @else
                            <span style="font-weight:800;color:#f97316;font-size:0.95rem;">
                                {{ number_format($upsell->prix_effectif, 0, ',', ' ') }} F CFA
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('boutique.checkout.produit', ['id' => $pu->id]) }}" class="btn-upsell">
                    <i class="fas fa-bolt"></i> Je veux ça
                </a>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- Actions --}}
    <div class="actions">
        <a href="{{ route('client.mes-achats.index') }}" class="btn-primary-action">
            <i class="fas fa-folder-open"></i> Voir mes téléchargements
        </a>
        <a href="{{ route('boutique.accueil') }}" class="btn-secondary-action">
            <i class="fas fa-store"></i> Retourner à la boutique
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function() {
    const colors = ['#7c3aed','#22c55e','#f59e0b','#3b82f6','#ec4899','#f97316','#a855f7'];
    const wrap = document.getElementById('confetti-wrap');
    for (let i = 0; i < 50; i++) {
        const el = document.createElement('div');
        el.style.cssText = [
            'position:absolute', `left:${Math.random()*100}%`, 'top:-20px',
            `background:${colors[Math.floor(Math.random()*colors.length)]}`,
            `width:${6+Math.random()*8}px`, `height:${6+Math.random()*8}px`,
            `border-radius:${Math.random()>.5?'50%':'3px'}`,
            `animation:fall ${2.5+Math.random()*2}s ease-in ${Math.random()*1.5}s forwards`
        ].join(';');
        wrap.appendChild(el);
    }
    const style = document.createElement('style');
    style.textContent = '@keyframes fall{0%{transform:translateY(-20px) rotate(0);opacity:1}100%{transform:translateY(110vh) rotate(720deg);opacity:0}}';
    document.head.appendChild(style);
    setTimeout(() => wrap.remove(), 5000);
})();
</script>
@endpush
