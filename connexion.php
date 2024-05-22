<?php


try {
    $connexion = new PDO("mysql:host=localhost;dbname=e_annonce","root","");
} catch (PDOException $message) {
    "la connexion est echoué".$message->getMessage();
    
}

?>
