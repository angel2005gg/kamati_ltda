<?php
require_once '../modelo/dao/FactorIndependienteMaquinariaDao.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nombreTablaMaquinaria) && isset($data->id_hidden_table_maquinaria)) {
    function limpiarNumero($valor) {
        $valor = str_replace(['$', ' ', '.'], '', $valor);
        return floatval(str_replace(',', '.', $valor));
    }

    $totalCliente = isset($data->txtTotalCliente_maquinariaValor) 
        ? limpiarNumero($data->txtTotalCliente_maquinariaValor)
        : 0;

    $totalKamati = isset($data->txtTotalKamati_maquinariaValor) 
        ? limpiarNumero($data->txtTotalKamati_maquinariaValor)
        : 0;

    $factorDao = new FactorIndependienteMaquinariaDao();
    $resultado = $factorDao->insertIdentificadorMaquinaria(
        $data->nombreTablaMaquinaria,
        $data->id_hidden_table_maquinaria,
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