<?php
class Cargo
{
    private $id_Cargo, $nombre_cargo, $id_area_fk, $estado_cargo;

    public function __construct(
        $id_Cargo =  null,
        $nombre_cargo = null,
        $id_area_fk = null,
        $estado_cargo = null
    ) {
        $this->id_Cargo = $id_Cargo;
        $this->nombre_cargo = $nombre_cargo;
        $this->id_area_fk = $id_area_fk;
        $this->estado_cargo = $estado_cargo;
    }

    public function getId_Cargo()
    {
        return $this->id_Cargo;
    }
    public function setId_Cargo($id_Cargo)
    {
        return $this->id_Cargo = $id_Cargo;
    }
    public function getNombre_cargo()
    {
        return $this->nombre_cargo;
    }
    public function setNombre_cargo($nombre_cargo)
    {
        return $this->nombre_cargo = $nombre_cargo;
    }
    public function getId_area_fk()
    {
        return $this->id_area_fk;
    }
    public function setId_area_fk($id_area_fk)
    {
        return $this->id_area_fk = $id_area_fk;
    }

    public function getEstado_cargo()
    {
        return $this->estado_cargo;
    }

    public function setEstado_cargo($estado_cargo)
    {
        $this->estado_cargo = $estado_cargo;

        return $this;
    }
}
