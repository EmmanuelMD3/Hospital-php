<?php
// Incluir archivo de conexión
include 'Conexion.php';  // Este archivo maneja la conexión

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Depuración: Imprimir datos enviados por el formulario
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Obtener datos del formulario
    $usuario = $_POST["usuario"] ?? '';
    $contrasenia_plana = $_POST["contrasenia"] ?? '';

    echo "Usuario: " . $usuario . "<br>";
    echo "Contraseña en texto plano: " . $contrasenia_plana . "<br>";

    // Crear una nueva conexión
    $conn = conectar();

    // Inicializar $stmt_paciente como null
    $stmt_paciente = null;

    // Verificar en la tabla Empleados
    $sql_empleado = "SELECT IDEmpleado, contrasenia_hash, roles FROM Empleados WHERE usuario = ?";
    $stmt_empleado = $conn->prepare($sql_empleado);

    if ($stmt_empleado === false) {
        die("Error en la preparación de la consulta para empleados: " . $conn->error);
    }

    $stmt_empleado->bind_param("s", $usuario);
    $stmt_empleado->execute();
    $result_empleado = $stmt_empleado->get_result();

    if ($result_empleado->num_rows > 0) {
        $row_empleado = $result_empleado->fetch_assoc();
        $hash_almacenado = $row_empleado["contrasenia_hash"]; // Hash almacenado en la base de datos
        $rol = $row_empleado["roles"];  // Aquí definimos $rol

        // Verificar la contraseña
        if (password_verify($contrasenia_plana, $hash_almacenado)) {
            // Contraseña correcta
            if ($rol == 1) {
                // Rol 1 - Redirigir a la página de administradores
                header("Location: Admi.php");
                exit();
            } elseif ($rol == 2) {
                // Rol 2 - Redirigir a la página de médicos
                header("Location: Medico.php");
                exit();
            } elseif ($rol == 3) {
                // Rol 3 - Redirigir a la página de enfermeros
                header("Location: Enfermero.php");
                exit();
            } else {
                echo "<script>alert('Acceso denegado: Rol no autorizado.');</script>";
            }
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
        }
    } else {
        // Verificar en la tabla Pacientes
        $sql_paciente = "SELECT IDPaciente, contrasenia_hash FROM Paciente WHERE usuario = ?";
        $stmt_paciente = $conn->prepare($sql_paciente);

        if ($stmt_paciente === false) {
            die("Error en la preparación de la consulta para pacientes: " . $conn->error);
        }

        $stmt_paciente->bind_param("s", $usuario);
        $stmt_paciente->execute();
        $result_paciente = $stmt_paciente->get_result();

        if ($result_paciente->num_rows > 0) {
            $row_paciente = $result_paciente->fetch_assoc();
            $hash_almacenado = $row_paciente["contrasenia_hash"]; // Hash almacenado en la base de datos

            // Verificar la contraseña
            if (password_verify($contrasenia_plana, $hash_almacenado)) {
                header("Location: Paciente.php");
                // Redirigir a una página para pacientes si es necesario
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
            }
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
        }
    }

    // Cerrar los statement después de usarlos
    $stmt_empleado->close();
    if ($stmt_paciente !== null) {
        $stmt_paciente->close(); // Solo cerrar si está definida
    }

    // Cerrar la conexión
    $conn->close();
}
?>