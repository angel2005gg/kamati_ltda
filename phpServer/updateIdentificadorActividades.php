<?php

require_once '../modelo/dao/ActividadesDao.php'; // Asegúrate de cargar el DAO correctamente

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}else{

    $data = json_decode(file_get_contents("php://input"), true);
    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Datos no válidos o no recibidos correctamente']);
        exit;
    }
    
    // Obtener los valores del array $data
    $nombreTablaActividades = $data['nombreTablaActividades'] ?? '';
    $checkFactoresActividades = $data['checkFactoresActividades'] ?? '';
    $totalKamati = isset($data['totalKamatiAc']) ? (float)$data['totalKamatiAc'] : 0;
    $totalCliente = isset($data['totalClienteAc']) ? (float)$data['totalClienteAc'] : 0;
    $idIdentificadorActividades = $data['idIdentificadorActividades'] ?? 0;
    
    // Verificar si los datos de los totales son válidos
    if ($totalKamati === 0 || $totalCliente === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Los valores de totalKamati o totalCliente son incorrectos']);
        exit;
    }
    
    // Instanciar el DAO para actualizar los datos
    $dao = new ActividadesDao();
    $success = $dao->updateIdentificadorActidades(
        $nombreTablaActividades,
        $checkFactoresActividades,
        $totalKamati,
        $totalCliente,
        $idIdentificadorActividades
    );
    
    // Enviar la respuesta según el resultado
    echo json_encode([
        'status' => $success ? 'success' : 'error',
        'message' => $success ? 'Actualización exitosa' : 'Error al actualizar'
    ]);
}


?>