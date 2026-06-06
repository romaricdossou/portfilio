<?php
$projets = [
    [
        'titre' => 'Espace voyage',
        'description' => "Création de la page d'accueil d'un site de voyage pour présenter une destination et proposer une interface moderne, claire et attractive.",
        'technologies' => 'HTML, CSS',
        'image' => '../images/VOYAGE.png',
        'lien_github' => 'https://github.com/romaricdossou/ESPACE-VOYAGE'
    ],
    [
        'titre' => 'Card',
        'description' => "Réalisation d'une carte de présentation inspirée des interfaces modernes. Ce projet m'a permis de travailler la mise en page, l'alignement des contenus et la hiérarchie visuelle.",
        'technologies' => 'HTML, CSS',
        'image' => '../images/Card.PNG',
        'lien_github' => 'https://github.com/romaricdossou/CARD'
    ],
    [
        'titre' => 'Chat',
        'description' => "Développement d'une page simple autour du thème du chat pour pratiquer les bases du HTML et du CSS, structurer le contenu et améliorer la qualité visuelle d'une interface.",
        'technologies' => 'HTML, CSS',
        'image' => '../images/Chat.png',
        'lien_github' => 'https://github.com/romaricdossou/CAT'
    ]
];

require_once '../fonctions.php';
$mots_cles = nettoyer($_GET['q'] ?? '');
$resultats = [];
if ($mots_cles !== '') {
    foreach ($projets as $projet) {
        if (stripos($projet['titre'], $mots_cles) !== false || stripos($projet['description'], $mots_cles) !== false) {
            $resultats[] = $projet;
        }
    }
} else {
    $resultats = $projets;
}

?>

<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

// Enregistrer la visite
enregistrerVisite($pdo, basename($_SERVER['PHP_SELF']));
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Projets</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body class="container">
    <?php include '../composants/navigation.php'; ?>

    <main>
        <section class="projet">
            <form class="search-form" method="get" action="">
                <label for="search">Rechercher des projets par mots-clés :</label>
                <input type="text" id="search" name="q" placeholder="Entrez des mots-clés..." value="<?= htmlspecialchars($mots_cles) ?>">
                <button type="submit">Rechercher</button>
            </form>

            <div class="project-container">
                <?php foreach ($resultats as $projet): ?>
                    <article class="project-card">
                        <img src="<?= htmlspecialchars($projet['image']) ?>" alt="Aperçu du projet <?= htmlspecialchars($projet['titre']) ?>">
                        <h3><?= htmlspecialchars($projet['titre']) ?></h3>
                        <p><?= htmlspecialchars($projet['description']) ?></p>
                        <p class="project-tech">Technologies : <?= htmlspecialchars($projet['technologies']) ?></p>
                        <a href="<?= htmlspecialchars($projet['lien_github']) ?>" target="_blank" rel="noopener noreferrer">Voir le code sur GitHub</a>
                    </article>
                <?php endforeach; ?>

            </div>

            <?php if (empty($resultats)): ?>
                <p style="text-align: center;">Aucun projet ne correspond à ta recherche </p>
            <?php endif; ?>
        </section>
    </main>

    <?php include '../composants/pied-de-page.php'; ?>
</body>

</html>