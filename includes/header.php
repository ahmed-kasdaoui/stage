<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Projet de Fin d'Études</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/validation.js"></script>
</head>
<body>
    <header>
        <h1>mon projet</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard.php">Tableau de bord</a>
                <a href="/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="/login.php">Connexion</a>
                <a href="/register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
