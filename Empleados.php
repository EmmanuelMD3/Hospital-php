<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Emmanuel360";
$dbname = "Hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Eliminar empleado si se recibe un ID válido por GET
if (isset($_GET["eliminar"])) {
    $id = intval($_GET["eliminar"]); // Sanitizar el ID
    $sql_delete = "DELETE FROM Empleados WHERE IDEmpleado = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Empleado eliminado correctamente.'); window.location.href='Empleados.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el empleado.');</script>";
    }
    $stmt->close();
}

// Consultar empleados
$sql = "SELECT IDEmpleado, nombre, correo, usuario FROM Empleados";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="styles7.css"> <!-- Aquí va la etiqueta -->
</head>
<body>
    <div class="sidebar">
        <h2>Administración</h2>
        <ul>
            <li><a href="Admi.php" class="active">🏠 Inicio</a></li>
            <li><a href="Empleados.php">👥 Ver Empleados</a></li>
            <li><a href="#">🩺 Médicos</a></li>
            <li><a href="#">👩‍⚕️ Enfermeros</a></li>
            <li><a href="#">🏥 Consultorios</a></li>
            <li><a href="#">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>👥 Gestión de Empleados</h2>
        </header>

        <section class="empleados-lista">
            <h3>Lista de Empleados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["IDEmpleado"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["correo"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["usuario"]) . "</td>";
                            echo "<td>
                                    <a href='Empleados.php?eliminar=" . htmlspecialchars($row["IDEmpleado"]) . "' 
                                       class='delete' 
                                       onclick='return confirm(\"¿Estás seguro de que deseas eliminar este empleado?\");'>
                                       ❌ Eliminar
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay empleados registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="agregar-empleado">
            <h3>➕ Agregar Nuevo Empleado</h3>
            <form method="POST" action="procesar_empleados.php">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="cargo">Cargo:</label>
                <select id="cargo" name="cargo">
                    <option value="Médico">Médico</option>
                    <option value="Enfermero">Enfermero</option>
                    <option value="Administrativo">Administrativo</option>
                </select>

                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <button type="submit" name="agregar">✔ Registrar Empleado</button>
            </form>
        </section>
    </div>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>