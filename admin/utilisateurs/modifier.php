<?php
session_start();
require_once '../../config/connexion.php';
require_once '../../fonctions.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = :id");
$stmt->execute([':id' => $id]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: index.php');
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = nettoyer($_POST['prenom'] ?? '');
    $nom = nettoyer($_POST['nom'] ?? '');
    $email = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (!champ_requis($prenom)) $erreur = "Le prénom est obligatoire.";
    elseif (!champ_requis($nom)) $erreur = "Le nom est obligatoire.";
    elseif (!champ_requis($email)) $erreur = "L'email est obligatoire.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreur = "L'email n'est pas valide.";
    else {
        // Vérifier si l'email existe déjà pour un autre administrateur
        $stmt = $pdo->prepare("SELECT id FROM administrateurs WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé par un autre administrateur.";
        } else {
            // Construction de la requête UPDATE
            if (!empty($mot_de_passe)) {
                if (strlen($mot_de_passe) < 6) {
                    $erreur = "Le mot de passe doit faire au moins 6 caractères.";
                } else {
                    $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE administrateurs SET prenom = :prenom, nom = :nom, email = :email, mot_de_passe = :mdp WHERE id = :id");
                    $stmt->execute([
                        ':prenom' => $prenom,
                        ':nom' => $nom,
                        ':email' => $email,
                        ':mdp' => $hash,
                        ':id' => $id
                    ]);
                    $succes = "Administrateur modifié avec succès !";
                }
            } else {
                $stmt = $pdo->prepare("UPDATE administrateurs SET prenom = :prenom, nom = :nom, email = :email WHERE id = :id");
                $stmt->execute([
                    ':prenom' => $prenom,
                    ':nom' => $nom,
                    ':email' => $email,
                    ':id' => $id
                ]);
                $succes = "Administrateur modifié avec succès !";
            }

            if ($succes) {
                // Recharger les données
                $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $admin = $stmt->fetch();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un administrateur</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Modifier un administrateur</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <?php if ($succes): ?>
        <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
        <p><a href="index.php">← Retour à la liste</a></p>
    <?php else: ?>
        <?php if ($erreur): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="prenom">Prénom * :</label>
            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" required>

            <label for="nom">Nom * :</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required>

            <label for="email">Email * :</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

            <label for="mot_de_passe">Nouveau mot de passe (laisser vide pour conserver l'ancien) :</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe">

            <button type="submit">Modifier</button>
        </form>

        <p><a href="index.php">← Retour à la liste</a></p>
    <?php endif; ?>
</body>

</html>