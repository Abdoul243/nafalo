@extends('layouts.boutique')

@section('title', 'Paiement annulé')

@section('content')
<div style="max-width:600px;margin:4rem auto;padding:0 1rem;text-align:center;">

    <div style="width:80px;height:80px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
        <i class="fas fa-times" style="font-size:2rem;color:#ef4444;"></i>
    </div>

    <h1 style="font-size:1.8rem;font-weight:900;color:#0f172a;margin-bottom:0.5rem;">
        Paiement annulé
    </h1>
    <p style="color:#64748b;font-size:1rem;line-height:1.7;margin-bottom:2rem;">
        Votre paiement a été annulé. Votre panier est toujours disponible.
        Vous pouvez réessayer quand vous le souhaitez.
    </p>

    <div style="display:flex;flex-direction:column;gap:0.75rem;">
        <a href="{{ route('boutique.panier.index') }}"
           style="display:block;background:#2563eb;color:white;border-radius:12px;padding:0.875rem;font-weight:700;text-decoration:none;">
            <i class="fas fa-shopping-cart me-2"></i> Retourner au panier
        </a>
        <a href="{{ route('boutique.accueil') }}"
           style="display:block;background:white;color:#0f172a;border:1.5px solid #e2e8f0;border-radius:12px;padding:0.875rem;font-weight:600;text-decoration:none;">
            <i class="fas fa-arrow-left me-2"></i> Retour à la boutique
        </a>
    </div>
</div>
@endsection
