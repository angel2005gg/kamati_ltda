<?php
require_once '../controlador/ControladorCurso.php';
require_once '../modelo/EmpresaCliente.php';
require_once '../modelo/Contratista.php';
require_once '../modelo/CursoEmpresa.php';
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../controlador/ControladorContratista.php';

$controladorCurso = new ControladorCurso();
$controladorEmpresa = new ControladorEmpresaCliente();
$empresa = new EmpresaCliente();
$contratista = new Contratista();
$curso = new CursoEmpresa();
$controladorContratista = new ControladorContratista();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_contratista'])) {
    $nombre_contratista = $_POST['nombre_contratista'];
    $resultadoContratista = $controladorContratista->crear($nombre_contratista);
    if ($resultadoContratista) {
        header("Location: CursosGestion.php?success_contratista=1");
        exit();
    } else {
        header("Location: CursosGestion.php?error_contratista=1");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_curso_fk'])) {
    $nombre_curso_fk = $_POST['nombre_curso_fk'];
    $resultadoCurso = $controladorCurso->crear($nombre_curso_fk);
    if ($resultadoCurso) {
        header("Location: CursosGestion.php?success_curso=1");
        exit();
    } else {
        header("Location: CursosGestion.php?error_curso=1");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_empresa'])) {
    $nombre_empresa = $_POST['nombre_empresa'];
    $resultado = $controladorEmpresa->crear($nombre_empresa);
    if ($resultado) {
        header("Location: CursosGestion.php?success_empresa=1");
        exit();
    } else {
        header("Location: CursosGestion.php?error_empresa=1");
        exit();
    }
}

session_start();
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
                <a class="nav-link active" href="../vista/CursosGestion.php">Crear Nuevo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../vista/CursosGestionEliminar.php">Eliminar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="CursosGestionFrecuencia.php">Crear Frecuencia</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="ListaEmpresaAsociadas.php">Lista de Frecuencias</a></li>

        </ul>

        <div class="row mt-4">
            <!-- Formulario para crear una nueva empresa -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h5>Crear Nueva Empresa</h5></div>
                    <div class="card-body">
                        <?php if (isset($_GET['success_empresa'])): ?>
                            <div class="alert alert-success" role="alert">
                                Empresa creada correctamente.
                            </div>
                        <?php elseif (isset($_GET['error_empresa'])): ?>
                            <div class="alert alert-danger" role="alert">
                                Error al crear la empresa.
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" placeholder="Nombre de la Empresa" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario para crear un nuevo curso -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h5>Crear Nuevo Curso</h5></div>
                    <div class="card-body">
                        <?php if (isset($_GET['success_curso'])): ?>
                            <div class="alert alert-success" role="alert">
                                Curso creado correctamente.
                            </div>
                        <?php elseif (isset($_GET['error_curso'])): ?>
                            <div class="alert alert-danger" role="alert">
                                Error al crear el curso.
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nombre_curso_fk" name="nombre_curso_fk" placeholder="Nombre del Curso" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Crear Curso</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario para añadir contratista -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h5>Crear Nuevo Contratista</h5></div>
                    <div class="card-body">
                        <?php if (isset($_GET['success_contratista'])): ?>
                            <div class="alert alert-success" role="alert">
                                Contratista creado correctamente.
                            </div>
                        <?php elseif (isset($_GET['error_contratista'])): ?>
                            <div class="alert alert-danger" role="alert">
                                Error al crear el contratista.
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nombre_contratista" name="nombre_contratista" placeholder="Nombre del Contratista" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Añadir Contratista</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>