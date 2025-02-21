<?php
require_once '../configuracion/ConexionBD.php';

class Contratista {
    private $id_contratista;
    private $nombre_contratista;
    private $correo_contratista;
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBD();
    }

    // Getters y Setters
    public function getIdContratista() {
        return $this->id_contratista;
    }

    public function getNombreContratista() {
        return $this->nombre_contratista;
    }

    public function setNombreContratista($nombre_contratista) {
        $this->nombre_contratista = $nombre_contratista;
    }

    public function getCorreoContratista() {
        return $this->correo_contratista;
    }

    public function setCorreoContratista($correo_contratista) {
        $this->correo_contratista = $correo_contratista;
    }

    // Métodos CRUD
    public function crear($nombre_contratista, $correo_contratista) {
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO contratista (nombre_contratista, correo_contratista) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre_contratista, $correo_contratista);
        $resultado = $stmt->execute();
        $this->id_contratista = $stmt->insert_id;
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerPorId($id) {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT * FROM contratista WHERE id_contratista = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerTodos() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT * FROM contratista";
        $resultado = $conn->query($sql);
        $contratistas = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $contratistas;
    }

    public function actualizar($id, $nombre_contratista, $correo_contratista) {
        $conn = $this->conexion->conectarBD();
        $sql = "UPDATE contratista SET nombre_contratista = ?, correo_contratista = ? WHERE id_contratista = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre_contratista, $correo_contratista, $id);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function eliminar($id_contratista) {
        $conn = $this->conexion->conectarBD();
        $sql = "DELETE FROM contratista WHERE id_contratista = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_contratista);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }
}
?>