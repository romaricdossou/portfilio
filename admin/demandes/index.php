<?php
session_start();
require_once '../../config/connexion.php';
require_once '../../fonctions.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

// Récupérer toutes les demandes
$stmt = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC");
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Demandes de projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="container">
    <h1>Demandes de projet</h1>

    <nav class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/index.php">Projets</a>
        <a href="../utilisateurs/index.php">Administrateurs</a>
        <a href="../messages/index.php">Messages</a>
        <a href="index.php">Demandes</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </nav>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Type de projet</th>
                <th>Budget</th>
                <th>Lu</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($demandes)): ?>
                <tr>
                    <td colspan="8">Aucune demande.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($demandes as $demande): ?>
                    <tr style="<?= $demande['lu'] ? '' : 'font-weight: bold; background-color: #f9f9f9;' ?>">
                        <td><?= $demande['id'] ?></td>
                        <td><?= htmlspecialchars($demande['nom']) ?></td>
                        <td><?= htmlspecialchars($demande['email']) ?></td>
                        <td><?= htmlspecialchars($demande['type_projet']) ?></td>
                        <td><?= htmlspecialchars($demande['budget'] ?: 'Non précisé') ?></td>
                        <td><?= $demande['lu'] ? '✅ Lu' : '❌ Non lu' ?></td>
                        <td><?= $demande['date_demande'] ?></td>
                        <td>
                            <a href="voir.php?id=<?= $demande['id'] ?>">👁️ Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>