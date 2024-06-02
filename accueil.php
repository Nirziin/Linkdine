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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['write'])) {
    $content = $conn->real_escape_string($_POST['write']);
    $date = $conn->real_escape_string($_POST['date']);
    $visibility = $conn->real_escape_string($_POST['visibility']);
    $type = 0; // Vous pouvez changer cela si nécessaire
    $likes = 0;

    $current_date = date("Y-m-d\TH:i");
    if ($date < $current_date) {
        $date = $current_date;
    }

    $image = "";
    if (isset($_FILES['image_uploads']) && $_FILES['image_uploads']['size'] > 0) {
        $image = file_get_contents($_FILES['image_uploads']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO publications (type, date, description, image, likes, userID, visibility) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiis", $type, $date, $content, $image, $likes, $user_id, $visibility);

    if ($stmt->execute()) {
        // Redirection après la création de la publication pour éviter la duplication
        header("Location: accueil.php");
        exit();
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name'])) {
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_description = $conn->real_escape_string($_POST['event_description']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $event_time = $conn->real_escape_string($_POST['event_time']);
    $event_lieu = $conn->real_escape_string($_POST['event_lieu']);

    $stmt = $conn->prepare("INSERT INTO evenements (titre, description, date, heure, lieu) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_name, $event_description, $event_date, $event_time, $event_lieu);

    if ($stmt->execute()) {
        // Redirection après la création de l'événement pour éviter la duplication
        header("Location: accueil.php");
        exit();
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE-in</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="CSSaccueil.css">
    <link rel="stylesheet" type="text/css" href="global.css">
</head>
<body>
    <nav class = "wrapper" style = "background-color:#2C978C">
        <?php include 'head.php'; ?>
        <div id="section" style="border:solid">
            <div id="EventHebdo">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Evénement de la semaine :</h3>
                <?php
                $events_query = "
                    SELECT e.*
                    FROM evenements e
                    WHERE e.date <= DATE_ADD(NOW(), INTERVAL 7 DAY) AND e.date >= NOW()
                    ORDER BY e.date ASC";

                $stmt = $conn->prepare($events_query);
                $stmt->execute();
                $events = $stmt->get_result();
                $stmt->close();
                ?>
                <?php if ($events->num_rows > 0): ?>
                    <?php while($event = $events->fetch_assoc()): ?>
                        <div class="event">
                            <p><strong><?php echo htmlspecialchars($event['titre']); ?></strong></p>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
                            <p>Heure: <?php echo htmlspecialchars($event['heure']); ?></p>
                            <p>Lieu: <?php echo htmlspecialchars($event['lieu']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Aucun événement trouvé.</p>
                <?php endif; ?>
            </div>
            <div id="EventPerso">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Fil d'actualité :</h3>
                <div id="publications">
                    <?php
                    $publications_query = "
                        SELECT p.*, u.username
                        FROM publications p
                        LEFT JOIN friends f ON p.userID = f.friend_id
                        JOIN users u ON p.userID = u.id
                        WHERE ((p.visibility = 'public' AND p.userID != ?) OR (p.visibility = 'friends' AND f.user_id = ?))
                        AND p.date <= NOW()
                        ORDER BY p.date DESC";

                    $stmt = $conn->prepare($publications_query);
                    $stmt->bind_param("ii", $user_id, $user_id);
                    $stmt->execute();
                    $publications = $stmt->get_result();
                    $stmt->close();
                    $conn->close();
                    ?>
                    <?php if ($publications->num_rows > 0): ?>
                        <?php while($publication = $publications->fetch_assoc()): ?>
                            <div class="publication">
                                <p><strong><?php echo htmlspecialchars($publication['username']); ?></strong></p>
                                <p><?php echo htmlspecialchars($publication['description']); ?></p>
                                <?php if ($publication['image']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($publication['image']); ?>" alt="Publication Image" width="200">
                                <?php endif; ?>
                                <p>Posté le: <?php echo htmlspecialchars($publication['date']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Aucune publication trouvée.</p>
                    <?php endif; ?>
                </div>
            </div>
            <nav class="post" style="border: solid; border: outset; margin: 2px;">
                <form method="post" action="accueil.php" enctype="multipart/form-data">
                    <label for="ameliorer">Créer un post</label><br>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-7">
                                <textarea name="write" id="write" cols="50" rows="10" wrap="hard" placeholder="Comment vous sentez vous aujourd'hui?" required></textarea>
                            </div>
                            <div class="col-sm-5">
                                <label for="image_uploads"><img src="Images/logoPhoto.jpg" width="120" height="100" alt="Appareil photo"></label>
                                <input type="file" id="image_uploads" name="image_uploads" accept=".jpg, .jpeg, .png" style="display:none">
                                <button type="submit" style="margin-top: 10%; margin-left: 3%;">Publier</button>
                            </div>
                        </div>
                    </div>
                    <label for="start">Quand ?</label>
                    <input type="datetime-local" id="date" name="date" value="<?php echo date('Y-m-d\TH:i'); ?>" min="<?php echo date('Y-m-d\TH:i'); ?>" style="text-align: left">
                    <label for="visibility">Visibilité :</label>
                    <select id="visibility" name="visibility">
                        <option value="public">Tout le monde</option>
                        <option value="friends">Amis uniquement</option>
                    </select>
                </form>
            </nav>
        </div>
    </nav>
</body>
</html>
