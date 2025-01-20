<?php
class Proveedores
{

    private $idProveedor, $nombre_preveedor;

    public function __construct(
        $idProveedor = null,
        $nombre_preveedor = null
    ) {
        $this->$idProveedor = $idProveedor;
        $this->nombre_preveedor = $nombre_preveedor;
    }
    

    public function getIdProveedor()
    {
        return $this->idProveedor;
    }


    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;

        return $this;
    }

    public function getNombre_preveedor()
    {
        return $this->nombre_preveedor;
    }


    public function setNombre_preveedor($nombre_preveedor)
    {
        $this->nombre_preveedor = $nombre_preveedor;

        return $this;
    }
}
