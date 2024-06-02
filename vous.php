<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}

include 'fond.php'; // Inclure le fichier fond.php

$user_id = $_SESSION["user_id"];
$background_color = getUserBackgroundColor($user_id);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Récupérer les informations de l'utilisateur
$sql = "SELECT nom, prenom, bio, image, couleur FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom, $prenom, $bio, $image, $couleur);
$stmt->fetch();
$stmt->close();

// Vérifier et traiter le formulaire de téléchargement de l'image
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image_uploads"])) {
    $imageData = file_get_contents($_FILES["image_uploads"]["tmp_name"]);
    $imageType = mime_content_type($_FILES["image_uploads"]["tmp_name"]);

    // Vérifier si le fichier est une image
    if (strpos($imageType, 'image') !== false) {
        $sql = "UPDATE users SET image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("bi", $null, $user_id);
        $stmt->send_long_data(0, $imageData);
        if ($stmt->execute()) {
            //echo "Image successfully updated.";
        } else {
            echo "Error updating image.";
        }
        $stmt->close();
    } else {
        echo "File is not an image.";
    }
}

// Vérifier et traiter le choix de la couleur de fond
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["drone"])) {
    $selected_color = $_POST["drone"];
    $color_value = 0;

    // Mapping des valeurs de couleur
    switch ($selected_color) {
        case 'white':
            $color_value = 0;
            break;
        case 'paleturquoise':
            $color_value = 1;
            break;
        case '#71da88':
            $color_value = 2;
            break;
        case 'burlywood':
            $color_value = 3;
            break;
        case '#e05a5a':
            $color_value = 4;
            break;
    }

    $sql = "UPDATE users SET couleur = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $color_value, $user_id);
    if ($stmt->execute()) {
        $couleur = $color_value;
    } else {
        echo "Error updating background color.";
    }
    $stmt->close();
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vous</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="global.css">
    <link rel="stylesheet" type="text/css" href="vous.css">
    <style>
        body {
            background-image: <?php echo htmlspecialchars($background_color); ?> !important;
        }
    </style>
</head>
<body>
    <nav class="wrapper">
        <?php include 'head.php'; ?>
        <nav class="profil">
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    if (!empty($image)) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($image) . '" alt="Sa photo de profil" width="200">';
                    } else {
                        echo '<img src="default-profile.png" alt="Sa photo de profil" width="200">';
                    }
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="file" id="image_uploads" name="image_uploads" accept=".jpg, .jpeg, .png" onchange="previewImage();" style="display:none">
                        <button type="button" onclick="document.getElementById('image_uploads').click();">Choisir une photo</button>
                        <button type="submit" id="publish_button">Valider</button>
                    </form>
                </div>
                <div class="col-sm-8">
                    <div style="background-color: #d6a3b7; margin:2%">
                        <h1><?php echo htmlspecialchars($prenom) . " " . htmlspecialchars($nom); ?></h1>
                    </div>
                    <div style="background-color: #a7d4d4; margin:2%">
                        <h3><?php echo nl2br(htmlspecialchars($bio)); ?></h3>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Choix du fond par l'utilisateur via des boutons radio -->
        <nav class="Choix-fond">
            <h1 style="margin-top : 5%">Choisir son fond</h1>
            <form method="post" action="">
                <p>Cliquez pour choisir le fond que vous préférez </p>

                <div>
                    <input type="radio" id="blanc" name="drone" value="white" <?php echo $couleur == 0 ? 'checked' : ''; ?>>
                    <label for="blanc">Fond blanc (par défaut)</label>
                </div>

                <div>
                    <input type="radio" id="bleu" name="drone" value="paleturquoise" <?php echo $couleur == 1 ? 'checked' : ''; ?>>
                    <label for="bleu">Fond bleu</label>
                </div>

                <div>
                    <input type="radio" id="vert" name="drone" value="#71da88" <?php echo $couleur == 2 ? 'checked' : ''; ?>>
                    <label for="vert">Fond vert</label>
                </div>

                <div>
                    <input type="radio" id="creme" name="drone" value="burlywood" <?php echo $couleur == 3 ? 'checked' : ''; ?>>
                    <label for="creme">Fond crème</label>
                </div>

                <div>
                    <input type="radio" id="rouge" name="drone" value="#e05a5a" <?php echo $couleur == 4 ? 'checked' : ''; ?>>
                    <label for="rouge">Fond rouge</label>
                </div>
                <button type="submit" name="choixFond" id="refresh" value="Fond" style=" margin-top : 2%;">Sélectionner</button>
            </form>
        </nav>
        <br>
        <br>
        <nav class="Ajout-formation" style="border : solid grey 1px">
            <h1 style="margin-top : 5% ">Ajouter une formation</h1>
            <form method="post" action="">
                <div class="row">
                    <div class="col-sm-4">
                        <h5 style="margin-top:15%">Date de début :</h5>
                        <input type="date" name="datedebut" value="2023-01-01" min="1960-01-01" max="2023-12-31" style="margin : 15%">
                        <br>
                        <h5>Date de fin :</h5>
                        <input type="date" name="datefin" value="2023-06-06" min="1960-01-01" max="2040-12-31" style="margin : 15% ">
                    </div>
                    <div class="col-sm-8" >
                        <div style="margin:2%">
                            <h5>Nom de la formation : <input type="text" name="nomFormation" style="margin : 5%" required></h5>
                        </div>
                        <div style="*margin:2%">
                            <h5 style="margin:2%">Description de la formation : <textarea name="institution" id="Formation-text" rows="10" cols="50" style="margin: 3%;" required></textarea></h5>
                        </div>
                    </div>
                </div>
                <button type="submit" name="ajouterForm" value="CreerForm" style=" margin-top : 2%;">Publier</button>
            </form>
        </nav>
        <nav class="Ajout-projet" style="border : solid grey 1px">
            <h1 style="margin-top : 5% ">Ajouter un projet</h1>
            <form method="post" action="">
                <div style="margin:2%">
                    <h5>Nom du projet : <input type="text" name="nompjt" style="margin : 5%" required></h5>
                </div>
                <div style="margin:2%">
                    <h5 style="margin:2%">Description du projet : </h5><textarea name="description" id="Projet-text" rows="10" cols="50" style="margin: 3%;" required></textarea>
                </div>
                <button type="submit" name="ajouterPjt" value="CreerPjt" style=" margin : 2%;">Publier</button>
            </form>
        </nav>
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
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
