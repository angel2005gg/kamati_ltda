<?php
require_once '../modelo/EmpresaCliente.php';
require_once '../modelo/Contratista.php';
require_once '../modelo/CursoEmpresa.php';
require_once '../controlador/ControladorEmpresaCliente.php';

$controladorEmpresa = new ControladorEmpresaCliente();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_empresa'])) {
    $nombre_empresa = $_POST['nombre_empresa'];
    $resultado = $controladorEmpresa->crear($nombre_empresa);
    if ($resultado) {
        echo "<script>alert('Empresa creada correctamente');</script>";
    } else {
        echo "<script>alert('Error al crear la empresa');</script>";
    }
}

session_start();

$empresa = new EmpresaCliente();
$contratista = new Contratista();
$curso = new CursoEmpresa();

// Obtener todas las empresas y contratistas para los selectores
$empresas = $empresa->obtenerTodos();
$contratistas = $contratista->obtenerTodos();
$cursos = $curso->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Estilo para las pestañas centradas y más grandes */
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
            border: none;
            color: #0d6efd;
        }

        .nav-tabs .nav-link.active {
            border: none;
            border-bottom: 3px solid #0d6efd;
            color: #0d6efd;
            font-weight: bold;
        }

        /* Estilo para las alertas */
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }
        
    </style>
</head>
<body>
    <!-- Alerta flotante para mensajes -->
    <div id="alertMessage" class="alert alert-floating" role="alert"></div>

    <div class="container mt-4">
        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="crear-tab" data-bs-toggle="tab" href="#crear" role="tab">Crear Nuevo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="eliminar-tab" data-bs-toggle="tab" href="#eliminar" role="tab">Eliminar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="frecuencia-tab" data-bs-toggle="tab" href="#frecuencia" role="tab">Crear Frecuencia</a>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="myTabContent">
            <!-- Pestaña Crear -->
            <div class="tab-pane fade show active" id="crear" role="tabpanel">
                <div class="row mt-4">
                    <!-- Formulario para crear una nueva empresa -->
<div class="col-md-4">
<div class="card"> 
<div class="card-header" > <h5>Crear Nueva Empresa</h5> </div>
    <div class="card-body">
    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" placeholder="Nombre de la Empresa"  required>
        </div>
        <button type="submit" class="btn btn-primary">Añadir</button>
    </form>
    </div>
    </div>
