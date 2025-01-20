<?php
require_once '../modelo/dao/FactorIndependienteDao.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nombreTablaMateriales) && isset($data->id_hidden_table_materiales)) {
    function limpiarNumero($valor) {
        $valor = str_replace(['$', ' ', '.'], '', $valor);
        return floatval(str_replace(',', '.', $valor));
    }

    $totalCliente = isset($data->txtTotalCliente_materialesValor) 
        ? limpiarNumero($data->txtTotalCliente_materialesValor)
        : 0;

    $totalKamati = isset($data->txtTotalKamati_materialesValor) 
        ? limpiarNumero($data->txtTotalKamati_materialesValor)
        : 0;

    $factorDao = new FactorIndependienteDao();
    $resultado = $factorDao->insertFactorIndependiente(
        $data->nombreTablaMateriales,
        $data->id_hidden_table_materiales,
        $totalKamati,
        $totalCliente

    );

    if ($resultado) {
        echo json_encode(['success' => true, 'insertId' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo insertar los datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
}
?>