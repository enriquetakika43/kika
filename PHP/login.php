<?php
session_start();
header('Content-Type: application/json');
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';
    
    // Validaciones básicas
    if (empty($usuario) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son obligatorios']);
        exit;
    }
    
    // Preparar la consulta
    $stmt = $conexion->prepare("SELECT id, usuario, correo_electronico, contraseña FROM usuarios WHERE usuario = ?");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
        exit;
    }
    
    // Vincular parámetro
    $stmt->bind_param("s", $usuario);
    
    // Ejecutar consulta
    $stmt->execute();
    
    // Obtener resultado
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($contraseña, $fila['contraseña'])) {
            // Contraseña correcta - crear sesión
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['correo'] = $fila['correo_electronico'];
            
            echo json_encode(['success' => true, 'message' => 'Login exitoso. Redirigiendo...']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
    
    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>