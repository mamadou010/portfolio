<?php
/* ============================================================
   ADMIN — admin/utilisateurs/modifier.php
   Modification d'un administrateur.
   Si le champ mot de passe est vide → l'ancien hash est conservé.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

$req = $pdo->prepare('SELECT id, prenom, nom, email FROM administrateurs WHERE id = :id LIMIT 1');
$req->execute([':id' => $id]);
$admin = $req->fetch();
if (!$admin) { header('Location: liste.php'); exit; }

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf('modifier_admin');

    $prenom       = trim($_POST['prenom'] ?? '');
    $nom          = trim($_POST['nom'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if (!champ_requis($prenom))    { $erreurs['prenom'] = 'Le prénom est obligatoire.'; }
    if (!champ_requis($nom))       { $erreurs['nom']    = 'Le nom est obligatoire.'; }
    if (!champ_requis($email))     { $erreurs['email']  = "L'email est obligatoire."; }
    elseif (!email_valide($email)) { $erreurs['email']  = "L'email n'est pas valide."; }

    /* Vérifier que l'email n'est pas pris par un AUTRE admin */
    if (empty($erreurs['email'])) {
        $req2 = $pdo->prepare('SELECT id FROM administrateurs WHERE email = :e AND id != :id LIMIT 1');
        $req2->execute([':e' => $email, ':id' => $id]);
        if ($req2->fetch()) { $erreurs['email'] = 'Cet email est déjà utilisé.'; }
    }

    /* Mot de passe : optionnel à la modification */
    $nouveau_hash = null;
    if (!empty($mot_de_passe)) {
        if (strlen($mot_de_passe) < 8) { $erreurs['mot_de_passe'] = 'Minimum 8 caractères.'; }
        elseif ($mot_de_passe !== $confirmation) { $erreurs['confirmation'] = 'Les mots de passe ne correspondent pas.'; }
        else { $nouveau_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT); }
    }

    if (empty($erreurs)) {
        if ($nouveau_hash) {
            /* Mettre à jour avec nouveau hash */
            $req3 = $pdo->prepare(
                'UPDATE administrateurs SET prenom=:p, nom=:n, email=:e, mot_de_passe=:m WHERE id=:id'
            );
            $req3->execute([':p'=>$prenom,':n'=>$nom,':e'=>$email,':m'=>$nouveau_hash,':id'=>$id]);
        } else {
            /* Conserver l'ancien hash */
            $req3 = $pdo->prepare(
                'UPDATE administrateurs SET prenom=:p, nom=:n, email=:e WHERE id=:id'
            );
            $req3->execute([':p'=>$prenom,':n'=>$nom,':e'=>$email,':id'=>$id]);
        }
        header('Location: liste.php?msg=modifie');
        exit;
    }

    $admin['prenom'] = $prenom;
    $admin['nom']    = $nom;
    $admin['email']  = $email;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier administrateur</title>
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
                <h1><i class="fas fa-user-edit"></i> Modifier l'administrateur</h1>
                <a href="liste.php" class="btn-secondaire"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
            <div class="admin-section">
                <form method="POST" action="modifier.php" novalidate>
                    <?php champ_csrf('modifier_admin'); ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="form-grille-2">
                        <div class="champ-groupe">
                            <label for="prenom">Prénom <span class="requis">*</span></label>
                            <input type="text" id="prenom" name="prenom" value="<?= nettoyer($admin['prenom']) ?>">
                            <?php afficher_erreur($erreurs, 'prenom'); ?>
                        </div>
                        <div class="champ-groupe">
                            <label for="nom">Nom <span class="requis">*</span></label>
                            <input type="text" id="nom" name="nom" value="<?= nettoyer($admin['nom']) ?>">
                            <?php afficher_erreur($erreurs, 'nom'); ?>
                        </div>
                    </div>
                    <div class="champ-groupe">
                        <label for="email">Email <span class="requis">*</span></label>
                        <input type="email" id="email" name="email" value="<?= nettoyer($admin['email']) ?>">
                        <?php afficher_erreur($erreurs, 'email'); ?>
                    </div>
                    <p class="info-champ"><i class="fas fa-info-circle"></i> Laissez le mot de passe vide pour le conserver.</p>
                    <div class="form-grille-2">
                        <div class="champ-groupe">
                            <label for="mot_de_passe">Nouveau mot de passe</label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Laisser vide = inchangé">
                            <?php afficher_erreur($erreurs, 'mot_de_passe'); ?>
                        </div>
                        <div class="champ-groupe">
                            <label for="confirmation">Confirmer</label>
                            <input type="password" id="confirmation" name="confirmation" placeholder="Répétez">
                            <?php afficher_erreur($erreurs, 'confirmation'); ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-principal"><i class="fas fa-save"></i> Enregistrer</button>
                        <a href="liste.php" class="btn-secondaire">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
