<?php
require_once '../controlador/ControladorCursoEmpresa.php';
$output = ob_get_clean();
if (!empty($output)) {
    error_log('Output no deseado: ' . $output);
}

$controladorCursoEmpresa = new ControladorCursoEmpresa();
$cursosEmpresas = $controladorCursoEmpresa->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empresas Asociadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .estado-vigente { color: green; }
        .estado-a-vencer { color: orange; }
        .estado-vencido { color: red; }
        .no-resultados {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            font-size: 1.2em;
        }
        .tabla-container {
            margin-top: 20px;
        }
        .acciones-col {
            width: 100px; /* Ajustar el ancho de la columna de acciones */
        }
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Mantener la sección de navegación -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link" href="CursosGestion.php">Crear Nuevo</a></li>
            <li class="nav-item"><a class="nav-link" href="CursosGestionEliminar.php">Eliminar</a></li>
            <li class="nav-item"><a class="nav-link" href="CursosGestionFrecuencia.php">Crear Frecuencia</a></li>
            <li class="nav-item"><a class="nav-link active" href="ListaEmpresaAsociadas.php">Lista de Cursos</a></li>
        </ul>

        <!-- Tabla de resultados -->
        <div class="tabla-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Curso</th>
                        <th>Duración (meses)</th>
                        <th class="acciones-col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cursosEmpresas)): ?>
                        <tr>
                            <td colspan="4" class="no-resultados">No se encontraron resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cursosEmpresas as $cursoEmpresa): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cursoEmpresa['nombre_empresa']); ?></td>
                                <td><?php echo htmlspecialchars($cursoEmpresa['nombre_curso_fk']); ?></td>
                                <td><?php echo htmlspecialchars($cursoEmpresa['duracion']); ?> meses</td>
                                <td class="acciones-col">
                                    <a href="#" class="delete-icon" onclick="confirmarEliminacion(<?php echo $cursoEmpresa['id_curso_empresa']; ?>)">
                                        <img src="../img/eliminar.png" alt="Eliminar" width="20" height="20">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar una solicitud AJAX para intentar eliminar la asociación
                fetch('EliminarCursoEmpresa.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'La asociación ha sido eliminada correctamente.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
            icon: 'warning', 
            title: 'Advertencia', 
            text: data.error
        });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al intentar eliminar la asociación.'
                        });
                    });
            }
        });
    }

    <?php if (isset($_GET['mensaje'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($_GET['mensaje'] == 'eliminado'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'La asociación ha sido eliminada correctamente.'
            });
        <?php elseif ($_GET['mensaje'] == 'error'): ?>
            Swal.fire({
    icon: 'warning',
    title: 'Advertencia',
    text: 'No se pudo eliminar la asociación porque está siendo referenciada por otros registros.'
});
        <?php elseif ($_GET['mensaje'] == 'sin_id'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'ID no proporcionado.'
            });
        <?php endif; ?>
    });
    <?php endif; ?>
    </script>
</body>
</html>