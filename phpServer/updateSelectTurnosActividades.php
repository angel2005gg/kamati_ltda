<?php
session_start();
// Incluir la clase de conexión a la base de datos y el DAO
require_once '../modelo/dao/ActividadesDao.php';

// Establecer la cabecera para respuesta JSON
header('Content-Type: application/json');

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear una instancia del DAO
    $dao = new ActividadesDao();
    
    try {
        // Obtener las filas de TurnoActividades a través del DAO, que internamente obtiene los IDs necesarios
        $filas = $dao->getFilasTurnoActividades();

        // Verificar si se obtuvieron resultados
        if (!empty($filas)) {
            echo json_encode(['data' => $filas]);
        } else {
            echo json_encode(['message' => 'No se encontraron datos para los IDs proporcionados.']);
        }
    } catch (Exception $e) {
        // En caso de error, retornar el error
        echo json_encode(['error' => 'Hubo un problema al obtener los datos', 'details' => $e->getMessage()]);
    }
} else {
    // Si el método no es POST, retornar un error
    echo json_encode(['error' => 'Método no permitido']);
}

?>