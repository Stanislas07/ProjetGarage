<?php
include_once '../connexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];


// Requête pour récupérer les données de la voiture par ID
$query = "DELETE FROM Voiture 
        WHERE Voiture.id_voiture = $id";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Requête échouée: " . mysqli_error());
    header("Location: ../admin.php?delete_error=Un problème est survenu lors de la suppression des données");
} else {
    header("Location: ../admin.php?delete_succes=La suppression des données a bien été effectués");
}

}
?>
