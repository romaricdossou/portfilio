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

// Marquer le message comme lu
$stmt = $pdo->prepare("UPDATE messages_contact SET lu = 1 WHERE id = :id");
$stmt->execute([':id' => $id]);

// Récupérer le message
$stmt = $pdo->prepare("SELECT * FROM messages_contact WHERE id = :id");
$stmt->execute([':id' => $id]);
$message = $stmt->fetch();

if (!$message) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Message de <?= htmlspecialchars($message['nom']) ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Message de <?= htmlspecialchars($message['nom']) ?></h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <p><strong>Nom :</strong> <?= htmlspecialchars($message['nom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($message['email']) ?></p>
    <p><strong>Date :</strong> <?= htmlspecialchars($message['date_envoi']) ?></p>
    <p><strong>Message :</strong></p>
    <p style="border:1px solid #ccc; padding:10px;"><?= nl2br(htmlspecialchars($message['message'])) ?></p>

    <p><a href="index.php">← Retour à la liste</a></p>
</body>

</html>