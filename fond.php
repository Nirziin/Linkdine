<?php
function getUserBackgroundColor($user_id) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "social_network";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT couleur FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($couleur);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    switch ($couleur) {
        case 0:
            return 'linear-gradient(45deg, #ffffff, #f0f0f0, #e0e0e0, #d0d0d0, #c0c0c0)'; // White gradient
        case 1:
            return 'linear-gradient(45deg, #222274, #4040a7, #5353bd, #4d4de0, #4444fc)'; // Blue gradient
        case 2:
            return 'linear-gradient(45deg, #71da88, #7be6a0, #84f1b9, #8efdd2, #97ffeb)'; // Green gradient
        case 3:
            return 'linear-gradient(45deg, #e0b395, #e6c0a8, #ebd0bd, #f0e0d3, #f5f0e8)'; // Cream gradient
        case 4:
            return 'linear-gradient(45deg, #e05a5a, #e67373, #eb8d8d, #f0a7a7, #f5c0c0)'; // Red gradient
        default:
            return 'linear-gradient(45deg, #ffffff, #f0f0f0, #e0e0e0, #d0d0d0, #c0c0c0)'; // Default to white gradient
    }
}
?>