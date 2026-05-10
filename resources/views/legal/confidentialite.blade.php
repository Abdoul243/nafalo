@extends('legal.layout')

@section('badge', 'Confidentialité')
@section('title', 'Politique de Confidentialité')
@section('subtitle', "Nafalo s'engage à protéger vos données personnelles. Cette politique explique comment nous collectons, utilisons et protégeons vos informations.")

@section('content')

<div class="toc">
    <h3><i class="fas fa-list me-2"></i>Sommaire</h3>
    <ol>
        <li><a href="#p1">Responsable du traitement</a></li>
        <li><a href="#p2">Données collectées</a></li>
        <li><a href="#p3">Finalités du traitement</a></li>
        <li><a href="#p4">Base légale du traitement</a></li>
        <li><a href="#p5">Partage des données</a></li>
        <li><a href="#p6">Durée de conservation</a></li>
        <li><a href="#p7">Vos droits</a></li>
        <li><a href="#p8">Cookies et traceurs</a></li>
        <li><a href="#p9">Sécurité des données</a></li>
        <li><a href="#p10">Contact DPO</a></li>
    </ol>
</div>

<div class="legal-section" id="p1">
    <h2><span class="section-num">1</span> Responsable du traitement</h2>
    <p>Le responsable du traitement des données personnelles collectées sur la plateforme Nafalo est :</p>
    <ul>
        <li><strong>Dénomination :</strong> Nafalo</li>
        <li><strong>Siège social :</strong> Abidjan, Côte d'Ivoire</li>
        <li><strong>Email :</strong> privacy@nafalo.com</li>
    </ul>
</div>

<div class="legal-section" id="p2">
    <h2><span class="section-num">2</span> Données collectées</h2>
    <h3>2.1 Données des marchands</h3>
    <p>Lors de la création de votre compte marchand, nous collectons :</p>
    <ul>
        <li>Nom et prénom</li>
        <li>Adresse email</li>
        <li>Informations de votre boutique (nom, description, logo)</li>
        <li>Coordonnées bancaires ou de paiement mobile (chiffrées)</li>
        <li>Adresse IP et données de connexion</li>
    </ul>
    <h3>2.2 Données des clients finaux</h3>
    <p>Lors des achats effectués dans les boutiques Nafalo, nous collectons :</p>
    <ul>
        <li>Adresse email (pour la livraison du produit)</li>
        <li>Informations de paiement (traitées par notre prestataire sécurisé GeniusPay)</li>
        <li>Historique des achats</li>
    </ul>
    <h3>2.3 Données de navigation</h3>
    <p>Nous collectons automatiquement certaines données de navigation : adresse IP, type de navigateur, pages visitées, durée de visite. Ces données sont utilisées à des fins statistiques uniquement.</p>
</div>

<div class="legal-section" id="p3">
    <h2><span class="section-num">3</span> Finalités du traitement</h2>
    <p>Vos données sont traitées aux fins suivantes :</p>
    <ul>
        <li><strong>Gestion du compte :</strong> création, authentification et administration de votre espace marchand</li>
        <li><strong>Traitement des transactions :</strong> facturation, reversement des paiements, gestion des commissions</li>
        <li><strong>Communication :</strong> envoi des confirmations d'achat, notifications de vente, support client</li>
        <li><strong>Amélioration du service :</strong> analyse des usages pour améliorer la plateforme</li>
        <li><strong>Obligations légales :</strong> conservation des données comptables et fiscales</li>
        <li><strong>Marketing (optionnel) :</strong> envoi de newsletters sur les nouveautés Nafalo (avec votre consentement)</li>
    </ul>
</div>

<div class="legal-section" id="p4">
    <h2><span class="section-num">4</span> Base légale du traitement</h2>
    <p>Selon le type de traitement, la base légale est :</p>
    <ul>
        <li><strong>Exécution du contrat :</strong> traitement des commandes, gestion du compte</li>
        <li><strong>Consentement :</strong> newsletters, cookies non essentiels</li>
        <li><strong>Obligation légale :</strong> conservation des données comptables (10 ans)</li>
        <li><strong>Intérêt légitime :</strong> sécurité de la plateforme, prévention de la fraude</li>
    </ul>
</div>

