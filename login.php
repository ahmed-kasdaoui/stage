<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation serveur
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors['password'] = "Mot de passe trop court (min 6 caractères).";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['roles'] = $userData['roles'];
            header("Location: dashboard.php");
            exit();
        } else {
            $errors['login'] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<h2>Connexion</h2>
<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <?php foreach ($errors as $error): echo htmlspecialchars($error) . "<br>"; endforeach; ?>
    </div>
<?php endif; ?>
<form id="loginForm" method="post">
    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<?php require_once 'includes/footer.php'; ?>
