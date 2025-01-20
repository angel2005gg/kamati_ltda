<?php
// Incluir el archivo que contiene la clase con el método de actualización
include_once '../modelo/dao/ActividadesDao.php';

// Establecer el encabezado para permitir el acceso
header('Content-Type: application/json');

// Leer los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['valorCargo']) && isset($data['idCargo']) && isset($data['idIdentificador'])) {
    // Crear una instancia de la clase DAO
    $daoViaticos = new ActividadesDao();
    
    // Llamar al método para actualizar los viáticos
    $resultado = $daoViaticos->updateCargosIndependientes(
        $data['valorCargo'],
        $data['idCargo'],
        $data['idIdentificador']
    );
    
    // Responder con el resultado
    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Viático actualizado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el viático.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
}
?>