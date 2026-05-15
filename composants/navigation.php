<?php
/* ============================================================
   COMPOSANT — composants/navigation.php
   Barre de navigation commune à toutes les pages.

   Utilisation dans n'importe quelle page :
       <?php require 'composants/navigation.php'; ?>
   ============================================================ */

/* Récupérer uniquement le nom du fichier en cours d'exécution.
   Exemple : basename('/home/portfolio/apropos.php') → 'apropos.php' */
$page_courante = basename($_SERVER['PHP_SELF']);

/**
 * Retourne la classe CSS 'actif' si le fichier correspond à la page courante.
 * Permet de mettre en surbrillance le lien de navigation actif.
 *
 * @param string $fichier  Nom du fichier à comparer (ex: 'index.php')
 * @return string          'actif' ou chaîne vide
 */
function lien_actif(string $fichier): string {
    global $page_courante;
    return ($page_courante === $fichier) ? 'actif' : '';
}
?>

<!-- ========== NAVIGATION ========== -->
<nav class="navbar">

    <!-- Logo cliquable qui ramène à l'accueil -->
    <div class="nav-logo">
        <a href="index.php">MN<span>.</span></a>
    </div>

    <!-- Menu principal — tous les liens pointent vers la racine -->
    <ul class="nav-menu">
        <li>
            <a href="index.php"
               class="nav-lien <?= lien_actif('index.php') ?>">
               Accueil
            </a>
        </li>
        <li>
            <a href="apropos.php"
               class="nav-lien <?= lien_actif('apropos.php') ?>">
               À propos
            </a>
        </li>
        <li>
            <a href="competences.php"
               class="nav-lien <?= lien_actif('competences.php') ?>">
               Compétences
            </a>
        </li>
        <li>
            <a href="projets.php"
               class="nav-lien <?= lien_actif('projets.php') ?>">
               Projets
            </a>
        </li>
        <li>
            <a href="contact.php"
               class="nav-lien <?= lien_actif('contact.php') ?>">
               Contact
            </a>
        </li>
    </ul>

    <!-- Bouton pour basculer entre mode sombre et mode clair -->
    <button class="btn-theme" id="btnTheme" title="Changer le thème">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Bouton hamburger pour le menu sur mobile -->
    <button class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </button>

</nav>
