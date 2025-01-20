<?php
// Archivo controlador: updateSelectCotizaciones.php

header('Content-Type: application/json');

// Incluir la clase para conectar con la base de datos y la clase DAO
require_once('../configuracion/ConexionBD.php');
require_once('../modelo/dao/CargoCotizacionesDao.php');

$cargosDaoCot = new CargoCotizacionesDao();

// Manejar solicitudes POST (actualizar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos enviados desde JavaScript (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se enviaron los campos necesarios
    if (isset($data['idCargosCot']) && isset($data['valorCargosCot'])) {
        $idCargosCot = $data['idCargosCot'];
        $valorCargosCot = $data['valorCargosCot'];

        // Llamar a la función del DAO para actualizar el factor de la cotización
        $resultado = $cargosDaoCot->actualizarCargos($idCargosCot, $valorCargosCot);

        // Devolver la respuesta al cliente
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Factor actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el factor']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    $datosCotizacion = $cargosDaoCot->obtenerDatosCargos();
    if ($datosCotizacion) {
        echo json_encode(['success' => true, 'dom' => $datosCotizacion]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
    }
}
