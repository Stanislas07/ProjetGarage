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

    // Vérification et upload de l'image
    $image_url = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 Mo

        if (!in_array($_FILES['image_url']['type'], $allowedTypes)) {
            header("Location: ../admin.php?add_error=Format d'image non pris en charge.");
            exit;
        }

        if ($_FILES['image_url']['size'] > $maxFileSize) {
            header("Location: ../admin.php?add_error=Fichier trop volumineux (max : 2 Mo).");
            exit;
        }

        $target_dir = __DIR__ . "/../img/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Créer le répertoire si nécessaire
        }
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image_url"]["name"]);
        if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
            $image_url = 'img/' . basename($target_file);
        } else {
            header("Location: ../admin.php?add_error=Erreur lors du téléchargement de l'image.");
            exit;
        }
    }
    
    // Fonction pour vérifier et insérer dans une table
    function checkAndInsert($conn, $table, $column, $value, $id) {
        $value = mysqli_real_escape_string($conn, $value);
        $query = "SELECT $id FROM $table WHERE $column = '$value'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$id];
        } else {
            $insert_query = "INSERT INTO $table ($column) VALUES ('$value')";
            if ($conn->query($insert_query) === TRUE) {
                return $conn->insert_id;
            } else {
                die("Erreur d'insertion dans $table : " . $conn->error);
            }
        }
    }

    // Fonction pour vérifier et insérer un modèle lié à une marque
    function checkAndInsertModele($conn, $table, $column, $value, $id_marque) {
        $value = mysqli_real_escape_string($conn, $value);
        $id_marque = mysqli_real_escape_string($conn, $id_marque);
        $query = "SELECT id_modele FROM $table WHERE $column = '$value' AND id_marque = '$id_marque'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id_modele'];
        } else {
            $insert_query = "INSERT INTO $table ($column, id_marque) VALUES ('$value', '$id_marque')";
            if ($conn->query($insert_query) === TRUE) {
                return $conn->insert_id;
            } else {
                die("Erreur d'insertion dans $table : " . $conn->error);
            }
        }
    }

    // Obtenir ou insérer les IDs nécessaires
    $id_marque = checkAndInsert($conn, 'Marque', 'nom_marque', $marque, 'id_marque');
    $id_modele = checkAndInsertModele($conn, 'Modele', 'nom_modele', $modele, $id_marque);
    $id_couleur = checkAndInsert($conn, 'Couleur', 'nom_couleur', $couleur, 'id_couleur');
    $id_kilometrage = checkAndInsert($conn, 'Kilometrage', 'valeur_kilometrage', $kilometrage, 'id_kilometrage');
    $id_prix = checkAndInsert($conn, 'Prix', 'valeur_prix', $prix, 'id_prix');

    // Récupérer les IDs pour carburant et transmission
    $id_carburant = checkAndInsert($conn, 'Carburant', 'type_carburant', $carburant, 'id_carburant');
    $id_transmission = checkAndInsert($conn, 'Transmission', 'type_transmission', $transmission, 'id_transmission');

    // Vérifier que toutes les valeurs sont valides
    if (!$id_marque || !$id_modele || !$id_couleur || !$id_carburant || !$id_transmission || !$id_kilometrage || !$id_prix) {
        header("Location: ../admin.php?add_error=Une ou plusieurs valeurs du formulaire sont invalides.");
        exit;
    }

    // Insertion dans la table Voiture
    $query_voiture = "INSERT INTO Voiture (id_marque, id_modele, id_couleur, id_carburant, id_transmission, id_kilometrage, id_prix, date_arrivee, description, image_url) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query_voiture);
    $stmt->bind_param(
        "iiiiiiisss",
        $id_marque,
        $id_modele,
        $id_couleur,
        $id_carburant,
        $id_transmission,
        $id_kilometrage,
        $id_prix,
        $date_arrivee,
        $description,
        $image_url
    );

    if ($stmt->execute()) {
        header("Location: ../admin.php?add_succes=Voiture ajoutée avec succès");
    } else {
        header("Location: ../admin.php?add_error=Erreur lors de l'ajout : " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>
