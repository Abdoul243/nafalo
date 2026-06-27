<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Utilisateur;
use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\ConfigurationBoutique;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Achat;

class DemoBoutiquesSeeder extends Seeder
{
    public function run(): void
    {
        // ────────────────────────────────────────────────────────────
        //  DONNÉES DE DÉMO — 8 boutiques, niches africaines
        // ────────────────────────────────────────────────────────────
        $boutiquesData = $this->donneesBoutiques();

        foreach ($boutiquesData as $data) {
            $this->creerBoutique($data);
        }

        $this->command->info('✅ ' . count($boutiquesData) . ' boutiques de démo créées avec succès !');
    }

    // ════════════════════════════════════════════════════════════════
    //  CRÉATION D'UNE BOUTIQUE COMPLÈTE
    // ════════════════════════════════════════════════════════════════
    private function creerBoutique(array $data): void
    {
        // 1. Utilisateur (marchand)
        $utilisateur = Utilisateur::firstOrCreate(
            ['email' => $data['email']],
            [
                'nom'         => $data['marchand'],
                'mot_de_passe' => Hash::make('password123'),
                'role'        => 'admin',
            ]
        );

        // 2. Boutique
        $boutique = Boutique::firstOrCreate(
            ['domaine_personnalise' => $data['domaine']],
            [
                'utilisateur_id'      => $utilisateur->id,
                'nom'                 => $data['nom'],
                'description'         => $data['description'],
                'logo'                => $data['logo'],
                'logo_mime'           => 'image/jpeg',
                'email'               => $data['email'],
                'telephone'           => $data['telephone'],
                'domaine_personnalise' => $data['domaine'],
                'est_active'          => true,
            ]
        );

        // 3. Configuration boutique
        ConfigurationBoutique::firstOrCreate(
            ['boutique_id' => $boutique->id],
            [
                'devise'            => 'XOF',
                'langue'            => 'fr',
                'theme'             => 'light',
                'couleur'           => $data['couleur'],
                'email_expediteur'  => $data['email'],
                'passerelle_paiement' => 'moneroo',
            ]
        );

        // 4. Catégories + produits
        foreach ($data['categories'] as $catData) {
            $categorie = Categorie::firstOrCreate(
                [
                    'boutique_id' => $boutique->id,
                    'slug'        => Str::slug($catData['nom']),
                ],
                [
                    'nom'         => $catData['nom'],
                    'description' => $catData['description'] ?? null,
                ]
            );

            foreach ($catData['produits'] as $prodData) {
                $slug = Str::slug($prodData['nom']);

                // Éviter les doublons de slug par boutique
                $exists = Produit::where('boutique_id', $boutique->id)
                                 ->where('slug', $slug)
                                 ->exists();
                if ($exists) continue;

                Produit::create([
                    'boutique_id'  => $boutique->id,
                    'categorie_id' => $categorie->id,
                    'nom'          => $prodData['nom'],
                    'slug'         => $slug,
                    'description'  => $prodData['description'],
                    'prix'         => $prodData['prix'],
                    'image'        => $prodData['image'],
                    'fichier'      => 'demo/' . $slug . '.pdf',
                    'est_publie'   => true,
                    'nb_ventes'    => $prodData['nb_ventes'] ?? rand(5, 80),
                ]);
            }
        }

        // 5. Transactions fictives pour le classement par popularité
        $this->creerTransactionsFictives($boutique, $utilisateur, $data['nb_ventes_total']);

        $this->command->line("  → {$boutique->nom} ({$utilisateur->email}) ✓");
    }

