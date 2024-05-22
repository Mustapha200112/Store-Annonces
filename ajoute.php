<?php 
include("connexion.php");
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
  
$sql_categorie = "SELECT categori FROM categori_sous_categorie";
$connexion_categorie = $connexion->prepare($sql_categorie);
$connexion_categorie->execute();
$categorie_afficher = $connexion_categorie->fetchAll(PDO::FETCH_ASSOC);


   

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"]) && isset($_FILES["imagePrincipale"])) {
        function ajoute_image($name,$size,$tmp_name,$error){
            $chek_type_image = array("png","jpeg","jpg");
            $message_image ="";
            if ($error === 0 ) {
                if ($size < 2000000) {
                    $image_exetesion = pathinfo($name,PATHINFO_EXTENSION);
                    $image_ex_lower = strtolower($image_exetesion);
                    if (in_array($image_ex_lower,$chek_type_image)) {
                        $new_name = uniqid("IMG",true).".".$image_ex_lower;
                        $image_profil = "imagedetaille/".$new_name;
                        move_uploaded_file($tmp_name,$image_profil);
                        return $new_name;
                    }else{
                    return $message_image ="choisire un type de cette option png , jpeg ou jpg";
                    }
                            
                }else{
                return $message_image = "choisire une image infeurier a 200bit ";
                }
            }else{
                return $message_image = "error au chargment d'image";
            }
        }
        
        $message_image1 = $message_image2= $message_image3 =$message_image4 =$message_image5 =$message_image6 = "";
        if (isset($_FILES["image1"]) && $_FILES["image1"]["error"] != UPLOAD_ERR_NO_FILE) {
            if (!empty($_FILES["image1"]["name"])) {
                $nom1 = $_FILES["image1"]["name"];
                $size1 = $_FILES["image1"]["size"];
                $error1 = $_FILES["image1"]["error"];
                $tmp_name1 = $_FILES["image1"]["tmp_name"];
                $addIMG1 = ajoute_image($nom1, $size1, $tmp_name1, $error1);
                if ((substr($addIMG1, 0, 3) === "IMG")) {
                    $image1=$addIMG1;
                }
                else{
                    $message_image1=$addIMG1;

                }
            }
        }else{
            $image1 = "";
        }
            
        if (isset($_FILES["image2"]) && $_FILES["image2"]["error"] != UPLOAD_ERR_NO_FILE) {
            if (!empty($_FILES["image2"]["name"])) {
                $nom2 = $_FILES["image2"]["name"];
                $size2 = $_FILES["image2"]["size"];
                $error2 = $_FILES["image2"]["error"];
                $tmp_name2 = $_FILES["image2"]["tmp_name"];
                $addIMG2 = ajoute_image($nom2, $size2, $tmp_name2, $error2);
                if ((substr($addIMG2, 0, 3) === "IMG")) {
                    $image2=$addIMG2;
                }
                else{
                    $message_image2=$addIMG2;

                }
            }
        }else{
            $image2 = "";
        }
        
        if (isset($_FILES["image3"]) && $_FILES["image3"]["error"] != UPLOAD_ERR_NO_FILE) {
            if (!empty($_FILES["image3"]["name"])) {
            $nom3 = $_FILES["image3"]["name"];
            $size3 = $_FILES["image3"]["size"];
            $error3 = $_FILES["image3"]["error"];
            $tmp_name3 = $_FILES["image3"]["tmp_name"];
            $addIMG3 = ajoute_image($nom3, $size3, $tmp_name3, $error3);
            if ((substr($addIMG3, 0, 3) === "IMG")) {
                $image3=$addIMG3;
            }
            else{
                $message_image3=$addIMG3;

            }
        }
        }else{
            $image3 = "";
        }
        
        if (isset($_FILES["image4"]) && $_FILES["image4"]["error"] != UPLOAD_ERR_NO_FILE) {
            if (!empty($_FILES["image4"]["name"])) {
            $nom4 = $_FILES["image4"]["name"];
            $size4 = $_FILES["image4"]["size"];
            $error4 = $_FILES["image4"]["error"];
            $tmp_name4 = $_FILES["image4"]["tmp_name"];
            $addIMG4 = ajoute_image($nom4, $size4, $tmp_name4, $error4);
            if ((substr($addIMG4, 0, 3) === "IMG")) {
                $image4=$addIMG4;
            }
            else{
                $message_image4=$addIMG4;

            }
        }
        }else{
            $image4 = "";
        }
        
        if (isset($_FILES["image5"]) && $_FILES["image5"]["error"] != UPLOAD_ERR_NO_FILE) {
            if (!empty($_FILES["image5"]["name"])) {
            $nom5 = $_FILES["image5"]["name"];
            $size5 = $_FILES["image5"]["size"];
            $error5 = $_FILES["image5"]["error"];
            $tmp_name5 = $_FILES["image5"]["tmp_name"];
            $addIMG5 = ajoute_image($nom5, $size5, $tmp_name5, $error4);
            if ((substr($addIMG5, 0, 3) === "IMG")) {
                $image5=$addIMG5;
            }
            else{
                $message_image5=$addIMG5;

            }
        }
        }else{
            $image5 = "";
        }
        
        if (isset($_FILES["image6"]) && $_FILES["image6"]["error"] != UPLOAD_ERR_NO_FILE) {
                if (!empty($_FILES["image6"]["name"])) {
                $nom6 = $_FILES["image6"]["name"];
                $size6 = $_FILES["image6"]["size"];
                $error6 = $_FILES["image6"]["error"];
                $tmp_name6 = $_FILES["image6"]["tmp_name"];
                $addIMG6 = ajoute_image($nom6, $size6, $tmp_name6, $error6);
                if ((substr($addIMG6, 0, 3) === "IMG")) {
                    $image6=$addIMG6;
                }
                else{
                    $message_image6=$addIMG6;

                }
            }
        }else{
            $image6 = "";
        }
        
            
            $message_titre = $message_description = $message_ville = $message_categorie = $message_prix = "";
            $titre = $_POST["titre"];
            $description = nl2br($_POST["description"]);
            $ville = $_POST["ville"];
            $prix = $_POST["prix"];
            $email = $donner["email"];
            $categorie = "";
            if (isset($_POST["categorie_selected"]) && !empty($_POST["categorie_selected"])) {
                $categorie = $_POST["categorie_selected"];
            }
            if (isset($_POST["new_categorie"]) && !empty($_POST["new_categorie"])) {
                $categorie = $_POST["new_categorie"];
            }
            if (empty($titre)) {
                $message_titre = "Votre titre est pas valider ";
            }
            if (empty($description)) {
                $message_description = "Votre description est pas valider ";
            }
            if (empty($ville)) {
                $message_ville = "Votre ville est pas valider ";
            }
            if (empty($prix)) {
                $message_prix = "Votre prix est pas valider ";
            }
            if (empty($categorie) ) {
                $message_categorie = "Votre categorie est pas valider ";
            }
            if (empty($message_titre) && empty($message_description) && empty($message_ville) && empty($message_prix) && empty($message_categorie) || (!empty($message_image1) || !empty($message_image2) || !empty($message_image3) || !empty($message_image4) || !empty($message_image5) || !empty($message_image6) )) {
                $name = $_FILES["imagePrincipale"]["name"];
                $size = $_FILES["imagePrincipale"]["size"];
                $error = $_FILES["imagePrincipale"]["error"];
                $tmp_name = $_FILES["imagePrincipale"]["tmp_name"];
                $chek_type_image = array("png","jpeg","jpg");
                $message_image ="";
                if ($error === 0 ) {
                    if ($size < 2000000) {
                        $image_exetesion = pathinfo($name,PATHINFO_EXTENSION);
                        $image_ex_lower = strtolower($image_exetesion);
                        if (in_array($image_ex_lower,$chek_type_image)) {
                            $new_name = uniqid("IMG",true).".".$image_ex_lower;
                            $image_profil = "image/".$new_name;
                            move_uploaded_file($tmp_name,$image_profil);
                            #ajout  a base de donner 
                            
                            $sql_add_annonce = "INSERT INTO `addannonce`( `titre`, `description`, `prix`, `ville`, `imagePoste`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `email_annonce`,`categori`) VALUES (:titre,:descriptione,:prix,:ville,:imagePrincipale,:image1,:image2,:image3,:image4,:image5,:image6,:email_annonce,:categori)";
                            $add_annonce = $connexion->prepare($sql_add_annonce);
                            $add_annonce->bindParam(":titre",$titre);
                            $add_annonce->bindParam(":descriptione",$description);
                            $add_annonce->bindParam(":ville",$ville);
                            $add_annonce->bindParam(":prix",$prix);
                            $add_annonce->bindParam(":imagePrincipale",$new_name);
                            $add_annonce->bindParam(":image1",$image1);
                            $add_annonce->bindParam(":image2",$image2);
                            $add_annonce->bindParam(":image3",$image3);
                            $add_annonce->bindParam(":image4",$image4);
                            $add_annonce->bindParam(":image5",$image5);
                            $add_annonce->bindParam(":image6",$image6);     
                            $add_annonce->bindParam(":email_annonce",$email);
                            $add_annonce->bindParam(":categori",$categorie);
                            $add_annonce->execute();
                            $message_total_sucsses = "Votre annonce est ajouter avec sucsses ";
                            if(!(empty($_POST["sous1"]) && empty($_POST["sous1"]) && empty($_POST["sous1"]) && empty($_POST["sous1"]) && empty($_POST["sous1"]))){
                                $sous1 = $_POST["sous1"];
                                $sous2 = $_POST["sous2"];
                                $sous3 = $_POST["sous3"];
                                $sous4 = $_POST["sous4"];
                                $sous5 = $_POST["sous5"];
                                $sous6 = $_POST["sous6"];
                                $sql_add_cat = "INSERT INTO `categori_sous_categorie`(`categori`, `sous1`, `sous2`, `sous3`, `sous4`, `sous5`, `sous6`) VALUES (:categori, :sous1, :sous2, :sous3, :sous4, :sous5, :sous6)";
                                $add_cat = $connexion->prepare($sql_add_cat);
                                $add_cat->bindParam(":categori",$categorie);
                                $add_cat->bindParam(":sous1",$sous1);
                                $add_cat->bindParam(":sous2",$sous2);
                                $add_cat->bindParam(":sous3",$sous3);
                                $add_cat->bindParam(":sous4",$sous4);
                                $add_cat->bindParam(":sous5",$sous5);
                                $add_cat->bindParam(":sous6",$sous6);
                                $add_cat->execute();

                            }
    
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
                $message_total_error = "Votre annonce n'est pas ajouter ";

            }


    }

}









?>










<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une annonce</title>
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
    .image-preview {
        max-width: 300px;
        max-height: 300px; 
        border-radius: 50%;
        overflow: hidden; 
        transition: transform 0.3s ease;
    }
    
    .image-preview:hover {
        transform: scale(1.1); 
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







<div class="container mt-5 mb-5">
    <h1>Ajouter une annonce</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" class="form-control" id="titre" name="titre">
        </div>
        <?php if (!empty($message_titre)) { ?>
            <div class="alert alert-danger"><?php echo $message_titre; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <?php if (!empty($message_description)) { ?>
            <div class="alert alert-danger"><?php echo $message_description; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="prix">Prix :</label>
            <input type="number" class="form-control" id="prix" name="prix">
        </div>
        <?php if (!empty($message_prix)) { ?>
            <div class="alert alert-danger"><?php echo $message_prix; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="ville">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville">
        </div>
        <?php if (!empty($message_ville)) { ?>
            <div class="alert alert-danger"><?php echo $message_ville; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie_selected" id="categorie">
                <option value=""></option>
                <?php foreach ($categorie_afficher as $categorie_aff) { ?>
                    <option value="<?php echo $categorie_aff["categori"]; ?>"><?php echo $categorie_aff["categori"]; ?></option>
                <?php } ?>
            </select>
        </div>
        <?php if (!empty($message_categorie)) { ?>
            <div class="alert alert-danger"><?php echo $message_categorie; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="categorie">Donner une catégorie et sous-catégories s'il n'existe pas :</label>
            <input type="text" class="form-control" name="new_categorie" placeholder="Catégorie">
            <input type="text" class="form-control" name="sous1" placeholder="Sous-catégorie 1">
            <input type="text" class="form-control" name="sous2" placeholder="Sous-catégorie 2">
            <input type="text" class="form-control" name="sous3" placeholder="Sous-catégorie 3">
            <input type="text" class="form-control" name="sous4" placeholder="Sous-catégorie 4">
            <input type="text" class="form-control" name="sous5" placeholder="Sous-catégorie 5">
            <input type="text" class="form-control" name="sous6" placeholder="Sous-catégorie 6">
        </div>
        <div class="form-group">
            <label for="imagePrincipale">Image principale :</label>
            <input type="file" class="form-control-file" id="imagePrincipale" name="imagePrincipale" onchange="afficherImage(this, 'imagePrincipalePreview')">
            <br>
            <img class="image-preview" id="imagePrincipalePreview" src="" alt="Image principale">
        </div>
        <?php if (!empty($message_image)) { ?>
            <div class="alert alert-danger"><?php echo $message_image; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees1">Images détaillées 1 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees1" name="image1" onchange="afficherImage(this, 'image1Preview')">
            <br>
            <img class="image-preview" id="image1Preview" src="" alt="Image détaillée 1">
        </div>
        <?php if (!empty($message_image1) && !isset($image1)) { ?>
            <div class="alert alert-danger"><?php echo $message_image1; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees2">Images détaillées 2 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees2" name="image2" onchange="afficherImage(this, 'image2Preview')">
            <br>
            <img class="image-preview"  id="image2Preview" src="" alt="Image détaillée 2">
        </div>
        <?php if (!empty($message_image2) && !isset($image2)) { ?>
            <div class="alert alert-danger"><?php echo $message_image2; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees3">Images détaillées 3 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees3" name="image3" onchange="afficherImage(this, 'image3Preview')">
            <br>
            <img class="image-preview"  id="image3Preview" src="" alt="Image détaillée 3">
        </div>
        <?php if (!empty($message_image3) && !isset($image3)) { ?>
            <div class="alert alert-danger"><?php echo $message_image3; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees4">Images détaillées 4 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees4" name="image4" onchange="afficherImage(this, 'image4Preview')">
            <br>
            <img class="image-preview"  id="image4Preview" src="" alt="Image détaillée 4">
        </div>
        <?php if (!empty($message_image4) && !isset($image4)) { ?>
            <div class="alert alert-danger"><?php echo $message_image4; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees5">Images détaillées 5 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees5" name="image5" onchange="afficherImage(this, 'image5Preview')">
            <br>
            <img class="image-preview"  id="image5Preview" src="" alt="Image détaillée 5">
        </div>
        <?php if (!empty($message_image5) && !isset($image5)) { ?>
            <div class="alert alert-danger"><?php echo $message_image5; ?></div>
        <?php } ?>
        <div class="form-group">
            <label for="imagesDetaillees6">Images détaillées 6 :</label>
            <input type="file" class="form-control-file" id="imagesDetaillees6" name="image6" onchange="afficherImage(this, 'image6Preview')">
            <br>
            <img class="image-preview"  id="image6Preview" src="" alt="Image détaillée 6">
        </div>
        <?php if (!empty($message_image6) && !isset($image6)) { ?>
            <div class="alert alert-danger"><?php echo $message_image6; ?></div>
        <?php } ?>
        <button type="submit" class="btn btn-primary" name="submit">Ajouter l'annonce</button>
        <?php if (isset($message_total_error)) { ?>
            <div class="alert alert-danger"><?php echo $message_total_error; ?></div>
        <?php } ?>
        <?php if (isset($message_total_sucsses)) { ?>
            <div class="alert alert alert-success"><?php echo $message_total_sucsses; ?></div>
        <?php } ?>
    </form>
</div>
<script>
    function afficherImage(input, previewId) {
    var previewElement = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewElement.setAttribute('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        previewElement.setAttribute('src', '');
    }
}

</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
