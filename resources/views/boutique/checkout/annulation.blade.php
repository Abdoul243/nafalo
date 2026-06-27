@extends('layouts.boutique')

@section('title', 'Paiement annulé')

@section('content')
<div style="max-width:560px;margin:5rem auto;padding:0 1.25rem;text-align:center;">

    <div style="width:80px;height:80px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
        <i class="fas fa-times" style="font-size:2rem;color:#ef4444;"></i>
    </div>

    <h1 style="font-family:'Playfair Display',Georgia,serif;font-size:1.75rem;font-weight:700;color:var(--text-1);margin-bottom:0.5rem;">
        Paiement annulé
    </h1>
    <p style="color:var(--text-3);font-size:0.95rem;line-height:1.7;margin-bottom:2rem;">
        Votre paiement a été annulé. Votre panier est toujours disponible.<br>
        Vous pouvez réessayer quand vous le souhaitez.
    </p>

    <div style="display:flex;flex-direction:column;gap:0.75rem;">
        <a href="{{ route('boutique.panier.index') }}"
           style="display:flex;align-items:center;justify-content:center;gap:9px;background:var(--accent);color:white;border-radius:14px;padding:1rem;font-weight:700;font-size:0.95rem;text-decoration:none;transition:all 0.2s;box-shadow:0 4px 18px rgba(124,58,237,0.3);">
            <i class="fas fa-shopping-cart"></i> Retourner au panier
        </a>
        <a href="{{ route('boutique.accueil') }}"
           style="display:flex;align-items:center;justify-content:center;gap:9px;background:rgba(255,255,255,0.04);color:var(--text-2);border:1px solid var(--border);border-radius:14px;padding:1rem;font-weight:600;font-size:0.9rem;text-decoration:none;transition:all 0.2s;">
            <i class="fas fa-arrow-left"></i> Retour à la boutique
        </a>
    </div>
</div>
@endsection
