document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    if (!form) {
        console.error('Erreur : Le formulaire #registerForm n\'est pas trouvé sur la page.');
        return;
    }
    const inputs = form.querySelectorAll('input, select');
    const errorElements = {};

    // Créer des éléments d'erreur pour chaque champ
    inputs.forEach(input => {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
        errorElements[input.name] = errorDiv;
    });

    // Validation en temps réel
    inputs.forEach(input => {
        input.addEventListener('input', function () {
            validateField(this);
        });
    });

    // Validation au submit
    form.addEventListener('submit', function (event) {
        let isValid = true;
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        if (!isValid) {
            event.preventDefault();
            console.log('Soumission bloquée : formulaire invalide.');
        } else {
            console.log('Soumission autorisée : formulaire valide.');
        }
    });

    function validateField(input) {
        const value = input.value.trim();
        const error = errorElements[input.name];
        error.textContent = ''; // Réinitialiser l'erreur
        console.log(`Validation de ${input.name} avec valeur : "${value}"`);

        let isValid = true;
        if (input.required && !value) {
            error.textContent = "Ce champ est obligatoire.";
            return false;
        }

        switch (input.name) {
            case 'nom':
                if (value && (!/^[a-zA-Zéèàêïöù ]+$/.test(value) || value.length < 3)) {
                    error.textContent = "Nom invalide (lettres seulement, min 3 caractères).";
                    isValid = false;
                }
                break;
            case 'prenom':
                if (value && (!/^[a-zA-Zéèàêïöù ]+$/.test(value) || value.length < 3)) {
                    error.textContent = "Prénom invalide (lettres seulement, min 3 caractères).";
                    isValid = false;
                }
                break;
            case 'date_naissance':
                if (value) {
                    const age = new Date().getFullYear() - new Date(value).getFullYear();
                    if (age < 10) {
                        error.textContent = "Vous devez avoir au moins 10 ans.";
                        isValid = false;
                    }
                }
                break;
            case 'email':
                if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    error.textContent = "Email invalide.";
                    isValid = false;
                } else if (value) {
                    checkAvailability('email', value, error);
                }
                break;
            case 'num_telephone':
                if (value && !/^\d{8}$/.test(value)) {
                    error.textContent = "Téléphone invalide (8 chiffres).";
                    isValid = false;
                } else if (value) {
                    checkAvailability('num_telephone', value, error);
                }
                break;
            case 'password':
                if (value && value.length < 6) {
                    error.textContent = "Mot de passe trop court (min 6 caractères).";
                    isValid = false;
                }
                break;
            case 'confirm_password':
                const password = form.querySelector('input[name="password"]').value;
                if (value && value !== password) {
                    error.textContent = "Les mots de passe ne correspondent pas.";
                    isValid = false;
                }
                break;
            case 'genre':
                if (value === '') {
                    error.textContent = "Genre requis.";
                    isValid = false;
                }
                break;
            case 'localisation':
                if (value && value.length < 3) {
                    error.textContent = "Localisation requise (min 3 caractères).";
                    isValid = false;
                }
                break;
            case 'roles':
                if (value === '') {
                    error.textContent = "Rôle requis.";
                    isValid = false;
                }
                break;
        }

        return isValid;
    }

    // Fonction pour vérifier la disponibilité via XMLHttpRequest
    function checkAvailability(field, value, errorElement) {
        console.log(`Lancement de la requête pour ${field} avec valeur : ${value}`);
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `http://192.168.43.128/check_availability.php?${field}=${encodeURIComponent(value)}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log(`Réponse reçue pour ${field}, statut : ${xhr.status}, texte : ${xhr.responseText}`);
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        console.log('Données JSON parsées : ', data);
                        if (data.exists) {
                            errorElement.textContent = data.message || 'Cet élément existe déjà.';
                        } else if (data.message) {
                            errorElement.textContent = data.message;
                        } else {
                            errorElement.textContent = ''; // Réinitialiser si pas d'erreur
                        }
                    } catch (e) {
                        console.error('Erreur de parsing JSON : ', e);
                        errorElement.textContent = 'Erreur lors du traitement de la réponse.';
                    }
                } else {
                    console.error(`Erreur HTTP : ${xhr.status} - ${xhr.statusText}`);
                    errorElement.textContent = `Erreur serveur (${xhr.status}).`;
                }
            }
        };
        xhr.onerror = function () {
            console.error('Erreur réseau détectée.');
            errorElement.textContent = 'Problème de connexion au serveur.';
        };
        xhr.send();
    }
});
