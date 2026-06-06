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
    <title>Portfolio | À propos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body class="container">
    <?php include '../composants/navigation.php'; ?>

    <main>
        <section class="about">
            <h2>À propos de moi</h2>
            <div class="about-container">
                <div class="about-card">
                    <h3>Parcours académique</h3>
                    <p>
                        2007 - 2020 : Élève à Mère Jean Louis Dieng, avec l'obtention du CFE et du BFEM.<br>
                        2020 - 2024 : Lycéen au Complexe Académique de Dakar, série scientifique.
                    </p>
                </div>
                <div class="about-card">
                    <h3>Compétences transversales</h3>
                    <p>
                        Communication, sens de l'organisation, curiosité technique et capacité à apprendre
                        rapidement.<br>
                        Travail d'équipe et résolution de problèmes dans un contexte de projet.
                    </p>
                </div>
                <div class="about-card">
                    <h3>Compétences techniques</h3>
                    <p>
                        HTML : Intermédiaire<br>
                        CSS : Intermédiaire<br>
                        JavaScript : Débutant<br>
                        Git / GitHub : Bases solides
                    </p>
                </div>
                <div class="about-card">
                    <h3>Expériences</h3>
                    <p>
                        Réalisation de projets académiques en développement web, avec mise en page responsive,
                        intégration d'interfaces et structuration de contenus.<br>
                        Participation à des travaux pratiques et à des réalisations individuelles pour renforcer mes
                        compétences.
                    </p>
                </div>
                <div class="about-card center">
                    <h3>Centres d'intérêt</h3>
                    <p>
                        Innovation technologique, apprentissage continu, projets open-source, lecture technique, sport et voyages.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <?php include '../composants/pied-de-page.php'; ?>
</body>

</html>