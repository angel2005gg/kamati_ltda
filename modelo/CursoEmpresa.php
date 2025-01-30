<?php
require_once '../configuracion/ConexionBD.php';
require_once '../modelo/EmpresaCliente.php';
require_once '../modelo/Contratista.php';

class CursoEmpresa {
    private $id_curso_empresa;
    private $id_empresa_cliente;
    private $id_contratista;
    private $nombre_curso;
    private $fecha_realizacion;
    private $fecha_vencimiento;
    private $estado;
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBD();
    }

    // Getters y Setters
    public function getIdCursoEmpresa() {
        return $this->id_curso_empresa;
    }

    public function getIdEmpresaCliente() {
        return $this->id_empresa_cliente;
    }

    public function setIdEmpresaCliente($id_empresa_cliente) {
        $this->id_empresa_cliente = $id_empresa_cliente;
    }

    public function getIdContratista() {
        return $this->id_contratista;
    }

    public function setIdContratista($id_contratista) {
        $this->id_contratista = $id_contratista;
    }

    public function getNombreCurso() {
        return $this->nombre_curso;
    }

    public function setNombreCurso($nombre_curso) {
        $this->nombre_curso = $nombre_curso;
    }

    public function getFechaRealizacion() {
        return $this->fecha_realizacion;
    }

    public function setFechaRealizacion($fecha_realizacion) {
        $this->fecha_realizacion = $fecha_realizacion;
    }

    public function getFechaVencimiento() {
        return $this->fecha_vencimiento;
    }

    public function setFechaVencimiento($fecha_vencimiento) {
        $this->fecha_vencimiento = $fecha_vencimiento;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    // Métodos CRUD
    public function crear($id_empresa_cliente, $id_contratista, $id_curso, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado) {
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO curso_empresa (id_empresa_cliente, id_contratista, id_curso, nombre_curso, fecha_realizacion, fecha_vencimiento, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiissss", $id_empresa_cliente, $id_contratista, $id_curso, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado);
        $resultado = $stmt->execute();
        $this->id_curso_empresa = $stmt->insert_id;
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerPorId($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT ce.*, ec.nombre_empresa, c.nombre_contratista 
                FROM curso_empresa ce
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa
                JOIN contratista c ON ce.id_contratista = c.id_contratista
                WHERE ce.id_curso_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerTodos() {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT 
                        ce.id_curso_empresa,
                        ce.id_empresa_cliente,
                        ce.nombre_curso,
                        ce.fecha_realizacion,
                        ce.fecha_vencimiento,
                        ce.estado,
                        ec.nombre_empresa
                    FROM curso_empresa ce
                    JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente";
            
            $resultado = $conn->query($sql);
            
            if (!$resultado) {
                throw new Exception("Error en la consulta: " . $conn->error);
            }
            
            $cursos = $resultado->fetch_all(MYSQLI_ASSOC);
            $this->conexion->desconectarBD();
            return $cursos;
            
        } catch (Exception $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar($id, $id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado) {
        $conn = $this->conexion->conectarBD();
        $sql = "UPDATE curso_empresa 
                SET id_empresa_cliente = ?, 
                    id_contratista = ?, 
                    nombre_curso = ?, 
                    fecha_realizacion = ?, 
                    fecha_vencimiento = ?, 
                    estado = ? 
                WHERE id_curso_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssi", $id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado, $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function eliminar($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "DELETE FROM curso_empresa WHERE id_curso_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    // Métodos adicionales útiles
// Modelo CursoEmpresa.php - Ajustes en el método obtenerPorEmpresa
public function obtenerPorEmpresa($id_empresa) {
    try {
        error_log("Intentando obtener cursos para empresa ID: " . $id_empresa);
        
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT 
                    ce.id_curso_empresa,
                    ce.nombre_curso,
                    ce.fecha_realizacion,
                    ce.fecha_vencimiento,
                    ce.estado,
                    ec.nombre_empresa
                FROM curso_empresa ce
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa_cliente
                WHERE ce.id_empresa_cliente = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        error_log("Resultado obtenido: " . print_r($resultado, true));
        
        $this->conexion->desconectarBD();
        return $resultado;
        
    } catch (Exception $e) {
        error_log("Error en obtenerPorEmpresa: " . $e->getMessage());
        throw $e;
    }
}
    public function obtenerPorContratista($id_contratista) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT ce.*, ec.nombre_empresa, c.nombre_contratista 
                FROM curso_empresa ce
                JOIN empresa_cliente ec ON ce.id_empresa_cliente = ec.id_empresa
                JOIN contratista c ON ce.id_contratista = c.id_contratista
                WHERE ce.id_contratista = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_contratista);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $resultado;
    }
}

?>