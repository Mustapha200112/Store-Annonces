<?php 
session_start();
if (!isset($_SESSION["donner"])) {
    header("location:login.php");
    exit();
}else{
  $donner = $_SESSION["donner"];
  $nom = $donner["nom"];
  $prenom = $donner["prenom"];
  $email = $donner["email"];
  $ville = $donner["ville"];
  $tele = $donner["telephone"];
  $image = $donner["imageProfil"];
}


include("connexion.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $numero_table = array();

  if (isset($_POST["suprimerAll"])) { 
      $crono_1 = $_POST["crono"];
      $message_table = array();
      for ($i=0; $i <$crono_1 ; $i++) {
        if (isset($_POST[$i])) {
          $sql_delete_comments = "DELETE FROM `commentaire` WHERE numero = :id";
          $delete_comments = $connexion->prepare($sql_delete_comments);
          $delete_comments->bindParam(":id", $_POST[$i]);
          $delete_comments->execute();


          $sql_suprimmer = "DELETE FROM `addannonce` WHERE numero=:id";
          $delet_annonce = $connexion->prepare($sql_suprimmer);
          $delet_annonce->bindParam(":id",$_POST[$i]);
          $delet_annonce->execute();
          $message_table[] = $_POST[$i];

        }
      }
      if (count($message_table) == 0 ) {
        $message_delet = "pas des annonce suprimer";
      }elseif(count($message_table) == 1){
        $message_delet = "l'annonce de numero ".$message_table[0]." est bien suprimer";

      }else{
        $message_delet = "les annonce des numero :";
        foreach ($message_table as $numero) {
          if ($message_table[0] == $numero) {
            $message_delet .=" ".$numero;

          }else{
              $message_delet .= " ,".$numero;

          }


        }
          $message_delet .= " est bien suprimmer ";

      }


     
      
  }
  if (isset($_POST["modifierAll"])) { 
    $crono_1 = $_POST["crono"];
    for ($i=0; $i <$crono_1 ; $i++) {
      if (isset($_POST[$i])) {
        $numero_table[] = $_POST[$i];

      }
    }
    if ($crono_1 == 0) {
      header("location:".$_SERVER["PHP_SELF"]);
    }
    $resulte = 1;
 
  }
  if (isset($_POST["enregestrerAll"])) { 
    if (isset( $_POST["crono_enregistrer"])) {
      $crono_1 = $_POST["crono_enregistrer"];
      $message_enregetrement = "pas de modification fait";
      for ($i=0; $i <=$crono_1 ; $i++) {
        if (isset($_POST[$i])) {
          $numero = $_POST[$i];
          #requper les annonces pour modifier 
          $sql_modification = "SELECT * FROM addannonce WHERE numero = :numero";
          $resulta_modification = $connexion->prepare($sql_modification);
          $resulta_modification->bindParam(":numero", $numero);
          $resulta_modification->execute();
          $annonces_modification = $resulta_modification->fetch(PDO::FETCH_ASSOC);
          #stocker les annonces pour modifier 
          $image = $annonces_modification["imagePoste"];
          $titre = $annonces_modification["titre"];
          $description = $annonces_modification["description"];
          $prix = $annonces_modification["prix"];
          $ville = $annonces_modification["ville"];
          #requper les modification
          if (isset($_FILES["image".$i]) && $_FILES["image".$i]["error"] != UPLOAD_ERR_NO_FILE) {
              $name = $_FILES["image".$i]["name"];
              $size = $_FILES["image".$i]["size"];
              $tmp_name = $_FILES["image".$i]["tmp_name"];
              $error = $_FILES["image".$i]["error"];
              $chek_type_image = array("png", "jpeg", "jpg");
  
              if ($error === 0) {
                  if ($size < 4000000) {
                      $image_exetesion = pathinfo($name, PATHINFO_EXTENSION);
                      $image_ex_lower = strtolower($image_exetesion);
                      if (in_array($image_ex_lower, $chek_type_image)) {
                          $new_name = uniqid("IMG", true) . "." . $image_ex_lower;
                          $image_modif = "image/" . $new_name;
                          move_uploaded_file($tmp_name, $image_modif);
                          $image = $new_name;
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
          if (!empty($_POST["titre".$i]) && isset($_POST["titre".$i])) {
            $titre_modifier = $_POST["titre".$i];
            $titre = $titre_modifier;
          }
          if (!empty($_POST["description".$i]) && isset($_POST["description".$i])) {
            $description_modifier = $_POST["description".$i];
            $description = $description_modifier;
          }
          if (!empty($_POST["prix".$i]) && isset($_POST["prix".$i])) {
            $prix_modifier = $_POST["prix".$i];
            $prix = $prix_modifier;
          }
          if (!empty($_POST["ville".$i]) && isset($_POST["ville".$i])) {
            $ville_modifier = $_POST["ville".$i];
            $ville = $ville_modifier;
          }
          
          if (!isset($message_image)) {
            $sql_modification_valid = "UPDATE `addannonce` SET `titre`=:titre,`description`=:description,`prix`=:prix,`ville`=:ville,`imagePoste`=:image WHERE numero =:id ";
            $enregistre_modif = $connexion->prepare($sql_modification_valid);
            $enregistre_modif->bindParam(":titre", $titre);
            $enregistre_modif->bindParam(":description", $description);
            $enregistre_modif->bindParam(":prix", $prix);
            $enregistre_modif->bindParam(":ville", $ville);
            $enregistre_modif->bindParam(":image", $image);
            $enregistre_modif->bindParam(":id", $numero);
            $enregistre_modif->execute();
            $message_enregetrement = "votre modification et bien fiat";
  
          }
  
  
        }
      }    
    }
   
 
  }
  if (isset($_POST["modifie"])) {
    $numero_table = array();
    $numero_table[] = $_POST["numero"];
    $resulte = 1;
  } 
  if (isset($_POST["enregistrer"])) {
    if (isset($_POST["numero"])) {
      $numero = $_POST["numero"];
      $crono_enregistrer = $_POST["crono_enregistrer"];
      #requper les annonces pour modifier 

      $sql_modification = "SELECT * FROM addannonce WHERE numero = :numero";
      $resulta_modification = $connexion->prepare($sql_modification);
      $resulta_modification->bindParam(":numero", $numero);
      $resulta_modification->execute();
      $annonces_modification = $resulta_modification->fetch(PDO::FETCH_ASSOC);
      #stocker les annonces pour modifier 
      $image_annonce = $annonces_modification["imagePoste"];
      $titre = $annonces_modification["titre"];
      $description = $annonces_modification["description"];
      $prix = $annonces_modification["prix"];
      $ville = $annonces_modification["ville"];
      #requper les modification
      if (isset($_FILES["image".$crono_enregistrer]) && $_FILES["image".$crono_enregistrer]["error"] != UPLOAD_ERR_NO_FILE) {
          $name = $_FILES["image".$crono_enregistrer]["name"];
          $size = $_FILES["image".$crono_enregistrer]["size"];
          $tmp_name =$_FILES["image".$crono_enregistrer]["tmp_name"];
          $error = $_FILES["image".$crono_enregistrer]["error"];
          $chek_type_image = array("png", "jpeg", "jpg");

          if ($error === 0) {
              if ($size < 4000000) {
                  $image_exetesion = pathinfo($name, PATHINFO_EXTENSION);
                  $image_ex_lower = strtolower($image_exetesion);
                  if (in_array($image_ex_lower, $chek_type_image)) {
                      $new_name = uniqid("IMG", true) . "." . $image_ex_lower;
                      $image_modif = "image/" . $new_name;
                      move_uploaded_file($tmp_name, $image_modif);
                      $image_annonce = $new_name;
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
      
      if (!empty($_POST["titre".$crono_enregistrer]) && isset($_POST["titre".$crono_enregistrer])) {
        $titre_modifier = $_POST["titre".$crono_enregistrer];
        $titre = $titre_modifier;
      }
      if (!empty($_POST["description".$crono_enregistrer]) && isset($_POST["description".$crono_enregistrer])) {
        $description_modifier = $_POST["description".$crono_enregistrer];
        $description = $description_modifier;
      }
      if (!empty($_POST["prix".$crono_enregistrer]) && isset($_POST["prix".$crono_enregistrer])) {
        $prix_modifier = $_POST["prix".$crono_enregistrer];
        $prix = $prix_modifier;
      }
      if (!empty($_POST["ville".$crono_enregistrer]) && isset($_POST["ville".$crono_enregistrer])) {
        $ville_modifier = $_POST["ville".$crono_enregistrer];
        $ville = $ville_modifier;
      }
      if (empty($_POST["ville".$crono_enregistrer]) && empty($_POST["prix".$crono_enregistrer]) && empty($_POST["description".$crono_enregistrer]) && empty($_POST["titre".$crono_enregistrer]) && empty($_POST["ville".$crono_enregistrer]) && !(isset($_FILES["image".$crono_enregistrer]) && $_FILES["image".$crono_enregistrer]["error"] != UPLOAD_ERR_NO_FILE)) {
        $message_enregetrement = "pas de modification fait";
      }else{
        $message_enregetrement = "votre modification et bien fiat";

      }
      
      if (!isset($message_image)) {
        $sql_modification_valid = "UPDATE `addannonce` SET `titre`=:titre,`description`=:description,`prix`=:prix,`ville`=:ville,`imagePoste`=:image WHERE numero =:id ";
        $enregistre_modif = $connexion->prepare($sql_modification_valid);
        $enregistre_modif->bindParam(":titre", $titre);
        $enregistre_modif->bindParam(":description", $description);
        $enregistre_modif->bindParam(":prix", $prix);
        $enregistre_modif->bindParam(":ville", $ville);
        $enregistre_modif->bindParam(":image", $image_annonce);
        $enregistre_modif->bindParam(":id", $numero);
        $enregistre_modif->execute();

      }


    }
  }   
  $numero_table_suprim = array();

  if (isset($_POST["suprimer"])) {
    $numero_table_suprim[] = $_POST["numero"];
    $resulte = 1;

  }
  if (isset($_POST["ok"])) {
          $sql_delete_comments = "DELETE FROM `commentaire` WHERE numero = :id";
          $delete_comments = $connexion->prepare($sql_delete_comments);
          $delete_comments->bindParam(":id", $_POST["numero"]);
          $delete_comments->execute();


          $sql_suprimmer = "DELETE FROM `addannonce` WHERE numero=:id";
          $delet_annonce = $connexion->prepare($sql_suprimmer);
          $delet_annonce->bindParam(":id",$_POST["numero"]);
          $delet_annonce->execute();
          $message_delet = "votre annonce est suprimmer";
  }
  if (isset($_POST["annuler"])) {
    header("location:".$_SERVER["PHP_SELF"]);
  }

  
  $sql_affiche = "SELECT * FROM addannonce WHERE email_annonce = :email";
  $resulta_normal = $connexion->prepare($sql_affiche);
  $resulta_normal->bindParam(":email", $email);
  $resulta_normal->execute();
  $annonces_normal = $resulta_normal->fetchAll(PDO::FETCH_ASSOC);
}else{
  $sql_affiche = "SELECT * FROM addannonce WHERE email_annonce = :email";
$resulta_normal = $connexion->prepare($sql_affiche);
$resulta_normal->bindParam(":email", $email);
$resulta_normal->execute();
$annonces_normal = $resulta_normal->fetchAll(PDO::FETCH_ASSOC);
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <style>

.package {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: #f8f9fa;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
    }
    .package:hover {
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }
    .package img {
      width: 100%;
      margin-bottom: 10px;
      transition: transform 0.3s ease;
    }
    .package img:hover {
      transform: scale(1.05);
    }
    .package-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
      color: #333;
    }
    .package-description {
      margin-bottom: 10px;
      color: #777;
    }
    .package-price {
      font-weight: bold;
      color: #007bff;
    }
    .package-city {
      color: #666;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }
    .btn-primary:focus, .btn-primary.focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
    }
    .animate {
      animation-duration: 1s;
      animation-fill-mode: both;
    }
    .fadeIn {
      animation-name: fadeIn;
    }
    .carousel-item img {
      width: 100%;
      height: 400px;
      object-fit: cover;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .footer {
      position: relative;
      bottom: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      padding: 20px;
    }
    a{
      text-decoration: none;
    }
    a:hover {
    text-decoration: none;
    }
    .package img {
        width: 100%;
        max-width: 200px; /* Taille maximale de l'image */
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }
    button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  border-radius: 5px;
  margin-bottom: 2px;
  outline: none;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}

button:active {
  background-color: #003d80;
}

button:hover {
  animation-name: buttonAnimation;
  animation-duration: 0.5s;
  animation-fill-mode: forwards;
}

@keyframes buttonAnimation {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
  100% {
    transform: translateY(0);
  }
}


    


  </style>
</head> 
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
        <a class="nav-link" href="index.php?déconnecter">Se déconnecter</a>
      </li>
    </ul>

    <ul class="navbar-nav mr-auto">          
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



    <h1>Liste des annonces</h1>

<?php if (isset($resulte) && $resulte == 1) {
?>
<form action="" method="post" enctype="multipart/form-data">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Numéro</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Ville</th>
                <th>Action</th>
                <th>
                        <input type="checkbox" id="checkAll">
                        <label for="checkAll">Sélectionner tout</label>
                </th>
          
            </tr>
        </thead>
        <tbody>
<?php
$crono_enregistrer = 0 ;
  $crono = 0; foreach ($annonces_normal as $annonce) {
      if (in_array($annonce["numero"],$numero_table)) {
?>                  <?php if (count($numero_table)==1){ ?>
                      <form action="" method="post">
                    <?php } ?>

                          <tr>
                            <td>
                              <div class="package">
                                <img class="imagePreview" src="image/<?php echo $annonce['imagePoste']; ?>" alt="Photo">
                              </div>
                              <input type="file" name="image<?php if (count($numero_table)>=1) { echo $crono_enregistrer; }?>" onchange="afficherImage(this)">
                            </td>
                            <td><?php echo $annonce['numero']; ?></td>
                            <td><input type="text" name="titre<?php if (count($numero_table)>=1) { echo $crono_enregistrer; }  ?>"></td>
                            <td><input type="text" name="description<?php if (count($numero_table)>=1) { echo $crono_enregistrer; } ?>"></td>
                            <td><input type="text" name="prix<?php if (count($numero_table)>=1) { echo $crono_enregistrer; } ?>"></td>
                            <td><input type="text" name="ville<?php if (count($numero_table)>=1) { echo $crono_enregistrer; } ?>"></td>
                            <td>
                              <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
                              <input style="display:none" type="text" value="<?php echo $crono_enregistrer ?>" name="crono_enregistrer">
                              <button type="submit" name="enregistrer">Enregistrer</button>
                       <?php if (count($numero_table)==1){ ?>

                          </form>
                        <?php } ?>

                            </td>
                          </tr>
                          <input style="display:none" type="text" value="<?php echo $crono ?>" name="crono">
                          <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="<?php echo $crono_enregistrer ?>">
                          <input style="display:none" type="text" value="<?php echo $crono_enregistrer ?>" name="crono_enregistrer">


              <?php if ($annonce == $annonces_normal[count($annonces_normal)-1] ) { ?>
                      <tr>
                        <td colspan="3"><button type="submit" name="suprimerAll">Supprimer les annonces sélectionnées</button></td>
                        <td colspan="3"><button type="submit" name="enregestrerAll">Enregestrer tout les modification</button></td>
                      </tr>
                      

              <?php if (isset($message_delet) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_delet; ?></div>

              <?php }  ?>
              <?php if (isset($message_enregetrement) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_enregetrement; ?></div>
              <?php } } ?>

<?php ;
 }elseif(in_array($annonce["numero"],$numero_table_suprim)){
?>

<tr>
                        <td>
                            <div class="package">
                                <img src="image/<?php echo $annonce['imagePoste']; ?>" alt="Photo ">
                            </div>
                        </td>
                        <td><?php echo $annonce['numero']; ?></td>
                        <td><?php echo $annonce['titre']; ?></td>
                        <td><?php echo substr($annonce["description"], 0, 40) . "..."; ?></td>
                        <td><?php echo $annonce['prix']; ?></td>
                        <td><?php echo $annonce['ville']; ?></td>
                        <td>
                          <form action="" method="post">
                            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
                            <button type="submit" name="ok">ok</button>
                            <button type="submit" name="annuler">Annuler</button>
                          </form>
                        </td>
                        <td><input type="checkbox" name="<?php echo $crono;?>" value="<?php echo $annonce["numero"] ?>"></td>
                    </tr>
                    <input style="display:none" type="text" value="<?php echo $crono ?>" name="crono">


      

              <?php if ($annonce == $annonces_normal[count($annonces_normal)-1] ) { ?>
                      <tr>   
                        <td colspan="3"><button type="submit" name="suprimerAll">Supprimer les annonces sélectionnées</button></td>
                        <td colspan="3"><button type="submit" name="enregestrerAll">Enregestrer tout les modification</button></td>
                      </tr>
                      <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="<?php echo $crono_enregistrer ?>">
                      <input style="display:none" type="text" value="<?php echo $crono_enregistrer ?>" name="crono_enregistrer">


              <?php if (isset($message_delet) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_delet; ?></div>
              <?php }  ?>
              <?php if (isset($message_enregetrement) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_enregetrement; ?></div>
              <?php } } ?>
              
<?php }else{ ?>

                    <tr>
                        <td>
                            <div class="package">
                                <img src="image/<?php echo $annonce['imagePoste']; ?>" alt="Photo ">
                            </div>
                        </td>
                        <td><?php echo $annonce['numero']; ?></td>
                        <td><?php echo $annonce['titre']; ?></td>
                        <td><?php echo substr($annonce["description"], 0, 40) . "..."; ?></td>
                        <td><?php echo $annonce['prix']; ?></td>
                        <td><?php echo $annonce['ville']; ?></td>
                        <td>
                          <form action="" method="post">
                            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
                            <button type="submit" name="modifie">Modifier</button>
                            <button type="submit" name="suprimer">Supprimer</button>
                          </form>
                        </td>
                        <td><input type="checkbox" name="<?php echo $crono;?>" value="<?php echo $annonce["numero"] ?>"></td>
                    </tr>
                    <input style="display:none" type="text" value="<?php echo $crono ?>" name="crono">


      

              <?php if ($annonce == $annonces_normal[count($annonces_normal)-1] ) { ?>
                      <tr>   
                        <td colspan="3"><button type="submit" name="suprimerAll">Supprimer les annonces sélectionnées</button></td>
                        <td colspan="3"><button type="submit" name="enregestrerAll">Enregestrer tout les modification</button></td>
                      </tr>
                      <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="<?php echo $crono_enregistrer ?>">
                      <input style="display:none" type="text" value="<?php echo $crono_enregistrer ?>" name="crono_enregistrer">


              <?php if (isset($message_delet) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_delet; ?></div>
              <?php }  ?>
              <?php if (isset($message_enregetrement) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_enregetrement; ?></div>
              <?php } } ?>
              
              


<?php 
}$crono++;$crono_enregistrer++;

}?>
</tbody> 
</table>   
</form>

<?php
}else{?>
<form action="" method="post" enctype="multipart/form-data">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Numéro</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Ville</th>
                <th>Action</th>
                <th>
                  <input type="checkbox" id="checkAll">
                  <label for="checkAll">Sélectionner tout</label>
                </th>
          
            </tr>
        </thead>
            <tbody>
                <?php $crono = 0; foreach ($annonces_normal as $annonce) { ?>
                    <tr>
                        <td>
                            <div class="package">
                                <img src="image/<?php echo $annonce['imagePoste']; ?>" alt="Photo de profil">
                            </div>
                        </td>
                        <td><?php echo $annonce['numero']; ?></td>
                        <td><?php echo $annonce['titre']; ?></td>
                        <td><?php echo substr($annonce["description"], 0, 40) . "..."; ?></td>
                        <td><?php echo $annonce['prix']; ?></td>
                        <td><?php echo $annonce['ville']; ?></td>
                        <td>

                        <form action="" method="post">
                            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
                            <button type="submit" name="modifie">Modifier</button>
                            <button type="submit" name="suprimer">Supprimer</button>
                          </form>
                        </td>
                        <td><input type="checkbox" name="<?php echo $crono;?>" value="<?php echo $annonce["numero"] ?>"></td>
                    </tr>
                <?php $crono++; } ?>
                <tr>
                  <td colspan="3"><button type="submit" name="suprimerAll">Supprimer les annonces sélectionnées</button></td>
                  <td colspan="3"><button type="submit" name="modifierAll">modifier les annonces sélectionnées</button></td>
                </tr>
                <input style="display:none" type="text" value="<?php echo $crono ?>" name="crono">

            </tbody>
        </table>
      

    <?php if (isset($message_delet) ) { ?>
            <div class="alert alert alert-success"><?php echo $message_delet; ?></div>
    <?php } ?>
    <?php if (isset($message_enregetrement) ) { ?>
                      <div class="alert alert alert-success"><?php echo $message_enregetrement; ?></div>
    <?php }  ?>

</form>


<?php 

}
?>



    <script>
    $(document).ready(function() {
        $("#checkAll").click(function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        });
    });
    function afficherImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          var imagePreview = input.parentElement.querySelector('.imagePreview');

          imagePreview.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
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