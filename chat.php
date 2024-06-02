<?php
session_start();
include 'fond.php';
$user_id = $_SESSION["user_id"];
$background_color = getUserBackgroundColor($user_id);

 if (isset($_GET['logout'])){


 //Message de sortie simple
 $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>" .
 $_SESSION['name'] . "</b> a quitté la session de chat.</span><br></div>";


 $myfile = fopen(__DIR__ . "/log.html", "a") or die("Impossible d'ouvrir le fichier!" . __DIR__ . "/log.html");
 fwrite($myfile, $logout_message);
 fclose($myfile);
 session_destroy();
 sleep(1);
 header("Location: chat.php"); //Rediriger l'utilisateur
 }
 if (isset($_POST['enter'])){
 if($_POST['name'] != ""){
 $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
 }
 else{
 echo '<span class="error">Veuillez saisir votre nom</span>';
 }
 }
 function loginForm() {
 echo
 '<div id="loginform">
 <p>Veuillez saisir votre nom pour continuer!</p>
 <form action="chat.php" method="post">
 <label for="name">Nom: </label>
 <input type="text" name="name" id="name" />
 <input type="submit" name="enter" id="enter" value="Soumettre" />
 </form>
 </div>';
 }
?>

<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8" />
 <title>Exemple Chat Texto</title>
 <link rel="stylesheet" href="chat.css" />
  <link rel="stylesheet" href="global.css" />
  <style>
        body {
            background-image: <?php echo htmlspecialchars($background_color); ?> !important;
        }
    </style>
 </head>
 <body>
 <nav class = "wrapper">
	<?php
		include 'head.php';
	?>
 <?php
 if (!isset($_SESSION['name'])){
 loginForm();
 }
 else {
 ?>
 <div id="wrapper">
 <div id="menu"><p class="welcome">Bienvenue, <b><?php echo $_SESSION['name']; ?></b></p>
 <p class="logout"><a id="exit" href="#">Quitter la conversation</a></p>
 </div>
 <div id="chatbox">
 <?php
 if(file_exists("log.html") && filesize("log.html") > 0){
 $contents = file_get_contents("log.html");
 echo $contents;
 }
 ?>
 </div>
 <form name="message" action="">
 <input name="usermsg" type="text" id="usermsg" />
 <input name="submitmsg" type="submit" id="submitmsg" value="Envoyer" />
 </form>
 </div>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script type="text/javascript">
 // jQuery Document
 $(document).ready(function () {
 $("#submitmsg").click(function () {
 var clientmsg = $("#usermsg").val();
 $.post("post.php", { text: clientmsg });
 $("#usermsg").val("");
 return false;
 });
 function loadLog() {
 var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Hauteur de défilement avant la requête
 $.ajax({
 url: "log.html",
 cache: false,
 success: function (html) {
 $("#chatbox").html(html); //Insérez le log de chat dans la #chatbox div
 //Auto-scroll
 var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Hauteur de défilement apres la requête
 if(newscrollHeight > oldscrollHeight){
 $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Défilement automatique
 }
 }
 });
 }
 setInterval (loadLog, 2500);
 $("#exit").click(function () {
 var exit = confirm("Voulez-vous vraiment mettre fin à la session ?");
 if (exit == true) {
 window.location = "chat.php?logout=true";
 }
 });
 });
 </script>
 </nav>
 </body>
</html>
<?php
}
?>