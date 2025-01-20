<?php
session_start();
require_once '../modelo/dao/PermisosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreEstado'])) {
    $nombreEst = trim($_POST['nombreEstado']); // Eliminar espacios en blanco al inicio y al final


    $nombre = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ\s-]/u", "", $nombreEst);

    // Validar que el nombre no esté vacío después de saneado
    if (!empty($nombre)) {
        // Instancia de tu clase que contiene el método de consulta
        $permisos = new PermisosDao();

        $datas = $permisos->consultarPermisoSolicitadoFiltroPendiente($nombre, $_SESSION['idUser']);

        echo json_encode($datas);
    } else {
        echo json_encode(['error' => 'Nombre inválido']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}

?>
