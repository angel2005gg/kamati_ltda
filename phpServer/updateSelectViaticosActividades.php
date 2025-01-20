<?php
require_once '../modelo/dao/ActividadesDao.php';

// Establecer encabezados para manejar JSON y permitir CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

try {
    session_start();

    // Obtener los datos enviados desde JavaScript
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['ids'])) {
        throw new Exception('No se recibieron los ids correctamente.');
    }

    $ids = $data['ids']; // Los ids enviados desde JavaScript

    // Crear una instancia de la clase DAO y obtener los datos basados en los ids
    $dao = new ActividadesDao();

    // Puedes pasar los ids directamente a la función consultaidIdentificadorActividades si es necesario.
    $datos = $dao->getDataViaticosActividadesIndependiente();

    // Validar si los datos están disponibles
    if (empty($datos)) {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'No se encontraron datos.'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => $datos
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error: ' . $e->getMessage()
    ]);
    http_response_code(500); // Establecer el código de estado HTTP a 500
}
?>