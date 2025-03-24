<?php
// Iniciar la sesión
session_start();

// Eliminar todas las variables de la sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a la página principal
header("Location: Inicio_Sesion.html"); // O la página que prefieras, como 'Admi.php' o 'index.php'
exit(); // Asegura que no se ejecuten más acciones
?>
