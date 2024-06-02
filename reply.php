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
$reply = $conn->real_escape_string($_POST['reply']);

$stmt = $conn->prepare("INSERT INTO replies (comment_id, user_id, reply) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $comment_id, $user_id, $reply);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: accueil.php");
exit();
?>
