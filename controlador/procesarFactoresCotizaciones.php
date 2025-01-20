<?php

header('Content-Type: application/json');

require_once '../modelo/FactoresCotizaciones.php';
require_once '../modelo/dao/FactoresCotizacionesDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Asegurarse de que los datos estén disponibles
    if (isset($data['id_cotizacion']) && isset($data['idFactor']) && isset($data['valorFactor'])) {

        // Crear un objeto comercial con los datos recibidos
        $factoresCot = new FactoresCotizaciones();
        $factoresCot->setIdCotizacionComercialFKs($data['id_cotizacion']);
        $factoresCot->setValorFactor($data['valorFactor']);
        $factoresCot->setId_Factores($data['idFactor']); // ID del factor

        // Llamar al método de actualización
        $tuClase = new FactoresCotizacionesDao();
        $result = $tuClase->updateFactoresCotizacion($factoresCot);

        // Responder con éxito o error según el resultado
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar el factor']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>