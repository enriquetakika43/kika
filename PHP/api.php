<?php
session_start();
header('Content-Type: application/json');
include 'conexion.php';

// Función para generar código de seguridad único
function generarCodigoSeguridad($conexion) {
    do {
        // Generar código aleatorio de 16 caracteres
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codigo = '';
        for ($i = 0; $i < 16; $i++) {
            $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        
        // Verificar que no exista
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE codigo_seguridad = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        
    } while ($resultado->num_rows > 0);
    
    return $codigo;
}

$action = $_POST['action'] ?? '';

// ===================== REGISTRO =====================
if ($action == 'registrar') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';
    
    // Validaciones
    if (empty($usuario) || empty($correo) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }
    
    if (strlen($usuario) < 3) {
        echo json_encode(['success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres']);
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
    
    // Verificar si usuario o correo existen
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo_electronico = ?");
    $stmt->bind_param("ss", $usuario, $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
    
    if ($resultado->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El usuario o correo ya está registrado']);
        exit;
    }
    
    // Encriptar contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
    
    // Generar código de seguridad único
    $codigo_seguridad = generarCodigoSeguridad($conexion);
    
    // Insertar usuario
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, correo_electronico, contraseña, codigo_seguridad, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación: ' . $conexion->error]);
        exit;
    }
    
    $stmt->bind_param("ssss", $usuario, $correo, $contraseña_hash, $codigo_seguridad);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registro exitoso',
            'codigo' => $codigo_seguridad
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . $stmt->error]);
    }
    
    $stmt->close();
}

// ===================== LOGIN =====================
elseif ($action == 'login') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';
    
    if (empty($usuario) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son obligatorios']);
        exit;
    }
    
    $stmt = $conexion->prepare("SELECT id, usuario, correo_electronico, contraseña FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        
        if (password_verify($contraseña, $fila['contraseña'])) {
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['correo'] = $fila['correo_electronico'];
            
            echo json_encode(['success' => true, 'message' => 'Login exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
    
    $stmt->close();
}

// ===================== OBTENER CÓDIGO DE SEGURIDAD =====================
elseif ($action == 'obtenerCodigo') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    
    if (empty($usuario)) {
        echo json_encode(['success' => false, 'message' => 'Usuario requerido']);
        exit;
    }
    
    $stmt = $conexion->prepare("SELECT codigo_seguridad FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        echo json_encode([
            'success' => true,
            'codigo' => $fila['codigo_seguridad']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
    
    $stmt->close();
}

// ===================== VERIFICAR CÓDIGO DE SEGURIDAD PARA RECUPERACIÓN =====================
elseif ($action == 'verificarCodigoRecuperacion') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    
    if (empty($correo) || empty($codigo)) {
        echo json_encode(['success' => false, 'message' => 'Correo y código son requeridos']);
        exit;
    }
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo inválido']);
        exit;
    }
    
    $stmt = $conexion->prepare("SELECT id, usuario FROM usuarios WHERE correo_electronico = ? AND codigo_seguridad = ?");
    $stmt->bind_param("ss", $correo, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        // Generar token de recuperación
        $token = bin2hex(random_bytes(32));
        $fecha_token = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt_update = $conexion->prepare("UPDATE usuarios SET token_recuperacion = ?, fecha_token = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $token, $fecha_token, $fila['id']);
        $stmt_update->execute();
        $stmt_update->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Código verificado. Puedes resetear tu contraseña',
            'token' => $token
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo o código de seguridad incorrectos']);
    }
    
    $stmt->close();
}

// ===================== RESETEAR CONTRASEÑA =====================
elseif ($action == 'resetearContraseña') {
    $token = isset($_POST['token']) ? trim($_POST['token']) : '';
    $nueva_contraseña = isset($_POST['nueva_contraseña']) ? trim($_POST['nueva_contraseña']) : '';
    
    if (empty($token) || empty($nueva_contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    if (strlen($nueva_contraseña) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    // Verificar token
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE token_recuperacion = ? AND fecha_token > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Enlace inválido o expirado']);
        $stmt->close();
        exit;
    }
    
    $fila = $resultado->fetch_assoc();
    $usuario_id = $fila['id'];
    $stmt->close();
    
    // Encriptar nueva contraseña
    $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);
    
    // Actualizar contraseña
    $stmt = $conexion->prepare("UPDATE usuarios SET contraseña = ?, token_recuperacion = NULL, fecha_token = NULL WHERE id = ?");
    $stmt->bind_param("si", $contraseña_hash, $usuario_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña']);
    }
    
    $stmt->close();
}

$conexion->close();
?>