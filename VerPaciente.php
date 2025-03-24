<?php
session_start(); // Iniciar la sesión

// Incluir el archivo de conexión
$conn = include 'ConexionGlobal.php';

// Verificar si la conexión está disponible
if (!$conn) {
    die("La conexión a la base de datos no está disponible.");
}

// Eliminar paciente si se recibe un ID válido por GET
if (isset($_GET["eliminar"])) {
    $id = intval($_GET["eliminar"]); // Sanitizar el ID
    $sql_delete = "DELETE FROM Paciente WHERE IDPaciente = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Paciente eliminado correctamente.'); window.location.href='Pacientes.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el paciente.');</script>";
    }
    $stmt->close();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $nss = $_POST['nss'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $contrasenia_hash = password_hash($_POST['contrasenia'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $direccion = $_POST['direccion'];

    // Subir foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']); // Obtener la foto en formato BLOB
    } else {
        echo "<script>alert('Error al subir la foto.');</script>";
        exit;
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO Paciente (nombre, apellidoP, apellidoM, NSS, telefono, correo, usuario, contrasenia_hash, direccion, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssb", $nombre, $apellidoP, $apellidoM, $nss, $telefono, $correo, $usuario, $contrasenia_hash, $direccion, $foto);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Paciente registrado correctamente.'); window.location.href='Pacientes.php';</script>";
    } else {
        echo "<script>alert('Error al registrar el paciente.');</script>";
    }
    $stmt->close();
}

// Obtener la lista de pacientes
$sql = "SELECT * FROM Paciente";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title>
    <link rel="stylesheet" href="styles7.css"> <!-- Aquí va la etiqueta de tu archivo CSS -->
</head>

<body>
    <div class="sidebar">
        <h2>Medico</h2>
        <ul>
            <li><a href="Medico.php" class="active">🏠 Inicio</a></li>
            <li><a href="#">👩‍⚕️ Ver Pacientes</a></li>
            <li><a href="#">🏥 Consultorios</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>👥 Gestión de Pacientes</h2>
        </header>

        <section class="pacientes-lista">
            <h3>Lista de Pacientes</h3>
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
                            echo "<td>" . htmlspecialchars($row["IDPaciente"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . " " . htmlspecialchars($row["apellidoP"]) . " " . htmlspecialchars($row["apellidoM"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["correo"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["usuario"]) . "</td>";
                            echo "<td>
                                    <a href='Pacientes.php?eliminar=" . htmlspecialchars($row["IDPaciente"]) . "' 
                                       class='delete' 
                                       onclick='return confirm(\"¿Estás seguro de que deseas eliminar este paciente?\");'>
                                       ❌ Eliminar
                                    </a>
                                    |
                                    <a href='ModificarEmpleados.php'/a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay pacientes registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="agregar-paciente">
            <h3>➕ Agregar Nuevo Paciente</h3>
            <form action="RegistroPacientes.html">
                <button type="submit" name="agregar">✔ Registrar Paciente</button>
            </form>
        </section>
    </div>
</body>

</html>

<?php
// Cerrar conexión
$conn->close();
?>
