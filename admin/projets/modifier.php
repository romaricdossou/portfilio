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
$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
$stmt->execute([':id' => $id]);
$projet = $stmt->fetch();

if (!$projet) {
    header('Location: index.php');
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = nettoyer($_POST['titre'] ?? '');
    $description = nettoyer($_POST['description'] ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');
    $lien = nettoyer($_POST['lien'] ?? '');

    // Gestion de l'upload d'image
    $image = $projet['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier = '../../images/projets/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $nom_fichier = uniqid() . '.' . $extension;
        $chemin = $dossier . $nom_fichier;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $chemin)) {
            // Supprimer l'ancienne image si elle existe
            if ($projet['image'] && file_exists('../../' . $projet['image'])) {
                unlink('../../' . $projet['image']);
            }
            $image = 'images/projets/' . $nom_fichier;
        } else {
            $erreur = "Erreur lors de l'upload de l'image.";
        }
    }

    if (empty($erreur)) {
        if (!champ_requis($titre)) $erreur = "Le titre est obligatoire.";
        elseif (!champ_requis($description)) $erreur = "La description est obligatoire.";
        elseif (!champ_requis($technologies)) $erreur = "Les technologies sont obligatoires.";
        else {
            $stmt = $pdo->prepare("UPDATE projets SET titre = :titre, description = :description, technologies = :technologies, image = :image, lien = :lien WHERE id = :id");
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':technologies' => $technologies,
                ':image' => $image,
                ':lien' => $lien,
                ':id' => $id
            ]);
            $succes = "Projet modifié avec succès !";

            // Recharger les données
            $stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $projet = $stmt->fetch();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier le projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Modifier le projet</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <?php if ($succes): ?>
        <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
    <?php endif; ?>

    <?php if ($erreur): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="titre">Titre * :</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>

        <label for="description">Description * :</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($projet['description']) ?></textarea>

        <label for="technologies">Technologies * :</label>
        <input type="text" name="technologies" id="technologies" value="<?= htmlspecialchars($projet['technologies']) ?>" required>

        <label for="image">Image (laisser vide pour conserver l'actuelle) :</label>
        <?php if ($projet['image']): ?>
            <p><img src="../../<?= htmlspecialchars($projet['image']) ?>" width="100" alt=""></p>
        <?php endif; ?>
        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image/webp">

        <label for="lien">Lien GitHub :</label>
        <input type="text" name="lien" id="lien" value="<?= htmlspecialchars($projet['lien']) ?>">

        <button type="submit">Modifier le projet</button>
    </form>

    <p><a href="index.php">← Retour à la liste</a></p>
</body>

</html>