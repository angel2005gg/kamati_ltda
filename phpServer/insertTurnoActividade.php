<?php
require_once '../modelo/dao/ActividadesDao.php';

header('Content-Type: application/json');

// Verificar que el método de solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener los datos JSON enviados en la solicitud
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

// Verificar que los datos sean válidos
if (!$data || !is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos o estructura incorrecta']);
    exit;
}

$dao = new ActividadesDao();

try {
    // Insertar los datos utilizando el método insertTurnoActividades de la clase DAO
    $resultado = $dao->insertTurnoActividades($data);

    // Verificar el resultado de la inserción
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Datos insertados correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudieron insertar los datos']);
    }
} catch (Exception $e) {
    // Capturar cualquier error y devolver una respuesta de error
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}