<?php
// Configuración de la base de datos en Hostinger
$servername = "localhost";
$username = "u371020655_ESEAADMIN";
$password = "3d6W3b3s34";
$dbname = "u371020655_esea";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => 'Error de conexión: ' . $conexion->connect_error
    ]));
}

// Configurar charset
$conexion->set_charset("utf8mb4");
?>