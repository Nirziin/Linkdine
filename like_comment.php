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
$comment_id = $_POST['comment_id'];

$stmt = $conn->prepare("SELECT * FROM comment_likes WHERE user_id = ? AND comment_id = ?");
$stmt->bind_param("ii", $user_id, $comment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO comment_likes (user_id, comment_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $comment_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header("Location: accueil.php");
exit();
?>
