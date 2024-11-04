<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $couleur = $_POST['couleur'];
    $carburant = $_POST['carburant'];
    $transmission = $_POST['transmission'];
    $kilometrage = $_POST['kilometrage'];
    $prix = $_POST['prix'];
    $date_arrivee = $_POST['date_arrivee'];
    $description = $_POST['description'];
    $image_url = $_FILES['image_url']['name'];

    // Déplacer le fichier image téléchargé dans le dossier des images
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image_url"]["name"]);
    move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);

    // Insérer les données dans la table Voiture
    $sql = "INSERT INTO Voiture (id_marque, id_modele, id_couleur, id_carburant, id_transmission, id_kilometrage, id_prix, date_arrivee, description, image_url)
            VALUES ('$marque', '$modele', '$couleur', '$carburant', '$transmission', '$kilometrage', '$prix', '$date_arrivee', '$description', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "Voiture ajoutée avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
