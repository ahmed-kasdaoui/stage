<?php
require_once 'includes/config.php';


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
            header("Location: test.php");
            exit();
        } else {
            $errors['login'] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!doctype html>
<html lang="en"> 
<head> 
<meta charset="UTF-8"> 
<title>Connexion</title> 
<style>
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap');
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Quicksand', sans-serif; }
body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #000; }
section { position: absolute; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center; gap: 2px; flex-wrap: wrap; overflow: hidden; }
section::before { content: ''; position: absolute; width: 100%; height: 100%; background: linear-gradient(#000,#0f0,#000); animation: animate 5s linear infinite; }
@keyframes animate { 0% { transform: translateY(-100%); } 100% { transform: translateY(100%); } }
section span { position: relative; display: block; width: calc(6.25vw - 2px); height: calc(6.25vw - 2px); background: #181818; z-index: 2; transition: 1.5s; }
section span:hover { background: #0f0; transition: 0s; }
section .signin { position: absolute; width: 400px; background: #222; z-index: 1000; display: flex; justify-content: center; align-items: center; padding: 40px; border-radius: 4px; box-shadow: 0 15px 35px rgba(0,0,0,0.9); }
section .signin .content { position: relative; width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 20px; }
section .signin .content h2 { font-size: 2em; color: #0f0; text-transform: uppercase; }
section .signin .content .form { width: 100%; display: flex; flex-direction: column; gap: 20px; }
section .signin .content .form .inputBox { position: relative; width: 100%; }
section .signin .content .form .inputBox input { position: relative; width: 100%; background: #333; border: none; outline: none; padding: 15px 10px; border-radius: 4px; color: #fff; font-weight: 500; font-size: 1em; }
section .signin .content .form .inputBox i { position: absolute; left: 0; padding: 15px 10px; font-style: normal; color: #aaa; transition: 0.5s; pointer-events: none; }
.signin .content .form .inputBox input:focus ~ i, .signin .content .form .inputBox input:valid ~ i { transform: translateY(-7.5px); font-size: 0.8em; color: #fff; }
.signin .content .form .inputBox input[type="submit"] { padding: 10px; background: #0f0; color: #000; font-weight: 600; font-size: 1.2em; cursor: pointer; border-radius: 4px; }
.signin .content .form .inputBox input[type="submit"]:active { opacity: 0.6; }
.signin .content .errors { color: red; text-align: center; }
@media (max-width: 900px) { section span { width: calc(10vw - 2px); height: calc(10vw - 2px); } }
@media (max-width: 600px) { section span { width: calc(20vw - 2px); height: calc(20vw - 2px); } }
</style>
</head>
<body>
<section>
    <!-- Background squares -->
    <?php for($i=0;$i<200;$i++): ?><span></span><?php endfor; ?>

    <div class="signin">
        <div class="content">
            <h2>Connexion</h2>

            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <?php foreach ($errors as $error): echo htmlspecialchars($error) . "<br>"; endforeach; ?>
                </div>
            <?php endif; ?>

           <form class="form" method="POST">
    <div class="inputBox">
        <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        <i>Email</i>
    </div>

    <div class="inputBox">
        <input type="password" name="password" required>
        <i>Mot de passe</i>
    </div>

    <div class="inputBox">
        <input type="submit" value="Se connecter">
    </div>

    <!-- Nouveau bouton S'inscrire -->
    <div class="inputBox">
        <a href="http://localhost/stage/register.php" 
           style="display:block; text-align:center; text-decoration:none; background:#0f0; color:#000; padding:10px; border-radius:4px; font-weight:600; margin-top:10px;">
           S'inscrire
        </a>
    </div>
</form>


        </div>
    </div>
</section>
</body>
</html>
