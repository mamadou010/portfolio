-- ============================================================
-- PORTFOLIO MAMADOU NDIAYE — database.sql
-- Script SQL de création des 5 tables
-- ============================================================

-- Créer la base de données si elle n'existe pas encore
CREATE DATABASE IF NOT EXISTS portfolio
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Sélectionner la base
USE portfolio;

-- ============================================================
-- TABLE 1 : projets
-- Stocke les projets affichés sur le portfolio public.
-- ============================================================
CREATE TABLE IF NOT EXISTS projets (
    id             INT           NOT NULL AUTO_INCREMENT,
    titre          VARCHAR(150)  NOT NULL,
    description    TEXT          NOT NULL,
    technologies   VARCHAR(255)  NOT NULL,
    image          VARCHAR(255)  NULL DEFAULT NULL,
    lien           VARCHAR(255)  NULL DEFAULT NULL,
    date_creation  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 2 : messages_contact
-- Stocke les messages envoyés via le formulaire de contact.
-- ============================================================
CREATE TABLE IF NOT EXISTS messages_contact (
    id          INT          NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    message     TEXT         NOT NULL,
    lu          TINYINT(1)   NOT NULL DEFAULT 0,   -- 0 = non lu, 1 = lu
    date_envoi  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 3 : demandes_projet
-- Stocke les demandes de projet envoyées via le formulaire public.
-- ============================================================
CREATE TABLE IF NOT EXISTS demandes_projet (
    id            INT          NOT NULL AUTO_INCREMENT,
    nom           VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL,
    type_projet   VARCHAR(100) NOT NULL,
    description   TEXT         NOT NULL,
    budget        VARCHAR(50)  NULL DEFAULT NULL,
    lu            TINYINT(1)   NOT NULL DEFAULT 0,  -- 0 = non lu, 1 = lu
    date_demande  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 4 : administrateurs
-- Stocke les comptes autorisés à accéder à l'espace d'administration.
-- Les mots de passe sont toujours hashés (jamais en clair).
-- ============================================================
CREATE TABLE IF NOT EXISTS administrateurs (
    id             INT          NOT NULL AUTO_INCREMENT,
    prenom         VARCHAR(100) NOT NULL,
    nom            VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe   VARCHAR(255) NOT NULL,           -- Hash bcrypt uniquement
    date_creation  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 5 : visites
-- Enregistre automatiquement chaque visite sur les pages publiques.
-- ============================================================
CREATE TABLE IF NOT EXISTS visites (
    id           INT          NOT NULL AUTO_INCREMENT,
    adresse_ip   VARCHAR(45)  NOT NULL,             -- IPv4 ou IPv6
    page         VARCHAR(255) NOT NULL,             -- Nom/chemin de la page
    date_visite  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Données de démo : projets initiaux
-- Ces projets correspondent au tableau PHP de la Partie 2.
-- ============================================================
INSERT INTO projets (titre, description, technologies, image, lien) VALUES
('Portfolio HTML/CSS',
 'Réalisation d\'un site portfolio en HTML et CSS durant ma Licence 1. Ce projet m\'a permis de maîtriser les bases du développement web, la structuration des pages et le design responsive.',
 'HTML, CSS, Responsive',
 'images/Portfolio HTML CSS L1.png',
 NULL),
('Application de gestion de contacts',
 'Développement d\'une application en C et MySQL permettant de gérer un répertoire téléphonique. Fonctionnalités : ajout, modification, suppression et recherche de contacts.',
 'C, MySQL, CRUD',
 'images/Projet en C & MySQL.png',
 NULL),
('Poubelle intelligente',
 'Réalisation en groupe d\'une poubelle capable de s\'ouvrir automatiquement grâce à un capteur de proximité. Travail sur l\'interaction matériel/logiciel et la programmation de microcontrôleurs.',
 'Embarqué, Microcontrôleur, C',
 'images/Poubelle intelligente.png',
 NULL),
('Mesure température & humidité',
 'Réalisation en groupe d\'un système de mesure de température et d\'humidité à l\'aide de capteurs connectés à un microcontrôleur pour afficher les données en temps réel.',
 'Embarqué, Capteurs, Microcontrôleur',
 'images/Mesure température & humidité.png',
 NULL),
('Projet entrepreneurial — CARA',
 'Conception en groupe d\'un Centre d\'Accompagnement et de Réussite Académique. Ce projet m\'a permis de développer le travail en équipe et la gestion de projet.',
 'Gestion de projet, Travail en équipe',
 'images/CARA-Centre-Accompagnement-Reussite-Academique.png',
 NULL),
('Sites d\'affiliation',
 'Création et gestion de deux sites web d\'affiliation (meilleur-mixeur.com et mon-guide-petit-electromenager.com). Publication de contenu SEO et stratégies pour générer du trafic.',
 'WordPress, SEO, Affiliation',
 'images/Meilleurs-mixeurs & Blenders.png',
 'https://meilleur-mixeur.com'),
('DNSSEC — Sécurité DNS',
 'Étude et mise en place du protocole DNSSEC pour sécuriser les résolutions DNS. Configuration des zones signées, gestion des clés cryptographiques et validation des enregistrements.',
 'Réseau, DNS, Sécurité, Administration réseau',
 'images/dnssec.png',
 NULL);
