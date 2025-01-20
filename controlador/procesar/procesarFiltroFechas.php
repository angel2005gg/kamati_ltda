<?php
// Importar la clase PermisosDao y cualquier otra dependencia necesaria
require_once '../modelo/dao/PermisosDao.php';

// Validar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar el valor de 'fecha' recibido del usuario
    $fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : '';

    // Validar que la fecha no esté vacía antes de continuar
    if (!empty($fecha)) {
        try {
            // Crear una instancia de PermisosDao para manejar las consultas
            $permisosDao = new PermisosDao();

            // Consultar los permisos filtrados por fecha
            $data = $permisosDao->consultarPermisosCompletosFiltroFecha($fecha);

            // Devolver los datos obtenidos como respuesta JSON
            echo json_encode($data);
        } catch (Exception $e) {
            // Manejo de errores: atrapar cualquier excepción y devolver un mensaje de error
            http_response_code(500); // Código de error de servidor interno
            echo json_encode(array('error' => 'Error al procesar la solicitud'));
        }
    } else {
        // Si la fecha está vacía o no se proporcionó, devolver un mensaje de error
        http_response_code(400); // Código de error de solicitud incorrecta
        echo json_encode(array('error' => 'Debe proporcionar una fecha válida'));
    }
} else {
    // Si la solicitud no es de tipo POST, devolver un mensaje de error
    http_response_code(405); // Método no permitido
    echo json_encode(array('error' => 'Método no permitido'));
}
