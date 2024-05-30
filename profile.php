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

$sql = "SELECT u.id, u.username, u.full_name FROM users u 
        JOIN friends f ON u.id = f.friend_id 
        WHERE f.user_id = $user_id";
$result = $conn->query($sql);

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
        $sql_requests = "SELECT u.id, u.username FROM users u 
                         JOIN friend_requests fr ON u.id = fr.sender_id 
                         WHERE fr.receiver_id = $user_id AND fr.status = 'pending'";
        $result_requests = $conn->query($sql_requests);

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
