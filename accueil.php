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
$db_handle = mysqli_connect($servername, $username, $password);
$db_found = mysqli_select_db($db_handle, $dbname);
$user_id = $_SESSION["user_id"];

if (!$db_handle) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Fonction pour récupérer les événements
function getEvents($db_handle) {
    $sql = "SELECT * FROM evenements";
    $result = mysqli_query($db_handle, $sql);
    return $result;
}

// Fonction pour récupérer les publications des amis avec le nom d'utilisateur
function getFriendsPublications($db_handle, $user_id) {
    $sql = "SELECT publications.*, users.username 
            FROM publications 
            JOIN friends ON publications.userID = friends.friend_id 
            JOIN users ON publications.userID = users.id
            WHERE friends.user_id = $user_id 
            ORDER BY publications.date DESC";
    $result = mysqli_query($db_handle, $sql);
    return $result;
}

// Ajouter une nouvelle publication
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = mysqli_real_escape_string($db_handle, $_POST['write']);
    $date = date("Y-m-d H:i:s");
    $user_id = $_SESSION['user_id'];
    $image = ''; // Ajoute le code nécessaire si l'image est aussi incluse dans le form

    $sql = "INSERT INTO publications (type, date, description, image, likes, userID) 
            VALUES (0, '$date', '$description', '$image', 0, $user_id)";

    if (mysqli_query($db_handle, $sql)) {
        echo "Nouvelle publication ajoutée avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . mysqli_error($db_handle);
    }
}

$events = getEvents($db_handle);
$friendsPublications = getFriendsPublications($db_handle, $user_id);

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
    <link rel="stylesheet" type="text/css" href="CSSaccueil.css">
    <link rel="stylesheet" type="text/css" href="global.css">
</head>
<body>
    <nav class="wrapper">
    <?php include 'head.php'; ?>
        <div id="section" style="border:solid">
            <div id="EventHebdo">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Evénement de la semaine :</h3>
                <?php
                if (mysqli_num_rows($events) > 0) {
                    while($event = mysqli_fetch_assoc($events)) {
                        echo "<div class='evenement'>";
                        echo "<img src='" . $event["image"] . "' alt='" . $event["titre"] . "' style='width:100px;height:100px;'>";
                        echo "<h4>" . $event["titre"] . " à " . $event["lieu"] . " le " . $event["date"] . " à " . $event["heure"] . ": </h4>";
                        echo "<p>" . $event["description"] . "</p>";
                        echo "</div><hr>";
                    }
                } else {
                    echo "<p>Aucun événement trouvé.</p>";
                }
                ?>
            </div>

            <div id="EventPerso">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Fil d'actualité :</h3>
                <?php
                if (mysqli_num_rows($friendsPublications) > 0) {
                    while($publication = mysqli_fetch_assoc($friendsPublications)) {
                        echo "<div class='publication'>";
                        echo "<p>Le " . $publication["date"] . ", " . $publication["username"] . " a publié : </p>";
                        echo "<p>" . $publication["description"] . "</p>";
                        echo "</div><hr>";
                    }
                } else {
                    echo "<p>Aucune publication trouvée.</p>";
                }
                ?>
            </div>

            <nav class="post" style="border: solid; border: outset; margin: 2px;">
                <form method="post" action="accueil.php">
                    <label for="write">Créer un post</label><br>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-7">
                                <textarea name="write" id="write" cols="50" rows="10" wrap="hard" placeholder="Comment vous sentez-vous aujourd'hui?" required></textarea>
                            </div>
                            <div class="col-sm-5">
                                <label for="image_uploads"><img src="Images/logoPhoto.jpg" width="120" height="100" alt="Appareil photo"></label>
                                <input type="file" id="image_uploads" name="image_uploads" accept=".jpg, .jpeg, .png" style="display:none">
                                <button type="submit" style="margin-top: 10%; margin-left: 3%;">Publier</button>
                                <fieldset>
                                    <p>A qui voulez-vous le partager ?</p>
                                    <div>
                                        <input type="radio" id="friend" name="secu" value="Amis" checked>
                                        <label for="friend">Vos amis</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="all" name="secu" value="tous">
                                        <label for="all">Tout le monde</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <label for="start">Quand ?</label>
                    <input type="datetime-local" id="date" name="date" value="2023-03-22" min="2015-01-01" max="2026-12-31" style="text-align: left">
                    <label for="where" style="margin-left: 10%;">Où ?</label>
                    <input type="text" id="lieu" name="lieu" style="margin-left: 10%;">
                </form>
            </nav>
        </div>

        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6" style="border: solid black; padding:2px">
                        <p style="margin-top:10%;">Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.</p>
                    </div>
                    <div class="col-sm-6" style="border: solid black; padding:2px">
                        <p style="text-align: center;">Nous contacter</p>
                        <a href="mailto:romain.barriere@edu.ece.fr">Mail</a><br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d'ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
