<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
require_once '../controlador/ControladorCotizaciones.php';
$controlador = new ControladorComercial();
$cotizaciones = $controlador->controladorSelectProjects(); // Obtener las cotizaciones
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../css/estyleComercialDashboardCotizacion.css">
    <title>Dashboard Cotizaciones</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../jsServerFiltroCotizacion/ajaxFiltroCotizacion.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include 'navBarJefeComercial.php'; ?>
    <br><br>
    <div class="container mt-5 fixed_alerts">
        <div id="alertContainer"></div>
    </div>
    <br><br>
    <form method="POST" action="../controlador/ServletCotizacionesJefes.php">
        <div class="container">
            <!-- Sidebar (Menu) -->
            <div class="sidebar">
                <h4 class="text-center">-</h4>
                <h4 class="text-center">-</h4>
                <h4 class="text-center">Acciones</h4>
                <input type="submit" name="accion" class="create-table-btn" value="crearcotizacion">
                <input type="hidden" name="menu" value="crearCotizacion">
            </div>
        </div>
    </form>
    <!-- Main Content Area -->
   
    <div class="main-content">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                
                <h2>Dashboard Comercial</h2>
            </div>
            <div class="col-auto border_nuew_unieque">
                <input type="hidden">
            </div>
            <div class="col-auto">
                <input type="text" id="consulta_filtro_cotizacion_codigo_uniqueId" class="form-control" aria-describedby="passwordHelpInline" placeholder="Filtro código cotización EJ: 0000000000">
            </div>
            <div class="col-auto">
                <span  class="form-text">
                    Busca las cotizaciones por su código.
                </span>
            </div>
        </div>
    
        <p>Bienvenid@, aquí podrás crear tus cotizaciones.</p>
        <!-- Your table or other content goes here -->
        <div id="tableContainer">
            <?php if (!empty($cotizaciones)): ?>
                <div class="cotizacion-container">
                    <?php foreach ($cotizaciones as $cotizacion): ?>
                        <div class="cotizacion-card" onclick="setCotizacionID(<?php echo $cotizacion['id_Cotizacion']; ?>)">
                            <div class="cotizacion-header">
                                <h4>ID Cotización: <?php echo $cotizacion['id_Cotizacion']; ?></h4>
                            </div>
                            <div class="cotizacion-body">
                                <input type="hidden" name="idOcultoConsulta" value="<?php echo $cotizacion['id_Cotizacion']; ?>">
                                <p><strong>Nombre Cotización:</strong> <?php echo $cotizacion['nombre_cotizacion']; ?></p>
                                <p><strong>Código Cotización:</strong> <?php echo $cotizacion['codigo_cotizacion']; ?></p>
                                <p><strong>Fecha Creación:</strong> <?php echo $cotizacion['fecha_creacion']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay cotizaciones disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript para manejar el clic en la tarjeta -->
    <script>
        function setCotizacionID(idCotizacion) {
            // Crear un formulario oculto para enviar el ID
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'guardarCotizacionIDJefe.php'; // Archivo PHP que maneja la sesión

            // Crear un campo oculto con el ID de la cotización
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'id_cotizacion';
            hiddenInput.value = idCotizacion;
            form.appendChild(hiddenInput);

            // Agregar el formulario al body y enviarlo
            document.body.appendChild(form);
            form.submit(); // Enviar el formulario
        }
    </script>
</body>

</html>