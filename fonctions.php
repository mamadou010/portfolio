<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — fonctions.php
   Fonctions utilitaires réutilisables dans tout le projet.
   Ce fichier est inclus dans chaque page avec :
       require 'fonctions.php';
   ============================================================ */

/**
 * Vérifie qu'un champ n'est pas vide après suppression des espaces.
 *
 * @param string $valeur  La valeur à vérifier
 * @return bool           true si le champ contient du texte, false sinon
 */
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

/**
 * Nettoie une valeur pour l'afficher en toute sécurité dans du HTML.
 * Supprime les espaces inutiles et convertit les caractères spéciaux
 * en entités HTML pour éviter les attaques XSS.
 *
 * @param string $valeur  La valeur brute provenant du formulaire ou du tableau
 * @return string         La valeur nettoyée et sécurisée
 */
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur), ENT_QUOTES, 'UTF-8');
}

/**
 * Vérifie qu'une adresse e-mail a un format valide.
 *
 * @param string $email  L'adresse e-mail à vérifier
 * @return bool          true si le format est valide, false sinon
 */
function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Récupère et nettoie une valeur depuis $_POST.
 * Retourne une chaîne vide si le champ n'existe pas.
 * Utile pour pré-remplir les formulaires après une erreur.
 *
 * @param string $champ  Le nom du champ dans $_POST
 * @return string        La valeur nettoyée ou ''
 */
function valeur_post(string $champ): string {
    return nettoyer($_POST[$champ] ?? '');
}

/**
 * Récupère et nettoie une valeur depuis $_GET.
 * Retourne une chaîne vide si le paramètre n'existe pas.
 * Utile pour la barre de recherche des projets.
 *
 * @param string $champ  Le nom du paramètre dans $_GET
 * @return string        La valeur nettoyée ou ''
 */
function valeur_get(string $champ): string {
    return nettoyer($_GET[$champ] ?? '');
}

/**
 * Affiche un message d'erreur HTML pour un champ donné.
 * N'affiche rien si aucune erreur n'est enregistrée pour ce champ.
 *
 * @param array  $erreurs  Tableau associatif des erreurs (clé = nom du champ)
 * @param string $champ    Le nom du champ à vérifier
 * @return void
 */
function afficher_erreur(array $erreurs, string $champ): void {
    if (!empty($erreurs[$champ])) {
        echo '<span class="erreur-champ">'
           . '<i class="fas fa-exclamation-circle"></i> '
           . $erreurs[$champ]
           . '</span>';
    }
}

/**
 * Retourne le tableau complet de tous les projets du portfolio.
 * Les projets sont stockés ici en PHP (sans base de données pour l'instant).
 * Pour ajouter un projet : ajouter une entrée dans ce tableau.
 * La recherche s'effectue sur le champ 'mots_cles' ainsi que
 * sur le titre et la description.
 *
 * @return array  Tableau de projets avec leurs propriétés
 */
