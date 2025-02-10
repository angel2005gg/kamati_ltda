<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/styleKamaInitrApi.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../js/JavaScriptJs.js" defer></script>
    <title>DashBoard</title>
</head>

<body>

    <?php include 'navBarJefeAdminL.php' ?>
    <br><br><br><br>
    <div class="boton_excel_format">
        <h2>Exportar en excel todas las novedades</h2>
        <form action="../controlador/exportarPermisosExcel.php" method="post">
            <button type="submit" class="ingreso_submit" style=" width: 160px" ;>Exportar</button>
        </form>
    </div>
    <br><br><br><br>

    <div class="grafica_barras">
        <h2>Indicador de novedades</h2>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script src="../js/ScriptChart.js"></script>
    </div>

    <br><br><br><br>    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
</body>

</html>