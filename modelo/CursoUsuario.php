<?php
require_once '../configuracion/ConexionBD.php';

class CursoUsuario {
    private $id_curso_usuario;
    private $id_usuario;
    private $id_curso_empresa;
    private $fecha_inicio;
    private $fecha_fin;
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBD();
    }

    // Getters y Setters
    public function getIdCursoUsuario() {
        return $this->id_curso_usuario;
    }

    public function getIdUsuario() {
        return $this->id_usuario; 
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getIdCursoEmpresa() {
        return $this->id_curso_empresa;
    }

    public function setIdCursoEmpresa($id_curso_empresa) {
        $this->id_curso_empresa = $id_curso_empresa;
    }

    public function getFechaInicio() {
        return $this->fecha_inicio;
    }

    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFechaFin() {
        return $this->fecha_fin;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    // Métodos CRUD
    public function crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $dias_notificacion, $tipo) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO curso_usuario (id_Usuarios, id_curso_empresa, fecha_inicio, fecha_fin, dias_notificacion, tipo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Error en la preparación de la consulta: " . $conn->error);
                return false;
            }

            $stmt->bind_param("iissis", $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $dias_notificacion, $tipo);

            if (!$stmt->execute()) {
                error_log("Error al ejecutar la consulta: " . $stmt->error);
                error_log("Último error MySQL: " . $conn->error);
                return false;
            }

            $this->id_curso_usuario = $stmt->insert_id;
            error_log("Curso usuario creado con ID: " . $this->id_curso_usuario);

            $this->conexion->desconectarBD();
            return true;

        } catch (Exception $e) {
            error_log("Error en crear curso_usuario: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            if (isset($conn)) {
                $this->conexion->desconectarBD();
            }
            return false;
        }
    }

    public function obtenerUltimoIdCursoUsuario() {
        return $this->id_curso_usuario;
    }

    public function obtenerPorId($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT 
                    cu.*, 
                    CASE 
                        WHEN cu.tipo = 'usuario' THEN CONCAT(u.primer_nombre, ' ', IFNULL(u.segundo_nombre, ''), ' ', u.primer_apellido, ' ', IFNULL(u.segundo_apellido, ''))
                        WHEN cu.tipo = 'contratista' THEN c.nombre_contratista
                        ELSE 'N/A'
                    END AS nombre_usuario,
                    CASE 
                        WHEN cu.tipo = 'usuario' THEN a.nombre_area
                        WHEN cu.tipo = 'contratista' THEN 'Contratista'
                        ELSE 'N/A'
                    END AS area,
                    u.correo_electronico as correo_usuario,
                    CASE 
                        WHEN cu.tipo = 'contratista' THEN c.correo_contratista
                        ELSE NULL
                    END AS correo_contratista,
                    cr.nombre_curso_fk as nombre_curso,
                    cu.fecha_inicio as fecha_inicio,
                    cu.fecha_fin as fecha_fin,
                    ec.nombre_empresa as empresa,
                    ce.estado,
                    ce.duracion,
                    cu.tipo
                FROM curso_usuario cu
                LEFT JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                LEFT JOIN contratista c ON cu.id_Usuarios = c.id_contratista
                LEFT JOIN cargo c2 ON u.id_Cargo_Usuario = c2.id_Cargo
                LEFT JOIN area a ON c2.id_area_fk = a.id_Area
                JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                JOIN curso cr ON ce.id_curso = cr.id_curso
                WHERE cu.id_curso_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $this->conexion->desconectarBD();
        return $resultado;
    }
    
    
    public function obtenerTodos() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT cu.*, 
                CONCAT(u.primer_nombre, ' ', 
                      IFNULL(u.segundo_nombre, ''), ' ',
                      u.primer_apellido, ' ', 
                      IFNULL(u.segundo_apellido, '')) as nombre_usuario,
                u.correo as correo_usuario,
                a.nombre_area as area,
                cr.nombre_curso_fk as nombre_curso,
                cu.fecha_inicio as fecha_inicio,
                cu.fecha_fin as fecha_fin,
                ec.nombre_empresa as empresa,
                ce.estado
                FROM curso_usuario cu
                JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
                JOIN area a ON c.id_area_fk = a.id_Area
                JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                JOIN curso cr ON ce.id_curso = cr.id_curso";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $resultado;
    }
    // Método para obtener los días de notificación de un curso de usuario
    // Método para obtener los días de notificación de un curso de usuario
    // Método para obtener los días de notificación de un curso de usuario
