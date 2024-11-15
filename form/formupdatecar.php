<?php
include('../header.php');
?>

<?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    

    // Requête pour récupérer les données de la voiture par ID
    $query = "SELECT 
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
            Voiture.image_url,
            Voiture.id_marque,
            Voiture.id_modele,
            Voiture.id_couleur,
            Voiture.id_carburant,
            Voiture.id_transmission,
            Voiture.id_kilometrage,
            Voiture.id_prix
            FROM Voiture
            JOIN Marque ON Voiture.id_marque = Marque.id_marque
            JOIN Modele ON Voiture.id_modele = Modele.id_modele
            JOIN Kilometrage ON Voiture.id_kilometrage = Kilometrage.id_kilometrage
            JOIN Prix ON Voiture.id_prix = Prix.id_prix
            JOIN Couleur ON Voiture.id_couleur = Couleur.id_couleur
            JOIN Carburant ON Voiture.id_carburant = Carburant.id_carburant
            JOIN Transmission ON Voiture.id_transmission = Transmission.id_transmission 
            WHERE Voiture.id_voiture = $id";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Requête échouée: " . mysqli_error($conn));
    } else {
        $row = mysqli_fetch_assoc($result);
    }

    // Récupération des valeurs pour les selects
    $marques_query = "SELECT * FROM Marque";
    $marques_result = mysqli_query($conn, $marques_query);

    $modeles_query = "SELECT * FROM Modele";
    $modeles_result = mysqli_query($conn, $modeles_query);

    $couleurs_query = "SELECT * FROM Couleur";
    $couleurs_result = mysqli_query($conn, $couleurs_query);

    $carburants_query = "SELECT * FROM Carburant";
    $carburants_result = mysqli_query($conn, $carburants_query);

    $transmissions_query = "SELECT * FROM Transmission";
    $transmissions_result = mysqli_query($conn, $transmissions_query);

    $kilometrages_query = "SELECT * FROM Kilometrage";
    $kilometrages_result = mysqli_query($conn, $kilometrages_query);

    $prix_query = "SELECT * FROM Prix";
    $prix_result = mysqli_query($conn, $prix_query);
}
?>

<?php
// Fonction pour vérifier si la valeur existe et insérer si nécessaire
function checkAndInsert($conn, $table, $column, $value, $id_column) {
    $query = "SELECT $id_column FROM $table WHERE $column = '$value'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Retourner l'ID existant si la valeur est trouvée
        $row = mysqli_fetch_assoc($result);
        return $row[$id_column];
    } else {
        // Insérer la valeur et retourner l'ID inséré
        $insert_query = "INSERT INTO $table ($column) VALUES ('$value')";
        if (mysqli_query($conn, $insert_query)) {
            return mysqli_insert_id($conn);
        } else {
            die("Erreur lors de l'insertion dans la table $table : " . mysqli_error($conn));
        }
    }
}

