# Cahier des Charges — Digital Store
**Version** : 2.0  
**Date** : Mai 2026  
**Stack** : Laravel 12 · PHP 8.5 · MySQL · Bootstrap 5 · Blade  
**Statut** : En production

---

## 1. Présentation du projet

**Digital Store** est une plateforme SaaS multi-tenant permettant à des marchands africains de créer et gérer leur boutique de vente de produits numériques (ebooks, formations, templates, logiciels, audio, vidéo, etc.).

Chaque marchand dispose d'un espace admin privé et d'une boutique publique accessible via sous-domaine ou domaine personnalisé. Les paiements sont traités en **FCFA** via le prestataire **GeniusPay**.

---

## 2. Architecture technique

### Stack
| Couche | Technologie |
|--------|-------------|
| Backend | Laravel 12 / PHP 8.5 |
| Base de données | MySQL |
| Frontend Admin | Blade + Bootstrap 5 + CSS custom |
| Frontend Boutique | Blade + CSS custom (sans Bootstrap) |
| Paiement | GeniusPay (webhook) |
| Stockage fichiers | Laravel Storage (disk `public`) |
| Auth | Système custom — modèle `Utilisateur` avec champ `mot_de_passe` |

### Multi-tenancy
```
Utilisateur (marchand)
  └── Boutique (1 ou plusieurs)
        ├── Categorie[]
        ├── Produit[]
        │     ├── Achat[]
        │     ├── Telechargement[]
        │     ├── Avis[]
        │     └── Upsell[]
        ├── Client[]
        ├── Transaction[]
        ├── CodePromo[]
        ├── PixelMarketing[]
        ├── Copublication[]
        └── ConfigurationBoutique
```

### Résolution de boutique
- La boutique active est stockée en `session('boutique_id')`
- Le middleware `domaine` résout la boutique selon le sous-domaine ou domaine personnalisé
- Toutes les requêtes admin et boutique sont filtrées par `boutique_id`

---

## 3. Modèles de données

### Utilisateur
| Champ | Type | Description |
|-------|------|-------------|
| id | bigint | Clé primaire |
| nom | string | Nom complet |
| email | string unique | Email de connexion |
| mot_de_passe | string | Hash bcrypt (≠ champ `password` Laravel standard) |
| role | enum | `admin`, `marchand`, `superadmin` |
| avatar | string nullable | Chemin image |

### Boutique
| Champ | Type | Description |
|-------|------|-------------|
| utilisateur_id | FK | Propriétaire |
| nom | string | Nom de la boutique |
| description | text | Description publique |
| logo | string nullable | Chemin image stockage |
| email | string | Email de contact |
| telephone | string | Téléphone |
| reseaux_sociaux | json | Liens sociaux (Facebook, Instagram, WhatsApp...) |
| domaine_personnalise | string nullable | Domaine custom |
| est_active | boolean | Activation |

### Produit
| Champ | Type | Description |
|-------|------|-------------|
| boutique_id | FK | Boutique propriétaire |
| categorie_id | FK nullable | Catégorie |
| nom | string | Nom du produit |
| slug | string unique | URL friendly |
| description | longtext | Description HTML (éditeur Quill) |
| prix | decimal(10,2) | Prix en FCFA |
| type | enum | `payant` ou `gratuit` (lead magnet) |
| image | string nullable | Image couverture |
| fichier | string nullable | Fichier numérique (PDF, ZIP, MP3, MP4…) |
| est_publie | boolean | Visibilité publique |
| lead_champs_requis | json | Champs du formulaire lead magnet actifs |
| lead_limite_dl | integer nullable | Limite de téléchargements gratuits |
| lead_compteur | integer | Compteur de téléchargements |

### Client
Acheteur ou prospect capturé via lead magnet.
- Lié à une boutique
- Historique des achats et téléchargements

### Transaction
- Référence GeniusPay
- Montant, statut (`en_attente`, `reussie`, `echouee`, `remboursee`)
- Liée à un client et une boutique

### Achat
- Lié à Transaction + Produit + Client
- Génère un lien de téléchargement unique

### Upsell
- Produit suggéré après un achat ou téléchargement
- Configurable par produit source : titre, description, réduction, ordre

### CodePromo
- Code alphanumérique unique par boutique
- Type : `pourcentage` ou `montant_fixe`
- Dates de validité, limite d'utilisations, usage actuel

### Avis
- Note 1–5 + commentaire
- Lié à un produit + client vérifié
- Modération : `est_visible`

### PixelMarketing
- Types : `facebook`, `google_analytics`, `tiktok`, `snapchat`, `twitter`
- ID pixel + activation par boutique

