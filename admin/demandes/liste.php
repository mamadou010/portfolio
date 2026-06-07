<?php
/* ============================================================
   ADMIN — admin/demandes/liste.php
   Liste toutes les demandes de projet, du plus récent au plus ancien.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$demandes = $pdo->query(
    'SELECT id, nom, email, type_projet, budget, lu, date_demande
     FROM demandes_projet
     ORDER BY date_demande DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de projet - Administration</title>
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
                <h1><i class="fas fa-rocket"></i> Demandes de projet</h1>
                <span class="badge-compteur">
                    <?= count(array_filter($demandes, fn($d) => !$d['lu'])) ?> non lue(s)
                </span>
            </div>

            <div class="admin-section">
                <?php if (!empty($demandes)) : ?>
                    <div class="tableau-wrapper">
                        <table class="admin-tableau">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Type de projet</th>
                                    <th>Budget</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($demandes as $dem) : ?>
                                    <tr class="<?= $dem['lu'] ? '' : 'ligne-non-lue' ?>">
                                        <td><?= nettoyer($dem['nom']) ?></td>
                                        <td><?= nettoyer($dem['email']) ?></td>
                                        <td><?= nettoyer($dem['type_projet']) ?></td>
                                        <td><?= nettoyer($dem['budget'] ?? '—') ?></td>
                                        <td><?= nettoyer($dem['date_demande']) ?></td>
                                        <td>
                                            <?php if ($dem['lu']) : ?>
                                                <span class="badge-lu">Lu</span>
                                            <?php else : ?>
                                                <span class="badge-non-lu">Non lu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="voir.php?id=<?= (int)$dem['id'] ?>"
                                               class="btn-action btn-voir" title="Voir la demande">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="aucun-resultat">Aucune demande de projet reçue pour l'instant.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
