<?php
// Iniciar sesión
session_start();

// Incluir archivo de configuración
require_once "../config/config.php";

// Definir variables e inicializar con valores vacíos
$username = $password = "";
$username_err = $password_err = "";

// Procesar datos del formulario cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validar nombre de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingrese un nombre de usuario.";
    } else {
        // Preparar una declaración select
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = $mysqli->prepare($sql)) {
            // Enlazar variables a la declaración preparada como parámetros
            $stmt->bind_param("s", $param_username);
            
            // Establecer parámetros
            $param_username = trim($_POST["username"]);
            
            // Intentar ejecutar la declaración preparada
            if ($stmt->execute()) {
                // Guardar resultado
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $username_err = "Este nombre de usuario ya está en uso.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Cerrar declaración
            $stmt->close();
        }
    }
    
    // Validar contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingrese una contraseña.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Verificar errores de entrada antes de insertar en la base de datos
    if (empty($username_err) && empty($password_err)) {
        
        // Preparar una declaración insert
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if ($stmt = $mysqli->prepare($sql)) {
            // Enlazar variables a la declaración preparada como parámetros
            $stmt->bind_param("ss", $param_username, $param_password);
            
            // Establecer parámetros
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Crear un hash de la contraseña
            
            // Intentar ejecutar la declaración preparada
            if ($stmt->execute()) {
                // Redirigir a la página de inicio de sesión
                header("location: login_form.php");
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Cerrar declaración
            $stmt->close();
        }
    }
    
    // Cerrar conexión
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/register_style.css">
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" name="username" id="username" required>
                <span><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                </div>
                <span><?php echo $password_err; ?></span>
            </div>
            <input type="submit" value="Registrar">
        </form>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var togglePassword = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "🙈";
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
