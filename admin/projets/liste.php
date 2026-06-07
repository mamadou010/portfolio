<?php
/* ============================================================
   ADMIN — admin/projets/liste.php
   Liste tous les projets triés par date décroissante.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

/* ---- Message de confirmation (après ajout/modification/suppression) */
$message_succes = $_GET['msg'] ?? '';

/* ---- Récupérer tous les projets ---- */
$projets = $pdo->query(
    'SELECT id, titre, technologies, image, date_creation FROM projets ORDER BY date_creation DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Administration</title>
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
                <h1><i class="fas fa-folder-open"></i> Gestion des projets</h1>
                <a href="creer.php" class="btn-principal">
                    <i class="fas fa-plus"></i> Nouveau projet
                </a>
            </div>

            <?php if ($message_succes === 'ajoute') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Projet ajouté avec succès.</div>
            <?php elseif ($message_succes === 'modifie') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Projet modifié avec succès.</div>
            <?php elseif ($message_succes === 'supprime') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Projet supprimé avec succès.</div>
            <?php endif; ?>

            <div class="admin-section">
                <?php if (!empty($projets)) : ?>
                    <div class="tableau-wrapper">
                        <table class="admin-tableau">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Technologies</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projets as $projet) : ?>
                                    <tr>
                                        <td><?= (int)$projet['id'] ?></td>
                                        <td><?= nettoyer($projet['titre']) ?></td>
                                        <td><?= nettoyer($projet['technologies']) ?></td>
                                        <td><?= nettoyer($projet['date_creation']) ?></td>
                                        <td class="actions-cellule">
                                            <!-- Lien modifier -->
                                            <a href="modifier.php?id=<?= (int)$projet['id'] ?>"
                                               class="btn-action btn-modifier"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Suppression via POST (jamais GET) -->
                                            <form method="POST"
                                                  action="supprimer.php"
                                                  class="form-suppr"
                                                  onsubmit="return confirm('Supprimer ce projet ? Cette action est irréversible.');">
                                                <?php champ_csrf('suppr_projet'); ?>
                                                <input type="hidden" name="id" value="<?= (int)$projet['id'] ?>">
                                                <button type="submit" class="btn-action btn-supprimer" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="aucun-resultat">Aucun projet pour l'instant. <a href="creer.php">Créer le premier projet</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
