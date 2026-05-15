/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — js/script.js

   Ce fichier gère UNIQUEMENT les interactions côté client :
     1. Mode sombre / mode clair (persisté via localStorage)
     2. Menu hamburger pour mobile
     3. Carrousel d'images (avec défilement auto)
     4. Onglets du formulaire de contact

   La recherche de projets et la validation des formulaires
   sont entièrement gérées en PHP côté serveur.
   ============================================================ */

/* ============================================================
   1. MODE SOMBRE / MODE CLAIR
   ============================================================ */

var btnTheme = document.getElementById('btnTheme');

/* Au chargement de la page, appliquer le thème enregistré dans localStorage.
   Par défaut on utilise le mode sombre si rien n'est enregistré. */
var themeSauvegarde = localStorage.getItem('theme') || 'sombre';
appliquerTheme(themeSauvegarde);

/**
 * Applique un thème (clair ou sombre) à toute la page.
 * En mode clair  → ajoute data-theme="clair" sur <html>
 * En mode sombre → retire l'attribut data-theme (CSS par défaut)
 * Met aussi à jour l'icône du bouton.
 *
 * @param {string} theme - 'clair' ou 'sombre'
 */
function appliquerTheme(theme) {
    if (theme === 'clair') {
        document.documentElement.setAttribute('data-theme', 'clair');
        if (btnTheme) {
            /* Icône soleil en mode clair */
            btnTheme.innerHTML = '<i class="fas fa-sun"></i>';
        }
    } else {
        document.documentElement.removeAttribute('data-theme');
        if (btnTheme) {
            /* Icône lune en mode sombre */
            btnTheme.innerHTML = '<i class="fas fa-moon"></i>';
        }
    }
    /* Sauvegarder le choix pour la prochaine visite */
    localStorage.setItem('theme', theme);
}

/* Écouter le clic sur le bouton de thème */
if (btnTheme) {
    btnTheme.addEventListener('click', function () {
        var themeActuel = localStorage.getItem('theme') || 'sombre';
        /* Basculer vers l'autre thème */
        appliquerTheme(themeActuel === 'sombre' ? 'clair' : 'sombre');
    });
}

/* ============================================================
   2. MENU HAMBURGER MOBILE
   ============================================================ */

var hamburger = document.getElementById('hamburger');
var navMenu   = document.querySelector('.nav-menu');

if (hamburger && navMenu) {

    /* Ouvrir / fermer le menu au clic sur le hamburger */
    hamburger.addEventListener('click', function () {
        navMenu.classList.toggle('ouvert');
        hamburger.classList.toggle('ouvert');
    });

    /* Fermer le menu automatiquement quand on clique sur un lien */
    var liensNav = navMenu.querySelectorAll('.nav-lien');
    liensNav.forEach(function (lien) {
        lien.addEventListener('click', function () {
            navMenu.classList.remove('ouvert');
            hamburger.classList.remove('ouvert');
        });
    });
}


/* ============================================================
   3. CARROUSEL D'IMAGES
   ============================================================ */

var piste = document.getElementById('carrouselPiste');

if (piste) {

    var slides          = piste.querySelectorAll('.carrousel-slide');
    var conteneurPoints = document.getElementById('carrouselPoints');
    var indexActuel     = 0;
    var intervalle;

    /* --- Créer les points de navigation dynamiquement --- */
    for (var j = 0; j < slides.length; j++) {
        var point = document.createElement('button');
        point.className = 'point-nav';
        point.setAttribute('aria-label', 'Aller au slide ' + (j + 1));
        point.dataset.index = j;

        /* Premier point actif par défaut */
        if (j === 0) {
            point.classList.add('actif');
        }

        conteneurPoints.appendChild(point);
    }

    /**
     * Déplace le carrousel vers le slide à l'index donné.
     * Utilise CSS transform translateX pour le glissement.
     * Boucle automatiquement (dernier → premier et vice-versa).
     *
     * @param {number} index - Index du slide cible
     */
    function allerAuSlide(index) {
        /* Le modulo permet de boucler dans les deux sens */
        indexActuel = (index + slides.length) % slides.length;

        /* Déplacer la piste horizontalement */
        piste.style.transform = 'translateX(-' + (indexActuel * 100) + '%)';

        /* Mettre à jour les points de navigation */
        var points = conteneurPoints.querySelectorAll('.point-nav');
        for (var k = 0; k < points.length; k++) {
            points[k].classList.remove('actif');
        }
        points[indexActuel].classList.add('actif');
    }

    /* Bouton précédent */
    var btnPrecedent = document.getElementById('btnPrecedent');
    if (btnPrecedent) {
        btnPrecedent.addEventListener('click', function () {
            allerAuSlide(indexActuel - 1);
            reinitialiserIntervalle(); /* Réinitialiser le timer auto */
        });
    }

    /* Bouton suivant */
    var btnSuivant = document.getElementById('btnSuivant');
    if (btnSuivant) {
        btnSuivant.addEventListener('click', function () {
            allerAuSlide(indexActuel + 1);
            reinitialiserIntervalle();
        });
    }

    /* Clic sur les points de navigation */
    conteneurPoints.addEventListener('click', function (e) {
        if (e.target.classList.contains('point-nav')) {
            allerAuSlide(parseInt(e.target.dataset.index));
            reinitialiserIntervalle();
        }
    });

    /* --- Défilement automatique toutes les 4 secondes --- */
    function demarrerIntervalle() {
        intervalle = setInterval(function () {
            allerAuSlide(indexActuel + 1);
        }, 4000);
    }

    /* Réinitialiser le timer après une action manuelle
       pour éviter un saut immédiat après le clic */
    function reinitialiserIntervalle() {
        clearInterval(intervalle);
        demarrerIntervalle();
    }

    /* Lancer le défilement automatique dès le chargement */
    demarrerIntervalle();
}


/* ============================================================
   4. ONGLETS DU FORMULAIRE DE CONTACT
   Gestion de l'affichage côté client.
   Le traitement (validation + soumission) est géré par PHP.
   ============================================================ */

var boutonsOnglets = document.querySelectorAll('.onglet');
var panneauxForm   = document.querySelectorAll('.panneau-form');

if (boutonsOnglets.length > 0) {

    boutonsOnglets.forEach(function (btn) {
        btn.addEventListener('click', function () {

            /* Récupérer l'identifiant du panneau à afficher */
            var cibleId = btn.getAttribute('data-cible');

            /* Désactiver tous les onglets et masquer tous les panneaux */
            boutonsOnglets.forEach(function (b) { b.classList.remove('actif'); });
            panneauxForm.forEach(function (p)   { p.classList.remove('actif'); });

            /* Activer l'onglet cliqué et afficher son panneau */
            btn.classList.add('actif');
            var panneau = document.getElementById(cibleId);
            if (panneau) {
                panneau.classList.add('actif');
            }
        });
    });
}
