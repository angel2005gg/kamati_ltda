<?php
require_once '../modelo/dao/CargoDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_Cargo'])) {
    $cargoId = $_POST['id_Cargo'];

    // Instancia de tu clase que contiene el método de consulta
    $cargo = new CargoDao(); // Cambia esto por la instancia correcta

    $data = $cargo->seleccionarCargo($cargoId);

    echo json_encode($data);
}