<?php

header('Content-Type: application/json');

// Asegúrate de que no haya ningún espacio en blanco o nueva línea antes de esta línea

require_once '../modelo/ComercialProjects.php';
require_once '../modelo/dao/ComecialProjectsDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Asegúrate de que los datos estén disponibles
    if (isset($data['id_cotizacion']) && isset($data['nombre_cotizacion']) && isset($data['codigo_cotizacion']) &&
        isset($data['fecha_actual']) && isset($data['nombre_cliente']) && isset($data['dolarCotizacion']) && isset($data['euroCotizacion'])) {

        // Crea un objeto comercial con los datos recibidos
        $comercial = new ComercialProjects();
        $comercial->setId_Cotizacion($data['id_cotizacion']);
        $comercial->setNombre_cotizacion($data['nombre_cotizacion']);
        $comercial->setCodigo_cotizacion($data['codigo_cotizacion']);
        $comercial->setFechaActual($data['fecha_actual']);
        $comercial->setNombreCliente($data['nombre_cliente']);
        $comercial->setDolarCotizacion($data['dolarCotizacion']);
        $comercial->setEuroCotizacion($data['euroCotizacion']);

        // Llama al método de actualización
        $tuClase = new ComercialProjectsDao();
        $tuClase->updateCotizacion($comercial);

        // Responde con éxito
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>