<?php
require_once '../modelo/EmpresaCliente.php';

class ControladorEmpresaCliente {
    private $empresaCliente;

    public function __construct() {
        $this->empresaCliente = new EmpresaCliente();
    }

    public function crear($nombre_empresa) {
        return $this->empresaCliente->crear($nombre_empresa);
    }

    public function obtenerPorId($id) {
        return $this->empresaCliente->obtenerPorId($id);
    }

    public function obtenerTodos() {
        return $this->empresaCliente->obtenerTodos();
    }

    public function actualizar($id, $nombre_empresa) {
        return $this->empresaCliente->actualizar($id, $nombre_empresa);
    }

    public function eliminar($id) {
        return $this->empresaCliente->eliminar($id);
    }
}
?>