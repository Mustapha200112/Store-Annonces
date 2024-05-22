<?php 
session_start();
include('connexion.php');

if ($_GET["numero"]) {
  $numero = $_GET["numero"];
  $roq_detaille = "SELECT * FROM addannonce WHERE numero = :numero";
  $result = $connexion->prepare($roq_detaille);
  $result->bindParam(":numero", $numero);
  $result->execute();
  $resultat_detaille = $result->fetch(PDO::FETCH_ASSOC);
  #numero telephon dial mol annonce 
  $sql_num = "SELECT telephone FROM `inscription` WHERE email=:em";
  $result_num = $connexion->prepare($sql_num);
  $result_num->bindParam(":em", $resultat_detaille["email_annonce"]);
  $result_num->execute();
  $resultat_tele = $result_num->fetch(PDO::FETCH_ASSOC);

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $commentaire = $_POST['commentaire'];
  if (isset($_POST["nom"])) {
    $nom_cmnt = $_POST["nom"];
  }
  if (isset($_SESSION["donner"]["email"])) {
    $nom_cmnt = $_SESSION["donner"]["email"];
  }

  if (empty($commentaire)) {
    $message_comnt = "Entrez un commentaire";
  }
  if (empty($nom_cmnt)) {
    $message_nom_cmnt = "Entrez votre nom";
  }
  if (empty($message_comnt) && empty($message_nom_cmnt)) {
    $sql_insert_commentaire = "INSERT INTO commentaire (cmnt, nom_cmnt, numero) VALUES (:commentaire, :nom, :numero)";
    $result_insert = $connexion->prepare($sql_insert_commentaire);
    $result_insert->bindParam(":commentaire", $commentaire);
    $result_insert->bindParam(":nom", $nom_cmnt);
    $result_insert->bindParam(":numero", $numero);
    $result_insert->execute();
  }
}

// Récupérer les 6 derniers commentaires avec leurs noms
$sql_select_comments = "SELECT * FROM (SELECT * FROM commentaire WHERE numero = :numero ORDER BY id DESC LIMIT 6) AS subquery ORDER BY id ASC";
$result_select = $connexion->prepare($sql_select_comments);
$result_select->bindParam(":numero", $numero);
$result_select->execute();
$commentaires = $result_select->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total de commentaires
$sql_count_comments = "SELECT COUNT(*) as total FROM commentaire WHERE numero = :numero";
$result_count = $connexion->prepare($sql_count_comments);
$result_count->bindParam(":numero", $numero);
$result_count->execute();
$total_commentaires = $result_count->fetch(PDO::FETCH_ASSOC);
$total_commentaires = $total_commentaires['total'];
  ?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails de l'annonce</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .fixed-size-image {
      height: 500px; 

    }
    .footer {
      position: relative;
      bottom: 0;
      width: 100%;
      background-color: #343a40;
      color: white;
      padding: 20px;
    }
    .comment-card {
      background-color: #f8f9fa;
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .comment-card:hover {
      transform: translateY(-5px);
    }

    .comment-author {
      color: #007bff;
      font-weight: bold;
    }

    .comment-content {
      color: #333;
    }

    .total-comments {
      color: #6c757d;
    }

    .show-comments-btn {
      background-color: #007bff;
      border: none;
    }

    .show-comments-btn:hover {
      background-color: #0069d9;
    }

    .show-comments-btn:focus {
      box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }
    .next_prev{
      background-color: #343a40;
    }


    
  </style>
</head>

<body>
<?php  if (isset($_SESSION["donner"]) ) {
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


  <div class="container mt-5 mb-5">
    <div class="annonce-details card">
      <div class="row">
        <div class="col-md-12">
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <?php if(!empty($resultat_detaille["imagePoste"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <?php } if(!empty($resultat_detaille["image1"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <?php } if(!empty($resultat_detaille["image2"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
              <?php } if(!empty($resultat_detaille["image3"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="3" ></li>
              <?php } if(!empty($resultat_detaille["image4"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
              <?php } if(!empty($resultat_detaille["image5"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
              <?php } if(!empty($resultat_detaille["image6"])){?>

              <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
              <?php } ?>

            </ol>
            <div class="carousel-inner p-5">
             <?php if(!empty($resultat_detaille["imagePoste"])){ ?>
              <div class="carousel-item active">
                <img src="image/<?php echo $resultat_detaille["imagePoste"]?>" class="d-block w-70 fixed-size-image" alt="Image 1">
              </div>
              <?php } if(!empty($resultat_detaille["image1"])){?>
              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image1"]?>" class="d-block w-70 fixed-size-image" alt="Image 2">
              </div>
              <?php } if(!empty($resultat_detaille["image2"])){?>

              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image2"]?>" class="d-block w-70 fixed-size-image" alt="Image 3">
              </div>
              <?php } if(!empty($resultat_detaille["image3"])){?>

              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image3"]?>" class="d-block w-70 fixed-size-image" alt="Image 4">
              </div>
              <?php } if(!empty($resultat_detaille["image4"])){?>

              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image4"]?>" class="d-block w-70 fixed-size-image" alt="Image 5">
              </div>
              <?php } if(!empty($resultat_detaille["image5"])){?>

              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image5"]?>" class="d-block w-70 fixed-size-image" alt="Image 6">
              </div>
              <?php } if(!empty($resultat_detaille["image6"])){?>

              <div class="carousel-item">
                <img src="imagedetaille/<?php echo $resultat_detaille["image6"]?>" class="d-block w-70 fixed-size-image" alt="Image 7">
              </div>
              <?php }?>

            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon next_prev" aria-hidden="true"></span>
              <span class="sr-only">Précédent</span>
            </a>
            <a class="carousel-control-next " href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon next_prev" aria-hidden="true"></span>
              <span class="sr-only">Suivant</span>
            </a>
          </div>
        </div>    
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card-body">
            <h3 class="card-title"><?php echo $resultat_detaille["titre"]?></h3>
            <p class="card-text"><?php echo $resultat_detaille["description"]?></p>

            <p class="card-text text-info font-weight-bold"><?php echo $resultat_detaille["ville"]?></p>
            <p class="card-text price text-primary"><?php echo $resultat_detaille["prix"]?> DH</p>
            <h3 class="card-text price text-secondary"><?php echo $resultat_tele["telephone"]?></h3>
          </div>
        </div>
        </div>
    </div> 

    <div class="mt-5">
      <h4 class="mb-4">Derniers commentaires :</h4>
      <?php foreach (array_reverse($commentaires) as $commentaire) : ?>
        <div class="card comment-card mb-3">
          <div class="card-body">
            <h5 class="card-title comment-author"><?php echo $commentaire['nom_cmnt']; ?></h5>
            <p class="card-text comment-content"><?php echo $commentaire['cmnt']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      <p class="total-comments mb-4">Total commentaires : <?php echo $total_commentaires; ?></p>
      <button class="btn btn-primary show-comments-btn">Afficher tous les commentaires</button>
    </div>
        <form method="POST">
          <div class="form-group">
            <?php 
             if (!isset($_SESSION["donner"]["email"])) {
             ?>
            <label for="nom">entrez votre nom :</label>
            <input type="text" placeholder="entrez votre nom" name="nom" class="form-control"><br>
            <?php 
            }?>
            <label for="commentaire">Ajouter un commentaire :</label>
            <textarea class="form-control" name="commentaire" id="commentaire" ></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
  </div>
  
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

