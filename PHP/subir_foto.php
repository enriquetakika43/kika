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

// Validar que haya un archivo
if (!isset($_FILES['foto_perfil'])) {
    echo json_encode(['success' => false, 'message' => 'No se envió ningún archivo']);
    exit;
}

$archivo = $_FILES['foto_perfil'];

// Validar tipo de archivo
$tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($archivo['type'], $tipos_permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes (JPG, PNG, GIF, WEBP)']);
    exit;
}

// Validar tamaño (máximo 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($archivo['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'La imagen es muy grande (máximo 5MB)']);
    exit;
}

// Leer el archivo como contenido binario
$contenido_archivo = file_get_contents($archivo['tmp_name']);

if ($contenido_archivo === false) {
    echo json_encode(['success' => false, 'message' => 'Error al leer el archivo']);
    exit;
}

// Guardar en la base de datos como BLOB
$stmt = $conexion->prepare("UPDATE usuarios SET foto_perfil_url = ? WHERE id = ?");

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error en prepare: ' . $conexion->error]);
    exit;
}

// IMPORTANTE: El orden es inverso: primero el tipo (s para string/blob, i para int), luego las variables
$stmt->bind_param("si", $contenido_archivo, $usuario_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Foto guardada correctamente',
        'foto_perfil' => 'PHP/obtener_foto.php?id=' . $usuario_id . '&t=' . time()
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>