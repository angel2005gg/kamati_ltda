<?php
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../modelo/Usuarios.php';

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
include 'incluirNavegacion.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .filtro-container {
            display: none;
            margin-top: 10px;
        }
        .filtro-container.visible {
            display: block;
        }
        .filtro-fecha {
            display: flex;
            gap: 10px;
        }
        
        .no-resultados {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            font-size: 1.2em;
        }
        .busqueda-container {
            position: relative;
        }
        #sugerencias {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }
        .sugerencia-item {
            padding: 8px;
            cursor: pointer;
        }
        .sugerencia-item:hover {
            background-color: #f0f0f0;
        }
        .tabla-container {
            margin-top: 20px;
        }
        tr.fila-vigente td { background-color:rgb(186, 255, 186) !important; } /* Verde claro */
        tr.fila-a-vencer td { background-color:rgb(255, 255, 181) !important; } /* Amarillo claro */
        tr.fila-vencido td { background-color:rgb(255, 189, 189) !important; } /* Rojo claro */
    </style>
</head>
<body>
    <br><br><br>
    <div class="container mt-4">
        <h2>Listado de Cursos</h2>
        <!-- Botón para quitar todos los filtros -->
        <div class="mb-3">
            <a href="ListaCursos.php" class="btn btn-secondary">Quitar Filtros</a>
        </div>
        <!-- Barra de búsqueda fija -->
    <!-- Reemplaza el input de búsqueda actual por este -->
<div class="row mb-3">
    <div class="col-md-4">
    <div class="busqueda-container">
    <input type="text" id="nombre_usuario" class="form-control" placeholder="Buscar usuario..." value="<?php echo htmlspecialchars($filtros['nombre_usuario'] ?? ''); ?>">
    <div id="sugerencias"></div>
</div>

    </div>
</div>

        <!-- Encabezados con iconos de filtro -->
        <div class="tabla-container" >
            <table class="table table-bordered" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>
                            Área 
                            <i class="fas fa-filter" onclick="toggleFiltro('area')"></i>
                            <div id="filtroArea" class="filtro-container">
                                <select class="form-select" name="area">
                                    <option value=""> Área</option>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?php echo htmlspecialchars($area['nombre_area']); ?>">
                                            <?php echo htmlspecialchars($area['nombre_area']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </th>
                        <th>
                            Fecha Inicio
                            <i class="fas fa-filter" onclick="toggleFiltro('fecha_inicio')"></i>
                            <div id="filtroFechaInicio" class="filtro-container">
                                <div class="filtro-fecha">
                                    <select class="form-select" name="año_inicio">
                                        <option value="">Año</option>
                                        <?php foreach ($años as $año): ?>
                                            <option value="<?php echo $año['año']; ?>">
                                                <?php echo $año['año']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select class="form-select" name="mes_inicio">
                                        <option value="">Mes</option>
                                        <?php foreach ($meses as $numero => $nombre): ?>
                                            <option value="<?php echo $numero; ?>">
                                                <?php echo $nombre; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </th>
                        <th>
                            Fecha Fin
                            <i class="fas fa-filter" onclick="toggleFiltro('fecha_fin')"></i>
                            <div id="filtroFechaFin" class="filtro-container">
                                <div class="filtro-fecha">
                                    <select class="form-select" name="año_fin">
                                        <option value="">Año</option>
                                        <?php foreach ($años as $año): ?>
                                            <option value="<?php echo $año['año']; ?>">
                                                <?php echo $año['año']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select class="form-select" name="mes_fin">
                                        <option value="">Mes</option>
                                        <?php foreach ($meses as $numero => $nombre): ?>
                                            <option value="<?php echo $numero; ?>">
                                                <?php echo $nombre; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </th>
                        <th>
                            Curso
                            <i class="fas fa-filter" onclick="toggleFiltro('curso')"></i>
                            <div id="filtroCurso" class="filtro-container">
                                <select class="form-select" name="nombre_curso">
                                    <option value=""> Curso</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo htmlspecialchars($curso['nombre_curso_fk']); ?>">
                                            <?php echo htmlspecialchars($curso['nombre_curso_fk']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </th>
                        <th>
                            Empresa
                            <i class="fas fa-filter" onclick="toggleFiltro('empresa')"></i>
                            <div id="filtroEmpresa" class="filtro-container">
                                <select class="form-select" name="empresa">
                                    <option value=""> Empresa</option>
                                    <?php foreach ($empresas as $empresa): ?>
                                        <option value="<?php echo htmlspecialchars($empresa['nombre_empresa']); ?>">
                                            <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </th>
                        <th>
                            Estado
                            <i class="fas fa-filter" onclick="toggleFiltro('estado')"></i>
                            <div id="filtroEstado" class="filtro-container">
                                <select class="form-select" name="estado">
                                    <option value="">Estado</option>
                                    <option value="Vigente">Vigente</option>
                                    <option value="A vencer">A vencer</option>
                                    <option value="Vencido">Vencido</option>
                                </select>
                            </div>
                        </th>
                        <th>Acciones</th>
                    </tr>
                    
                </thead>
                <tbody>
    <?php if (empty($cursosUsuarios)): ?>
    <tr>
        <td colspan="8" class="no-resultados">
            No se encontraron resultados para los filtros seleccionados.
        </td>
    </tr>
    <?php else: ?>
    <?php foreach ($cursosUsuarios as $cursoUsuario): ?>
    <?php
    $estado = calcularEstado($cursoUsuario['fecha_inicio'], $cursoUsuario['fecha_fin']);
    ?>
    <tr class="fila-<?php echo strtolower(str_replace(' ', '-', $estado['estado'])); ?>">
        <td><?php echo htmlspecialchars($cursoUsuario['nombre_usuario'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($cursoUsuario['area'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($cursoUsuario['fecha_inicio'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($cursoUsuario['fecha_fin'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($cursoUsuario['nombre_curso'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($cursoUsuario['empresa'] ?? 'N/A'); ?></td>
        <td class="<?php echo $estado['clase']; ?>"><?php echo $estado['estado']; ?></td>
        <td>
            <a href="EditarCursoUsuario.php?id=<?php echo $cursoUsuario['id_curso_usuario']; ?>" class="edit-icon">
                <img src="../img/boton-editar.png" alt="Editar" width="20" height="20">
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</tbody>


            </table>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/ListaCursos.js"></script>
</body>
</html>