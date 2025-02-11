<?php
session_start();
require_once '../modelo/CursoUsuario.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

// Obtener el ID del usuario de la sesión
$id_usuario = $_SESSION['user']['id'];

// Crear una instancia del modelo CursoUsuario
$cursoUsuarioModel = new CursoUsuario();

// Obtener los cursos asociados al ID del usuario
$cursosUsuarios = $cursoUsuarioModel->obtenerCursosPorUsuario($id_usuario);

// Definir la función calcularEstado
function calcularEstado($fecha_inicio, $fecha_fin) {
    $fecha_actual = new DateTime();
    $fecha_inicio = new DateTime($fecha_inicio);
    $fecha_fin = new DateTime($fecha_fin);

    if ($fecha_fin >= $fecha_actual) {
        return ['estado' => 'Vigente', 'clase' => 'estado-vigente'];
    } elseif ($fecha_fin < $fecha_actual && $fecha_fin >= $fecha_actual->modify('-30 days')) {
        return ['estado' => 'A vencer', 'clase' => 'estado-a-vencer'];
    } else {
        return ['estado' => 'Vencido', 'clase' => 'estado-vencido'];
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
<?php include 'navBar.php'; ?>
<br><br>
<div class="container mt-4">
    <br>
    <h2>Tus cursos actuales</h2>
    <br>
   

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