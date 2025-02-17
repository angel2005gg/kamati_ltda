<?php
error_log("Iniciando script de notificaciones programadas");
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../configuracion/ConexionBD.php';
require_once __DIR__ . '/emailHelper.php';

try {
    // Iniciar conexión
    $conexion = new ConexionBD();
    $conn = $conexion->conectarBD();

    // Consulta SQL
    $sql = "SELECT cu.*,
            CONCAT(u.primer_nombre, ' ',
                  IFNULL(u.segundo_nombre, ''), ' ',
                  u.primer_apellido, ' ',
                  IFNULL(u.segundo_apellido, '')) as nombre_usuario,
            u.correo_electronico as correo_usuario
            FROM curso_usuario cu
            JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
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

        // Verificar si hoy es sábado o domingo
        $dia_semana = $fecha_actual->format('N'); // 1 (lunes) a 7 (domingo)
        if ($dia_semana >= 6) {
            error_log("Hoy es sábado o domingo, no se envían correos.");
            continue;
        }

        if ($dias_restantes <= $cursoUsuario['dias_notificacion']) {
            try {
                $destinatarios = [$cursoUsuario['correo_usuario']];
                $asunto = 'Recordatorio de Curso';
                $mensaje = sprintf(
                    'Este es un recordatorio de que el curso finalizará el %s. Quedan %d días. Mensaje automático',
                    $cursoUsuario['fecha_fin'],
                    $dias_restantes
                );

                error_log("Enviando correo a: " . $cursoUsuario['correo_usuario']);
                if (enviarCorreo($destinatarios, $asunto, $mensaje)) {
                    error_log("Correo enviado a: " . $cursoUsuario['correo_usuario']);
                } else {
                    error_log("Error al enviar correo a: " . $cursoUsuario['correo_usuario']);
                }
            } catch (Exception $e) {
                error_log("Error enviando correo a {$cursoUsuario['correo_usuario']}: " . $e->getMessage());
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
?>