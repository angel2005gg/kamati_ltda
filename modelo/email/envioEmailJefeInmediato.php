<?php
// Incluir Composer autoload (esto cargará PHPMailer)
require_once '../controlador/controladorEmail.php';
require_once '../modelo/dao/JefeAreaDao.php';

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para obtener el servidor SMTP basado en el dominio del remitente del correo electrónico
function getSmtpServer($email)
{
    $domain = substr(strrchr($email, "@"), 1);
    $smtpServers = [
        'gmail.com' => ['host' => 'smtp.gmail.com', 'port' => 587],
        'yahoo.com' => ['host' => 'smtp.mail.yahoo.com', 'port' => 587],
        'outlook.com' => ['host' => 'smtp-mail.outlook.com', 'port' => 587],
        'kamatiltda.com' => ['host' => 'smtp-mail.kamatiltda.com', 'port' => 587],

    ];

    return isset($smtpServers[$domain]) ? $smtpServers[$domain] : null;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $control = new ControladorEmail();
    $jefe = new JefeAreaDao();
    $email = $control->controlEmail();
    // Obtener los datos del formulario
    $senderEmail = $email->getCorreo();
    $senderPassword = $email->getClave();
    $recipientEmail1 =  $_SESSION['correo_persona'];
    $recipientEmail2 =  $_SESSION['correo_user_inicio_sesion'];
    $recipientEmail3 =  'gestionhumana@kamatiltda.com';
    $recipientEmail4 =  'gestionhumana2@kamatiltda.com';

    $message = "Estimado/a " . $_SESSION['nombre_persona_completo'] . "<br><br>" .
        " Espero que se encuentre muy bien <br><br>" .
        " Le escribe Kamati notificandole que su permiso - licencia - vacaciones ha sido puesto en estado pendiente. <br><br>" .
        " Por favor revisa la página en el apartado de 'solicitudes', para visualizar el estado de tu solicitud. <br><br> " .
        " Link de la página: intranet.kamati.co";

    // Obtener la configuración del servidor SMTP basada en el dominio del remitente
    $smtpConfig = getSmtpServer($senderEmail);

    if ($smtpConfig) {
        try {
            // Crear una nueva instancia de PHPMailer
            $mail = new PHPMailer(true);

            // Configurar PHPMailer con la información proporcionada por el usuario
            $mail->isSMTP();
            $mail->Host = $smtpConfig['host'];
            $mail->Port = $smtpConfig['port'];
            $mail->SMTPAuth = true;
            $mail->Username = $senderEmail;
            $mail->Password = $senderPassword;
            $mail->SMTPSecure = 'tls';

            // Remitente y destinatarios
            $mail->setFrom($senderEmail);
            $mail->addAddress($recipientEmail1);
            $mail->addAddress($recipientEmail2);
            $mail->addAddress($recipientEmail3);
            $mail->addAddress($recipientEmail4);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Solicitud Pendiente';
            $mail->Body = $message;

            // Enviar el correo
            $mail->send();
            echo ' El mensaje ha sido enviado ';
        } catch (Exception $e) {
            echo 'No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'No se encontró un servidor SMTP para el dominio del remitente';
    }
} else {
    echo "Método de solicitud no válido.";
}
