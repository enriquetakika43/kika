<?php
session_start();
header('Content-Type: application/json');

// Verificar si hay sesión activa
if (isset($_SESSION['usuario_id']) || isset($_SESSION['usuario'])) {
    echo json_encode(['logueado' => true]);
} else {
    echo json_encode(['logueado' => false]);
}
exit;
?>