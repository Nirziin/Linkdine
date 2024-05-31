<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vous</title>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="global.css">
  <link rel="stylesheet" type="text/css" href="css/vous.css">
</head>
<body>
  <nav class = "wrapper">
    <?php
		include 'head.php';
	?>
    <nav class="profil">
      <div class="row">
        <div class="col-sm-4">
          <img src="" alt="Sa photo de profil">
          <form method="post" action="" enctype="multipart/form-data">
              <input type="file" id="image_uploads" name="image_uploads" accept=".jpg, .jpeg, .png" onchange="previewImage();" style="display:none">
              <button type="submit" id="publish_button">Changer de Photo</button>
          </form>
        </div>
        <div class="col-sm-8">
          <div style="background-color: #d6a3b7; margin:2%">
            <h1>
             BARRIERE Romain
            </h1>
            <h3>
              Etudiant
            </h3>
          </div>
          <div style="background-color: #a7d4d4; margin:2%">
            <h3>
              J'aime bien céleste et les jeux videos.
            </h3>
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
          <input type="radio" id="blanc" name="drone" value="white" checked>
          <label for="blanc">Fond blanc (par défaut)</label>
        </div>

        <div>
          <input type="radio" id="bleu" name="drone" value="paleturquoise">
          <label for="bleu">Fond bleu</label>
        </div>

        <div>
          <input type="radio" id="vert" name="drone" value="#71da88">
          <label for="vert">Fond vert</label>
        </div>

        <div>
          <input type="radio" id="creme" name="drone" value="burlywood">
          <label for="creme">Fond crème</label>
        </div>

        <div>
          <input type="radio" id="rouge" name="drone" value="#e05a5a">
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
            <input type="date" name="datefin" value="2023-06-06" min="1960-01-01" max="2040-12-31"
                   style="margin : 15% ">
          </div>
          <div class="col-sm-8" >
            <div style="margin:2%">
              <h5>Titre de la formation : <input type="text" name="nom" style="margin : 5%" required> </h5>
            </div>
            <div style="*margin:2%">
              <h5 style="margin:2%">Description de la formation : <textarea name="institution" id="Formation-text" rows="10" cols="50" style="margin: 3%;" required></textarea> </h5>
            </div>
          </div>
        </div>
        <button type="submit" name="ajouterForm" value="CreerForm" style=" margin-top : 2%;">Publier</button>
      </form>
    </nav>
    <nav class="Ajout-projet" style="border : solid grey 1px">
      <h1 style="margin-top : 5% "> Ajouter un projet</h1>
      <form method="post" action="">

        <div style="margin:2%">
          <h5>Nom du projet : <input type="text" name="nompjt" style="margin : 5%" required> </h5>
        </div>
        <div style="margin:2%">
          <h5 style="margin:2%"> Description du projet : </h5><textarea name="description" id="Projet-text" rows="10" cols="50" style="margin: 3%;" required></textarea>
        </div>
        <button type="submit" name="ajouterPjt" value="CreerPjt" style=" margin : 2%;">Publier</button>
      </form>
    </nav>
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