<?php
header('Content-Type: application/json');
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';
    
    // Validaciones
    if (empty($usuario)) {
        echo json_encode(['success' => false, 'message' => 'El usuario es obligatorio']);
        exit;
    }
    
    if (empty($correo)) {
        echo json_encode(['success' => false, 'message' => 'El correo es obligatorio']);
        exit;
    }
    
    if (empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'La contraseña es obligatoria']);
        exit;
    }
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El correo no es válido']);
        exit;
    }
    
    if (strlen($contraseña) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    // Validar longitud del usuario
    if (strlen($usuario) < 3) {
        echo json_encode(['success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres']);
        exit;
    }
    
    // Encriptar contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
    
    // Obtener fecha actual
    $fecha_registro = date('Y-m-d H:i:s');
    
    // Preparar consulta
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, correo_electronico, contraseña, fecha_registro) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error de preparación: ' . $conexion->error]);
        exit;
    }
    
    // Vincular parámetros
    $stmt->bind_param("ssss", $usuario, $correo, $contraseña_hash, $fecha_registro);
    
    // Ejecutar
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Registro exitoso. Redirigiendo...'
        ]);
    } else {
        $error = $stmt->error;
        
        if (strpos($error, 'Duplicate entry') !== false) {
            if (strpos($error, 'usuario') !== false) {
                echo json_encode(['success' => false, 'message' => 'El usuario ya está registrado']);
            } else {
                echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . $error]);
        }
    }
    
    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>