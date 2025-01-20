<?php
require_once '../modelo/dao/MaterialesDao.php';

// Establece los encabezados necesarios para recibir datos JSON
header('Content-Type: application/json');

// Lee y decodifica el cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Verifica si hubo un error al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al decodificar JSON: ' . json_last_error_msg()
    ]);
    exit;
}

// Verifica que los datos se hayan recibido correctamente
if (!empty($data)) {
    $dao = new MaterialesDao();

    // Llama al método para guardar los materiales y recibe los IDs insertados
    $insertedIds = $dao->guardarMateriales($data);
    
    if ($insertedIds) {
        echo json_encode([
            'success' => true,
            'insertedIds' => $insertedIds
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar los materiales.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibieron datos válidos.'
    ]);
}
?>