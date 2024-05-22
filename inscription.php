<?php
    include("connexion.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {
            $message_nom = $message_prenom = $message_email = $message_ville = $message_telephon = $message_password = $message_image =  "";

            if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
                $name = $_FILES["image"]["name"];
                $size = $_FILES["image"]["size"];
                $tmp_name = $_FILES["image"]["tmp_name"];
                $error = $_FILES["image"]["error"];
                $chek_type_image = array("png","jpeg","jpg");
              if ($error === 0 ) {
                if ($size < 200000) {   
                    $image_exetesion = pathinfo($name,PATHINFO_EXTENSION);
                    $image_ex_lower = strtolower($image_exetesion);
                    if (in_array($image_ex_lower,$chek_type_image)) {
                    $new_name = uniqid("IMG",true).".".$image_ex_lower;
                    $image_profil = "image_profil/".$new_name;
                    move_uploaded_file($tmp_name,$image_profil);
                    $image_profil_new = $new_name;
                    }else{
                      $message_image ="choisire un type de cette option png , jpeg ou jpg";
                    }
                            
                }else{
                    $message_image = "choisire une image infeurier a 200bit ";
                }
              }else{
                $message_image = "error au chargment d'image";
              }
            }else{
              $image_profil_new = "image_default.jpg";

            }
            if (empty($_POST["nom"])) {
                $message_nom = "votre nom est invalider";
            }if (empty($_POST["prenom"])) {
                $message_prenom = "votre prenom est invalider";
            }if (empty($_POST["email"])) {
                $message_email = "votre email est invalider";
            }if (empty($_POST["ville"])) {
                $message_ville = "votre ville est invalider";
            }if (empty($_POST["tele"])) {
                $message_telephon = "votre numero est invalider";
            }
      
            if ( empty($_POST["confirmation_pd"]) || empty($_POST["password"]) || $_POST["password"]!=$_POST["confirmation_pd"]) {
              $message_password = "votre password ou confirmation de password est invalider";
            }            
            $sql_verif_email = "SELECT`email`, `telephone` FROM `inscription` WHERE 1";
            $verif_email = $connexion->prepare($sql_verif_email);
            $verif_email->execute();
            while($ligne = $verif_email->fetch(PDO::FETCH_ASSOC)){
              if ($ligne["email"] == $_POST["email"] ) {
                $message_email ="cette email a deja été utilisée";
                break;
              }
              if ($ligne["telephone"] == $_POST["tele"]) {
                $message_telephon ="ce numéro a déjà été utilisé";
                break;
              }
            }

            if(empty($message_nom) && empty($message_prenom) && empty($message_email) && empty($message_ville) && empty($message_telephon) && empty($message_password) && empty($message_image)){
                
                $sql_inscription = "INSERT INTO `inscription`(`nom`, `prenom`, `email`, `ville`, `telephone`, `imageProfil`, `password`, `cmpsd`) VALUES (:nom,:prenom,:email,:ville,:telephone,:imageProfil,:password,:cmpsd)";
                $profil = $connexion->prepare($sql_inscription);
                $profil->bindParam(":nom",$_POST["nom"]);
                $profil->bindParam(":prenom",$_POST["prenom"]);
                $profil->bindParam(":email",$_POST["email"]);
                $profil->bindParam(":ville",$_POST["ville"]);
                $profil->bindParam(":telephone",$_POST["tele"]);
                $profil->bindParam(":imageProfil",$image_profil_new);
                $profil->bindParam(":password",$_POST["password"]);
                $profil->bindParam(":cmpsd",$_POST["confirmation_pd"]);
                $profil->execute();
                session_start();
                $donner = array();
                $donner["email"] = $_POST["email"];
                $donner["nom"] = $_POST["nom"];
                $donner["prenom"] = $_POST["prenom"];
                $donner["nom"] = $_POST["nom"];
                $donner["ville"] = $_POST["ville"];
                $donner["telephone"] = $_POST["tele"];
                $donner["imageProfil"] = $image_profil_new;
                $donner["password"] = $_POST["password"];
                $_SESSION["donner"]=$donner;

                // Définition du cookie
                setcookie('email', $_POST["email"], time() + (30 * 24 * 60 * 60));

                header("location:profil.php");
                exit();
                

            }

        }
    }




?>

<!DOCTYPE html>
<html>
<head>
  <title>Inscription</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    .image-container {
    position: relative;
    display: inline-block;
    overflow: hidden;
    }

    .image-container {
    position: relative;
    display: inline-block;
    overflow: hidden;
  }

  .image-container img {
    transition: transform 0.5s ease;
  }

  .image-container:hover img {
    transform: scale(1.2);
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
    <h1>Inscription</h1>
    <form action=""  method="post" enctype="multipart/form-data" >
      <div class="form-group">
        <label for="nom">Nom:</label>
        <input type="text" class="form-control" id="nom" name="nom"><br>
        
        <?php  if (!empty($message_nom)) {?>
            <div class="alert alert-danger"><?php echo $message_nom;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="prenom">Prénom:</label>
        <input type="text" class="form-control" id="prenom" name="prenom">
        <br>
        <?php  if (!empty($message_prenom)) {?>
            <div class="alert alert-danger"><?php echo $message_prenom;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" class="form-control" id="email" name="email">
        <br>
        <?php  if (!empty($message_email)) {?>
            <div class="alert alert-danger"><?php echo $message_email;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="ville">Ville:</label>
        <input type="text" class="form-control" id="ville" name="ville">
        <br>
        <?php  if (!empty($message_ville)) {?>
            <div class="alert alert-danger"><?php echo $message_ville;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="telephone">Téléphone:</label>
        <input type="number" class="form-control" id="telephone" name="tele">
        <br>
        <?php  if (!empty($message_telephon)) {?>
            <div class="alert alert-danger"><?php echo $message_telephon;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control-file" id="image" name="image" onchange="afficherImage(this)">
        <br>
        <?php if (!empty($message_image)) { ?>
          <div class="alert alert-danger"><?php echo $message_image; ?></div>
        <?php } ?>
        <div class="image-container">
          <img id="imagePreview" src="image_profil/image_default.jpg" alt="Image par défaut" style="max-width: 200px; border-radius: 50%;">
        </div>
      </div>

      <div class="form-group">
        <label for="motdepasse">Mot de passe:</label>
        <input type="password" class="form-control" id="motdepasse" name="password">
        <br>
        <?php  if (!empty($message_password)) {?>
            <div class="alert alert-danger"><?php echo $message_password;?></div>
        <?php }?>
      </div>
      <div class="form-group">
        <label for="confirmationmotdepasse">Confirmation de mot de passe:</label>
        <input type="password" class="form-control" id="confirmationmotdepasse" name="confirmation_pd">
        <br>
        <?php  if (!empty($message_password)) {?>
            <div class="alert alert-danger"><?php echo $message_password;?></div>
        <?php }?>
        
        

      </div>
      <button type="submit" class="btn btn-primary" name="submit">Inscription</button>
    </form>
    <div class="mt-3">
      <p>Déjà inscrit ? <a href="login.php">Connectez-vous</a></p>
    </div>
  </div>

  <script>
        function afficherImage(input) {
      var imagePreview = document.getElementById('imagePreview');

      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          imagePreview.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
      } else {
        imagePreview.src = "image_profil/image_default.jpg";
      }
    }

  </script>
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
