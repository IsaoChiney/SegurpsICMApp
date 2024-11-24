<?php
session_start();
require_once "../config/config.php";

// Obtener los parámetros del archivo a eliminar
$type = $_GET['type'];
$id = $_GET['id'];

// Verificar el tipo de archivo
if ($type === 'imagen_poliza') {
    $sql = "UPDATE cotizaciones SET ImagenÚltimaPóliza = NULL WHERE ID = ?";
} elseif ($type === 'pdf') {
    $sql = "UPDATE cotizaciones SET PDF = NULL WHERE ID = ?";
} else {
    echo "Error: Tipo de archivo no válido.";
    exit;
}

// Preparar y ejecutar la consulta para eliminar el archivo
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo "Archivo eliminado exitosamente.";
    } else {
        echo "Error al eliminar el archivo: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
