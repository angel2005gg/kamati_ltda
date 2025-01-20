<?php
// Importar la clase PermisosDao y cualquier otra dependencia necesaria
require_once '../modelo/dao/PermisosDao.php';

// Iniciar la sesión
session_start();

// Establecer el encabezado de respuesta JSON
header('Content-Type: application/json');

// Validar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar el valor de 'fecha' recibido del usuario
    $fecha = isset($_POST['fechaAproba']) ? trim($_POST['fechaAproba']) : '';

    // Validar que la fecha no esté vacía antes de continuar
    if (!empty($fecha)) {
        // Validar que el usuario esté autenticado y que 'idUser' esté disponible en la sesión
        if (isset($_SESSION['idUser'])) {
            try {
                // Crear una instancia de PermisosDao para manejar las consultas
                $permisosDao = new PermisosDao();

                // Consultar los permisos filtrados por fecha
                $data = $permisosDao->consultarPermisosAprobadosFiltroFecha($fecha, $_SESSION['idUser']);

                // Verificar si se obtuvieron datos
                if ($data !== null) {
                    // Devolver los datos obtenidos como respuesta JSON
                    echo json_encode($data);
                } else {
                    // No se obtuvieron datos, devolver un mensaje de error
                    http_response_code(404); // No encontrado
                    echo json_encode(array('error' => 'No se encontraron permisos aprobados para la fecha proporcionada.'));
                }
            } catch (Exception $e) {
                // Manejo de errores: atrapar cualquier excepción y devolver un mensaje de error
                http_response_code(500); // Código de error de servidor interno
                echo json_encode(array('error' => 'Error al procesar la solicitud: ' . $e->getMessage()));
            }
        } else {
            // El usuario no está autenticado
            http_response_code(401); // No autorizado
            echo json_encode(array('error' => 'Usuario no autenticado'));
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