    // ════════════════════════════════════════════════════════════════
    //  TRANSACTIONS FICTIVES (pour classement popularité)
    // ════════════════════════════════════════════════════════════════
    private function creerTransactionsFictives(Boutique $boutique, Utilisateur $utilisateur, int $nbVentes): void
    {
        // Client générique pour les démos
        // Un client par boutique pour les démos
        $clientEmail = 'demo.' . $boutique->domaine_personnalise . '@nafalo.test';
        $client = Client::firstOrCreate(
            ['email' => $clientEmail, 'boutique_id' => $boutique->id],
            [
                'boutique_id' => $boutique->id,
                'nom'         => 'Client Démo',
                'email'       => $clientEmail,
            ]
        );

        $produits = $boutique->produits()->where('est_publie', true)->get();
        if ($produits->isEmpty()) return;

        for ($i = 0; $i < $nbVentes; $i++) {
            $produit = $produits->random();

            $transaction = Transaction::create([
                'boutique_id'    => $boutique->id,
                'client_id'      => $client->id,
                'reference'      => 'DEMO-' . strtoupper(Str::random(10)),
                'statut'         => Transaction::STATUT_REUSSI,
                'montant_total'  => $produit->prix,
                'commission'     => round($produit->prix * 0.05, 2),
                'montant_marchand' => round($produit->prix * 0.95, 2),
                'moyen_paiement' => collect(['wave_ci', 'orange_ci', 'mtn_ci'])->random(),
                'created_at'     => now()->subDays(rand(1, 180)),
                'updated_at'     => now()->subDays(rand(0, 10)),
            ]);

            Achat::create([
                'transaction_id' => $transaction->id,
                'client_id'      => $client->id,
                'produit_id'     => $produit->id,
                'boutique_id'    => $boutique->id,
                'quantite'       => 1,
                'prix_unitaire'  => $produit->prix,
                'montant'        => $produit->prix,
            ]);
        }
    }

