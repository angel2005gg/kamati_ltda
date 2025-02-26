<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ob_start();

require_once '../controlador/ControladorCursoUsuario.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../controlador/ControladorCursoEmpresa.php';
require_once '../modelo/email/emailHelper.php';
$conexion = new ConexionBD();
$conn = $conexion->conectarBD();

// Limpia el buffer de salida para evitar salidas no deseadas
ob_clean();
// Instanciar controladores
$controladorCursoUsuario = new ControladorCursoUsuario();
$controladorEmpresa = new ControladorEmpresaCliente();
$controladorCursoEmpresa = new ControladorCursoEmpresa();

// Obtener el ID del curso de usuario de la URL
$id_curso_usuario = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id_curso_usuario === null) {
    die("ID de curso de usuario no proporcionado");
}

// Obtener detalles del curso de usuario
$cursoUsuario = $controladorCursoUsuario->obtenerPorId($id_curso_usuario);
if (!$cursoUsuario) {
    die("Curso de usuario no encontrado");
}

// Obtener todas las empresas
$empresas = $controladorEmpresa->obtenerTodos();

// Asegurar que exista id_empresa_cliente en el curso de usuario
$id_empresa_actual = isset($cursoUsuario['id_empresa_cliente']) ? intval($cursoUsuario['id_empresa_cliente']) : null;

// Obtener cursos de la empresa actual
$cursosEmpresa = $id_empresa_actual ? $controladorCursoEmpresa->obtenerPorEmpresa($id_empresa_actual) : [];

// Definir la función calcularEstado (definida una sola vez)
function calcularEstado($fecha_inicio, $fecha_fin) {
    $fecha_actual = new DateTime();
    $fecha_fin_dt = new DateTime($fecha_fin);
    $intervalo = $fecha_actual->diff($fecha_fin_dt)->days;

    if ($fecha_actual > $fecha_fin_dt) {
        return ['estado' => 'Vencido', 'clase' => 'estado-vencido'];
    } elseif ($intervalo <= 20) {
        return ['estado' => 'A vencer', 'clase' => 'estado-a-vencer'];
    } else {
        return ['estado' => 'Vigente', 'clase' => 'estado-vigente'];
    }
}

