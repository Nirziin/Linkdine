<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";
$db_handle = mysqli_connect($servername, $username, $password);
$db_found = mysqli_select_db($db_handle, $dbname);
$user_id = $_SESSION["user_id"];

// Vérifier si la connexion à la base de données est établie
if (!$db_found) {
    die("Impossible de se connecter à la base de données: " . mysqli_connect_error());
}

// Requête SQL pour récupérer les publications non vues
$sql_publications = "SELECT p.*, u.username FROM publications p JOIN users u ON p.userID = u.id WHERE p.userID != $user_id AND p.date >= ALL (SELECT date FROM publications WHERE userID = $user_id) AND p.date <= NOW()";
$result_publications = mysqli_query($db_handle, $sql_publications);

// Requête SQL pour récupérer les événements non vus
$sql_events = "SELECT e.*, u.username FROM evenements e JOIN users u ON e.ID = u.id WHERE e.date >= ALL (SELECT MAX(date) FROM evenements) AND e.date >= NOW()";
$result_events = mysqli_query($db_handle, $sql_events);

// Fermer la connexion à la base de données
mysqli_close($db_handle);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE-in</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="emplois.css">
    <link rel="stylesheet" type="text/css" href="global.css">
    <style>
        .publication {
            margin-bottom: 20px;
        }
        .publication .description {
            max-height: 50px;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .publication .description.expanded {
            max-height: none;
        }
        .read-more {
            cursor: pointer;
            color: blue;
        }
    </style>
    <script>
        $(document).ready(function(){
            $(".read-more").click(function(){
                $(this).prev().toggleClass("expanded");
                $(this).text($(this).text() == 'Voir moins' ? 'Voir plus' : 'Voir moins');
            });
        });
    </script>
</head>
<body>
    <nav class = "wrapper" style = "background-color:#2C978C">
        <?php include 'head.php'; ?>
        <div class="container">
            <h2>Centre de notifications</h2>
            <?php
            // Afficher les publications non vues
            if (mysqli_num_rows($result_publications) > 0) {
                while ($row = mysqli_fetch_assoc($result_publications)) {
                    echo '<div class="publication">';
                    echo '<p><strong>' . htmlspecialchars($row["username"]) . '</strong></p>';
                    echo '<div class="description">' . htmlspecialchars($row["description"]) . '</div>';
                    if (strlen($row["description"]) > 50) {
                        echo '<p class="read-more">Voir plus</p>';
                    }
                    echo '<p>Posté le: ' . htmlspecialchars($row["date"]) . '</p>';
                    echo '</div>';
                }
            }
            // Afficher les événements non vus
            if (mysqli_num_rows($result_events) > 0) {
                while ($row = mysqli_fetch_assoc($result_events)) {
                    echo '<div class="publication">';
                    echo '<p><strong>' . htmlspecialchars($row["username"]) . '</strong></p>';
                    echo '<div class="description">' . htmlspecialchars($row["titre"]) . '</div>';
                    if (strlen($row["titre"]) > 50) {
                        echo '<p class="read-more">Voir plus</p>';
                    }
                    echo '<p>Date: ' . htmlspecialchars($row["date"]) . '</p>';
                    echo '<p>Heure: ' . htmlspecialchars($row["heure"]) . '</p>';
                    echo '<p>Lieu: ' . htmlspecialchars($row["lieu"]) . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
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
                        <a href="mailto:romain.barriere@edu.ece.fr">Mail</a>
                        <br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