    // ════════════════════════════════════════════════════════════════
    //  DONNÉES DES 8 BOUTIQUES
    // ════════════════════════════════════════════════════════════════
    private function donneesBoutiques(): array
    {
        return [

            // ── 1. AGRICULTURE ──────────────────────────────────────
            [
                'marchand'    => 'Konan Adjoua Marie',
                'email'       => 'konan.adjoua@agribusiness-ci.com',
                'telephone'   => '+225 07 45 23 18',
                'nom'         => 'AgriBusiness CI',
                'domaine'     => 'agribusiness-ci',
                'couleur'     => '#16a34a',
                'logo'        => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=200&h=200&fit=crop',
                'description' => 'Votre référence en formations et guides numériques pour l\'agriculture tropicale en Côte d\'Ivoire et en Afrique de l\'Ouest. Des ressources pratiques pour moderniser votre exploitation agricole et maximiser vos rendements.',
                'nb_ventes_total' => 342,
                'categories' => [
                    [
                        'nom'         => 'Guides de culture',
                        'description' => 'Guides pratiques pour toutes les cultures tropicales',
                        'produits'    => [
                            [
                                'nom'         => 'Guide complet du maraîchage tropical',
                                'description' => 'Un guide exhaustif de 180 pages couvrant toutes les techniques de maraîchage adapté au climat tropical africain. Apprenez à cultiver tomates, aubergines, piments, courgettes et légumes-feuilles avec des méthodes éprouvées. Inclut : calendrier cultural, gestion de l\'eau, lutte biologique contre les ravageurs, conservation post-récolte et circuits de commercialisation. Idéal pour les petits et moyens exploitants.',
                                'prix'        => 4500,
                                'nb_ventes'   => 127,
                                'image'       => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Manuel de culture du cacao en Côte d\'Ivoire',
                                'description' => 'Le guide de référence pour la cacaoculture en Côte d\'Ivoire, premier pays producteur mondial. 220 pages détaillant le choix variétal, la création de pépinière, la plantation en zone forestière, la taille de formation, la fertilisation raisonnée, la lutte contre la pourriture brune et le swollen shoot. Avec les prix de vente actuels et les contacts d\'acheteurs certifiés.',
                                'prix'        => 7500,
                                'nb_ventes'   => 89,
                                'image'       => 'https://images.unsplash.com/photo-1611880216208-9d0b9de1b1e7?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Formation irrigation goutte-à-goutte low-cost',
                                'description' => 'Maîtrisez l\'irrigation goutte-à-goutte avec des systèmes conçus pour les petits agriculteurs africains, à partir de 50 000 FCFA. Ce guide pratique de 90 pages explique comment installer un système d\'irrigation économique, réduire votre consommation d\'eau de 60 %, augmenter vos rendements de 40 % et cultiver en saison sèche. Plans et schémas inclus.',
                                'prix'        => 6000,
                                'nb_ventes'   => 54,
                                'image'       => 'https://images.unsplash.com/photo-1495107334309-fcf20504a5ab?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Business agricole',
                        'description' => 'Transformez votre activité agricole en entreprise rentable',
                        'produits'    => [
                            [
                                'nom'         => 'Business plan type exploitation agricole 5 ha',
                                'description' => 'Template professionnel de business plan pour une exploitation agricole de 5 hectares en Afrique de l\'Ouest, adapté aux exigences des banques et des investisseurs. Inclut : étude de marché locale, prévisions financières sur 5 ans, plan de financement, analyse de rentabilité par spéculation, et modèle de présentation aux bailleurs de fonds. Format Excel + Word modifiable.',
                                'prix'        => 12000,
                                'nb_ventes'   => 72,
                                'image'       => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 2. ÉLEVAGE ──────────────────────────────────────────
            [
                'marchand'    => 'Ouédraogo Mamadou',
                'email'       => 'ouedraogo.mamadou@elevagepro-afrique.com',
                'telephone'   => '+226 70 45 89 12',
                'nom'         => 'ÉlevagePro Afrique',
                'domaine'     => 'elevagepro-afrique',
                'couleur'     => '#b45309',
                'logo'        => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=200&h=200&fit=crop',
                'description' => 'Formations et guides spécialisés en élevage bovin, ovin, caprin et aviculture pour les éleveurs professionnels d\'Afrique de l\'Ouest. Des contenus créés par des vétérinaires et zootechniciens expérimentés.',
                'nb_ventes_total' => 198,
                'categories' => [
                    [
                        'nom'         => 'Élevage bovin et ovin',
                        'description' => 'Guides techniques pour l\'élevage de bovins, ovins et caprins',
                        'produits'    => [
                            [
                                'nom'         => 'Guide complet élevage bovin en zone sahélienne',
                                'description' => 'Le guide indispensable pour les éleveurs de bovins au Sahel. 200 pages rédigées avec des vétérinaires praticiens couvrant : alimentation et complémentation en saison sèche, prévention et traitement des grandes maladies (PPCB, FVR, Charbon), reproduction et amélioration génétique, gestion du troupeau par transhumance, et commercialisation au marché à bétail. Avec fiches de suivi téléchargeables.',
                                'prix'        => 8500,
                                'nb_ventes'   => 95,
                                'image'       => 'https://images.unsplash.com/photo-1528697203912-19c96d74f6db?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Manuel gestion sanitaire du troupeau',
                                'description' => 'Protocole complet de gestion sanitaire pour éleveurs sans vétérinaire permanent. Apprenez à reconnaître et traiter vous-même les 20 maladies les plus fréquentes, mettre en place un calendrier vaccinal adapté à votre zone, créer une pharmacie vétérinaire d\'urgence, et tenir un registre sanitaire conforme aux normes. Fiches pratiques plastifiables incluses.',
                                'prix'        => 5500,
                                'nb_ventes'   => 68,
                                'image'       => 'https://images.unsplash.com/photo-1540742534-d7f12153028d?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Aviculture moderne',
                        'description' => 'Créez et gérez une unité avicole rentable',
                        'produits'    => [
                            [
                                'nom'         => 'Formation aviculture intensive — Poulets de chair',
                                'description' => 'De zéro à 1 000 poulets de chair en 45 jours : le guide complet pour démarrer et gérer une unité avicole intensive rentable en Afrique. Couverture de : construction du poulailler low-cost, choix du poussin, programme alimentaire et médicamenteux, gestion thermique, calcul du coût de revient, et stratégie de vente aux restaurants et grossistes. Inclut tableaux de bord Excel.',
                                'prix'        => 9000,
                                'nb_ventes'   => 112,
                                'image'       => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Guide élevage de pondeuses — 500 poules',
                                'description' => 'Lancez une unité de 500 poules pondeuses avec ce guide pratique complet. Sélection des races à fort potentiel de ponte (280+ œufs/an), aménagement optimal du poulailler, programme lumineux, gestion de la ponte, tri et conditionnement des œufs, et circuits de distribution aux supermarchés et hôtels. Retour sur investissement en 8 mois démontré par des exemples réels.',
                                'prix'        => 7000,
                                'nb_ventes'   => 78,
                                'image'       => 'https://images.unsplash.com/photo-1518569656558-1f25e69d2fd4?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 3. TECHNOLOGIE & FORMATION ──────────────────────────
            [
                'marchand'    => 'N\'Guessan Kouassi Jean',
                'email'       => 'nguessankj@techformationci.com',
                'telephone'   => '+225 05 66 78 34',
                'nom'         => 'TechFormation CI',
                'domaine'     => 'techformation-ci',
                'couleur'     => '#2563eb',
                'logo'        => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=200&h=200&fit=crop',
                'description' => 'Formations numériques en développement web, bureautique avancée, design graphique et création d\'entreprise digitale. Plus de 2 000 étudiants formés en Côte d\'Ivoire depuis 2021. Apprenez à votre rythme avec des cours en français adaptés au contexte africain.',
                'nb_ventes_total' => 521,
                'categories' => [
                    [
                        'nom'         => 'Développement & Programmation',
                        'description' => 'Apprenez à coder et créer des applications',
                        'produits'    => [
                            [
                                'nom'         => 'Formation complète HTML/CSS/JavaScript — Niveau débutant',
                                'description' => 'La formation de référence pour apprendre le développement web en partant de zéro. 40 heures de contenu structuré en 120 leçons vidéo, 15 projets pratiques et exercices corrigés. Vous apprendrez à créer des sites web professionnels responsives, maîtriser les animations CSS, manipuler le DOM avec JavaScript et publier votre site sur un hébergement gratuit. Certificat de complétion inclus.',
                                'prix'        => 25000,
                                'nb_ventes'   => 203,
                                'image'       => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Guide création d\'application mobile sans coder (No-Code)',
                                'description' => 'Créez votre application mobile iOS et Android sans une seule ligne de code grâce aux outils no-code Glide, Adalo et Bubble. Ce guide de 150 pages et 8 heures de tutoriels vidéo vous montre comment construire une app de livraison, une marketplace locale ou une app de gestion boutique — et la monétiser. Témoignages d\'entrepreneurs africains ayant généré des revenus dès le 1er mois.',
                                'prix'        => 18000,
                                'nb_ventes'   => 167,
                                'image'       => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Excel & Bureautique',
                        'description' => 'Maîtrisez Excel et les outils de productivité',
                        'produits'    => [
                            [
                                'nom'         => 'Masterclass Excel 2024 — De débutant à expert',
                                'description' => 'La formation Excel la plus complète en français pour l\'Afrique. Partez de zéro et maîtrisez les formules avancées (XLOOKUP, INDEX-MATCH), les tableaux croisés dynamiques, Power Query, les graphiques professionnels et les macros VBA. 35 heures de contenu avec fichiers d\'exercices. Utilisé par les comptables, RH, commerciaux et étudiants dans 15 pays africains.',
                                'prix'        => 15000,
                                'nb_ventes'   => 312,
                                'image'       => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Templates Excel gestion PME africaine',
                                'description' => 'Pack de 25 templates Excel prêts à l\'emploi conçus pour les PME et commerçants africains. Inclut : gestion de stock avec alertes, facturation clients, suivi de trésorerie quotidien, paie des employés, tableau de bord commercial, budget prévisionnel annuel et rapport mensuel automatisé. Compatible Excel 2016, 2019, 2021 et Microsoft 365. Documentation complète fournie.',
                                'prix'        => 8500,
                                'nb_ventes'   => 189,
                                'image'       => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 4. BEAUTÉ NATURELLE ─────────────────────────────────
            [
                'marchand'    => 'Coulibaly Aminata',
                'email'       => 'aminata.coulibaly@beautysecrets-afrique.com',
                'telephone'   => '+225 07 89 45 67',
                'nom'         => 'Beauty Secrets Afrique',
                'domaine'     => 'beautysecrets-afrique',
                'couleur'     => '#db2777',
                'logo'        => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=200&h=200&fit=crop',
                'description' => 'Découvrez les secrets de beauté naturelle transmis de génération en génération en Afrique. Nos guides et formations sur les soins capillaires, la cosmétique naturelle et le bien-être africain vous permettront de prendre soin de vous avec des produits locaux, sains et économiques.',
                'nb_ventes_total' => 287,
                'categories' => [
                    [
                        'nom'         => 'Soins capillaires naturels',
                        'description' => 'Prenez soin de vos cheveux avec des produits 100% naturels',
                        'produits'    => [
                            [
                                'nom'         => 'Guide soins cheveux afro naturels — La bible complète',
                                'description' => 'Le guide ultime pour prendre soin des cheveux afro naturels avec des ingrédients africains. 280 pages abordant les types de cheveux 4A, 4B, 4C, les routines de soin hebdomadaires et mensuelles, les masques hydratants au beurre de karité, à l\'huile d\'avocat et à l\'aloe vera, les techniques de coiffure sans manipulation excessive et les remèdes naturels contre la casse et la sécheresse. Adapté au climat tropical.',
                                'prix'        => 6000,
                                'nb_ventes'   => 245,
                                'image'       => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Programme pousse de cheveux en 90 jours',
                                'description' => 'Programme intensif de 90 jours pour retrouver des cheveux longs, forts et brillants. Découvrez le protocole de massages du cuir chevelu, les huiles essentielles stimulatrices de croissance disponibles en Afrique, le régime alimentaire pro-pousse et les compléments naturels locaux. Suivi semaine par semaine avec journal de progression. Résultats visibles garantis dès le 30e jour.',
                                'prix'        => 9500,
                                'nb_ventes'   => 178,
                                'image'       => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Cosmétiques maison',
                        'description' => 'Fabriquez vos propres cosmétiques naturels',
                        'produits'    => [
                            [
                                'nom'         => 'Recettes cosmétiques naturelles africaines — 50 formules',
                                'description' => 'Fabriquez 50 cosmétiques naturels avec des ingrédients africains disponibles en marché local : beurre de karité du Burkina, huile de palme rouge, huile de coco, argile blanche, eau de rose et plantes médicinales. Crèmes hydratantes, savons artisanaux, huiles de corps, masques visage, déodorants naturels, dentifrices et baumes à lèvres. Chaque recette inclut coût de fabrication et prix de vente suggéré.',
                                'prix'        => 7500,
                                'nb_ventes'   => 156,
                                'image'       => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 5. FINANCE PERSONNELLE ──────────────────────────────
            [
                'marchand'    => 'Koné Ibrahim Sékou',
                'email'       => 'kone.ibrahim@financeafrique-pro.com',
                'telephone'   => '+225 05 34 78 92',
                'nom'         => 'Finance Afrique Pro',
                'domaine'     => 'financeafrique-pro',
                'couleur'     => '#0f172a',
                'logo'        => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=200&h=200&fit=crop',
                'description' => 'Maîtrisez vos finances personnelles et développez votre patrimoine en Afrique. Des guides pratiques sur l\'épargne, l\'investissement immobilier, la bourse africaine et la création d\'entreprise adaptés aux réalités économiques ivoiriennes et ouest-africaines.',
                'nb_ventes_total' => 445,
                'categories' => [
                    [
                        'nom'         => 'Épargne et investissement',
                        'description' => 'Faites fructifier votre argent intelligemment',
                        'produits'    => [
                            [
                                'nom'         => 'Guide investissement BRVM — Bourse d\'Abidjan pour débutants',
                                'description' => 'Votre premier guide complet pour investir à la Bourse Régionale des Valeurs Mobilières (BRVM) d\'Abidjan. Apprenez à ouvrir un compte titre, analyser les actions des entreprises cotées (SONATEL, ONATEL, SAPH, SICABLE...), lire les bilans annuels, construire un portefeuille diversifié avec 50 000 FCFA et gérer le risque. Stratégies éprouvées par des investisseurs ayant multiplié leur capital par 3 en 4 ans.',
                                'prix'        => 15000,
                                'nb_ventes'   => 267,
                                'image'       => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Méthode épargne automatique — Objectif 1 million FCFA en 12 mois',
                                'description' => 'La méthode simple et éprouvée pour économiser 1 000 000 FCFA en 12 mois, même avec un salaire de 150 000 FCFA. Découvrez les 6 enveloppes budgétaires adaptées à la vie en Afrique de l\'Ouest, les applications d\'épargne disponibles (Wave, FloozAfrique, CinetPay), les tontines modernes et les comptes rémunérés. Inclut tableur de suivi mensuel personnalisable.',
                                'prix'        => 5000,
                                'nb_ventes'   => 389,
                                'image'       => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Création d\'entreprise',
                        'description' => 'Lancez et développez votre business',
                        'produits'    => [
                            [
                                'nom'         => 'Kit complet création SARL en Côte d\'Ivoire',
                                'description' => 'Tout ce qu\'il faut pour créer votre SARL en Côte d\'Ivoire en moins de 7 jours. Ce kit complet de 120 pages détaille les démarches au CEPICI, les frais exacts, les statuts types modifiables, le pacte d\'associés, les premières déclarations fiscales à la DGI et les obligations légales de la 1ère année. Bonus : liste des banques proposant des crédits aux nouvelles entreprises avec leurs conditions 2024.',
                                'prix'        => 12000,
                                'nb_ventes'   => 198,
                                'image'       => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 6. CUISINE AFRICAINE ────────────────────────────────
            [
                'marchand'    => 'Diallo Fatoumata Bintou',
                'email'       => 'fatoumata.diallo@cuisineafricaine-net.com',
                'telephone'   => '+224 620 45 78 34',
                'nom'         => 'Cuisine Africaine.net',
                'domaine'     => 'cuisineafricaine-net',
                'couleur'     => '#dc2626',
                'logo'        => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=200&h=200&fit=crop',
                'description' => 'La plateforme de référence pour les passionnés de cuisine africaine. Recettes traditionnelles et modernes de Côte d\'Ivoire, Sénégal, Mali, Cameroun et de toute l\'Afrique. Des livres de cuisine numériques créés avec amour par des chefs et cuisinières africaines passionnées.',
                'nb_ventes_total' => 378,
                'categories' => [
                    [
                        'nom'         => 'Recettes traditionnelles',
                        'description' => 'L\'authenticité des saveurs africaines',
                        'produits'    => [
                            [
                                'nom'         => '150 recettes de la cuisine ivoirienne traditionnelle',
                                'description' => 'Le livre de cuisine numérique le plus complet sur la gastronomie ivoirienne. 150 recettes authentiques avec photos en couleur : Attiéké poisson, Foutou banane sauce graine, Kedjenou de poulet, Alloco, Garba, soupe Kpété, gâteaux traditionnels Akpessi et bien plus. Chaque recette inclut la liste des ingrédients disponibles en marché local, les quantités pour 4 à 6 personnes, les astuces de chef et le temps de préparation.',
                                'prix'        => 6000,
                                'nb_ventes'   => 287,
                                'image'       => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Sauces et pâtes d\'Afrique de l\'Ouest — 80 recettes',
                                'description' => 'Le guide définitif des sauces africaines : sauce graine, sauce arachide, sauce tomate fraîche, sauce gombo, sauce claire, moyo, sauce feuilles de manioc... 80 recettes de sauces et condiments de Côte d\'Ivoire, Sénégal, Ghana, Cameroun et Nigeria. Avec les variantes régionales, les ingrédients de substitution et les techniques de conservation pour 3 jours sans réfrigérateur.',
                                'prix'        => 4500,
                                'nb_ventes'   => 198,
                                'image'       => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Pâtisserie et boissons',
                        'description' => 'Pâtisseries et boissons africaines modernes',
                        'produits'    => [
                            [
                                'nom'         => 'Guide pâtisserie africaine moderne — 60 créations',
                                'description' => 'Sublimez la pâtisserie africaine avec 60 créations fusion alliant techniques françaises et saveurs africaines. Gâteau au gingembre et citron vert, tarte mangue-coco, brioche au lait de baobab, macarons au chocolat de São Tomé, cheesecake au bissap... Recettes détaillées pas à pas avec photos, accompagnées d\'une section business pour vendre vos créations en ligne ou en commande.',
                                'prix'        => 8000,
                                'nb_ventes'   => 145,
                                'image'       => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 7. MODE & ARTISANAT ─────────────────────────────────
            [
                'marchand'    => 'Touré Mariama Sétou',
                'email'       => 'mariama.toure@modeafrik-creation.com',
                'telephone'   => '+223 76 34 52 89',
                'nom'         => 'Mode Afrik Création',
                'domaine'     => 'modeafrik-creation',
                'couleur'     => '#7c3aed',
                'logo'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=200&h=200&fit=crop',
                'description' => 'Célébrez la mode et l\'artisanat africain avec nos formations et guides de couture, création de bijoux et design africain contemporain. Apprenez à créer des collections qui valorisent le patrimoine textile africain tout en répondant aux tendances mondiales.',
                'nb_ventes_total' => 156,
                'categories' => [
                    [
                        'nom'         => 'Couture et stylisme',
                        'description' => 'Apprenez la couture africaine contemporaine',
                        'produits'    => [
                            [
                                'nom'         => 'Pack patrons couture — 20 modèles boubous et robes wax',
                                'description' => 'Collection de 20 patrons de couture haute définition pour boubous hommes et femmes, robes wax modernes, bazins et ensembles ankara. Chaque patron est disponible en tailles XS à 5XL, fourni en PDF imprimable sur A4 avec guide d\'assemblage illustré. Inclut : tableau de mesures africaines, guide des tissus locaux et astuces pour adapter les modèles à sa morphologie. Conçus par des stylistes de Bamako et Abidjan.',
                                'prix'        => 11000,
                                'nb_ventes'   => 134,
                                'image'       => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Formation couture atelier professionnel — Démarrer son business',
                                'description' => 'De couturière amateur à patronne d\'atelier professionnel : le guide complet pour créer son atelier de couture rentable en Afrique. 200 pages couvrant : équipement minimal pour démarrer (machines, outils), acquisition de clients par WhatsApp et Instagram, tarification de la confection, gestion des délais et des fournitures, et passage de l\'artisanat au prêt-à-porter. Avec témoignages de 10 couturières ayant réussi.',
                                'prix'        => 14000,
                                'nb_ventes'   => 89,
                                'image'       => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

            // ── 8. SANTÉ NATURELLE ──────────────────────────────────
            [
                'marchand'    => 'Yao Akosua Cécile',
                'email'       => 'yao.akosua@santenaturelle-afrique.com',
                'telephone'   => '+225 05 78 34 12',
                'nom'         => 'Santé Naturelle Afrique',
                'domaine'     => 'santenaturelle-afrique',
                'couleur'     => '#059669',
                'logo'        => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=200&h=200&fit=crop',
                'description' => 'Retrouvez la santé et le bien-être grâce aux remèdes traditionnels africains validés par la science moderne. Nos guides sur la phytothérapie africaine, la nutrition équilibrée et le sport adapté au quotidien africain sont conçus avec des professionnels de santé.',
                'nb_ventes_total' => 234,
                'categories' => [
                    [
                        'nom'         => 'Plantes médicinales et phytothérapie',
                        'description' => 'Soignez-vous avec les plantes africaines',
                        'produits'    => [
                            [
                                'nom'         => 'Encyclopédie des plantes médicinales d\'Afrique de l\'Ouest',
                                'description' => 'La référence complète sur 120 plantes médicinales africaines utilisées en médecine traditionnelle, validées par des études scientifiques. Pour chaque plante : nom local et scientifique, photo d\'identification, propriétés thérapeutiques, préparations (décoctions, macérations, poudres), dosages adultes et enfants, contre-indications et interactions médicamenteuses. Conçu avec des ethnobotanistes de l\'Université Félix Houphouët-Boigny.',
                                'prix'        => 10000,
                                'nb_ventes'   => 167,
                                'image'       => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=500&h=350&fit=crop',
                            ],
                            [
                                'nom'         => 'Programme détox 21 jours aux plantes africaines',
                                'description' => 'Nettoyez votre corps en 21 jours avec un protocole de détoxification basé exclusivement sur des plantes et aliments africains. Plan jour par jour incluant tisanes détoxifiantes, jus de fruits locaux, recettes légères et exercices de yoga africain. Adapté au contexte culinaire ouest-africain, sans produits importés. Résultats documentés : perte de poids, peau éclatante, meilleure digestion et énergie retrouvée.',
                                'prix'        => 7500,
                                'nb_ventes'   => 134,
                                'image'       => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                    [
                        'nom'         => 'Fitness et sport',
                        'description' => 'Restez en forme avec des méthodes adaptées à l\'Afrique',
                        'produits'    => [
                            [
                                'nom'         => 'Programme fitness maison 30 jours — Sans équipement',
                                'description' => 'Transformez votre corps en 30 jours sans salle de sport ni équipement coûteux. Ce programme intensif adapté au contexte africain (chaleur, petits espaces, alimentation locale) propose des séances quotidiennes de 30 minutes : cardio danse afrobeat, musculation au poids du corps, étirements. Avec plan nutritionnel basé sur les aliments africains locaux. Niveau débutant à confirmé. Vidéos démos incluses.',
                                'prix'        => 5500,
                                'nb_ventes'   => 178,
                                'image'       => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=500&h=350&fit=crop',
                            ],
                        ],
                    ],
                ],
            ],

        ];
    }
}
