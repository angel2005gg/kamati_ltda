<?php
require_once '../modelo/dao/MaquinariaDao.php';  // Suponiendo que el DAO está en este archivo

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $dao = new MaquinariaDao();
    
    $result = $dao->updateTablaMaquinaria($data);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actualización realizada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la tabla.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos no válidos recibidos.']);
}