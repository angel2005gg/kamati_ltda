<?php
// Archivo controlador: updateSelectCotizaciones.php

header('Content-Type: application/json');

// Incluir la clase para conectar con la base de datos y la clase DAO
require_once('../configuracion/ConexionBD.php');
require_once('../modelo/dao/ComecialProjectsDao.php');

$comercialProjectsDao = new ComercialProjectsDao();

// Guardar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Obtener datos del JSON
    $fechaActual = $data['fechaActual'] ?? '';
    $nombreCotizacion = $data['nombreCotizacion'] ?? '';
    $codigoCotizacion = $data['codigoCotizacion'] ?? '';
    $nombreCliente = $data['nombreCliente'] ?? '';
    $dolar = $data['dolar'] ?? '';
    $euro = $data['euro'] ?? '';
    
    // Llamar a la función del DAO para actualizar los datos
    $respuesta = $comercialProjectsDao->actualizarDatosCotizacion($fechaActual, $nombreCotizacion, $codigoCotizacion, $nombreCliente, $dolar, $euro);
    echo json_encode($respuesta);
    
} else {
    // Llamar a la función del DAO para obtener los datos de la cotización
    $datosCotizacion = $comercialProjectsDao->obtenerDatosCotizacion();

    if ($datosCotizacion) {
        echo json_encode(['success' => true, 'dom' => $datosCotizacion]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
    }
}
?>