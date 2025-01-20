<?php
session_start();
require_once '../modelo/dao/CargoDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cargoNombre'])) {
    $cargo = trim($_POST['cargoNombre']); // Eliminar espacios en blanco al inicio y al final

    // Saneamiento robusto que permite letras con tildes
    // Elimina todos los caracteres que no sean letras, letras con tildes, espacios o guiones
    $nombreCargo = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ\s-]/u", "", $cargo);

    // Validar que el nombre no esté vacío después de saneado
    if (!empty($nombreCargo)) {
        // Instancia de tu clase que contiene el método de consulta
        $cargos = new CargoDao();

        $datas = $cargos->consultarCargoTablaFiltro($nombreCargo);

        echo json_encode($datas);
    } else {
        echo json_encode(['error' => 'Nombre inválido']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}

?>
