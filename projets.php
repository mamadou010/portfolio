<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — projets.php
   Page des projets avec recherche côté serveur en PHP.

   Fonctionnement de la recherche :
   - L'utilisateur saisit un mot-clé dans le formulaire
   - Le formulaire est soumis en méthode GET (?q=mot)
   - PHP filtre les projets et retourne les résultats
   - La méthode GET permet de partager/mémoriser l'URL
   ============================================================ */

require 'fonctions.php';

/* --- Récupérer le mot-clé depuis l'URL (?q=...) ---
   valeur_get() nettoie la valeur et retourne '' si absente */
$mot_cle = valeur_get('q');

/* --- Récupérer tous les projets puis filtrer selon le mot-clé --- */
$tous_les_projets = get_projets();
$resultats        = filtrer_projets($tous_les_projets, $mot_cle);
$nb_resultats     = count($resultats);
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

    <!-- ========== EN-TÊTE DE PAGE ========== -->
    <section class="entete-page">
        <h1>Mes <span class="couleur-accent">projets</span></h1>
        <p>Des projets académiques et personnels qui reflètent mon évolution.</p>
    </section>

    <!-- ========== FORMULAIRE DE RECHERCHE (méthode GET) ========== -->
    <section class="section-recherche">

        <!--
            action="projets.php" : le formulaire s'envoie à lui-même
            method="GET"         : le mot-clé apparaît dans l'URL (?q=...)
                                   avantage : URL partageable et historique navigateur
        -->
        <form method="GET" action="projets.php" class="recherche-wrapper" role="search">

            <!-- Icône loupe décorative -->
            <i class="fas fa-search icone-recherche"></i>

            <input
                type="search"
                id="champRecherche"
                name="q"
                class="champ-recherche"
                placeholder="Rechercher un projet (ex: HTML, C, MySQL, embarqué...)"
                aria-label="Rechercher un projet"
                value="<?= nettoyer($mot_cle) ?>"
            >

            <!-- Bouton de soumission (fonctionne aussi avec la touche Entrée) -->
            <button type="submit" class="btn-recherche" title="Lancer la recherche">
                <i class="fas fa-arrow-right"></i>
            </button>

        </form>

        <?php if ($mot_cle !== '') : ?>
            <!--
                Afficher le nombre de résultats et un lien pour réinitialiser.
                Cette section n'apparaît que si une recherche a été effectuée.
            -->
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

    <!-- ========== GRILLE DE PROJETS (affichage dynamique PHP) ========== -->
    <section class="section-projets">
        <div class="projets-grille" id="projetsGrille">

            <?php if (!empty($resultats)) : ?>

                <?php
                /* Boucle sur les projets filtrés.
                   Chaque projet génère une carte article. */
                foreach ($resultats as $projet) :
                ?>

                <article class="projet-carte">

                    <!-- Image du projet -->
                    <div class="projet-image-remplace">
                        <img src="<?= nettoyer($projet['image']) ?>"
                             alt="<?= nettoyer($projet['titre']) ?>">
                    </div>

                    <!-- Corps de la carte -->
                    <div class="projet-corps">

                        <h3><?= nettoyer($projet['titre']) ?></h3>
                        <p><?= nettoyer($projet['description']) ?></p>

                        <!-- Badges des technologies utilisées -->
                        <div class="projet-tags">
                            <?php foreach ($projet['technologies'] as $tech) : ?>
                                <span class="tag"><?= nettoyer($tech) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($projet['lien'])) : ?>
                            <!-- Lien vers le site si disponible (ex: affiliation) -->
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

                <!-- Message affiché si aucun projet ne correspond à la recherche -->
                <div class="msg-aucun-resultat">
                    <i class="fas fa-search"></i>
                    <p>Aucun projet ne correspond à cette recherche.</p>
                    <a href="projets.php" class="btn-secondaire">
                        Voir tous les projets
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <?php require 'composants/pied-de-page.php'; ?>

    <script src="js/script.js"></script>

</body>
</html>
