<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo con la clase DAO
require_once '../modelo/dao/ActividadesDao.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Leer los datos recibidos en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si hubo un error al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    // Si hay un error en la decodificación, devolver un mensaje con el error
    echo json_encode(['success' => false, 'message' => 'Error JSON: ' . json_last_error_msg()]);
    exit;  // Detener la ejecución aquí para evitar más respuestas
}

// Verificar que los datos no estén vacíos
if (!empty($data)) {
    // Crear una instancia del DAO
    $dao = new ActividadesDao();
    
    // Llamar al método para guardar los materiales
    $insertedIds = $dao->insertTurnoFilaNuevo($data);

    // Verificar si la inserción fue exitosa
    if ($insertedIds) {
        // Si la inserción fue exitosa, devolver la respuesta con el ID insertado
        echo json_encode(['success' => true, 'insertedIds' => $insertedIds]);
    } else {
        // Si hubo un error al guardar, devolver un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Error al guardar los materiales.']);
    }
} else {
    // Si no se recibieron datos válidos, devolver un mensaje de error
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos válidos.']);
}
?>