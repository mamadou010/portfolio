<?php
/* ============================================================
   config/connexion.php
   Connexion PDO à la base de données MySQL.

   Utilisation dans n'importe quelle page :
       require_once 'config/connexion.php';
   Puis utilisation de la variable $pdo pour les requêtes.
   ============================================================ */

define('DB_HOST',    'localhost');   // Hôte du serveur MySQL
define('DB_NOM',     'portfolio');   // Nom de la base de données
define('DB_UTIL',    'root');        // Nom d'utilisateur MySQL
define('DB_MDP',     '');            // Mot de passe MySQL (vide en local)
define('DB_CHARSET', 'utf8mb4');     // Encodage UTF-8 complet (emoji inclus)

/* ---- DSN (Data Source Name) ---------------------------------
   Chaîne de connexion PDO : pilote:hôte;dbname;charset
   ----------------------------------------------------------- */
$dsn = 'mysql:host=' . DB_HOST
     . ';dbname='    . DB_NOM
     . ';charset='   . DB_CHARSET;

/* ---- Options PDO -------------------------------------------
   - ERRMODE_EXCEPTION  : lance une exception PDOException en cas d'erreur
   - DEFAULT_FETCH_MODE : retourne les lignes sous forme de tableaux associatifs
   - EMULATE_PREPARES   : false → requêtes réellement préparées côté MySQL
                          (plus sûr contre les injections SQL)
   ----------------------------------------------------------- */
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

/* ---- Connexion ----------------------------------------------
   On tente la connexion PDO dans un try/catch.
   En cas d'échec, on n'affiche JAMAIS l'erreur réelle au visiteur
   (elle pourrait révéler des informations sensibles).
   L'erreur est écrite dans les logs du serveur avec error_log().
   ----------------------------------------------------------- */
try {
    $pdo = new PDO($dsn, DB_UTIL, DB_MDP, $options);
} catch (PDOException $e) {
    /* Écrire le message d'erreur dans les logs serveur (invisible au visiteur) */
    error_log('[PORTFOLIO] Erreur PDO : ' . $e->getMessage());

    /* Afficher un message générique et arrêter le script */
    http_response_code(503);
    die('Le service est temporairement indisponible. Veuillez réessayer plus tard.');
}
