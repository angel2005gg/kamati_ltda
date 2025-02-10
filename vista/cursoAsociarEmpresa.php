<?php
ob_start();
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


// Código existente...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $fecha_fin = $_POST['fecha_fin'];
    $enviar_correo = isset($_POST['enviar_correo']) ? true : false;
    $dias_notificacion = $_POST['dias_notificacion'];

    // Código existente para procesar el formulario...

    // Configuración de notificaciones
    if ($enviar_correo) {
        $destinatarios = ['destinatario1@dominio.com', 'destinatario2@dominio.com']; // Reemplaza con los correos reales
        $asunto = 'Notificación de Curso';
        $mensaje = 'Este es un recordatorio de que el curso finalizará el ' . $fecha_fin;

        // Enviar correos electrónicos en los días especificados
        foreach ($dias_notificacion as $dias) {
            $fecha_notificacion = date('Y-m-d', strtotime("$fecha_fin - $dias days"));
            if (date('Y-m-d') == $fecha_notificacion) {
                enviarCorreo($destinatarios, $asunto, $mensaje);
            }
        }
    }
}
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
// Manejo del formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $id_curso_empresa = $_POST['id_curso_empresa'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;

    if ($id_usuario && $id_curso_empresa && $fecha_inicio) {
        // Obtener la duración del curso desde curso_empresa
        $cursoEmpresa = $controladorCursoEmpresa->obtenerPorId($id_curso_empresa);
        $duracion = $cursoEmpresa['duracion'];
        $fecha_fin = date('Y-m-d', strtotime("+$duracion months", strtotime($fecha_inicio)));

        $resultado = $controladorUsuario->crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin);
        if ($resultado) {
            header("Location: ListaCursos.php?success=1");
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
<?php include 'navBar.php'; ?>
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
            <!-- Campo Usuario -->
            <div class="mb-3">
                <label for="id_usuario" class="form-label">Nombre:</label>
                <select class="form-select select2" id="id_usuario" name="id_usuario" required>
                    <option value="">Buscar usuario...</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo htmlspecialchars($usuario['id_Usuarios']); ?>">
                            <?php echo htmlspecialchars(trim($usuario['nombre_completo'])); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Por favor seleccione un usuario.</div>
            </div>
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
                <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
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
        <select class="form-select" id="dias_notificacion" name="dias_notificacion" multiple>
            <option value="10">10 días</option>
            <option value="15">15 días</option>
            <option value="20">20 días</option>
        </select>
        <small class="form-text text-muted">Puedes seleccionar múltiples opciones</small>
    </div>
</div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
   $('.select2').select2();

   $('#fecha_inicio').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+10',
                showOn: 'both',  // Mostrar tanto en el input como en un icono
                buttonImage: '../img/calendario.png',
                buttonImageOnly: true,
                beforeShow: function(input, inst) {
                    setTimeout(function() {
                        var buttonPane = $(input)
                            .datepicker("widget")
                            .find(".ui-datepicker-buttonpane");

                        $("<button>", {
                            text: "Hoy",
                            click: function() {
                                $.datepicker._selectDate(input);
                            }
                        }).appendTo(buttonPane)
                          .addClass("ui-datepicker-current ui-state-default ui-priority-primary ui-corner-all");
                    }, 1);
                }
            });
   $('#selectEmpresa').on('change', function() {
       const empresaId = $(this).val();
       if (empresaId) {
           $.ajax({
               url: 'cursoAsociarEmpresa.php',
               type: 'GET',
               data: { action: 'getCursos', empresa_id: empresaId },
               dataType: 'json',
               success: function(data) {
    $('#selectCurso').empty();
    $('#selectCurso').append('<option value="">Seleccione un curso...</option>');
    if (data && data.length > 0) {
        data.forEach(function(curso) {
            if (curso && curso.nombre_curso_fk) {
                $('#selectCurso').append(
                    '<option value="' + curso.id_curso_empresa + '" data-duracion="' + curso.duracion + '">' +
                    curso.nombre_curso_fk + ' (' + curso.duracion + ' meses)' +
                    '</option>'
                );
            }
        });
    }
},
               error: function(xhr, status, error) {
                   console.error('Error al obtener los cursos:', xhr.responseText);
               }
           });
       } else {
           $('#selectCurso').empty().append('<option value="">Seleccione un curso...</option>');
       }
   });

   $('#selectCurso, #fecha_inicio').on('change', function() {
       const selectedCurso = $('#selectCurso').find('option:selected');
       const duracion = selectedCurso.data('duracion');
       const fechaInicio = $('#fecha_inicio').val();

       if (fechaInicio && duracion) {
           const fechaFin = new Date(fechaInicio);
           fechaFin.setMonth(fechaFin.getMonth() + parseInt(duracion));
           $('#fecha_fin').val($.datepicker.formatDate('yy-mm-dd', fechaFin));
       }
   });

   // Validación del formulario
   const form = document.querySelector('.needs-validation');
   form.addEventListener('submit', function(event) {
       if (!form.checkValidity()) {
           event.preventDefault();
           event.stopPropagation();
       }
       form.classList.add('was-validated');
   });

   // Configuración de Select2 para días de notificación
   $('#dias_notificacion').select2({
       placeholder: "Seleccione días de notificación",
       allowClear: true
   });
   $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant ',
    nextText: ' Sig >',
    currentText: 'Actualiza la fecha',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'yy-mm-dd',
    firstDay: 1
};
$.datepicker.setDefaults($.datepicker.regional['es']);

$('#id_usuario').on('change', function() {
    const usuarioId = $(this).val();
    if (usuarioId) {
        const formData = new FormData();
        formData.append('id', usuarioId);

        $.ajax({
            url: 'obtener_area_usuario.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta completa:', response); // Debug
                if (response.area) {
                    $('#area_usuario').val(response.area);
                } else {
                    $('#area_usuario').val('');
                    console.error('Error al obtener el área:', response.error);
                    console.log('Debug info:', response.debug); // Debug
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error);
                console.log('Estado de la respuesta:', xhr.status);
                console.log('Respuesta:', xhr.responseText);
                $('#area_usuario').val('');
            }
        });
    } else {
        $('#area_usuario').val('');
    }
});
});
    </script>
</body>
</html>