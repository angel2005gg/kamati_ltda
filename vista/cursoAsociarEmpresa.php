<?php
ob_start();
require_once '../modelo/Usuarios.php';
require_once '../controlador/ControladorCursoEmpresa.php';
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../modelo/email/emailHelper.php';

$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}

$controladorCursoEmpresa = new ControladorCursoEmpresa();
$controladorUsuario = new ControladorCursoUsuario();
$controladorEmpresa = new ControladorEmpresaCliente();

$usuarios = $controladorUsuario->obtenerTodosUsuarios();
$empresas = $controladorEmpresa->obtenerTodos();
$cursoUsuarioModel = new CursoUsuario();

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

if (isset($_GET['term'])) {
    header('Content-Type: application/json');
    
    try {
        $termino = trim($_GET['term']);
        $tipoBusqueda = isset($_GET['tipo']) ? $_GET['tipo'] : 'usuarios';
        
        error_log("Término de búsqueda recibido: " . $termino);
        error_log("Tipo de búsqueda: " . $tipoBusqueda);

        if ($tipoBusqueda === 'usuarios') {
            $resultados = $controladorUsuario->buscarUsuarios($termino);
        } elseif ($tipoBusqueda === 'contratistas') {
            $resultados = $controladorUsuario->buscarContratistas($termino);
        }

        error_log("Resultados de búsqueda: " . json_encode($resultados));
        echo json_encode($resultados);
        
    } catch (Exception $e) {
        error_log("Error en la búsqueda: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener los datos del formulario
        $id_usuario_con_prefijo = $_POST['id_usuario'];
        // Extraer el ID real (numérico) si existe un prefijo
        if (strpos($id_usuario_con_prefijo, '_') !== false) {
            list($tipo_del_id, $real_id) = explode('_', $id_usuario_con_prefijo, 2);
            $id_usuario = $real_id;
        } else {
            $id_usuario = $id_usuario_con_prefijo;
        }

        $tipo_usuario = $_POST['tipo_usuario'] ?? 'usuario';
        $id_curso_empresa = $_POST['id_curso_empresa'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $dias_notificacion = $_POST['dias_notificacion'] ?? 0;

        // Validación de datos requeridos
        if (!$id_usuario || !$id_curso_empresa || !$fecha_inicio) {
            throw new Exception("Faltan datos requeridos");
        }

        // (Opcional) Procesar según el tipo de usuario
        if ($tipo_usuario === 'contratista') {
            error_log("Procesando contratista con ID: " . $id_usuario);
            // Aquí podrías agregar lógica especial para contratistas.
        } else {
            error_log("Procesando usuario con ID: " . $id_usuario);
        }

        // Obtener la duración del curso y calcular fecha fin
        $cursoEmpresa = $controladorCursoEmpresa->obtenerPorId($id_curso_empresa);
        if (!$cursoEmpresa) {
            throw new Exception("No se pudo obtener la información del curso");
        }

        $duracion = $cursoEmpresa['duracion'];
        $fecha_fin = date('Y-m-d', strtotime("+$duracion months", strtotime($fecha_inicio)));

        // Debug
        error_log("Creando curso usuario con los siguientes datos:");
        error_log("ID Usuario: " . $id_usuario);
        error_log("ID Curso Empresa: " . $id_curso_empresa);
        error_log("Fecha Inicio: " . $fecha_inicio);
        error_log("Fecha Fin: " . $fecha_fin);
        error_log("Días de Notificación: " . $dias_notificacion);

        // Crear el curso usuario (usa la lógica adecuada)
        $resultado = $controladorUsuario->crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $dias_notificacion, $tipo_usuario);
        
        if (!$resultado) {
            throw new Exception("Error al crear el curso usuario");
        }

        // Redirigir en caso de éxito
        header("Location: ListaCursos.php?success=1");
        exit();
    } catch (Exception $e) {
        error_log("Error en el proceso: " . $e->getMessage());
        header("Location: cursoAsociarEmpresa.php?error=1&message=" . urlencode("Error al asociar el curso: " . $e->getMessage()));
        exit();
    }
}



include 'incluirNavegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #dee2e6;
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
        .ui-datepicker {
            background: #fff;
            border: 1px solid #555;
            color: #000;
        }
        .ui-datepicker-header {
            background: #f2f2f2;
            border-bottom: 1px solid #ddd;
        }
        .ui-datepicker-title {
            color: #333;
        }
        .ui-datepicker-prev, .ui-datepicker-next {
            cursor: pointer;
        }
        .ui-datepicker-calendar th {
            color: #333;
        }
        .ui-datepicker-calendar td a {
            color: #333;
        }
        .ui-datepicker-calendar td a:hover {
            background: #eee;
        }
        .ui-datepicker-trigger {
            width: 20px; 
            height: 20px; 
            vertical-align: middle; 
}
    </style>
   

</head>
<body>
<br><br>
    <div id="alertMessage" class="alert alert-floating" role="alert"></div>

    <div class="container form-container">
        <h2 class="text-center mb-4">Asignación de Curso</h2>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                Curso asociado correctamente.
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                Error al asociar el curso.
            </div>
        <?php endif; ?>
        <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3">
    <label for="tipo_busqueda" class="form-label">Buscar en:</label>
    <select class="form-select" id="tipo_busqueda" name="tipo_busqueda" required>
         <option value="usuarios">Usuarios</option>
         <option value="contratistas">Contratistas</option>
    </select>
</div>
   
        
        <!-- Campo Usuario -->
<div class="mb-3">
    <label for="id_usuario" class="form-label">Nombre:</label>
    <select class="form-select select2" id="id_usuario" name="id_usuario" required>
        <option value="">Buscar usuario...</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="usuario_<?php echo htmlspecialchars($usuario['id_Usuarios']); ?>">
    <?php echo htmlspecialchars(trim($usuario['nombre_completo'])); ?>
</option>

        <?php endforeach; ?>
    </select>
    <div class="invalid-feedback">Por favor seleccione un usuario.</div>
</div>

<!-- Campo oculto para almacenar el tipo (usuario o contratista) -->
<input type="hidden" id="tipo_usuario" name="tipo_usuario" value="">

            
            <!-- Campo Área (nuevo) -->
            <div class="mb-3">
              <label for="area_usuario" class="form-label">Área:</label>
               <input type="text" class="form-control" id="area_usuario" readonly>
             </div>

            <!-- Campo Empresa -->
            <div class="mb-3">
                <label for="selectEmpresa" class="form-label">Seleccione una Empresa:</label>
                <select class="form-select" id="selectEmpresa" name="selectEmpresa" required>
                    <option value="">Seleccione una empresa...</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa['id_empresa_cliente']; ?>"><?php echo $empresa['nombre_empresa']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campo Curso -->
            <div class="mb-3">
                <label for="selectCurso" class="form-label">Seleccione un Curso:</label>
                <select class="form-select" id="selectCurso" name="id_curso_empresa" required>
                    <option value="">Seleccione un curso...</option>
                </select>
            </div>

            <!-- Campo Fecha de Inicio -->
<div class="mb-3">
    <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
    <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" required autocomplete="off">
</div>

            <!-- Campo Fecha de Fin -->
            <div class="mb-3">
                <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" readonly>
            </div>
            <!-- Configuración de Notificaciones -->
            <div class="mb-3">
                <label class="form-label">Configuración de Notificación</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="enviar_correo" name="enviar_correo" checked>
                    <label class="form-check-label" for="enviar_correo">
                        Enviar notificación por correo
                    </label>
                </div>
                <div class="mb-3">
                    <label for="dias_notificacion" class="form-label">Días antes de enviar notificación:</label>
                    <select class="form-select" id="dias_notificacion" name="dias_notificacion" required>
                        <option value="10">10 días</option>
                        <option value="15">15 días</option>
                        <option value="20">20 días</option>
                        <!-- Agrega más opciones según sea necesario -->
                    </select>
                    <small class="form-text text-muted">Selecciona una opción</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>




<!-- 2. Luego Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

 <!-- Cargar jQuery primero -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Luego Bootstrap -->
<!-- 4. jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- 5. Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- 6. Tu script personalizado al final -->
<script src="../js/cursoAsociarEmpresa.js"></script>

</body>
</html>