<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexion.php';
require_once 'config_mail.php';

// Cargar PHPMailer al inicio
// Cargar PHPMailer manualmente
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$correo = filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);

if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    exit;
}

try {
    // Verificar si el correo existe
    $sql = "SELECT id, usuario FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        echo json_encode(['success' => true, 'message' => 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación']);
        $stmt->close();
        exit;
    }
    
    $usuario = $resultado->fetch_assoc();
    $usuario_id = $usuario['id'];
    $nombre_usuario = $usuario['usuario'];
    $stmt->close();
    
    // Generar token único
    $token = bin2hex(random_bytes(32));
    $fecha_token = date('Y-m-d H:i:s', strtotime('+' . TOKEN_EXPIRATION_HOURS . ' hour'));
    
    // Guardar token en la BD
    $sql = "UPDATE usuarios SET token_recuperacion = ?, fecha_token = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la actualización: " . $conexion->error);
    }
    
    $stmt->bind_param("ssi", $token, $fecha_token, $usuario_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al guardar el token: " . $stmt->error);
    }
    
    $stmt->close();
    
    // Crear enlace de recuperación
    $enlace_recuperacion = BASE_URL . "resetear.html?token=" . urlencode($token);
    
    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    
    // Configurar SMTP de Mailtrap
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->Port = MAIL_PORT;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;
    
    // Configurar el correo
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress($correo, $nombre_usuario);
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de Contraseña - ESEA';
    
    // Cuerpo del correo HTML
    $html = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
            .email-container { 
                max-width: 600px; 
                margin: 20px auto; 
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .header { 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                color: white; 
                padding: 40px 30px;
                text-align: center;
            }
            .header h1 { 
                margin: 0; 
                font-size: 28px; 
                font-weight: 600;
            }
            .header p { 
                margin: 8px 0 0 0; 
                font-size: 14px;
                opacity: 0.9;
            }
            .content { 
                padding: 40px 30px;
                color: #333;
                line-height: 1.6;
            }
            .content h2 {
                color: #667eea;
                font-size: 20px;
                margin-top: 0;
            }
            .greeting {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .cta-button {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 14px 40px;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                margin: 25px 0;
            }
            .token-section {
                background: #f9f9f9;
                padding: 20px;
                border-left: 4px solid #667eea;
                margin: 20px 0;
            }
            .token-label {
                font-size: 12px;
                color: #999;
                text-transform: uppercase;
                margin-bottom: 8px;
            }
            .token-link {
                word-break: break-all;
                font-family: 'Courier New', monospace;
                font-size: 12px;
                color: #667eea;
            }
            .warning {
                background: #fff3cd;
                border: 1px solid #ffc107;
                border-radius: 6px;
                padding: 15px;
                margin-top: 20px;
                font-size: 14px;
                color: #856404;
            }
            .warning strong { color: #d9534f; }
            .footer {
                background: #f9f9f9;
                padding: 20px 30px;
                text-align: center;
                font-size: 12px;
                color: #999;
                border-top: 1px solid #eee;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h1>🔐 Recuperación de Contraseña</h1>
                <p>ESEA - Sistema</p>
            </div>
            
            <div class='content'>
                <h2>Hola, {$nombre_usuario}!</h2>
                
                <p class='greeting'>
                    Recibimos una solicitud para recuperar tu contraseña. 
                    Si fuiste tú quien lo solicitó, haz clic en el botón a continuación para continuar:
                </p>
                
                <center>
                    <a href='{$enlace_recuperacion}' class='cta-button'>Recuperar Contraseña</a>
                </center>
                
                <p style='text-align: center; color: #999; font-size: 14px;'>o copia y pega este enlace:</p>
                
                <div class='token-section'>
                    <div class='token-label'>Enlace de recuperación:</div>
                    <div class='token-link'>{$enlace_recuperacion}</div>
                </div>
                
                <div class='warning'>
                    <strong>⚠️ Importante:</strong> Este enlace expira en <strong>" . TOKEN_EXPIRATION_HOURS . " hora(s)</strong>. 
                    Si no solicitaste esta recuperación, puedes ignorar este correo de forma segura.
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>© 2026 ESEA - Todos los derechos reservados</strong></p>
                <p>Este es un correo automático, por favor no respondas.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $mail->Body = $html;
    
    // Enviar correo
    if ($mail->send()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Se ha enviado un enlace de recuperación a tu correo. Revisa tu bandeja de entrada.'
        ]);
    } else {
        throw new Exception('No se pudo enviar el correo: ' . $mail->ErrorInfo);
    }
    
} catch (Exception $e) {
    $error_msg = $e->getMessage();
    error_log("Error en recuperación: " . $error_msg);
    
    // Debug: mostrar el error
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $error_msg,
        'debug' => [
            'host' => MAIL_HOST,
            'port' => MAIL_PORT,
            'user' => MAIL_USER
        ]
    ]);
}

$conexion->close();
?>