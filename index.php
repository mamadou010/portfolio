<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — index.php
   Page d'accueil. Partie 3 : lecture BDD + journalisation.
   ============================================================ */

session_start();
require_once 'fonctions.php';
require_once 'config/connexion.php';

/* Journalisation de la visite */
enregistrer_visite($pdo, 'index.php');

/* Récupérer les projets depuis la BDD pour le carrousel */
$tous_les_projets = get_projets_bdd($pdo);
$nb_projets       = count($tous_les_projets);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamadou Ndiaye - Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php require 'composants/navigation.php'; ?>

    <!-- ========== SECTION HERO ========== -->
    <section class="hero">
        <div class="hero-texte">
            <p class="hero-salut">👋 Bonjour, je suis</p>
            <h1 class="hero-nom">Mamadou <span class="couleur-accent">Ndiaye</span></h1>
            <h2 class="hero-titre">Étudiant en Génie Logiciel & Créateur de contenu</h2>
            <p class="hero-description">
                Passionné par le développement web, les systèmes embarqués et la création
                de contenu digital. J'aime transformer des idées en projets concrets et utiles.
            </p>
            <div class="hero-boutons">
                <a href="projets.php" class="btn-principal">Voir mes projets</a>
                <a href="contact.php" class="btn-secondaire">Me contacter</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="photo-placeholder">
                <img src="images/Ma photo principale.png" alt="Photo de Mamadou Ndiaye">
            </div>
        </div>
    </section>

    <!-- ========== STATISTIQUES ========== -->
    <section class="section-stats">
        <div class="stat-item">
            <span class="stat-nombre"><?= $nb_projets ?>+</span>
            <span class="stat-label">Projets réalisés</span>
        </div>
        <div class="stat-item">
            <span class="stat-nombre">8+</span>
            <span class="stat-label">Technologies apprises</span>
        </div>
        <div class="stat-item">
            <span class="stat-nombre">2</span>
            <span class="stat-label">Sites d'affiliation</span>
        </div>
        <div class="stat-item">
            <span class="stat-nombre">2</span>
            <span class="stat-label">Années de formation</span>
        </div>
    </section>

    <!-- ========== CARROUSEL ========== -->
    <section class="section-carrousel">
        <h2 class="titre-section">Aperçu de mes <span class="couleur-accent">projets</span></h2>
        <p class="sous-titre">Quelques réalisations récentes</p>

        <div class="carrousel-conteneur">
            <div class="carrousel-piste" id="carrouselPiste">

                <?php
                /* Afficher les 4 premiers projets dans le carrousel */
                $projets_carrousel = array_slice($tous_les_projets, 0, 4);
                foreach ($projets_carrousel as $projet) :
                    /* Convertir la chaîne technologies en badge lisible */
                    $tech_badge = $projet['technologies'];
                ?>
                <div class="carrousel-slide">
                    <div class="slide-image">
                        <?php if (!empty($projet['image'])) : ?>
                            <img src="<?= nettoyer($projet['image']) ?>"
                                 alt="<?= nettoyer($projet['titre']) ?>"
                                 class="slide-img">
                        <?php endif; ?>
                    </div>
                    <h3><?= nettoyer($projet['titre']) ?></h3>
                    <p><?= nettoyer($projet['description']) ?></p>
                    <span class="slide-tag"><?= nettoyer($tech_badge) ?></span>
                </div>
                <?php endforeach; ?>

            </div>

            <button class="carrousel-btn precedent" id="btnPrecedent">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carrousel-btn suivant" id="btnSuivant">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="carrousel-points" id="carrouselPoints"></div>
        </div>
    </section>

    <?php require 'composants/pied-de-page.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
