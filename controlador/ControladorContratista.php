<?php
require_once '../modelo/Contratista.php';

class ControladorContratista {
    private $contratista;

    public function __construct() {
        $this->contratista = new Contratista();
    }

    public function crear($nombre_contratista) {
        return $this->contratista->crear($nombre_contratista);
    }

    public function obtenerPorId($id) {
        return $this->contratista->obtenerPorId($id);
    }

    public function obtenerTodos() {
        return $this->contratista->obtenerTodos();
    }

    public function actualizar($id, $nombre_contratista) {
        return $this->contratista->actualizar($id, $nombre_contratista);
    }

    public function eliminar($id_contratista) {
        return $this->contratista->eliminar($id_contratista);
    }
}
?>