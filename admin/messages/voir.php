<?php
/* ============================================================
   ADMIN — admin/messages/voir.php
   Affiche le détail d'un message et le marque comme lu.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

$req = $pdo->prepare('SELECT * FROM messages_contact WHERE id = :id LIMIT 1');
$req->execute([':id' => $id]);
$msg = $req->fetch();
if (!$msg) { header('Location: liste.php'); exit; }

/* Marquer comme lu si ce n'est pas déjà le cas */
if (!$msg['lu']) {
    $pdo->prepare('UPDATE messages_contact SET lu = 1 WHERE id = :id')
        ->execute([':id' => $id]);
}

/* Construire les liens de réponse */
$email_encode  = urlencode($msg['email']);
$sujet_encode  = urlencode('Re: Votre message sur mon portfolio');

/* Lien Gmail web (fonctionne sans client email installé) */
$lien_gmail = 'https://mail.google.com/mail/?view=cm&to=' . $email_encode . '&su=' . $sujet_encode;

/* Lien mailto classique (pour ceux qui ont Outlook/Thunderbird) */
$lien_mailto = 'mailto:' . nettoyer($msg['email']) . '?subject=Re%3A%20Votre%20message%20sur%20mon%20portfolio';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de <?= nettoyer($msg['nom']) ?> - Administration</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Carte email visible pour copier l'adresse */
        .email-visible {
            background  : rgba(203,166,247,0.08);
            border      : 1px solid rgba(203,166,247,0.3);
            border-radius: 10px;
            padding     : 1rem 1.25rem;
            margin      : 1rem 0;
            display     : flex;
            align-items : center;
            gap         : .75rem;
            flex-wrap   : wrap;
        }
        .email-visible .email-adresse {
            font-size   : 1.05rem;
            font-weight : 700;
            color       : #cba6f7;
            font-family : 'Courier New', monospace;
            letter-spacing: .5px;
            flex        : 1;
        }
        .btn-copier {
            background  : rgba(203,166,247,0.15);
            color       : #cba6f7;
            border      : 1px solid rgba(203,166,247,0.3);
            border-radius: 8px;
            padding     : .4rem .9rem;
            cursor      : pointer;
            font-size   : .85rem;
            font-family : 'Nunito', sans-serif;
            display     : flex;
            align-items : center;
            gap         : .4rem;
            transition  : background .2s;
        }
        .btn-copier:hover { background: rgba(203,166,247,0.3); }
        .btn-copier.copie { background: rgba(166,227,161,0.2); color: #a6e3a1; border-color: rgba(166,227,161,0.4); }

        /* Groupe de boutons de réponse */
        .reponse-options {
            display     : flex;
            gap         : 1rem;
            flex-wrap   : wrap;
            margin-top  : 1.5rem;
            align-items : center;
        }
        .btn-gmail {
            display     : inline-flex;
            align-items : center;
            gap         : .5rem;
            background  : #EA4335;
            color       : #fff;
            padding     : .65rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight : 700;
            font-size   : .95rem;
            font-family : 'Nunito', sans-serif;
            transition  : opacity .2s;
        }
        .btn-gmail:hover { opacity: .85; color: #fff; }
        .btn-mailto {
            display     : inline-flex;
            align-items : center;
            gap         : .5rem;
            background  : rgba(137,220,235,0.12);
            color       : #89dceb;
            border      : 1px solid rgba(137,220,235,0.3);
            padding     : .65rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight : 600;
            font-size   : .95rem;
            font-family : 'Nunito', sans-serif;
            transition  : background .2s;
        }
        .btn-mailto:hover { background: rgba(137,220,235,0.25); color: #89dceb; }
        .info-mailto {
            font-size   : .8rem;
            color       : #7c7fa8;
            font-style  : italic;
        }
    </style>
</head>
<body class="admin-body">
    <?php require '../nav-admin.php'; ?>
    <main class="admin-main">
        <div class="admin-conteneur">
            <div class="admin-entete">
                <h1><i class="fas fa-envelope-open-text"></i> Détail du message</h1>
                <a href="liste.php" class="btn-secondaire">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="admin-section carte-message">

                <!-- ===== INFOS DE L'EXPÉDITEUR ===== -->
                <div class="message-meta">
                    <p><strong><i class="fas fa-user"></i> De :</strong>
                        <?= nettoyer($msg['nom']) ?>
                    </p>
                    <p><strong><i class="fas fa-calendar-alt"></i> Reçu le :</strong>
                        <?= nettoyer($msg['date_envoi']) ?>
                    </p>
                </div>

                <!-- ===== EMAIL VISIBLE ET COPIABLE ===== -->
                <p style="margin:.75rem 0 .25rem;font-weight:600;color:#7c7fa8;font-size:.9rem;">
                    <i class="fas fa-envelope"></i> Adresse email du contact :
                </p>
                <div class="email-visible">
                    <span class="email-adresse" id="emailAdresse">
                        <?= nettoyer($msg['email']) ?>
                    </span>
                    <button class="btn-copier" id="btnCopier" onclick="copierEmail()">
                        <i class="fas fa-copy"></i> Copier
                    </button>
                </div>

                <hr class="separateur">

                <!-- ===== CONTENU DU MESSAGE ===== -->
                <div class="message-corps">
                    <h3>Message :</h3>
                    <p><?= nl2br(nettoyer($msg['message'])) ?></p>
                </div>

                <!-- ===== BOUTONS DE RÉPONSE ===== -->
                <div class="reponse-options">

                    <!-- Option 1 : Gmail dans le navigateur (recommandé) -->
                    <a href="<?= $lien_gmail ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn-gmail">
                        <i class="fab fa-google"></i> Répondre via Gmail
                    </a>

                    <!-- Option 2 : mailto (Outlook / client email installé) -->
                    <a href="<?= $lien_mailto ?>"
                       class="btn-mailto">
                        <i class="fas fa-envelope"></i> Ouvrir dans Outlook
                    </a>

                    <a href="liste.php" class="btn-secondaire">
                        Retour à la liste
                    </a>

                </div>

                <p class="info-mailto" style="margin-top:.75rem;">
                    <i class="fas fa-info-circle"></i>
                    "Répondre via Gmail" ouvre Gmail dans un nouvel onglet.
                    "Ouvrir dans Outlook" nécessite un client email installé sur le PC.
                </p>

            </div>
        </div>
    </main>

    <script>
    /* Copie l'adresse email dans le presse-papier */
    function copierEmail() {
        const email = document.getElementById('emailAdresse').textContent.trim();
        const btn   = document.getElementById('btnCopier');

        navigator.clipboard.writeText(email).then(() => {
            /* Feedback visuel temporaire */
            btn.classList.add('copie');
            btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
            setTimeout(() => {
                btn.classList.remove('copie');
                btn.innerHTML = '<i class="fas fa-copy"></i> Copier';
            }, 2000);
        }).catch(() => {
            /* Fallback si clipboard API non disponible */
            alert('Email : ' + email);
        });
    }
    </script>

    <script src="../../js/script.js"></script>
</body>
</html>
