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
    <h1>Listado de Cursos de Usuarios</h1>
    
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Área</th>
                <th>Fecha Final</th>
                <th>Curso</th>
                <th>Empresa</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursosUsuarios as $cursoUsuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($cursoUsuario['usuario']); ?></td>
                <td><?php echo htmlspecialchars($cursoUsuario['area']); ?></td>
                <td><?php echo htmlspecialchars($cursoUsuario['fecha_vencimiento']); ?></td>
                <td><?php echo htmlspecialchars($cursoUsuario['curso']); ?></td>
                <td><?php echo htmlspecialchars($cursoUsuario['empresa']); ?></td>
                <td><?php echo htmlspecialchars($cursoUsuario['estado']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>