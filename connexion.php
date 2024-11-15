<?php
// Configuration de la base de données
$host = 'localhost';
$db = 'garagevoituresoccasion';
$user = 'root';
$pass = 'Pass1word!Mysql';

// Connexion à la base de données
$conn = mysqli_connect($host, $user, $pass, $db);

// Vérification de la connexion
if(!$conn){
    die("Erreur de connexion : ".mysqli_error());
}
?>