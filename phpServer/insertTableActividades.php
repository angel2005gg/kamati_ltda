<!-- <?php
// require_once '../modelo/dao/ActividadesDao.php';  // Ajustar según la ruta real

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// $data = json_decode(file_get_contents("php://input"), true);

// if ($data === null) {
//     echo json_encode(["success" => false, "error" => "Datos JSON inválidos"]);
//     exit;
// }

// if (!isset($data['actividades']) || empty($data['actividades'])) {
//     echo json_encode(["success" => false, "error" => "No se recibieron actividades para guardar"]);
//     exit;
// }

// try {
//     $dao = new ActividadesDao();
//     $insertedIds = $dao->guardarActividades($data['actividades']); // Se espera que las actividades vengan en un array bajo 'actividades'

//     if ($insertedIds) {
//         echo json_encode(["success" => true, "insertedIds" => $insertedIds]);
//     } else {
//         echo json_encode(["success" => false, "error" => "No se pudo guardar las actividades"]);
//     }
// } catch (Exception $e) {
//     echo json_encode(["success" => false, "error" => "Excepción: " . $e->getMessage()]);
// }
?> -->