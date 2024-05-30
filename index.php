<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "A CHANGER -------------------------";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

$message_erreur = ""; // Initialisation du message d'erreur

$id = $_POST['ID'];

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Index</title>
    <link rel="stylesheet" type="text/css" href="global.css">
  <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
  <nav id="container">
    <form method="post" action="">
      Nom d'utilisateur:<br>
      <input type="text" name="NomUtilisateur">
      <br>
      Adresse mail:<br>
      <input type="text" name="email">
      <br>
      Mot de passe:<br>
      <input type="password" name="mdp">
      <br><br>
      <input type="submit" value="Submit">
    </form>
  </nav>
</body>
</html>