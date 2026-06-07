<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — fonctions.php
   Fonctions utilitaires réutilisables dans tout le projet.
   Partie 3 : ajout des fonctions CSRF, visites et PDO.
   ============================================================ */

/* ============================
   1. FONCTIONS DE VALIDATION 
   ============================ */

function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur), ENT_QUOTES, 'UTF-8');
}

function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function valeur_post(string $champ): string {
    return nettoyer($_POST[$champ] ?? '');
}

function valeur_get(string $champ): string {
    return nettoyer($_GET[$champ] ?? '');
}

function afficher_erreur(array $erreurs, string $champ): void {
    if (!empty($erreurs[$champ])) {
        echo '<span class="erreur-champ">'
           . '<i class="fas fa-exclamation-circle"></i> '
           . $erreurs[$champ]
           . '</span>';
    }
}

/* ============================================================
   2. FONCTIONS CSRF
   ============================================================ */

function generer_jeton_csrf(string $cle = 'csrf'): string {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_SESSION['csrf_' . $cle])) {
        $_SESSION['csrf_' . $cle] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_' . $cle];
}

function champ_csrf(string $cle = 'csrf'): void {
    $jeton = generer_jeton_csrf($cle);
    echo '<input type="hidden" name="csrf_token" value="' . $jeton . '">';
}

function verifier_csrf(string $cle = 'csrf'): void {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $jeton_session    = $_SESSION['csrf_' . $cle] ?? '';
    $jeton_formulaire = $_POST['csrf_token'] ?? '';
    if (!hash_equals($jeton_session, $jeton_formulaire)) {
        http_response_code(403);
        die('Erreur de sécurité : jeton CSRF invalide. Veuillez recharger la page et réessayer.');
    }
    unset($_SESSION['csrf_' . $cle]);
}

/* ============================================================
   3. JOURNALISATION DES VISITES
   ============================================================ */

function enregistrer_visite(PDO $pdo, string $page): void {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $adresse_ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } else {
        $adresse_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    if (!filter_var($adresse_ip, FILTER_VALIDATE_IP)) {
        $adresse_ip = '0.0.0.0';
    }
    try {
        $req = $pdo->prepare('INSERT INTO visites (adresse_ip, page) VALUES (:ip, :page)');
        $req->execute([':ip' => $adresse_ip, ':page' => $page]);
    } catch (PDOException $e) {
        error_log('[PORTFOLIO] Erreur enregistrement visite : ' . $e->getMessage());
    }
}

/* ============================================================
   4. PROTECTION ESPACE ADMIN
   ============================================================ */

function verifier_session_admin(): void {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_SESSION['admin_id'])) {
        header('Location: /portfolio/admin/connexion.php');
        exit;
    }
}

/* ============================================================
   5. FONCTIONS PROJETS (BDD)
   ============================================================ */

function get_projets_bdd(PDO $pdo): array {
    $req = $pdo->query('SELECT * FROM projets ORDER BY date_creation DESC');
    return $req->fetchAll();
}

function filtrer_projets_bdd(PDO $pdo, string $mot_cle): array {
    if (trim($mot_cle) === '') { return get_projets_bdd($pdo); }
    $like = '%' . $mot_cle . '%';
    $req = $pdo->prepare(
        'SELECT * FROM projets
         WHERE titre LIKE :like1 OR description LIKE :like2 OR technologies LIKE :like3
         ORDER BY date_creation DESC'
    );
    $req->execute([':like1' => $like, ':like2' => $like, ':like3' => $like]);
    return $req->fetchAll();
}

/* ============================================================
   6. UPLOAD IMAGE
   ============================================================ */

function traiter_upload_image(array $fichier, string $dossier_dest = 'images/projets/'): ?string {
    if ($fichier['error'] !== UPLOAD_ERR_OK) { return null; }
    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $extensions_autorisees, true)) { return null; }
    $nom_fichier = uniqid('projet_', true) . '.' . $extension;
    $chemin_dest = $dossier_dest . $nom_fichier;
    if (!move_uploaded_file($fichier['tmp_name'], $chemin_dest)) { return null; }
    return $chemin_dest;
}

/* ============================================================
   7. FONCTIONS PARTIE 2 (fallback / compatibilité)
   ============================================================ */

function get_projets(): array {
    return [
        ['titre'=>'Portfolio HTML/CSS','description'=>"Réalisation d'un site portfolio en HTML et CSS durant ma Licence 1. Ce projet m'a permis de maîtriser les bases du développement web, la structuration des pages et le design responsive.",'technologies'=>['HTML','CSS','Responsive'],'image'=>'images/Portfolio HTML CSS L1.png','lien'=>'','mots_cles'=>'html css portfolio licence responsive design'],
        ['titre'=>'Application de gestion de contacts','description'=>"Développement d'une application en C et MySQL permettant de gérer un répertoire téléphonique. Fonctionnalités : ajout, modification, suppression et recherche de contacts.",'technologies'=>['C','MySQL','CRUD'],'image'=>'images/Projet en C & MySQL.png','lien'=>'','mots_cles'=>'c mysql contacts base données répertoire crud langage'],
        ['titre'=>'Poubelle intelligente','description'=>"Réalisation en groupe d'une poubelle capable de s'ouvrir automatiquement grâce à un capteur de proximité.",'technologies'=>['Embarqué','Microcontrôleur','C'],'image'=>'images/Poubelle intelligente.png','lien'=>'','mots_cles'=>'embarqué microcontrôleur poubelle intelligente capteur proximité'],
        ['titre'=>'Mesure température & humidité','description'=>"Système de mesure de température et d'humidité avec capteurs et microcontrôleur.",'technologies'=>['Embarqué','Capteurs','Microcontrôleur'],'image'=>'images/Mesure température & humidité.png','lien'=>'','mots_cles'=>'embarqué capteur température humidité système microcontrôleur temps réel'],
        ['titre'=>'Projet entrepreneurial — CARA','description'=>"Conception en groupe d'un Centre d'Accompagnement et de Réussite Académique.",'technologies'=>['Gestion de projet','Travail en équipe'],'image'=>'images/CARA-Centre-Accompagnement-Reussite-Academique.png','lien'=>'','mots_cles'=>'entrepreneuriat gestion projet équipe centre académique cara'],
        ['titre'=>"Sites d'affiliation",'description'=>"Création et gestion de deux sites web d'affiliation avec contenu SEO.",'technologies'=>['WordPress','SEO','Affiliation'],'image'=>'images/Meilleurs-mixeurs & Blenders.png','lien'=>'https://meilleur-mixeur.com','mots_cles'=>'affiliation wordpress seo marketing site web contenu digital'],
        ['titre'=>'DNSSEC — Sécurité DNS','description'=>"Étude et mise en place du protocole DNSSEC pour sécuriser les résolutions DNS.",'technologies'=>['Réseau','DNS','Sécurité','Administration réseau'],'image'=>'images/dnssec.png','lien'=>'','mots_cles'=>'dnssec dns sécurité réseau cryptographie zone protocole clé'],
    ];
}

function filtrer_projets(array $projets, string $mot_cle): array {
    if (trim($mot_cle) === '') { return $projets; }
    $resultats = [];
    foreach ($projets as $projet) {
        $texte = strtolower($projet['titre'] . ' ' . $projet['description'] . ' ' . $projet['mots_cles']);
        if (stripos($texte, $mot_cle) !== false) { $resultats[] = $projet; }
    }
    return $resultats;
}
