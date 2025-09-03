<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
<p>Rôle : <?php echo htmlspecialchars($_SESSION['roles']); ?></p>
<a href="logout.php">Déconnexion</a>

<?php require_once 'includes/footer.php'; ?>
