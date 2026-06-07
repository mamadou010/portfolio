<?php
/* ============================================================
   ADMIN — admin/utilisateurs/supprimer.php
   Suppression d'un administrateur via POST.
   Un admin ne peut PAS supprimer son propre compte.
   ============================================================ */

session_start();
require_once '../../fonctions.php';
require_once '../../config/connexion.php';
verifier_session_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: liste.php'); exit; }

verifier_csrf('suppr_admin');

$id = (int)($_POST['id'] ?? 0);

/* Vérification côté serveur : interdire l'auto-suppression */
if ($id === $_SESSION['admin_id']) {
    header('Location: liste.php?msg=interdit');
    exit;
}

$req = $pdo->prepare('DELETE FROM administrateurs WHERE id = :id');
$req->execute([':id' => $id]);

header('Location: liste.php?msg=supprime');
exit;
