<?php
session_start();
require_once '../modelo/dao/PermisosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fechaJefeAdmin'])) {
    $fecha = trim($_POST['fechaJefeAdmin']); // Eliminar espacios en blanco al inicio y al final

    // Saneamiento robusto que permite letras con tildes
    // Elimina todos los caracteres que no sean letras, letras con tildes, espacios o guiones
   

    // Validar que el nombre no esté vacío después de saneado
    if (!empty($fecha)) {
        // Instancia de tu clase que contiene el método de consulta
        $permisos = new PermisosDao();

        $datas = $permisos->consultarPermisoSolicitadoFiltro($_SESSION['idUser'],$fecha);

        echo json_encode($datas);
    } else {
        echo json_encode(['error' => 'Nombre inválido']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}

?>
