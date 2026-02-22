<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
include 'conexion.php';

// Obtener ID del usuario desde la URL
$usuario_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($usuario_id === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Obtener foto de la base de datos
$stmt = $conexion->prepare("SELECT foto_perfil_url FROM usuarios WHERE id = ?");

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en prepare: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    
    if ($fila['foto_perfil_url'] && strlen($fila['foto_perfil_url']) > 0) {
        // Detectar el tipo de imagen
        $imagen = imagecreatefromstring($fila['foto_perfil_url']);
        
        if ($imagen === false) {
            // Si no se puede detectar, asumir JPEG
            header('Content-Type: image/jpeg');
        } else {
            // Intentar detectar el tipo
            $info = @getimagesizefromstring($fila['foto_perfil_url']);
            if ($info && isset($info['mime'])) {
                header('Content-Type: ' . $info['mime']);
            } else {
                header('Content-Type: image/jpeg');
            }
            imagedestroy($imagen);
        }
        
        header('Content-Length: ' . strlen($fila['foto_perfil_url']));
        echo $fila['foto_perfil_url'];
        exit;
    }
}

// Si no encuentra la foto, devuelve error 404
http_response_code(404);
echo json_encode(['error' => 'Foto no encontrada']);
$stmt->close();
$conexion->close();
?>