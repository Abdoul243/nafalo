@extends('layouts.boutique')

@section('title', $produit->nom)

@section('content')
<style>
    .product-page { background: #f8f9fa; min-height: 100vh; }
    .product-hero {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .product-image-wrapper {
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        overflow: hidden;
    }
    .product-image-wrapper img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .product-info { padding: 2.5rem; }
    .product-badge {
        display: inline-block;
        background: #e8f5e9;
        color: #2e7d32;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .product-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }
    .product-price {
        font-size: 2.2rem;
        font-weight: 900;
        color: #2563eb;
        margin: 1.5rem 0;
    }
    .btn-buy {
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }
    .btn-buy:hover {
        background: #1d4ed8;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(37,99,235,0.4);
        color: white;
    }
    .features-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    .features-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        color: #555;
        font-size: 0.95rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .features-list li:last-child { border: none; }
    .features-list li i { color: #2563eb; width: 20px; }
    .description-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 30px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
    }
    .description-card h3 {
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .description-content {
        color: #444;
        line-height: 1.8;
    }
    .description-content img { max-width: 100%; border-radius: 10px; margin: 1rem 0; }
    .description-content h1, .description-content h2, .description-content h3 { color: #0f172a; margin-top: 1.5rem; }
    .description-content ul, .description-content ol { padding-left: 1.5rem; }
    .stars i { color: #ffc107; }
    .review-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .similar-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        height: 100%;
    }
    .similar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    .similar-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .similar-card .card-body { padding: 1.2rem; }
    .guarantee-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 1.2rem;
        margin-top: 1.5rem;
    }

    /* Urgency / social proof */
    .urgency-bar { background: linear-gradient(135deg, #fff7ed, #fef3c7); border: 1px solid #fed7aa; border-radius: 11px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; }
    .urgency-bar i { color: #f97316; font-size: 1.1rem; flex-shrink: 0; }
    .urgency-bar span { font-size: 0.875rem; font-weight: 600; color: #9a3412; }
    .urgency-bar strong { color: #ea580c; }

    .social-proof { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #64748b; margin-bottom: 1rem; }
    .social-proof .avatars { display: flex; }
    .social-proof .av { width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; background: linear-gradient(135deg, #2563eb, #7c3aed); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.55rem; font-weight: 800; margin-left: -6px; }
    .social-proof .av:first-child { margin-left: 0; }

    .countdown { display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem; font-weight: 700; }
    .cd-seg { background: #0f172a; color: white; border-radius: 6px; padding: 2px 6px; font-variant-numeric: tabular-nums; min-width: 28px; text-align: center; }
    .cd-sep { color: #64748b; font-weight: 400; }

    .rating-bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .rating-bar-label { font-size: 0.78rem; color: #94a3b8; width: 48px; flex-shrink: 0; }
    .rating-bar-track { flex: 1; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
    .rating-bar-fill  { height: 100%; background: #f59e0b; border-radius: 3px; }
    .rating-bar-count { font-size: 0.75rem; color: #94a3b8; width: 20px; flex-shrink: 0; text-align: right; }
</style>

<div class="product-page py-4">
<div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('boutique.accueil') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('boutique.produit.index') }}">Produits</a></li>
            @if($produit->categorie)
                <li class="breadcrumb-item">{{ $produit->categorie->nom }}</li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($produit->nom, 30) }}</li>
        </ol>
    </nav>


    <div class="product-hero">
        <div class="row g-0">
            {{-- Image --}}
            <div class="col-md-5">
                <div class="product-image-wrapper">
                    @if($produit->image)
                        <img src="{{ asset('storage/' . $produit->image) }}"
                             alt="{{ $produit->nom }}">
                    @else
                        <div class="text-center p-5">
                            <i class="fas fa-box-open fa-5x text-muted opacity-25"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Infos --}}
            <div class="col-md-7">
                <div class="product-info">
                    <span class="product-badge">
                        <i class="fas fa-check-circle me-1"></i> Produit digital
                    </span>

                    <h1 class="product-title">{{ $produit->nom }}</h1>

                    {{-- Étoiles --}}
                    @php
                        $moyenne = $produit->avis->where('est_visible', true)->avg('note') ?? 0;
                        $totalAvis = $produit->avis->where('est_visible', true)->count();
                    @endphp
                    @if($totalAvis > 0)
                    <div class="stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($moyenne) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                        <span class="text-muted ms-2 small">({{ $totalAvis }} avis)</span>
                    </div>
                    @endif

                    <div class="product-price">
                        @if($produit->estGratuit())
                            <span style="background:#dcfce7;color:#15803d;font-size:1rem;font-weight:800;padding:6px 18px;border-radius:20px;display:inline-block;">
                                🎁 GRATUIT
                            </span>
                        @else
                            {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                        @endif
                    </div>

                    {{-- Social proof --}}
                    @php $nbVentes = $produit->achats()->count(); @endphp
                    @if($nbVentes > 0)
                    <div class="social-proof">
                        <div class="avatars">
                            @for($av = 0; $av < min(4, $nbVentes); $av++)
                            <div class="av">{{ chr(65 + $av) }}</div>
                            @endfor
                        </div>
                        <span><strong style="color:#0f172a;">{{ $nbVentes }} personne{{ $nbVentes > 1 ? 's' : '' }}</strong> {{ $nbVentes > 1 ? 'ont' : 'a' }} déjà acheté ce produit</span>
                    </div>
                    @endif

                    {{-- Urgence --}}
                    <div class="urgency-bar">
                        <i class="fas fa-fire"></i>
                        <span>🔥 Téléchargement disponible immédiatement · Offre valable &nbsp;
                            <span class="countdown">
                                <span class="cd-seg" id="cd-h">23</span><span class="cd-sep">h</span>
                                <span class="cd-seg" id="cd-m">59</span><span class="cd-sep">m</span>
                                <span class="cd-seg" id="cd-s">59</span><span class="cd-sep">s</span>
                            </span>
                        </span>
                    </div>

                    {{-- ══ PRODUIT PAYANT ══ --}}
                    @if($produit->estPayant())
                    <form action="{{ route('boutique.panier.ajouter', $produit) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="btn btn-buy">
                            <i class="fas fa-cart-plus me-2"></i> Ajouter au panier
                        </button>
                    </form>

                    <ul class="features-list mt-3">
                        <li><i class="fas fa-bolt"></i> Téléchargement immédiat après paiement</li>
                        <li><i class="fas fa-infinity"></i> Accès à vie</li>
                        <li><i class="fas fa-sync-alt"></i> Mises à jour incluses</li>
                        <li><i class="fas fa-shield-alt"></i> Paiement sécurisé</li>
                    </ul>
                    <div class="guarantee-box">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-medal fa-2x text-warning"></i>
                            <div>
                                <div class="fw-bold text-dark">Satisfait ou remboursé</div>
                                <div class="text-muted small">7 jours pour changer d'avis</div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ PRODUIT GRATUIT / LEAD MAGNET ══ --}}
                    @else
                    @if($produit->limiteAtteinte())
                        {{-- Limite atteinte --}}
                        <div class="alert border-0 rounded-3 text-center py-3" style="background:#fef2f2;color:#991b1b;">
                            <i class="fas fa-lock fa-2x mb-2 d-block"></i>
                            <strong>Ce produit gratuit n'est plus disponible.</strong><br>
                            <small>La limite de {{ number_format($produit->lead_limite_dl, 0, ',', ' ') }} téléchargements a été atteinte.</small>
                        </div>
                    @else
                        {{-- Compteur de places si limite définie --}}
                        @if($produit->lead_limite_dl)
                        <div class="urgency-bar mb-3">
                            <i class="fas fa-fire"></i>
                            <span>
                                Plus que <strong>{{ $produit->placesRestantes() }}</strong> téléchargements gratuits disponibles !
                            </span>
                        </div>
                        @endif

                        {{-- Bouton déclencheur du formulaire --}}
                        <button type="button" class="btn btn-buy" style="background:linear-gradient(135deg,#16a34a,#15803d);"
                                onclick="document.getElementById('lead-form-modal').style.display='flex'">
                            <i class="fas fa-gift me-2"></i> Obtenir gratuitement
                        </button>

                        <ul class="features-list mt-3">
                            <li><i class="fas fa-bolt" style="color:#16a34a;"></i> Accès instantané par email</li>
                            <li><i class="fas fa-lock-open" style="color:#16a34a;"></i> 100 % gratuit, sans engagement</li>
                            <li><i class="fas fa-infinity" style="color:#16a34a;"></i> Téléchargement illimité</li>
                        </ul>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($produit->description)
    <div class="description-card">
        <h3><i class="fas fa-align-left me-2 text-primary"></i> Description</h3>
        <div class="description-content">{!! $produit->description !!}</div>
    </div>
    @endif

    {{-- Avis --}}
    @if($totalAvis > 0)
    @php
        $avisVisible = $produit->avis->where('est_visible', true);
        $distribution = [];
        for ($n = 5; $n >= 1; $n--) {
            $distribution[$n] = $avisVisible->where('note', $n)->count();
        }
    @endphp
    <div class="description-card">
        <h3><i class="fas fa-star me-2 text-warning"></i> Avis clients ({{ $totalAvis }})</h3>

        {{-- Résumé notes --}}
        <div style="display:flex;gap:2rem;align-items:center;padding:1.25rem;background:#fafafa;border-radius:14px;margin-bottom:1.5rem;flex-wrap:wrap;">
            <div style="text-align:center;flex-shrink:0;">
                <div style="font-size:3.5rem;font-weight:900;color:#0f172a;line-height:1;">{{ number_format($moyenne, 1) }}</div>
                <div class="stars" style="justify-content:center;display:flex;gap:2px;margin:4px 0;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= round($moyenne) ? 'fas' : 'far' }} fa-star" style="font-size:0.85rem;"></i>
                    @endfor
                </div>
                <div style="font-size:0.78rem;color:#94a3b8;">{{ $totalAvis }} avis vérifiés</div>
            </div>
            <div style="flex:1;min-width:180px;">
                @for($n = 5; $n >= 1; $n--)
                <div class="rating-bar-row">
                    <div class="rating-bar-label">{{ $n }} ⭐</div>
                    <div class="rating-bar-track">
                        <div class="rating-bar-fill" style="width:{{ $totalAvis > 0 ? round(($distribution[$n]/$totalAvis)*100) : 0 }}%;"></div>
                    </div>
                    <div class="rating-bar-count">{{ $distribution[$n] }}</div>
                </div>
                @endfor
            </div>
        </div>

        @foreach($avisVisible as $avis)
        <div class="review-card">
            <div class="d-flex justify-content-between align-items-start">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:0.9rem;flex-shrink:0;">
                        {{ strtoupper(substr($avis->client->nom ?? $avis->client->email ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:0.9rem;">{{ $avis->client->nom ?? 'Client vérifié' }}</div>
                        <div class="stars" style="display:flex;gap:2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avis->note ? 'fas' : 'far' }} fa-star" style="font-size:0.75rem;"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <small class="text-muted">{{ $avis->created_at->format('d/m/Y') }}</small>
                    <div style="display:flex;align-items:center;gap:4px;font-size:0.72rem;color:#22c55e;margin-top:2px;">
                        <i class="fas fa-check-circle"></i> Achat vérifié
                    </div>
                </div>
            </div>
            @if($avis->commentaire)
            <p class="mt-2 mb-0 text-muted" style="font-size:0.9rem;line-height:1.65;">{{ $avis->commentaire }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Produits similaires --}}
    @if($produitsSimilaires->count() > 0)
    <div class="description-card">
        <h3><i class="fas fa-th-large me-2 text-primary"></i> Produits similaires</h3>
        <div class="row g-3">
            @foreach($produitsSimilaires as $similaire)
            <div class="col-md-3">
                <div class="similar-card">
                    @if($similaire->image)
                        <img src="{{ asset('storage/' . $similaire->image) }}"
                             alt="{{ $similaire->nom }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:160px">
                            <i class="fas fa-image fa-3x text-muted opacity-25"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ Str::limit($similaire->nom, 40) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold" style="color:#2563eb;">{{ number_format($similaire->prix, 0, ',', ' ') }} FCFA</span>
                            <a href="{{ route('boutique.produit.show', $similaire->slug) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>

{{-- ══ MODAL LEAD MAGNET ══ --}}
@if($produit->estGratuit() && !$produit->limiteAtteinte())
<div id="lead-form-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:1rem;"
     onclick="if(event.target===this) this.style.display='none'">

    <div style="background:white;border-radius:24px;width:100%;max-width:480px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,0.25);animation:slideUp .3s ease;">

        {{-- En-tête modal --}}
        <div style="background:linear-gradient(135deg,#16a34a,#15803d);padding:28px 28px 20px;position:relative;">
            <button onclick="document.getElementById('lead-form-modal').style.display='none'"
                    style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,0.2);border:none;color:white;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;">
                ✕
            </button>
            <div style="font-size:2rem;margin-bottom:8px;">🎁</div>
            <h3 style="color:white;margin:0;font-size:1.3rem;font-weight:800;">{{ $produit->nom }}</h3>
            <p style="color:rgba(255,255,255,0.85);margin:6px 0 0;font-size:0.9rem;">
                Entrez vos informations pour recevoir votre accès gratuit immédiatement.
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('boutique.lead.capturer', $produit) }}" method="POST" style="padding:24px 28px 28px;">
            @csrf

            {{-- Nom --}}
            <div style="margin-bottom:14px;">
                <label style="font-weight:600;font-size:0.85rem;color:#374151;display:block;margin-bottom:5px;">
                    Votre prénom & nom *
                </label>
                <input type="text" name="nom" required placeholder="Ex: Moussa Traoré"
                       style="width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:10px 14px;font-size:0.95rem;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#16a34a'" onblur="this.style.borderColor='#d1d5db'">
            </div>

            {{-- Email --}}
            <div style="margin-bottom:14px;">
                <label style="font-weight:600;font-size:0.85rem;color:#374151;display:block;margin-bottom:5px;">
                    Votre email *
                </label>
                <input type="email" name="email" required placeholder="votre@email.com"
                       style="width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:10px 14px;font-size:0.95rem;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#16a34a'" onblur="this.style.borderColor='#d1d5db'">
            </div>

            {{-- Champs optionnels activés par le marchand --}}
            @foreach($produit->champsLeadActifs() as $champ)
            @php
                $labels = ['telephone'=>'Téléphone','ville'=>'Ville','profession'=>'Profession / Métier','pays'=>'Pays'];
                $placeholders = ['telephone'=>'Ex: +221 77 000 00 00','ville'=>'Ex: Dakar','profession'=>'Ex: Entrepreneur','pays'=>'Ex: Sénégal'];
                $types = ['telephone'=>'tel','ville'=>'text','profession'=>'text','pays'=>'text'];
            @endphp
            <div style="margin-bottom:14px;">
                <label style="font-weight:600;font-size:0.85rem;color:#374151;display:block;margin-bottom:5px;">
                    {{ $labels[$champ] ?? $champ }}
                </label>
                <input type="{{ $types[$champ] ?? 'text' }}" name="{{ $champ }}"
                       placeholder="{{ $placeholders[$champ] ?? '' }}"
                       style="width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:10px 14px;font-size:0.95rem;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#16a34a'" onblur="this.style.borderColor='#d1d5db'">
            </div>
            @endforeach

            {{-- Soumission --}}
            <button type="submit"
                    style="width:100%;background:linear-gradient(135deg,#16a34a,#15803d);color:white;border:none;border-radius:12px;padding:14px;font-weight:700;font-size:1rem;cursor:pointer;margin-top:8px;transition:opacity .2s;"
                    onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-paper-plane me-2"></i> Envoyer et recevoir mon accès
            </button>

            <p style="font-size:0.75rem;color:#9ca3af;text-align:center;margin:12px 0 0;">
                🔒 Vos données sont confidentielles. Aucun spam.
            </p>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
</style>
@endif

@endsection

@push('scripts')
<script>
// Countdown urgency timer
(function() {
    // Reset each visit (or read from sessionStorage)
    const key = 'nafalo_cd_{{ $produit->id }}';
    let end = sessionStorage.getItem(key);
    if (!end) {
        end = Date.now() + 24 * 3600 * 1000; // 24h
        sessionStorage.setItem(key, end);
    }
    end = parseInt(end);

    function pad(n) { return n < 10 ? '0' + n : n; }

    function tick() {
        const diff = Math.max(0, end - Date.now());
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const elH = document.getElementById('cd-h');
        const elM = document.getElementById('cd-m');
        const elS = document.getElementById('cd-s');
        if (elH) elH.textContent = pad(h);
        if (elM) elM.textContent = pad(m);
        if (elS) elS.textContent = pad(s);
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush