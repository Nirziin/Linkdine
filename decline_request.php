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
$friend_id = $_GET["id"];

$sql = "UPDATE friend_requests SET status='declined' WHERE sender_id=$friend_id AND receiver_id=$user_id";

if ($conn->query($sql) === TRUE) {
    echo "Friend request declined.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