public function obtenerDiasNotificacion($id_curso_usuario) {
    try {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT dias FROM dias_notificacion WHERE id_curso_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_curso_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        
        // Debug
        error_log("Días de notificación obtenidos para curso_usuario " . $id_curso_usuario . ": " . print_r($resultado, true));
        
        return $resultado;
    } catch (Exception $e) {
        error_log("Error al obtener días de notificación: " . $e->getMessage());
        $this->conexion->desconectarBD();
        return [];
    }
}

    // Método para insertar los días de notificación de un curso de usuario
    public function insertarDiasNotificacion($id_curso_usuario, $dias_notificacion) {
        try {
            $conn = $this->conexion->conectarBD();
            
            // Primero eliminamos notificaciones existentes
            $sqlDelete = "DELETE FROM dias_notificacion WHERE id_curso_usuario = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $id_curso_usuario);
            $stmtDelete->execute();
            
            // Luego insertamos las nuevas
            $sql = "INSERT INTO dias_notificacion (id_curso_usuario, dias) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            
            foreach ($dias_notificacion as $dias) {
                $stmt->bind_param("ii", $id_curso_usuario, $dias);
                $stmt->execute();
            }
            
            $this->conexion->desconectarBD();
            return true;
            
        } catch (Exception $e) {
            error_log("Error en insertarDiasNotificacion: " . $e->getMessage());
            $this->conexion->desconectarBD();
            return false;
        }
    }

    // Método para eliminar los días de notificación de un curso de usuario
    public function eliminarDiasNotificacion($id_curso_usuario) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "DELETE FROM dias_notificacion WHERE id_curso_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_curso_usuario);
            $resultado = $stmt->execute();
            $this->conexion->desconectarBD();
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en eliminarDiasNotificacion: " . $e->getMessage());
            $this->conexion->desconectarBD();
            return false;
        }
    }

    public function eliminar($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "DELETE FROM curso_usuario WHERE id_curso_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    
    public function obtenerCursosPorUsuario($id_usuario) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT 
                cu.id_curso_usuario,
                CONCAT(u.primer_nombre, ' ', 
                      IFNULL(u.segundo_nombre, ''), ' ',
                      u.primer_apellido, ' ', 
                      IFNULL(u.segundo_apellido, '')) as nombre_usuario,
                a.nombre_area as area,
                cu.fecha_inicio,
                cu.fecha_fin,
                cr.nombre_curso_fk as nombre_curso,
                ec.nombre_empresa as empresa,
                ce.estado,
                cu.tipo,
                cu.id_Usuarios
                FROM curso_usuario cu
                JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
                JOIN area a ON c.id_area_fk = a.id_Area
                JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                JOIN curso cr ON ce.id_curso = cr.id_curso
                WHERE cu.id_Usuarios = ?
                AND (cu.tipo IS NULL OR cu.tipo = 'usuario')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerUsuariosPorCurso($id_curso_empresa) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT cu.id_curso_usuario,
                CONCAT(u.primer_nombre, ' ', 
                      IFNULL(u.segundo_nombre, ''), ' ',
                      u.primer_apellido, ' ', 
                      IFNULL(u.segundo_apellido, '')) as nombre_usuario,
                a.nombre_area as area,
                u.tipo_contratista,
                u.estado_usuario as estado
                FROM curso_usuario cu
                JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
                JOIN area a ON c.id_area_fk = a.id_Area
                WHERE cu.id_curso_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_curso_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function verificarInscripcion($id_usuario, $id_curso_empresa) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT COUNT(*) as existe 
                FROM curso_usuario 
                WHERE id_Usuarios = ? AND id_curso_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_curso_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $this->conexion->desconectarBD();
        return $resultado['existe'] > 0;
    }

    public function actualizar($id, $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin) {
        $conn = $this->conexion->conectarBD();
        $sql = "UPDATE curso_usuario 
                SET id_Usuarios = ?, 
                    id_curso_empresa = ?, 
                    fecha_inicio = ?, 
                    fecha_fin = ? 
                WHERE id_curso_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissi", $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerTodosFiltrados($filtros) {
        $conn = $this->conexion->conectarBD();
        
        // Construimos una consulta que recupere cursos para usuarios y contratistas
        $sql = "SELECT 
                cu.id_curso_usuario,
                CASE 
                    WHEN cu.tipo = 'usuario' THEN CONCAT(u.primer_nombre, ' ', IFNULL(u.segundo_nombre, ''), ' ', u.primer_apellido, ' ', IFNULL(u.segundo_apellido, ''))
                    WHEN cu.tipo = 'contratista' THEN c.nombre_contratista
                    ELSE 'N/A'
                END AS nombre_usuario,
                CASE 
                    WHEN cu.tipo = 'usuario' THEN a.nombre_area
                    WHEN cu.tipo = 'contratista' THEN 'Contratista'
                    ELSE 'N/A'
                END AS area,
                cu.fecha_inicio,
                cu.fecha_fin,
                cr.nombre_curso_fk as nombre_curso,
                ec.nombre_empresa as empresa,
                ce.estado,
                cu.tipo
            FROM curso_usuario cu
            LEFT JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
            LEFT JOIN contratista c ON cu.id_Usuarios = c.id_contratista
            LEFT JOIN cargo c2 ON u.id_Cargo_Usuario = c2.id_Cargo
            LEFT JOIN area a ON c2.id_area_fk = a.id_Area
            JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
            JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
            JOIN curso cr ON ce.id_curso = cr.id_curso
            WHERE 1=1";
        
        $params = [];
        $types = '';
    
        // Filtro por nombre (se aplicará a ambos, usuario o contratista)
        if (!empty($filtros['nombre_usuario'])) {
            $sql .= " AND (
                        (cu.tipo = 'usuario' AND CONCAT(u.primer_nombre, ' ', IFNULL(u.segundo_nombre, ''), ' ', u.primer_apellido, ' ', IFNULL(u.segundo_apellido, '')) LIKE ?)
                        OR
                        (cu.tipo = 'contratista' AND c.nombre_contratista LIKE ?)
                      )";
            $termino = '%' . $filtros['nombre_usuario'] . '%';
            $params[] = $termino;
            $params[] = $termino;
            $types .= 'ss';
        }
        
        // Filtro por área (si se selecciona, se aplica tal cual; para contratistas el área se mostrará como "Contratista")
        if (!empty($filtros['area'])) {
            $sql .= " AND (
                        (u.id_Usuarios IS NOT NULL AND a.nombre_area = ?)
                        OR
                        (u.id_Usuarios IS NULL AND 'Contratista' = ?)
                      )";
            $params[] = $filtros['area'];
            $params[] = $filtros['area'];
            $types .= 'ss';
        }
        
        // Filtros para fechas, curso, empresa y estado (como estaban)
        if (!empty($filtros['año_inicio']) && !empty($filtros['mes_inicio'])) {
            $sql .= " AND YEAR(cu.fecha_inicio) = ? AND MONTH(cu.fecha_inicio) = ?";
            $params[] = $filtros['año_inicio'];
            $params[] = $filtros['mes_inicio'];
            $types .= 'ii';
        } elseif (!empty($filtros['año_inicio'])) {
            $sql .= " AND YEAR(cu.fecha_inicio) = ?";
            $params[] = $filtros['año_inicio'];
            $types .= 'i';
        } elseif (!empty($filtros['mes_inicio'])) {
            $sql .= " AND MONTH(cu.fecha_inicio) = ?";
            $params[] = $filtros['mes_inicio'];
            $types .= 'i';
        }
        
        if (!empty($filtros['año_fin']) && !empty($filtros['mes_fin'])) {
            $sql .= " AND YEAR(cu.fecha_fin) = ? AND MONTH(cu.fecha_fin) = ?";
            $params[] = $filtros['año_fin'];
            $params[] = $filtros['mes_fin'];
            $types .= 'ii';
        } elseif (!empty($filtros['año_fin'])) {
            $sql .= " AND YEAR(cu.fecha_fin) = ?";
            $params[] = $filtros['año_fin'];
            $types .= 'i';
        } elseif (!empty($filtros['mes_fin'])) {
            $sql .= " AND MONTH(cu.fecha_fin) = ?";
            $params[] = $filtros['mes_fin'];
            $types .= 'i';
        }
        
        if (!empty($filtros['nombre_curso'])) {
            $sql .= " AND cr.nombre_curso_fk = ?";
            $params[] = $filtros['nombre_curso'];
            $types .= 's';
        }
        
        if (!empty($filtros['empresa'])) {
            $sql .= " AND ec.nombre_empresa = ?";
            $params[] = $filtros['empresa'];
            $types .= 's';
        }
        
        if (!empty($filtros['estado'])) {
            $sql .= " AND (
                        CASE 
                            WHEN CURRENT_DATE > cu.fecha_fin THEN 'Vencido'
                            WHEN DATEDIFF(cu.fecha_fin, CURRENT_DATE) <= 20 THEN 'A vencer'
                            ELSE 'Vigente'
                        END
                    ) = ?";
            $params[] = $filtros['estado'];
            $types .= 's';
        }
        
        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
       if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
       }
       $stmt->execute();
       $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
       $this->conexion->desconectarBD();
       return $resultado;
    }
    

    public function obtenerTodasLasAreas() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT DISTINCT nombre_area FROM area ORDER BY nombre_area";
        $resultado = $conn->query($sql);
        $areas = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $areas;
    }
    
    public function obtenerAñosDisponibles() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT DISTINCT YEAR(fecha_inicio) as año FROM curso_usuario ORDER BY año";
        $resultado = $conn->query($sql);
        $años = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $años;
    }
    
    public function obtenerCursosDisponibles() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT DISTINCT nombre_curso_fk FROM curso ORDER BY nombre_curso_fk";
        $resultado = $conn->query($sql);
        $cursos = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $cursos;
    }
    
    public function obtenerEmpresasDisponibles() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT DISTINCT nombre_empresa FROM empresa_cliente ORDER BY nombre_empresa";
        $resultado = $conn->query($sql);
        $empresas = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $empresas;
    }
    public function buscarContratistas($termino) {
        $conn = $this->conexion->conectarBD();
        try {
            $sql = "SELECT 'contratista' as tipo,
                   id_contratista AS id, 
                   nombre_contratista AS nombre 
            FROM contratista 
            WHERE nombre_contratista LIKE ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Error en prepare: " . $conn->error);
                return [];
            }
    
            $terminoBusqueda = "%{$termino}%";
            $stmt->bind_param("s", $terminoBusqueda);
    
            if (!$stmt->execute()) {
                error_log("Error en execute: " . $stmt->error);
                return [];
            }
    
            $resultado = $stmt->get_result();
            $datos = [];
    
            while ($fila = $resultado->fetch_assoc()) {
                error_log("Contratista encontrado: " . json_encode($fila));
                $datos[] = [
                    // Prefijamos con "contratista_"
                    'id' => 'contratista_' . $fila['id'],
                    'nombre' => trim($fila['nombre']),
                    'tipo' => 'contratista'
                ];
            }
            
    
            return $datos;
    
        } catch (Exception $e) {
            error_log("Error en buscarContratistas: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if ($conn) {
                $this->conexion->desconectarBD();
            }
        }
    }

    public function buscarUsuarios($termino) {
        $conn = $this->conexion->conectarBD();
        try {
            $sql = "SELECT 'usuario' as tipo, 
                   id_Usuarios as id,
                   CONCAT(
                       COALESCE(primer_nombre, '') COLLATE utf8mb4_general_ci, ' ',
                       COALESCE(segundo_nombre, '') COLLATE utf8mb4_general_ci, ' ',
                       COALESCE(primer_apellido, '') COLLATE utf8mb4_general_ci, ' ',
                       COALESCE(segundo_apellido, '') COLLATE utf8mb4_general_ci
                   ) AS nombre
            FROM usuarios
            WHERE (
                primer_nombre LIKE ? OR
                segundo_nombre LIKE ? OR
                primer_apellido LIKE ? OR
                segundo_apellido LIKE ?
            ) AND estado_usuario = 'Activo'";
    
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Error en prepare: " . $conn->error);
                return [];
            }
    
            $terminoBusqueda = "%{$termino}%";
            $stmt->bind_param("ssss", 
                $terminoBusqueda, 
                $terminoBusqueda, 
                $terminoBusqueda, 
                $terminoBusqueda
            );
    
            if (!$stmt->execute()) {
                error_log("Error en execute: " . $stmt->error);
                return [];
            }
    
            $resultado = $stmt->get_result();
            $datos = [];
    
            while ($fila = $resultado->fetch_assoc()) {
                error_log("Usuario encontrado: " . json_encode($fila));
                $datos[] = [
                    // Prefijamos con "usuario_"
                    'id' => 'usuario_' . $fila['id'],
                    'nombre' => trim($fila['nombre']),
                    'tipo' => 'usuario'
                ];
            }
            
    
            return $datos;
    
        } catch (Exception $e) {
            error_log("Error en buscarUsuarios: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if ($conn) {
                $this->conexion->desconectarBD();
            }
        }
    }
}
?>