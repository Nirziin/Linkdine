<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$post_id = $_POST['post_id'];

// Mettre à jour le nombre de likes dans la table publications
$stmt = $conn->prepare("UPDATE publications SET likes = likes + 1 WHERE ID = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->close();

// Insérer une nouvelle ligne dans la table likes
$stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: accueil.php");
exit();
?>
