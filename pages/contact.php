<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

// Enregistrer la visite
enregistrerVisite($pdo, basename($_SERVER['PHP_SELF']));

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erreurs = [];
$succes = false;
$nom = '';
$email = '';
$description = '';
$budget = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $erreurs[] = "Erreur de sécurité. Veuillez réessayer.";
    } else {
        // Détecter quel formulaire a été soumis
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'demande') {
            // Formulaire de demande de projet
            $nom = nettoyer($_POST['client_name'] ?? '');
            $email = nettoyer($_POST['client_email'] ?? '');
            $description = nettoyer($_POST['project_description'] ?? '');
            $budget = nettoyer($_POST['budget'] ?? '');

            if (!champ_requis($nom)) $erreurs[] = "Le nom est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "L'email n'est pas valide.";
            if (!champ_requis($description)) $erreurs[] = "La description du projet est obligatoire.";

            if (empty($erreurs)) {
                $stmt = $pdo->prepare("INSERT INTO demandes_projet (nom, email, description, budget) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $email, $description, $budget]);
                $succes = true;
                $nom = $email = $description = $budget = '';
            }
        } else {
            // Formulaire de contact simple
            $nom = nettoyer($_POST['nom'] ?? '');
            $email = nettoyer($_POST['email'] ?? '');
            $message = nettoyer($_POST['message'] ?? '');

            if (!champ_requis($nom)) $erreurs[] = "Le nom est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "L'email n'est pas valide.";
            if (!champ_requis($message)) $erreurs[] = "Le message est obligatoire.";

            if (empty($erreurs)) {
                $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, message) VALUES (?, ?, ?)");
                $stmt->execute([$nom, $email, $message]);
                $succes = true;
                $nom = $email = $message = '';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Portfolio | Contact</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body class="container">
    <?php include '../composants/navigation.php'; ?>

    <main>
        <section class="contact">
            <h2>Contactez-moi</h2>

            <?php if ($succes === true && (!isset($_POST['form_type']) || $_POST['form_type'] !== 'demande')): ?>
                <p style="color: green; font-weight: bold;">✅ Votre message a été envoyé avec succès !</p>
            <?php endif; ?>

            <?php if (!empty($erreurs)): ?>
                <ul style="color: red;">
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($nom) ?>">

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>">

                <label for="message">Message :</label>
                <textarea name="message" id="message" required><?= htmlspecialchars($message ?? '') ?></textarea>

                <button type="submit">Envoyer</button>
            </form>

            <h2>Demande de projet</h2>

            <?php if ($succes === true && isset($_POST['form_type']) && $_POST['form_type'] === 'demande'): ?>
                <p style="color: green; font-weight: bold;">✅ Votre demande a été envoyée avec succès !</p>
            <?php endif; ?>

            <form method="POST" class="project-request">
                <input type="hidden" name="form_type" value="demande">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <label for="client_name">Nom :</label>
                <input type="text" id="client_name" name="client_name" required value="<?= htmlspecialchars($nom) ?>">

                <label for="client_email">Email :</label>
                <input type="email" id="client_email" name="client_email" required value="<?= htmlspecialchars($email) ?>">

                <label for="project_description">Description du projet :</label>
                <textarea name="project_description" id="project_description" required><?= htmlspecialchars($description) ?></textarea>

                <label for="budget">Budget estimé :</label>
                <input type="text" id="budget" name="budget" value="<?= htmlspecialchars($budget) ?>">

                <button type="submit">Envoyer la demande</button>
            </form>

            <div class="contact-info">
                <a href="tel:778382833"><i class="fa fa-phone"></i></a>
                <a href="mailto:romaricdossou18@gmail.com"><i class="fa fa-envelope"></i></a>
                <a href="https://www.linkedin.com/in/romaric-dossou-38343a3ab/" target="_blank"><i class="fa fa-linkedin"></i></a>
            </div>
        </section>
    </main>

    <?php include '../composants/pied-de-page.php'; ?>
</body>

</html>