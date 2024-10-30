<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - Le garage</title>
    <link rel="stylesheet" href="style_backoffice.css">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Barre de navigation -->
    <header>
        <div class="nav container">
            <i class='bx bx-menu' id="menu-icon"></i>
            <a href="index.php" class="logo">Le<span>garage</span></a>
            <ul class="navbar">
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="index.php#cars">Voiture d'occasion</a></li>
                <li><a href="index.php#about">A propos de</a></li>
            </ul>
            <a href="formlogin.html" class="login">Se connecter</a>
        </div>
    </header>

    <!-- Section d'inscription -->
    <section class="login-section">
        <div class="container">
            <h2>S'inscrire</h2>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="nom_utilisateur">Nom d'utilisateur :</label>
                    <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe :</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <button type="submit" class="btn">S'inscrire</button>
            </form>
            <p>Déjà inscrit ? <a href="formlogin.html">Connectez-vous ici</a></p>
        </div>
    </section>

    <!-- Pied de page -->
    <section class="footer">
        <div class="footer-container container">
            <div class="footer-box">
                <a href="index.php" class="logo">Le<span>garage</span></a>
                <div class="social">
                    <a href="#"><i class="bx bxl-instagram"></i></a>
                    <a href="#"><i class="bx bxl-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-box">
                <h3>Page</h3>
                <a href="index.php">Accueil</a>
                <a href="index.php#cars">Voiture</a>
                <a href="#">Parts</a>
                <a href="#">Ventes</a>
            </div>
            <div class="footer-box">
                <h3>Legal</h3>
                <a href="#">Privé</a>
                <a href="#">Politique de remboursements</a>
                <a href="#">Politique de cookie</a>
            </div>
            <div class="footer-box">
                <h3>Contact</h3>
                <p>France</p>
                <p>Nouvelle-Calédonie</p>
            </div>
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
