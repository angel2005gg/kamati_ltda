<?php 
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../dao/EmailSoftwareDao.php');
require_once(__DIR__ . '/../../configuracion/ConexionBD.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Obtiene la información (área y sede) de un usuario dado su id.
 */
function obtenerInfoUsuario($id_usuario) {
    $conexion = new ConexionBD();
    $conn = $conexion->conectarBD();
    
    // Se modifica la consulta para obtener el área mediante join con cargo y area
    $sql = "SELECT a.nombre_area, u.sede_laboral 
            FROM usuarios u
            JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
            JOIN area a ON c.id_area_fk = a.id_Area
            WHERE u.id_Usuarios = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    
    $conexion->desconectarBD();
    
    return $usuario;
}


/**
 * Envía un correo electrónico usando PHPMailer.
 * Además de los destinatarios dinámicos, siempre se envían a:
 * - auxiliaresgi@kamatiltda.com  
 * - esuarez@kamatiltda.com  
 * Y según el área y la sede del usuario (si se pasa $id_usuario), se agregan otros destinatarios.
 */
function enviarCorreo($destinatarios, $asunto, $mensaje, $id_usuario = null) {
    // Si se proporciona el id, obtener área y sede
    $area = null;
    $sede = null;
    if ($id_usuario !== null) {
        $infoUsuario = obtenerInfoUsuario($id_usuario);
        $area = isset($infoUsuario['nombre_area']) ? $infoUsuario['nombre_area'] : null;
        $sede = isset($infoUsuario['sede_laboral']) ? $infoUsuario['sede_laboral'] : null;
    }
    
    // Obtener credenciales del remitente
    $emailDao = new EmailSoftwareDao();
    $email = $emailDao->consultarEmail();
    if (!$email) {
        error_log('No se pudo obtener el correo y la contraseña del remitente desde la base de datos');
        return false;
    }
    $remitenteEmail = $email->getCorreo();
    $remitentePassword = $email->getClave();
    
    // Configurar el servidor SMTP basado en el dominio
    $smtpConfig = getSmtpServer($remitenteEmail);
    if ($smtpConfig) {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0; // Sin salida de depuración
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = $smtpConfig['host'];
            $mail->Port = $smtpConfig['port'];
            $mail->SMTPAuth = true;
            $mail->Username = $remitenteEmail;
            $mail->Password = $remitentePassword;
            $mail->SMTPSecure = 'tls';
            
            // Remitente
            $mail->setFrom($remitenteEmail);
            
            // Agregar destinatarios dinámicos
            foreach ($destinatarios as $destinatario) {
                $mail->addAddress($destinatario);
            }
            // Agregar destinatarios fijos
            $mail->addAddress('auxiliaresgi@kamatiltda.com');
            $mail->addAddress('esuarez@kamatiltda.com');
            
            // Agregar destinatarios según área y sede
            if ($area) {
                switch (strtolower(trim($area))) {
                    case 'ingenieria':
                        $mail->addAddress('oherrera@kamatiltda.com');
                        break;
                    case 'instalaciones electricas':
                        $mail->addAddress('wnieto@kamatiltda.com');
                        break;
                    case 'sheq':
                        // esuarez@kamatiltda.com ya está incluido
                        break;
                }
                if ($sede && strtolower(trim($sede)) === 'bogota') {
                    if (in_array(strtolower(trim($area)), ['ingenieria', 'instalaciones electricas'])) {
                        $mail->addAddress('lrodriguez@kamatiltda.com');
                    }
                }
            }
            
            // Configurar contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;
            
            // Enviar
            $mail->send();
            
            // Registrar en historial
            almacenarHistorialCorreo($destinatarios, $asunto, $mensaje);
            
            return true;
        } catch (Exception $e) {
            error_log('No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo);
            return false;
        }
    } else {
        error_log('No se encontró un servidor SMTP para el dominio del remitente');
        return false;
    }
}

/**
 * Almacena en la base de datos el historial de correos enviados.
 */
function almacenarHistorialCorreo($destinatarios, $asunto, $mensaje) {
    // Agrega siempre los destinatarios fijos
    $destinatarios[] = 'auxiliaresgi@kamatiltda.com';
    $destinatarios[] = 'esuarez@kamatiltda.com';

    $conexion = new ConexionBD();
    $conn = $conexion->conectarBD();
    $fecha_envio = date('Y-m-d H:i:s');

    // Eliminar correos más antiguos si el total supera los 30
    $sql = "DELETE FROM historial_correos WHERE id_historial_correos NOT IN (
                SELECT id_historial_correos FROM (
                    SELECT id_historial_correos FROM historial_correos ORDER BY fecha_envio DESC LIMIT 29
                ) AS t
            )";
    if (!$conn->query($sql)) {
        error_log("Error ejecutando la consulta de eliminación: " . $conn->error);
    }

    // Insertar en el historial cada destinatario
    foreach ($destinatarios as $destinatario) {
        // Verificar los parámetros antes de la inserción
        if (empty($destinatario) || empty($asunto) || empty($mensaje)) {
            error_log("Parámetros inválidos para almacenarHistorialCorreo: destinatario=$destinatario, asunto=$asunto, mensaje=$mensaje");
            continue;
        }

        $sqlInsert = "INSERT INTO historial_correos (destinatario, asunto, mensaje, fecha_envio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        if (!$stmt) {
            error_log("Error preparando la consulta: " . $conn->error);
            continue;
        }

        $stmt->bind_param('ssss', $destinatario, $asunto, $mensaje, $fecha_envio);
        if (!$stmt->execute()) {
            error_log("Error ejecutando la consulta: " . $stmt->error);
        }
    }

    $conexion->desconectarBD();
}


/**
 * Devuelve la configuración SMTP según el dominio del correo.
 */
function getSmtpServer($email) {
    if (!$email) return null;
    $domain = substr(strrchr($email, "@"), 1);
    $smtpServers = [
        'gmail.com' => ['host' => 'smtp.gmail.com', 'port' => 587],
        'yahoo.com' => ['host' => 'smtp.mail.yahoo.com', 'port' => 587],
        'outlook.com' => ['host' => 'smtp-mail.outlook.com', 'port' => 587],
        'kamatiltda.com' => ['host' => 'smtp-mail.kamatiltda.com', 'port' => 587],
    ];
    return isset($smtpServers[$domain]) ? $smtpServers[$domain] : null;
}
?>
