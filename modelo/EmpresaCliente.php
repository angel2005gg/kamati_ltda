<?php
require_once '../configuracion/ConexionBD.php';

class EmpresaCliente {
    private $id_empresa;
    private $nombre_empresa;
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBD();
    }

    // Getters y Setters
    public function getIdEmpresa() {
        return $this->id_empresa;
    }

    public function getNombreEmpresa() {
        return $this->nombre_empresa;
    }

    public function setNombreEmpresa($nombre_empresa) {
        $this->nombre_empresa = $nombre_empresa;
    }

    // Métodos CRUD
    public function crear($nombre_empresa) {
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO empresa_cliente (nombre_empresa) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre_empresa);
        $resultado = $stmt->execute();
        $this->id_empresa = $stmt->insert_id;
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerPorId($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT * FROM empresa_cliente WHERE id_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerTodos() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT * FROM empresa_cliente";
        $resultado = $conn->query($sql);
        if ($resultado === false) {
            die("Error en la consulta: " . $conn->error);
        }
        $empresas = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $empresas;
    }

    public function actualizar($id, $nombre_empresa) {
        $conn = $this->conexion->conectarBD();
        $sql = "UPDATE empresa_cliente SET nombre_empresa = ? WHERE id_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre_empresa, $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function eliminar($id_empresa) {
        $conn = $this->conexion->conectarBD();
        $sql = "DELETE FROM empresa_cliente WHERE id_empresa_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }
}
?>