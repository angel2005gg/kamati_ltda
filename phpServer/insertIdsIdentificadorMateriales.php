<?php
session_start();
require_once '../modelo/dao/MaterialesDao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dao = new MaterialesDao();
        $datos = $dao->consultarDatosIdentificadorMateriales();

        if (!empty($datos)) {
            echo json_encode($datos); // Enviamos los datos como JSON
        } else {
            http_response_code(204); // No Content
            echo json_encode(["message" => "No se encontraron datos"]);
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["error" => "Método no permitido"]);
}
?>