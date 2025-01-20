<?php
require_once '../modelo/Area.php';
require_once '../modelo/Usuarios.php';
require_once '../modelo/dao/CargoDao.php';
require_once '../modelo/dao/AreaDao.php';
require_once '../controlador/ControladorUser.php';
require_once '../modelo/dao/JefeAreaDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];



    try {
        switch ($action) {
            case 'consultarArea':
                if (isset($_POST['id_cargo'])) {
                    $clase = new CargoDao();
                    $idCargo = $_POST['id_cargo'];
                    $idArea = $clase->consultarArea($idCargo);

                    error_log("ID de área consultada: $idArea");  // Depuración

                    header('Content-Type: application/json');
                    echo json_encode(['id_area_fk' => $idArea ?: null]);
                } else {
                    throw new Exception("ID de cargo no proporcionado");
                }
                break;

            case 'consultarJefeArea':
                if (isset($_POST['id_area'])) {
                    $jefe = new JefeAreaDao();
                    $idArea = $_POST['id_area'];
                    $jefes = $jefe->consultarJefeArea($idArea);

                    error_log("Jefes consultados: " . json_encode($jefes));  // Depuración

                    header('Content-Type: application/json');
                    echo json_encode($jefes ?: []);
                } else {
                    throw new Exception("ID de área no proporcionado");
                }
                break;

            default:
                throw new Exception("Acción no válida: $action");
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());  // Depuración
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    error_log("Método no permitido");  // Depuración
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Método no permitido']);
}

?>
