<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config/config.php";
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $_SESSION['error_message'] = null;  // Limpiar el mensaje de error previo

    // Verificar si el nombre de usuario ya existe
    $sql = "SELECT id FROM users WHERE username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // El nombre de usuario ya existe
            $_SESSION['error_message'] = "Este nombre de usuario ya está en uso. Por favor, elige otro nombre.";
            header("location: ../views/register_form.php");
            exit;
        } else {
            // El nombre de usuario no existe, proceder con la inserción
            $stmt->close();
            
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ss", $username, $password);
                if ($stmt->execute()) {
                    header("location: ../views/login_form.php");
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error: " . $stmt->error;
                }
            }
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error: " . $mysqli->error;
    }
    $mysqli->close();
}
?>
