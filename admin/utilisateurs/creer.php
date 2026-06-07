<?php
/* ============================================================
   ADMIN — admin/utilisateurs/creer.php
   Création d'un nouvel administrateur.
   Le mot de passe est haché avec PASSWORD_BCRYPT avant insertion.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$erreurs = [];
$valeurs = ['prenom' => '', 'nom' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf('creer_admin');

    $prenom       = trim($_POST['prenom'] ?? '');
    $nom          = trim($_POST['nom'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    $valeurs = ['prenom' => nettoyer($prenom), 'nom' => nettoyer($nom), 'email' => nettoyer($email)];

    if (!champ_requis($prenom))       { $erreurs['prenom']       = 'Le prénom est obligatoire.'; }
    if (!champ_requis($nom))          { $erreurs['nom']          = 'Le nom est obligatoire.'; }
    if (!champ_requis($email))        { $erreurs['email']        = "L'email est obligatoire."; }
    elseif (!email_valide($email))    { $erreurs['email']        = "L'email n'est pas valide."; }
    if (!champ_requis($mot_de_passe)) { $erreurs['mot_de_passe'] = 'Le mot de passe est obligatoire.'; }
    elseif (strlen($mot_de_passe) < 8){ $erreurs['mot_de_passe'] = 'Le mot de passe doit faire au moins 8 caractères.'; }
    if ($mot_de_passe !== $confirmation){ $erreurs['confirmation'] = 'Les mots de passe ne correspondent pas.'; }

    /* Vérifier que l'email n'est pas déjà utilisé */
    if (empty($erreurs['email'])) {
        $req = $pdo->prepare('SELECT id FROM administrateurs WHERE email = :email LIMIT 1');
        $req->execute([':email' => $email]);
        if ($req->fetch()) { $erreurs['email'] = 'Cet email est déjà utilisé.'; }
    }

    if (empty($erreurs)) {
        /* Hacher le mot de passe avec BCRYPT */
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $req  = $pdo->prepare(
            'INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (:p, :n, :e, :m)'
        );
        $req->execute([':p' => $prenom, ':n' => $nom, ':e' => $email, ':m' => $hash]);
        header('Location: liste.php?msg=ajoute');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel administrateur</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
    <?php require '../nav-admin.php'; ?>
    <main class="admin-main">
        <div class="admin-conteneur">
            <div class="admin-entete">
                <h1><i class="fas fa-user-plus"></i> Nouvel administrateur</h1>
                <a href="liste.php" class="btn-secondaire"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
            <div class="admin-section">
                <form method="POST" action="creer.php" novalidate>
                    <?php champ_csrf('creer_admin'); ?>
                    <div class="form-grille-2">
                        <div class="champ-groupe">
                            <label for="prenom">Prénom <span class="requis">*</span></label>
                            <input type="text" id="prenom" name="prenom" value="<?= $valeurs['prenom'] ?>">
                            <?php afficher_erreur($erreurs, 'prenom'); ?>
                        </div>
                        <div class="champ-groupe">
                            <label for="nom">Nom <span class="requis">*</span></label>
                            <input type="text" id="nom" name="nom" value="<?= $valeurs['nom'] ?>">
                            <?php afficher_erreur($erreurs, 'nom'); ?>
                        </div>
                    </div>
                    <div class="champ-groupe">
                        <label for="email">Email <span class="requis">*</span></label>
                        <input type="email" id="email" name="email" value="<?= $valeurs['email'] ?>">
                        <?php afficher_erreur($erreurs, 'email'); ?>
                    </div>
                    <div class="form-grille-2">
                        <div class="champ-groupe">
                            <label for="mot_de_passe">Mot de passe <span class="requis">*</span></label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Min. 8 caractères">
                            <?php afficher_erreur($erreurs, 'mot_de_passe'); ?>
                        </div>
                        <div class="champ-groupe">
                            <label for="confirmation">Confirmer le mot de passe <span class="requis">*</span></label>
                            <input type="password" id="confirmation" name="confirmation" placeholder="Répétez le mot de passe">
                            <?php afficher_erreur($erreurs, 'confirmation'); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-principal"><i class="fas fa-user-plus"></i> Créer l'administrateur</button>
                        <a href="liste.php" class="btn-secondaire">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
