<?php

function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}

/**
 * Enregistre une visite dans la table visites
 */
function enregistrerVisite($pdo, $page)
{
    // Récupérer l'adresse IP du visiteur
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    // Si derrière un proxy (ex: hébergement)
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    }

    $stmt = $pdo->prepare('INSERT INTO visites (adresse_ip, page, date_visite) VALUES (:ip, :page, NOW())');
    $stmt->execute([
        ':ip' => $ip,
        ':page' => $page
    ]);
}