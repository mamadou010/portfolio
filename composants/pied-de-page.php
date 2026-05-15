<?php
/* ============================================================
   COMPOSANT — composants/pied-de-page.php
   Pied de page commun à toutes les pages.
   
   Utilisation dans n'importe quelle page :
       <?php require 'composants/pied-de-page.php'; ?>
   ============================================================ */
?>

<!-- ========== PIED DE PAGE ========== -->
<footer class="footer">
    <div class="footer-contenu">

        <!-- Logo cliquable vers l'accueil -->
        <div class="footer-logo">
            <a href="index.php">MN<span>.</span></a>
        </div>

        <!-- Liens rapides de navigation -->
        <div class="footer-liens">
            <a href="apropos.php">À propos</a>
            <a href="projets.php">Projets</a>
            <a href="contact.php">Contact</a>
        </div>

        <!-- Liens réseaux sociaux et sites externes -->
        <div class="footer-reseaux">
            <a href="https://github.com/mamadou010"
               target="_blank"
               rel="noopener noreferrer"
               title="Mon profil GitHub">
                <i class="fab fa-github"></i>
            </a>
            <a href="https://meilleur-mixeur.com"
               target="_blank"
               rel="noopener noreferrer"
               title="Site d'affiliation">
                <i class="fas fa-globe"></i>
            </a>
            <a href="mailto:ndiayem9999@gmail.com"
               title="Envoyer un e-mail">
                <i class="fas fa-envelope"></i>
            </a>
        </div>

        <!-- Année calculée dynamiquement par PHP -->
        <p class="footer-copy">
            &copy; <?= date('Y') ?> Mamadou Ndiaye &mdash; Tous droits réservés
        </p>

    </div>
</footer>
