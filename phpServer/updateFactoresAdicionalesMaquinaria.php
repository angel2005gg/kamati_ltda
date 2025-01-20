<?php
session_start();
require_once '../modelo/dao/MaquinariaDao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Crear instancia del DAO
        $dao = new MaquinariaDao();
        
        // Consultar los factores independientes de materiales
        $datos = $dao->getFactoresAdicionalesMaquinaria();

        if (!empty($datos)) {
            // Enviar los datos como JSON al cliente
            echo json_encode($datos);
        } else {
            // Si no hay datos, enviar un código 204 y un mensaje
            http_response_code(204);
            echo json_encode(["message" => "No se encontraron datos"]);
        }
    } catch (Exception $e) {
        // Manejar errores con un código 500
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    // Si no es una solicitud POST, devolver un código 405
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>