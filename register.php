<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Design Moderne</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 480px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h2 {
            text-align: center;
            color: #2d3748;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        input, select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.8);
            color: #2d3748;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
            background: white;
        }

        input:hover, select:hover {
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }

        select {
            cursor: pointer;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 20px;
            padding-right: 50px;
        }

        input[type="file"] {
            padding: 12px 16px;
            border: 2px dashed #cbd5e0;
            background: rgba(248, 250, 252, 0.8);
            cursor: pointer;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        input[type="file"]:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        input[type="file"]:focus {
            border-color: #667eea;
            border-style: solid;
        }

        .file-input-text::before {
            content: "📷 Choisir une photo de profil";
            font-size: 14px;
            color: #64748b;
        }

        button {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 24px;
                margin: 10px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            h2 {
                font-size: 2rem;
            }

            input, select {
                padding: 14px 16px;
            }
        }

        /* Animation d'entrée */
        .container {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

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

        /* Effet de focus pour les labels flottants */
        .form-group input:not(:placeholder-shown),
        .form-group select:not(:invalid) {
            background: white;
        }

        /* Styles pour les erreurs (à ajouter si nécessaire) */
        .error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 4px;
            margin-left: 4px;
        }

        input.error, select.error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <p class="subtitle">Créez votre compte en quelques étapes</p>
        
        <!-- Le message de succès sera affiché ici par PHP -->
        <!-- <?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?> -->
        
        <form id="registerForm" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="nom" placeholder="Nom" required>
                </div>
                
                <div class="form-group">
                    <input type="text" name="prenom" placeholder="Prénom" required>
                </div>
                
                <div class="form-group">
                    <input type="date" name="date_naissance" required>
                </div>
                
                <div class="form-group">
                    <select name="genre" required>
                        <option value="">Genre</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <input type="email" name="email" placeholder="Adresse email" required>
                </div>
                
                <div class="form-group full-width">
                    <input type="text" name="num_telephone" placeholder="Numéro de téléphone" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required>
                </div>
                
                <div class="form-group full-width">
                    <input type="text" name="localisation" placeholder="Localisation" required>
                </div>
                
                <div class="form-group full-width">
                    <select name="roles" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="Etudiant">Étudiant</option>
                        <option value="Enseignant">Enseignant</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" class="file-input-text">
                </div>
            </div>
            
            <button type="submit">Créer mon compte</button>
        </form>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>