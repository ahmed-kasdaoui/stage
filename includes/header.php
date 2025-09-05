<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Projet de Fin d'Études</title>
    <link rel="stylesheet" href="/stage/css/style.css">
    <script src="/stagejs/validation.js"></script>
</head>
<body>
    <header>
        <h1>mon projet</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="http://localhost/stage/dashboard.php">Tableau de bord</a>
                <a href="http://localhost/stage/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="http://localhost/stage/login.php">Connexion</a>
                <a href="http://localhost/stage/register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
