<?php
function conectar() {
    $servername = "localhost";
    $username = "root";
    $password = "M22L10J08*e";  // Cambia la contraseña si es necesario
    $dbname = "Hospital";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Comprobar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}
?>