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

$sql_user = "SELECT * FROM users WHERE id=?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $friend_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $friend = $result_user->fetch_assoc();

    // Requête SQL pour récupérer les dernières publications de l'utilisateur
    $sql_posts = "SELECT * FROM publications WHERE userID=? ORDER BY date DESC LIMIT 5";
    $stmt_posts = $conn->prepare($sql_posts);
    $stmt_posts->bind_param("i", $friend_id);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
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
    <?php if ($result_posts->num_rows > 0): ?>
        <h3>Dernières Publications</h3>
        <ul>
            <?php while($post = $result_posts->fetch_assoc()): ?>
                <li>
                    <p>Date: <?php echo $post["date"]; ?></p>
                    <p>Description: <?php echo $post["description"]; ?></p>
                    <?php if (!empty($post["image"])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($post["image"]); ?>" alt="Publication Image" style="max-width: 300px; max-height: 300px;">
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Aucune publication récente.</p>
    <?php endif; ?>
    <a href="profile.php">Retour au Profil</a>
</body>
</html>
