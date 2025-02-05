<?php
require_once '../controlador/ControladorCursoEmpresa.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../controlador/ControladorCurso.php';
$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}

$controladorCursoEmpresa = new ControladorCursoEmpresa();
$controladorEmpresa = new ControladorEmpresaCliente();
$controladorCurso = new ControladorCurso();

$empresas = $controladorEmpresa->obtenerTodos();
$cursos = $controladorCurso->obtenerTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa_cliente = $_POST['selectEmpresa'] ?? null;
    $id_curso = $_POST['selectCurso'] ?? null;
    $duracion = $_POST['duracion'] ?? null;
    $fecha_realizacion = $_POST['fecha_realizacion'] ?? null;
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

    if (!empty($_POST['custom_duration_enabled'])) {
        $custom_years = intval($_POST['custom_years'] ?? 0);
        $custom_months = intval($_POST['custom_months'] ?? 0);
        $duracion = ($custom_years * 12) + $custom_months;
    }

    if ($id_empresa_cliente && $id_curso) {
        if (!empty($fecha_realizacion) && !empty($fecha_vencimiento)) {
            $duracion = null;
        }

        $resultado = $controladorCursoEmpresa->crear($id_empresa_cliente, $id_curso, $duracion, $fecha_realizacion, $fecha_vencimiento, 'activo');

        if ($resultado) {
            header("Location: CursosGestionFrecuencia.php?success=1");
            exit();
        } else {
            header("Location: CursosGestionFrecuencia.php?error=1");
            exit();
        }
    } else {
        header("Location: CursosGestionFrecuencia.php?error=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos - Frecuencia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        .nav-tabs {
            justify-content: center;
            border-bottom: 2px solid #dee2e6;
        }
        .nav-tabs .nav-link {
            font-size: 1.2rem;
            padding: 1rem 2rem;
            margin: 0 1rem;
            border: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            color: #0d6efd;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 3px solid #0d6efd;
            color: #0d6efd;
            font-weight: bold;
        }
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }
        .custom-duration {
            display: none;
        }
    </style>
</head>
<body>
    <div id="alertMessage" class="alert alert-floating" role="alert"></div>
    <div class="container mt-4">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link" href="CursosGestion.php">Crear Nuevo</a></li>
            <li class="nav-item"><a class="nav-link" href="CursosGestionEliminar.php">Eliminar</a></li>
            <li class="nav-item"><a class="nav-link active" href="CursosGestionFrecuencia.php">Crear Frecuencia</a></li>
            <li class="nav-item"><a class="nav-link" href="ListaEmpresaAsociadas.php">Lista de Cursos</a></li>

        </ul>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Curso asignado correctamente.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Error al asignar el curso.</div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="selectEmpresa" class="form-label">Seleccione una Empresa:</label>
                <select class="form-select" id="selectEmpresa" name="selectEmpresa" required>
                    <option value="">Seleccione una empresa...</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa['id_empresa_cliente']; ?>"><?php echo $empresa['nombre_empresa']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="selectCurso" class="form-label">Seleccione un Curso:</label>
                <select class="form-select" id="selectCurso" name="selectCurso" required>
                    <option value="">Seleccione un curso...</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo $curso['id_curso']; ?>"><?php echo $curso['nombre_curso_fk']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Duración del Curso:</label>
                <select class="form-select" id="duracion" name="duracion">
                    <option value="12">1 Año</option>
                    <option value="6">6 meses</option>
                    <option value="24">2 Años</option>
                </select>
            </div>

            <button type="button" class="btn btn-secondary" id="toggleCustomDuration">Personalizar Duración</button>

            <div class="custom-duration">
                <input type="hidden" name="custom_duration_enabled" id="custom_duration_enabled" value="0">
                <div class="mb-3">
                    <label for="custom_years" class="form-label">Años:</label>
                    <select class="form-select" id="custom_years" name="custom_years">
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> <?= $i == 1 ? 'año' : 'años' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="custom_months" class="form-label">Meses:</label>
                    <select class="form-select" id="custom_months" name="custom_months">
                        <?php for ($i = 0; $i <= 11; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> <?= $i == 1 ? 'mes' : 'meses' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#toggleCustomDuration').click(function() {
                $('.custom-duration').toggle();
                $('#custom_duration_enabled').val($('.custom-duration').is(':visible') ? '1' : '0');
                $('#duracion').prop('disabled', $('.custom-duration').is(':visible'));
            });
        });
    </script>
</body>
</html>
