<?php
session_start();
require_once '../../config/connexion.php';
require_once '../../fonctions.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

// Récupérer tous les administrateurs
$stmt = $pdo->query("SELECT * FROM administrateurs ORDER BY date_creation DESC");
$admins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des administrateurs</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Gestion des administrateurs</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="../demandes/index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <p><a href="creer.php">➕ Ajouter un administrateur</a></p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($admins)): ?>
                <tr>
                    <td colspan="6">Aucun administrateur.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= $admin['id'] ?></td>
                        <td><?= htmlspecialchars($admin['prenom']) ?></td>
                        <td><?= htmlspecialchars($admin['nom']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td><?= $admin['date_creation'] ?></td>
                        <td>
                            <a href="modifier.php?id=<?= $admin['id'] ?>">✏️ Modifier</a>
                            <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                <a href="supprimer.php?id=<?= $admin['id'] ?>" onclick="return confirm('Supprimer cet administrateur ?')">🗑️ Supprimer</a>
                            <?php else: ?>
                                <span style="color:gray;">(Vous-même)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="../dashboard.php">← Retour au dashboard</a></p>
</body>

</html>