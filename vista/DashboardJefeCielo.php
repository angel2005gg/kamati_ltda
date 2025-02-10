<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <title>JEFE_INMEDIATO</title>
</head>
<body>
    <?php require_once 'navBarJefeCielo.php'; ?>
    <div class="container mt-5 fixed_alerts">
        <div id="alertContainer"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/alerts.js"></script>
</body>
</html>