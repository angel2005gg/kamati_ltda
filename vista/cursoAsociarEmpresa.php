<?php
ob_start();
require_once '../controlador/ControladorCursoEmpresa.php';
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../controlador/ControladorEmpresaCliente.php';

$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}

$controladorCursoEmpresa = new ControladorCursoEmpresa();
$controladorUsuario = new ControladorCursoUsuario();
$controladorEmpresa = new ControladorEmpresaCliente();

$usuarios = $controladorUsuario->obtenerTodosUsuarios();
$empresas = $controladorEmpresa->obtenerTodos();

// Manejo de solicitud AJAX para obtener cursos
if (isset($_GET['action']) && $_GET['action'] === 'getCursos' && isset($_GET['empresa_id'])) {
    try {
        error_log("Recibida solicitud para empresa_id: " . $_GET['empresa_id']);
        $cursosEmpresa = $controladorCursoEmpresa->obtenerPorEmpresa($_GET['empresa_id']);
        
        if ($cursosEmpresa === false) {
            throw new Exception("Error al obtener los cursos de la base de datos");
        }
        
        header('Content-Type: application/json');
        echo json_encode($cursosEmpresa ?: []);
        
    } catch (Exception $e) {
        error_log("Error en la solicitud AJAX: " . $e->getMessage());
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener los cursos: ' . $e->getMessage()]);
    }
    exit();
}

// Manejo del formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $id_curso_empresa = $_POST['id_curso_empresa'] ?? null;
    $duracion = $_POST['duracion'] ?? null;

    if ($id_usuario && $id_curso_empresa && $duracion) {
        $resultado = $controladorUsuario->crear($id_usuario, $id_curso_empresa);
        if ($resultado) {
            header("Location: cursoAsociarEmpresa.php?success=1");
            exit();
        } else {
            header("Location: cursoAsociarEmpresa.php?error=1");
            exit();
        }
    } else {
        header("Location: cursoAsociarEmpresa.php?error=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Curso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .select2-container {
            width: 100% !important;
        }
        .form-label {
            font-weight: 500;
        }
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        .error-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h2 class="text-center mb-4">Asignación de Curso</h2>
        <form method="POST" action="" class="needs-validation" novalidate>
            <!-- Campo Usuario -->
            <div class="mb-3">
                <label for="id_usuario" class="form-label">Nombre:</label>
                <select class="form-select select2" id="id_usuario" name="id_usuario" required>
    <option value="">Buscar usuario...</option>
    <?php foreach ($usuarios as $usuario): ?>
        <option value="<?php echo htmlspecialchars($usuario['id_Usuarios']); ?>" 
                data-area="<?php echo htmlspecialchars($usuario['area'] ?? ''); ?>">
            <?php echo htmlspecialchars($usuario['nombre_completo']); ?>
        </option>
    <?php endforeach; ?>
</select>
                <div class="invalid-feedback">Por favor seleccione un usuario.</div>
            </div>
            <!-- Campo Área -->
            <div class="mb-3">
                <label class="form-label">Área:</label>
                <input type="text" class="form-control" id="area" name="area" readonly>
            </div>

            <!-- Campo Empresa -->
            <div class="mb-3">
                <label for="id_empresa" class="form-label">Empresa:</label>
                <select class="form-select" id="id_empresa" name="id_empresa" required>
                    <option value="">Seleccione una empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo htmlspecialchars($empresa['id_empresa_cliente']); ?>">
                            <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Por favor seleccione una empresa.</div>
            </div>

            <!-- Campo Curso -->
            <div class="mb-3">
                <label for="id_curso_empresa" class="form-label">Curso:</label>
                <select class="form-select" id="id_curso_empresa" name="id_curso_empresa" required>
                    <option value="">Primero seleccione una empresa</option>
                </select>
                <div class="invalid-feedback">Por favor seleccione un curso.</div>
            </div>

            <!-- Campo Fecha de realización -->
            <div class="mb-3">
                <label class="form-label">Fecha de realización:</label>
                <input type="date" class="form-control" id="fecha_realizacion" name="fecha_realizacion" required>
                <div class="invalid-feedback">Por favor seleccione una fecha de realización.</div>
            </div>

            <!-- Campo Fecha de vencimiento -->
            <div class="mb-3">
                <label class="form-label">Fecha de vencimiento:</label>
                <input type="text" class="form-control" id="fecha_vencimiento" readonly>
            </div>

            <!-- Campo Estado -->
            <div class="mb-3">
                <label class="form-label">Estado:</label>
                <input type="text" class="form-control" id="estado" readonly>
            </div>

            <!-- Botones -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='ListaCursos.php'">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    Asignar
                </button>
            </div>
        </form>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
$(document).ready(function() {
    console.log("Iniciando configuración...");
    
    // Inicializar Select2
    $('#id_usuario').select2({
        placeholder: 'Buscar usuario...',
        minimumInputLength: 1,
        width: '100%',
        language: {
            inputTooShort: function() {
                return "Por favor ingrese 1 o más caracteres";
            },
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        },
        matcher: function(params, data) {
            // Si no hay término de búsqueda, retornar todos
            if ($.trim(params.term) === '') {
                return data;
            }

            // Si no hay datos, retornar null
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Buscar en el texto del usuario
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }

            // Si no hay coincidencia, retornar null
            return null;
        }
    });

    // Manejar el cambio de usuario
    $('#id_usuario').on('change', function() {
        console.log("Usuario seleccionado");
        const selectedOption = $(this).find('option:selected');
        const area = selectedOption.data('area');
        console.log("Área:", area);
        $('#area').val(area || '');
    });

    // Mantén el resto de tu código existente que maneja empresas y cursos
    $('#id_empresa').on('change', function() {
        // ... tu código existente para manejar empresas ...
    });

    $('#id_curso_empresa').on('change', function() {
        // ... tu código existente para manejar cursos ...
    });

        // Función para resetear campos relacionados con el curso
        function resetearCamposCurso() {
            $('#fecha_realizacion').val('');
            $('#fecha_vencimiento').val('');
            $('#estado').val('');
        }
    });
    </script>
</body>
</html>