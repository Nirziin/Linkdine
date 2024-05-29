<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
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

$sender_id = $_SESSION["user_id"];
$friend_username = $_POST["username"];

$sql = "SELECT id FROM users WHERE username='$friend_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $receiver_id = $row["id"];

    $sql_check = "SELECT * FROM friend_requests WHERE sender_id=$sender_id AND receiver_id=$receiver_id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {
        $sql_insert = "INSERT INTO friend_requests (sender_id, receiver_id) VALUES ($sender_id, $receiver_id)";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Friend request sent.";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    } else {
        echo "Friend request already sent.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
