<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social_network";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $full_name = $_POST["full_name"];

    $sql = "INSERT INTO users (username, password, full_name) VALUES ('$username', '$password', '$full_name')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
