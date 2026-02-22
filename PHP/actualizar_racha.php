<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$conn = new mysqli('localhost', 'usuario', 'contraseña', 'nombre_bd');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$leccion_id = $_POST['leccion_id'] ?? null;

if (!$leccion_id) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// Obtener racha actual
$sql = "SELECT racha_actual, racha_maxima, ultima_leccion_fecha FROM racha_diaria WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$hoy = date('Y-m-d');
$nueva_racha = 1;
nueva_racha_maxima = 1;

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $ultima_fecha = $data['ultima_leccion_fecha'];
    $racha_actual = $data['racha_actual'];
    $racha_maxima = $data['racha_maxima'];
    
    // Calcular si ya completó una lección hoy
    if ($ultima_fecha === $hoy) {
        // Ya completó hoy, no incrementar racha
        echo json_encode(['racha_actual' => $racha_actual, 'racha_maxima' => $racha_maxima, 'mensaje' => 'Ya completaste una lección hoy']);
        exit;
    } elseif ($ultima_fecha === date('Y-m-d', strtotime('-1 day'))) {
        // Completó ayer, incrementar racha
        $nueva_racha = $racha_actual + 1;
        $nueva_racha_maxima = max($racha_maxima, $nueva_racha);
    } else {
        // Rompió la racha
        $nueva_racha = 1;
        $nueva_racha_maxima = max($racha_maxima, 1);
    }
} else {
    // Crear registro nuevo
    $sql_insert = "INSERT INTO racha_diaria (usuario_id, racha_actual, racha_maxima, ultima_leccion_fecha) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param('iis', $usuario_id, $nueva_racha, $nueva_racha_maxima, $hoy);
$stmt_insert->execute();
$stmt_insert->close();
    
echo json_encode(['racha_actual' => $nueva_racha, 'racha_maxima' => $nueva_racha_maxima, 'mensaje' => 'Racha iniciada']);
    exit;
}

// Actualizar racha
$sql_update = "UPDATE racha_diaria SET racha_actual = ?, racha_maxima = ?, ultima_leccion_fecha = ? WHERE usuario_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param('iisi', $nueva_racha, $nueva_racha_maxima, $hoy, $usuario_id);
$stmt_update->execute();
$stmt_update->close();

echo json_encode([
    'racha_actual' => $nueva_racha,
    'racha_maxima' => $nueva_racha_maxima,
    'mensaje' => 'Racha actualizada'
]);

$conn->close();
?>