<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login_styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="login_process.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Contraseña:</label>
            <div class="input-container">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            
            <input type="submit" value="Login">
        </form>
        
        <div class="links"> 
            <p>¿No tienes una cuenta? <a href="/home/site/wwwroot/Views/register.php">Regístrate aquí</a></p>
            <p>¿Olvidaste tu contraseña? <a href="reset_password.php">Restablecer contraseña</a></p> 
        </div>
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