<div class="legal-section" id="p5">
    <h2><span class="section-num">5</span> Partage des données</h2>
    <p>Nafalo ne vend jamais vos données personnelles à des tiers. Vos données peuvent être partagées uniquement avec :</p>
    <ul>
        <li><strong>GeniusPay :</strong> notre prestataire de paiement, pour le traitement sécurisé des transactions</li>
        <li><strong>Prestataires d'email :</strong> Brevo (anciennement Sendinblue), pour l'envoi des emails transactionnels</li>
        <li><strong>Autorités compétentes :</strong> uniquement sur réquisition judiciaire ou administrative</li>
    </ul>
    <div class="highlight-box">
        <i class="fas fa-lock"></i> <strong>Transfert hors UE :</strong> certains de nos prestataires peuvent traiter vos données hors de l'Union Européenne. Dans ce cas, nous nous assurons que des garanties appropriées sont en place (clauses contractuelles types, Privacy Shield, etc.).
    </div>
</div>

<div class="legal-section" id="p6">
    <h2><span class="section-num">6</span> Durée de conservation</h2>
    <ul>
        <li><strong>Données de compte :</strong> durée de vie du compte + 3 ans après la clôture</li>
        <li><strong>Données de transaction :</strong> 10 ans (obligation comptable et fiscale)</li>
        <li><strong>Données de navigation :</strong> 13 mois maximum</li>
        <li><strong>Emails de marketing :</strong> jusqu'au retrait du consentement</li>
    </ul>
</div>

<div class="legal-section" id="p7">
    <h2><span class="section-num">7</span> Vos droits</h2>
    <p>Conformément à la réglementation applicable, vous disposez des droits suivants :</p>
    <ul>
        <li><strong>Droit d'accès :</strong> obtenir une copie de vos données personnelles</li>
        <li><strong>Droit de rectification :</strong> corriger des données inexactes ou incomplètes</li>
        <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données ("droit à l'oubli")</li>
        <li><strong>Droit à la portabilité :</strong> recevoir vos données dans un format structuré</li>
        <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos données à des fins marketing</li>
        <li><strong>Droit de limitation :</strong> demander la limitation du traitement dans certains cas</li>
    </ul>
    <p>Pour exercer ces droits, contactez-nous à <a href="mailto:privacy@nafalo.com">privacy@nafalo.com</a> en joignant une preuve d'identité. Nous répondrons dans un délai de <strong>30 jours</strong>.</p>
</div>

<div class="legal-section" id="p8">
    <h2><span class="section-num">8</span> Cookies et traceurs</h2>
    <h3>Cookies essentiels (obligatoires)</h3>
    <p>Ces cookies sont nécessaires au fonctionnement de la plateforme et ne peuvent pas être désactivés :</p>
    <ul>
        <li><strong>Session :</strong> maintien de votre connexion</li>
        <li><strong>CSRF :</strong> protection contre les attaques de sécurité</li>
    </ul>
    <h3>Cookies analytiques (optionnels)</h3>
    <p>Avec votre consentement, nous utilisons des cookies pour analyser l'audience de la plateforme et améliorer notre service. Vous pouvez les désactiver à tout moment.</p>
    <h3>Pixels marketing (marchands)</h3>
    <p>Les marchands peuvent configurer des pixels de tracking (Facebook Pixel, Google Analytics) sur leurs boutiques. Ces pixels sont soumis aux politiques de confidentialité de leurs éditeurs respectifs.</p>
</div>

<div class="legal-section" id="p9">
    <h2><span class="section-num">9</span> Sécurité des données</h2>
    <p>Nafalo met en œuvre des mesures techniques et organisationnelles pour protéger vos données :</p>
    <ul>
        <li>Chiffrement des données sensibles (mots de passe, informations bancaires)</li>
        <li>Connexions sécurisées (HTTPS/TLS)</li>
        <li>Accès aux données restreint aux personnes habilitées</li>
        <li>Sauvegardes régulières des données</li>
        <li>Surveillance et alertes en cas d'activité suspecte</li>
    </ul>
    <div class="warn-box">
        <i class="fas fa-exclamation-triangle"></i> En cas de violation de données susceptible d'affecter vos droits et libertés, nous nous engageons à vous en informer dans les <strong>72 heures</strong> suivant la détection de l'incident.
    </div>
</div>

<div class="legal-section" id="p10">
    <h2><span class="section-num">10</span> Contact et réclamations</h2>
    <p>Pour toute question relative à la protection de vos données personnelles :</p>
    <ul>
        <li><strong>Email :</strong> <a href="mailto:privacy@nafalo.com">privacy@nafalo.com</a></li>
        <li><strong>Objet :</strong> "Protection des données — [votre demande]"</li>
    </ul>
    <p>Si vous estimez que le traitement de vos données n'est pas conforme à la réglementation, vous pouvez déposer une réclamation auprès de l'autorité de contrôle compétente dans votre pays.</p>
</div>

@endsection
