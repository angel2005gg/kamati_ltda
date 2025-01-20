<?php
require_once '../modelo/dao/UsuariosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['numero_identificacion'])) {
    $numero_identificacion = $_POST['numero_identificacion'];

    // Instancia de tu clase que contiene el método de consulta
    $usuarios = new UsuariosDao(); // Cambia esto por la instancia correcta

    $data = $usuarios->consultarActualizarUser($numero_identificacion);

    echo json_encode($data);
}