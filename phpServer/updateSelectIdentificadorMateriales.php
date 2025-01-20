<?php
session_start();
require_once '../modelo/dao/MaterialesDao.php';

// Mostrar errores para depuración (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Asegurarse de que el contenido devuelto sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dao = new MaterialesDao();
        $ids = $dao->consultaidIdentificadorMateriales(); // Asegúrate de que esta función devuelva un array

        if (!empty($ids)) {
            echo json_encode($ids);
        } else {
            http_response_code(204); // No Content
            echo json_encode(["message" => "No se encontraron IDs"]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al consultar los datos del servidor.", "details" => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método de solicitud no válido."]);
}
?>