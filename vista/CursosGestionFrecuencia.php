<?php
require_once '../controlador/ControladorEmpresaCliente.php';
require_once '../controlador/ControladorCurso.php';

$controladorEmpresa = new ControladorEmpresaCliente();
$empresas = $controladorEmpresa->obtenerTodos();

$controladorCurso = new ControladorCurso();
$cursos = $controladorCurso->obtenerTodos();

session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos - Frecuencia</title>
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
    <div id="alertMessage" class="alert alert-floating" role="alert"></div>

    <div class="container mt-4">
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="CursosGestion.php">Crear Nuevo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="CursosGestionEliminar.php">Eliminar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="CursosGestionFrecuencia.php">Crear Frecuencia</a>
            </li>
        </ul>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Crear Frecuencia de Curso</h5>
            </div>
            <form method="POST" action="">
                <div class="card-body">
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
                        <label class="form-label">Duración del Curso</label>
                        <select class="form-select" name="duracion" required>
                            <option value="6">6 meses</option>
                            <option value="12">1 Año</option>
                            <option value="12">2 Año</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAlert(message, type) {
            const alertMessage = document.getElementById('alertMessage');
            alertMessage.className = `alert alert-${type}`;
            alertMessage.textContent = message;
            alertMessage.style.display = 'block';
            setTimeout(() => {
                alertMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>