//créer le formulaire de connexion pour l'espace d'administration.
<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

$erreurs = '';

//si deja connecté, rediriger vers le dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

//Generation du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] ===  'POST'){
    //Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
        {
        $erreur = 'Erreur de sécurité. Veuillez réessayer.';
}else {
    $email = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        //conexion réussie
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_prenom'] = $admin['prenom'];
        $_SESSION['admin_nom'] = $admin['nom'];
        header('Location: dashboard.php');
        exit;
    } else {
        //Message generique (ne precise pas si c'est email ou mot de passe)
        $erreurs = 'Email ou mot de passe incorrect.';
    }
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-login">
    <h1>Connexion Administration</h1>
    <?php if ($erreurs): ?>
        <p class="erreur"><?= htmlspecialchars($erreurs) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <button type="submit">Se connecter</button>
        
    </form>

    <p><a href="../index.php"><- Retour au site</a></p>
</div>
</body>
</html>