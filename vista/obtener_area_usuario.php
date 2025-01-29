<?php
require_once '../configuracion/ConexionBD.php';

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer cabeceras
header('Content-Type: application/json');

// Log de la petición
$request_log = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'raw_input' => file_get_contents('php://input')
];

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'error' => 'Método no permitido',
        'debug' => $request_log
    ]);
    exit();
}

// Verificar ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'error' => 'ID no proporcionado',
        'debug' => $request_log
    ]);
    exit();
}

try {
    $id_curso_usuario = $_POST['id'];
    $conn = (new ConexionBD())->conectarBD();
    
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $sql = "SELECT a.nombre_area 
            FROM curso_usuario cu
            JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
            JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
            JOIN area a ON c.id_area_fk = a.id_Area
            WHERE cu.id_curso_usuario = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_curso_usuario);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    if ($fila) {
        echo json_encode(['area' => $fila['nombre_area']]);
    } else {
        echo json_encode([
            'error' => 'Área no encontrada',
            'debug' => [
                'id_buscado' => $id_curso_usuario,
                'sql' => $sql
            ]
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'debug' => [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>