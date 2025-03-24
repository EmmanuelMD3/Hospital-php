<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de conexión
$conn = include 'ConexionGlobal.php';

// Verificar si la conexión está disponible
if (!$conn) {
    die("La conexión a la base de datos no está disponible.");
}

header("Content-Type: application/json");

if (!$conn) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

$sql = "SELECT IDServicio, nombre_servicio FROM Servicio";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
    echo json_encode($servicios);
} else {
    echo json_encode(["error" => "No hay servicios en la base de datos"]);
}

$conn->close();
?>
