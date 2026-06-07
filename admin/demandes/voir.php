<?php
/* ============================================================
   ADMIN — admin/demandes/voir.php
   Affiche le détail d'une demande de projet et la marque comme lue.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: /portfolio/admin/demandes/liste.php'); exit; }

$req = $pdo->prepare('SELECT * FROM demandes_projet WHERE id = :id LIMIT 1');
$req->execute([':id' => $id]);
$dem = $req->fetch();
if (!$dem) { header('Location: /portfolio/admin/demandes/liste.php'); exit; }

/* Marquer comme lue */
if (!$dem['lu']) {
    $pdo->prepare('UPDATE demandes_projet SET lu = 1 WHERE id = :id')
        ->execute([':id' => $id]);
}

/* Construire les liens de réponse */
$email_encode = urlencode($dem['email']);
$sujet_encode = urlencode('Suite à votre demande de projet — ' . $dem['type_projet']);
$lien_gmail   = 'https://mail.google.com/mail/?view=cm&to=' . $email_encode . '&su=' . $sujet_encode;
$lien_mailto  = 'mailto:' . nettoyer($dem['email']) . '?subject=Suite%20%C3%A0%20votre%20demande%20de%20projet';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de <?= nettoyer($dem['nom']) ?> - Administration</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .email-visible {
            background   : rgba(203,166,247,0.08);
            border       : 1px solid rgba(203,166,247,0.3);
            border-radius: 10px;
            padding      : 1rem 1.25rem;
            margin       : 1rem 0;
            display      : flex;
            align-items  : center;
            gap          : .75rem;
            flex-wrap    : wrap;
        }
        .email-adresse {
            font-size    : 1.05rem;
            font-weight  : 700;
            color        : #cba6f7;
            font-family  : 'Courier New', monospace;
            letter-spacing: .5px;
            flex         : 1;
        }
        .btn-copier {
            background   : rgba(203,166,247,0.15);
            color        : #cba6f7;
            border       : 1px solid rgba(203,166,247,0.3);
            border-radius: 8px;
            padding      : .4rem .9rem;
            cursor       : pointer;
            font-size    : .85rem;
            font-family  : 'Nunito', sans-serif;
            display      : flex;
            align-items  : center;
            gap          : .4rem;
            transition   : background .2s;
        }
        .btn-copier:hover { background: rgba(203,166,247,0.3); }
        .btn-copier.copie { background: rgba(166,227,161,0.2); color: #a6e3a1; border-color: rgba(166,227,161,0.4); }

        .reponse-options {
            display      : flex;
            gap          : 1rem;
            flex-wrap    : wrap;
            margin-top   : 1.5rem;
            align-items  : center;
        }
        .btn-gmail {
            display      : inline-flex;
            align-items  : center;
            gap          : .5rem;
            background   : #EA4335;
            color        : #fff;
            padding      : .65rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight  : 700;
            font-size    : .95rem;
            font-family  : 'Nunito', sans-serif;
            transition   : opacity .2s;
        }
        .btn-gmail:hover { opacity: .85; color: #fff; }
        .btn-mailto {
            display      : inline-flex;
            align-items  : center;
            gap          : .5rem;
            background   : rgba(137,220,235,0.12);
            color        : #89dceb;
            border       : 1px solid rgba(137,220,235,0.3);
            padding      : .65rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight  : 600;
            font-size    : .95rem;
            font-family  : 'Nunito', sans-serif;
            transition   : background .2s;
        }
        .btn-mailto:hover { background: rgba(137,220,235,0.25); color: #89dceb; }

        /* Grille de métadonnées de la demande */
        .demande-grille {
            display      : grid;
            grid-template-columns: 1fr 1fr;
            gap          : 1rem;
            margin-bottom: 1.25rem;
        }
        .demande-champ {
            background   : rgba(255,255,255,0.03);
            border       : 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding      : .85rem 1rem;
        }
        .demande-champ .champ-label {
            font-size    : .78rem;
            color        : #7c7fa8;
            font-weight  : 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .3rem;
            display      : flex;
            align-items  : center;
            gap          : .4rem;
        }
        .demande-champ .champ-valeur {
            font-size    : .97rem;
            color        : #cdd6f4;
            font-weight  : 600;
        }
        @media(max-width:640px) { .demande-grille { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="admin-body">
    <?php require '../nav-admin.php'; ?>
    <main class="admin-main">
        <div class="admin-conteneur">
            <div class="admin-entete">
                <h1><i class="fas fa-project-diagram"></i> Détail de la demande</h1>
                <a href="liste.php" class="btn-secondaire">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="admin-section carte-message">

                <!-- ===== GRILLE D'INFORMATIONS ===== -->
                <div class="demande-grille">
                    <div class="demande-champ">
                        <div class="champ-label"><i class="fas fa-user"></i> Client</div>
                        <div class="champ-valeur"><?= nettoyer($dem['nom']) ?></div>
                    </div>
                    <div class="demande-champ">
                        <div class="champ-label"><i class="fas fa-briefcase"></i> Type de projet</div>
                        <div class="champ-valeur"><?= nettoyer($dem['type_projet']) ?></div>
                    </div>
                    <div class="demande-champ">
                        <div class="champ-label"><i class="fas fa-coins"></i> Budget</div>
                        <div class="champ-valeur">
                            <?= !empty($dem['budget']) ? nettoyer($dem['budget']) : '<em style="color:#7c7fa8;font-weight:400;">Non renseigné</em>' ?>
                        </div>
                    </div>
                    <div class="demande-champ">
                        <div class="champ-label"><i class="fas fa-calendar-alt"></i> Date de réception</div>
                        <div class="champ-valeur"><?= nettoyer($dem['date_demande']) ?></div>
                    </div>
                </div>

                <!-- ===== EMAIL VISIBLE ET COPIABLE ===== -->
                <p style="margin:.75rem 0 .25rem;font-weight:600;color:#7c7fa8;font-size:.9rem;">
                    <i class="fas fa-envelope"></i> Adresse email du client :
                </p>
                <div class="email-visible">
                    <span class="email-adresse" id="emailAdresse">
                        <?= nettoyer($dem['email']) ?>
                    </span>
                    <button class="btn-copier" id="btnCopier" onclick="copierEmail()">
                        <i class="fas fa-copy"></i> Copier
                    </button>
                </div>

                <hr class="separateur">

                <!-- ===== DESCRIPTION DU PROJET ===== -->
                <div class="message-corps">
                    <h3>Description du projet :</h3>
                    <p><?= nl2br(nettoyer($dem['description'])) ?></p>
                </div>

                <!-- ===== BOUTONS DE RÉPONSE ===== -->
                <div class="reponse-options">

                    <!-- Gmail web (recommandé) -->
                    <a href="<?= $lien_gmail ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn-gmail">
                        <i class="fab fa-google"></i> Répondre via Gmail
                    </a>

                    <!-- mailto (Outlook) -->
                    <a href="<?= $lien_mailto ?>"
                       class="btn-mailto">
                        <i class="fas fa-envelope"></i> Ouvrir dans Outlook
                    </a>

                    <a href="liste.php" class="btn-secondaire">
                        Retour à la liste
                    </a>

                </div>

                <p style="margin-top:.75rem;font-size:.8rem;color:#7c7fa8;font-style:italic;">
                    <i class="fas fa-info-circle"></i>
                    "Répondre via Gmail" ouvre Gmail dans un nouvel onglet (aucun logiciel requis).
                    "Ouvrir dans Outlook" nécessite un client email installé sur le PC.
                </p>

            </div>
        </div>
    </main>

    <script>
    function copierEmail() {
        const email = document.getElementById('emailAdresse').textContent.trim();
        const btn   = document.getElementById('btnCopier');
        navigator.clipboard.writeText(email).then(() => {
            btn.classList.add('copie');
            btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
            setTimeout(() => {
                btn.classList.remove('copie');
                btn.innerHTML = '<i class="fas fa-copy"></i> Copier';
            }, 2000);
        }).catch(() => {
            alert('Email : ' + email);
        });
    }
    </script>

    <script src="../../js/script.js"></script>
</body>
</html>
