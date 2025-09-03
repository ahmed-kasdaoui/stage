<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

if (!$pdo) {
    error_log("Échec de la connexion à la base de données à " . date('Y-m-d H:i:s'));
    echo json_encode(['exists' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit;
}

$response = ['exists' => false, 'message' => ''];

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    error_log("Requête email reçue : $email à " . date('Y-m-d H:i:s'));
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $exists = $stmt->fetchColumn();
        $response['exists'] = ($exists > 0);
        $response['message'] = $exists > 0 ? 'Cet email est déjà utilisé.' : '';
    } else {
        $response['message'] = 'Format d\'email invalide.';
    }
} elseif (isset($_GET['num_telephone'])) {
    $num_telephone = trim($_GET['num_telephone']);
    error_log("Requête num_telephone reçue : $num_telephone à " . date('Y-m-d H:i:s'));
    if (!empty($num_telephone) && preg_match('/^\d{8}$/', $num_telephone)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE num_telephone = ?");
        $stmt->execute([$num_telephone]);
        $exists = $stmt->fetchColumn();
        $response['exists'] = ($exists > 0);
        $response['message'] = $exists > 0 ? 'Ce numéro est déjà utilisé.' : '';
    } else {
        $response['message'] = 'Numéro invalide (doit contenir 8 chiffres).';
    }
} else {
    error_log("Aucune requête valide reçue à " . date('Y-m-d H:i:s'));
    $response['message'] = 'Requête invalide.';
}

echo json_encode($response);
exit;
?>
