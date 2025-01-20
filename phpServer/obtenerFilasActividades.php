<?php
session_start();

require_once '../modelo/dao/ActividadesDao.php';

header('Content-Type: application/json');

try {
    $dao = new ActividadesDao();
    $data = $dao->getDataActividades();
    
    // Verificar si los datos están vacíos
    if (empty($data)) {
        echo json_encode(['error' => 'No se encontraron actividades.']);
    } else {
        echo json_encode($data); // Asegúrate de convertir el array a JSON
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>