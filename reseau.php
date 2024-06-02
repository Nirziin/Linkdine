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

// Requête SQL pour récupérer les amis de l'utilisateur avec leurs photos
$sql_friends = "SELECT u.id, u.username, u.image FROM users u 
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
    <link rel="stylesheet" type="text/css" href="reseau.css">
    <link rel="stylesheet" type="text/css" href="global.css">
    <style>
        .carousel-item img {
            max-width: 100px;
            max-height: 100px;
            margin: auto;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="wrapper">
        <?php include 'head.php'; ?>
    
        <h2>Bienvenue, <?php echo $_SESSION["username"]; ?></h2>
        <h3>Amis : </h3>
        
        <?php if ($result_friends->num_rows > 0): ?>
        <div id="friendsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = 'active';
                while ($row = $result_friends->fetch_assoc()):
                    $image_data = base64_encode($row['image']);
                    $image_src = 'data:image/jpeg;base64,' . $image_data;
                ?>
                <div class="carousel-item <?php echo $active; ?>">
                    <img src="<?php echo $image_src; ?>" class="d-block w-100" alt="<?php echo $row['username']; ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo $row['username']; ?></h5>
                    </div>
                </div>
                <?php
                    $active = '';
                endwhile;
                ?>
            </div>
            <a class="carousel-control-prev" href="#friendsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#friendsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <?php else: ?>
            <p>You have no friends.</p>
        <?php endif; ?>
        
        <h3>Friend Requests</h3>
        <ul>
            <?php
            if ($result_requests->num_rows > 0) {
                while ($row = $result_requests->fetch_assoc()) {
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
                    <div class="col-sm-6" style="border: solid black; padding:2px">
                        <p style="margin-top:10%;">
                            Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.
                        </p>
                    </div>
                    <div class="col-sm-6" style="border: solid black; padding:2px">
                        <p style="text-align: center;">Nous contacter</p>
                        <a href="mailto:romain.barriere@edu.ece.fr"> Mail </a>
                        <br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-QgLfdRxT7FQF9cF8rHLkDaINm5Ew3phlCcgcQhXo+l3N5dTewjwpT5EzESZFRf9m" crossorigin="anonymous"></script>
</body>
</html>
<?php
$conn->close();
?>