// Votre code pour la mise à jour
if (isset($_POST['update_cars'])) {
    if (isset($_GET['id_new'])) {
        $idnew = $_GET['id_new'];
    }

    // Récupérer les valeurs des champs du formulaire
    $marque = !empty($_POST['marque_input']) ? $_POST['marque_input'] : $_POST['marque_select'];
    $modele = !empty($_POST['modele_input']) ? $_POST['modele_input'] : $_POST['modele_select'];
    $couleur = !empty($_POST['couleur_input']) ? $_POST['couleur_input'] : $_POST['couleur_select'];
    $carburant = $_POST['carburant'];
    $transmission = $_POST['transmission'];
    $kilometrage = $_POST['kilometrage'];
    $prix = $_POST['prix'];
    $date_arrivee = $_POST['date_arrivee'];
    $description = $_POST['description'];

    // Sécurisation des données pour éviter les injections SQL
    $marque = mysqli_real_escape_string($conn, $marque);
    $modele = mysqli_real_escape_string($conn, $modele);
    $couleur = mysqli_real_escape_string($conn, $couleur);
    $kilometrage = mysqli_real_escape_string($conn, $kilometrage);
    $prix = mysqli_real_escape_string($conn, $prix);
    $date_arrivee = mysqli_real_escape_string($conn, $date_arrivee);
    $description = mysqli_real_escape_string($conn, $description);

    // Traitement de l'image (s'il y en a une)
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $image_url = $_FILES['image_url']['name'];
    } else {
        $image_url = null;
    }

    // Insérer ou récupérer l'ID de la marque, du modèle et de la couleur
    $id_marque = checkAndInsert($conn, 'Marque', 'nom_marque', $marque, 'id_marque');
    $id_modele = checkAndInsert($conn, 'Modele', 'nom_modele', $modele, 'id_modele');
    $id_couleur = checkAndInsert($conn, 'Couleur', 'nom_couleur', $couleur, 'id_couleur');

    // Insérer le kilométrage
    $query_kilometrage = "INSERT INTO Kilometrage (valeur_kilometrage) VALUES ('$kilometrage')";
    if ($conn->query($query_kilometrage) === TRUE) {
        $id_kilometrage = $conn->insert_id;
    } else {
        $result = $conn->query("SELECT id_kilometrage FROM Kilometrage WHERE valeur_kilometrage = '$kilometrage'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_kilometrage = $row['id_kilometrage'];
        } else {
            die("Erreur lors de l'insertion ou récupération du kilométrage");
        }
    }

    // Insérer le prix
    $query_prix = "INSERT INTO Prix (valeur_prix) VALUES ('$prix')";
    if ($conn->query($query_prix) === TRUE) {
        $id_prix = $conn->insert_id;
    } else {
        $result = $conn->query("SELECT id_prix FROM Prix WHERE valeur_prix = '$prix'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_prix = $row['id_prix'];
        } else {
            die("Erreur lors de l'insertion ou récupération du prix");
        }
    }

    // Récupére l'ID du carburant
    $query_carburant = "SELECT id_carburant FROM Carburant WHERE type_carburant = '$carburant'";
    $result_carburant = mysqli_query($conn, $query_carburant);
    
    if ($result_carburant && mysqli_num_rows($result_carburant) > 0) {
        $carburant_row = mysqli_fetch_assoc($result_carburant);
        $id_carburant = $carburant_row['id_carburant'];
    } else {
        die("Erreur : Le carburant sélectionné n'existe pas dans la base de données.");
    }

    // Récupére l'ID de la transmission
    $query_transmission = "SELECT id_transmission FROM Transmission WHERE type_transmission = '$transmission'";
    $result_transmission = mysqli_query($conn, $query_transmission);
    
    if ($result_transmission && mysqli_num_rows($result_transmission) > 0) {
        $transmission_row = mysqli_fetch_assoc($result_transmission);
        $id_transmission = $transmission_row['id_transmission'];
    } else {
        die("Erreur : La transmission sélectionné n'existe pas dans la base de données.");
    }


    // Mise à jour de la voiture
    $query_voiture = "UPDATE Voiture 
                      SET id_marque = '$id_marque', id_modele = '$id_modele', id_couleur = '$id_couleur', 
                          id_carburant = '$id_carburant', id_transmission = '$id_transmission', id_kilometrage = '$id_kilometrage',
                          id_prix = '$id_prix', date_arrivee = '$date_arrivee', description = '$description'";

    if ($image_url !== null) {
        $query_voiture .= ", image_url = '$target_file'"; // Si une image est téléchargée, mettre à jour l'URL de l'image
    }

    $query_voiture .= " WHERE id_voiture = '$idnew'"; // Filtrer par l'ID de la voiture

    if ($conn->query($query_voiture) === TRUE) {
        header("Location: ../admin.php?update_succes=Voiture mise à jour avec succès");
    } else {
        header("Location: ../admin.php?update_error=Un problème est survenu lors de la mise à jour des données");
    }
}

?>

