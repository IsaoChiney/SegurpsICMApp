<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_form.php");
    exit;
}

require_once "../config/config.php";

// Validar que se envíe el formulario con los datos necesarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $prospecto = $_POST['prospecto'];
    $edad_cliente = $_POST['edad_cliente'];
    $fecha_nacimiento_cliente = $_POST['fecha_nacimiento_cliente'];
    $cp = $_POST['cp'];
    $marca = $_POST['marca'];
    $ano_auto = $_POST['ano_auto'];
    $version_auto = $_POST['version_auto'];
    $version_transmision = $_POST['version_transmision'];
    $forma_de_pago = $_POST['forma_de_pago'];
    $folio_cotizacion = $_POST['folio_cotizacion'];
    $estatus_cotizacion = $_POST['estatus_cotizacion'];
    $asesor = $_POST['asesor'];
    $nombre_asesor = $_POST['nombre_asesor'];
    $com_adicionales = $_POST['com_adicionales'];

    // Procesar imagen de última póliza
    $imgn_ultima_poliza = null;
    if (isset($_FILES['imgn_ultima_poliza']) && $_FILES['imgn_ultima_poliza']['error'] === UPLOAD_ERR_OK) {
        $imgn_ultima_poliza = file_get_contents($_FILES['imgn_ultima_poliza']['tmp_name']);
    } elseif (isset($_POST['delete_imgn_ultima_poliza']) && $_POST['delete_imgn_ultima_poliza'] == '1') {
        $imgn_ultima_poliza = null; // Eliminar la imagen
    }

    // Procesar archivo PDF
    $pdf = null;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $pdf = file_get_contents($_FILES['pdf']['tmp_name']);
    } elseif (isset($_POST['delete_pdf']) && $_POST['delete_pdf'] == '1') {
        $pdf = null; // Eliminar el PDF
    }

    // Construir la consulta SQL
    $sql = "UPDATE cotizaciones SET 
                prospecto = ?, 
                edad_cliente = ?, 
                fecha_nacimiento_cliente = ?, 
                cp = ?, 
                marca = ?, 
                ano_auto = ?, 
                version_auto = ?, 
                version_transmision = ?, 
                forma_de_pago = ?, 
                folio_cotizacion = ?, 
                estatus_cotizacion = ?, 
                asesor = ?, 
                nombre_asesor = ?, 
                com_adicionales = ?";

    if ($imgn_ultima_poliza !== null) {
        $sql .= ", imgn_ultima_poliza = ?";
    }

    if ($pdf !== null) {
        $sql .= ", pdf = ?";
    }

    $sql .= " WHERE ID = ?";

    // Preparar la consulta
    if ($stmt = $mysqli->prepare($sql)) {
        // Vincular parámetros
        if ($imgn_ultima_poliza !== null && $pdf !== null) {
            $stmt->bind_param(
                "sissssssssssssssi",
                $prospecto,
                $edad_cliente,
                $fecha_nacimiento_cliente,
                $cp,
                $marca,
                $ano_auto,
                $version_auto,
                $version_transmision,
                $forma_de_pago,
                $folio_cotizacion,
                $estatus_cotizacion,
                $asesor,
                $nombre_asesor,
                $com_adicionales,
                $imgn_ultima_poliza,
                $pdf,
                $id
            );
        } elseif ($imgn_ultima_poliza !== null) {
            $stmt->bind_param(
                "sisssssssssssssi",
                $prospecto,
                $edad_cliente,
                $fecha_nacimiento_cliente,
                $cp,
                $marca,
                $ano_auto,
                $version_auto,
                $version_transmision,
                $forma_de_pago,
                $folio_cotizacion,
                $estatus_cotizacion,
                $asesor,
                $nombre_asesor,
                $com_adicionales,
                $imgn_ultima_poliza,
                $id
            );
        } elseif ($pdf !== null) {
            $stmt->bind_param(
                "sisssssssssssssi",
                $prospecto,
                $edad_cliente,
                $fecha_nacimiento_cliente,
                $cp,
                $marca,
                $ano_auto,
                $version_auto,
                $version_transmision,
                $forma_de_pago,
                $folio_cotizacion,
                $estatus_cotizacion,
                $asesor,
                $nombre_asesor,
                $com_adicionales,
                $pdf,
                $id
            );
        } else {
            $stmt->bind_param(
                "sissssssssssssi",
                $prospecto,
                $edad_cliente,
                $fecha_nacimiento_cliente,
                $cp,
                $marca,
                $ano_auto,
                $version_auto,
                $version_transmision,
                $forma_de_pago,
                $folio_cotizacion,
                $estatus_cotizacion,
                $asesor,
                $nombre_asesor,
                $com_adicionales,
                $id
            );
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir al usuario a la página de éxito
            header("location: ../views/welcome.php");
            exit();
        } else {
            echo "Error: No se pudo actualizar la cotización. " . $stmt->error;
        }
    } else {
        echo "Error: " . $mysqli->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
