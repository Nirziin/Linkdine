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
	if($db_found)
	{
		$SQL = "SELECT * FROM evenements";
		$result = mysqli_query($db_handle, $SQL);
		while ($db_field = mysqli_fetch_assoc($result) ) {
			$titre = $db_field['image'];
			$images = $db_field['image'];
			$user_role = $db_field['image'];
			$user_role = $db_field['image'];
		}

	}
	else{
		echo "Database pas trouvée";
	}
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
    <nav class = "wrapper">
	<?php
		include 'head.php';
	?>
        <div id = "section" style="border:solid">
            <div id = "EventHebdo">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Evénement de la semaine :</h3>
            </div>

            <div id = "EventPerso">
                <h3 style="text-align: center; margin:3%; text-decoration:underline;">Fil d'actualité :</h3>
            </div>
			<nav class = "post" style =" border : solid outset;">
        <form method="post" action="">
            <label for="ameliorer">Creer un post</label><br>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-7"><textarea name="write" id="write" cols = "50" rows = "10" wrap="hard" placeholder="Comment vous sentez vous aujourd'hui?" required></textarea></div>
                    <div class="col-sm-5">
                        <label for="image_uploads"><img src="Images\logoPhoto.jpg"  width="120" height="100" alt="Appareil photo . png"></label>
                        <input type="file" id="image_uploads" name="image_uploads" accept=".jpg, .jpeg, .png" style="display:none">
                        <button type="submit"  style = "margin-top : 10%; margin-left : 3%;">Publier</button>
                        <fieldset>
                            <p>A qui voulez vous le partager ?</p>

                            <div>
                                <input type="radio" id="friend" name="secu" value="Amis" checked>
                            <label for="huey">Vos amis</label>
                            </div>

                            <div>
                                <input type="radio" id="all" name="secu" value="tous">
                                <label for="dewey">Tout le monde</label>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <label for="start">Quand ?</label>
            <input type="datetime-local" id="date" name="date" value="2023-03-22" min="2015-01-01" max="2026-12-31" style = "text-align : left">
            <label for="where"style = "margin-left : 10%;">Où ?</label>
            <input type="text" id="lieu" name="lieu" style = "margin-left : 10%;">
        </form>
    </nav>
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