<?php
// Incluir el archivo de conexión
require_once 'conexion.php';

// Verificar si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $matricula = trim($_POST["matricula"]);
    $nombre = trim($_POST["nombre"]);
    $apellidoP = trim($_POST["apellidoP"]);
    $apellidoM = trim($_POST["apellidoM"]);
    $IDServicio = trim($_POST["IDServicio"]);
    $sueldo = trim($_POST["sueldo"]);
    $correo = trim($_POST["email"]);
    $usuario = trim($_POST["usuario"]);
    $contrasenia_hash = password_hash(trim($_POST["contrasenia_hash"]), PASSWORD_DEFAULT); // Hash de la contraseña
    $roles = trim($_POST["roles"]);
    $foto = ""; // Inicializar la variable para la ruta de la foto

    // Validar y subir la foto
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $permitidos = ["image/jpeg", "image/png", "image/jpg"];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $foto = $_FILES["foto"];

        if (in_array($foto["type"], $permitidos) && $foto["size"] <= $maxSize) {
            $carpetaDestino = "uploads/";
            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true); // Crear la carpeta si no existe
            }

            $nombreArchivo = uniqid() . "_" . basename($foto["name"]);
            $rutaDestino = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($foto["tmp_name"], $rutaDestino)) {
                $foto = $rutaDestino; // Guardar la ruta de la foto
            } else {
                echo "<p style='color:red;'>Error al subir la imagen.</p>";
                exit;
            }
        } else {
            echo "<p style='color:red;'>Formato de imagen no permitido o tamaño excedido.</p>";
            exit;
        }
    } else {
        echo "<p style='color:red;'>Debe subir una imagen.</p>";
        exit;
    }

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO Empleados (matricula, nombre, apellidoP, apellidoM, IDServicio, sueldo, correo, usuario, contrasenia_hash, roles, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("isssidsssis", $matricula, $nombre, $apellidoP, $apellidoM, $IDServicio, $sueldo, $correo, $usuario, $contrasenia_hash, $roles, $foto);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Empleado registrado con éxito.</p>";
    } else {
        echo "<p style='color:red;'>Error al registrar el empleado: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close(); // Cerrar la conexión después de usarla
}
?>