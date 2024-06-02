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
$comment = $conn->real_escape_string($_POST['comment']);
$parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : NULL;

$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment, parent_comment_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $post_id, $user_id, $comment, $parent_comment_id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: accueil.php");
exit();
?>
