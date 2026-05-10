@extends('layouts.boutique')

@section('title', 'Merci pour votre achat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="card">
            <div class="card-body py-5">
                <i class="fas fa-check-circle text-success fa-5x mb-4"></i>
                
                <h1 class="mb-4">Merci pour votre achat !</h1>
                
                <p class="lead mb-4">
                    Votre paiement a bien été confirmé.<br>
                    Vous allez recevoir un email de confirmation avec vos liens de téléchargement.
                </p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Vous pouvez également retrouver vos achats dans votre espace client.
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('client.acces.demande') }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-download"></i> Accéder à mes achats
                    </a>
                    
                    <a href="{{ route('boutique.produit.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-shopping"></i> Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pixels Conversion -->
@if(isset($pixelService))
    {!! $pixelService->injecterConversion($boutique, [
        'montant' => $transaction->montant_total,
        'reference' => $transaction->reference,
        'devise' => $boutique->configuration->devise ?? 'XOF'
    ]) !!}
@endif
@endsection