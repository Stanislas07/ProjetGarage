<?php
 session_start();
 if (!isset($_SESSION['user_id'])) {
     header("Location: main.php"); // Redirige vers la page de connexion
     exit();
 }
include('connexion.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice</title>
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Boite d'icones -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style/style_admin.css">
</head>

<body>
    <div id="main_title">
        <p></p>
        <h1>Administration</h1>
        <a href="deconnexion.php" class="btn btn-warning">Déconnexion</a>
    </div> 