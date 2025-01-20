<?php
require_once '../modelo/dao/FactorIndependienteMaquinariaDao.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_factoresIndependientesMaquinaria']) && isset($data['factors'])) {
    $id = $data['id_factoresIndependientesMaquinaria'];
    $factors = $data['factors'];

    $factorDao = new FactorIndependienteMaquinariaDao();
    $result = $factorDao->registrarFactoresIndependientesMaquinaria($id, $factors);

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