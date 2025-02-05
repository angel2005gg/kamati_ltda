<?php
ob_clean();
ob_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once '../controlador/ControladorCursoEmpresa.php';

$response = ['success' => false, 'error' => 'Error desconocido'];

try {
    $curso_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($curso_id <= 0) {
        throw new Exception("ID inválido");
    }

    $controlador = new ControladorCursoEmpresa();
    $resultado = $controlador->eliminar($curso_id);

    if ($resultado['eliminado']) {
        $response = [
            'success' => true, 
            'message' => $resultado['mensaje']
        ];
    } else {
        $response = [
            'success' => false, 
            'error' => $resultado['error']
        ];
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
} finally {
    ob_end_clean();
    echo json_encode($response);
    exit();
}
?>