function get_projets(): array {
    return [
        [
            'titre'        => 'Portfolio HTML/CSS',
            'description'  => 'Réalisation d\'un site portfolio en HTML et CSS durant ma Licence 1. '
                            . 'Ce projet m\'a permis de maîtriser les bases du développement web, '
                            . 'la structuration des pages et le design responsive.',
            'technologies' => ['HTML', 'CSS', 'Responsive'],
            'image'        => 'images/Portfolio HTML CSS L1.png',
            'lien'         => '',
            'mots_cles'    => 'html css portfolio licence responsive design',
        ],
        [
            'titre'        => 'Application de gestion de contacts',
            'description'  => 'Développement d\'une application en C et MySQL permettant de gérer '
                            . 'un répertoire téléphonique. Fonctionnalités : ajout, modification, '
                            . 'suppression et recherche de contacts.',
            'technologies' => ['C', 'MySQL', 'CRUD'],
            'image'        => 'images/Projet en C & MySQL.png',
            'lien'         => '',
            'mots_cles'    => 'c mysql contacts base données répertoire crud langage',
        ],
        [
            'titre'        => 'Poubelle intelligente',
            'description'  => 'Réalisation en groupe d\'une poubelle capable de s\'ouvrir '
                            . 'automatiquement grâce à un capteur de proximité. Travail sur '
                            . 'l\'interaction matériel/logiciel et la programmation de microcontrôleurs.',
            'technologies' => ['Embarqué', 'Microcontrôleur', 'C'],
            'image'        => 'images/Poubelle intelligente.png',
            'lien'         => '',
            'mots_cles'    => 'embarqué microcontrôleur poubelle intelligente capteur proximité',
        ],
        [
            'titre'        => 'Mesure température & humidité',
            'description'  => 'Réalisation en groupe d\'un système de mesure de température '
                            . 'et d\'humidité à l\'aide de capteurs connectés à un microcontrôleur '
                            . 'pour afficher les données en temps réel.',
            'technologies' => ['Embarqué', 'Capteurs', 'Microcontrôleur'],
            'image'        => 'images/Mesure température & humidité.png',
            'lien'         => '',
            'mots_cles'    => 'embarqué capteur température humidité système microcontrôleur temps réel',
        ],
        [
            'titre'        => 'Projet entrepreneurial — CARA',
            'description'  => 'Conception en groupe d\'un Centre d\'Accompagnement et de Réussite '
                            . 'Académique. Ce projet m\'a permis de développer le travail en équipe '
                            . 'et la gestion de projet.',
            'technologies' => ['Gestion de projet', 'Travail en équipe'],
            'image'        => 'images/CARA-Centre-Accompagnement-Reussite-Academique.png',
            'lien'         => '',
            'mots_cles'    => 'entrepreneuriat gestion projet équipe centre académique cara',
        ],
        [
            'titre'        => 'Sites d\'affiliation',
            'description'  => 'Création et gestion de deux sites web d\'affiliation '
                            . '(meilleur-mixeur.com et mon-guide-petit-electromenager.com). '
                            . 'Publication de contenu SEO et stratégies pour générer du trafic.',
            'technologies' => ['WordPress', 'SEO', 'Affiliation'],
            'image'        => 'images/Meilleurs-mixeurs & Blenders.png',
            'lien'         => 'https://meilleur-mixeur.com',
            'mots_cles'    => 'affiliation wordpress seo marketing site web contenu digital',
        ],
        [
            'titre'        => 'DNSSEC — Sécurité DNS',
            'description'  => 'Étude et mise en place du protocole DNSSEC pour sécuriser '
                            . 'les résolutions DNS. Configuration des zones signées, gestion '
                            . 'des clés cryptographiques et validation des enregistrements.',
            'technologies' => ['Réseau', 'DNS', 'Sécurité', 'Administration réseau'],
            'image'        => 'images/dnssec.png',
            'lien'         => '',
            'mots_cles'    => 'dnssec dns sécurité réseau cryptographie zone protocole clé',
        ],
    ];
}

/**
 * Filtre un tableau de projets selon un mot-clé.
 * La recherche est insensible à la casse.
 * Elle s'effectue dans le titre, la description et les mots-clés.
 *
 * @param array  $projets  Tableau complet de projets
 * @param string $mot_cle  Le mot-clé saisi par l'utilisateur
 * @return array           Tableau filtré des projets correspondants
 */
function filtrer_projets(array $projets, string $mot_cle): array {
    /* Si aucun mot-clé, retourner tous les projets sans filtrage */
    if (trim($mot_cle) === '') {
        return $projets;
    }

    $resultats = [];

    foreach ($projets as $projet) {
        /* Construire une chaîne de recherche combinant titre, description et mots-clés */
        $texte_recherche = strtolower(
            $projet['titre']       . ' ' .
            $projet['description'] . ' ' .
            $projet['mots_cles']
        );

        /* stripos retourne false si le mot n'est pas trouvé */
        if (stripos($texte_recherche, $mot_cle) !== false) {
            $resultats[] = $projet;
        }
    }

    return $resultats;
}
