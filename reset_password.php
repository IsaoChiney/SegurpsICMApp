<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <form action="../controllers/reset_password_process.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
            
            <input type="submit" value="Restablecer">
        </form>
    </div>
</body>
</html>
