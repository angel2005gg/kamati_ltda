<?php
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../dao/EmailSoftwareDao.php');
require_once(__DIR__ . '/../../configuracion/ConexionBD.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreo($destinatarios, $asunto, $mensaje) {
    // Obtener el correo y la contraseña del remitente desde la base de datos
    $emailDao = new EmailSoftwareDao();
    $email = $emailDao->consultarEmail();

    if (!$email) {
        error_log('No se pudo obtener el correo y la contraseña del remitente desde la base de datos');
        return false;
    }

    $remitenteEmail = $email->getCorreo();
    $remitentePassword = $email->getClave();

    // Obtener la configuración del servidor SMTP basada en el dominio del remitente
    $smtpConfig = getSmtpServer($remitenteEmail);

    if ($smtpConfig) {
        try {
            // Crear una nueva instancia de PHPMailer
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0; // Desactivar depuración

            // Configurar PHPMailer con la información proporcionada por el usuario
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

            // Destinatarios
            foreach ($destinatarios as $destinatario) {
                $mail->addAddress($destinatario);
            }

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            // Enviar el correo
            $mail->send();

            // Almacenar el historial del correo enviado
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

function almacenarHistorialCorreo($destinatarios, $asunto, $mensaje) {
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

    foreach ($destinatarios as $destinatario) {
        // Verificar los parámetros antes de la inserción
        if (empty($destinatario) || empty($asunto) || empty($mensaje)) {
            error_log("Parámetros inválidos para almacenarHistorialCorreo: destinatario=$destinatario, asunto=$asunto, mensaje=$mensaje");
            continue;
        }

        $sql = "INSERT INTO historial_correos (destinatario, asunto, mensaje, fecha_envio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
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

// Función para obtener el servidor SMTP basado en el dominio del remitente del correo electrónico
function getSmtpServer($email) {
    if (!$email) {
        return null;
    }

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