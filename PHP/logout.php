<?php
session_start();
session_destroy();

// Limpiar cookies
setcookie('PHPSESSID', '', time() - 3600, '/');

// Responder con JSON antes de redirigir
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Sesión cerrada']);
exit;
?>