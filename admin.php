<?php
include('header.php');
?>

<?php if(isset($_GET['add_succes'])) { ?>
<div class='alert alert-success' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['add_succes']; ?></h4>
    <p>La voiture a bien été enregistré</p>
</div>
<?php } ?>
<?php if(isset($_GET['add_error'])) { ?>
<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['add_error']; ?></h4>
    <p>Assurez vous que le formulaire a été correctement rempli</p>
</div>"
<?php }?>
<?php if(isset($_GET['update_succes'])) { ?>
<div class='alert alert-success' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['update_succes']; ?></h4>
    <p>Les données ont bien été modifiés</p>
</div>
<?php } ?>
<?php if(isset($_GET['update_error'])) { ?>
<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['update_error']; ?></h4>
    <p>Veuille réessayer avec les bonnes informations</p>
</div>"
<?php }?>
<?php if(isset($_GET['delete_succes'])) { ?>
<div class='alert alert-success' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['delete_succes']; ?></h4>
    <p>Les données ont bien été supprimés</p>
</div>
<?php } ?>
<?php if(isset($_GET['delete_error'])) { ?>
<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'><?php echo $_GET['delete_error']; ?></h4>
    <p>Veuillez réessayer</p>
</div>"
<?php }?>

<div class='container'>
    <div class='box1'>
        <h2>Toutes les voitures</h2>
        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">AJOUTER VOITURE</button>
    </div>
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Kilomètrage</th>
                <th>prix</th>
                <th>Couleur</th>
                <th>Carburant</th>
                <th>Transmission</th>
                <th>Description</th>
                <th>Date d'arivée</th>
                <th>Image</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php

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
                    Voiture.image_url
                    FROM Voiture
                    JOIN Marque ON Voiture.id_marque = Marque.id_marque
                    JOIN Modele ON Voiture.id_modele = Modele.id_modele
                    JOIN Kilometrage ON Voiture.id_kilometrage = Kilometrage.id_kilometrage
                    JOIN Prix ON Voiture.id_prix = Prix.id_prix
                    JOIN Couleur ON Voiture.id_couleur = Couleur.id_couleur
                    JOIN Carburant ON Voiture.id_carburant = Carburant.id_carburant
                    JOIN Transmission ON Voiture.id_transmission = Transmission.id_transmission";

            $result = mysqli_query($conn, $query);

            if(!$result){
                die("requête echoué".mysqli_error());
            } else {
                while($row = mysqli_fetch_assoc($result)){
                    ?>
            <tr>
                <td><?php echo $row['id_voiture']; ?></td>
                <td><?php echo $row['nom_marque']; ?></td>
                <td><?php echo $row['nom_modele']; ?></td>
                <td><?php echo $row['valeur_kilometrage']; ?></td>
                <td><?php echo $row['valeur_prix']; ?></td>
                <td><?php echo $row['nom_couleur']; ?></td>
                <td><?php echo $row['type_carburant']; ?></td>
                <td><?php echo $row['type_transmission']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['date_arrivee']; ?></td>
                <td><img src='<?php echo $row['image_url']; ?>' alt='Image de la voiture' class="img-backoffice"></td>
                <td><a href="form/formupdatecar.php?id=<?php echo $row['id_voiture']; ?>"
                        class="btn btn-success">MODIDIER</a></td>
                <td><a href="fetch/fetch_deletecar.php?id=<?php echo $row['id_voiture']; ?>"
                        class="btn btn-danger">SUPPRIMER</a></td>
            </tr>

            <?php
                }
            }

            ?>
        </tbody>
    </table>

    <!-- Modal -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un véhicule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="fetch/fetch_addcar.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <select name="marque_select" id="marque_select" class="form-control" required>
                                <option value="">Sélectionnez une marque</option>
                                <?php
                                        $query_marques = "SELECT * FROM Marque ORDER BY nom_marque";
                                        $result_marques = mysqli_query($conn, $query_marques);
                                        while($row = mysqli_fetch_assoc($result_marques)) {
                                            echo "<option value='{$row['id_marque']}'>{$row['nom_marque']}</option>";
                                        }
                                    ?>
                            </select>
                            <p>OU</p>
                            <input type="text" name="marque_input" id="marque_input" class="form-control"
                                placeholder="Entrez une nouvelle marque">
                        </div>

                        <div class="form-group">
                            <label for="modele">Modèle</label>
                            <select name="modele_select" id="modele_select" class="form-control" required>
                                <option value="">Sélectionnez un modèle</option>
                                <?php
                                        $query_models = "SELECT * FROM Modele ORDER BY nom_modele";
                                        $result_models = mysqli_query($conn, $query_models);
                                        while($row = mysqli_fetch_assoc($result_models)) {
                                            echo "<option value='{$row['id_modele']}'>{$row['nom_modele']}</option>";
                                        }
                                    ?>
                            </select>
                            <p>OU</p>
                            <input type="text" name="modele_input" id="modele_input" class="form-control"
                                placeholder="Entrez un nouveau modèle">
                        </div>

                        <div class="form-group">
                            <label for="kilometrage">Kilometrage</label>
                            <input type="number" name="kilometrage" id="kilometrage" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="prix">Prix</label>
                            <input type="number" name="prix" id="prix" class="form-control" required>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="couleur">Couleur</label>
                            <select name="couleur_select" id="couleur_select" class="form-control" required>
                                <option value="">Sélectionnez une couleur</option>
                                <?php
                                        $query_couleur = "SELECT * FROM Couleur ORDER BY nom_couleur";
                                        $result_couleur = mysqli_query($conn, $query_couleur);
                                        while($row = mysqli_fetch_assoc($result_couleur)) {
                                            echo "<option value='{$row['id_couleur']}'>{$row['nom_couleur']}</option>";
                                        }
                                    ?>
                            </select>
                            <p>OU</p>
                            <input type="text" name="couleur_input" id="couleur_input" class="form-control"
                                placeholder="Entrez une nouvelle couleur">
                        </div>

                        <div class="form-group">
                            <label for="carburant">Carburant</label>
                            <select name="carburant" id="carburant" class="form-control" required>
    <?php
        $query_carburant = "SELECT * FROM carburant ORDER BY type_carburant";
        $result_carburant = mysqli_query($conn, $query_carburant);
        while ($row = mysqli_fetch_assoc($result_carburant)) {
            echo "<option value='{$row['type_carburant']}'>{$row['type_carburant']}</option>";
        }
    ?>
</select>

                        </div>

                        <div class="form-group">
                            <label for="transmission">Transmission</label>
                            <select name="transmission" id="transmission" class="form-control">
                                <?php
                        $query_transmission="SELECT * FROM Transmission ORDER BY type_transmission";
                        $result_transmission=mysqli_query($conn, $query_transmission);
                        if(!$result_transmission){
                            die("Aucune transmission".mysqli_error());
                        } else {
                            while($row = mysqli_fetch_assoc($result_transmission)){
                                echo "<option value='{$row['type_transmission']}'>{$row['type_transmission']}</option>";
                            }
                        }
                    ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="date_arrivee">Date</label>
                            <input type="date" name="date_arrivee" id="date_arrivee" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="image_url">Image</label>
                            <input type="file" name="image_url" id="image_url" class="form-control" required>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lien vers JS -->
    <script src="main.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src=></script>
    </body>

    </html>