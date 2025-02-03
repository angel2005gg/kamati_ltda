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
    public function crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin) {
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO curso_usuario (id_Usuarios, id_curso_empresa, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin);
        $resultado = $stmt->execute();
        $this->id_curso_usuario = $stmt->insert_id;
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerPorId($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT cu.*, 
                CONCAT(u.primer_nombre, ' ', 
                      IFNULL(u.segundo_nombre, ''), ' ',
                      u.primer_apellido, ' ', 
                      IFNULL(u.segundo_apellido, '')) as nombre_usuario,
                a.nombre_area as area,
                cr.nombre_curso_fk as nombre_curso,
                ce.fecha_realizacion as fecha_inicio,
                ce.fecha_vencimiento as fecha_fin,
                ec.nombre_empresa as empresa,
                ce.estado
                FROM curso_usuario cu
                JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
                JOIN area a ON c.id_area_fk = a.id_Area
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
        $sql = "SELECT cu.id_curso_usuario,
                CONCAT(u.primer_nombre, ' ', 
                       IFNULL(u.segundo_nombre, ''), ' ', 
                       u.primer_apellido, ' ', 
                       IFNULL(u.segundo_apellido, '')) as nombre_usuario,
                a.nombre_area as area,
                cu.fecha_inicio,
                cu.fecha_fin,
                cr.nombre_curso_fk as nombre_curso,
                ec.nombre_empresa as empresa,
                ce.estado
                FROM curso_usuario cu
                JOIN usuarios u ON cu.id_Usuarios = u.id_Usuarios
                JOIN cargo c ON u.id_Cargo_Usuario = c.id_Cargo
                JOIN area a ON c.id_area_fk = a.id_Area
                JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                JOIN curso cr ON ce.id_curso = cr.id_curso";
        
        $resultado = $conn->query($sql);
        if (!$resultado) {
            throw new Exception("Error en la consulta: " . $conn->error);
        }
        $cursos_usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $cursos_usuarios;
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
        $sql = "SELECT cu.id_curso_usuario,
                cr.nombre_curso_fk as nombre_curso,
                ce.fecha_realizacion as fecha_inicio,
                ce.fecha_vencimiento as fecha_fin,
                ce.estado,
                ec.nombre_empresa as empresa
                FROM curso_usuario cu
                JOIN curso_empresa ce ON cu.id_curso_empresa = ce.id_curso_empresa
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                JOIN curso cr ON ce.id_curso = cr.id_curso
                WHERE cu.id_Usuarios = ?";
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

    
}
?>