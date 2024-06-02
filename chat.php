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

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE-in</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="chat.css">
    <link rel="stylesheet" type="text/css" href="global.css">
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
  <br>
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
 
 <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6" style="border: solid black; padding: 2px;">
                        <p style="margin-top: 10%;">
                            Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.
                        </p>
                    </div>
                    <div class="col-sm-6" style="border: solid black; padding: 2px;">
                        <p style="text-align: center;">Nous contacter</p>
                        <a href="mailto:romain.barriere@edu.ece.fr">Mail</a>
                        <br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border: 0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
<?php
}
?>