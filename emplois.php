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
    $db_handle = mysqli_connect($servername, $username, $password );
    $db_found = mysqli_select_db($db_handle, $dbname);
    $user_id = $_SESSION["user_id"];

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
</head>
<body>
    <nav class = "wrapper" style = "background-color:#2C978C">
        <?php
        include 'head.php';
    ?>
        <div class="container">
            <h2>Emplois disponibles</h2>
            <?php
            if ($db_found) {
                $sql = "SELECT titre, entreprise, type, salaire, description FROM offres";
                $result = mysqli_query($db_handle, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='informations'>";
                        echo "<div id='annonce'>";
                        echo "<h3>" . $row["titre"] . "</h3>";
                        echo "<p>Entreprise : " . $row["entreprise"] . "</p>";
                        echo "<p>Contrat : " . $row["type"] . "</p>";
                        echo "<p>Salaire : " . $row["salaire"] . " €</p>";
                        echo "<p>Description : " . $row["description"] . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>Aucune offre d'emploi disponible pour le moment.</p>";
                }

                mysqli_close($db_handle);
            } else {
                echo "<p>Erreur de connexion à la base de données.</p>";
            }
            ?>
        </div>
            


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