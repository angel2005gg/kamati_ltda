<?php
error_log("Iniciando script de notificaciones programadas");
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../configuracion/ConexionBD.php';
require_once __DIR__ . '/emailHelper.php';

try {
    // Iniciar conexión
    $conexion = new ConexionBD();
    $conn = $conexion->conectarBD();

    // Consulta SQL que obtiene cursos con notificaciones programadas, diferenciando usuarios y contratistas
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
        cu.tipo
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

    // Logging para debug
    error_log("Procesando " . count($cursosUsuarios) . " cursos");

    foreach ($cursosUsuarios as $cursoUsuario) {
        $fecha_fin = new DateTime($cursoUsuario['fecha_fin']);
        $fecha_actual = new DateTime();
        $dias_restantes = $fecha_actual->diff($fecha_fin)->days;

        // Evitar enviar notificaciones en sábado o domingo
        $dia_semana = $fecha_actual->format('N'); // 1=lunes, 7=domingo
        if ($dia_semana >= 6) {
            error_log("Hoy es sábado o domingo, no se envían correos.");
            continue;
        }

        if ($dias_restantes <= $cursoUsuario['dias_notificacion']) {
            try {
                // Usar el correo correcto según el tipo (ya se definió en la consulta)
                $destinatarios = [$cursoUsuario['correo']];

                // Si hoy es el último día (0 días restantes)
                if ($dias_restantes == 0) {
                    $asunto = 'Recordatorio de Curso - Último Día';
                    $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                        "Le informamos que el curso <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                        "de la empresa <strong>" . $cursoUsuario['empresa'] . "</strong> vence hoy (" . $cursoUsuario['fecha_fin'] . ").<br><br>" .
                        "Este es el último día de su curso; a partir de mañana, dejaremos de enviarle notificaciones.<br><br>" .
                        "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
                } else {
                    $asunto = 'Recordatorio de Curso - A vencer';
                    // Aquí podrías incluir el estado (por ejemplo, "A vencer" o "Vigente") si lo deseas
                    $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                        "Le recordamos que el curso <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                        "de la empresa <strong>" . $cursoUsuario['empresa'] . "</strong> finalizará el " . $cursoUsuario['fecha_fin'] .
                        " y quedan <strong>" . $dias_restantes . "</strong> días para su finalización.<br><br>" .
                        "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
                }

                error_log("Enviando correo a: " . $cursoUsuario['correo']);
                if (enviarCorreo($destinatarios, $asunto, $mensaje, $id_usuario)) {
                    error_log("Correo enviado a: " . $cursoUsuario['correo']);
                } else {
                    error_log("Error al enviar correo a: " . $cursoUsuario['correo']);
                }
            } catch (Exception $e) {
                error_log("Error enviando correo a {$cursoUsuario['correo']}: " . $e->getMessage());
            }
        }
    }

    error_log("Script de notificaciones programadas finalizado");
} catch (Exception $e) {
    error_log("Error en el proceso de notificaciones: " . $e->getMessage());
} finally {
    if (isset($conexion)) {
        $conexion->desconectarBD();
    }
}
