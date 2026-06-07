<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — competences.php
   Page des compétences techniques et transversales.
   Les données sont dans des tableaux PHP pour un affichage
   dynamique avec des boucles foreach.
   ============================================================ */

session_start();
require_once 'fonctions.php';
require_once 'config/connexion.php';
enregistrer_visite($pdo, 'competences.php');

/* ============================================================
   DONNÉES DES LANGAGES DE PROGRAMMATION
   ============================================================ */
$langages = [
    [
        'nom'         => 'HTML5',
        'icone'       => 'fab fa-html5',
        'couleur'     => '#e34c26',
        'niveau'      => 85,
        'description' => 'Structure et sémantique des pages web, formulaires, accessibilité.',
    ],
    [
        'nom'         => 'CSS3',
        'icone'       => 'fab fa-css3-alt',
        'couleur'     => '#264de4',
        'niveau'      => 80,
        'description' => 'Mise en forme, Flexbox, Grid, responsive design et animations.',
    ],
    [
        'nom'         => 'JavaScript',
        'icone'       => 'fab fa-js',
        'couleur'     => '#f7df1e',
        'niveau'      => 70,
        'description' => 'Interactions dynamiques, manipulation du DOM, événements.',
        'icone_style' => 'color:#000;', /* Texte noir pour contraste sur fond jaune */
    ],
    [
        'nom'         => 'PHP',
        'icone'       => 'fab fa-php',
        'couleur'     => '#8892be',
        'niveau'      => 95,
        'description' => 'Développement côté serveur, formulaires, sessions et connexion BDD.',
    ],
    [
        'nom'         => 'MySQL',
        'icone'       => 'fas fa-database',
        'couleur'     => '#00758f',
        'niveau'      => 85,
        'description' => 'Requêtes SQL, CRUD, gestion de bases de données relationnelles.',
    ],
    [
        'nom'         => 'Java',
        'icone'       => 'fab fa-java',
        'couleur'     => '#007396',
        'niveau'      => 75,
        'description' => 'Programmation orientée objet, logique algorithmique, applications de base.',
    ],
    [
        'nom'         => 'Langage C',
        'icone'       => 'fas fa-terminal',
        'couleur'     => '#555555',
        'niveau'      => 90,
        'description' => 'Programmation bas niveau, gestion mémoire, projets embarqués.',
    ],
    [
        'nom'         => 'Python',
        'icone'       => 'fab fa-python',
        'couleur'     => '#3776ab',
        'niveau'      => 85,
        'description' => 'Scripting, algorithmique, traitement de données et automatisation.',
    ],
];

/* ============================================================
   DONNÉES DES OUTILS ET LOGICIELS
   ============================================================ */
$outils = [
    ['icone' => 'fab fa-git-alt', 'nom' => 'Git & GitHub'],
    ['icone' => 'fas fa-code',    'nom' => 'VS Code'],
    ['icone' => 'fas fa-coffee',  'nom' => 'IntelliJ IDEA'],
    ['icone' => 'fas fa-circle',  'nom' => 'Eclipse'],
    ['icone' => 'fas fa-server',  'nom' => 'XAMPP'],
    ['icone' => 'fas fa-table',   'nom' => 'phpMyAdmin'],
];

/* ============================================================
   COMPÉTENCES RÉSEAUX ET SYSTÈMES
   ============================================================ */
$competences_reseau = [
    ['icone' => 'fas fa-network-wired',   'texte' => 'Configuration de réseaux (IP, sous-réseaux)'],
    ['icone' => 'fas fa-route',           'texte' => 'Routage statique et dynamique'],
    ['icone' => 'fas fa-project-diagram', 'texte' => 'Configuration de routeurs et commutateurs'],
    ['icone' => 'fab fa-linux',           'texte' => 'Systèmes Linux (commandes de base)'],
    ['icone' => 'fas fa-lock',            'texte' => 'Notions de sécurité (chiffrement)'],
    ['icone' => 'fas fa-shield-alt',      'texte' => 'DNSSEC — Sécurisation des zones DNS'],
];

/* ============================================================
   COMPÉTENCES TRANSVERSALES (soft skills)
   ============================================================ */
