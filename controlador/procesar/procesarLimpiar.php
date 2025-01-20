<?php
require_once '../modelo/dao/UsuariosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    // Instancia de tu clase que contiene el método de consulta
    $user = new UsuariosDao(); // Cambia esto por la instancia correcta

    $data = $user->consultaFiltroUser($nombre);

    echo json_encode($data);
}
