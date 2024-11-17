<?php
include_once '../connexion.php';

// Validation de l'entrée
function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$id_marque = isset($_GET['marque']) ? validate($_GET['marque']) : '';
if (empty($id_marque)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id_modele, nom_modele FROM modele WHERE id_marque = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_marque); // Utilisez 'i' pour un entier
$stmt->execute();
$result = $stmt->get_result();

$models = [];
while ($row = $result->fetch_assoc()) {
    $models[] = $row;
}

header('Content-Type: application/json');
echo json_encode($models);
?>
