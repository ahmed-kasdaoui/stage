<?php

require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;text-align:center; font-size:18px; margin-top:20px;'>
            ⚠️ Vous devez être connecté pour soumettre un projet.
          </p>";
    exit; // ✅ STOPPE le script ici → rien d’autre ne s’affiche
}

// Vérifier si l'utilisateur est connecté
$sql = "SELECT id, project_title, project_description, project_files, funding_goal, project_category, created_at 
        FROM projet ORDER BY created_at DESC LIMIT 6";
$stmt = $pdo->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id =  $_SESSION['user_id'];
    $title = trim($_POST['project_title']);
    $description = trim($_POST['project_description']);
    $funding_goal = !empty($_POST['funding_goal']) ? $_POST['funding_goal'] : null;
    $category = !empty($_POST['project_category']) ? $_POST['project_category'] : null;

    // Gestion des fichiers uploadés
    $uploaded_files = [];
    if (!empty($_FILES['project_files']['name'][0])) {
        $upload_dir = "uploads/projects/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['project_files']['name'] as $key => $filename) {
            $tmp_name = $_FILES['project_files']['tmp_name'][$key];
            $target_path = $upload_dir . uniqid() . "_" . basename($filename);
            if (move_uploaded_file($tmp_name, $target_path)) {
                $uploaded_files[] = $target_path;
            }
        }
    }

    // Sauvegarde chemins en JSON
    $files_json = !empty($uploaded_files) ? json_encode($uploaded_files) : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO projet 
            (project_title, project_description, project_files, funding_goal, project_category, user_id) 
            VALUES (:title, :description, :files, :goal, :category, :user_id)");

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':files' => $files_json,
            ':goal' => $funding_goal,
            ':category' => $category,
            ':user_id' => $user_id
        ]);

        echo "<p style='color:green;text-align:center;'>✅ Projet ajouté avec succès !</p>";

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
   
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ProjectHub — Votre vision, notre plateforme</title>
  <style>
    :root{
      --primary: #6366f1;
      --primary-dark: #4338ca;
      --secondary: #f59e0b;
      --accent: #ec4899;
      --success: #10b981;
      --dark: #1f2937;
      --light: #f8fafc;
      --muted: #64748b;
      --border: #e2e8f0;
      --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-warm: linear-gradient(135deg, #ff9a56 0%, #ff6b95 100%);
    }
    
    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--dark);
      background: var(--light);
      line-height: 1.6;
    }
    
    /* Header moderne */
    .header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.3s ease;
    }
    
    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 2rem;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: 800;
      font-size: 1.5rem;
      color: var(--primary);
    }
    
    .logo-icon {
      width: 36px;
      height: 36px;
      background: var(--gradient);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .nav-links {
      display: flex;
      gap: 2rem;
      align-items: center;
    }
    
    .nav-links a {
      text-decoration: none;
      color: var(--muted);
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .nav-links a:hover {
      color: var(--primary);
      transform: translateY(-1px);
    }
    
    .btn-primary {
      background: var(--gradient);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }
    
    /* Hero section créative */
    .hero {
      background: var(--gradient);
      position: relative;
      overflow: hidden;
      min-height: 70vh;
      display: flex;
      align-items: center;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    
    .hero-content {
      max-width: 1400px;
      margin: 0 auto;
      padding: 4rem 2rem;
      position: relative;
      z-index: 1;
      color: white;
      text-align: center;
    }
    
    .hero h1 {
      font-size: clamp(2.5rem, 5vw, 4rem);
      font-weight: 800;
      margin-bottom: 1.5rem;
      background: linear-gradient(45deg, #ffffff, #e0e7ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .hero-cta {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .btn-secondary {
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 0.75rem 2rem;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }
    
    /* Section soumission de projet */
    .submit-section {
      padding: 5rem 2rem;
      background: white;
      position: relative;
    }
    
    .submit-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .section-header {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .section-header h2 {
      font-size: 2.5rem;
      font-weight: 800;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1rem;
    }
    
    .section-header p {
      font-size: 1.1rem;
      color: var(--muted);
      max-width: 500px;
      margin: 0 auto;
    }
    
    /* Formulaire moderne */
    .project-form {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      padding: 3rem;
      border-radius: 24px;
      border: 1px solid var(--border);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .form-group {
      margin-bottom: 2rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.75rem;
      font-weight: 600;
      color: var(--dark);
      font-size: 1.1rem;
    }
    
    .form-control {
      width: 100%;
      padding: 1rem 1.5rem;
      border: 2px solid var(--border);
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
      transform: translateY(-1px);
    }
    
    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }
    
    .file-upload {
      position: relative;
      display: inline-block;
      width: 100%;
    }
    
    .file-upload input[type="file"] {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }
    
    .file-upload-label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      padding: 2rem;
      border: 2px dashed var(--border);
      border-radius: 12px;
      background: white;
      cursor: pointer;
      transition: all 0.3s ease;
      min-height: 120px;
    }
    
    .file-upload-label:hover {
      border-color: var(--primary);
      background: rgba(99, 102, 241, 0.02);
    }
    
    .upload-icon {
      font-size: 2rem;
      color: var(--primary);
    }
    
    .upload-text {
      color: var(--muted);
      font-weight: 500;
    }
    
    .upload-text strong {
      color: var(--primary);
    }
    
    /* Section projets en vedette */
    .featured-section {
      padding: 5rem 2rem;
      background: var(--light);
    }
    
    .featured-container {
      max-width: 1400px;
      margin: 0 auto;
    }
    
    .projects-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }
    
    .project-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border: 1px solid var(--border);
    }
    
    .project-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }
    
    .project-image {
      height: 200px;
      background: var(--gradient-warm);
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
    }
    
    .project-content {
      padding: 2rem;
    }
    
    .project-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .project-description {
      color: var(--muted);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }
    
    .project-stats {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .project-amount {
      font-weight: 700;
      color: var(--success);
      font-size: 1.1rem;
    }
    
    .project-backers {
      color: var(--muted);
      font-size: 0.9rem;
    }
    
    /* Footer */
    .footer {
      background: var(--dark);
      color: white;
      padding: 3rem 2rem 1rem;
      text-align: center;
    }
    
    .footer-content {
      max-width: 1400px;
      margin: 0 auto;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }
      
      .hero-cta {
        flex-direction: column;
        align-items: center;
      }
      
      .project-form {
        padding: 2rem 1.5rem;
      }
      
      .projects-grid {
        grid-template-columns: 1fr;
      }
    }
    
    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .project-card {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .project-card:nth-child(1) { animation-delay: 0.1s; }
    .project-card:nth-child(2) { animation-delay: 0.2s; }
    .project-card:nth-child(3) { animation-delay: 0.3s; }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="nav-container">
      <div class="logo">
        <div class="logo-icon">P</div>
        ProjectHub
      </div>
      <nav class="nav-links">
        <a href="#explore">Explorer</a>
        <a href="#submit">Soumettre</a>
        <a href="#about">À propos</a>
        <a href="#contact">Contact</a>
        <a href="http://localhost/stage/logout.php" class="btn-primary">Déconnexion</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Donnez vie à vos projets</h1>
      <p>La plateforme moderne qui connecte les créateurs visionnaires avec une communauté prête à soutenir l'innovation.</p>
      <div class="hero-cta">
        <a href="#submit" class="btn-primary">Soumettre un projet</a>
        <a href="#explore" class="btn-secondary">Explorer les projets</a>
      </div>
    </div>
  </section>

  <!-- Section soumission de projet -->
  <section id="submit" class="submit-section">
    <div class="submit-container">
      <div class="section-header">
        <h2>Soumettez votre projet</h2>
        <p>Partagez votre vision avec notre communauté et donnez-lui vie grâce au financement participatif.</p>
      </div>
      
      <form class="project-form" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="project-title">Titre du projet *</label>
          <input type="text" id="project-title" name="project_title" class="form-control" 
                 placeholder="Donnez un nom captivant à votre projet" required>
        </div>
        
        <div class="form-group">
          <label for="project-description">Description du projet *</label>
          <textarea id="project-description" name="project_description" class="form-control" 
                    placeholder="Décrivez votre projet en détail : objectifs, impact, pourquoi il mérite d'être soutenu..."
                    required></textarea>
        </div>
        
        <div class="form-group">
          <label for="project-file">Fichiers du projet</label>
          <div class="file-upload">
            <input type="file" id="project-file" name="project_files[]" multiple 
                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4,.mp3">
            <label for="project-file" class="file-upload-label">
              <div class="upload-icon">📁</div>
              <div class="upload-text">
                <strong>Cliquez pour uploader</strong> ou glissez vos fichiers ici<br>
                <small>PDF, images, vidéos, documents acceptés</small>
              </div>
            </label>
          </div>
        </div>
        
        <div class="form-group">
          <label for="funding-goal">Objectif de financement (€)</label>
          <input type="number" id="funding-goal" name="funding_goal" class="form-control" 
                 placeholder="10000" min="100">
        </div>
        
        <div class="form-group">
          <label for="project-category">Catégorie</label>
          <select id="project-category" name="project_category" class="form-control">
            <option value="">Sélectionnez une catégorie</option>
            <option value="tech">Technologie</option>
            <option value="art">Art & Design</option>
            <option value="social">Impact Social</option>
            <option value="business">Business</option>
            <option value="education">Éducation</option>
            <option value="health">Santé</option>
            <option value="environment">Environnement</option>
          </select>
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
          🚀 Soumettre mon projet
        </button>
      </form>
    </div>
  </section>

  <!-- Section projets en vedette -->
  <?php
require_once "includes/config.php"; // connexion PDO

// Récupérer les projets
$sql = "SELECT id, project_title, project_description, project_files, funding_goal, project_category, created_at 
        FROM projet ORDER BY created_at DESC LIMIT 6";
$stmt = $pdo->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="explore" class="featured-section">
  <div class="featured-container">
    <div class="section-header">
      <h2>Projets en vedette</h2>
      <p>Découvrez les projets les plus prometteurs de notre communauté</p>
    </div>

    <div class="projects-grid">
      <?php foreach ($projects as $project): ?>
        <?php
          // image par défaut si aucun fichier image trouvé
          $imagePath = 'assets/default.png';

          if (!empty($project['project_files'])) {
              // projet_files est stocké en JSON (ex: ["uploads\/projects\/file.png"])
              $files = json_decode($project['project_files'], true);

              // si json_decode échoue, essaie d'enlever les slashs et retenter
              if (!is_array($files)) {
                  $files = @json_decode(stripslashes($project['project_files']), true);
              }

              if (is_array($files)) {
                  foreach ($files as $file) {
                      $file = trim($file, "\"' \t\n\r");
                      // sécurité : pas de remontée de dossier
                      if (strpos($file, '..') !== false) continue;

                      // vérifier extension image
                      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                      if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) continue;

                      // candidate path (tel que stocké, ex: uploads/projects/xxx.png)
                      $candidate = $file;

                      // vérifier sur le filesystem (doc root)
                      $serverCandidate = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($candidate, '/');
                      if (file_exists($serverCandidate)) {
                          $imagePath = $candidate;
                          break;
                      }

                      // parfois le DB contient seulement le nom de fichier -> essayer uploads/projects/
                      $candidate2 = 'uploads/projects/' . basename($file);
                      if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $candidate2)) {
                          $imagePath = $candidate2;
                          break;
                      }

                      // essayer __DIR__ (si tu exécutes dans un sous-dossier)
                      if (file_exists(__DIR__ . '/' . ltrim($candidate, '/'))) {
                          $imagePath = $candidate;
                          break;
                      }
                  }
              } else {
                  // fallback : extraire un chemin uploads/... dans la chaîne
                  if (preg_match('/(uploads\/[^\s"\']+\.(?:png|jpe?g|gif|webp))/i', $project['project_files'], $m)) {
                      $cand = $m[1];
                      if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $cand) || file_exists(__DIR__ . '/' . $cand)) {
                          $imagePath = $cand;
                      }
                  }
              }
          }
        ?>

        <div class="project-card">
          <div class="project-image" style="overflow:hidden;">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($project['project_title']) ?>"
                 style="width:100%; height:200px; object-fit:cover; display:block;">
          </div>

          <div class="project-content">
            <h3 class="project-title"><?= htmlspecialchars($project['project_title']) ?></h3>
            <p class="project-description"><?= nl2br(htmlspecialchars($project['project_description'])) ?></p>
            <div class="project-stats">
              <span class="project-amount">
                <?= !empty($project['funding_goal']) ? 'Objectif : €' . number_format($project['funding_goal'], 2, ',', ' ') : '' ?>
              </span>
              <span class="project-backers"><?= htmlspecialchars($project['project_category']) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <p>&copy; 2025 ProjectHub. Propulsé par la passion de l'innovation.</p>
    </div>
  </footer>

  <script>
    // Animation smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Animation du header au scroll
    window.addEventListener('scroll', function() {
      const header = document.querySelector('.header');
      if (window.scrollY > 100) {
        header.style.background = 'rgba(255, 255, 255, 0.98)';
        header.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
      } else {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.boxShadow = 'none';
      }
    });

    // Feedback visuel pour l'upload de fichier
    document.getElementById('project-file').addEventListener('change', function(e) {
      const label = document.querySelector('.file-upload-label');
      const files = e.target.files;
      
      if (files.length > 0) {
        label.innerHTML = `
          <div class="upload-icon">✅</div>
          <div class="upload-text">
            <strong>${files.length} fichier(s) sélectionné(s)</strong><br>
            <small>Cliquez pour changer</small>
          </div>
        `;
        label.style.borderColor = 'var(--success)';
        label.style.background = 'rgba(16, 185, 129, 0.05)';
      }
    });
  </script>
</body>
</html>