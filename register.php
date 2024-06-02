<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Chemin vers l'image par défaut sur votre serveur
$imagePath = 'Images/imagepardefaut.jpeg';

// Lecture de l'image par défaut en tant que données binaires (blob)
$imageData = file_get_contents($imagePath);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $mail = $_POST["mail"];
    $naissance = $_POST["naissance"];

    $sql = "INSERT INTO users (username, password, nom, prenom, naissance, mail, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $password, $nom, $prenom, $naissance, $mail, $imageData);

    if ($stmt->execute()) {
        echo "Registration successful. <a href='index.html'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
