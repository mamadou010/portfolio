<?php
/* ============================================================
   ADMIN — admin/messages/liste.php
   Liste tous les messages de contact, du plus récent au plus ancien.
   Les messages non lus sont visuellement distingués.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

/* Récupérer tous les messages triés par date décroissante */
$messages = $pdo->query(
    'SELECT id, nom, email, message, lu, date_envoi
     FROM messages_contact
     ORDER BY date_envoi DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Administration</title>
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
                <h1><i class="fas fa-envelope"></i> Messages de contact</h1>
                <span class="badge-compteur">
                    <?= count(array_filter($messages, fn($m) => !$m['lu'])) ?> non lu(s)
                </span>
            </div>

            <div class="admin-section">
                <?php if (!empty($messages)) : ?>
                    <div class="tableau-wrapper">
                        <table class="admin-tableau">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Aperçu du message</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg) : ?>
                                    <tr class="<?= $msg['lu'] ? '' : 'ligne-non-lue' ?>">
                                        <td><?= nettoyer($msg['nom']) ?></td>
                                        <td><?= nettoyer($msg['email']) ?></td>
                                        <!-- Aperçu tronqué à 60 caractères -->
                                        <td><?= nettoyer(mb_substr($msg['message'], 0, 60)) ?>…</td>
                                        <td><?= nettoyer($msg['date_envoi']) ?></td>
                                        <td>
                                            <?php if ($msg['lu']) : ?>
                                                <span class="badge-lu">Lu</span>
                                            <?php else : ?>
                                                <span class="badge-non-lu">Non lu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="voir.php?id=<?= (int)$msg['id'] ?>"
                                               class="btn-action btn-voir" title="Lire le message">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="aucun-resultat">Aucun message reçu pour l'instant.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="../../js/script.js"></script>
</body>
</html>
