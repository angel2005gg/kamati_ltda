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
        .edit-icon {
            cursor: pointer;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Listado de Cursos de Usuarios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Area</th>
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
    </div>
</body>
</html>