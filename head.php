<?php
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
		$SQL = "SELECT role FROM users WHERE ID = $user_id";
		$result = mysqli_query($db_handle, $SQL);
		$db_field = mysqli_fetch_assoc($result);
		$user_role = $db_field['role'];
	}
	else{
		echo "Database pas trouvée";
	}
?>

<div id = "header" style = "background-color:#2C978C">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8"><h3>ECE-in : Social Media Professionnel de l'ECE Paris</h3></div>
			<div class="col-sm" ><img src="Images\ECELogo.png" width="121" height="49.5" alt="ECElogo"></div>
		</div>
	</div>
</div>
<div id = "navbar" style = "margin-top : 0px;">
	<ul id="liste1">
		<li id="accueil"><a href="accueil.php">Accueil</a></li>
		<li id="reseau"><a href="reseau.php">Mon réseau</a></li>
		<li id="vous"><a href="vous.php">Vous</a></li>
		<li id="notifs"><a href="notifications.php">Notifications</a></li>
		<li id="emploisnav"><a href="emplois.php">Emplois</a></li>
		<li id="chat"><a href="chat.php">Messagerie</a></li>
		<?php if ($user_role == 1): ?>
		<li id="admin"><a href="admin.php">Admin</a></li>
		<?php endif; ?>
		<li id="deco" style="float:right"><a href="index.html">Deconnexion</a></li>
	</ul>
</div>