### Copublication
- Permet à 2 marchands de partager un produit
- Statuts : `en_attente`, `acceptee`, `refusee`
- Commission configurable

### ConfigurationBoutique
- Extension one-to-one de Boutique
- Couleur primaire, thème, WhatsApp, configuration email, GeniusPay credentials

### KYC (Know Your Customer)
- Vérification d'identité du marchand
- Pièce d'identité, statut de validation

---

## 4. Module Admin

### 4.1 Authentification
- Login / Logout via formulaire custom
- Champ `mot_de_passe` (non standard Laravel)
- Middleware `auth` sur toutes les routes admin protégées
- Sélection de boutique active en session après connexion

### 4.2 Dashboard
- Chiffre d'affaires du mois en cours
- Nombre de ventes, clients, produits publiés
- Graphique des ventes (30 derniers jours)
- Dernières transactions

### 4.3 Gestion des produits

#### Création (wizard multi-étapes)
Formulaire en 5 sections **toutes obligatoires**, navigation bloquante :

| Étape | Champs | Validation |
|-------|--------|------------|
| 1. Informations | Nom + Catégorie | Nom non vide + catégorie sélectionnée |
| 2. Tarification | Type (Payant/Gratuit) + Prix si payant | Prix > 0 si payant |
| 3. Fichiers | Fichier numérique | Fichier sélectionné |
| 4. Description | Éditeur Quill | ≥ 20 caractères |
| 5. Visuel | Image couverture | Image sélectionnée |

**Comportements wizard :**
- Barre de progression 0–100% en temps réel (5 sections)
- Badge ✓ (vert) / ! (rouge) sur chaque étape dans la sidebar
- Bouton "Publier" désactivé tant que les 5 sections ne sont pas valides
- Bouton "Enregistrer" (brouillon) toujours accessible
- Navigation Suivant / Précédent dans chaque section
- Messages d'erreur inline si validation échoue

#### Mode Lead Magnet (type gratuit)
- Le produit est gratuit : prix forcé à 0
- Formulaire de capture email avant téléchargement
- Champs configurables : Téléphone, Ville, Profession, Pays (en plus de Nom + Email obligatoires)
- Limite de téléchargements optionnelle (crée de la rareté)
- Le client reçoit le fichier par email ou lien direct

#### Liste des produits
- Vue **responsive** :
  - Mobile (< 768px) : cartes individuelles
  - Desktop : tableau avec tri et filtres
- Filtres : catégorie, recherche par nom
- Menu 3 points (dropdown custom `position: fixed`) par produit :
  - Modifier
  - Voir la page boutique
  - Copier le lien
  - Upsells
  - Publier / Dépublier
  - Supprimer

### 4.4 Upsells
- Définir des produits suggérés après achat/téléchargement d'un produit source
- Configuration : titre accroche, réduction (%), ordre d'affichage, activation
- Affichés sur la page de remerciement checkout et lead magnet

### 4.5 Catégories
- CRUD complet
- Icônes prédéfinies par catégorie (Formation, Ebook, Template, Audio, Vidéo, Logiciel, Art, Business, Santé, Autre)

### 4.6 Clients
- Liste avec recherche
- Fiche client : informations, historique achats, téléchargements
- Export CSV

### 4.7 Transactions
- Liste complète avec filtres (statut, date, montant)
- Détail par transaction
- Export CSV

### 4.8 Codes promo
- Création : code, type (% ou FCFA fixe), valeur, dates, limite d'utilisations
- Suivi du nombre d'utilisations en temps réel

### 4.9 Avis clients
- Liste de tous les avis reçus
- Modération : rendre visible / masquer
- Suppression

### 4.10 Pixels Marketing
- Ajout de pixels de tracking par boutique
- Types supportés : Facebook Pixel, Google Analytics, TikTok Pixel, Snapchat, Twitter
- Activation / désactivation sans suppression

### 4.11 Co-publication
- Inviter un autre marchand à co-publier un produit
- Le marchand invité accepte ou refuse
- Commission partagée configurable
- Le produit apparaît dans les deux boutiques

### 4.12 Statistiques
- **Ventes** : CA par période, courbe de ventes, produits les plus vendus
- **Produits** : taux de conversion, vues vs achats

### 4.13 Exports CSV
- Produits, Clients, Transactions

### 4.14 Configurations boutique
| Section | Contenu |
|---------|---------|
| Général | Nom, description, logo, email, téléphone, réseaux sociaux |
| Paiement | Clés GeniusPay (public + secret), webhook |
| Email | Expéditeur, SMTP ou service tiers |
| Apparence | Couleur primaire, thème, bannière |
| WhatsApp | Numéro pour lien direct CTA |

