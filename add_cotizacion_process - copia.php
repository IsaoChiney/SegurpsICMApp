<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config/config.php";

    // Verificar que las variables de sesión estén definidas
    if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
        echo "Error: No se ha iniciado sesión correctamente.";
        exit;
    }

    // Recoger los valores del formulario
    $prospecto = $_POST['prospecto'];
    $edad_cliente = $_POST['edad_cliente']; // La edad es proporcionada por JavaScript
    $fecha_nacimiento_cliente = $_POST['fecha_nacimiento_cliente'];
    $cp = $_POST['cp'];
    $marca = $_POST['marca'];
    $ano_auto = $_POST['ano_auto'];
    $version_auto = $_POST['version_auto'];
    $version_transmision = $_POST['version_transmision'];
    $forma_de_pago = $_POST['forma_de_pago'];
    $folio_cotizacion = $_POST['folio_cotizacion'];
    $estatus_cotizacion = $_POST['estatus_cotizacion'];
    $asesor = $_SESSION['id'];
    $nombre_asesor = $_SESSION['username'];
    $com_adicionales = $_POST['com_adicionales'];

    // Procesar archivo de imagen de última póliza
    $imgn_ultima_poliza = NULL;
    if (isset($_FILES['imgn_ultima_poliza']) && $_FILES['imgn_ultima_poliza']['error'] == 0) {
        // Verificar que el archivo sea una imagen válida (opcional)
        $imgn_ultima_poliza = file_get_contents($_FILES['imgn_ultima_poliza']['tmp_name']);
    }

    // Procesar archivo PDF
    $pdf = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        // Verificar que el archivo sea un PDF válido (opcional)
        $pdf = file_get_contents($_FILES['pdf']['tmp_name']);
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO cotizaciones (prospecto, edad_cliente, fecha_nacimiento_cliente, cp, marca, ano_auto, version_auto, version_transmision, forma_de_pago, folio_cotizacion, estatus_cotizacion, asesor, nombre_asesor, imgn_ultima_poliza, com_adicionales, pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("sisssissssssbssb", $prospecto, $edad_cliente, $fecha_nacimiento_cliente, $cp, $marca, $ano_auto, $version_auto, $version_transmision, $forma_de_pago, $folio_cotizacion, $estatus_cotizacion, $asesor, $nombre_asesor, $imgn_ultima_poliza, $com_adicionales, $pdf);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("location: ../views/welcome.php");  // Redirigir al usuario después de registrar la cotización
            exit;
        } else {
            // Si hay un error, mostrarlo
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: No se pudo preparar la consulta.";
    }

    // Cerrar la conexión
    $mysqli->close();
}
?>
