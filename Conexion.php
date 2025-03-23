<?php

$servername = "localhost";
$username = "root";
$password = "Emmanuel360";
$dbname = "Hospital";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>