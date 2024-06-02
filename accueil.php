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

    $image = NULL;
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

// Récupérer les publications des amis et les publications publiques
$publications_query = "
    SELECT p.*, u.username AS author_username
    FROM publications p
    LEFT JOIN friends f ON p.userID = f.friend_id
    LEFT JOIN users u ON p.userID = u.id
    WHERE (p.visibility = 'public' OR (p.visibility = 'friends' AND f.user_id = ?))
    AND p.userID != ?
    ORDER BY p.date DESC";

$stmt = $conn->prepare($publications_query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$publications = $stmt->get_result();
$stmt->close();

$conn->close();
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
    <nav class="wrapper">
        <?php include 'head.php'; ?>
        <div id="section" style="border:solid">
            <div id="EventHebdo">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Evénement de la semaine :</h3>
            </div>
            <div id="EventPerso">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Fil d'actualité :</h3>
                <div id="publications">
                    <?php if ($publications->num_rows > 0): ?>
                        <?php while($publication = $publications->fetch_assoc()): ?>
                            <div class="publication">
                                <p><?php echo htmlspecialchars($publication['description']); ?></p>
                                <?php if ($publication['image']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($publication['image']); ?>" alt="Publication Image" width="200">
                                <?php endif; ?>
                                <p>Posté par <?php echo htmlspecialchars($publication['author_username']); ?> le: <?php echo htmlspecialchars($publication['date']); ?></p>
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
                    <input type="datetime-local" id="date" name="date" value="2023-03-22" min="2015-01-01" max="2026-12-31" style="text-align: left">
                    <label for="visibility">Visibilité :</label>
                    <select id="visibility" name="visibility">
                        <option value="public">Tout le monde</option>
                        <option value="friends">Amis uniquement</option>
                    </select>
                </form>
            </nav>
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
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.285364776180058!3d48.85163477125254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fc71e95c48f%3A0xf051be22bd92bb0!2s10%20Rue%20Sextius%20Michel%2C%2075025%20Paris!5e0!3m2!1sfr!2sfr!4v1678898338663!5m2!1sfr!2sfr" width="400" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
