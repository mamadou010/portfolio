<?php
/* ============================================================
   ADMIN — admin/projets/creer.php
   Formulaire de création d'un nouveau projet.
   Upload d'image avec validation du type de fichier.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$erreurs = [];
$valeurs = ['titre' => '', 'description' => '', 'technologies' => '', 'lien' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf('creer_projet');

    $valeurs['titre']        = valeur_post('titre');
    $valeurs['description']  = valeur_post('description');
    $valeurs['technologies'] = valeur_post('technologies');
    $valeurs['lien']         = valeur_post('lien');

    /* Validation */
    if (!champ_requis($valeurs['titre']))        { $erreurs['titre']        = 'Le titre est obligatoire.'; }
    if (!champ_requis($valeurs['description']))  { $erreurs['description']  = 'La description est obligatoire.'; }
    if (!champ_requis($valeurs['technologies'])) { $erreurs['technologies'] = 'Les technologies sont obligatoires.'; }

    /* Upload image (optionnel) */
    $chemin_image = null;
    if (!empty($_FILES['image']['name'])) {
        /* Créer le dossier si nécessaire */
        $dossier = '../../images/projets/';
        if (!is_dir($dossier)) { mkdir($dossier, 0755, true); }
        $chemin_image = traiter_upload_image($_FILES['image'], $dossier);
        if ($chemin_image === null) {
            $erreurs['image'] = 'Format non autorisé. Utilisez jpg, jpeg, png, webp ou gif.';
        } else {
            /* Stocker un chemin relatif depuis la racine du projet */
            $chemin_image = 'images/projets/' . basename($chemin_image);
        }
    }

    if (empty($erreurs)) {
        $req = $pdo->prepare(
            'INSERT INTO projets (titre, description, technologies, image, lien)
             VALUES (:titre, :description, :technologies, :image, :lien)'
        );
        $req->execute([
            ':titre'        => trim($_POST['titre']),
            ':description'  => trim($_POST['description']),
            ':technologies' => trim($_POST['technologies']),
            ':image'        => $chemin_image,
            ':lien'         => trim($_POST['lien']) ?: null,
        ]);
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
    <title>Nouveau projet - Administration</title>
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
                <h1><i class="fas fa-plus-circle"></i> Nouveau projet</h1>
                <a href="liste.php" class="btn-secondaire">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
            <div class="admin-section">
                <!-- enctype requis pour l'upload de fichier -->
                <form method="POST" action="creer.php" enctype="multipart/form-data" novalidate>
                    <?php champ_csrf('creer_projet'); ?>

                    <div class="champ-groupe">
                        <label for="titre">Titre du projet <span class="requis">*</span></label>
                        <input type="text" id="titre" name="titre"
                               value="<?= $valeurs['titre'] ?>" placeholder="Ex: Application de gestion...">
                        <?php afficher_erreur($erreurs, 'titre'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="description">Description <span class="requis">*</span></label>
                        <textarea id="description" name="description" rows="5"
                                  placeholder="Décrivez le projet..."><?= $valeurs['description'] ?></textarea>
                        <?php afficher_erreur($erreurs, 'description'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="technologies">Technologies <span class="requis">*</span></label>
                        <input type="text" id="technologies" name="technologies"
                               value="<?= $valeurs['technologies'] ?>"
                               placeholder="Ex: PHP, MySQL, CSS (séparées par des virgules)">
                        <?php afficher_erreur($erreurs, 'technologies'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="lien">Lien externe <span class="optionnel">(optionnel)</span></label>
                        <input type="url" id="lien" name="lien"
                               value="<?= $valeurs['lien'] ?>" placeholder="https://exemple.com">
                    </div>

                    <div class="champ-groupe">
                        <label for="image">Image du projet <span class="optionnel">(jpg, jpeg, png, webp, gif)</span></label>
                        <input type="file" id="image" name="image"
                               accept=".jpg,.jpeg,.png,.webp,.gif">
                        <?php afficher_erreur($erreurs, 'image'); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-principal">
                            <i class="fas fa-save"></i> Créer le projet
                        </button>
                        <a href="liste.php" class="btn-secondaire">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
