<?php
require_once '../modelo/dao/MaquinariaDao.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Datos no válidos o no recibidos correctamente']);
    exit;
}

$idIdentificadorMaquinaria = $data['idIdentificadorMaquinaria'] ?? 0;
$factorActualizado = $data['factorActualizado'] ?? '';
$valorActualizado = $data['valorActualizado'] ?? '';

// Procesa la actualización como se requiera en tu DAO
$dao = new MaquinariaDao();
$success = $dao->updateFactoresIndependientesIdentificadorMaquinaria($idIdentificadorMaquinaria, $factorActualizado, $valorActualizado);

echo json_encode([
    'status' => $success ? 'success' : 'error',
    'message' => $success ? 'Actualización exitosa' : 'Error al actualizar'
]);
?>