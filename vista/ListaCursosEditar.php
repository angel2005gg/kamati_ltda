<?php
require_once '../controlador/ControladorCursoUsuario.php';
$controladorCursoUsuario = new ControladorCursoUsuario();
$cursosUsuarios = $controladorCursoUsuario->obtenerTodos();

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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #fff;
        }
        .estado-vigente { color: green; }
        .estado-a-vencer { color: orange; }
        .estado-vencido { color: red; }
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Listado de Cursos de Usuarios</h2>
        <!-- Añadir el campo de búsqueda -->
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar usuario...">
        </div>

        <!-- Tabla de resultados -->
        <table class="table table-bordered" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Área</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Curso</th>
                    <th>Empresa</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cursosUsuarios)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron resultados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cursosUsuarios as $cursoUsuario): ?>
                        <?php
                        $estado = calcularEstado($cursoUsuario['fecha_inicio'], $cursoUsuario['fecha_fin']);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cursoUsuario['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['area']); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['fecha_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['fecha_fin']); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['nombre_curso']); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['empresa']); ?></td>
                            <td class="<?php echo $estado['clase']; ?>"><?php echo $estado['estado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Añadir jQuery y el script de búsqueda -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tablaUsuarios tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>
</html>