<!-- Formulaire de modification de voiture -->
<form action="formupdatecar.php?id_new=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
    <!-- Marque -->
    <div class="form-group">
        <label for="marque">Marque</label>
        <select name="marque_select" id="marque_select" class="form-control">
            <?php while ($marque_row = mysqli_fetch_assoc($marques_result)) { ?>
                <option value="<?php echo $marque_row['nom_marque']; ?>" 
                        <?php if ($marque_row['nom_marque'] == $row['nom_marque']) echo 'selected'; ?>>
                    <?php echo $marque_row['nom_marque']; ?>
                </option>
            <?php } ?>
        </select>
        <p>OU</p>
        <input type="text" name="marque_input" id="marque_input" class="form-control" value="">
    </div>

    <!-- Modèle -->
    <div class="form-group">
        <label for="modele">Modèle</label>
        <select name="modele_select" id="modele_select" class="form-control">
            <?php while ($modele_row = mysqli_fetch_assoc($modeles_result)) { ?>
                <option value="<?php echo $modele_row['nom_modele']; ?>"
                        <?php if ($modele_row['nom_modele'] == $row['nom_modele']) echo 'selected'; ?>>
                    <?php echo $modele_row['nom_modele']; ?>
                </option>
            <?php } ?>
        </select>
        <p>OU</p>
        <input type="text" name="modele_input" id="modele_input" class="form-control">
    </div>

    <!-- Kilométrage -->
    <div class="form-group">
        <label for="kilometrage">Kilométrage</label>
        <input type="number" name="kilometrage" id="kilometrage" class="form-control"
               value="<?php echo $row['valeur_kilometrage']; ?>">
    </div>

    <!-- Prix -->
    <div class="form-group">
        <label for="prix">Prix</label>
        <input type="number" name="prix" id="prix" class="form-control" value="<?php echo $row['valeur_prix']; ?>">
    </div>

    <!-- Couleur -->
    <div class="form-group">
        <label for="couleur">Couleur</label>
        <select name="couleur_select" id="couleur_select" class="form-control">
            <?php while ($couleur_row = mysqli_fetch_assoc($couleurs_result)) { ?>
                <option value="<?php echo $couleur_row['nom_couleur']; ?>"
                        <?php if ($couleur_row['nom_couleur'] == $row['nom_couleur']) echo 'selected'; ?>>
                    <?php echo $couleur_row['nom_couleur']; ?>
                </option>
            <?php } ?>
        </select>
        <p>OU</p>
        <input type="text" name="couleur_input" id="couleur_input" class="form-control">
    </div>

    <!-- Carburant -->
    <div class="form-group">
        <label for="carburant">Carburant</label>
        <select name="carburant" id="carburant" class="form-control">
            <?php while ($carburant_row = mysqli_fetch_assoc($carburants_result)) { ?>
                <option value="<?php echo $carburant_row['type_carburant']; ?>"
                        <?php if ($carburant_row['type_carburant'] == $row['type_carburant']) echo 'selected'; ?>>
                    <?php echo $carburant_row['type_carburant']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Transmission -->
    <div class="form-group">
        <label for="transmission">Transmission</label>
        <select name="transmission" id="transmission" class="form-control">
            <?php while ($transmission_row = mysqli_fetch_assoc($transmissions_result)) { ?>
                <option value="<?php echo $transmission_row['type_transmission']; ?>"
                        <?php if ($transmission_row['type_transmission'] == $row['type_transmission']) echo 'selected'; ?>>
                    <?php echo $transmission_row['type_transmission']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Description -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" id="description"><?php echo $row['description']; ?></textarea>
    </div>

    <!-- Date d'arrivée -->
    <div class="form-group">
        <label for="date_arrivee">Date</label>
        <input type="date" name="date_arrivee" id="date_arrivee" class="form-control"
               value="<?php echo $row['date_arrivee']; ?>">
    </div>

    <!-- Image -->
    <div class="form-group">
        <label for="image_url">Image</label>
        <input type="file" name="image_url" id="image_url" class="form-control">
    </div>

    <button type="submit" class="btn btn-success" name="update_cars">MODIFIER</button>
</form>

<!-- Lien vers JS -->
<script src="main.js"></script>
<?php include('../footer.php'); ?>
