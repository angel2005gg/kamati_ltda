<?php
require '../vendor/autoload.php';
require_once '../modelo/dao/EmailSoftwareDao.php';
require_once '../modelo/CursoUsuario.php';
require_once '../modelo/email/emailHelper.php';

$cursoUsuarioModel = new CursoUsuario();
$emailDao = new EmailSoftwareDao();

// Obtener todos los cursos de usuarios
$cursosUsuarios = $cursoUsuarioModel->obtenerTodos();

foreach ($cursosUsuarios as $cursoUsuario) {
    $fecha_fin = new DateTime($cursoUsuario['fecha_fin']);
    $fecha_actual = new DateTime();
    $dias_restantes = $fecha_actual->diff($fecha_fin)->days;

    // Obtener los días de notificación desde la base de datos
    $dias_notificacion = $cursoUsuarioModel->obtenerDiasNotificacion($cursoUsuario['id_curso_usuario']);

    $dias_notificacion_array = array_column($dias_notificacion, 'dias');

    if (in_array($dias_restantes, $dias_notificacion_array)) {
        $destinatarios = [$cursoUsuario['correo_usuario']];
        $asunto = 'Recordatorio de Curso';
        $mensaje = 'Este es un recordatorio de que el curso finalizará el ' . $cursoUsuario['fecha_fin'] . '. Quedan ' . $dias_restantes . ' días.';

        enviarCorreo($destinatarios, $asunto, $mensaje);
    }
}
?>