<?php
require_once '../modelo/dao/ActividadesDao.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Datos no válidos o no recibidos correctamente']);
    exit;
}

$idIdentificadorActividades = $data['idIdentificadorActividades'] ?? 0;
$factorActualizadoActividades = $data['factorActualizadoActividades'] ?? '';
$valorActualizadoActividades = $data['valorActualizadoActividades'] ?? '';

// Procesa la actualización como se requiera en tu DAO
$dao = new ActividadesDao();
$success = $dao->updateFactoresIndependientesIdentificadorActividades($idIdentificadorActividades, $factorActualizadoActividades, $valorActualizadoActividades);

echo json_encode([
    'status' => $success ? 'success' : 'error',
    'message' => $success ? 'Actualización exitosa' : 'Error al actualizar'
]);
?>