### 4.15 Profil marchand
- Modifier nom, email
- Changer le mot de passe
- Changer l'avatar
- Préférences d'affichage

### 4.16 Notifications in-app
- Notifications pour : nouvelle vente, nouvel avis, invitation co-publication, alerte KYC
- Centre de notifications avec compteur non lu
- Marquer tout comme lu, supprimer

### 4.17 KYC (vérification marchand)
- Soumission de pièce d'identité
- Statuts : non soumis, en attente, validé, refusé
- Déblocage de fonctionnalités à la validation

---

## 5. Module Boutique (Frontend public)

### 5.1 Accueil boutique
- Héro avec nom + description + logo de la boutique
- Produits mis en avant
- Catégories

### 5.2 Liste produits
- Grille responsive
- Filtres par catégorie
- Carte produit : image, nom, prix, badge "Gratuit" si lead magnet

### 5.3 Page produit (show)
- Image couverture
- Nom, prix, étoiles (note moyenne)
- Social proof : nombre d'acheteurs
- Compteur d'urgence (24h)
- **Produit payant** : Bouton "Ajouter au panier" → Checkout
- **Produit gratuit** : Bouton "Obtenir gratuitement" → Modal formulaire lead
- Liste des avantages inclus
- Garantie satisfait ou remboursé
- Description complète (HTML Quill)
- Avis clients avec barres de distribution par note
- Produits similaires

### 5.4 Panier
- Ajout / suppression de produits
- Application de code promo
- Récapitulatif avec total
- Passage au checkout

### 5.5 Checkout (paiement GeniusPay)
- Formulaire informations client (nom, email, téléphone)
- Récapitulatif commande
- Redirection vers GeniusPay
- Pages : Succès / Annulation / Callback webhook

### 5.6 Lead Magnet
- Modal de capture avec champs configurés par le marchand
- Validation email
- Envoi du fichier après soumission
- Page de remerciement avec upsells

### 5.7 Avis
- Formulaire de dépôt d'avis (client authentifié ou via token achat)
- Note étoiles + commentaire

### 5.8 Espace client
- Accès via email + code
- Mes achats : liste des produits achetés, lien de re-téléchargement

---

## 6. Système de paiement

### GeniusPay
- Initiation du paiement via API GeniusPay
- Redirection client vers page de paiement sécurisée
- Retour webhook pour confirmer le paiement
- Génération automatique de l'accès téléchargement après confirmation
- Gestion des statuts : en_attente → reussie / echouee / remboursee

---

## 7. Sécurité

| Mesure | Détail |
|--------|--------|
| Authentification | Session Laravel + middleware `auth` |
| Isolation multi-tenant | Chaque requête filtrée par `boutique_id` session |
| Autorisation produit | `abort_if($produit->boutique_id !== session('boutique_id'), 403)` |
| CSRF | Tokens Laravel sur tous les formulaires POST/PUT/DELETE |
| Upload fichiers | Validation MIME + taille, stockage hors webroot |
| Webhook GeniusPay | Signature vérifiée, sans CSRF |
| Téléchargements | Liens signés ou token unique par achat |

---

## 8. Responsive Design

| Breakpoint | Comportement |
|------------|-------------|
| < 480px | Barre supérieure empilée verticalement, boutons pleine largeur |
| < 640px | Sidebar produit → bandeau tabs horizontal, formulaire mono-colonne |
| < 768px | Vue cartes au lieu du tableau dans la liste produits |
| ≥ 768px | Tableau desktop avec colonnes, sidebar latérale |

---

## 9. Dropdowns & interactions JS

### Menu 3 points (liste produits admin)
- Positionnement `position: fixed` calculé en JS (évite le clipping `overflow: hidden` des tableaux)
- Un seul menu ouvert à la fois
- Fermeture au clic extérieur (listener en phase capture)
- Encapsulé dans IIFE pour éviter la pollution du scope global

### Wizard de création produit
- Navigation bloquante : impossible de passer à la section suivante sans valider la section courante (sections requises)
- Validation live : erreurs disparaissent dès que le champ est rempli correctement
- Sections optionnelles (Page IA supprimée) : accès libre en cliquant la sidebar

---

## 10. Routes principales

### Admin (`/admin/...`)
| Route | Description |
|-------|-------------|
| `GET /admin/` | Dashboard |
| `GET/POST /admin/produits` | CRUD produits |
| `GET/POST /admin/categories` | CRUD catégories |
| `GET /admin/clients` | Liste clients |
| `GET /admin/transactions` | Liste transactions |
| `GET/POST /admin/codes-promo` | CRUD codes promo |
| `GET/POST /admin/avis` | Modération avis |
| `GET/POST /admin/pixels` | CRUD pixels marketing |
| `GET/POST /admin/copublications` | Gestion co-publications |
| `GET/POST /admin/configurations/*` | Configuration boutique |
| `GET /admin/statistiques/*` | Statistiques |
| `GET /admin/exports/*` | Exports CSV |
| `GET /admin/profil` | Profil marchand |
| `GET /admin/notifications` | Centre de notifications |
| `GET/POST /admin/kyc` | KYC marchand |

