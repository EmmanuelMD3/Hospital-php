<?php
session_start(); // Iniciar la sesión

// Incluir el archivo de conexión
$conn = include 'ConexionGlobal.php';

// Verificar si la conexión está disponible
if (!$conn) {
    die("La conexión a la base de datos no está disponible.");
}

// Verificar el rol seleccionado para filtrar la consulta
$rol = isset($_GET['rol']) ? $_GET['rol'] : 'todos'; // Default es mostrar todos los empleados

// Preparar la consulta SQL basada en el rol
$sql = "SELECT * FROM Empleados";

// Si el rol es 'medico', buscamos solo médicos (roles = 1)
if ($rol == 'medico') {
    $sql .= " WHERE roles = 2"; // 1 para médicos
}
// Si el rol es 'enfermero', buscamos solo enfermeros (roles = 2)
elseif ($rol == 'enfermero') {
    $sql .= " WHERE roles = 3"; // 2 para enfermeros
}

$result = $conn->query($sql);

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
            <li><a href="Empleados.php?rol=medico">👨‍⚕️ Ver Médicos</a></li>
            <li><a href="Empleados.php?rol=enfermero">👩‍⚕️ Ver Enfermeros</a></li>
            <li><a href="#">🏥 Consultorios</a></li>
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
                            echo "<td>" . htmlspecialchars($row["nombre"]) . " " . htmlspecialchars($row["apellidoP"]) . " " . htmlspecialchars($row["apellidoM"]) . "</td>";
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
                        echo "<tr><td colspan='5'>No hay empleados registrados para este rol.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="agregar-empleado">
            <h3>➕ Agregar Nuevo Empleado</h3>
            <form action="RegistroEmpleados.html">                
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