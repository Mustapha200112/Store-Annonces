<?php 
include("connexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["submit"])){
        $search = $_POST["search"];
        if (empty($search)) {
          header("location:".$_SERVER["PHP_SELF"]);
          
        }else{
            $sql_roquet1 = "SELECT categori FROM categori_sous_categorie where categori = :categorie or sous1 = :categorie or sous2 = :categorie or sous3 = :categorie or sous4 = :categorie or sous5 = :categorie or sous6 = :categorie";
            $resulta1 = $connexion->prepare($sql_roquet1);
            $resulta1->bindParam(":categorie",$search);
            $resulta1->execute();
            $resulta_categorie = $resulta1->fetch(PDO::FETCH_ASSOC);

            if (isset($resulta_categorie) && empty($resulta_categorie)) {
                $sql_roquet2 = "SELECT * FROM addannonce where ville LIKE CONCAT('%',:ville, '%')";
                $resulta2 = $connexion->prepare($sql_roquet2);
                $resulta2->bindParam(":ville",$search);
                $resulta2->execute();
                $annonces_ville = $resulta2->fetchAll(PDO::FETCH_ASSOC);

            }else{
                $sql_roquet3 = "SELECT * FROM addannonce where `categori` = :categorie  ";
                $resulta3 = $connexion->prepare($sql_roquet3);
                $resulta3->bindParam(":categorie",$resulta_categorie["categori"]);
                $resulta3->execute();
                $annonces_categorie = $resulta3->fetchAll(PDO::FETCH_ASSOC);


            }
            if(isset($annonces_ville) && empty($annonces_ville)){
              $sql_roquet4 = "SELECT * FROM addannonce where titre LIKE CONCAT('%',:mini_titre, '%') or description LIKE CONCAT('%',:mini_titre, '%')";
              $resulta4 = $connexion->prepare($sql_roquet4);
              $resulta4->bindParam(":mini_titre",$search);
              $resulta4->execute();
              $annonces_titre = $resulta4->fetchAll(PDO::FETCH_ASSOC);

            }
        }
        if (((isset($annonces_ville) && empty($annonces_ville)) || (isset($annonces_categorie) && empty($annonces_categorie)))&& (isset($annonces_titre) && empty($annonces_titre))  ) {
            $message_total = "aucune annonces trouver";
        }
        


    }
}else{

    $sql_affiche = "SELECT * FROM addannonce";
    $resulta_normal = $connexion->prepare($sql_affiche);
    $resulta_normal->execute();
    $annonces_normal = $resulta_normal->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_GET["pagination"])) {
      $num_pagi = $_GET["pagination"];
    }else{
      $num_pagi = 1;

    }
    $annonces_normal_limit = array_slice($annonces_normal,($num_pagi*6)-6,$num_pagi*6);
    $slider_element = array_slice($annonces_normal,-5);
    $nombre_annonce_afficher = count($annonces_normal)/6;



}
if (isset($_GET["déconnecter"])) {
  session_start();
  session_destroy();
  header("location:".$_SERVER["PHP_SELF"]);

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
    .next_prev{
      background-color: #343a40;
    }
    .inverse-colors {
      color: #fff;
      background-color: #0d6efd;
    }
  </style>
</head> 
<body>
  <?php session_start(); if (isset($_SESSION["donner"]) ) {
      $donner = $_SESSION["donner"];

    
    ?>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Basique store</a>


    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="historique.php">Historique</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ajoute.php">Ajouter une annonce</a>
      </li>
      <li class="nav-item">
      <form class="form-inline my-2 my-lg-0 ml-auto ml-2" method="post">
        <input class="form-control mr-sm-2" type="text" placeholder="Rechercher" name="search">
        <button class="btn btn-outline-light my-2 my-sm-0" type="submit" name="submit">Rechercher</button>
      </form>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?déconnecter">Se déconnecter</a>
      </li>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="profil.php">
        <img src="image_profil/<?php
               
               echo $donner["imageProfil"] ;

             
             
             
             ?>" alt="Photo de profil" class="profile-pic" style="width: 40px; height: 40px; border-radius: 50%;">
          
          <?php echo $donner["nom"]." ".$donner["prenom"] ?>
        </a>
      </li>
    </ul>
  </div>
</nav>




<?php   
  }else{
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
    <a class="navbar-brand" href="index.php">Basique store</a>

        <div class="collapse navbar-collapse">
            <form class="form-inline my-2 my-lg-0 ml-auto" method="post">
                <input class="form-control mr-sm-2" type="text" placeholder="Rechercher" name="search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit" name="submit">Rechercher</button>
            </form>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Contactez-nous</a>
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


<?php
  }
?>






<?php 

if (isset($resulta_normal) && count($annonces_normal)>3) {
  $counteur = 1;
?>
  <div id="myCarousel" class="carousel slide container mt-5" data-ride="carousel">
  <h1 class="text-center">Voici les annonces du jour </h1>
      <ol class="carousel-indicators">

  <?php $counteur_slider =0;  foreach ($slider_element as $element) { if ($element == $slider_element[0]) {?>
          <li data-target="#myCarousel" data-slide-to="<?= $counteur_slider?>" class="active"></li>

  <?php }else{ ?>

      <li data-target="#myCarousel" data-slide-to="<?= $counteur_slider?>"></li>


    
 <?php   } $counteur_slider++ ;}?>

    </ol>
    <div class="carousel-inner">

    <?php $counteur_slider_img =0;  foreach ($slider_element as $element) { if ($element == $slider_element[0]) {?>
    <div class="carousel-item active">
        <a href="detaille.php?numero=<?php echo $element["numero"]?>"><img src="image/<?php echo $element["imagePoste"]?>" class="d-block w-100 fixed-size-image" alt="Image <?= $counteur_slider_img?>"></a>
    </div>
    <?php }else{ ?>


        <div class="carousel-item">
        <a href="detaille.php?numero=<?php echo $element["numero"]?>"><img src="image/<?php echo $element["imagePoste"]?>" class="d-block w-100 fixed-size-image" alt="Image <?= $counteur_slider_img?>"></a>
        </div>


    <?php   } $counteur_slider_img++ ;}?>

    </div>
    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
      <span class="carousel-control-prev-icon next_prev"></span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" data-slide="next">
      <span class="carousel-control-next-icon next_prev"></span>
    </a>
    </div>

    <?php } ?>    


<div class="container">
  <h1 class="text-center">Annonces</h1>
  <div class="row">
    
<?php 

if (isset($annonces_ville)&& !empty($annonces_ville)) {
?>

    <?php foreach ($annonces_ville as $annonce){?>
      <a class="col-md-4" href="detaille.php?numero=<?php echo $annonce["numero"]?>">
        <div >
        <div class="package animate fadeIn">
          <img src="image/<?php echo $annonce["imagePoste"];?>" alt="Package Image" class="img-fluid" style="width: 300px; height: 200px;>
          <h2 class="package-title"><?php echo $annonce["titre"];?></h2>
          <p class="package-description"><?php echo substr($annonce["description"],0,100)."..." ;?></p>
          <p class="package-price"><?php echo $annonce["prix"];?> DH</p>
          <p class="package-city"><?php echo $annonce["ville"];?></p>
            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
            <button class="btn btn-primary " name="detaille">Voir les détails</button>
        </div>
      </div>
    </a>
    <?php } ?>


<?php 

}elseif(isset($annonces_categorie)&& !empty($annonces_categorie)){

?>
    <?php foreach ($annonces_categorie as $annonce){ ?>
      <a class="col-md-4" href="detaille.php?numero=<?php echo $annonce["numero"]?>">
        <div >
        <div class="package animate fadeIn">
          <img src="image/<?php echo $annonce["imagePoste"];?>" alt="Package Image" class="img-fluid" style="width: 300px; height: 200px;>
          <h2 class="package-title"><?php echo $annonce["titre"];?></h2>
          <p class="package-description"><?php echo substr($annonce["description"],0,100)."..." ;?></p>
          <p class="package-price"><?php echo $annonce["prix"];?> DH</p>
          <p class="package-city"><?php echo $annonce["ville"];?></p>
            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
            <button class="btn btn-primary " name="detaille">Voir les détails</button>
        </div>
      </div>
    </a>
      <?php } ?>

<?php 
}
if (isset($resulta_normal)) {
?>

<?php foreach ($annonces_normal_limit as $annonce){ ?>
  <a class="col-md-4" href="detaille.php?numero=<?php echo $annonce["numero"]?> ">
        <div >
        <div class="package animate fadeIn">
          <img src="image/<?php echo $annonce["imagePoste"];?>" alt="Package Image" class="img-fluid" style="width: 300px; height: 200px;>
          <h2 class="package-title"><?php echo $annonce["titre"];?></h2>
          <p class="package-description"><?php echo substr($annonce["description"],0,100)."..." ;?></p>
          <p class="package-price"><?php echo $annonce["prix"];?> DH</p>
          <p class="package-city"><?php echo $annonce["ville"];?></p>
            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
            <button class="btn btn-primary " name="detaille">Voir les détails</button>
        </div>
      </div>
    </a>
<?php } ?>

<?php
    
}

?>
<?php 

if (isset($annonces_titre) && !empty($annonces_titre)) {
?>

<?php foreach ($annonces_titre as $annonce){ ?>
  <a class="col-md-4" href="detaille.php?numero=<?php echo $annonce["numero"]?>">
        <div >
        <div class="package animate fadeIn">
          <img src="image/<?php echo $annonce["imagePoste"];?>" alt="Package Image" class="img-fluid" style="width: 300px; height: 200px;>
          <h2 class="package-title"><?php echo $annonce["titre"];?></h2>
          <p class="package-description"><?php echo substr($annonce["description"],0,100)."..." ;?></p>
          <p class="package-price"><?php echo $annonce["prix"];?> DH</p>
          <p class="package-city"><?php echo $annonce["ville"];?></p>
            <input style="display:none" type="text" value="<?php echo $annonce["numero"] ?>" name="numero">
            <button class="btn btn-primary " name="detaille">Voir les détails</button>
        </div>
      </div>
    </a>
<?php } ?>

<?php
    
}

?>


</div>
<nav>
    <ul class="pagination d-flex justify-content-center align-items-center">
      <?php if (isset($nombre_annonce_afficher)) {
      for ($i=0; $i <$nombre_annonce_afficher ; $i++) {
      if (isset($_GET["pagination"])&& $_GET['pagination'] == $i+1) { ?>
      <li class="page-item">
        <a class="page-link inverse-colors" href="index.php?pagination=<?= $i+1?>"><?= $i+1?></a>
      </li>
      <?php }else{      ?>

      <li class="page-item">
        <a class="page-link" href="index.php?pagination=<?= $i+1?>"><?= $i+1?></a>
      </li>
      
      <?php } }} ?>
     
    </ul>
  </nav>
</div>
<?php 
if(isset($message_total) && !empty($message_total)){
?>

<div class="alert alert-danger text-black h1 font-weight-bold text-center"><?php echo $message_total; ?></div>

<?php 

}

?>


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