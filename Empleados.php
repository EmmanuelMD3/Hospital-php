<?php
include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar"])) {
    $nombre = $_POST["nombre"];
    $cargo = $_POST["cargo"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];

    $sql = "INSERT INTO Empleados (nombre, cargo, correo, usuario) VALUES ('$nombre', '$cargo', '$correo', '$usuario')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Empleado agregado exitosamente'); window.location='Empleados.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $sql = "DELETE FROM Empleados WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Empleado eliminado exitosamente'); window.location='Empleados.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM empleados");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="styles7.css">
</head>

<body>

    <div class="sidebar">
        <h2>Administración</h2>
        <ul>
            <li><a href="Admi.php">🏠 Inicio</a></li>
            <li><a href="Empleados.php" class="active">👥 Ver Empleados</a></li>
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
                        <th>Cargo</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["nombre"]; ?></td>
                            <td><?php echo $row["cargo"]; ?></td>
                            <td><?php echo $row["correo"]; ?></td>
                            <td><?php echo $row["usuario"]; ?></td>
                            <td>
                                <a href="Empleados.php?eliminar=<?php echo $row['id']; ?>" class="delete"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">❌
                                    Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

        <section class="agregar-empleado">
            <h3>➕ Agregar Nuevo Empleado</h3>
            <form method="POST">
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

<?php $conn->close(); ?>