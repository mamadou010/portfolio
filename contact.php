<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — contact.php
   Page de contact avec deux formulaires traités en PHP :
     1. Formulaire de contact simple
     2. Formulaire de demande de projet

   Fonctionnement :
   - Les deux formulaires postent vers cette même page (contact.php)
   - Un champ caché 'form_type' distingue les deux formulaires
   - PHP valide chaque champ et affiche les erreurs en ligne
   - En cas de succès, un message de confirmation est affiché
   - Les valeurs saisies sont conservées si un champ est invalide
   ============================================================ */

require 'fonctions.php';

/* ============================================================
   TRAITEMENT — FORMULAIRE 1 : CONTACT SIMPLE
   ============================================================ */
$erreurs_contact  = [];    /* Tableau associatif des erreurs par nom de champ */
$succes_contact   = false; /* Passe à true si tous les champs sont valides */

/* Valeurs conservées pour pré-remplir le formulaire après une erreur */
$contact_nom      = '';
$contact_email    = '';
$contact_sujet    = '';
$contact_message  = '';

/* Traiter uniquement si le formulaire a été soumis en POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['form_type'])
    && $_POST['form_type'] === 'contact')
{
    /* Récupérer et nettoyer chaque champ */
    $contact_nom     = valeur_post('nom');
    $contact_email   = valeur_post('email');
    $contact_sujet   = valeur_post('sujet');
    $contact_message = valeur_post('message');

    /* --- Validation champ par champ --- */

    /* Nom : ne doit pas être vide */
    if (!champ_requis($contact_nom)) {
        $erreurs_contact['nom'] = 'Le nom est obligatoire.';
    }

    /* Email : obligatoire et format valide */
    if (!champ_requis($contact_email)) {
        $erreurs_contact['email'] = 'L\'adresse e-mail est obligatoire.';
    } elseif (!email_valide($contact_email)) {
        $erreurs_contact['email'] = 'L\'adresse e-mail n\'est pas valide.';
    }

    /* Sujet : ne doit pas être vide */
    if (!champ_requis($contact_sujet)) {
        $erreurs_contact['sujet'] = 'Le sujet est obligatoire.';
    }

    /* Message : obligatoire et au moins 10 caractères */
    if (!champ_requis($contact_message)) {
        $erreurs_contact['message'] = 'Le message ne peut pas être vide.';
    } elseif (strlen(trim($contact_message)) < 10) {
        $erreurs_contact['message'] = 'Le message est trop court (minimum 10 caractères).';
    }

    /* Si aucune erreur : succès — ici on pourrait appeler mail() ou insérer en BDD */
    if (empty($erreurs_contact)) {
        $succes_contact = true;
    }
}

/* ============================================================
   TRAITEMENT — FORMULAIRE 2 : DEMANDE DE PROJET
   ============================================================ */
$erreurs_projet  = [];
$succes_projet   = false;

/* Valeurs conservées en cas d'erreur */
$proj_nom        = '';
$proj_email      = '';
$proj_type       = '';
$proj_budget     = '';
$proj_desc       = '';

/* Tableau de correspondance pour afficher les types lisibles */
$types_lisibles = [
    'site-vitrine' => 'Site vitrine',
    'ecommerce'    => 'Site e-commerce',
    'application'  => 'Application web',
    'blog'         => 'Blog / Contenu',
    'autre'        => 'Autre',
];

/* Récapitulatif affiché après soumission réussie */
$recap_projet = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['form_type'])
    && $_POST['form_type'] === 'projet')
{
    /* Récupérer et nettoyer chaque champ */
    $proj_nom    = valeur_post('proj-nom');
    $proj_email  = valeur_post('proj-email');
    $proj_type   = valeur_post('proj-type');
    $proj_budget = valeur_post('proj-budget');
    $proj_desc   = valeur_post('proj-desc');

    /* --- Validation champ par champ --- */

    if (!champ_requis($proj_nom)) {
        $erreurs_projet['proj-nom'] = 'Le nom ou l\'entreprise est obligatoire.';
    }

    if (!champ_requis($proj_email)) {
        $erreurs_projet['proj-email'] = 'L\'adresse e-mail est obligatoire.';
    } elseif (!email_valide($proj_email)) {
        $erreurs_projet['proj-email'] = 'L\'adresse e-mail n\'est pas valide.';
    }

    /* Vérifier que le type choisi est bien dans la liste autorisée */
    if (!champ_requis($proj_type) || !array_key_exists($proj_type, $types_lisibles)) {
        $erreurs_projet['proj-type'] = 'Veuillez choisir un type de projet.';
    }

    if (!champ_requis($proj_desc)) {
        $erreurs_projet['proj-desc'] = 'La description du projet est obligatoire.';
    } elseif (strlen(trim($proj_desc)) < 20) {
        $erreurs_projet['proj-desc'] = 'La description est trop courte (minimum 20 caractères).';
    }

    /* Si tout est valide : construire le récapitulatif */
    if (empty($erreurs_projet)) {
        $succes_projet = true;

        $recap_projet = [
            'nom'         => $proj_nom,
            'email'       => $proj_email,
            'type_lisible'=> $types_lisibles[$proj_type],
            'budget'      => $proj_budget !== '' ? $proj_budget : 'Non précisé',
            'description' => $proj_desc,
        ];
    }
}

