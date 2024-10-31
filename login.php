<?php
session_start();
include "connexion.php"; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT * FROM Employe WHERE nom_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id_employe'];
            $_SESSION['role'] = $user['role'];
            header("Location: admin.php"); // Rediriger vers la page admin
            exit();
        } else {
            header("Location: formlogin.html"); // Rediriger vers la page de connexion si non autorisé
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
}
?>