<div id = "header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8"><h3>ECE-in : Social Media Professionnel de l'ECE Paris</h3></div>
			<div class="col-sm" ><img src="Images\ECELogo.png" width="121" height="49.5" alt="ECElogo"></div>
		</div>
	</div>
</div>
<div id = "navbar">
	<ul id="liste1">
		<li id="accueil"><a href="accueil.php">Accueil</a></li>
		<li id="reseau"><a href="reseau.html">Mon réseau</a></li>
		<li id="vous"><a href="vous.html">Vous</a></li>
		<li id="notifs"><a href="notifications.html">Notifications</a></li>
		<li id="emploisnav"><a href="emplois.html">Emplois</a></li>
		<li id="chat"><a href="chat.html">Messagerie</a></li>
		<?php if ($user_id == 1): ?>
		<li id="admin"><a href="admin.html">Admin</a></li>
		<?php else: ?>
		<li id="auteur"><a href="auteur.html">Auteur</a></li>
		<?php endif; ?>
		<li id="deco" style="float:right"><a href="index.html">Deconnexion</a></li>
	</ul>
</div>