<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Por defecto XAMPP no tiene contraseña
define('DB_NAME', 'clothing_store');

// Crear conexión
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar charset UTF-8
$conexion->set_charset("utf8mb4");

// Función para limpiar datos de entrada
function limpiar_entrada($datos) {
    global $conexion;
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $conexion->real_escape_string($datos);
}
?>
