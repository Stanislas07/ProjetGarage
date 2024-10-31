<?php
include "connexion.php";

// Récupérer l'ID de la marque depuis la requête GET
$id_marque = isset($_GET['marque']) ? validate($_GET['marque']) : '';

// Requête pour récupérer les modèles correspondant à la marque
$sql = "SELECT id_modele, nom_modele FROM modele WHERE id_marque = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('', $id_marque);
$stmt->execute();
$result = $stmt->get_result();

// Stocker les résultats dans un tableau
$models = [];
while ($row = $result->fetch_assoc()) {
    $models[] = $row;
}

// Retourner les résultats sous format JSON
echo json_encode($models);
?>
