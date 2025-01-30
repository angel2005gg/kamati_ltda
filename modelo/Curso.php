<?php
require_once '../configuracion/ConexionBD.php';

class Curso {
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBD();
    }

    public function crear($nombre_curso_fk) {
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO curso (nombre_curso_fk) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre_curso_fk);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerTodos() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT * FROM curso";
        $resultado = $conn->query($sql);
        $cursos = $resultado->fetch_all(MYSQLI_ASSOC);
        $this->conexion->desconectarBD();
        return $cursos;
    }

    public function eliminar($id_curso) {
        $conn = $this->conexion->conectarBD();
        $sql = "DELETE FROM curso WHERE id_curso = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_curso);
        $resultado = $stmt->execute();
        $this->conexion->desconectarBD();
        return $resultado;
    }

    public function obtenerUltimoId() {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT MAX(id_curso) AS id_curso FROM curso";
        $resultado = $conn->query($sql);
        $id_curso = $resultado->fetch_assoc()['id_curso'];
        $this->conexion->desconectarBD();
        return $id_curso;
    }
}
?>