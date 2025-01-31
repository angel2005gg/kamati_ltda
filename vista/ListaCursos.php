<?php
require_once '../controlador/ControladorCursoUsuario.php';

// Crear instancia del controlador y obtener los datos
$controlador = new ControladorCursoUsuario();
$cursosUsuarios = $controlador->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos de Usuarios</title>
    <!-- Agregar estilos CSS si lo deseas -->
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Listado de Cursos de Usuarios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Curso Usuario</th>
                    <th>Usuario</th>
                    <th>Curso</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursosUsuarios as $cursoUsuario): ?>
                    <tr>
                        <td><?php echo $cursoUsuario['id_curso_usuario']; ?></td>
                        <td><?php echo $cursoUsuario['nombre_usuario']; ?></td>
                        <td><?php echo $cursoUsuario['nombre_curso_fk']; ?></td>
                        <td><?php echo $cursoUsuario['fecha_inicio']; ?></td>
                        <td><?php echo $cursoUsuario['fecha_fin']; ?></td>
                        <td><?php echo $cursoUsuario['estado']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>