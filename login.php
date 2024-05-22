<?php
include("connexion.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Vérification des informations de connexion
        $sql = "SELECT * FROM inscription WHERE email = :email AND password = :password";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        $information = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($stmt->rowCount() == 1) {
            session_start();
            $donner["email"] = $information["email"];
            $donner["nom"] = $information["nom"];
            $donner["prenom"] = $information["prenom"];
            $donner["nom"] = $information["nom"];
            $donner["ville"] = $information["ville"];
            $donner["telephone"] = $information["telephone"];
            $donner["imageProfil"] = $information["imageProfil"];
            $donner["password"] = $information["password"];
            $_SESSION["donner"]=$donner;

            header("location: profil.php");
            exit();
        } else {
            $message = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Chargement de Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    .footer {
      position: relative;
      bottom: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      padding: 20px;
    }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
    <a class="navbar-brand" href="acuille.php">Basique store</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contactez-nous</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inscription.php">Inscription</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" >/</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1>Connexion</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <?php if (isset($message)) { ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php } ?>
        <button type="submit" class="btn btn-primary" name="submit">Se connecter</button>
    </form>
    <div class="mt-3">
        <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
    </div>
</div>
<footer class="footer bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Liens utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="acuille.php">Accueil</a></li>
                    <li><a href="ajoute.php">Ajouter une annonce</a></li>
                    <li><a href="inscription.php">inscription</a></li>
                    <li><a href="login.php">login</a></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Contact</h5>
                <ul class="list-unstyed">
                    <li>Adresse : 123 Rue de l'Exemple, Ville</li>
                    <li>Téléphone : 01l23456789</li>
                    <li>Email : contact@example.com</li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="text-center">© 2023 Mon Site. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>
