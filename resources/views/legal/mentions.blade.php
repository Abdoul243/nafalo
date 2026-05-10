@extends('legal.layout')

@section('badge', 'Mentions légales')
@section('title', 'Mentions Légales')
@section('subtitle', "Informations légales relatives à la plateforme Nafalo conformément aux dispositions légales en vigueur.")

@section('content')

<div class="legal-section">
    <h2><span class="section-num">1</span> Éditeur de la plateforme</h2>
    <ul>
        <li><strong>Nom de la plateforme :</strong> Nafalo</li>
        <li><strong>Statut juridique :</strong> Entreprise individuelle / Startup</li>
        <li><strong>Siège social :</strong> Abidjan, Côte d'Ivoire</li>
        <li><strong>Email :</strong> contact@nafalo.com</li>
        <li><strong>Directeur de la publication :</strong> L'équipe Nafalo</li>
    </ul>
</div>

<div class="legal-section">
    <h2><span class="section-num">2</span> Hébergement</h2>
    <p>La plateforme Nafalo est hébergée par :</p>
    <ul>
        <li><strong>Hébergeur :</strong> À définir selon l'environnement de production</li>
        <li><strong>Adresse :</strong> —</li>
        <li><strong>Site web :</strong> —</li>
    </ul>
    <div class="highlight-box">
        <i class="fas fa-server"></i> En environnement de développement local (Laragon), la plateforme est hébergée localement sur le poste du développeur.
    </div>
</div>

<div class="legal-section">
    <h2><span class="section-num">3</span> Propriété intellectuelle</h2>
    <p>L'ensemble des éléments constituant la plateforme Nafalo (marque, logo, interface, code source, textes, images) sont protégés par le droit de la propriété intellectuelle et sont la propriété exclusive de Nafalo.</p>
    <p>Toute reproduction, représentation, modification, publication ou adaptation de tout ou partie de ces éléments, quel que soit le moyen ou le procédé utilisé, est interdite sauf autorisation écrite préalable de Nafalo.</p>
</div>

<div class="legal-section">
    <h2><span class="section-num">4</span> Limitation de responsabilité</h2>
    <p>Nafalo s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur la plateforme. Cependant, Nafalo ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition.</p>
    <p>Nafalo décline toute responsabilité pour :</p>
    <ul>
        <li>Toute imprécision, inexactitude ou omission portant sur des informations disponibles sur la plateforme</li>
        <li>Tous dommages résultant d'une intrusion frauduleuse d'un tiers</li>
        <li>Et plus généralement, tous dommages directs ou indirects, quelles qu'en soient les causes, origines, natures ou conséquences</li>
    </ul>
</div>

<div class="legal-section">
    <h2><span class="section-num">5</span> Liens hypertextes</h2>
    <p>La plateforme Nafalo peut contenir des liens vers d'autres sites internet. Nafalo n'exerce aucun contrôle sur ces sites et n'assume aucune responsabilité quant à leur contenu.</p>
    <p>La création de liens hypertextes vers la plateforme Nafalo est soumise à autorisation préalable écrite de Nafalo.</p>
</div>

<div class="legal-section">
    <h2><span class="section-num">6</span> Droit applicable</h2>
    <p>Les présentes mentions légales sont soumises au droit ivoirien. En cas de litige, les juridictions compétentes sont celles d'Abidjan, Côte d'Ivoire.</p>
</div>

@endsection