// Procesar el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $controladorCursoUsuario->eliminar($id_curso_usuario);
        header("Location: /vista/ListaCursos.php?deleted=1");
        exit();
    } elseif (isset($_POST['notificar'])) {
        // Seleccionar el correo según el tipo
        if ($cursoUsuario['tipo'] === 'contratista') {
            $destinatarios = [$cursoUsuario['correo_contratista']];
        } else {
            $destinatarios = [$cursoUsuario['correo_usuario']];
        }
        $asunto = 'Notificación del curso';
    
        if (!empty($cursoUsuario['fecha_fin'])) {
            $fecha_fin = new DateTime($cursoUsuario['fecha_fin']);
            $fecha_actual = new DateTime();
            if ($fecha_actual > $fecha_fin) {
                $dias_transcurridos = $fecha_fin->diff($fecha_actual)->days;
                $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                           "Le informamos que el curso: <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                           "en la empresa: <strong>" . $cursoUsuario['empresa'] . "</strong> finalizó el " . $cursoUsuario['fecha_fin'] .
                           " y se encuentra en estado <strong>Vencido</strong> (finalizó hace <strong>" . $dias_transcurridos . "</strong> días).<br><br>" .
                           "Este es el último día de notificaciones, a partir de mañana dejaremos de notificarle.<br><br>" .
                           "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
            } else {
                $dias_restantes = $fecha_actual->diff($fecha_fin)->days;
                $estadoCurso = calcularEstado($cursoUsuario['fecha_inicio'], $cursoUsuario['fecha_fin']);
                $mensaje = "Estimado(a) " . $cursoUsuario['nombre_usuario'] . ",<br><br>" .
                           "Le recordamos que el curso: <strong>" . $cursoUsuario['nombre_curso'] . "</strong> " .
                           "en la empresa: <strong>" . $cursoUsuario['empresa'] . "</strong> finalizará el " . $cursoUsuario['fecha_fin'] .
                           " y quedan <strong>" . $dias_restantes . "</strong> días para su finalización. " .
                           "El curso se encuentra en estado <strong>" . $estadoCurso['estado'] . "</strong>.<br><br>" .
                           "Saludos cordiales,<br>Su equipo de capacitación.<br><br>[Mensaje automático]";
            }
        
            if (enviarCorreo($destinatarios, $asunto, $mensaje)) {
                $mensajeNotificacion = 'Notificación enviada';
            } else {
                $mensajeNotificacion = 'Error al enviar la notificación';
            }
            // No se llama a exit(), para que se siga mostrando la vista.
            
            
            
        } else {
            echo 'Error: La fecha de fin no está definida.';
            exit();
        }
    } elseif (isset($_POST['desactivar_notificaciones'])) {
        // Actualizar el campo a 1 para desactivar (notificaciones NO se envían)
        $sqlUpdate = "UPDATE curso_usuario SET notificaciones_activas = 1 WHERE id_curso_usuario = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $id_curso_usuario);
        if ($stmtUpdate->execute()) {
            $mensajeNotificacion = 'Notificaciones desactivadas';
            // Actualizamos la sesión para reflejar que ahora están desactivadas
            $_SESSION['notificaciones_activas'] = false;
        } else {
            $mensajeNotificacion = 'Error al desactivar notificaciones';
        }
    } elseif (isset($_POST['activar_notificaciones'])) {
        // Actualizar el campo a 0 para activar (notificaciones se enviarán)
        $sqlUpdate = "UPDATE curso_usuario SET notificaciones_activas = 0 WHERE id_curso_usuario = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $id_curso_usuario);
        if ($stmtUpdate->execute()) {
            $mensajeNotificacion = 'Notificaciones activadas';
            // Actualizamos la sesión para reflejar que ahora están activadas
            $_SESSION['notificaciones_activas'] = true;
        } else {
            $mensajeNotificacion = 'Error al activar notificaciones';
        }
    }
    
    
    else {
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $id_empresa_cliente = $_POST['selectEmpresa'] ?? null;
        $id_curso_empresa = $_POST['selectCurso'] ?? null;
    
        if ($fecha_inicio && $id_empresa_cliente && $id_curso_empresa) {
            $cursoEmpresa = $controladorCursoEmpresa->obtenerPorId($id_curso_empresa);
            $duracion = $cursoEmpresa['duracion'] ?? 0;
            $fecha_fin = date('Y-m-d', strtotime("+$duracion months", strtotime($fecha_inicio)));
    
            $controladorCursoUsuario->actualizar($id_curso_usuario, $cursoUsuario['id_Usuarios'], $id_curso_empresa, $fecha_inicio, $fecha_fin);
            header("Location: /vista/ListaCursos.php?updated=1");
            exit();
        }
    }
}
$notificaciones_activas = isset($_SESSION['notificaciones_activas']) ? $_SESSION['notificaciones_activas'] : true;
// Incluimos la navegación después de procesar el POST (si no se redirige)
include 'incluirNavegacion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso de Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <br><br><br>
    
    <div class="container form-container">
        <h2 class="text-center mb-4">Editar Curso de Usuario</h2>
        <?php if (isset($mensajeNotificacion)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $mensajeNotificacion; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre_usuario" class="form-label">Nombre del Usuario:</label>
                        <input type="text" class="form-control" id="nombre_usuario" 
                               value="<?php echo htmlspecialchars($cursoUsuario['nombre_usuario'] ?? ''); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="area" class="form-label">Área:</label>
                        <input type="text" class="form-control" id="area" 
                               value="<?php echo htmlspecialchars($cursoUsuario['area'] ?? ''); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="selectEmpresa" class="form-label">Seleccione una Empresa:</label>
                        <select class="form-select" id="selectEmpresa" name="selectEmpresa" required>
    <?php foreach ($empresas as $empresa): 
        // Comparamos el nombre de la empresa asignado al curso con el nombre de la empresa en la lista
        $selected = (isset($cursoUsuario['empresa']) && $cursoUsuario['empresa'] === $empresa['nombre_empresa']) ? 'selected' : '';
    ?>
        <option value="<?php echo $empresa['id_empresa_cliente']; ?>" <?php echo $selected; ?>>
            <?php echo htmlspecialchars(substr($empresa['nombre_empresa'], 0, 50)); ?>
        </option>
    <?php endforeach; ?>
</select>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="selectCurso" class="form-label">Nombre del Curso:</label>
                        <select class="form-select" id="selectCurso" name="selectCurso" required>
                            <?php 
                            if (!empty($cursosEmpresa)): 
                                foreach ($cursosEmpresa as $curso): 
                                    $selected = ($curso['id_curso_empresa'] == $cursoUsuario['id_curso_empresa']) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo $curso['id_curso_empresa']; ?>" 
                                        data-duracion="<?php echo $curso['duracion']; ?>"
                                        <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($curso['nombre_curso_fk']); ?>
                                    </option>
                                <?php endforeach; 
                            else: ?>
                                <option value="">No hay cursos disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                        <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                               value="<?php echo htmlspecialchars($cursoUsuario['fecha_inicio'] ?? ''); ?>" placeholder="Actualiza la fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                        <input type="text" class="form-control" id="fecha_fin" name="fecha_fin"
                               value="<?php echo htmlspecialchars($cursoUsuario['fecha_fin'] ?? ''); ?>" readonly>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/vista/ListaCursos.php'">Cancelar</button> 
        </form>
        <br>
        <!-- Formulario de acciones de notificación -->
<form method="post">
    <button type="submit" name="notificar" class="btn btn-warning">Notificar Ahora</button>
    <?php if ($cursoUsuario['notificaciones_activas'] == 0): ?>
        <!-- Si el campo es 0, las notificaciones están activadas, por lo que se muestra la opción de desactivarlas -->
        <button type="submit" name="desactivar_notificaciones" class="btn btn-secondary">Desactivar Notificaciones</button>
    <?php else: ?>
        <!-- Si el campo es 1, están desactivadas, se muestra la opción de activarlas -->
        <button type="submit" name="activar_notificaciones" class="btn btn-secondary">Activar Notificaciones</button>
    <?php endif; ?>
</form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fecha_inicio').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            function cargarCursosPorEmpresa(empresaId, cursoSeleccionado) {
                $.ajax({
                    url: 'cursoAsociarEmpresa.php',
                    type: 'GET',
                    data: { action: 'getCursos', empresa_id: empresaId },
                    dataType: 'json',
                    success: function(data) {
                        $('#selectCurso').empty();
                        if (data && data.length > 0) {
                            $.each(data, function(index, curso) {
                                const selected = curso.id_curso_empresa == cursoSeleccionado ? 'selected' : '';
                                $('#selectCurso').append(
                                    '<option value="' + curso.id_curso_empresa + '" ' + selected + ' data-duracion="' + (curso.duracion || 0) + '">' +
                                    (curso.nombre_curso_fk || curso.nombre_curso || 'Curso sin nombre') + ' (' + (curso.duracion || 0) + ' meses)' +
                                    '</option>'
                                );
                            });
                            $('#selectCurso').trigger('change');
                            $('#fecha_inicio').val('<?php echo $cursoUsuario["fecha_inicio"] ?? ""; ?>');
                            $('#fecha_fin').val('<?php echo $cursoUsuario["fecha_fin"] ?? ""; ?>');
                        } else {
                            $('#selectCurso').append('<option value="">No hay cursos disponibles</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener los cursos:', error);
                        $('#selectCurso').empty().append('<option value="">Error al cargar cursos</option>');
                    }
                });
            }
            $('#selectEmpresa').on('change', function() {
                const empresaId = $(this).val();
                if (empresaId) {
                    cargarCursosPorEmpresa(empresaId, <?php echo $cursoUsuario['id_curso_empresa'] ?? 0; ?>);
                } else {
                    $('#selectCurso').empty().append('<option value="">Seleccione un curso</option>');
                }
            });
            $('#selectCurso, #fecha_inicio').on('change', function() {
                const duracion = $('#selectCurso').find('option:selected').data('duracion');
                const fechaInicio = $('#fecha_inicio').val();
                if (fechaInicio) {
                    const fechaFin = new Date(fechaInicio);
                    fechaFin.setMonth(fechaFin.getMonth() + parseInt(duracion));
                    $('#fecha_fin').val($.datepicker.formatDate('yy-mm-dd', fechaFin));
                }
            });
            const empresaId = $('#selectEmpresa').val();
            cargarCursosPorEmpresa(empresaId, <?php echo $cursoUsuario['id_curso_empresa'] ?? 0; ?>);
        });
    </script>
</body>
</html>