</div>

                    <!-- Formulario para añadir curso -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Añadir Nuevo Curso</h5>
                            </div>
                            <div class="card-body">
                                <form id="formCurso">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nombre_curso" placeholder="Nombre del curso" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Añadir</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario para añadir contratista -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Añadir Nuevo Contratista</h5>
                            </div>
                            <div class="card-body">
                                <form id="formContratista">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nombre_contratista" placeholder="Nombre del contratista" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Añadir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña Eliminar -->
            <div class="tab-pane fade" id="eliminar" role="tabpanel">
                <div class="row mt-4">
                    <!-- Eliminar Empresa -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Eliminar Empresa</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select mb-3" id="eliminarEmpresa">
                                    <option value="">Seleccione una empresa...</option>
                                    <?php foreach ($empresas as $emp): ?>
                                        <option value="<?php echo $emp['id_empresa']; ?>"><?php echo $emp['nombre_empresa']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-danger" onclick="eliminarEmpresa()">Eliminar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Eliminar Curso -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Eliminar Curso</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select mb-3" id="eliminarCurso">
                                    <option value="">Seleccione un curso...</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id_curso']; ?>"><?php echo $curso['nombre_curso']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-danger" onclick="eliminarCurso()">Eliminar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Eliminar Contratista -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Eliminar Contratista</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select mb-3" id="eliminarContratista">
                                    <option value="">Seleccione un contratista...</option>
                                    <?php foreach ($contratistas as $cont): ?>
                                        <option value="<?php echo $cont['id_contratista']; ?>"><?php echo $cont['nombre_contratista']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-danger" onclick="eliminarContratista()">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña Frecuencia -->
            <div class="tab-pane fade" id="frecuencia" role="tabpanel">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Crear Frecuencia de Curso</h5>
                    </div>
                    <div class="card-body">
                        <form id="formFrecuencia">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Empresa</label>
                                <select class="form-select" name="empresa_id" id="selectEmpresa" required>
                                    <option value="">Seleccione una empresa...</option>
                                    <?php foreach ($empresas as $emp): ?>
                                        <option value="<?php echo $emp['id_empresa']; ?>"><?php echo $emp['nombre_empresa']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Duración del Curso</label>
                                <select class="form-select" name="duracion" required>
                                    <option value="6">6 meses</option>
                                    <option value="12">1 Año</option>
                                    <option value="24">2 Años</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Seleccionar Curso</label>
                                <select class="form-select" name="curso_id" id="selectCurso" required>
                                    <option value="">Seleccione un curso...</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id_curso']; ?>"><?php echo $curso['nombre_curso']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Añadir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar alertas
        function showAlert(message, type) {
            const alert = document.getElementById('alertMessage');
            alert.className = `alert alert-${type} alert-floating`;
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

        // Función para manejar los formularios
        function handleForm(formId, url, successMessage) {
            document.getElementById(formId).addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert(successMessage, 'success');
                        this.reset();
                    } else {
                        showAlert('Error: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    showAlert('Error en la operación', 'danger');
                });
            });
        }

        // Inicializar los formularios
        handleForm('formEmpresa', 'controlador/ControladorEmpresaCliente.php', 'Empresa añadida correctamente');
        handleForm('formCurso', 'controllers/curso_controller.php', 'Curso añadido correctamente');
        handleForm('formContratista', 'controllers/contratista_controller.php', 'Contratista añadido correctamente');
        handleForm('formFrecuencia', 'controllers/frecuencia_controller.php', 'Frecuencia creada correctamente');

        // Funciones para eliminar
        function eliminarEmpresa() {
            const id = document.getElementById('eliminarEmpresa').value;
            if (id && confirm('¿Está seguro de eliminar esta empresa?')) {
                fetch(`controllers/empresa_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert('Empresa eliminada correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Error al eliminar la empresa', 'danger');
                    }
                });
            }
        }

        function eliminarCurso() {
            const id = document.getElementById('eliminarCurso').value;
            if (id && confirm('¿Está seguro de eliminar este curso?')) {
                fetch(`controllers/curso_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert('Curso eliminado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Error al eliminar el curso', 'danger');
                    }
                });
            }
        }

        function eliminarContratista() {
            const id = document.getElementById('eliminarContratista').value;
            if (id && confirm('¿Está seguro de eliminar este contratista?')) {
                fetch(`controllers/contratista_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert('Contratista eliminado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Error al eliminar el contratista', 'danger');
                    }
                });
            }
        }

        // Actualizar cursos cuando se selecciona una empresa
        document.getElementById('selectEmpresa').addEventListener('change', function() {
            const empresaId = this.value;
            if(empresaId) {
                fetch(`controllers/curso_controller.php?action=getPorEmpresa&id=${empresaId}`)
                    .then(response => response.json())
                    .then(data => {
                        const selectCurso = document.getElementById('selectCurso');
                        selectCurso.innerHTML = '<option value="">Seleccione un curso...</option>';
                        
                        if (data.success && data.cursos) {
                            data.cursos.forEach(curso => {
                                selectCurso.innerHTML += `<option value="${curso.id_curso}">${curso.nombre_curso}</option>`;
                            });
                        } else {
                            showAlert('Error al cargar los cursos', 'warning');
                        }
                    })
                    .catch(error => {
                        showAlert('Error al obtener los cursos', 'danger');
                    });
            }
        });
    </script>
</body>
</html>