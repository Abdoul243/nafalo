@extends('legal.layout')

@section('badge', 'Remboursements')
@section('title', 'Politique de Remboursement')
@section('subtitle', "Nafalo garantit une expérience d'achat de confiance. Voici les conditions dans lesquelles un remboursement peut être accordé.")

@section('content')

<div class="legal-section">
    <h2><span class="section-num">1</span> Principe général</h2>
    <p>Les produits vendus sur Nafalo étant des biens numériques à livraison instantanée, le droit de rétractation classique de 14 jours ne s'applique pas, conformément à la réglementation en vigueur sur les contenus numériques livrés immédiatement après l'achat.</p>
    <div class="highlight-box">
        <i class="fas fa-shield-alt"></i> Cependant, Nafalo et ses marchands s'engagent à traiter équitablement toute réclamation légitime d'un client insatisfait.
    </div>
</div>

<div class="legal-section">
    <h2><span class="section-num">2</span> Cas ouvrant droit à remboursement</h2>
    <p>Un remboursement peut être accordé dans les situations suivantes :</p>
    <ul>
        <li><strong>Produit non livré :</strong> le client n'a pas reçu son produit numérique après le paiement confirmé</li>
        <li><strong>Produit défectueux :</strong> le fichier est corrompu, illisible ou ne correspond pas à la description</li>
        <li><strong>Double facturation :</strong> le client a été facturé deux fois pour la même commande</li>
        <li><strong>Fraude avérée :</strong> paiement effectué sans le consentement du titulaire du compte</li>
        <li><strong>Produit non conforme :</strong> le produit reçu est substantiellement différent de ce qui était décrit</li>
    </ul>
</div>

<div class="legal-section">
    <h2><span class="section-num">3</span> Cas n'ouvrant pas droit à remboursement</h2>
    <div class="warn-box">
        <i class="fas fa-times-circle"></i> Les remboursements ne sont <strong>pas accordés</strong> dans les cas suivants :
    </div>
    <ul>
        <li>Le client a changé d'avis après le téléchargement du produit</li>
        <li>Le client ne dispose pas de la configuration technique requise (mentionnée dans la description du produit)</li>
        <li>Le client a déjà utilisé ou partagé le produit numérique</li>
        <li>La demande est effectuée plus de <strong>7 jours</strong> après l'achat sans raison valable</li>
        <li>Le produit a été acheté lors d'une promotion clairement indiquée comme non remboursable</li>
    </ul>
</div>

<div class="legal-section">
    <h2><span class="section-num">4</span> Procédure de demande</h2>
    <p>Pour demander un remboursement :</p>
    <ol>
        <li>Accédez à votre espace client dans la boutique concernée</li>
        <li>Rendez-vous dans "Mes achats" et sélectionnez la commande concernée</li>
        <li>Cliquez sur "Signaler un problème" et renseignez les détails</li>
        <li>Le marchand disposera de <strong>72 heures</strong> pour répondre à votre réclamation</li>
        <li>Si le marchand ne répond pas ou refuse injustement, contactez le support Nafalo</li>
    </ol>
    <p>Vous pouvez également contacter directement le support : <a href="mailto:support@nafalo.com">support@nafalo.com</a> avec votre référence de commande.</p>
</div>

<div class="legal-section">
    <h2><span class="section-num">5</span> Délais de remboursement</h2>
    <p>Une fois le remboursement approuvé :</p>
    <ul>
        <li><strong>Mobile Money (Wave, Orange Money, MTN MoMo) :</strong> sous 24 à 72 heures ouvrées</li>
        <li><strong>Carte bancaire :</strong> sous 5 à 10 jours ouvrés selon votre banque</li>
        <li><strong>Virement bancaire :</strong> sous 3 à 7 jours ouvrés</li>
    </ul>
    <div class="highlight-box">
        <i class="fas fa-info-circle"></i> La commission Nafalo de 5% est remboursée intégralement en cas de remboursement dû à une erreur de la plateforme ou du marchand.
    </div>
</div>

<div class="legal-section">
    <h2><span class="section-num">6</span> Responsabilité des marchands</h2>
    <p>Les marchands s'engagent à :</p>
    <ul>
        <li>Répondre aux réclamations clients dans les 72 heures</li>
        <li>Effectuer les remboursements légitimes sans opposition injustifiée</li>
        <li>Maintenir un taux de litige inférieur à 2% de leurs transactions</li>
    </ul>
    <p>Un marchand présentant un taux de litiges élevé ou refusant systématiquement les remboursements légitimes peut voir son compte suspendu par Nafalo.</p>
</div>

@endsection
