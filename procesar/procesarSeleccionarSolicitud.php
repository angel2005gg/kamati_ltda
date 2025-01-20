<?php
require_once '../modelo/dao/PermisosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_permiso'])) {
    $idPermiso = $_POST['id_permiso'];

    // Instancia de tu clase que contiene el método de consulta
    $usuarios = new PermisosDao(); // Cambia esto por la instancia correcta

    $data = $usuarios->seleccionarPermiso($idPermiso);

    echo json_encode($data);
}