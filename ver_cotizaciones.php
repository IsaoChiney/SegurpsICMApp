<?php
session_start();

// Verificar si el usuario está logueado y tiene facultades
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_form.php");
    exit;
}

// Verificar si el usuario tiene facultades
if ($_SESSION["facultades"] != 1) {
    // Redirigir a una página sin acceso a cotizaciones
    $_SESSION['error_message'] = "No tienes acceso a las cotizaciones.";
    header("Location: login_form.php");
    exit;
}

require_once "../config/config.php"; 

// Variables para el filtrado
$searchConditions = [];
$searchValues = [];

// Si hay filtros, se aplican
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['prospecto'])) {
        $searchConditions[] = "prospecto LIKE ?";
        $searchValues[] = "%" . $_GET['prospecto'] . "%";
    }
    if (!empty($_GET['marca'])) {
        $searchConditions[] = "marca LIKE ?";
        $searchValues[] = "%" . $_GET['marca'] . "%";
    }
    if (!empty($_GET['ano_auto'])) {
        $searchConditions[] = "ano_auto = ?";
        $searchValues[] = $_GET['ano_auto'];
    }
    if (!empty($_GET['estatus_cotizacion'])) {
        $searchConditions[] = "estatus_cotizacion LIKE ?";
        $searchValues[] = "%" . $_GET['estatus_cotizacion'] . "%";
    }
}

// Construir la consulta base
$sql = "SELECT * FROM cotizaciones";

// Si hay filtros, agregamos la cláusula WHERE
if (!empty($searchConditions)) {
    $sql .= " WHERE " . implode(" AND ", $searchConditions);
}

// Ejecutar la consulta
if ($stmt = $mysqli->prepare($sql)) {
    // Vincular los parámetros
    if (count($searchValues) > 0) {
        $types = str_repeat("s", count($searchValues)); // Suponiendo que todos los campos son cadenas
        $stmt->bind_param($types, ...$searchValues);
    }
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
    <title>Ver Cotizaciones</title>
    <link rel="stylesheet" href="../css/welcome_styles.css">
</head>
<body>
    <div class="container">
        <h1>Buscar Cotizaciones</h1>

        <!-- Formulario para filtrado -->
        <form method="GET" action="ver_cotizaciones.php">
            <div class="filter-container">
                <label for="prospecto">Prospecto:</label>
                <input type="text" name="prospecto" id="prospecto" value="<?php echo isset($_GET['prospecto']) ? htmlspecialchars($_GET['prospecto']) : ''; ?>" />

                <label for="marca">Marca:</label>
                <input type="text" name="marca" id="marca" value="<?php echo isset($_GET['marca']) ? htmlspecialchars($_GET['marca']) : ''; ?>" />

                <label for="ano_auto">Año Auto:</label>
                <input type="text" name="ano_auto" id="ano_auto" value="<?php echo isset($_GET['ano_auto']) ? htmlspecialchars($_GET['ano_auto']) : ''; ?>" />

                <label for="estatus_cotizacion">Estatus Cotización:</label>
                <input type="text" name="estatus_cotizacion" id="estatus_cotizacion" value="<?php echo isset($_GET['estatus_cotizacion']) ? htmlspecialchars($_GET['estatus_cotizacion']) : ''; ?>" />

                <button type="submit" class="btn">Filtrar</button>
            </div>
        </form>

        <h2>Todas las Cotizaciones</h2>

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
