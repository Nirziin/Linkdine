<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
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

// Requête SQL pour récupérer les amis de l'utilisateur
$sql = "SELECT u.id, u.username FROM users u 
        JOIN friends f ON u.id = f.friend_id 
        WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
    <h3>Your Friends</h3>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='view_profile.php?id=" . $row["id"] . "'>" . $row["username"] . "</a></li>";
            }
        } else {
            echo "You have no friends.";
        }
        ?>
    </ul>
    <h3>Friend Requests</h3>
    <ul>
        <?php
        // Requête SQL pour récupérer les demandes d'amis en attente
        $sql_requests = "SELECT u.id, u.username FROM users u 
                         JOIN friend_requests fr ON u.id = fr.sender_id 
                         WHERE fr.receiver_id = ? AND fr.status = 'pending'";
        $stmt_requests = $conn->prepare($sql_requests);
        $stmt_requests->bind_param("i", $user_id);
        $stmt_requests->execute();
        $result_requests = $stmt_requests->get_result();

        if ($result_requests->num_rows > 0) {
            while($row = $result_requests->fetch_assoc()) {
                echo "<li>" . $row["username"] . " 
                      <a href='accept_request.php?id=" . $row["id"] . "'>Accept</a> 
                      <a href='decline_request.php?id=" . $row["id"] . "'>Decline</a></li>";
            }
        } else {
            echo "No friend requests.";
        }
        ?>
    </ul>
    <a href="add_friend.php">Add Friend</a>
</body>
</html>

<?php
$conn->close();
?>
