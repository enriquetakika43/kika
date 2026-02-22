<?php
require_once 'conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$token = $_POST['token'] ?? '';
$nueva_contraseña = $_POST['nueva_contraseña'] ?? '';

if (empty($token) || empty($nueva_contraseña)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

if (strlen($nueva_contraseña) < 6) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
    exit;
}

try {
    // Verificar el token y que no haya expirado
    $sql = "SELECT id FROM usuarios WHERE token_recuperacion = ? AND fecha_token > NOW()";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Enlace inválido o expirado']);
        $stmt->close();
        exit;
    }
    
    $usuario = $resultado->fetch_assoc();
    $usuario_id = $usuario['id'];
    $stmt->close();
    
    // ⚠️ IMPORTANTE: Usa el mismo método de encriptación que en tu login
    // Si usas MD5:
    $contraseña_hash = md5($nueva_contraseña);
    
    // Si usas password_hash (bcrypt - más seguro):
    // $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);
    
    // Actualizar contraseña y limpiar token
    $sql = "UPDATE usuarios SET contraseña = ?, token_recuperacion = NULL, fecha_token = NULL WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la actualización: " . $conexion->error);
    }
    
    $stmt->bind_param("si", $contraseña_hash, $usuario_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar: " . $stmt->error);
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Contraseña actualizada correctamente. Redirigiendo a login...'
    ]);
    
} catch (Exception $e) {
    error_log("Error en reseteo: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error al procesar tu solicitud. Intenta más tarde.'
    ]);
}

$conexion->close();
?>