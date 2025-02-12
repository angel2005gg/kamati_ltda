<?php
require_once '../controlador/ControladorCurso.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../modelo/EmpresaCliente.php';
require_once '../modelo/Contratista.php';
require_once '../modelo/CursoEmpresa.php';
require_once '../controlador/ControladorContratista.php';


$controladorEmpresa = new ControladorEmpresaCliente();
$empresas = $controladorEmpresa->obtenerTodos();
$controladorCurso = new ControladorCurso();
$cursos = $controladorCurso->obtenerTodos();
$controladorContratista = new ControladorContratista();
$contratistas = $controladorContratista->obtenerTodos();

// Para empresa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_empresa'])) {
    $id_empresa = $_POST['id_empresa'];
    try {
        $resultado = $controladorEmpresa->eliminar($id_empresa);
        if ($resultado) {
            header("Location: CursosGestionEliminar.php?success=empresa");
        } else {
            header("Location: CursosGestionEliminar.php?error=empresa");
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: CursosGestionEliminar.php?error=constraint_empresa");
    }
    exit();
}

// Para curso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_curso'])) {
    $id_curso = $_POST['id_curso'];
    try {
        $resultado = $controladorCurso->eliminar($id_curso);
        if ($resultado) {
            header("Location: CursosGestionEliminar.php?success=curso");
        } else {
            header("Location: CursosGestionEliminar.php?error=curso");
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: CursosGestionEliminar.php?error=constraint_curso");
    }
    exit();
}

// Para contratista
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_contratista'])) {
    $id_contratista = $_POST['id_contratista'];
    try {
        $resultado = $controladorContratista->eliminar($id_contratista);
        if ($resultado) {
            header("Location: CursosGestionEliminar.php?success=contratista");
        } else {
            header("Location: CursosGestionEliminar.php?error=contratista");
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: CursosGestionEliminar.php?error=constraint_contratista");
    }
    exit();
}
$empresa = new EmpresaCliente();
$contratista = new Contratista();
$curso = new CursoEmpresa();

session_start();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos - Eliminar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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
            border: none;
            color: #0d6efd;
        }

        .nav-tabs .nav-link.active {
            border: none;
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
    </style>
</head>
<body>
<?php include 'navBar.php'; ?>
<br><br>

    <div id="alertMessage" class="alert alert-floating" role="alert"></div>

    <div class="container mt-4">
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="../vista/CursosGestion.php">Crear Nuevo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="../vista/CursosGestionEliminar.php">Eliminar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../vista/CursosGestionFrecuencia.php">Crear Frecuencia</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="ListaEmpresaAsociadas.php">Lista de Frecuencias</a></li>
            <li class="nav-item"><a class="nav-link " href="ListaCorreoUsuario.php">Historial</a></li>


        </ul>

        <div class="row mt-4">
            <!-- Eliminar Empresa -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Eliminar Empresa</h5>
                    </div>
                    <div class="card-body">
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'empresa'): ?>
    <div class="alert alert-success" role="alert">
        Empresa eliminada correctamente.
    </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'empresa'): ?>
    <div class="alert alert-danger" role="alert">
        Error al eliminar la empresa.
    </div>
<?php endif; ?>
<!-- Para empresa -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'constraint_empresa'): ?>
    <div class="alert alert-warning" role="alert">
        No se puede eliminar. La empresa está siendo utilizada en otros registros.
    </div>
<?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <select class="form-select" id="id_empresa" name="id_empresa" required>
                                    <option value="">Seleccione una empresa...</option>
                                    <?php foreach ($empresas as $emp): ?>
                                        <option value="<?php echo $emp['id_empresa_cliente']; ?>"><?php echo $emp['nombre_empresa']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Eliminar Empresa</button>
                        </form>
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
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'curso'): ?>
    <div class="alert alert-success" role="alert">
        Curso eliminado correctamente.
    </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'curso'): ?>
    <div class="alert alert-danger" role="alert">
        Error al eliminar el curso.
    </div>
<?php endif; ?>
<!-- Para curso -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'constraint_curso'): ?>
    <div class="alert alert-warning" role="alert">
        No se puede eliminar. El curso está siendo utilizado en otros registros.
    </div>
<?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <select class="form-select" id="id_curso" name="id_curso" required>
                                    <option value="">Seleccione un curso...</option>
                                    
                                    <?php var_dump($cursos); ?>

                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id_curso']; ?>"><?php echo $curso['nombre_curso_fk']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Eliminar Curso</button>
                        </form>
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
                        <?php if (isset($_GET['success']) && $_GET['success'] === 'contratista'): ?>
                            <div class="alert alert-success" role="alert">
                                Contratista eliminado correctamente.
                            </div>
                        <?php elseif (isset($_GET['error']) && $_GET['error'] === 'contratista'): ?>
                            <div class="alert alert-danger" role="alert">
                                Error al eliminar el contratista.
                            </div>
                        <?php endif; ?>
                        <!-- Para contratista -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'constraint_contratista'): ?>
    <div class="alert alert-warning" role="alert">
        No se puede eliminar. El contratista está siendo utilizado en otros registros.
    </div>
<?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <select class="form-select" id="id_contratista" name="id_contratista" required>
                                    <option value="">Seleccione un contratista...</option>
                                    <?php foreach ($contratistas as $contratista): ?>
                                        <option value="<?php echo $contratista['id_contratista']; ?>"><?php echo $contratista['nombre_contratista']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Eliminar Contratista</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function showAlert(message, type) {
            const alert = document.getElementById('alertMessage');
            alert.className = `alert alert-${type} alert-floating`;
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

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
    </script>
</body>
</html>