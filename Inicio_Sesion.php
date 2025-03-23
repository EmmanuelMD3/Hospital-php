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

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $usuario = $_POST["usuario"];
    $contrasenia_hash = $_POST["contrasenia_hash"]; // Contraseña en texto plano (debes usar hash en producción)

    // Verificar en la tabla Empleados
    $sql_empleado = "SELECT IDEmpleado, rol FROM Empleados WHERE usuario = ? AND contrasenia_hash = ?";
    $stmt_empleado = $conn->prepare($sql_empleado);
    $stmt_empleado->bind_param("ss", $usuario, $contrasenia_hash);
    $stmt_empleado->execute();
    $result_empleado = $stmt_empleado->get_result();

    if ($result_empleado->num_rows > 0) {
        // El usuario es un empleado
        $row_empleado = $result_empleado->fetch_assoc();
        echo "Rol del empleado: " . $row_empleado["rol"]; // Mensaje de depuración
        if ($row_empleado["rol"] == 1) {
            echo "Redirigiendo a Empleados.php..."; // Mensaje de depuración
            header("Location:Empleados.php");
            exit();
        } else {
            echo "<script>alert('Acceso denegado: Rol no autorizado.');</script>";
        }
    } else {
        // Verificar en la tabla Paciente
        $sql_paciente = "SELECT IDPaciente FROM Paciente WHERE usuario = ? AND contrasenia_hash = ?";
        $stmt_paciente = $conn->prepare($sql_paciente);
        $stmt_paciente->bind_param("ss", $usuario, $contrasenia_hash);
        $stmt_paciente->execute();
        $result_paciente = $stmt_paciente->get_result();

        if ($result_paciente->num_rows > 0) {
            // El usuario es un paciente
            echo "<script>alert('Bienvenido, paciente.');</script>";
            // Aquí puedes redirigir a una página específica para pacientes
        } else {
            // Usuario no encontrado
            echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <h2>Inicio de Sesión</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <label for="usuario">Usuario:</label>
            <input
              type="text"
              id="usuario"
              name="usuario"
              placeholder="Usuario:"
              required
            />

            <label for="contrasenia_hash">Contraseña:</label>
            <input
              type="password"
              id="contrasenia_hash"
              name="contrasenia_hash"
              placeholder="Contraseña:"
              required
            />
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>