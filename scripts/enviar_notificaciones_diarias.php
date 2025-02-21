#!/usr/bin/php
<?php
error_log("Iniciando script de notificaciones diarias - " . date('Y-m-d H:i:s'));
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../configuracion/ConexionBD.php';
require_once __DIR__ . '/../modelo/email/emailHelper.php';

try {
    $conexion = new ConexionBD();
    $conn = $conexion->conectarBD();

    // Consulta que obtiene cursos dentro del rango de notificación, incluyendo id_Usuarios
    $sql = "SELECT cu.*,
        CASE 
            WHEN cu.tipo = 'contratista' THEN c.correo_contratista
            ELSE u.correo_electronico
        END as correo,
        CASE 
            WHEN cu.tipo = 'usuario' THEN CONCAT(u.primer_nombre, ' ', IFNULL(u.segundo_nombre, ''), ' ', u.primer_apellido, ' ', IFNULL(u.segundo_apellido, ''))
            WHEN cu.tipo = 'contratista' THEN c.nombre_contratista
            ELSE 'N/A'
        END as nombre_usuario,
        cr.nombre_curso_fk as nombre_curso,
        ec.nombre_empresa as empresa,
        cu.fecha_fin,
        cu.dias_notificacion,
        cu.tipo,
        cu.id_Usuarios,
        (SELECT MAX(fecha_envio) FROM historial_correos 
         WHERE id_curso_usuario = cu.id_curso_usuario 
         AND DATE(fecha_envio) = CURRENT_DATE) as ultima_notificacion
    FROM curso_usuario cu
    LEFT JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
    LEFT JOIN contratista c ON cu.id_Usuarios = c.id_contratista
    JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
    JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
    JOIN curso cr ON ce.id_curso = cr.id_curso
    WHERE DATEDIFF(cu.fecha_fin, CURRENT_DATE) <= cu.dias_notificacion
    AND CURRENT_DATE <= cu.fecha_fin";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparando la consulta: " . $conn->error);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cursosUsuarios = $resultado->fetch_all(MYSQLI_ASSOC);

    error_log("Procesando " . count($cursosUsuarios) . " cursos");

    foreach ($cursosUsuarios as $cursoUsuario) {
        // Si ya se envió notificación hoy, saltar este registro
        if ($cursoUsuario['ultima_notificacion']) {
            error_log("Ya se envió notificación hoy para el curso " . $cursoUsuario['id_curso_usuario']);
            continue;
        }
        // No enviar notificaciones en fin de semana
        $dia_semana = date('N'); // 1=lunes ... 7=domingo
        if ($dia_semana >= 6) {
            error_log("Hoy es fin de semana, no se envían correos.");
            continue;
        }

        $fecha_fin = new DateTime($cursoUsuario['fecha_fin']);
        $fecha_actual = new DateTime();
        $dias_restantes = $fecha_actual->diff($fecha_fin)->days;

        try {
            $destinatarios = [$cursoUsuario['correo']];
            if ($dias_restantes == 0) {
                $asunto = 'Recordatorio de Curso - Último Día';
                $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                           "Le informamos que el curso <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                           "de la empresa <strong>" . $cursoUsuario['empresa'] . "</strong> vence hoy (" . $cursoUsuario['fecha_fin'] . ").<br><br>" .
                           "Este es el último día de su curso; a partir de mañana dejaremos de enviarle notificaciones.<br><br>" .
                           "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
            } else {
                $asunto = 'Recordatorio de Curso - A vencer';
                $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                           "Le recordamos que el curso <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                           "de la empresa <strong>" . $cursoUsuario['empresa'] . "</strong> finalizará el " . $cursoUsuario['fecha_fin'] .
                           " y quedan <strong>" . $dias_restantes . "</strong> días para su finalización.<br><br>" .
                           "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
            }

            // Enviar el correo; se pasa el id del usuario para que se añadan correos según área/sede
            if (enviarCorreo($destinatarios, $asunto, $mensaje, $cursoUsuario['id_Usuarios'])) {
                // Registrar la notificación en historial
                $sql_registro = "INSERT INTO historial_correos (destinatario, asunto, mensaje, fecha_envio) VALUES (?, ?, ?, NOW())";
                $destinatarioRegistro = $cursoUsuario['correo'];
                $stmt_registro = $conn->prepare($sql_registro);
                $stmt_registro->bind_param('sss', $destinatarioRegistro, $asunto, $mensaje);
                $stmt_registro->execute();
                error_log("Correo enviado exitosamente a: " . $cursoUsuario['correo']);
            } else {
                error_log("Error al enviar correo a: " . $cursoUsuario['correo']);
            }
        } catch (Exception $e) {
            error_log("Error enviando correo a {$cursoUsuario['correo']}: " . $e->getMessage());
        }
    }

    error_log("Script de notificaciones diarias finalizado - " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    error_log("Error en el proceso de notificaciones: " . $e->getMessage());
} finally {
    if (isset($conexion)) {
        $conexion->desconectarBD();
    }
}
?>
