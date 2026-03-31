<?php
session_start();

require_once __DIR__ . '/../../inc/connexion.php';

// Si déjà connecté, rediriger vers articles
if (isset($_SESSION['user_id'])) {
    header('Location: articles.php');
    exit;
}

$error = '';

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if (!empty($identifiant) && !empty($mdp)) {
        try {
            $pdo = getConnexion();
            $stmt = $pdo->prepare('SELECT * FROM Utilisateur WHERE identifiant = ?');
            $stmt->execute([$identifiant]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['mdp'] === $mdp) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                $_SESSION['user_identifiant'] = $user['identifiant'];
                header('Location: articles.php');
                exit;
            } else {
                $error = 'Identifiant ou mot de passe incorrect';
            }
        } catch (Exception $e) {
            $error = 'Erreur lors de la connexion';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Backoffice</title>
    <link rel="stylesheet" href="../../assets/css/styleBO.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>L'Echo</h1>
            <p class="login-subtitle">Connexion administrative</p>

            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="identifiant">Identifiant</label>
                    <input
                        type="text"
                        id="identifiant"
                        name="identifiant"
                        value="boss.admin"
                        autofocus
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input
                        type="password"
                        id="mdp"
                        name="mdp"
                        value="admin"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">Connexion</button>
            </form>
            
            <div class="back-to-site">
                <a href="/pages/frontoffice/home.php"><- Retour au site</a>
            </div>
        </div>
    </div>
</body>
</html>
