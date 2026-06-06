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

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $erreur = "Erreur de sécurité. Veuillez réessayer.";
    } else {
        // Supprimer l'image si elle existe
        if ($projet['image'] && file_exists('../../' . $projet['image'])) {
            unlink('../../' . $projet['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM projets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $succes = "Projet supprimé avec succès !";

        // Redirection après 2 secondes
        header('refresh:2;url=index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer le projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Supprimer le projet</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <?php if ($succes): ?>
        <p style="color: green;"><?= htmlspecialchars($succes) ?> Redirection en cours...</p>
    <?php elseif ($erreur): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php else: ?>
        <p>Voulez-vous vraiment supprimer le projet : <strong><?= htmlspecialchars($projet['titre']) ?></strong> ?</p>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit">Oui, supprimer</button>
            <a href="index.php">Non, annuler</a>
        </form>
    <?php endif; ?>

    <p><a href="index.php">← Retour à la liste</a></p>
</body>

</html>