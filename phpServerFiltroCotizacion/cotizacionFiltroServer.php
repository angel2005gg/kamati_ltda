<?php
require_once '../modelo/dao/FiltroCotizacionDao.php';

$daoCotizacion = new FiltroDao();

$codigoCotizacion = isset($_POST['codigo_cotizacion']) ? trim($_POST['codigo_cotizacion']) : null;
$cotizaciones = $daoCotizacion->consultaCodigoCotizacionFiltro($codigoCotizacion);

header('Content-Type: application/json');
echo json_encode($cotizaciones);
?>