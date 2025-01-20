<?php
require_once '../modelo/dao/FactorIndependienteActividadesDao.php'; // Incluye el archivo DAO

$data = json_decode(file_get_contents("php://input"), true);
$dao = new FactorIndependienteActividadesDao(); // Cambia al nombre real de tu clase DAO
$resultados = [];

foreach ($data['datos'] as $item) {
    $id_IdentificadorActividades = $item['identificadorViaticos']; // Cambia según corresponda
    $nombreViatico = $item['nombreViatico'];
    $valorViatico = $item['valorViatico'];

    $insertedId = $dao->insertDataTarifaCargoActividadesIndependientes($id_IdentificadorActividades, $nombreViatico, $valorViatico);
    $resultados[] = ["nombre" => $nombreViatico, "idInsertado" => $insertedId];
}

echo json_encode(["status" => "success", "datosInsertados" => $resultados]);