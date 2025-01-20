<?php
class Area
{
    private $id_Area, $nombre_area;

    public function __construct(
        $id_Area = null,
        $nombre_area = null
    ) {
        $this->$id_Area = $id_Area;
        $this->nombre_area = $nombre_area;
    }

    public function setId_area($id_Area)
    {
        return $this->$id_Area = $id_Area;
    }
    public function getId_area()
    {
        return $this->id_Area;
    }
    public function setNombre_area($nombre_area)
    {
        return $this->nombre_area = $nombre_area;
    }
    public function getNombre_area()
    {
        return $this->nombre_area;
    }
}
