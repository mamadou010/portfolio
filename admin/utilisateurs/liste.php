<?php
/* ============================================================
   ADMIN — admin/utilisateurs/liste.php
   Liste tous les administrateurs.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$message_succes = $_GET['msg'] ?? '';

$admins = $pdo->query(
    'SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY date_creation DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateurs - Administration</title>
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
                <h1><i class="fas fa-users-cog"></i> Gestion des administrateurs</h1>
                <a href="creer.php" class="btn-principal"><i class="fas fa-plus"></i> Nouvel admin</a>
            </div>

            <?php if ($message_succes === 'ajoute') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Administrateur créé avec succès.</div>
            <?php elseif ($message_succes === 'modifie') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Administrateur modifié avec succès.</div>
            <?php elseif ($message_succes === 'supprime') : ?>
                <div class="alerte alerte-succes"><i class="fas fa-check-circle"></i> Administrateur supprimé avec succès.</div>
            <?php elseif ($message_succes === 'interdit') : ?>
                <div class="alerte alerte-erreur"><i class="fas fa-ban"></i> Vous ne pouvez pas supprimer votre propre compte.</div>
            <?php endif; ?>

            <div class="admin-section">
                <div class="tableau-wrapper">
                    <table class="admin-tableau">
                        <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin) : ?>
                                <tr>
                                    <td><?= nettoyer($admin['prenom']) ?></td>
                                    <td><?= nettoyer($admin['nom']) ?></td>
                                    <td><?= nettoyer($admin['email']) ?></td>
                                    <td><?= nettoyer($admin['date_creation']) ?></td>
                                    <td class="actions-cellule">
                                        <a href="modifier.php?id=<?= (int)$admin['id'] ?>"
                                           class="btn-action btn-modifier" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($admin['id'] !== $_SESSION['admin_id']) : ?>
                                            <form method="POST" action="supprimer.php" class="form-suppr"
                                                  onsubmit="return confirm('Supprimer cet administrateur ?');">
                                                <?php champ_csrf('suppr_admin'); ?>
                                                <input type="hidden" name="id" value="<?= (int)$admin['id'] ?>">
                                                <button type="submit" class="btn-action btn-supprimer" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <span class="badge-vous" title="Votre compte">Vous</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
