<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="../controllers/register_process.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Contraseña:</label>
            <div class="input-container">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            
            <input type="submit" value="Registrar">
        </form>
    </div>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>
