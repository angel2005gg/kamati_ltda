<?php
require_once '../modelo/EmpresaCliente.php';
require_once '../modelo/Contratista.php';

session_start();

$empresa = new EmpresaCliente();
$contratista = new Contratista();

// Obtener todas las empresas y contratistas para los selectores
$empresas = $empresa->obtenerTodos();
$contratistas = $contratista->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                    <!-- #############################################Formulario para añadir empresa ##############################################-->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Añadir Nueva Empresa</h5>
                            </div>
                            <div class="card-body">
                                <form id="formEmpresa" method="POST" action="controllers/empresa_controller.php">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nombre_empresa" required>
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
                                <form id="formCurso" method="POST" action="controllers/curso_controller.php">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nombre_curso" required>
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
                                <form id="formContratista" method="POST" action="controllers/contratista_controller.php">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nombre_contratista" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Añadir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ######################################################################Pestaña Eliminar  ################################################ -->
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
                                        <option value="<?php echo $emp['id_empresa']; ?>">
                                            <?php echo $emp['nombre_empresa']; ?>
                                        </option>
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
                                        <option value="<?php echo $curso['id_curso']; ?>">
                                            <?php echo $curso['nombre_curso']; ?>
                                        </option>
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
                                        <option value="<?php echo $cont['id_contratista']; ?>">
                                            <?php echo $cont['nombre_contratista']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-danger" onclick="eliminarContratista()">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ###########################################################Pestaña Frecuencia########################################### -->
            <div class="tab-pane fade" id="frecuencia" role="tabpanel">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Crear Frecuencia de Curso</h5>
                    </div>
                    <div class="card-body">
                        <form id="formFrecuencia" method="POST" action="controllers/frecuencia_controller.php">
                            <!-- Selector de Empresa -->
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Empresa</label>
                                <select class="form-select" name="empresa_id" required>
                                    <option value="">Seleccione una empresa...</option>
                                    <?php foreach ($empresas as $emp): ?>
                                        <option value="<?php echo $emp['id_empresa']; ?>">
                                            <?php echo $emp['nombre_empresa']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Selector de Frecuencia -->
                            <div class="mb-3">
                                <label class="form-label">Duración del Curso</label>
                                <select class="form-select" name="duracion" required>
                                    <option value="6">6 meses</option>
                                    <option value="12">1 Año</option>
                                    <option value="24">2 Años</option>
                                </select>
                            </div>

                            <!-- Selector de Curso -->
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Curso</label>
                                <select class="form-select" name="curso_id" required>
                                    <option value="">Seleccione un curso...</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id_curso']; ?>">
                                            <?php echo $curso['nombre_curso']; ?>
                                        </option>
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
        // Funciones para eliminar
        function eliminarEmpresa() {
            const id = document.getElementById('eliminarEmpresa').value;
            if (id && confirm('¿Está seguro de eliminar esta empresa?')) {
                fetch(`controllers/empresa_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }

        function eliminarCurso() {
            const id = document.getElementById('eliminarCurso').value;
            if (id && confirm('¿Está seguro de eliminar este curso?')) {
                fetch(`controllers/curso_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }

        function eliminarContratista() {
            const id = document.getElementById('eliminarContratista').value;
            if (id && confirm('¿Está seguro de eliminar este contratista?')) {
                fetch(`controllers/contratista_controller.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>