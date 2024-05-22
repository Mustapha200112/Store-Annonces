<?php
include("connexion.php");
session_start();
if (!isset($_SESSION["donner"])) {
    header("location:login.php");
    exit();
} else {
    $donner = $_SESSION["donner"];
    $nom = $donner["nom"];
    $prenom = $donner["prenom"];
    $email = $donner["email"];
    $ville = $donner["ville"];
    $tele = $donner["telephone"];
    $image = $donner["imageProfil"];
}
$resulte = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["profil"])) {
      header("location:".$_SERVER["PHP_SELF"]);
    }
    if (isset($_POST["modifier"])) {
        $resulte = 1;
    }
    if (isset($_POST["enregistrer"])) {
      $resulte = 1;
        if (!empty($_POST["nom"])) {
            $donner["nom"] = $_POST["nom"];
        }
        if (!empty($_POST["prenom"])) {
            $donner["prenom"] = $_POST["prenom"];
        }
        if (!empty($_POST["ville"])) {
            $donner["ville"] = $_POST["ville"];
        }

        if (isset($_FILES["image_modifier"]) && $_FILES["image_modifier"]["error"] != UPLOAD_ERR_NO_FILE) {
            $name = $_FILES["image_modifier"]["name"];
            $size = $_FILES["image_modifier"]["size"];
            $tmp_name = $_FILES["image_modifier"]["tmp_name"];
            $error = $_FILES["image_modifier"]["error"];
            $chek_type_image = array("png", "jpeg", "jpg");

            if ($error === 0) {
                if ($size < 4000000) {
                    $image_exetesion = pathinfo($name, PATHINFO_EXTENSION);
                    $image_ex_lower = strtolower($image_exetesion);
                    if (in_array($image_ex_lower, $chek_type_image)) {
                        $new_name = uniqid("IMG", true) . "." . $image_ex_lower;
                        $image_profil = "image_profil/" . $new_name;
                        move_uploaded_file($tmp_name, $image_profil);
                        $donner["imageProfil"] = $new_name;
                    } else {
                        $message_image = "Choisissez un type d'image valide : png, jpeg ou jpg";
                    }
                } else {
                    $message_image = "Choisissez une image inférieure à 4 Mo";
                }
            } else {
                $message_image = "Erreur lors du chargement de l'image";
            }
        }
    if (!(isset($message_image) && !empty($message_image))) {
      $modif_sql = "UPDATE `inscription` SET `nom`=:nom, `prenom`=:prenom, `ville`=:ville, `imageProfil`=:imageProfil WHERE 1";
      $modif = $connexion->prepare($modif_sql);
      $modif->bindParam(":nom", $donner["nom"]);
      $modif->bindParam(":prenom", $donner["prenom"]);
      $modif->bindParam(":ville", $donner["ville"]);
      $modif->bindParam(":imageProfil", $donner["imageProfil"]);
      $modif->execute();


      $_SESSION["donner"] = $donner;

      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  }

  }
  if (isset($_POST["modifier_pass"])) {
    $resulte = 2 ;
  }
  if (isset($_POST["enregistrer_mdp"])) {
    $resulte = 2 ;

    $message_anncien_pass = $message_nouveau_pas = $message_confirmation_pas ="";
    $anncien_pas =$_POST["anncien_pas"];
    $nouveau_pas =$_POST["nouveau_pas"];
    $confirmation_pas =$_POST["nouveau_cmpd"];

    if(empty($anncien_pas)){
      $message_anncien_pass ="entrez l'encienne password";
    }
    if(empty($nouveau_pas) || $nouveau_pas != $confirmation_pas ){
      $message_nouveau_pas ="entrez le nouveau password";
    }
    if(empty($confirmation_pas)){
      $message_confirmation_pas ="entrez la confirmation de nouveau password";
    }
    
    if (empty($message_anncien_pass) && empty($message_nouveau_pas) && empty($message_confirmation_pas) && $anncien_pas == $donner["password"]) {
      $donner["password"] =$nouveau_pas; 
      $sql_mdf_pas = "UPDATE `inscription` SET `password`=:password,`cmpsd`=:confirmation WHERE 1";
      $mod_pas = $connexion->prepare($sql_mdf_pas);
      $mod_pas->bindParam(":password",$nouveau_pas);
      $mod_pas->bindParam(":confirmation",$confirmation_pas);
      $mod_pas->execute();
      $message_total = "Votre modification et valider";
      
      

      
    }else{
      $message_total = "votre modification et invalide";
    }
  }
}





?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<style>
.card {
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
}

.profile-pic {
  transition: all 0.3s ease;
}

.profile-pic:hover {
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
  transform: scale(1.1);
}

.profile-pic-edit-btn {
  transition: all 0.3s ease;
  opacity: 0;
}

.profile-pic:hover .profile-pic-edit-btn {
  opacity: 1;
  transform: translateY(-50%);
}

.profile-edit-btn {
  transition: all 0.3s ease;
}

.profile-edit-btn:hover {
  transform: translateY(-2px);
}

.profile-save-btn {
  background-color: #4CAF50;
  border-color: #4CAF50;
  transition: all 0.3s ease;
}

