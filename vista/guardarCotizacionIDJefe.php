<?php
session_start();  // Iniciar la sesión

if (isset($_POST['id_cotizacion'])) {
  // Guardar el ID Cotización en la sesión
  $_SESSION['id_cotizacion'] = $_POST['id_cotizacion'];
}

// Redirigir a la página de cotizaciones
echo "<script>
                        window.open('../vista/cotizacionesJefesComercial.php', '_blank');
                        window.location.href = '../vista/CreacionCotizacionJefe.php';
                      </script>";
exit;
