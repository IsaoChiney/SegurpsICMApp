<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_form.php");
    exit;
}

require_once "../config/config.php";

// Obtener la cotización específica por ID
$id = $_GET['id'];
$sql = "SELECT * FROM cotizaciones WHERE ID = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cotizacion = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Cotización</title>
    <link rel="stylesheet" href="../css/edit_cotizacion_style.css">
    <script>
    function goBack(event) {
        event.preventDefault(); // Evita que el formulario se envíe
        window.history.back(); // Regresa a la página anterior
    }
    </script>

</head>
<body>
    <div class="container">
        <h2>Editar Cotización</h2>
        <form action="../controllers/edit_cotizacion_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $cotizacion['ID']; ?>">

            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="prospecto">Prospecto:</label>
                <input type="text" name="prospecto" id="prospecto" value="<?php echo $cotizacion['prospecto']; ?>" required>
            </div>
            <div class="form-group">
                <label for="edad_cliente">Edad del Cliente:</label>
                <input type="number" name="edad_cliente" id="edad_cliente" value="<?php echo $cotizacion['edad_cliente']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento_cliente">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento_cliente" id="fecha_nacimiento_cliente" value="<?php echo $cotizacion['fecha_nacimiento_cliente']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cp">Código Postal:</label>
                <input type="text" name="cp" id="cp" value="<?php echo $cotizacion['cp']; ?>" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca del Auto:</label>
                <input type="text" name="marca" id="marca" value="<?php echo $cotizacion['marca']; ?>" required>
            </div>
            <div class="form-group">
                <label for="ano_auto">Año del Auto:</label>
                <input type="number" name="ano_auto" id="ano_auto" value="<?php echo $cotizacion['ano_auto']; ?>" required>
            </div>
            <div class="form-group">
                <label for="version_auto">Versión del Auto:</label>
                <input type="text" name="version_auto" id="version_auto" value="<?php echo $cotizacion['version_auto']; ?>" required>
            </div>
            <div class="form-group">
                <label for="version_transmision">Transmisión:</label>
                <input type="text" name="version_transmision" id="version_transmision" value="<?php echo $cotizacion['version_transmision']; ?>" required>
            </div>
            <div class="form-group">
                <label for="forma_de_pago">Forma de Pago:</label>
                <input type="text" name="forma_de_pago" id="forma_de_pago" value="<?php echo $cotizacion['forma_de_pago']; ?>" required>
            </div>
            <div class="form-group">
                <label for="folio_cotizacion">Folio de Cotización:</label>
                <input type="text" name="folio_cotizacion" id="folio_cotizacion" value="<?php echo $cotizacion['folio_cotizacion']; ?>" required>
            </div>
            <div class="form-group">
                <label for="estatus_cotizacion">Estatus:</label>
                <input type="text" name="estatus_cotizacion" id="estatus_cotizacion" value="<?php echo $cotizacion['estatus_cotizacion']; ?>" required>
            </div>
            <div class="form-group">
                <label for="asesor">Asesor:</label>
                <input type="text" name="asesor" id="asesor" value="<?php echo $cotizacion['asesor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre_asesor">Nombre del Asesor:</label>
                <input type="text" name="nombre_asesor" id="nombre_asesor" value="<?php echo $cotizacion['nombre_asesor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="com_adicionales">Comentarios Adicionales:</label>
                <textarea name="com_adicionales" id="com_adicionales" rows="4"><?php echo $cotizacion['com_adicionales']; ?></textarea>
            </div>

            <!-- Imagen última póliza -->
            <div class="form-group">
                <label for="imgn_ultima_poliza">Imagen Última Póliza:</label>
                <input type="file" name="imgn_ultima_poliza" id="imgn_ultima_poliza">
                <?php if (!empty($cotizacion['imgn_ultima_poliza'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($cotizacion['imgn_ultima_poliza']); ?>" alt="Imagen última póliza" width="100">
                    <input type="checkbox" name="delete_imgn_ultima_poliza" value="1"> Eliminar imagen
                <?php endif; ?>
            </div>

            <!-- PDF -->
            <div class="form-group">
                <label for="pdf">PDF:</label>
                <input type="file" name="pdf" id="pdf">
                <?php if (!empty($cotizacion['pdf'])): ?>
                    <a href="data:application/pdf;base64,<?php echo base64_encode($cotizacion['pdf']); ?>" target="_blank">Ver PDF existente</a>
                    <br>
                    <input type="checkbox" name="delete_pdf" value="1"> Eliminar PDF
                <?php endif; ?>
            </div>

            <input type="submit" value="Actualizar Cotización">
            <br><br>
            <input type="submit" value="Regresar" onclick="goBack(event)" >
            
        </form>
    </div>
</body>
</html>