$autres_competences = [
    ['icone' => 'fas fa-brain',      'texte' => 'Résolution de problèmes algorithmiques'],
    ['icone' => 'fas fa-lightbulb',  'texte' => 'Logique de programmation'],
    ['icone' => 'fas fa-sitemap',    'texte' => 'Analyse et conception de solutions'],
    ['icone' => 'fas fa-pen-nib',    'texte' => 'Rédaction de contenu web (SEO)'],
    ['icone' => 'fas fa-users',      'texte' => 'Travail en équipe'],
    ['icone' => 'fas fa-chart-line', 'texte' => 'Analyse des performances web'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compétences - Mamadou Ndiaye</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php require 'composants/navigation.php'; ?>

    <!-- ========== EN-TÊTE DE PAGE ========== -->
    <section class="entete-page">
        <h1>Mes <span class="couleur-accent">compétences</span></h1>
        <p>Les langages, outils et technologies que je maîtrise et continue d'apprendre.</p>
    </section>

    <!-- ========== LANGAGES DE PROGRAMMATION ========== -->
    <section class="section-competences">
        <h2 class="titre-section">Langages de <span class="couleur-accent">programmation</span></h2>
        <p class="sous-titre">Les langages que j'utilise dans mes projets</p>

        <div class="competences-grille">

            <?php
            /* Boucle foreach sur le tableau $langages.
               Chaque entrée génère une carte de compétence. */
            foreach ($langages as $lang) :

                /* Récupérer le style optionnel de l'icône (ex: couleur texte) */
                $style_icone = isset($lang['icone_style']) ? $lang['icone_style'] : '';
            ?>

            <div class="competence-carte">

                <!-- Icône colorée avec la couleur spécifique au langage -->
                <div class="comp-icone"
                     style="background:<?= nettoyer($lang['couleur']) ?>; <?= $style_icone ?>">
                    <i class="<?= nettoyer($lang['icone']) ?>"></i>
                </div>

                <h3><?= nettoyer($lang['nom']) ?></h3>
                <p><?= nettoyer($lang['description']) ?></p>

                <!-- Barre de progression du niveau de maîtrise -->
                <div class="barre-progression">
                    <div class="barre-remplissage"
                         style="width: <?= (int)$lang['niveau'] ?>%;"></div>
                </div>
                <span class="comp-pourcent"><?= (int)$lang['niveau'] ?>%</span>

            </div>

            <?php endforeach; ?>

        </div>
    </section>

    <!-- ========== OUTILS ET TECHNOLOGIES ========== -->
    <section class="section-outils">
        <h2 class="titre-section">Outils & <span class="couleur-accent">technologies</span></h2>
        <p class="sous-titre">Les outils que j'utilise au quotidien</p>

        <div class="outils-liste">
            <?php foreach ($outils as $outil) : ?>
                <div class="outil-badge">
                    <i class="<?= nettoyer($outil['icone']) ?>"></i>
                    <?= nettoyer($outil['nom']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ========== RÉSEAUX ET SYSTÈMES ========== -->
    <section class="section-competences fond-alt">
        <h2 class="titre-section">Réseaux & <span class="couleur-accent">systèmes</span></h2>
        <p class="sous-titre">Administration réseau et systèmes Linux</p>

        <div class="soft-grille">
            <?php foreach ($competences_reseau as $comp) : ?>
                <div class="soft-item">
                    <i class="<?= nettoyer($comp['icone']) ?>"></i>
                    <?= nettoyer($comp['texte']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ========== AUTRES COMPÉTENCES ========== -->
    <section class="section-competences">
        <h2 class="titre-section">Autres <span class="couleur-accent">compétences</span></h2>
        <p class="sous-titre">Compétences transversales</p>

        <div class="soft-grille">
            <?php foreach ($autres_competences as $comp) : ?>
                <div class="soft-item">
                    <i class="<?= nettoyer($comp['icone']) ?>"></i>
                    <?= nettoyer($comp['texte']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require 'composants/pied-de-page.php'; ?>

    <script src="js/script.js"></script>

</body>
</html>
