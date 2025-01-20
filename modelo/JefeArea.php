<?php

class JefeArea
{

    private $id_Jefe_Area, $id_Area_Jefe_Inmediato, $id_Jefe_Usuario;

    public function __construct($id_Jefe_Area = null,$id_Area_Jefe_Inmediato = null,$id_Jefe_Usuario = null)
    {
        $this->id_Jefe_Area = $id_Jefe_Area;
        $this->id_Area_Jefe_Inmediato = $id_Area_Jefe_Inmediato;
        $this->id_Jefe_Usuario = $id_Jefe_Usuario;
    }

    public function getId_Jefe_Area()
    {
        return $this->id_Jefe_Area;
    }

    public function setId_Jefe_Area($id_Jefe_Area)
    {
        $this->id_Jefe_Area = $id_Jefe_Area;

        return $this;
    }
 
    public function getId_Area_Jefe_Inmediato()
    {
        return $this->id_Area_Jefe_Inmediato;
    }

    public function setId_Area_Jefe_Inmediato($id_Area_Jefe_Inmediato)
    {
        $this->id_Area_Jefe_Inmediato = $id_Area_Jefe_Inmediato;

        return $this;
    }

    public function getId_Jefe_Usuario()
    {
        return $this->id_Jefe_Usuario;
    }

    public function setId_Jefe_Usuario($id_Jefe_Usuario)
    {
        $this->id_Jefe_Usuario = $id_Jefe_Usuario;

        return $this;
    }
}
