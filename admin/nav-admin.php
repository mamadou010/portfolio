<?php
/* ============================================================
   COMPOSANT ADMIN — admin/nav-admin.php
   Barre de navigation commune à toutes les pages admin.
   ============================================================ */

$page_admin = basename($_SERVER['PHP_SELF']);
$dossier    = basename(dirname($_SERVER['PHP_SELF']));

/* Détecter la racine du projet pour construire des URLs absolues.
   On remonte jusqu'au dossier admin/ quel que soit le sous-dossier actuel.
   Exemple : si on est dans admin/projets/liste.php
             on veut  /portfolio/admin/
   On utilise le chemin du script relatif à la racine Apache. */
$script_url = $_SERVER['SCRIPT_NAME']; 
/* Exemple : /portfolio/admin/projets/liste.php */

/* Extraire la partie jusqu'à /admin/ inclus */
$pos_admin = strpos($script_url, '/admin/');
if ($pos_admin !== false) {
    $base_admin = substr($script_url, 0, $pos_admin) . '/admin/';
} else {
    /* Fallback si on est directement dans admin/ */
    $base_admin = dirname($script_url) . '/';
}
/* $base_admin = /portfolio/admin/ */

function lien_admin_actif(string $fichier): string {
    global $page_admin;
    return ($page_admin === $fichier) ? 'actif' : '';
}
function dossier_admin_actif(string $dossier_cible): string {
    global $dossier;
    return ($dossier === $dossier_cible) ? 'actif' : '';
}
?>
<header class="admin-header">

    <!-- Logo → Dashboard (chemin absolu) -->
    <div class="admin-logo">
        <a href="<?= $base_admin ?>dashboard.php">MN<span>.</span> <small>Admin</small></a>
    </div>

    <nav class="admin-nav">

        <!-- Dashboard -->
        <a href="<?= $base_admin ?>dashboard.php"
           class="admin-lien <?= lien_admin_actif('dashboard.php') ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <!-- Projets -->
        <a href="<?= $base_admin ?>projets/liste.php"
           class="admin-lien <?= dossier_admin_actif('projets') ?>">
            <i class="fas fa-folder-open"></i> Projets
        </a>

        <!-- Administrateurs -->
        <a href="<?= $base_admin ?>utilisateurs/liste.php"
           class="admin-lien <?= dossier_admin_actif('utilisateurs') ?>">
            <i class="fas fa-users-cog"></i> Administrateurs
        </a>

        <!-- Messages -->
        <a href="<?= $base_admin ?>messages/liste.php"
           class="admin-lien <?= dossier_admin_actif('messages') ?>">
            <i class="fas fa-envelope"></i> Messages
        </a>

        <!-- Demandes -->
        <a href="<?= $base_admin ?>demandes/liste.php"
           class="admin-lien <?= dossier_admin_actif('demandes') ?>">
            <i class="fas fa-rocket"></i> Demandes
        </a>

        <!-- Voir le site public (remonte de admin/ vers la racine) -->
        <a href="<?= substr($base_admin, 0, $pos_admin + 1) ?>index.php"
           class="admin-lien" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-external-link-alt"></i> Voir le site
        </a>

        <!-- Déconnexion — TOUJOURS chemin absolu -->
        <a href="<?= $base_admin ?>deconnexion.php"
           class="admin-lien admin-deconnexion">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>

    </nav>

    <!-- Nom de l'admin connecté -->
    <div class="admin-user">
        <i class="fas fa-user-circle"></i>
        <?= nettoyer($_SESSION['admin_prenom'] ?? '') ?>
        <?= nettoyer($_SESSION['admin_nom'] ?? '') ?>
    </div>

</header>
