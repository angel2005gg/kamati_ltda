<?php
require_once '../modelo/dao/FactorIndependienteDao.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_factoresIndependientes']) && isset($data['factors'])) {
    $id = $data['id_factoresIndependientes'];
    $factors = $data['factors'];

    $factorDao = new FactorIndependienteDao();
    $result = $factorDao->registrarFactoresIndependientesMateriales($id, $factors);

    if (!empty($result['ids'])) {
        echo json_encode([
            'success' => true,
            'results' => $result['ids'],
            'factorNames' => $result['names']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudieron insertar todos los factores']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud']);
}
?>