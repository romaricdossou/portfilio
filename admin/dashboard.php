<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit;
}

// Nombre total de projets
$stmt = $pdo->query("SELECT COUNT(*) as total FROM projets");
$totalProjets = $stmt->fetch()['total'];

// Nombre de messages non lus
$stmt = $pdo->query("SELECT COUNT(*) as total FROM messages_contact WHERE lu = 0");
$messagesNonLus = $stmt->fetch()['total'];

// Nombre de demandes non lues
$stmt = $pdo->query("SELECT COUNT(*) as total FROM demandes_projet WHERE lu = 0");
$demandesNonLues = $stmt->fetch()['total'];

// Nombre total de messages
$stmt = $pdo->query("SELECT COUNT(*) as total FROM messages_contact");
$totalMessages = $stmt->fetch()['total'];

// Nombre total de demandes
$stmt = $pdo->query("SELECT COUNT(*) as total FROM demandes_projet");
$totalDemandes = $stmt->fetch()['total'];

// 5 dernières visites
$stmt = $pdo->query("SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5");
$dernieresVisites = $stmt->fetchAll();

// 5 dernières demandes de projet
$stmt = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5");
$dernieresDemandes = $stmt->fetchAll();

// 5 derniers messages
$stmt = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC LIMIT 5");
$derniersMessages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Administration</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="container">
    <h1>Dashboard</h1>

    <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['admin_prenom']) ?></strong> !</p>

    <nav class="admin-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="projets/index.php">Projets</a>
        <a href="utilisateurs/index.php">Administrateurs</a>
        <a href="messages/index.php">Messages</a>
        <a href="demandes/index.php">Demandes</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>

    <!-- Statistiques -->
    <div class="stats" style="display: flex; gap: 20px; margin: 20px 0;">
        <div style="border:1px solid #ccc; padding: 15px; text-align: center;">
            <h2><?= $totalProjets ?></h2>
            <p>Projets</p>
        </div>
        <div style="border:1px solid #ccc; padding: 15px; text-align: center;">
            <h2><?= $messagesNonLus ?></h2>
            <p>Messages non lus</p>
            <small>Total: <?= $totalMessages ?></small>
        </div>
        <div style="border:1px solid #ccc; padding: 15px; text-align: center;">
            <h2><?= $demandesNonLues ?></h2>
            <p>Demandes non lues</p>
            <small>Total: <?= $totalDemandes ?></small>
        </div>
    </div>

    <!-- 5 dernières visites -->
    <div class="dernieres-visites">
        <h2>5 dernières visites</h2>
        <?php if (empty($dernieresVisites)): ?>
            <p>Aucune visite enregistrée.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Adresse IP</th>
                        <th>Page visitée</th>
                        <th>Date et heure</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieresVisites as $visite): ?>
                        <tr>
                            <td><?= htmlspecialchars($visite['adresse_ip']) ?></td>
                            <td><?= htmlspecialchars($visite['page']) ?></td>
                            <td><?= htmlspecialchars($visite['date_visite']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- 5 dernières demandes -->
    <div class="dernieres-demandes">
        <h2>5 dernières demandes de projet</h2>
        <?php if (empty($dernieresDemandes)): ?>
            <p>Aucune demande reçue.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Type de projet</th>
                        <th>Budget</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieresDemandes as $demande): ?>
                        <tr>
                            <td><?= htmlspecialchars($demande['nom']) ?></td>
                            <td><?= htmlspecialchars($demande['email']) ?></td>
                            <td><?= htmlspecialchars($demande['type_projet']) ?></td>
                            <td><?= htmlspecialchars($demande['budget'] ?: 'Non précisé') ?></td>
                            <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                            <td><?= $demande['lu'] ? '✅ Lu' : '❌ Non lu' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><a href="demandes/index.php">Voir toutes les demandes →</a></p>
        <?php endif; ?>
    </div>

    <!-- 5 derniers messages -->
    <div class="derniers-messages">
        <h2>5 derniers messages</h2>
        <?php if (empty($derniersMessages)): ?>
            <p>Aucun message reçu.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($derniersMessages as $message): ?>
                        <tr>
                            <td><?= htmlspecialchars($message['nom']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td><?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($message['date_envoi']) ?></td>
                            <td><?= $message['lu'] ? '✅ Lu' : '❌ Non lu' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><a href="messages/index.php">Voir tous les messages →</a></p>
        <?php endif; ?>
    </div>
</body>

</html>