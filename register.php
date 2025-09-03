<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

$errors = [];
$success = '';
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $num_telephone = trim($_POST['num_telephone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $localisation = trim($_POST['localisation'] ?? '');
    $roles = $_POST['roles'] ?? 'Etudiant';

    // Validation serveur (similaire à JS pour sécurité)
    if (empty($nom) || !preg_match('/^[a-zA-Zéèàêïöù ]+$/', $nom) || strlen($nom) < 3) {
        $errors['nom'] = "Nom invalide (lettres seulement, min 3 caractères).";
    }
    if (empty($prenom) || !preg_match('/^[a-zA-Zéèàêïöù ]+$/', $prenom) || strlen($prenom) < 3) {
        $errors['prenom'] = "Prénom invalide (lettres seulement, min 3 caractères).";
    }
    if (empty($date_naissance) || new DateTime($date_naissance) > new DateTime('-10 years')) {
        $errors['date_naissance'] = "Vous devez avoir au moins 10 ans.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide.";
    }
    if (empty($num_telephone) || !preg_match('/^\d{8}$/', $num_telephone)) {
        $errors['num_telephone'] = "Téléphone invalide (8 chiffres).";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors['password'] = "Mot de passe trop court (min 6 caractères).";
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
    }
    if (empty($genre)) {
        $errors['genre'] = "Genre requis.";
    }
    if (empty($localisation) || strlen($localisation) < 3) {
        $errors['localisation'] = "Localisation requise (min 3 caractères).";
    }
    if (empty($roles)) {
        $errors['roles'] = "Rôle requis.";
    }

    // Vérification email unique
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors['email'] = "Email déjà utilisé.";
    }

    // Vérification numéro unique
    $stmt = $pdo->prepare("SELECT id FROM users WHERE num_telephone = ?");
    $stmt->execute([$num_telephone]);
    if ($stmt->fetch()) {
        $errors['num_telephone'] = "Numéro de téléphone déjà utilisé.";
    }

    $image = 'inconnu.jpg';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $image = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "images/uploads/$image");
        } else {
            $errors['image'] = "Format d'image invalide (jpg, jpeg, png uniquement).";
        }
    }

    if (empty($errors)) {
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setDateNaissance($date_naissance);
        $user->setEmail($email);
        $user->setNumTelephone($num_telephone);
        $user->setPassword($password);
        $user->setConfirmPassword($confirm_password);
        $user->setImage($image);
        $user->setGenre($genre);
        $user->setLocalisation($localisation);
        $user->setRoles($roles);
        $user->setCreatedAt(date('Y-m-d H:i:s'));

        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, date_naissance, email, num_telephone, password, confirm_password, image, genre, localisation, created_at, roles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->getNom(), $user->getPrenom(), $user->getDateNaissance(), $user->getEmail(), $user->getNumTelephone(),
            $user->getPassword(), $user->getConfirmPassword(), $user->getImage(), $user->getGenre(), $user->getLocalisation(),
            $user->getCreatedAt(), $user->getRoles()
        ]);
        $success = "Inscription réussie !";
    }
}
?>

<h2>Inscription</h2>
<?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
<form id="registerForm" method="post" enctype="multipart/form-data">
    <input type="text" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>
    <input type="text" name="prenom" placeholder="Prénom" value="<?php echo htmlspecialchars($prenom ?? ''); ?>" required>
    <input type="date" name="date_naissance" value="<?php echo htmlspecialchars($date_naissance ?? ''); ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    <input type="text" name="num_telephone" placeholder="Téléphone" value="<?php echo htmlspecialchars($num_telephone ?? ''); ?>" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required>
    <select name="genre" required>
        <option value="">Sélectionner un genre</option>
        <option value="Homme" <?php echo isset($genre) && $genre === 'Homme' ? 'selected' : ''; ?>>Homme</option>
        <option value="Femme" <?php echo isset($genre) && $genre === 'Femme' ? 'selected' : ''; ?>>Femme</option>
    </select>
    <input type="text" name="localisation" placeholder="Localisation" value="<?php echo htmlspecialchars($localisation ?? ''); ?>" required>
    <select name="roles" required>
        <option value="">Sélectionner un rôle</option>
        <option value="Etudiant" <?php echo isset($roles) && $roles === 'Etudiant' ? 'selected' : ''; ?>>Étudiant</option>
        <option value="Enseignant" <?php echo isset($roles) && $roles === 'Enseignant' ? 'selected' : ''; ?>>Enseignant</option>
        <option value="Admin" <?php echo isset($roles) && $roles === 'Admin' ? 'selected' : ''; ?>>Admin</option>
    </select>
    <input type="file" name="image" accept="image/jpeg,image/png,image/jpg">
    <button type="submit">S'inscrire</button>
</form>


