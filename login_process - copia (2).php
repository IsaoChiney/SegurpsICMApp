<?php
session_start();  // Iniciar la sesión
require_once "../config/config.php";  // Incluir la configuración de la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];  // Obtener el nombre de usuario del formulario
    $password = $_POST['password'];  // Obtener la contraseña del formulario

    // Consulta SQL para verificar el usuario y obtener las facultades
    $sql = "SELECT id, username, password, facultades FROM users WHERE username = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("s", $username);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Almacenar los resultados
            $stmt->store_result();
            
            // Verificar si existe exactamente un usuario con ese nombre
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $hashed_password, $facultades);  // Vincular las columnas a variables
                if ($stmt->fetch()) {
                    // Verificar la contraseña
                    if (password_verify($password, $hashed_password)) {
                        // Iniciar sesión y almacenar datos en la sesión
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $db_username;  // Guardamos el nombre de usuario
                        $_SESSION["facultades"] = $facultades;  // Guardar las facultades del usuario

                        // Redirigir según el valor de facultades (privilegios)
                        if ($facultades == 1) {
                            // Si tiene privilegios para ver las cotizaciones, redirigir a la página de cotizaciones
                            header("Location: ../views/ver_cotizaciones.php");
                        } else {
                            // Si no tiene privilegios, redirigir a la página principal o mostrar un mensaje
                            $_SESSION['error_message'] = "No tienes acceso a las cotizaciones.";
                            header("Location: ../views/welcome.php");
                        }
                        exit;
                    } else {
                        // Mensaje si la contraseña es incorrecta
                        $_SESSION['error_message'] = "Contraseña incorrecta.";
                        header("Location: ../views/login.php");
                        exit;
                    }
                }
            } else {
                // Mensaje si no se encuentra el usuario
                $_SESSION['error_message'] = "No existe una cuenta con ese usuario.";
                header("Location: ../views/login.php");
                exit;
            }
        } else {
            // Mensaje si ocurre un error al ejecutar la consulta
            $_SESSION['error_message'] = "Error al conectar con la base de datos.";
            header("Location: ../views/login.php");
            exit;
        }

        // Cerrar la declaración preparada
        $stmt->close();
    }

    // Cerrar la conexión con la base de datos
    $mysqli->close();
}
?>
