<?php
session_start();
require_once "../config/config.php";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario y la contraseña desde la base de datos
    $sql = "SELECT id, username, password, facultades FROM users WHERE username = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $username);  // Vincula el parámetro
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            // Verificar la contraseña
            $stmt->bind_result($id, $username, $hashed_password, $facultades);
            $stmt->fetch();
            
            if (password_verify($password, $hashed_password)) {
                // Guardar en la sesión los detalles del usuario
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["facultades"] = $facultades;  // Guardar las facultades

                // Redirigir según las facultades
                if ($facultades == 1) {
                    header("location: ../views/ver_cotizaciones.php");  // Usuario con facultades
                } else {
                    header("location: ../views/welcome.php");  // Usuario sin facultades
                }
                exit;
            } else {
                $_SESSION['error_message'] = "Contraseña incorrecta.";
                header("location: ../views/login.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "No se encontró ningún usuario con ese nombre.";
            header("location: ../views/login.php");
            exit;
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>
