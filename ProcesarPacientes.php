<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de conexión
$conn = include 'ConexionGlobal.php';

// Verificar si la conexión está disponible
if (!$conn) {
    die("La conexión a la base de datos no está disponible.");
}

// Verificar si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = trim($_POST["nombre"]);
    $apellidoP = trim($_POST["apellidoP"]);
    $apellidoM = trim($_POST["apellidoM"]);
    $NSS = trim($_POST["NSS"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $usuario = trim($_POST["usuario"]);
    $contrasenia_hash = password_hash(trim($_POST["contrasenia_hash"]), PASSWORD_DEFAULT); // Hash de la contraseña
    $direccion = trim($_POST["direccion"]);
    $foto = ""; // Inicializar la variable para la foto
    $errores = [];

    // Validación de los campos
    if (strlen($nombre) < 3 || strlen($nombre) > 20) {
        $errores[] = "El nombre debe tener entre 3 y 20 caracteres.";
    }

    if (strlen($apellidoP) < 3 || strlen($apellidoP) > 20) {
        $errores[] = "El apellido paterno debe tener entre 3 y 20 caracteres.";
    }

    if (strlen($apellidoM) < 3 || strlen($apellidoM) > 20) {
        $errores[] = "El apellido materno debe tener entre 3 y 20 caracteres.";
    }

    // Validación del NSS
    if (!preg_match("/^\d{11}$/", $NSS)) {
        $errores[] = "El número de seguridad social debe tener exactamente 11 dígitos.";
    }

    // Validación del teléfono
    if (!preg_match("/^\d{10}$/", $telefono)) {
        $errores[] = "El teléfono debe tener exactamente 10 dígitos.";
    }

    // Validación del correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico no válido.";
    }

    // Validación de la foto
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $permitidos = ["image/jpeg", "image/png", "image/jpg"];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $foto = $_FILES["foto"];

        if (!in_array($foto["type"], $permitidos)) {
            $errores[] = "Formato de imagen no permitido. Debe ser JPG, JPEG o PNG.";
        }
        if ($foto["size"] > $maxSize) {
            $errores[] = "La imagen debe pesar menos de 2MB.";
        }

        // Si no hay errores en la validación de la foto, se mueve
        if (empty($errores)) {
            $carpetaDestino = "uploads/";
            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true); // Crear la carpeta si no existe
            }

            $nombreArchivo = uniqid() . "_" . basename($foto["name"]);
            $rutaDestino = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($foto["tmp_name"], $rutaDestino)) {
                $foto = $rutaDestino; // Guardar la ruta de la foto
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        }
    } else {
        $errores[] = "Debe subir una imagen.";
    }

    // Mostrar errores si los hay
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // Si no hay errores, continuar con la inserción en la base de datos
        $sql = "INSERT INTO Pacientes (nombre, apellidoP, apellidoM, NSS, telefono, correo, usuario, contrasenia_hash, direccion, foto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        // Insertar los datos en la base de datos
        $stmt->bind_param("ssssssssss", $nombre, $apellidoP, $apellidoM, $NSS, $telefono, $correo, $usuario, $contrasenia_hash, $direccion, $foto);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Paciente registrado con éxito.</p>";
        } else {
            echo "<p style='color:red;'>Error al registrar el paciente: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>
