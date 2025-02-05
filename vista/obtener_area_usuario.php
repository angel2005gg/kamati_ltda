<?php
require_once '../configuracion/ConexionBD.php';

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer cabeceras
header('Content-Type: application/json');

try {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    $id_usuario = $_POST['id'];  // Cambiado de id_curso_usuario a id_usuario
    $conn = (new ConexionBD())->conectarBD();
    
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Modificada la consulta SQL para buscar directamente por id_usuario
    $sql = "SELECT a.nombre_area 
            FROM usuarios u
            JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
            JOIN area a ON c.id_area_fk = a.id_Area
            WHERE u.id_Usuarios = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_usuario);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    
    // Agregar logging
    error_log("ID Usuario: " . $id_usuario);
    error_log("SQL: " . $sql);
    error_log("Resultado: " . json_encode($fila));
    
    $stmt->close();
    $conn->close();
    
    if ($fila) {
        echo json_encode(['area' => $fila['nombre_area']]);
    } else {
        echo json_encode([
            'error' => 'Área no encontrada',
            'debug' => [
                'id_usuario' => $id_usuario,
                'sql' => $sql
            ]
        ]);
    }

} catch (Exception $e) {
    error_log("Error en obtener_area_usuario.php: " . $e->getMessage());
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