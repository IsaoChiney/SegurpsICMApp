<?php
session_start();
require_once "../config/config.php";

// Obtener los datos del formulario
$id = $_POST['id'];
$prospecto = $_POST['prospecto'];
$edad_cliente = $_POST['edad_cliente'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$cp = $_POST['cp'];
$marca = $_POST['marca'];
$ano_auto = $_POST['ano_auto'];
$version_auto = $_POST['version_auto'];
$version_transmision = $_POST['version_transmision'];
$forma_pago = $_POST['forma_pago'];
$folio_cotizacion = $_POST['folio_cotizacion'];
$estatus_cotizacion = $_POST['estatus_cotizacion'];
$asesor = $_POST['asesor'];
$nombre_asesor = $_POST['nombre_asesor'];
$comentarios = $_POST['comentarios'];

// Manejo de archivo de imagen (Imagen Última Póliza)
if (isset($_FILES['imagen_poliza']) && $_FILES['imagen_poliza']['error'] === UPLOAD_ERR_OK) {
    $imagen_poliza = file_get_contents($_FILES['imagen_poliza']['tmp_name']);
} else {
    $imagen_poliza = null;
}

// Manejo de archivo PDF
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    $pdf = file_get_contents($_FILES['pdf']['tmp_name']);
} else {
    $pdf = null;
}

// Preparar la consulta para actualizar la cotización
$sql = "UPDATE cotizaciones SET
            Prospecto = ?, EdadCliente = ?, FechaNacimientoCliente = ?, CP = ?, Marca = ?, AñoAuto = ?, 
            VersiónAuto = ?, VersiónTransmisión = ?, FormaPago = ?, FolioCotización = ?, EstatusCotización = ?, 
            Asesor = ?, NombreAsesor = ?, ComentariosAdicionales = ?, ImagenÚltimaPóliza = ?, PDF = ?
        WHERE ID = ?";

// Ejecutar la consulta
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssssssssssssbss", $prospecto, $edad_cliente, $fecha_nacimiento, $cp, $marca, $ano_auto, 
        $version_auto, $version_transmision, $forma_pago, $folio_cotizacion, $estatus_cotizacion, $asesor, 
        $nombre_asesor, $comentarios, $imagen_poliza, $pdf, $id);
    
    if ($stmt->execute()) {
        echo "Cotización actualizada exitosamente.";
    } else {
        echo "Error al actualizar la cotización: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