### Boutique (`/boutique/...`)
| Route | Description |
|-------|-------------|
| `GET /boutique/` | Accueil boutique |
| `GET /boutique/produits` | Liste produits |
| `GET /boutique/produits/{slug}` | Page produit |
| `GET /boutique/panier` | Panier |
| `POST /boutique/panier/ajouter/{produit}` | Ajouter au panier |
| `GET /boutique/checkout/informations` | Formulaire checkout |
| `POST /boutique/checkout/payer` | Initier paiement |
| `GET /boutique/checkout/succes` | Page succès |
| `POST /boutique/lead/{produit}/capturer` | Capture lead magnet |
| `GET/POST /boutique/avis/produit/{produit}` | Déposer un avis |

---

## 11. Structure des fichiers clés

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProduitController.php       ← CRUD + abort_if auth
│   │   │   ├── CategorieController.php
│   │   │   ├── ClientController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── CodePromoController.php
│   │   │   ├── AvisController.php
│   │   │   ├── PixelController.php
│   │   │   ├── CopublicationController.php
│   │   │   ├── UpsellController.php
│   │   │   ├── StatistiqueController.php
│   │   │   ├── ExportController.php
│   │   │   ├── ConfigurationController.php
│   │   │   ├── ProfilController.php
│   │   │   ├── NotificationController.php
│   │   │   └── KycController.php
│   │   └── Boutique/
│   │       ├── AccueilController.php
│   │       ├── ProduitController.php
│   │       ├── PanierController.php
│   │       ├── CheckoutController.php
│   │       ├── LeadController.php
│   │       └── AvisController.php
│   ├── Requests/
│   │   └── ProduitRequest.php
│   └── Middleware/
│       └── DomaineMiddleware.php
├── Models/
│   ├── Utilisateur.php
│   ├── Boutique.php
│   ├── Produit.php
│   ├── Categorie.php
│   ├── Client.php
│   ├── Transaction.php
│   ├── Achat.php
│   ├── Telechargement.php
│   ├── Avis.php
│   ├── CodePromo.php
│   ├── PixelMarketing.php
│   ├── Copublication.php
│   ├── Upsell.php
│   ├── ConfigurationBoutique.php
│   ├── NotificationMarchand.php
│   └── Kyc.php
resources/views/
├── layouts/
│   ├── admin.blade.php       ← Sidebar navigation
│   └── boutique.blade.php    ← Layout public boutique
├── admin/
│   ├── produits/
│   │   ├── index.blade.php   ← Dual layout mobile/desktop
│   │   ├── create.blade.php  ← Wizard 5 étapes
│   │   ├── edit.blade.php
│   │   └── _menu_actions.blade.php
│   └── ...
└── boutique/
    └── produits/
        ├── index.blade.php
        └── show.blade.php
routes/
├── web.php
└── admin.php
```

---

## 12. Conventions de développement

| Règle | Détail |
|-------|--------|
| Langue UI | Français (marché Afrique de l'Ouest) |
| Monnaie | FCFA, formaté avec `number_format($prix, 0, ',', ' ')` |
| Slugs | Générés automatiquement via `Str::slug($nom)` |
| Autorisations | `abort_if($produit->boutique_id !== session('boutique_id'), 403)` |
| JS | Encapsulé en IIFE, fonctions publiques exposées via `window.xxx` |
| CSS | Mobile-first, media queries `max-width` |
| Images | Stockées dans `storage/app/public/produits/images/` |
| Fichiers | Stockés dans `storage/app/public/produits/fichiers/` |
| Suppression | Supprime les fichiers Storage avant `$model->delete()` |

---

## 13. Fonctionnalités à venir (backlog)

- [ ] Abonnements / produits récurrents
- [ ] Système d'affiliation
- [ ] Multi-langue (EN/FR)
- [ ] Application mobile (PWA)
- [ ] Intégration paiement Mobile Money (Orange Money, MTN MoMo, Wave)
- [ ] Dashboard analytics avancé (entonnoir de conversion)
- [ ] Emails automatiques post-achat personnalisables
- [ ] Système de bundles (offres groupées de produits)
- [ ] Intégration Mailchimp / ConvertKit pour les leads
- [ ] Certificats automatiques pour les formations

---

*Document généré le 10 mai 2026 — reflète l'état réel du projet en production.*
