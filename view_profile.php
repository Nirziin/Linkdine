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

$friend_id = $_GET["id"];

$sql = "SELECT * FROM users WHERE id=$friend_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $friend = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $friend["username"]; ?>'s Profile</title>
</head>
<body>
    <h2><?php echo $friend["username"]; ?>'s Profile</h2>
    <p>Nom: <?php echo $friend["nom"]; echo " "; echo $friend["prenom"]; ?></p>
    <p>Bio: <?php echo $friend["bio"]; ?></p>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
