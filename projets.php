<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — projets.php
   Page des projets avec recherche côté serveur via PDO.
   Les projets sont maintenant lus depuis la table `projets`.
   Journalisation de la visite.
   ============================================================ */

session_start();
require_once 'fonctions.php';
require_once 'config/connexion.php';

/* ---- Journalisation de la visite -------------------------- */
enregistrer_visite($pdo, 'projets.php');

/* ---- Récupérer le mot-clé depuis l'URL (?q=...) ---------- */
$mot_cle = valeur_get('q');

/* ---- Récupérer les projets filtrés depuis la BDD ---------- */
$resultats    = filtrer_projets_bdd($pdo, $mot_cle);
$nb_resultats = count($resultats);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Mamadou Ndiaye</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php require 'composants/navigation.php'; ?>

    <section class="entete-page">
        <h1>Mes <span class="couleur-accent">projets</span></h1>
        <p>Des projets académiques et personnels qui reflètent mon évolution.</p>
    </section>

    <!-- ========== FORMULAIRE DE RECHERCHE (méthode GET) ========== -->
    <section class="section-recherche">
        <form method="GET" action="projets.php" class="recherche-wrapper" role="search">
            <i class="fas fa-search icone-recherche"></i>
            <input type="search"
                   id="champRecherche"
                   name="q"
                   class="champ-recherche"
                   placeholder="Rechercher un projet (ex: HTML, C, MySQL, embarqué...)"
                   aria-label="Rechercher un projet"
                   value="<?= nettoyer($mot_cle) ?>">
            <button type="submit" class="btn-recherche" title="Lancer la recherche">
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <?php if ($mot_cle !== '') : ?>
            <p class="info-recherche">
                <?php if ($nb_resultats > 0) : ?>
                    <i class="fas fa-check-circle" style="color:var(--accent);"></i>
                    <?= $nb_resultats ?> projet<?= $nb_resultats > 1 ? 's' : '' ?>
                    trouvé<?= $nb_resultats > 1 ? 's' : '' ?>
                    pour « <strong><?= nettoyer($mot_cle) ?></strong> »
                <?php else : ?>
                    <i class="fas fa-times-circle" style="color:#e74c3c;"></i>
                    Aucun projet ne correspond à « <strong><?= nettoyer($mot_cle) ?></strong> »
                <?php endif; ?>
                &mdash;
                <a href="projets.php" class="lien-reinit">Voir tous les projets</a>
            </p>
        <?php endif; ?>
    </section>

    <!-- ========== GRILLE DE PROJETS ========== -->
    <section class="section-projets">
        <div class="projets-grille" id="projetsGrille">

            <?php if (!empty($resultats)) : ?>

                <?php foreach ($resultats as $projet) :
                    /* Convertir la chaîne de technologies en tableau pour les badges */
                    $techs = array_map('trim', explode(',', $projet['technologies']));
                ?>

                <article class="projet-carte">
                    <div class="projet-image-remplace">
                        <?php if (!empty($projet['image'])) : ?>
                            <img src="<?= nettoyer($projet['image']) ?>"
                                 alt="<?= nettoyer($projet['titre']) ?>">
                        <?php else : ?>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="projet-corps">
                        <h3><?= nettoyer($projet['titre']) ?></h3>
                        <p><?= nettoyer($projet['description']) ?></p>

                        <!-- Badges des technologies -->
                        <div class="projet-tags">
                            <?php foreach ($techs as $tech) : ?>
                                <span class="tag"><?= nettoyer($tech) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($projet['lien'])) : ?>
                            <div class="projet-liens">
                                <a href="<?= nettoyer($projet['lien']) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="btn-petit btn-principal">
                                    <i class="fas fa-external-link-alt"></i> Voir le site
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>

                <?php endforeach; ?>

            <?php else : ?>
                <div class="msg-aucun-resultat">
                    <i class="fas fa-search"></i>
                    <p>Aucun projet ne correspond à cette recherche.</p>
                    <a href="projets.php" class="btn-secondaire">Voir tous les projets</a>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <?php require 'composants/pied-de-page.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
