<?php
session_start();

// Verificar si el usuario está logueado y tiene facultades
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_form.php");
    exit;
}

// Verificar si el usuario tiene facultades
if ($_SESSION["facultades"] != 1) {
    
}

require_once "../config/config.php"; 

// Obtener las cotizaciones del usuario actual
$asesor = $_SESSION["id"];
$sql = "SELECT * FROM cotizaciones WHERE asesor = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $asesor);  // Cambié el tipo de parámetro a "i" (entero) ya que es probable que 'asesor' sea un número
    $stmt->execute();
    $result = $stmt->get_result();
    $cotizaciones = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="../css/welcome_styles.css">
</head>
<body>
    <div class="container">
        <h1>Hola, <?php echo htmlspecialchars($_SESSION["username"]); ?> 👋</h1>
        <h2>Tus Cotizaciones</h2>
        <div class="table-container">
            <table class="cotizaciones-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prospecto</th>
                        <th>Edad Cliente</th>
                        <th>Fecha Nacimiento Cliente</th>
                        <th>CP</th>
                        <th>Marca</th>
                        <th>Año Auto</th>
                        <th>Versión Auto</th>
                        <th>Versión Transmisión</th>
                        <th>Forma de Pago</th>
                        <th>Folio Cotización</th>
                        <th>Estatus Cotización</th>
                        <th>Asesor</th>
                        <th>Nombre Asesor</th>
                        <th>Imagen Última Póliza</th>
                        <th>Comentarios Adicionales</th>
                        <th>PDF</th>
                        <th class="acciones-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cotizaciones as $cotizacion): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cotizacion['ID']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['prospecto']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['edad_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['fecha_nacimiento_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['cp']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['marca']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['ano_auto']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['version_auto']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['version_transmision']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['forma_de_pago']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['folio_cotizacion']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['estatus_cotizacion']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['asesor']); ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['nombre_asesor']); ?></td>
                        <td><?php echo $cotizacion['imgn_ultima_poliza'] ? '<img src="data:image/jpeg;base64,' . base64_encode($cotizacion['imgn_ultima_poliza']) . '" width="100"/>' : 'No disponible'; ?></td>
                        <td><?php echo htmlspecialchars($cotizacion['com_adicionales']); ?></td>
                        <td><?php echo $cotizacion['pdf'] ? '<a href="../controllers/download_pdf.php?id=' . $cotizacion['ID'] . '">Descargar PDF</a>' : 'No disponible'; ?></td>
                        <td>
                            <div class="action-btn-wrapper">
                                <a href="../views/edit_cotizacion.php?id=<?php echo $cotizacion['ID']; ?>" class="action-btn modify">Modificar</a>
                                <a href="../controllers/delete_cotizacion.php?id=<?php echo $cotizacion['ID']; ?>" class="action-btn delete" onclick="return confirm('¿Estás seguro de eliminar esta cotización?');">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="buttons">
            <a href="add_cotizacion.php" class="btn">Registrar Cotización</a>
            <a href="../logout.php" class="btn logout">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