/*
   Déterminer quel onglet afficher par défaut.
   Si c'est le formulaire projet qui vient d'être soumis,
   on reste sur l'onglet "projet", sinon on ouvre "contact".
*/
$onglet_actif = (isset($_POST['form_type']) && $_POST['form_type'] === 'projet')
                ? 'projet'
                : 'contact';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Mamadou Ndiaye</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php require 'composants/navigation.php'; ?>

    <!-- ========== EN-TÊTE DE PAGE ========== -->
    <section class="entete-page">
        <h1>Me <span class="couleur-accent">contacter</span></h1>
        <p>Une question, un projet ou une collaboration ? Écrivez-moi !</p>
    </section>

    <!-- ========== CARTES D'INFORMATIONS DE CONTACT ========== -->
    <section class="section-contact-infos">

        <div class="contact-info-carte">
            <div class="contact-icone"><i class="fas fa-envelope"></i></div>
            <strong>Email</strong>
            <a href="mailto:ndiayem9999@gmail.com">ndiayem9999@gmail.com</a>
        </div>

        <div class="contact-info-carte">
            <div class="contact-icone"><i class="fas fa-globe"></i></div>
            <strong>Sites web</strong>
            <a href="https://meilleur-mixeur.com"
               target="_blank"
               rel="noopener noreferrer">
                meilleur-mixeur.com
            </a>
            <a href="https://mon-guide-petit-electromenager.com"
               target="_blank"
               rel="noopener noreferrer">
                mon-guide-petit-electromenager.com
            </a>
        </div>

        <div class="contact-info-carte">
            <div class="contact-icone"><i class="fab fa-github"></i></div>
            <strong>GitHub</strong>
            <a href="https://github.com/mamadou010"
               target="_blank"
               rel="noopener noreferrer">
                github.com/mamadou010
            </a>
        </div>

    </section>

    <!-- ========== SECTION FORMULAIRES ========== -->
    <section class="section-formulaires">

        <!-- Onglets de sélection entre les deux formulaires -->
        <div class="onglets">
            <button class="onglet <?= $onglet_actif === 'contact' ? 'actif' : '' ?>"
                    data-cible="form-contact">
                <i class="fas fa-envelope"></i> Me contacter
            </button>
            <button class="onglet <?= $onglet_actif === 'projet' ? 'actif' : '' ?>"
                    data-cible="form-projet">
                <i class="fas fa-briefcase"></i> Demande de projet
            </button>
        </div>

        <!-- ========================================
             PANNEAU 1 : FORMULAIRE CONTACT SIMPLE
             ======================================== -->
        <div class="panneau-form <?= $onglet_actif === 'contact' ? 'actif' : '' ?>"
             id="form-contact">

            <?php if ($succes_contact) : ?>

                <!-- Message de succès après envoi correct -->
                <div class="message-succes">
                    <i class="fas fa-check-circle"></i>
                    <h3>Message envoyé avec succès !</h3>
                    <p>
                        Merci <strong><?= nettoyer($contact_nom) ?></strong>,
                        votre message a bien été reçu. Je vous répondrai
                        à <strong><?= nettoyer($contact_email) ?></strong>
                        dans les plus brefs délais.
                    </p>
                    <a href="contact.php" class="btn-secondaire">
                        Envoyer un autre message
                    </a>
                </div>

            <?php else : ?>

                <h3>Envoyez-moi un message</h3>

                <!--
                    action="contact.php" → la page se traite elle-même
                    method="POST"        → les données ne sont pas visibles dans l'URL
                    novalidate           → on désactive la validation HTML5 native
                                           pour laisser PHP gérer les erreurs
                -->
                <form method="POST" action="contact.php" novalidate>

                    <!-- Champ caché : identifie ce formulaire parmi les deux -->
                    <input type="hidden" name="form_type" value="contact">

                    <!-- Champ : Nom -->
                    <div class="groupe-champ <?= !empty($erreurs_contact['nom']) ? 'champ-erreur' : '' ?>">
                        <label for="nom">
                            Votre nom <span class="obligatoire">*</span>
                        </label>
                        <input type="text"
                               id="nom"
                               name="nom"
                               placeholder="Ex : Harouna Ndiaye"
                               value="<?= nettoyer($contact_nom) ?>"
                               required>
                        <?php afficher_erreur($erreurs_contact, 'nom'); ?>
                    </div>

                    <!-- Champ : Email -->
                    <div class="groupe-champ <?= !empty($erreurs_contact['email']) ? 'champ-erreur' : '' ?>">
                        <label for="email">
                            Votre email <span class="obligatoire">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               placeholder="exemple@mail.com"
                               value="<?= nettoyer($contact_email) ?>"
                               required>
                        <?php afficher_erreur($erreurs_contact, 'email'); ?>
                    </div>

                    <!-- Champ : Sujet -->
                    <div class="groupe-champ <?= !empty($erreurs_contact['sujet']) ? 'champ-erreur' : '' ?>">
                        <label for="sujet">
                            Sujet <span class="obligatoire">*</span>
                        </label>
                        <input type="text"
                               id="sujet"
                               name="sujet"
                               placeholder="Objet de votre message"
                               value="<?= nettoyer($contact_sujet) ?>"
                               required>
                        <?php afficher_erreur($erreurs_contact, 'sujet'); ?>
                    </div>

                    <!-- Champ : Message -->
                    <div class="groupe-champ <?= !empty($erreurs_contact['message']) ? 'champ-erreur' : '' ?>">
                        <label for="message">
                            Message <span class="obligatoire">*</span>
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="5"
                                  placeholder="Écrivez votre message ici..."
                                  required><?= nettoyer($contact_message) ?></textarea>
                        <?php afficher_erreur($erreurs_contact, 'message'); ?>
                    </div>

                    <button type="submit" class="btn-principal btn-plein">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>

                </form>

            <?php endif; ?>

        </div>

        <!-- ========================================
             PANNEAU 2 : FORMULAIRE DEMANDE DE PROJET
             ======================================== -->
        <div class="panneau-form <?= $onglet_actif === 'projet' ? 'actif' : '' ?>"
             id="form-projet">

            <?php if ($succes_projet) : ?>

                <!-- Récapitulatif de la demande après soumission réussie -->
                <div class="message-succes">
                    <i class="fas fa-check-circle"></i>
                    <h3>Demande reçue !</h3>
                    <p>
                        Merci <strong><?= nettoyer($recap_projet['nom']) ?></strong>,
                        voici le récapitulatif de votre demande :
                    </p>

                    <!-- Tableau récapitulatif des données envoyées -->
                    <div class="recap-projet">

                        <div class="recap-ligne">
                            <span class="recap-label">
                                <i class="fas fa-user"></i> Nom
                            </span>
                            <span class="recap-valeur">
                                <?= nettoyer($recap_projet['nom']) ?>
                            </span>
                        </div>

                        <div class="recap-ligne">
                            <span class="recap-label">
                                <i class="fas fa-envelope"></i> Email
                            </span>
                            <span class="recap-valeur">
                                <?= nettoyer($recap_projet['email']) ?>
                            </span>
                        </div>

                        <div class="recap-ligne">
                            <span class="recap-label">
                                <i class="fas fa-code"></i> Type de projet
                            </span>
                            <span class="recap-valeur">
                                <?= nettoyer($recap_projet['type_lisible']) ?>
                            </span>
                        </div>

                        <div class="recap-ligne">
                            <span class="recap-label">
                                <i class="fas fa-wallet"></i> Budget
                            </span>
                            <span class="recap-valeur">
                                <?= nettoyer($recap_projet['budget']) ?>
                            </span>
                        </div>

                        <div class="recap-ligne">
                            <span class="recap-label">
                                <i class="fas fa-file-alt"></i> Description
                            </span>
                            <span class="recap-valeur">
                                <?= nettoyer($recap_projet['description']) ?>
                            </span>
                        </div>

                    </div>

                    <p style="margin-top:1rem;">
                        Je vous contacterai à
                        <strong><?= nettoyer($recap_projet['email']) ?></strong>
                        dans les plus brefs délais.
                    </p>

                    <a href="contact.php" class="btn-secondaire">
                        Nouvelle demande
                    </a>
                </div>

            <?php else : ?>

                <h3>Décrivez votre projet</h3>

                <form method="POST" action="contact.php" novalidate>

                    <!-- Champ caché : identifie ce formulaire -->
                    <input type="hidden" name="form_type" value="projet">

                    <!-- Champ : Nom / Entreprise -->
                    <div class="groupe-champ <?= !empty($erreurs_projet['proj-nom']) ? 'champ-erreur' : '' ?>">
                        <label for="proj-nom">
                            Votre nom / entreprise <span class="obligatoire">*</span>
                        </label>
                        <input type="text"
                               id="proj-nom"
                               name="proj-nom"
                               placeholder="Nom ou entreprise"
                               value="<?= nettoyer($proj_nom) ?>"
                               required>
                        <?php afficher_erreur($erreurs_projet, 'proj-nom'); ?>
                    </div>

                    <!-- Champ : Email de contact -->
                    <div class="groupe-champ <?= !empty($erreurs_projet['proj-email']) ? 'champ-erreur' : '' ?>">
                        <label for="proj-email">
                            Email de contact <span class="obligatoire">*</span>
                        </label>
                        <input type="email"
                               id="proj-email"
                               name="proj-email"
                               placeholder="contact@entreprise.com"
                               value="<?= nettoyer($proj_email) ?>"
                               required>
                        <?php afficher_erreur($erreurs_projet, 'proj-email'); ?>
                    </div>

                    <!-- Champ : Type de projet (liste déroulante) -->
                    <div class="groupe-champ <?= !empty($erreurs_projet['proj-type']) ? 'champ-erreur' : '' ?>">
                        <label for="proj-type">
                            Type de projet <span class="obligatoire">*</span>
                        </label>
                        <select id="proj-type" name="proj-type" required>
                            <option value="" disabled
                                <?= $proj_type === '' ? 'selected' : '' ?>>
                                -- Choisir un type --
                            </option>
                            <?php
                            /* Générer les options dynamiquement depuis $types_lisibles.
                               L'option précédemment choisie est marquée 'selected'
                               pour pré-remplir le formulaire en cas d'erreur. */
                            foreach ($types_lisibles as $valeur => $libelle) :
                            ?>
                            <option value="<?= nettoyer($valeur) ?>"
                                    <?= $proj_type === $valeur ? 'selected' : '' ?>>
                                <?= nettoyer($libelle) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php afficher_erreur($erreurs_projet, 'proj-type'); ?>
                    </div>

                    <!-- Champ : Budget estimé (optionnel) -->
                    <div class="groupe-champ">
                        <label for="proj-budget">Budget estimé</label>
                        <select id="proj-budget" name="proj-budget">
                            <option value=""
                                <?= $proj_budget === '' ? 'selected' : '' ?>>
                                -- Budget approximatif --
                            </option>
                            <?php
                            /* Tableau des tranches de budget */
                            $budgets = [
                                'Moins de 100 000 FCFA',
                                '100 000 – 300 000 FCFA',
                                'Plus de 300 000 FCFA',
                            ];
                            foreach ($budgets as $b) :
                            ?>
                            <option value="<?= nettoyer($b) ?>"
                                    <?= $proj_budget === $b ? 'selected' : '' ?>>
                                <?= nettoyer($b) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Champ : Description du projet -->
                    <div class="groupe-champ <?= !empty($erreurs_projet['proj-desc']) ? 'champ-erreur' : '' ?>">
                        <label for="proj-desc">
                            Description du projet <span class="obligatoire">*</span>
                        </label>
                        <textarea id="proj-desc"
                                  name="proj-desc"
                                  rows="5"
                                  placeholder="Décrivez votre projet, vos besoins et vos délais..."
                                  required><?= nettoyer($proj_desc) ?></textarea>
                        <?php afficher_erreur($erreurs_projet, 'proj-desc'); ?>
                    </div>

                    <button type="submit" class="btn-principal btn-plein">
                        <i class="fas fa-paper-plane"></i> Envoyer la demande
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </section>

    <?php require 'composants/pied-de-page.php'; ?>

    <script src="js/script.js"></script>

</body>
</html>
