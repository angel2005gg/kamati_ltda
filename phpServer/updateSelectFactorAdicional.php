<?php
// Archivo controlador: updateSelectCotizaciones.php

header('Content-Type: application/json');

// Incluir la clase para conectar con la base de datos y la clase DAO

require_once('../modelo/dao/FactoresAdicionalesDao.php');

$factoresAdicionales = new FactoresAdicionalesDao();

// Manejar solicitudes POST (actualizar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos enviados desde JavaScript (JSON)
    $datas = json_decode(file_get_contents('php://input'), true);

    // Depuración: Verificar los datos recibidos
    var_dump($datas);

    // Verificar si se enviaron los campos necesarios
    if (isset($datas['idFactor']) && isset($datas['valorFactor'])) {
        $idFactor = $datas['idFactor'];  // Asegúrate de que esto coincida con lo que envías
        $valorFactor = $datas['valorFactor'];  // Asegúrate de que esto coincida con lo que envías

        // Llamar a la función del DAO para actualizar el factor de la cotización
        $resultado = $factoresAdicionales->actualizarFactoresAdicionales($idFactor, $valorFactor);

        // Devolver la respuesta al cliente
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Factor actualizado correctamente']);
        } else {
            // Si hay un error, registra el error aquí (opcional)
            // error_log('Error al actualizar el factor: ' . $factoresAdicionales->getLastError());
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el factor']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    // Manejar solicitudes GET (obtener datos)
    $datosAdicionales = $factoresAdicionales->obtenerDatosFactoresAd();

    if ($datosAdicionales) {
        // Devolver los datos al cliente en formato JSON
        echo json_encode(['success' => true, 'dom' => $datosAdicionales]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
    }
}
