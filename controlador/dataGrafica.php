<?php
require_once '../modelo/dao/PermisosDao.php';

$permisos = new PermisosDao();
$data = $permisos->consultarTiposPermisos();

header('Content-Type: application/json');
echo json_encode($data);
?>