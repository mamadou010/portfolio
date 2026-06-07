<?php
/* ============================================================
   ADMIN — admin/projets/modifier.php
   Formulaire de modification d'un projet existant.
   Si une nouvelle image est uploadée → remplace l'ancienne.
   Si aucune image → l'ancienne est conservée.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

/* ---- Récupérer l'id depuis GET ou POST ---- */
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: liste.php');
    exit;
}

/* ---- Charger le projet depuis la BDD ---- */
$req = $pdo->prepare('SELECT * FROM projets WHERE id = :id LIMIT 1');
$req->execute([':id' => $id]);
$projet = $req->fetch();

if (!$projet) {
    header('Location: liste.php');
    exit;
}

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf('modifier_projet');

    $titre        = trim($_POST['titre'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? '');
    $lien         = trim($_POST['lien'] ?? '');

    /* Validation */
    if (!champ_requis($titre))        { $erreurs['titre']        = 'Le titre est obligatoire.'; }
    if (!champ_requis($description))  { $erreurs['description']  = 'La description est obligatoire.'; }
    if (!champ_requis($technologies)) { $erreurs['technologies'] = 'Les technologies sont obligatoires.'; }

    /* Upload image (optionnel — si vide, on conserve l'ancienne) */
    $chemin_image = $projet['image']; /* Conserver par défaut */
    if (!empty($_FILES['image']['name'])) {
        $dossier = '../../images/projets/';
        if (!is_dir($dossier)) { mkdir($dossier, 0755, true); }
        $nouveau_chemin = traiter_upload_image($_FILES['image'], $dossier);
        if ($nouveau_chemin === null) {
            $erreurs['image'] = 'Format non autorisé. Utilisez jpg, jpeg, png, webp ou gif.';
        } else {
            /* Supprimer l'ancienne image si elle existe et n'est pas dans /images/ */
            if (!empty($projet['image']) && strpos($projet['image'], 'images/projets/') !== false) {
                $ancien_chemin = '../../' . $projet['image'];
                if (file_exists($ancien_chemin)) { unlink($ancien_chemin); }
            }
            $chemin_image = 'images/projets/' . basename($nouveau_chemin);
        }
    }

    if (empty($erreurs)) {
        $req = $pdo->prepare(
            'UPDATE projets SET titre=:titre, description=:description,
             technologies=:technologies, image=:image, lien=:lien
             WHERE id=:id'
        );
        $req->execute([
            ':titre'        => $titre,
            ':description'  => $description,
            ':technologies' => $technologies,
            ':image'        => $chemin_image,
            ':lien'         => $lien ?: null,
            ':id'           => $id,
        ]);
        header('Location: liste.php?msg=modifie');
        exit;
    }

    /* Mettre à jour les valeurs affichées si erreur */
    $projet['titre']        = $titre;
    $projet['description']  = $description;
    $projet['technologies'] = $technologies;
    $projet['lien']         = $lien;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier projet - Administration</title>
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
                <h1><i class="fas fa-edit"></i> Modifier le projet</h1>
                <a href="liste.php" class="btn-secondaire"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
            <div class="admin-section">
                <form method="POST" action="modifier.php" enctype="multipart/form-data" novalidate>
                    <?php champ_csrf('modifier_projet'); ?>
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="champ-groupe">
                        <label for="titre">Titre <span class="requis">*</span></label>
                        <input type="text" id="titre" name="titre"
                               value="<?= nettoyer($projet['titre']) ?>">
                        <?php afficher_erreur($erreurs, 'titre'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="description">Description <span class="requis">*</span></label>
                        <textarea id="description" name="description" rows="5"><?= nettoyer($projet['description']) ?></textarea>
                        <?php afficher_erreur($erreurs, 'description'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="technologies">Technologies <span class="requis">*</span></label>
                        <input type="text" id="technologies" name="technologies"
                               value="<?= nettoyer($projet['technologies']) ?>">
                        <?php afficher_erreur($erreurs, 'technologies'); ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="lien">Lien externe <span class="optionnel">(optionnel)</span></label>
                        <input type="url" id="lien" name="lien"
                               value="<?= nettoyer($projet['lien'] ?? '') ?>">
                    </div>

                    <div class="champ-groupe">
                        <label>Image actuelle</label>
                        <?php if (!empty($projet['image'])) : ?>
                            <img src="../../<?= nettoyer($projet['image']) ?>"
                                 alt="Image actuelle"
                                 class="apercu-image">
                        <?php else : ?>
                            <p class="texte-gris">Aucune image.</p>
                        <?php endif; ?>
                    </div>

                    <div class="champ-groupe">
                        <label for="image">Nouvelle image <span class="optionnel">(laisser vide pour conserver l'actuelle)</span></label>
                        <input type="file" id="image" name="image"
                               accept=".jpg,.jpeg,.png,.webp,.gif">
                        <?php afficher_erreur($erreurs, 'image'); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-principal">
                            <i class="fas fa-save"></i> Enregistrer les modifications
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
