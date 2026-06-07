<?php
/* ============================================================
   ESPACE ADMIN — admin/dashboard.php
   Tableau de bord principal après connexion.
   Affiche :
     - Statistiques globales (projets, messages non lus, demandes non lues)
     - 5 dernières visites
     - 5 dernières demandes de projet
   ============================================================ */

session_start();
require_once '../fonctions.php';
require_once '../config/connexion.php';

/* Vérifier la session admin — redirige si non connecté */
verifier_session_admin();

/* ---- Statistiques ----------------------------------------- */

/* Nombre total de projets */
$nb_projets = $pdo->query('SELECT COUNT(*) FROM projets')->fetchColumn();

/* Nombre de messages non lus (lu = 0) */
$nb_messages_non_lus = $pdo->query(
    'SELECT COUNT(*) FROM messages_contact WHERE lu = 0'
)->fetchColumn();

/* Nombre de demandes non lues */
$nb_demandes_non_lues = $pdo->query(
    'SELECT COUNT(*) FROM demandes_projet WHERE lu = 0'
)->fetchColumn();

/* 5 dernières visites (les plus récentes en premier) */
$dernieres_visites = $pdo->query(
    'SELECT adresse_ip, page, date_visite FROM visites ORDER BY date_visite DESC LIMIT 5'
)->fetchAll();

/* 5 dernières demandes de projet */
$dernieres_demandes = $pdo->query(
    'SELECT nom, email, type_projet, date_demande, lu FROM demandes_projet ORDER BY date_demande DESC LIMIT 5'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administration Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

    <?php require 'nav-admin.php'; ?>

    <main class="admin-main">
        <div class="admin-conteneur">

            <!-- En-tête du dashboard -->
            <div class="admin-entete">
                <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
                <p>Bienvenue, <strong><?= nettoyer($_SESSION['admin_prenom']) ?></strong> !</p>
            </div>

            <!-- ===== STATISTIQUES ===== -->
            <div class="stats-grille">
                <div class="stat-carte stat-bleu">
                    <i class="fas fa-folder-open"></i>
                    <div>
                        <span class="stat-nombre"><?= $nb_projets ?></span>
                        <span class="stat-label">Projets publiés</span>
                    </div>
                </div>
                <div class="stat-carte stat-orange">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <span class="stat-nombre"><?= $nb_messages_non_lus ?></span>
                        <span class="stat-label">Messages non lus</span>
                    </div>
                </div>
                <div class="stat-carte stat-violet">
                    <i class="fas fa-rocket"></i>
                    <div>
                        <span class="stat-nombre"><?= $nb_demandes_non_lues ?></span>
                        <span class="stat-label">Demandes non lues</span>
                    </div>
                </div>
            </div>

            <!-- ===== DERNIÈRES VISITES ===== -->
            <div class="admin-section">
                <h2><i class="fas fa-eye"></i> 5 dernières visites</h2>
                <?php if (!empty($dernieres_visites)) : ?>
                    <div class="tableau-wrapper">
                        <table class="admin-tableau">
                            <thead>
                                <tr>
                                    <th>Adresse IP</th>
                                    <th>Page visitée</th>
                                    <th>Date et heure</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dernieres_visites as $visite) : ?>
                                    <tr>
                                        <td><code><?= nettoyer($visite['adresse_ip']) ?></code></td>
                                        <td><?= nettoyer($visite['page']) ?></td>
                                        <td><?= nettoyer($visite['date_visite']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="aucun-resultat">Aucune visite enregistrée pour l'instant.</p>
                <?php endif; ?>
            </div>

            <!-- ===== DERNIÈRES DEMANDES ===== -->
            <div class="admin-section">
                <h2><i class="fas fa-project-diagram"></i> 5 dernières demandes de projet</h2>
                <?php if (!empty($dernieres_demandes)) : ?>
                    <div class="tableau-wrapper">
                        <table class="admin-tableau">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Type de projet</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dernieres_demandes as $demande) : ?>
                                    <tr class="<?= $demande['lu'] ? '' : 'ligne-non-lue' ?>">
                                        <td><?= nettoyer($demande['nom']) ?></td>
                                        <td><?= nettoyer($demande['email']) ?></td>
                                        <td><?= nettoyer($demande['type_projet']) ?></td>
                                        <td><?= nettoyer($demande['date_demande']) ?></td>
                                        <td>
                                            <?php if ($demande['lu']) : ?>
                                                <span class="badge-lu">Lu</span>
                                            <?php else : ?>
                                                <span class="badge-non-lu">Non lu</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="demandes/liste.php" class="btn-secondaire btn-petit">
                        Voir toutes les demandes <i class="fas fa-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <p class="aucun-resultat">Aucune demande reçue pour l'instant.</p>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <script src="../js/script.js"></script>
</body>
</html>
