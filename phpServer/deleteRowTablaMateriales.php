<?php
require_once '../modelo/dao/MaterialesDao.php'; // Ajusta la ruta a tu archivo DAO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['id_TablaMateriales'], $data['id_Identificador'])) {
        $dao = new MaterialesDao(); // Asegúrate de que coincida con tu clase
        $result = $dao->delFilaTablaMateriales($data);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Fila eliminada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la fila.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>