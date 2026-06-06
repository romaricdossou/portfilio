<?php
session_start();
require_once '../../config/connexion.php';
require_once '../../fonctions.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

// Récupérer tous les messages
$stmt = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Messages</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Messages de contact</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Lu</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="7">Aucun message.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <tr style="<?= $message['lu'] ? '' : 'font-weight: bold; background-color: #f9f9f9;' ?>">
                        <td><?= $message['id'] ?></td>
                        <td><?= htmlspecialchars($message['nom']) ?></td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars(substr($message['message'], 0, 100))) ?>...</td>
                        <td><?= $message['lu'] ? '✅ Lu' : '❌ Non lu' ?></td>
                        <td><?= $message['date_envoi'] ?></td>
                        <td>
                            <a href="voir.php?id=<?= $message['id'] ?>">👁️ Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>