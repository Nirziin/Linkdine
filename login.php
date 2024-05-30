<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $mail = $_POST["mail"];

    $sql = "SELECT * FROM users WHERE username='$username' AND mail='$mail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            header("Location: accueil.html");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with the provided username and mail.";
    }
}

$conn->close();
?>
