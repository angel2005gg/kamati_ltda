<?php
require_once '../modelo/dao/UsuariosDao.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
        $nombre = trim($_POST['nombre']); // Eliminar espacios en blanco al inicio y al final

        // Saneamiento robusto que permite letras con tildes
        // Elimina todos los caracteres que no sean letras, letras con tildes, espacios o guiones
        $nombres = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ\s-]/u", "", $nombre);

        // Validar que el nombre no esté vacío después de saneado
        if (!empty($nombres)) {
            // Instancia de tu clase que contiene el método de consulta
            $user = new UsuariosDao();

            $data = $user->consultaFiltroUser($nombres);

            // Asegúrate de que siempre devuelva un array
            if (is_array($data)) {
                echo json_encode($data);
            } else {
                echo json_encode(['error' => 'Error en la consulta de datos']);
            }
        } else {
            echo json_encode(['error' => 'Nombre inválido']);
        }
    } else {
        echo json_encode(['error' => 'Solicitud inválida']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Excepción capturada: ' . $e->getMessage()]);
}
