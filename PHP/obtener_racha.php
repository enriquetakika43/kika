<?php
header('Content-Type: application/json');
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Conexión a BD (ajusta tus credenciales)
$conn = new mysqli('localhost', 'usuario', 'contraseña', 'nombre_bd');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$accion = $_GET['accion'] ?? '';

if ($accion === 'obtener') {
    // Obtener racha actual
    $sql = "SELECT racha_actual, racha_maxima, ultima_leccion_fecha FROM racha_diaria WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        // Si no existe, crear registro
        $sql_insert = "INSERT INTO racha_diaria (usuario_id, racha_actual, racha_maxima, ultima_leccion_fecha) VALUES (?, 0, 0, NULL)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param('i', $usuario_id);
        $stmt_insert->execute();
        echo json_encode(['racha_actual' => 0, 'racha_maxima' => 0, 'ultima_leccion_fecha' => null]);
    }
    $stmt->close();
}

$conn->close();
?>