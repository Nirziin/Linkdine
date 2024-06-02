<?php
session_start();
//test

// Vérifiez si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit();
}
include 'fond.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";
$user_id = $_SESSION["user_id"];

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$background_color = getUserBackgroundColor($user_id);

// Supprimer temporairement un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user_id"])) {
    $delete_user_id = $_POST["delete_user_id"];
    $sql = "UPDATE users SET status = 'inactive' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_user_id);
    $stmt->execute();
    $stmt->close();
}

// Créer un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_user"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $naissance = $_POST["naissance"];
    $mail = $_POST["mail"];
    $role = $_POST["role"];
    $status = 'active';

    $sql = "INSERT INTO users (username, password, nom, prenom, naissance, mail, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $username, $password, $nom, $prenom, $naissance, $mail, $role, $status);
    $stmt->execute();
    $stmt->close();
}

// Recherche d'utilisateurs actifs
$search = isset($_POST["search"]) ? $_POST["search"] : '';
$sql_active = "SELECT id, username, nom, prenom, mail, naissance FROM users WHERE username LIKE ? AND status = 'active'";
$searchParam = "%" . $search . "%";
$stmt_active = $conn->prepare($sql_active);
$stmt_active->bind_param("s", $searchParam);
$stmt_active->execute();
$result_active = $stmt_active->get_result();

// Liste des utilisateurs inactifs
$sql_inactive = "SELECT id, username, nom, prenom, mail, naissance FROM users WHERE status = 'inactive'";
$result_inactive = $conn->query($sql_inactive);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin :: ECE-in</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" type="text/css" href="global.css">
    <style>
        body {
            background-image: <?php echo htmlspecialchars($background_color); ?> !important;
        }
    </style>
</head>
<body>
    <nav class="wrapper">
        <?php include 'head.php'; ?>
        <div id="section" style="border:solid; padding: 20px;">
            <h2>Liste des utilisateurs actifs</h2>
            <form method="post" action="" class="mb-4">
                <input type="text" name="search" placeholder="Rechercher un utilisateur" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Date de naissance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_active->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($row['mail']); ?></td>
                        <td><?php echo htmlspecialchars($row['naissance']); ?></td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Désactiver</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <h2>Liste des utilisateurs inactifs</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Date de naissance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row_inactive = $result_inactive->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row_inactive['id']); ?></td>
                        <td><?php echo htmlspecialchars($row_inactive['username']); ?></td>
                        <td><?php echo htmlspecialchars($row_inactive['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row_inactive['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($row_inactive['mail']); ?></td>
                        <td><?php echo htmlspecialchars($row_inactive['naissance']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <h2>Créer un nouvel utilisateur</h2>
            <form method="post" action="" class="mb-4">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="naissance">Date de naissance</label>
                    <input type="date" class="form-control" id="naissance" name="naissance" required>
                </div>
                <div class="form-group">
                    <label for="mail">Email</label>
                    <input type="email" class="form-control" id="mail" name="mail" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="0">Utilisateur</option>
                        <option value="1">Administrateur</option>
                    </select>
                </div>
                <button type="submit" name="create_user" class="btn btn-primary">Créer</button>
            </form>
        </div>
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6" style="border:solid black; padding:2px">
                        <p style="margin-top:10%;">
                            Bienvenue sur Link dine, le plus grand réseau professionnel mondial comptant plus de 2 utilisateurs dans plus de 0 pays et territoires du monde.
                        </p>
                    </div>
                    <div class="col-sm-6" style="border:solid black; padding:2px">
                        <p style="text-align:center;">Nous contacter</p>
                        <a href="mailto:romain.barriere@edu.ece.fr">Mail</a>
                        <br>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.3661096301935!2d2.2859856116549255!3d48.851228701091536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6701b4f58251b%3A0x167f5a60fb94aa76!2sECE%20-%20Ecole%20d&#39;ing%C3%A9nieurs%20-%20Engineering%20school.!5e0!3m2!1sfr!2sfr!4v1685461093343!5m2!1sfr!2sfr" width="100" height="100" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </footer>
    </nav>
</body>
</html>
