<?php
require_once '../controlador/ControladorCursoUsuario.php';

$controladorCursoUsuario = new ControladorCursoUsuario();

// Obtener opciones para los diferentes filtros
$areas = $controladorCursoUsuario->obtenerTodasLasAreas();
$años = $controladorCursoUsuario->obtenerAñosDisponibles();
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$cursos = $controladorCursoUsuario->obtenerCursosDisponibles();
$empresas = $controladorCursoUsuario->obtenerEmpresasDisponibles();

// Obtener los filtros del formulario
$filtros = [
    'nombre_usuario' => $_GET['nombre_usuario'] ?? '',
    'area' => $_GET['area'] ?? '',
    'año_inicio' => $_GET['año_inicio'] ?? '',
    'mes_inicio' => $_GET['mes_inicio'] ?? '',
    'año_fin' => $_GET['año_fin'] ?? '',
    'mes_fin' => $_GET['mes_fin'] ?? '',
    'nombre_curso' => $_GET['nombre_curso'] ?? '',
    'empresa' => $_GET['empresa'] ?? '',
    'estado' => $_GET['estado'] ?? ''
];

// Obtener los cursos de usuarios aplicando los filtros
$cursosUsuarios = $controladorCursoUsuario->obtenerTodosFiltrados($filtros);

function calcularEstado($fecha_inicio, $fecha_fin) {
    $fecha_actual = new DateTime();
    $fecha_inicio_dt = new DateTime($fecha_inicio);
    $fecha_fin_dt = new DateTime($fecha_fin);
    $intervalo = $fecha_actual->diff($fecha_fin_dt)->days;

    if ($fecha_actual > $fecha_fin_dt) {
        return ['estado' => 'Vencido', 'clase' => 'estado-vencido'];
    } elseif ($intervalo <= 20) {
        return ['estado' => 'A vencer', 'clase' => 'estado-a-vencer'];
    } else {
        return ['estado' => 'Vigente', 'clase' => 'estado-vigente'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .estado-vigente {
            color: green;
        }
        .estado-a-vencer {
            color: orange;
        }
        .estado-vencido {
            color: red;
        }
        .no-resultados {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Listado de Cursos de Usuarios</h2>
        
        <!-- Formulario de Filtros -->
        <form method="GET" action="ListaCursos.php" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre de Usuario" value="<?php echo htmlspecialchars($filtros['nombre_usuario']); ?>">
                </div>
                <!-- Contenedor del filtro de área -->
<div id="filtroArea" class="col-md-3" style="display: none;">
    <select class="form-select" name="area">
        <option value="">Seleccionar Área</option>
        <?php foreach ($areas as $area): ?>
            <option value="<?php echo htmlspecialchars($area['nombre_area']); ?>" 
                <?php echo $filtros['area'] == $area['nombre_area'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($area['nombre_area']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
                <div class="col-md-3">
                    <select class="form-select" name="año_inicio">
                        <option value="">Año Inicio</option>
                        <?php foreach ($años as $año): ?>
                            <option value="<?php echo $año['año']; ?>" 
                                <?php echo $filtros['año_inicio'] == $año['año'] ? 'selected' : ''; ?>>
                                <?php echo $año['año']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="mes_inicio">
                        <option value="">Mes Inicio</option>
                        <?php foreach ($meses as $numero => $nombre): ?>
                            <option value="<?php echo $numero; ?>" 
                                <?php echo $filtros['mes_inicio'] == $numero ? 'selected' : ''; ?>>
                                <?php echo $nombre; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-select" name="año_fin">
                        <option value="">Año Fin</option>
                        <?php foreach ($años as $año): ?>
                            <option value="<?php echo $año['año']; ?>" 
                                <?php echo $filtros['año_fin'] == $año['año'] ? 'selected' : ''; ?>>
                                <?php echo $año['año']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-select" name="mes_fin">
                        <option value="">Mes Fin</option>
                        <?php foreach ($meses as $numero => $nombre): ?>
                            <option value="<?php echo $numero; ?>" 
                                <?php echo $filtros['mes_fin'] == $numero ? 'selected' : ''; ?>>
                                <?php echo $nombre; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-select" name="nombre_curso">
                        <option value="">Seleccionar Curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?php echo htmlspecialchars($curso['nombre_curso_fk']); ?>" 
                                <?php echo $filtros['nombre_curso'] == $curso['nombre_curso_fk'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($curso['nombre_curso_fk']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-select" name="empresa">
                        <option value="">Seleccionar Empresa</option>
                        <?php foreach ($empresas as $empresa): ?>
                            <option value="<?php echo htmlspecialchars($empresa['nombre_empresa']); ?>" 
                                <?php echo $filtros['empresa'] == $empresa['nombre_empresa'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-select" name="estado">
                        <option value="">Estado</option>
                        <option value="Vigente" <?php echo $filtros['estado'] == 'Vigente' ? 'selected' : ''; ?>>Vigente</option>
                        <option value="A vencer" <?php echo $filtros['estado'] == 'A vencer' ? 'selected' : ''; ?>>A vencer</option>
                        <option value="Vencido" <?php echo $filtros['estado'] == 'Vencido' ? 'selected' : ''; ?>>Vencido</option>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <?php if (empty($cursosUsuarios)): ?>
            <div class="no-resultados">
                No se encontraron resultados para los filtros seleccionados.
            </div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Área <i class="fas fa-filter" onclick="toggleFiltroArea()"></i></th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Curso</th>
                        <th>Empresa</th>                    
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursosUsuarios as $cursoUsuario): ?>
                        <tr>
                            <td><?php echo isset($cursoUsuario['nombre_usuario']) ? htmlspecialchars($cursoUsuario['nombre_usuario']) : 'N/A'; ?></td>
                            <td><?php echo isset($cursoUsuario['area']) ? htmlspecialchars($cursoUsuario['area']) : 'N/A'; ?></td>
                            <td><?php echo isset($cursoUsuario['fecha_inicio']) ? htmlspecialchars($cursoUsuario['fecha_inicio']) : 'N/A'; ?></td>
                            <td><?php echo isset($cursoUsuario['fecha_fin']) ? htmlspecialchars($cursoUsuario['fecha_fin']) : 'N/A'; ?></td>
                            <td><?php echo isset($cursoUsuario['nombre_curso']) ? htmlspecialchars($cursoUsuario['nombre_curso']) : 'N/A'; ?></td>
                            <td><?php echo isset($cursoUsuario['empresa']) ? htmlspecialchars($cursoUsuario['empresa']) : 'N/A'; ?></td>
                            <?php
                            if (isset($cursoUsuario['fecha_inicio']) && isset($cursoUsuario['fecha_fin'])) {
                                $estado = calcularEstado($cursoUsuario['fecha_inicio'], $cursoUsuario['fecha_fin']);
                            } else {
                                $estado = ['estado' => 'N/A', 'clase' => ''];
                            }
                            ?>
                            <td class="<?php echo $estado['clase']; ?>"><?php echo $estado['estado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fecha_inicio').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#fecha_fin').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        function toggleFiltroArea() {
        const filtroArea = document.getElementById('filtroArea');
       if (filtroArea.style.display === 'none' || filtroArea.style.display === '') {
        filtroArea.style.display = 'block';
       } else {
        filtroArea.style.display = 'none';
       }
       }
    </script>
</body>
</html>