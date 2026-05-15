<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — apropos.php
   Page "À propos" : présentation, timeline et expériences.
   ============================================================ */

require 'fonctions.php';

/* ============================================================
   Données de la timeline définies en PHP.
   ============================================================ */
$etapes_timeline = [
    [
        'cote'  => 'gauche',
        'date'  => '2024/2025 — Licence 1',
        'titre' => 'Découverte du développement web',
        'texte' => 'Apprentissage de HTML et CSS. Réalisation de mon premier portfolio statique et responsive.',
        'actif' => false,
    ],
    [
        'cote'  => 'droite',
        'date'  => '2025 — 2026',
        'titre' => 'Programmation et bases de données',
        'texte' => 'Développement en C, Java et Python. Prise en main de MySQL pour la gestion de données.',
        'actif' => false,
    ],
    [
        'cote'  => 'gauche',
        'date'  => '2025 — 2026',
        'titre' => 'Systèmes embarqués & réseau',
        'texte' => 'Projets pratiques en systèmes embarqués. Configuration de routeurs, adressage IP et routage dynamique.',
        'actif' => false,
    ],
    [
        'cote'  => 'droite',
        'date'  => '2026 — Aujourd\'hui',
        'titre' => 'PHP, web avancé & affiliation',
        'texte' => 'Développement PHP/MySQL, création de sites d\'affiliation et gestion de contenu SEO.',
        'actif' => true, /* Étape en cours — mise en surbrillance */
    ],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Mamadou Ndiaye</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php require 'composants/navigation.php'; ?>

    <!-- ========== EN-TÊTE DE PAGE ========== -->
    <section class="entete-page">
        <h1>À <span class="couleur-accent">propos</span> de moi</h1>
        <p>Découvrez mon parcours, ma formation et ce qui me passionne.</p>
    </section>

    <!-- ========== PRÉSENTATION ========== -->
    <section class="section-presentation">

        <div class="presentation-photo">
            <div class="photo-placeholder grand">
                <img src="images/Ma photo.png" alt="Photo de Mamadou Ndiaye">
            </div>
            <div class="badge-photo">
                <i class="fas fa-graduation-cap"></i> Licence 2
            </div>
        </div>

        <div class="presentation-texte">
            <h2>Bonjour, je suis <span class="couleur-accent">Mamadou Ndiaye</span></h2>
            <p>
                Étudiant en Licence 2 en Génie Logiciel et Administration Réseau à l'École
                Supérieure de Technologie et de Management, je suis passionné par la création
                de solutions numériques.
            </p>
            <p>
                Je m'intéresse particulièrement au développement d'applications web et de logiciels,
                ainsi qu'à la création de contenu digital. Je développe également des projets en ligne,
                notamment à travers la rédaction d'articles sur mes sites d'affiliation.
            </p>
            <p>
                <strong>Ce qui me motive ?</strong> Transformer des idées en projets concrets,
                apprendre continuellement et repousser mes limites. Chaque projet représente
                pour moi une opportunité de progresser et de créer quelque chose d'utile et impactant.
            </p>

            <!-- Badges d'informations personnelles -->
            <div class="infos-badges">
                <span class="badge"><i class="fas fa-map-marker-alt"></i> Sénégal</span>
                <span class="badge"><i class="fas fa-university"></i> ESTM</span>
                <span class="badge"><i class="fas fa-code"></i> Développeur web</span>
                <span class="badge"><i class="fas fa-link"></i> Créateur de contenu</span>
            </div>

            <!-- Lien de téléchargement du CV -->
            <a href="images/Cv Mamadou Ndiaye.pdf"
               download
               class="btn-principal"
               style="margin-top: 1.5rem; display: inline-block;">
                <i class="fas fa-download"></i> Télécharger mon CV
            </a>
        </div>

    </section>

    <!-- ========== TIMELINE ========== -->
    <section class="section-timeline">
        <h2 class="titre-section">Ma <span class="couleur-accent">progression</span></h2>
        <p class="sous-titre">Les grandes étapes de mon apprentissage</p>

        <div class="timeline">

            <?php
            /* Afficher chaque étape de la timeline dynamiquement */
            foreach ($etapes_timeline as $etape) :

                /* Appliquer la classe 'actif' sur le point et le contenu si c'est l'étape en cours */
                $classe_point   = $etape['actif'] ? 'timeline-point actif'   : 'timeline-point';
                $classe_contenu = $etape['actif'] ? 'timeline-contenu actif' : 'timeline-contenu';
            ?>

            <div class="timeline-item <?= nettoyer($etape['cote']) ?>">
                <div class="<?= $classe_point ?>"></div>
                <div class="<?= $classe_contenu ?>">
                    <span class="timeline-date"><?= nettoyer($etape['date']) ?></span>
                    <h3><?= nettoyer($etape['titre']) ?></h3>
                    <p><?= nettoyer($etape['texte']) ?></p>
                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </section>

    <!-- ========== EXPÉRIENCES ========== -->
    <section class="section-experiences">
        <h2 class="titre-section">Expériences & <span class="couleur-accent">projets en ligne</span></h2>
        <p class="sous-titre">Mes activités en parallèle de mes études</p>

        <div class="experiences-grille">

            <div class="experience-carte">
                <div class="exp-icone"><i class="fas fa-globe"></i></div>
                <h3>Sites web d'affiliation</h3>
                <span class="exp-periode">2024 — Présent</span>
                <p>
                    Création et gestion de deux sites web d'affiliation :
                    <strong>meilleur-mixeur.com</strong> et
                    <strong>mon-guide-petit-electromenager.com</strong>.
                    Publication de contenu optimisé et stratégies pour générer du trafic.
                </p>
                <div class="exp-tags">
                    <span class="tag">WordPress</span>
                    <span class="tag">SEO</span>
                    <span class="tag">Marketing digital</span>
                    <span class="tag">Affiliation</span>
                </div>
            </div>

            <div class="experience-carte">
                <div class="exp-icone"><i class="fas fa-bullseye"></i></div>
                <h3>Objectif en cours</h3>
                <span class="exp-periode">En développement</span>
                <p>
                    Développer des projets web rentables et à fort impact. J'ai déjà obtenu
                    mes premiers résultats, ce qui renforce ma motivation à aller plus loin.
                </p>
                <div class="exp-tags">
                    <span class="tag">Analyse des performances</span>
                    <span class="tag">Amélioration continue</span>
                </div>
            </div>

        </div>
    </section>

    <?php require 'composants/pied-de-page.php'; ?>

    <script src="js/script.js"></script>

</body>
</html>
