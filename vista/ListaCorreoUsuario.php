<?php
require_once '../configuracion/ConexionBD.php';

$conexion = new ConexionBD();
$conn = $conexion->conectarBD();

// Obtener los últimos 30 correos enviados
$sql = "SELECT destinatario, asunto, mensaje, fecha_envio FROM historial_correos ORDER BY fecha_envio DESC LIMIT 30";
$result = $conn->query($sql);
$correos = $result->fetch_all(MYSQLI_ASSOC);

$conexion->desconectarBD();
include 'incluirNavegacion.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Correos Enviados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
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
            color: #0d6efd;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 3px solid #0d6efd;
            color: #0d6efd;
            font-weight: bold;
        }
        .container {
            margin-top: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
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
            <li class="nav-item"><a class="nav-link" href="ListaEmpresaAsociadas.php">Lista de Frecuencias</a></li>
            <li class="nav-item"><a class="nav-link active" href="ListaCorreoUsuario.php">Historial</a></li>
        </ul>

        <h2 class="text-center mb-4">Historial de Correos Enviados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Destinatario</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha de Envío</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($correos as $correo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($correo['destinatario']); ?></td>
                        <td><?php echo htmlspecialchars($correo['asunto']); ?></td>
                        <td><?php echo htmlspecialchars($correo['mensaje']); ?></td>
                        <td><?php echo htmlspecialchars($correo['fecha_envio']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>