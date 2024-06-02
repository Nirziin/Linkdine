<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}
include 'fond.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";
$conn = new mysqli($servername, $username, $password, $dbname);
$user_id = $_SESSION["user_id"];


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$background_color = getUserBackgroundColor($user_id);

// Requête SQL pour récupérer les amis de l'utilisateur
$sql_friends = "SELECT u.id, u.username FROM users u 
                JOIN friends f ON u.id = f.friend_id 
                WHERE f.user_id = ?";
$stmt_friends = $conn->prepare($sql_friends);
$stmt_friends->bind_param("i", $user_id);
$stmt_friends->execute();
$result_friends = $stmt_friends->get_result();

// Requête SQL pour récupérer les demandes d'amis en attente
$sql_requests = "SELECT u.id, u.username FROM users u 
                 JOIN friend_requests fr ON u.id = fr.sender_id 
                 WHERE fr.receiver_id = ? AND fr.status = 'pending'";
$stmt_requests = $conn->prepare($sql_requests);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();



?>

<!DOCTYPE html>
<html>
<head>
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
    <nav class = "wrapper">
        <?php
        include 'head.php';
    ?>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
    <h3>Your Friends</h3>
    <ul>
        <?php
        if ($result_friends->num_rows > 0) {
            while($row = $result_friends->fetch_assoc()) {
                echo "<li><a href='view_profile.php?id=" . $row["id"] . "'>" . $row["username"] . "</a></li>";
            }
        } else {
            echo "You have no friends.";
        }
        ?>
    </ul>
    <h3>Friend Requests</h3>
    <ul>
        <?php
        if ($result_requests->num_rows > 0) {
            while($row = $result_requests->fetch_assoc()) {
                echo "<li>" . $row["username"] . " 
                      <a href='accept_request.php?id=" . $row["id"] . "'>Accept</a> 
                      <a href='decline_request.php?id=" . $row["id"] . "'>Decline</a></li>";
            }
        } else {
            echo "No friend requests.";
        }
        ?>
    </ul>
    <a href="add_friend.php">Add Friend</a>
  <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6" style = "border : solid black; padding:2px">
                        <p style = "margin-top:10%;">
                            Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.
                        </p>
                    </div>
                    <div class="col-sm-6" style = "border : solid black; padding:2px">
                        <p style="text-align : center;">Nous contacter</p>

                        <a href="mailto:romain.barriere@edu.ece.fr"> Mail </a>
                        <br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
<?php
$conn->close();
?>
