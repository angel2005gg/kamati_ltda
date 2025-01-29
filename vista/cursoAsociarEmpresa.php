<?php
ob_start();
require_once '../controlador/ControladorCursoEmpresa.php';
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../controlador/ControladorEmpresaCliente.php';

$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}

$controladorCurso = new ControladorCursoEmpresa();
$controladorUsuario = new ControladorCursoUsuario();
$controladorEmpresa = new ControladorEmpresaCliente();

$usuarios = $controladorUsuario->obtenerTodos();
$empresas = $controladorEmpresa->obtenerTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $id_empresa = $_POST['id_empresa'] ?? null;
    $nombre_curso = $_POST['nombre_curso'] ?? null;
    $fecha_realizacion = $_POST['fecha_realizacion'] ?? null;
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
    $notificacion = isset($_POST['notificacion']) ? 1 : 0;
    $dias_notificacion = $_POST['dias_notificacion'] ?? null;

    if ($id_usuario && $id_empresa && $fecha_realizacion && $fecha_vencimiento && $nombre_curso) {
        try {
            $estado = determinarEstado($fecha_vencimiento);
            $controladorCurso->crear(
                $id_empresa,
                $id_usuario,
                $fecha_realizacion,
                $nombre_curso,
                $fecha_vencimiento,
                $estado
            );
            header('Location: ListaCursos.php');
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error al asignar la empresa: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos obligatorios.');</script>";
    }
}

function determinarEstado($fecha_vencimiento) {
    $fecha_actual = new DateTime();
    $fecha_venc = new DateTime($fecha_vencimiento);
    $diferencia = $fecha_actual->diff($fecha_venc);
    $dias = $diferencia->invert ? -$diferencia->days : $diferencia->days;

    if ($dias < 0) {
        return 'Vencido';
    } elseif ($dias <= 15) {
        return 'Por vencer';
    } else {
        return 'Vigente';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Empresa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        #dias_notificacion_container {
            display: none;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h2 class="text-center mb-4">Asignación de Empresa</h2>
        <form method="POST" action="" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="id_usuario" class="form-label">Nombre:</label>
                <select class="form-select select2" id="id_usuario" name="id_usuario" required>
                    <option value="">Buscar usuario...</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo htmlspecialchars($usuario['id_curso_usuario']); ?>"
                                data-area="<?php echo htmlspecialchars($usuario['area']); ?>">
                            <?php echo htmlspecialchars(trim($usuario['usuario'])); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Área:</label>
                <input type="text" class="form-control" id="area" name="area" readonly>
            </div>

            <div class="mb-3">
                <label for="id_empresa" class="form-label">Asociar Empresa:</label>
                <select class="form-select" id="id_empresa" name="id_empresa" required>
                    <option value="">Seleccione una empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo htmlspecialchars($empresa['id_empresa_cliente']); ?>">
                            <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nombre_curso" class="form-label">Nombre del Curso:</label>
                <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_realizacion" class="form-label">Fecha de realización:</label>
                    <input type="date" class="form-control" id="fecha_realizacion" name="fecha_realizacion" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_vencimiento" class="form-label">Fecha final:</label>
                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <input type="text" class="form-control" id="estado" name="estado" readonly>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notificacion" name="notificacion">
                    <label class="form-check-label" for="notificacion">Enviar notificación</label>
                </div>
            </div>

            <div class="mb-3" id="dias_notificacion_container">
                <label class="form-label">Días de anticipación:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="dias_notificacion" value="20" id="dias20">
                    <label class="form-check-label" for="dias20">20 días</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="dias_notificacion" value="15" id="dias15">
                    <label class="form-check-label" for="dias15">15 días</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="dias_notificacion" value="10" id="dias10">
                    <label class="form-check-label" for="dias10">10 días</label>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='ListaCursos.php'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Asignar</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        // Inicializar Select2 para la búsqueda de usuarios
        $('.select2').select2({
            placeholder: 'Buscar usuario...',
            minimumInputLength: 1
        });

        // Manejar el cambio de usuario y actualizar el área
        $('#id_usuario').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const area = selectedOption.data('area');
            $('#area').val(area || '');
        });

        // Manejar el cambio de fecha y actualizar el estado
        $('#fecha_vencimiento').on('change', function() {
            const fechaVencimiento = new Date($(this).val());
            const hoy = new Date();
            const diferenciaDias = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            
            if (diferenciaDias < 0) {
                $('#estado').val('Vencido');
            } else if (diferenciaDias <= 15) {
                $('#estado').val('Por vencer');
            } else {
                $('#estado').val('Vigente');
            }
        });

        // Mostrar/ocultar días de notificación
        $('#notificacion').change(function() {
            $('#dias_notificacion_container').toggle(this.checked);
        });
    });
    </script>
</body>
</html>