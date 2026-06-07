<?php
/* ============================================================
   PORTFOLIO MAMADOU NDIAYE — contact.php
   Page de contact avec deux formulaires :
     1. Formulaire de message général  → table messages_contact
     2. Formulaire de demande de projet → table demandes_projet
   Protection CSRF + validation serveur + journalisation visite.
   ============================================================ */

session_start();
require_once 'fonctions.php';
require_once 'config/connexion.php';

/* ---- Journalisation de la visite -------------------------- */
enregistrer_visite($pdo, 'contact.php');

/* ============================================================
   TRAITEMENT FORMULAIRE DE CONTACT (Onglet 1)
   ============================================================ */
$erreurs_contact  = [];
$succes_contact   = false;
$valeurs_contact  = ['nom' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_contact'])) {

    verifier_csrf('contact');

    $valeurs_contact['nom']     = valeur_post('nom');
    $valeurs_contact['email']   = valeur_post('email');
    $valeurs_contact['message'] = valeur_post('message');

    if (!champ_requis($valeurs_contact['nom']))
        { $erreurs_contact['nom']     = 'Le nom est obligatoire.'; }
    if (!champ_requis($valeurs_contact['email']))
        { $erreurs_contact['email']   = "L'adresse e-mail est obligatoire."; }
    elseif (!email_valide($valeurs_contact['email']))
        { $erreurs_contact['email']   = "L'adresse e-mail n'est pas valide."; }
    if (!champ_requis($valeurs_contact['message']))
        { $erreurs_contact['message'] = 'Le message est obligatoire.'; }

    if (empty($erreurs_contact)) {
        try {
            $req = $pdo->prepare(
                'INSERT INTO messages_contact (nom, email, message)
                 VALUES (:nom, :email, :message)'
            );
            $req->execute([
                ':nom'     => trim($_POST['nom']),
                ':email'   => trim($_POST['email']),
                ':message' => trim($_POST['message']),
            ]);
            $succes_contact  = true;
            $valeurs_contact = ['nom' => '', 'email' => '', 'message' => ''];
        } catch (PDOException $e) {
            error_log('[PORTFOLIO] Erreur insertion message : ' . $e->getMessage());
            $erreurs_contact['global'] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

/* ============================================================
   TRAITEMENT FORMULAIRE DEMANDE DE PROJET (Onglet 2)
   ============================================================ */
$erreurs_demande  = [];
$succes_demande   = false;
$valeurs_demande  = ['nom' => '', 'email' => '', 'type_projet' => '', 'description' => '', 'budget' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_demande'])) {

    verifier_csrf('demande');

    $valeurs_demande['nom']         = valeur_post('nom');
    $valeurs_demande['email']       = valeur_post('email');
    $valeurs_demande['type_projet'] = valeur_post('type_projet');
    $valeurs_demande['description'] = valeur_post('description');
    $valeurs_demande['budget']      = valeur_post('budget');

    if (!champ_requis($valeurs_demande['nom']))
        { $erreurs_demande['nom']         = 'Le nom est obligatoire.'; }
    if (!champ_requis($valeurs_demande['email']))
        { $erreurs_demande['email']       = "L'e-mail est obligatoire."; }
    elseif (!email_valide($valeurs_demande['email']))
        { $erreurs_demande['email']       = "L'e-mail n'est pas valide."; }
    if (!champ_requis($valeurs_demande['type_projet']))
        { $erreurs_demande['type_projet'] = 'Le type de projet est obligatoire.'; }
    if (!champ_requis($valeurs_demande['description']))
        { $erreurs_demande['description'] = 'La description est obligatoire.'; }

    if (empty($erreurs_demande)) {
        try {
            $req = $pdo->prepare(
                'INSERT INTO demandes_projet (nom, email, type_projet, description, budget)
                 VALUES (:nom, :email, :type_projet, :description, :budget)'
            );
            $req->execute([
                ':nom'         => trim($_POST['nom']),
                ':email'       => trim($_POST['email']),
                ':type_projet' => trim($_POST['type_projet']),
                ':description' => trim($_POST['description']),
                ':budget'      => trim($_POST['budget']) ?: null,
            ]);
            $succes_demande  = true;
            $valeurs_demande = ['nom' => '', 'email' => '', 'type_projet' => '', 'description' => '', 'budget' => ''];
        } catch (PDOException $e) {
            error_log('[PORTFOLIO] Erreur insertion demande : ' . $e->getMessage());
            $erreurs_demande['global'] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

/* Onglet actif : demande si soumission demande (erreur ou succès) */
$onglet_actif = (!empty($erreurs_demande) || $succes_demande) ? 'demande' : 'contact';
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
        <p>Une question, un projet, une collaboration ? Écrivez-moi !</p>
    </section>

    <!-- ========== INFOS DE CONTACT ========== -->
    <div class="section-contact-infos">

        <div class="contact-info-carte">
            <div class="contact-icone">
                <i class="fas fa-envelope"></i>
            </div>
            <strong>Email</strong>
            <a href="mailto:ndiayem9999@gmail.com">ndiayem9999@gmail.com</a>
        </div>

        <div class="contact-info-carte">
            <div class="contact-icone">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <strong>Localisation</strong>
            <span>Dakar, Sénégal</span>
        </div>

        <div class="contact-info-carte">
            <div class="contact-icone">
                <i class="fab fa-github"></i>
            </div>
            <strong>GitHub</strong>
            <a href="https://github.com/mamadou010" target="_blank" rel="noopener noreferrer">
                github.com/mamadou010
            </a>
        </div>

    </div>

    <!-- ========== FORMULAIRES AVEC ONGLETS ========== -->
    <div class="section-formulaires">

        <!-- Navigation par onglets — utilise les classes CSS existantes -->
        <div class="onglets">
            <button class="onglet <?= $onglet_actif === 'contact' ? 'actif' : '' ?>"
                    data-cible="panneau-contact">
                <i class="fas fa-envelope"></i> Message
            </button>
            <button class="onglet <?= $onglet_actif === 'demande' ? 'actif' : '' ?>"
                    data-cible="panneau-demande">
                <i class="fas fa-project-diagram"></i> Demande de projet
            </button>
        </div>

        <!-- ===== ONGLET 1 : Message de contact ===== -->
        <div id="panneau-contact"
             class="panneau-form <?= $onglet_actif === 'contact' ? 'actif' : '' ?>">

            <?php if ($succes_contact) : ?>
                <!-- Succès : affichage stylé avec l'icône et la classe existante -->
                <div class="message-succes">
                    <i class="fas fa-check-circle"></i>
                    <h3>Message envoyé !</h3>
                    <p>Merci pour votre message. Je vous répondrai dès que possible.</p>
                </div>
            <?php else : ?>

                <?php if (!empty($erreurs_contact['global'])) : ?>
                    <p class="erreur-champ" style="margin-bottom:1rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= nettoyer($erreurs_contact['global']) ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="contact.php" novalidate>
                    <!-- Champ caché pour identifier le formulaire -->
                    <input type="hidden" name="form_contact" value="1">
                    <!-- Jeton CSRF -->
                    <?php champ_csrf('contact'); ?>

                    <!-- Nom -->
                    <div class="groupe-champ">
                        <label for="contact-nom">
                            Votre nom <span class="obligatoire">*</span>
                        </label>
                        <input type="text"
                               id="contact-nom"
                               name="nom"
                               value="<?= $valeurs_contact['nom'] ?>"
                               placeholder="Ex : Harouna Ndiaye"
                               autocomplete="name">
                        <?php afficher_erreur($erreurs_contact, 'nom'); ?>
                    </div>

                    <!-- Email -->
                    <div class="groupe-champ">
                        <label for="contact-email">
                            Adresse e-mail <span class="obligatoire">*</span>
                        </label>
                        <input type="email"
                               id="contact-email"
                               name="email"
                               value="<?= $valeurs_contact['email'] ?>"
                               placeholder="example@mail.com"
                               autocomplete="email">
                        <?php afficher_erreur($erreurs_contact, 'email'); ?>
                    </div>

                    <!-- Message -->
                    <div class="groupe-champ">
                        <label for="contact-message">
                            Votre message <span class="obligatoire">*</span>
                        </label>
                        <textarea id="contact-message"
                                  name="message"
                                  rows="6"
                                  placeholder="Décrivez votre demande..."><?= $valeurs_contact['message'] ?></textarea>
                        <?php afficher_erreur($erreurs_contact, 'message'); ?>
                    </div>

                    <button type="submit" class="btn-principal" style="width:100%;justify-content:center;display:flex;gap:.5rem;align-items:center;">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>

                    <p class="note-form">* Champs obligatoires</p>
                </form>

            <?php endif; ?>
        </div>

        <!-- ===== ONGLET 2 : Demande de projet ===== -->
        <div id="panneau-demande"
             class="panneau-form <?= $onglet_actif === 'demande' ? 'actif' : '' ?>">

            <?php if ($succes_demande) : ?>
                <div class="message-succes">
                    <i class="fas fa-rocket" style="color:var(--couleur-accent);"></i>
                    <h3>Demande envoyée !</h3>
                    <p>Votre demande de projet a bien été reçue. Je vous contacterai rapidement.</p>
                </div>
            <?php else : ?>

                <?php if (!empty($erreurs_demande['global'])) : ?>
                    <p class="erreur-champ" style="margin-bottom:1rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= nettoyer($erreurs_demande['global']) ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="contact.php" novalidate>
                    <input type="hidden" name="form_demande" value="1">
                    <?php champ_csrf('demande'); ?>

                    <!-- Nom -->
                    <div class="groupe-champ">
                        <label for="demande-nom">
                            Votre nom / entreprise <span class="obligatoire">*</span>
                        </label>
                        <input type="text"
                               id="demande-nom"
                               name="nom"
                               value="<?= $valeurs_demande['nom'] ?>"
                               placeholder=" Nom ou entreprise"
                               autocomplete="name">
                        <?php afficher_erreur($erreurs_demande, 'nom'); ?>
                    </div>

                    <!-- Email -->
                    <div class="groupe-champ">
                        <label for="demande-email">
                            Email de contact <span class="obligatoire">*</span>
                        </label>
                        <input type="email"
                               id="demande-email"
                               name="email"
                               value="<?= $valeurs_demande['email'] ?>"
                               placeholder="contact@entreprise.com"
                               autocomplete="email">
                        <?php afficher_erreur($erreurs_demande, 'email'); ?>
                    </div>

                    <!-- Type de projet -->
                    <div class="groupe-champ">
                        <label for="demande-type">
                            Type de projet <span class="obligatoire">*</span>
                        </label>
                        <select id="demande-type" name="type_projet">
                            <option value="">-- Choisissez un type --</option>
                            <?php
                            $types = ['Site vitrine', 'Site e-commerce', 'Application web', 'Site WordPress', 'Portfolio', 'Autre'];
                            foreach ($types as $type) :
                                $sel = ($valeurs_demande['type_projet'] === $type) ? 'selected' : '';
                            ?>
                                <option value="<?= nettoyer($type) ?>" <?= $sel ?>>
                                    <?= nettoyer($type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php afficher_erreur($erreurs_demande, 'type_projet'); ?>
                    </div>

                    <!-- Description -->
                    <div class="groupe-champ">
                        <label for="demande-description">
                            Description du projet <span class="obligatoire">*</span>
                        </label>
                        <textarea id="demande-description"
                                  name="description"
                                  rows="5"
                                  placeholder="Décrivez votre projet, vos besoins et vos délais..."><?= $valeurs_demande['description'] ?></textarea>
                        <?php afficher_erreur($erreurs_demande, 'description'); ?>
                    </div>

                    <!-- Budget (optionnel) -->
                    <div class="groupe-champ">
                        <label for="demande-budget">
                            Budget estimé
                            <span style="font-size:0.8em;font-weight:400;color:var(--couleur-texte-doux);">(optionnel)</span>
                        </label>
                        <select id="demande-budget" name="budget">
                            <option value="">-- Budget approximatif --</option>
                            <?php
                            $budgets = ['Moins de 100 000 FCFA', '100 000 – 300 000 FCFA', '300 000 – 500 000 FCFA', 'Plus de 500 000 FCFA'];
                            foreach ($budgets as $budget) :
                                $sel = ($valeurs_demande['budget'] === $budget) ? 'selected' : '';
                            ?>
                                <option value="<?= nettoyer($budget) ?>" <?= $sel ?>>
                                    <?= nettoyer($budget) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-principal" style="width:100%;justify-content:center;display:flex;gap:.5rem;align-items:center;">
                        <i class="fas fa-rocket"></i> Envoyer la demande
                    </button>

                    <p class="note-form">* Champs obligatoires</p>
                </form>

            <?php endif; ?>
        </div>

    </div>

    <?php require 'composants/pied-de-page.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>