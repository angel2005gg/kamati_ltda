<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../modelo/dao/PermisosDao.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleKamaInitrAp.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="../js/alertsKam.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Consulta solicitudes</title>

    <style>
        .custom-alert {
            width: 60%;
            transition: opacity 0.5s ease;
        }
    </style>
</head>

<body>

    <?php include 'navBarJefeAdminLin.php'; ?>


    <?php
    $permisos = new PermisosDao();
    $data = $permisos->consultarPermisos($_SESSION['idUser']);

    ?>
    <div class="container">
        <div class="container_table">
            <br><br><br><br><br>
            <h2>Listado de mis solicitudes</h2>
            <!-- Contenedor para las alertas -->
            <div id="alert-container" class="d-flex justify-content-center my-3"></div>

            <br>
            <div class="tabla_solicitudes">
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Fecha solicitud</th>
                            <th scope="col">Tipo permiso</th>
                            <th scope="col">Tiempo</th>
                            <th scope="col">Cantidad tiempo</th>
                            <th scope="col">Fecha incio</th>
                            <th scope="col">Fecha fin</th>
                            <th scope="col">Dias compensados</th>
                            <th scope="col">Cantidad dias compensados</th>
                            <th scope="col">Total horas</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Remuneraci��n</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['fecha_elaboracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_inicio_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_fin_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_horas_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['motivo_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['remuneracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_permiso']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>