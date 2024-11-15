<?php
session_start();
include_once '../connexion.php'; // Assurez-vous que ce fichier contient les informations de connexion à votre base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $nom_utilisateur = htmlspecialchars(trim($_POST['userName']));
    $mot_de_passe = htmlspecialchars(trim($_POST['password']));
    $nom = htmlspecialchars(trim($_POST['name']));
    $prenom = htmlspecialchars(trim($_POST['firstName']));

    // Vérifier si le nom d'utilisateur existe déjà
    $check_user_sql = "SELECT * FROM Employe WHERE nom_utilisateur = ?";
    $stmt = $conn->prepare($check_user_sql);
    
    if ($stmt) {
        $stmt->bind_param('s', $nom_utilisateur);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Ce nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Préparer la requête SQL pour insérer un nouvel utilisateur
            $insert_sql = "INSERT INTO Employe ( nom, prenom, date_creation, nom_utilisateur, mot_de_passe, role) 
                           VALUES (?, ?, NOW(), ?, ?, 'employe')"; // 'employe' est le rôle par défaut

            $insert_stmt = $conn->prepare($insert_sql);
            
            if ($insert_stmt) {
                // Lier les paramètres
                $insert_stmt->bind_param('ssss', $nom, $prenom, $nom_utilisateur, $hashed_password);
                
                // Exécuter la requête
                if ($insert_stmt->execute()) {
                    // Inscription réussie
                    // Redirection vers la page de formlogin pour se connecter
                    header("Location: ../form/formlogin.html");
                    exit();
                } else {
                    // Gestion des erreurs d'insertion
                    echo "Erreur lors de l'inscription : " . $insert_stmt->error;
                }
            } else {
                echo "Erreur lors de la préparation de la requête : " . $conn->error;
            }
        }

        // Fermer la déclaration
        $stmt->close();
    }

    // Fermer la connexion
    $conn->close();
}
?>