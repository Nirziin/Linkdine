<!DOCTYPE html>
<html>
<head>
    <title>Add Friend</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <h2>Ajouter un ami:</h2>
    <form action="send_request.php" method="post">
        <label for="username">Pseudo:</label>
        <input type="text" id="username" name="username" required><br>
        <input type="submit" class="btn btn-primary mt-4" value="Add Friend">
    </form>
</body>
</html>
