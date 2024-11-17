<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voiture d'occasion à vendre</title>
    <!-- Lien vers CSS -->
    <link rel="stylesheet" href="style/style.css">
    <!-- Boite d'icones -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Barre de navigation -->
    <header>
        <h2 class="logo"><span>Le </span>garage</h2>
        <nav class="navbar">
            <a class="navbar-a" href="#home">Accueil</a>
            <a class="navbar-a" href="#cars">Catalogue</a>
            <button class="btnLogin-popup"><a href="form/formlogin.html">Connexion</a></button>
        </nav>
    </header>

    <!-- Accueil -->
    <section class="home" id="home">
        <div class="home-text">
            <h1><span>Bienvenue</span><br></h1>
            <p>Venez découvrir nos produits</p>
            <a href="#cars" class="btn">Découvrir maintenant</a>
        </div>
    </section>

    <!-- Selection de voitures -->
    <section class="cars" id="cars">
        <div class="heading">
            <span>Toutes les voitures</span>
            <h2>Nous avons plusieurs types de voitures</h2>
            <p>Vous trouverez ici notre catalogue de voitures d'occasion</p>
        </div>

        <!-- Filtre -->
<div class="filtre">
    <h1>Filtre</h1>
    <form action="" method="GET">
        <div class="row">
            <div class="col-md-4">
                <select name="marque" id="marque-select" class="form-select" onchange="fetchModels()">
                    <option value=""><p>Sélectionner une marque</p></option>
                    <?php
                        include_once "connexion.php";
                        $sql_marques = "SELECT * FROM Marque ORDER BY nom_marque";
                        $result_marques = $conn->query($sql_marques);
                        if ($result_marques->num_rows > 0) {
                            while ($row = $result_marques->fetch_assoc()) {
                                echo "<option value='{$row['id_marque']}'>{$row['nom_marque']}</option>";
                            }
                        } else {
                            echo "<option>Aucune</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="modele" id="modele-select" class="form-select">
                    <option value="">Sélectionner un modèle</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="couleur" id="couleur-select" class="form-select">
                    <option value="">Sélectionner une couleur</option>
                    <?php
                        $sql_couleurs = "SELECT * FROM Couleur ORDER BY nom_couleur";
                        $result_couleurs = $conn->query($sql_couleurs);
                        if ($result_couleurs->num_rows > 0) {
                            while ($row = $result_couleurs->fetch_assoc()) {
                                echo "<option value='{$row['id_couleur']}'>{$row['nom_couleur']}</option>";
                            }
                        } else {
                            echo "<option>Aucune</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="prix-range">Plage de prix :</label>
                <input type="range" name="prix" id="prix-range" min="0" max="50000" step="500" value="" oninput="updatePriceValue(this.value)">
                <p>Prix sélectionné : <span id="prix-value"><?=isset($_GET['prix']) ? validate($_GET['prix']) : '';?></span></p>
                <input type="hidden"  id="prix-input" value="">
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn container">Filtrer</button>
                <a href="main.php" class="btn container">Reset</a>
            </div>
        </div>
    </form>
</div>


        <div class="cars-container container">
            <?php
                // Fonction pour valider les inputs
                function validate($data) {
                    return htmlspecialchars(stripslashes(trim($data)));
                }

                // Requête de base
                $sql = "SELECT 
                    Voiture.id_voiture,
                    Marque.nom_marque,
                    Modele.nom_modele,
                    Kilometrage.valeur_kilometrage,
                    Prix.valeur_prix,
                    Couleur.nom_couleur,
                    Carburant.type_carburant,
                    Transmission.type_transmission,
                    Voiture.description,
                    Voiture.date_arrivee,
                    Voiture.image_url
                    FROM Voiture
                    JOIN Marque ON Voiture.id_marque = Marque.id_marque
                    JOIN Modele ON Voiture.id_modele = Modele.id_modele
                    JOIN Kilometrage ON Voiture.id_kilometrage = Kilometrage.id_kilometrage
                    JOIN Prix ON Voiture.id_prix = Prix.id_prix
                    JOIN Couleur ON Voiture.id_couleur = Couleur.id_couleur
                    JOIN Carburant ON Voiture.id_carburant = Carburant.id_carburant
                    JOIN Transmission ON Voiture.id_transmission = Transmission.id_transmission";

                // Vérifier les filtres appliqués
                $marque = isset($_GET['marque']) ? validate($_GET['marque']) : '';
                $modele = isset($_GET['modele']) ? validate($_GET['modele']) : '';
                $couleur = isset($_GET['couleur']) ? validate($_GET['couleur']) : '';
                $prix = isset($_GET['prix']) ? validate($_GET['prix']) : '';
                $verif = false;

                $conditions = [];

                if ($marque) {
                    $conditions[] = "Voiture.id_marque = '$marque'";
                }
                if ($modele) {
                    $conditions[] = "Voiture.id_modele = '$modele'";
                }
                if ($couleur) {
                    $conditions[] = "Voiture.id_couleur = '$couleur'";
                }
                if ($prix) {
                    $conditions[] = "Prix.valeur_prix <= '$prix'";
                    $verif = true;
                }


                // Ajouter les conditions à la requête
                if (count($conditions) > 0 && $verif == false) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }
                if ($verif) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }

                // Exécution de la requête
                $result_voitures = $conn->query($sql);

                // Vérification des résultats
                if ($result_voitures->num_rows > 0) {
                    while ($row = $result_voitures->fetch_assoc()) {
                        echo "<div class='box'>
                                <img src='{$row['image_url']}' alt='Image de la voiture'>
                                <h2>{$row['nom_marque']} {$row['nom_modele']}</h2>
                                <p><span>Carburant: </span>{$row['type_carburant']}</p>
                                <p><span>Transmission: </span>{$row['type_transmission']}</p>
                                <p><span>Kilométrage: </span>{$row['valeur_kilometrage']} km</p>
                                <p>{$row['description']}</p>
                                <p><span class='price'>€{$row['valeur_prix']}</span></p>
                            </div>";
                    }
                } else {
                    echo "<p>Aucune voiture trouvée.</p>";
                }
            ?>
        </div>
    </section>

    <!-- Copyright -->
<div class="copyright">
    <p>&#169; Tous les droits sont réservés pour Le garage</p>
</div>

    <!-- Lien vers JS -->
    <script src="main.js"></script>
</body>
</html>
