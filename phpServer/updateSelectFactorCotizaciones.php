<?php
// Archivo controlador: updateSelectCotizaciones.php

header('Content-Type: application/json');

// Incluir las clases necesarias para la conexión a la base de datos y el DAO
require_once('../configuracion/ConexionBD.php');
require_once('../modelo/dao/FactoresCotizacionesDao.php');

// Crear una instancia del DAO
$factoresCotizacionDao = new FactoresCotizacionesDao();

// Verificar si la solicitud es POST (para actualizar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos enviados desde JavaScript (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si los datos necesarios están presentes
    if (isset($data['idFactor']) && isset($data['valorFactor'])) {
        $idFactor = $data['idFactor'];
        $valorFactor = $data['valorFactor'];

        // Llamar al método del DAO para actualizar el factor de la cotización
        $resultado = $factoresCotizacionDao->actualizarFactoresCotizacion($idFactor, $valorFactor);

        // Devolver una respuesta JSON al cliente
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Factor actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el factor']);
        }
    } else {
        // Si faltan datos, devolver un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    // Si la solicitud no es POST, se asume que es GET (para obtener datos)

    $datosCotizacion = $factoresCotizacionDao->obtenerDatosFactores();

    // Devolver los datos al cliente en formato JSON
    if ($datosCotizacion) {
        echo json_encode(['success' => true, 'dom' => $datosCotizacion]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
    }
}
