<?php
/* ============================================================
   ADMIN — admin/projets/supprimer.php
   Suppression d'un projet via POST avec jeton CSRF.
   Un lien GET ne peut JAMAIS déclencher une suppression.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

/* Refuser toute requête qui n'est pas POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: liste.php');
    exit;
}

/* Vérifier le jeton CSRF */
verifier_csrf('suppr_projet');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: liste.php');
    exit;
}

/* Récupérer le chemin de l'image pour la supprimer du serveur */
$req = $pdo->prepare('SELECT image FROM projets WHERE id = :id LIMIT 1');
$req->execute([':id' => $id]);
$projet = $req->fetch();

if ($projet) {
    /* Supprimer l'image du serveur si elle est dans le dossier projets/ */
    if (!empty($projet['image']) && strpos($projet['image'], 'images/projets/') !== false) {
        $chemin = '../../' . $projet['image'];
        if (file_exists($chemin)) { unlink($chemin); }
    }
    /* Supprimer l'enregistrement en base */
    $req = $pdo->prepare('DELETE FROM projets WHERE id = :id');
    $req->execute([':id' => $id]);
}

header('Location: liste.php?msg=supprime');
exit;
