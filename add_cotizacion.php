<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cotización</title>
    <link rel="stylesheet" href="../css/cotizaciones_styles.css">
    <script>
        function calcularEdad() {
            var fechaNacimiento = new Date(document.getElementById("fecha_nacimiento_cliente").value);
            var hoy = new Date();
            var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            var m = hoy.getMonth() - fechaNacimiento.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }

            if (edad < 18) {
                alert("El cliente debe ser mayor de 18 años.");
                document.getElementById("fecha_nacimiento_cliente").value = '';
                document.getElementById("edad_cliente").value = '';
            } else {
                document.getElementById("edad_cliente").value = edad;
            }
        }
    function goBack(event) {
        event.preventDefault(); // Evita que el formulario se envíe
        window.history.back(); // Regresa a la página anterior
    }
    </script>
</head>
<body>
    <div class="container">
        <h2>Registrar Cotización</h2>
        <form action="../controllers/add_cotizacion_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="prospecto">Prospecto:</label>
                <input type="text" name="prospecto" id="prospecto" required>
            </div>
            <div class="form-group">
                <label for="edad_cliente">Edad Cliente:</label>
                <input type="number" name="edad_cliente" id="edad_cliente" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento_cliente">Fecha Nacimiento Cliente:</label>
                <input type="date" name="fecha_nacimiento_cliente" id="fecha_nacimiento_cliente" required onchange="calcularEdad()">
            </div>
            <div class="form-group">
                <label for="cp">CP:</label>
                <input type="number" name="cp" id="cp" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" name="marca" id="marca" required>
            </div>
            <div class="form-group">
                <label for="ano_auto">Año Auto:</label>
                <input type="number" name="ano_auto" id="ano_auto" required>
            </div>
            <div class="form-group">
                <label for="version_auto">Versión Auto:</label>
                <input type="text" name="version_auto" id="version_auto" required>
            </div>
            <div class="form-group">
                <label for="version_transmision">Versión Transmisión:</label>
                <select name="version_transmision" id="version_transmision" required>
                    <option value="estándar">Estándar</option>
                    <option value="automática">Automática</option>
                </select>
            </div>
            <div class="form-group">
                <label for="forma_de_pago">Forma de Pago:</label>
                <input type="text" name="forma_de_pago" id="forma_de_pago" required>
            </div>
            <!-- Generación automática del folio -->
            <div class="form-group">
                <label for="folio_cotizacion">Folio Cotización:</label>
                <input type="text" name="folio_cotizacion" id="folio_cotizacion" value="<?php echo 'FOLIO-' . uniqid(); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="com_adicionales">Comentarios Adicionales:</label>
                <textarea name="com_adicionales" id="com_adicionales"></textarea>
            </div>
            <div class="form-group">
                <label for="estatus_cotizacion">Estatus Cotización:</label>
                <input type="text" name="estatus_cotizacion" id="estatus_cotizacion" value="pendiente" readonly>
            </div>
            <div class="form-group">
                <label for="imgn_ultima_poliza">Imagen Última Póliza:</label>
                <input type="file" name="imgn_ultima_poliza" id="imgn_ultima_poliza">
            </div>
            <div class="form-group">
                <label for="pdf">PDF:</label>
                <input type="file" name="pdf" id="pdf">
            </div>

            <!-- Campos no modificables -->
            <input type="hidden" name="asesor" value="<?php echo $_SESSION['id']; ?>">
            <input type="hidden" name="nombre_asesor" value="<?php echo $_SESSION['username']; ?>">

            <input type="submit" value="Registrar">
            <!-- En add_cotizaciones.php -->

            <input type="submit" value="Regresar" onclick="goBack(event)" >
        </form>
    </div>
</body>
</html>

