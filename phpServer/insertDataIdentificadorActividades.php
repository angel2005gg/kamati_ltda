<?php
require_once '../modelo/dao/FactorIndependienteActividadesDao.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nombreTablaActividades) && isset($data->id_hidden_table_Actividades)) {
    function limpiarNumero($valor) {
        // Elimina los símbolos $ y los espacios en blanco
        $valor = str_replace(['$', ' ', ','], '', $valor);
        // Convierte los puntos a separadores decimales
        $valor = str_replace('.', '.', $valor);
        // Convierte la cadena resultante a un número decimal
        return floatval($valor);
    }
    $totalCliente = isset($data->txtTotalCliente_ActividadesValor) 
        ? limpiarNumero($data->txtTotalCliente_ActividadesValor)
        : 0;

    $totalKamati = isset($data->txtTotalKamati_ActividadesValor) 
        ? limpiarNumero($data->txtTotalKamati_ActividadesValor)
        : 0;

    $factorDao = new FactorIndependienteActividadesDao();
    $resultado = $factorDao->insertIdentificadorActividades(
        $data->nombreTablaActividades,
        $data->id_hidden_table_Actividades,
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