.profile-save-btn:hover {
  background-color: #39943d;
  border-color: #39943d;
}
.footer {
      position: relative;
      bottom: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      padding: 20px;
    }

    </style>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
  <a class="navbar-brand" href="index.php">Basique store</a>

    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
      <a class="nav-link" href="index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ajoute.php">Ajouter une annonce</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="historique.php">Historique</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?déconnecter">Se déconnecter</a>
      </li>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="profil.php">
        <img src="image_profil/<?php
               
               echo $image ;

             ?>" alt="Photo de profil" class="profile-pic" style="width: 40px; height: 40px; border-radius: 50%;">
          
          <?php echo $nom." ".$prenom ?>
        </a>
      </li>
    </ul>
  </div>
</nav>



<?php if (isset($resulte)) {
   if ($resulte == 0  ) {?>


<form action="" method="post" enctype="multipart/form-data">

<div class="container mb-5">
  <div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-lg border-0">
        <div class="card-body p-0">
          <div class="bg-info rounded-top py-3 px-4" width="120">
            <h5 class="mb-0 text-white"><?php echo $nom." ".$prenom ?></h5>
          </div>
          <div class="p-4">
            <div class="d-flex justify-content-center mb-4">
              <div class="position-relative">
                <img src="image_profil/<?php
               
                  echo $image ;

                
                ?>" alt="Photo de profil" class="rounded-circle border border-white profile-pic" style="width: 120px; height: 120px; border-radius: 50%;">
                <div class="position-absolute bottom-0 end-0">
                  <a href="#" class="btn btn-info rounded-circle profile-pic-edit-btn"><i class="fas fa-camera"></i></a>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <span class="fw-bold">Email:</span> <?php echo $email ?>
            </div>
            <div class="mb-3">
              <span class="fw-bold">Téléphone:</span> <?php echo $tele ?>
            </div>
            <div class="mb-3">
              <span class="fw-bold">Ville:</span> <?php echo $ville ?>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="modifier" value="Modifier">
              <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="modifier_pass" value="Modifier le mot de passe">
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>

<?php } ?>

<?php

if ($resulte == 1 ) {
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="container mb-5">
    <div class="row justify-content-center mt-5">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
          <div class="card-body p-0">
            <div class="p-4">
            <div class="mb-3">
                <span class="fw-bold">nom:</span> <input type="text" name="nom">
              </div>
              <div class="mb-3">
                <span class="fw-bold">prenom:</span> <input type="text" name="prenom">
              </div>
              <div class="d-flex justify-content-center mb-4">
                <div class="position-relative">
                  <img id="imageProfil" src="image_profil/<?php echo $image; ?>" alt="Photo de profil" class="profile-pic" style="width: 40px; height: 40px; border-radius: 50%;">

                  <label for="image_modifier">image :</label>
                  <input type="file" name="image_modifier" id="image_modifier" onchange="afficherImageProfil(this)">
                  
                  <?php if (isset($message_image) && !empty($message_image)) { ?>
                    <div class="alert alert-danger"><?php echo $message_image; ?></div>
                  <?php } ?>

                  <div class="position-absolute bottom-0 end-0">
                    <a href="#" class="btn btn-info rounded-circle profile-pic-edit-btn"><i class="fas fa-camera"></i></a>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <span class="fw-bold">Ville:</span> <input type="text" name="ville">
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-save-btn" name="enregistrer" value="Enregistrer">
                <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="modifier_pass" value="Modifier le mot de passe">
                <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="Annuler" value="Annuler">

              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<?php } ?>
<?php if($resulte == 2){ ?>
<form action="" method="post">
  <div class="container mb-5">
    <div class="row justify-content-center mt-5">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
          <div class="card-body p-0">
            <div class="p-4">
             <div class="mb-3">
                <span class="fw-bold">anncien password:</span> <input type="text" name="anncien_pas">
                <?php if (isset($message_anncien_pass)&& !empty($message_anncien_pass)) { ?>
                    <div class="alert alert-danger"><?php echo $message_anncien_pass; ?></div>
                  <?php } ?>
             </div>
              <div class="mb-3">
                <span class="fw-bold">nouveau password:</span> <input type="text" name="nouveau_pas">
                <?php if (isset($message_nouveau_pas) && !empty($message_nouveau_pas)) { ?>
                    <div class="alert alert-danger"><?php echo $message_nouveau_pas; ?></div>
                  <?php } ?>
              </div>        
              <div class="mb-3">
                <span class="fw-bold">confirmation de nouveau password:</span> <input type="text" name="nouveau_cmpd">
                <?php if (isset($message_confirmation_pas)&& !empty($message_confirmation_pas)) { ?>
                    <div class="alert alert-danger"><?php echo $message_confirmation_pas; ?></div>
                  <?php } ?>
              </div>           
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-save-btn" name="enregistrer_mdp" value="Enregistrer">
                <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="modifier" value="Modifie">
                <input type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0 profile-edit-btn" name="Annuler" value="Annuler">

                <?php if (isset($message_total)) { ?>
                    <div class="alert alert-danger"><?php echo $message_total; ?></div>
                  <?php } ?>
              </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</form>

<?php }} ?>






<footer class="footer bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Liens utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="acuille.php">Accueil</a></li>
                    <li><a href="ajoute.php">Ajouter une annonce</a></li>
                    <li><a href="recherche.php">Rechercher</a></li>
                    <li><a href="historique.php">Contactez-nous</a></li>
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


<script>
function afficherImageProfil(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      var imageProfil = document.getElementById('imageProfil');

      imageProfil.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

</script>