<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}
include'fond.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);
$user_id = $_SESSION["user_id"];
$background_color = getUserBackgroundColor($user_id);

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

function getImageSrc($image) {
    return 'data:image/jpeg;base64,' . base64_encode($image);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $friend["username"]; ?>'s Profile</title>
    <meta charset="UTF-8">
    <title>ECE-in</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="emplois.css">
    <link rel="stylesheet" type="text/css" href="global.css">
    <style>
        body {
            background-image: <?php echo htmlspecialchars($background_color); ?> !important;
        }
    </style>
</head>
<body>
    <nav class="wrapper">
        <?php include 'head.php'; ?>
    <h2><?php echo $friend["username"]; ?>'s Profile</h2>
    <img src="<?php echo getImageSrc($friend['image']); ?>" alt="Profile Picture">
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
    <a href="reseau.php">Retour au Profil</a>
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6" style="border: solid black; padding: 2px;">
                    <p style="margin-top: 10%;">
                        Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.
                    </p>
                </div>
                <div class="col-sm-6" style="border: solid black; padding: 2px;">
                    <p style="text-align: center;">Nous contacter</p>
                    <a href="mailto:romain.barriere@edu.ece.fr">Mail</a>
                    <br>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border: 0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </footer>
    </nav>
</body>
</html>
