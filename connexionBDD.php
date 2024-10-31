<?php
// Configuration de la base de données
$host = 'localhost';
$db = 'garagevoituresoccasion';
$user = 'root';
$pass = 'Pass1word!Mysql';

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>