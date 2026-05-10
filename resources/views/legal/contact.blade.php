@extends('legal.layout')

@section('badge', 'Contact')
@section('title', 'Contactez-nous')
@section('subtitle', "Une question, un problème ou une suggestion ? L'équipe Nafalo est là pour vous aider.")

@section('content')

<style>
    .contact-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem; }
    @media(max-width:640px){ .contact-grid { grid-template-columns:1fr; } }
    .contact-item {
        background:white; border-radius:16px; border:1px solid #e2e8f0;
        padding:1.75rem; text-align:center;
        box-shadow:0 1px 6px rgba(0,0,0,0.04);
        transition:all 0.2s;
    }
    .contact-item:hover { border-color:#bfdbfe; transform:translateY(-3px); box-shadow:0 8px 24px rgba(37,99,235,0.08); }
    .contact-icon {
        width:56px; height:56px; border-radius:16px;
        background:#eff6ff; display:flex; align-items:center; justify-content:center;
        margin:0 auto 1rem; font-size:1.4rem;
    }
    .contact-item h3 { font-size:1rem; font-weight:700; margin-bottom:0.35rem; }
    .contact-item p { color:#64748b; font-size:0.85rem; margin-bottom:1rem; line-height:1.6; }
    .contact-item a {
        display:inline-flex; align-items:center; gap:6px;
        color:#2563eb; font-weight:600; font-size:0.875rem;
        text-decoration:none;
    }
    .contact-item a:hover { text-decoration:underline; }

    .faq-section { background:white; border-radius:16px; border:1px solid #e2e8f0; padding:2rem 2.5rem; box-shadow:0 1px 6px rgba(0,0,0,0.04); }
    .faq-item { border-bottom:1px solid #f1f5f9; padding:1.25rem 0; }
    .faq-item:last-child { border-bottom:none; padding-bottom:0; }
    .faq-q { font-weight:700; font-size:0.9rem; color:#0f172a; margin-bottom:0.5rem; display:flex; align-items:center; gap:8px; }
    .faq-q::before { content:'Q'; background:#eff6ff; color:#2563eb; font-size:0.72rem; font-weight:800; padding:2px 7px; border-radius:6px; flex-shrink:0; }
    .faq-a { font-size:0.875rem; color:#374151; line-height:1.7; padding-left:28px; }
</style>

{{-- Canaux de contact --}}
<div class="contact-grid">
    <div class="contact-item">
        <div class="contact-icon">📧</div>
        <h3>Support général</h3>
        <p>Pour toute question sur l'utilisation de la plateforme, vos ventes ou votre boutique.</p>
        <a href="mailto:support@nafalo.com"><i class="fas fa-envelope"></i> support@nafalo.com</a>
    </div>
    <div class="contact-item">
        <div class="contact-icon">🔒</div>
        <h3>Données & Confidentialité</h3>
        <p>Pour exercer vos droits RGPD ou signaler une violation de données personnelles.</p>
        <a href="mailto:privacy@nafalo.com"><i class="fas fa-lock"></i> privacy@nafalo.com</a>
    </div>
    <div class="contact-item">
        <div class="contact-icon">⚖️</div>
        <h3>Questions légales</h3>
        <p>Pour toute question juridique, signalement de contenu illicite ou mise en demeure.</p>
        <a href="mailto:legal@nafalo.com"><i class="fas fa-gavel"></i> legal@nafalo.com</a>
    </div>
    <div class="contact-item">
        <div class="contact-icon">🤝</div>
        <h3>Partenariats</h3>
        <p>Vous souhaitez intégrer Nafalo à votre solution ou proposer un partenariat ?</p>
        <a href="mailto:partners@nafalo.com"><i class="fas fa-handshake"></i> partners@nafalo.com</a>
    </div>
</div>

{{-- Délais de réponse --}}
<div class="legal-section" style="margin-bottom:1.5rem;">
    <h2><span class="section-num"><i class="fas fa-clock" style="font-size:0.85rem;"></i></span> Délais de réponse</h2>
    <ul>
        <li><strong>Support général :</strong> réponse sous 24 à 48 heures ouvrées</li>
        <li><strong>Urgences (compte bloqué, fraude) :</strong> réponse sous 4 heures</li>
        <li><strong>Questions légales & RGPD :</strong> réponse sous 72 heures (max. 30 jours pour les demandes complexes)</li>
        <li><strong>Partenariats :</strong> réponse sous 5 jours ouvrés</li>
    </ul>
    <div class="highlight-box">
        <i class="fas fa-info-circle"></i> Pour un traitement plus rapide, indiquez toujours votre <strong>adresse email de compte</strong> et la <strong>référence de votre boutique</strong> dans votre message.
    </div>
</div>

{{-- FAQ rapide --}}
<div class="faq-section">
    <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:1.5rem;color:#0f172a;">
        <i class="fas fa-question-circle" style="color:#2563eb;margin-right:8px;"></i>Questions fréquentes
    </h2>

    <div class="faq-item">
        <div class="faq-q">Je n'arrive pas à me connecter à mon compte marchand</div>
        <div class="faq-a">Utilisez la fonction "Mot de passe oublié" sur la page de connexion. Si le problème persiste, contactez <a href="mailto:support@nafalo.com">support@nafalo.com</a> en précisant votre adresse email.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">Mon paiement a été prélevé mais le produit n'a pas été livré</div>
        <div class="faq-a">Vérifiez votre dossier spam. Si l'email n'est pas là, contactez le marchand via sa boutique. Si pas de réponse sous 72h, ouvrez un litige auprès de Nafalo via <a href="mailto:support@nafalo.com">support@nafalo.com</a> avec votre référence de commande.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">Comment supprimer mon compte marchand ?</div>
        <div class="faq-a">Envoyez une demande à <a href="mailto:support@nafalo.com">support@nafalo.com</a> depuis votre adresse email de compte en indiquant "Suppression de compte" en objet. Le traitement prend jusqu'à 30 jours. Vos données de transaction seront conservées 10 ans pour obligations légales.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">Quand vais-je recevoir mes fonds après une vente ?</div>
        <div class="faq-a">Les délais de reversement dépendent de votre prestataire de paiement. En général, les fonds sont disponibles sous 24 à 72 heures pour Mobile Money, et sous 3 à 5 jours ouvrés pour les cartes bancaires.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">Comment signaler un contenu illicite ou une contrefaçon ?</div>
        <div class="faq-a">Envoyez un email à <a href="mailto:legal@nafalo.com">legal@nafalo.com</a> avec le lien du produit concerné, la nature de l'infraction et les preuves dont vous disposez. Nous traiterons votre signalement dans les 72 heures.</div>
    </div>
</div>

@endsection
