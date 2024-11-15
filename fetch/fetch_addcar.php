<?php
include_once '../connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs entrées par l'utilisateur
    $marque = !empty($_POST['marque_input']) ? $_POST['marque_input'] : $_POST['marque_select'];
    $modele = !empty($_POST['modele_input']) ? $_POST['modele_input'] : $_POST['modele_select'];
    $couleur = !empty($_POST['couleur_input']) ? $_POST['couleur_input'] : $_POST['couleur_select'];
    $carburant = $_POST['carburant'];
    $transmission = $_POST['transmission'];
    $kilometrage = $_POST['kilometrage'];
    $prix = $_POST['prix'];
    $date_arrivee = $_POST['date_arrivee'];
    $description = $_POST['description'];

    // Vérifier si une image a été téléchargée
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $image_url = $_FILES['image_url']['name'];
    } else {
        $image_url = null; // Ou une valeur par défaut
    }

    // Sécuriser les entrées pour éviter les injections SQL
    $marque = mysqli_real_escape_string($conn, $marque);
    $modele = mysqli_real_escape_string($conn, $modele);
    $couleur = mysqli_real_escape_string($conn, $couleur);
    $kilometrage = mysqli_real_escape_string($conn, $kilometrage);
    $prix = mysqli_real_escape_string($conn, $prix);
    $date_arrivee = mysqli_real_escape_string($conn, $date_arrivee);
    $description = mysqli_real_escape_string($conn, $description);

    // Déplacer l'image téléchargée si elle existe
    if ($image_url !== null) {
        $target_dir = __DIR__ . "/../img/";
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image_url"]["name"]);
        move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
    }

    // Fonction pour vérifier et insérer dans une table
function checkAndInsert($conn, $table, $column, $value, $id) {
    // Sécuriser la valeur
    $value = mysqli_real_escape_string($conn, $value);

    // Vérifier si la valeur existe déjà dans la table
    $query = "SELECT $id FROM $table WHERE $column = '$value'";  // Assurez-vous que 'id_marque' existe dans la table
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Si la valeur existe, retourner l'ID
        $row = $result->fetch_assoc();
        return $row[$id];  // Assurez-vous que l'ID retourné est correct
    } else {
        // Sinon, insérer la nouvelle valeur et récupérer l'ID
        $insert_query = "INSERT INTO $table ($column) VALUES ('$value')";
        if ($conn->query($insert_query) === TRUE) {
            return $conn->insert_id;
        } else {
            die("Erreur d'insertion dans $table : " . $conn->error);
        }
    }
}

// Fonction pour vérifier et insérer dans la table Modele avec l'id_marque
function checkAndInsertModele($conn, $table, $column, $value, $id_marque) {
    $value = mysqli_real_escape_string($conn, $value);
    $id_marque = mysqli_real_escape_string($conn, $id_marque);

    // Vérifiez si le modèle existe déjà avec cet id_marque
    $query = "SELECT id_modele FROM $table WHERE $column = '$value' AND id_marque = '$id_marque'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_modele'];
    } else {
        // Ajoutez le modèle avec id_marque
        $insert_query = "INSERT INTO $table ($column, id_marque) VALUES ('$value', '$id_marque')";
        if ($conn->query($insert_query) === TRUE) {
            return $conn->insert_id;
        } else {
            die("Erreur d'insertion dans $table : " . $conn->error);
        }
    }
}

// Utilisation de la fonction checkAndInsert pour obtenir ou insérer les IDs
$id_marque = checkAndInsert($conn, 'Marque', 'nom_marque', $marque, 'id_marque');
$id_modele = checkAndInsertModele($conn, 'Modele', 'nom_modele', $modele, $id_marque);


    $id_couleur = checkAndInsert($conn, 'Couleur', 'nom_couleur', $couleur, 'id_couleur');

    // Insertion dans la table Kilometrage
    $query_kilometrage = "INSERT INTO Kilometrage (valeur_kilometrage) VALUES ('$kilometrage')";
    if ($conn->query($query_kilometrage) === TRUE) {
        $id_kilometrage = $conn->insert_id;
    } else {
        die("Erreur lors de l'insertion dans Kilometrage : " . $conn->error);
    }

    // Insertion dans la table Prix
    $query_prix = "INSERT INTO Prix (valeur_prix) VALUES ('$prix')";
    if ($conn->query($query_prix) === TRUE) {
        $id_prix = $conn->insert_id;
    } else {
        die("Erreur lors de l'insertion dans Prix : " . $conn->error);
    }

    // Insertion dans la table Voiture
    $query_voiture = "INSERT INTO Voiture (id_marque, id_modele, id_couleur, id_carburant, id_transmission, id_kilometrage, id_prix, date_arrivee, description, image_url) 
    VALUES ('$id_marque', '$id_modele', '$id_couleur', '$carburant', '$transmission', '$id_kilometrage', '$id_prix', '$date_arrivee', '$description', '$target_file')";
    
    if ($conn->query($query_voiture) === TRUE) {
        header("Location: ../admin.php?messagesucces=Voiture ajoutée avec succès");
    } else {
        header("Location: ../admin.php?messageerror=Un problème est survenu lors de l'enregistrement des données");
    }

    $conn->close();
}

?>