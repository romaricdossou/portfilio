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

// Marquer la demande comme lue
$stmt = $pdo->prepare("UPDATE demandes_projet SET lu = 1 WHERE id = :id");
$stmt->execute([':id' => $id]);

// Récupérer la demande
$stmt = $pdo->prepare("SELECT * FROM demandes_projet WHERE id = :id");
$stmt->execute([':id' => $id]);
$demande = $stmt->fetch();

if (!$demande) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demande de <?= htmlspecialchars($demande['nom']) ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Demande de projet</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <p><strong>Nom :</strong> <?= htmlspecialchars($demande['nom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($demande['email']) ?></p>
    <p><strong>Type de projet :</strong> <?= htmlspecialchars($demande['type_projet']) ?></p>
    <p><strong>Budget :</strong> <?= htmlspecialchars($demande['budget'] ?: 'Non précisé') ?></p>
    <p><strong>Date :</strong> <?= htmlspecialchars($demande['date_demande']) ?></p>
    <p><strong>Description :</strong></p>
    <p style="border:1px solid #ccc; padding:10px;"><?= nl2br(htmlspecialchars($demande['description'])) ?></p>

    <p><a href="index.php">← Retour à la liste</a></p>
</body>

</html>