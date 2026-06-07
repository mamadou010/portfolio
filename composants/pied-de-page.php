<?php
/* ============================================================
   COMPOSANT — composants/pied-de-page.php
   Pied de page commun à toutes les pages.

   Utilisation dans n'importe quelle page :
       <?php require 'composants/pied-de-page.php'; ?>
   ============================================================ */

/* Adresse email — définie une seule fois ici */
$email_contact  = 'ndiayem9999@gmail.com';
$email_encode   = urlencode($email_contact);
$sujet_encode   = urlencode('Contact via le portfolio de Mamadou Ndiaye');

/* Lien Gmail web (fonctionne dans Chrome sans client email) */
$lien_gmail = 'https://mail.google.com/mail/?view=cm&to=' . $email_encode . '&su=' . $sujet_encode;
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

            <!-- GitHub -->
            <a href="https://github.com/mamadou010"
               target="_blank"
               rel="noopener noreferrer"
               title="Mon profil GitHub">
                <i class="fab fa-github"></i>
            </a>

            <!-- Site d'affiliation -->
            <a href="https://meilleur-mixeur.com"
               target="_blank"
               rel="noopener noreferrer"
               title="Site d'affiliation">
                <i class="fas fa-globe"></i>
            </a>

            <!-- Email : ouvre Gmail dans un nouvel onglet
                 Compatible Chrome (pas besoin de client email installé)
                 Sur mobile/Edge, on garde aussi mailto en fallback via JS -->
            <a href="<?= $lien_gmail ?>"
               target="_blank"
               rel="noopener noreferrer"
               title="Envoyer un e-mail"
               id="lien-email-footer"
               data-email="<?= htmlspecialchars($email_contact) ?>"
               data-sujet="<?= htmlspecialchars($sujet_encode) ?>">
                <i class="fas fa-envelope"></i>
            </a>

        </div>

        <!-- Année calculée dynamiquement par PHP -->
        <p class="footer-copy">
            &copy; <?= date('Y') ?> Mamadou Ndiaye &mdash; Tous droits réservés
        </p>

    </div>
</footer>

<script>
/* ============================================================
   Détection intelligente pour le lien email du footer.
   - Sur mobile            → ouvre l'app email native (mailto:)
   - Sur PC (Chrome, etc.) → ouvre Gmail dans un nouvel onglet
   ============================================================ */
(function() {
    var lienEmail = document.getElementById('lien-email-footer');
    if (!lienEmail) return;

    /* Détecter si l'utilisateur est sur mobile */
    var estMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i
                    .test(navigator.userAgent);

    if (estMobile) {
        /* Sur mobile → mailto: ouvre l'app email native directement */
        var email  = lienEmail.getAttribute('data-email');
        var sujet  = decodeURIComponent(lienEmail.getAttribute('data-sujet'));
        lienEmail.href   = 'mailto:' + email + '?subject=' + encodeURIComponent(sujet);
        lienEmail.target = '_self';
    }
    /* Sur PC → on garde le lien Gmail (déjà défini en href) */
})();
</script>