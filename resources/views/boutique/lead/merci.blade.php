@extends('layouts.boutique')
@section('title', 'Merci ! Votre accès est prêt 🎁')

@push('styles')
<style>
.merci-page { max-width: 640px; margin: 0 auto; padding: 3rem 1.25rem 5rem; }

/* Hero */
.merci-hero {
    text-align: center;
    padding: 2.5rem 2rem 2rem;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 24px;
    border: 1px solid #bbf7d0;
    margin-bottom: 1.75rem;
}
.merci-icon {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #16a34a, #15803d);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 0 0 12px rgba(22,163,74,0.12), 0 0 0 24px rgba(22,163,74,0.06);
    font-size: 2.2rem;
}
.merci-hero h1 { font-size: 1.8rem; font-weight: 900; color: #14532d; margin-bottom: 0.5rem; }
.merci-hero p  { color: #475569; font-size: 0.95rem; line-height: 1.7; margin: 0; }

/* Download card */
.dl-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.5rem;
    margin-bottom: 1.75rem;
    display: flex; align-items: center; gap: 1.25rem;
}
.dl-thumb {
    width: 64px; height: 64px; border-radius: 14px;
    background: #f0fdf4; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
}
.dl-thumb img { width: 100%; height: 100%; object-fit: cover; }
.dl-thumb i   { color: #16a34a; font-size: 1.6rem; }
.dl-info { flex: 1; }
.dl-name { font-weight: 800; color: #0f172a; font-size: 1rem; }
.dl-sub  { color: #64748b; font-size: 0.82rem; margin-top: 2px; }
.btn-dl {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: white; text-decoration: none;
    padding: 0.7rem 1.4rem; border-radius: 12px;
    font-weight: 700; font-size: 0.9rem;
    white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(22,163,74,0.3);
    transition: all 0.2s;
}
.btn-dl:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 20px rgba(22,163,74,0.4); }

/* Email info */
.email-banner {
    background: #fffbeb; border: 1px solid #fde68a;
    border-radius: 12px; padding: 0.9rem 1.1rem;
    font-size: 0.84rem; color: #92400e;
    display: flex; align-items: flex-start; gap: 0.65rem;
    margin-bottom: 1.75rem;
}
.email-banner i { color: #f59e0b; margin-top: 1px; flex-shrink: 0; }

/* Upsell */
.upsell-section { margin-bottom: 1.75rem; }
.upsell-card {
    background: linear-gradient(135deg, #fff7ed, #fffbeb);
    border: 2px solid #fed7aa;
    border-radius: 20px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}
.upsell-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f97316, #ea580c);
}
.upsell-badge {
    display: inline-block;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; font-weight: 700; font-size: 0.75rem;
    padding: 3px 12px; border-radius: 20px;
    margin-bottom: 12px; letter-spacing: 0.3px;
}
.upsell-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.upsell-thumb {
    width: 60px; height: 60px; border-radius: 12px;
    overflow: hidden; flex-shrink: 0;
    background: #f8fafc; display: flex; align-items: center; justify-content: center;
}
.upsell-thumb img { width: 100%; height: 100%; object-fit: cover; }
.upsell-thumb i   { color: #f97316; font-size: 1.4rem; }
.upsell-info { flex: 1; min-width: 0; }
.upsell-name { font-weight: 800; color: #0f172a; font-size: 0.95rem; }
.upsell-desc { font-size: 0.82rem; color: #64748b; margin-top: 3px; }
.btn-upsell {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white; text-decoration: none;
    padding: 0.7rem 1.3rem; border-radius: 12px;
    font-weight: 700; font-size: 0.88rem;
    white-space: nowrap; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(249,115,22,0.35);
    transition: all 0.2s;
}
.btn-upsell:hover { transform: translateY(-2px); color: white; box-shadow: 0 8px 20px rgba(249,115,22,0.4); }

/* Actions */
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
.btn-secondary-action:hover { border-color: #94a3b8; color: #0f172a; }
</style>
@endpush

@section('content')
{{-- Confetti --}}
<div id="confetti-wrap" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;overflow:hidden;"></div>

<div class="merci-page">

    {{-- Hero --}}
    <div class="merci-hero">
        <div class="merci-icon">🎁</div>
        <h1>Votre accès est prêt !</h1>
        <p>
            Merci <strong>{{ $client->nom }}</strong> ! Votre fichier est disponible immédiatement.<br>
            Un email avec votre lien de téléchargement vient de vous être envoyé.
        </p>
    </div>

    {{-- Téléchargement direct --}}
    <div class="dl-card">
        <div class="dl-thumb">
            @if($produit->image)
                <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}">
            @else
                <i class="fas fa-file-download"></i>
            @endif
        </div>
        <div class="dl-info">
            <div class="dl-name">{{ $produit->nom }}</div>
            <div class="dl-sub">
                <span style="background:#dcfce7;color:#15803d;font-size:0.75rem;font-weight:700;padding:2px 8px;border-radius:10px;">
                    GRATUIT
                </span>
                &nbsp;Accès immédiat
            </div>
        </div>
        <a href="{{ route('client.telechargement', $achat) }}" class="btn-dl">
            <i class="fas fa-download"></i> Télécharger
        </a>
    </div>

    {{-- Info email --}}
    <div class="email-banner">
        <i class="fas fa-envelope"></i>
        <div>
            Un email avec votre lien de téléchargement a été envoyé à
            <strong>{{ $client->email }}</strong>. Vérifiez aussi vos spams si besoin.
        </div>
    </div>

    {{-- ── Upsells ─────────────────────────────────────────────────── --}}
    @if($upsells->isNotEmpty())
    <div class="upsell-section">
        @foreach($upsells as $upsell)
        @php $pu = $upsell->produitUpsell; @endphp
        @if($pu)
        <div class="upsell-card mb-3">
            <div class="upsell-badge">{{ $upsell->titre_offre }}</div>
            <div class="upsell-row">
                <div class="upsell-thumb">
                    @if($pu->image)
                        <img src="{{ asset('storage/' . $pu->image) }}" alt="{{ $pu->nom }}">
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
                            <span style="text-decoration:line-through;color:#94a3b8;font-size:0.82rem;margin-right:4px;">
                                {{ number_format($pu->prix, 0, ',', ' ') }} F CFA
                            </span>
                            <span style="background:#dcfce7;color:#16a34a;font-weight:800;padding:2px 10px;border-radius:20px;font-size:0.9rem;">
                                {{ number_format($upsell->prix_special, 0, ',', ' ') }} F CFA
                            </span>
                        @else
                            <span style="font-weight:800;color:#f97316;font-size:1rem;">
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
    const colors = ['#16a34a','#22c55e','#f59e0b','#667eea','#06b6d4','#f97316'];
    const wrap   = document.getElementById('confetti-wrap');
    for (let i = 0; i < 50; i++) {
        const el = document.createElement('div');
        el.style.cssText = [
            'position:absolute', `left:${Math.random()*100}%`, 'top:-20px',
            `background:${colors[Math.floor(Math.random()*colors.length)]}`,
            `width:${6+Math.random()*8}px`, `height:${6+Math.random()*8}px`,
            `border-radius:${Math.random()>.5?'50%':'2px'}`,
            `animation:fall ${2.5+Math.random()*2}s ease-in ${Math.random()*2}s forwards`
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
