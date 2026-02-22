<?php
session_start();
header('Content-Type: application/json');
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del usuario
$stmt = $conexion->prepare("SELECT id, usuario, correo_electronico, codigo_seguridad, foto_perfil_url FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    
    // Determinar si hay foto
    $tiene_foto = !empty($usuario['foto_perfil_url']);
    $foto_url = $tiene_foto ? 'PHP/obtener_foto.php?id=' . $usuario['id'] : null;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $usuario['id'],
            'usuario' => $usuario['usuario'],
            'correo_electronico' => $usuario['correo_electronico'],
            'codigo_seguridad' => $usuario['codigo_seguridad'],
            'foto_perfil_url' => $foto_url
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conexion->close();
?>