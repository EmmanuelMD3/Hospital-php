<?php
session_start(); // Iniciar la sesión

// Incluir el archivo de conexión
$conn = include 'ConexionGlobal.php';

// Verificar si la conexión está disponible
if (!$conn) {
    die("La conexión a la base de datos no está disponible.");
}

// Verificar si se ha recibido el ID del empleado a modificar
if (isset($_GET['id'])) {
    $idEmpleado = intval($_GET['id']); // Sanitizar el ID del empleado

    // Obtener los detalles del empleado
    $sqlEmpleado = "SELECT * FROM Empleados WHERE IDEmpleado = ?";
    $stmtEmpleado = $conn->prepare($sqlEmpleado);
    $stmtEmpleado->bind_param("i", $idEmpleado);
    $stmtEmpleado->execute();
    $resultadoEmpleado = $stmtEmpleado->get_result();
    $empleado = $resultadoEmpleado->fetch_assoc();
    $stmtEmpleado->close();

    if (!$empleado) {
        die("Empleado no encontrado.");
    }
} else {
    die("ID del empleado no válido.");
}

// Obtener la lista de servicios disponibles
$sqlServicios = "SELECT * FROM Servicios";
$resultServicios = $conn->query($sqlServicios);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Empleado</title>
    <link rel="stylesheet" href="styles7.css">
</head>

<body>
    <div class="main-content">
        <header>
            <h2>🖊 Modificar Empleado</h2>
        </header>

        <section class="formulario-modificar">
            <h3>Modificar Datos del Empleado</h3>
            <form action="ProcesarModificacion.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idEmpleado" value="<?php echo $empleado['IDEmpleado']; ?>">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>

                <label for="apellidoP">Apellido Paterno:</label>
                <input type="text" id="apellidoP" name="apellidoP" value="<?php echo htmlspecialchars($empleado['apellidoP']); ?>" required>

                <label for="apellidoM">Apellido Materno:</label>
                <input type="text" id="apellidoM" name="apellidoM" value="<?php echo htmlspecialchars($empleado['apellidoM']); ?>" required>

                <label for="servicio">Servicio:</label>
                <div class="select-container">
                    <select id="servicio" name="IDServicio" required>
                        <option value="">Selecciona un servicio</option>
                        <?php
                        // Si existen servicios, los mostramos en el select
                        if ($resultServicios->num_rows > 0) {
                            while ($rowServicio = $resultServicios->fetch_assoc()) {
                                // Verificar si el servicio del empleado es el que está seleccionado
                                $selected = ($empleado['IDServicio'] == $rowServicio['IDServicio']) ? 'selected' : '';
                                echo "<option value='" . $rowServicio['IDServicio'] . "' $selected>" . htmlspecialchars($rowServicio['nombre_servicio']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay servicios disponibles</option>";
                        }
                        ?>
                    </select>
                </div>

                <label for="sueldo">Sueldo:</label>
                <input type="number" id="sueldo" name="sueldo" value="<?php echo htmlspecialchars($empleado['sueldo']); ?>" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="correo" value="<?php echo htmlspecialchars($empleado['correo']); ?>" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($empleado['usuario']); ?>" required>

                <label for="contrasenia">Contraseña:</label>
                <input type="password" id="contrasenia" name="contrasenia_hash" required>

                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto">

                <button type="submit">Guardar cambios</button>
            </form>
        </section>
    </div>
</body>

</html>

<?php
// Cerrar la conexión
$conn->close();
?>
