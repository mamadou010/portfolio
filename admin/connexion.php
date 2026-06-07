<?php
/* ============================================================
   ESPACE ADMIN — admin/connexion.php
   Formulaire de connexion à l'espace d'administration.
   Sécurité : CSRF + message d'erreur générique + session_regenerate_id
   ============================================================ */

session_start();
require_once '../fonctions.php';
require_once '../config/connexion.php';

/* Si l'admin est déjà connecté → dashboard directement */
if (!empty($_SESSION['admin_id'])) {
    header('Location: /portfolio/admin/dashboard.php');
    exit;
}

$erreur  = '';
$email_v = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf('login');

    $email        = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $email_v      = nettoyer($email);

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = 'Identifiants incorrects.';
    } else {
        $req = $pdo->prepare(
            'SELECT id, prenom, nom, mot_de_passe FROM administrateurs WHERE email = :email LIMIT 1'
        );
        $req->execute([':email' => $email]);
        $admin = $req->fetch();

        if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']     = $admin['id'];
            $_SESSION['admin_prenom'] = $admin['prenom'];
            $_SESSION['admin_nom']    = $admin['nom'];
            header('Location: /portfolio/admin/dashboard.php');
            exit;
        } else {
            $erreur = 'Identifiants incorrects.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="page-connexion">
    <div class="connexion-conteneur">
        <div class="connexion-carte">
            <div class="connexion-logo"><a href="../index.php">MN<span>.</span></a></div>
            <h1>Espace <span class="couleur-accent">Administration</span></h1>
            <p class="connexion-sous-titre">Connectez-vous pour accéder au tableau de bord.</p>

            <?php if ($erreur) : ?>
                <div class="alerte alerte-erreur">
                    <i class="fas fa-exclamation-triangle"></i> <?= nettoyer($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="connexion.php" novalidate>
                <?php champ_csrf('login'); ?>
                <div class="champ-groupe">
                    <label for="email"><i class="fas fa-envelope"></i> Adresse e-mail</label>
                    <input type="email" id="email" name="email"
                           value="<?= $email_v ?>" placeholder="admin@exemple.com"
                           autocomplete="email" required>
                </div>
                <div class="champ-groupe">
                    <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                           placeholder="••••••••" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn-principal btn-formulaire">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
            <p class="retour-site"><a href="../index.php"><i class="fas fa-arrow-left"></i> Retour au portfolio</a></p>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
