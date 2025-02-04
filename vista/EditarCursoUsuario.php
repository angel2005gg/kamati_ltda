<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../controlador/ControladorCursoEmpresa.php';
$controladorCursoUsuario = new ControladorCursoUsuario();
$controladorEmpresa = new ControladorEmpresaCliente();
$controladorCursoEmpresa = new ControladorCursoEmpresa();
$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}
// Verificar si se proporcionó el ID del curso de usuario
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

// Asegurar que id_empresa_cliente exista en el curso de usuario
$id_empresa_actual = isset($cursoUsuario['id_empresa_cliente']) ? 
    intval($cursoUsuario['id_empresa_cliente']) : 
    null;

// Obtener cursos de la empresa específica
$cursosEmpresa = $id_empresa_actual ? 
    $controladorCursoEmpresa->obtenerPorEmpresa($id_empresa_actual) : 
    [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $controladorCursoUsuario->eliminar($id_curso_usuario);
        header("Location: ListaCursosEditar.php?deleted=1");
        exit();
    } else {
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $id_empresa_cliente = $_POST['selectEmpresa'] ?? null;
        $id_curso_empresa = $_POST['selectCurso'] ?? null;

        if ($fecha_inicio && $id_empresa_cliente && $id_curso_empresa) {
            $cursoEmpresa = $controladorCursoEmpresa->obtenerPorId($id_curso_empresa);
            $duracion = $cursoEmpresa['duracion'] ?? 0;
            $fecha_fin = date('Y-m-d', strtotime("+$duracion months", strtotime($fecha_inicio)));

            $controladorCursoUsuario->actualizar($id_curso_usuario, $cursoUsuario['id_Usuarios'], $id_curso_empresa, $fecha_inicio, $fecha_fin);
            header("Location: ListaCursosEditar.php?updated=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
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
    <div class="container form-container">
        <h2 class="text-center mb-4">Editar Curso de Usuario</h2>
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
                                $selected = ($id_empresa_actual && $empresa['id_empresa_cliente'] == $id_empresa_actual) ? 'selected' : '';
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
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/kamati_ltda/vista/ListaCursosEditar.php'">Cancelar</button>        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
    // Inicializar datepicker
    $('#fecha_inicio').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Cargar cursos automáticamente al cargar la página
    function cargarCursosPorEmpresa(empresaId) {
        $.ajax({
            url: 'cursoAsociarEmpresa.php',
            type: 'GET',
            data: { action: 'getCursos', empresa_id: empresaId },
            dataType: 'json',
            success: function(data) {
                $('#selectCurso').empty();
                if (data && data.length > 0) {
                    $.each(data, function(index, curso) {
                        const selected = curso.id_curso_empresa == <?php echo $cursoUsuario['id_curso_empresa'] ?? 0; ?> ? 'selected' : '';
                        $('#selectCurso').append(
                            '<option value="' + curso.id_curso_empresa + '" ' + selected + ' data-duracion="' + (curso.duracion || 0) + '">' +
                            (curso.nombre_curso_fk || curso.nombre_curso || 'Curso sin nombre') + ' (' + (curso.duracion || 0) + ' meses)' +
                            '</option>'
                        );
                    });
                    
                    // Forzar cambio de curso para calcular fecha de fin
                    $('#selectCurso').trigger('change');

                    // Establecer fechas después de cargar los cursos
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

    // Cargar cursos al cambiar la empresa
    $('#selectEmpresa').on('change', function() {
        const empresaId = $(this).val();
        if (empresaId) {
            cargarCursosPorEmpresa(empresaId);
        } else {
            $('#selectCurso').empty().append('<option value="">Seleccione un curso</option>');
        }
    });

    // Calcular fecha de fin automáticamente
    $('#selectCurso, #fecha_inicio').on('change', function() {
        const duracion = $('#selectCurso').find('option:selected').data('duracion');
        const fechaInicio = $('#fecha_inicio').val();
        if (fechaInicio) {
            const fechaFin = new Date(fechaInicio);
            fechaFin.setMonth(fechaFin.getMonth() + parseInt(duracion));
            $('#fecha_fin').val($.datepicker.formatDate('yy-mm-dd', fechaFin));
        }
    });

    // Cargar cursos automáticamente al inicio
    const empresaId = $('#selectEmpresa').val();
    cargarCursosPorEmpresa(empresaId);
});
    </script>
</body>
</html>