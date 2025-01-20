<?php

class Abreviaturas
{

    private $abreviaturas, $nombre_abreviatura;

    public function __construct(
        $abreviaturas = null,
        $nombre_abreviatura = null
    ) {
        $this->$abreviaturas = $abreviaturas;
        $this->$nombre_abreviatura = $nombre_abreviatura;
    }


    public function getNombre_abreviatura()
    {
        return $this->nombre_abreviatura;
    }

    public function setNombre_abreviatura($nombre_abreviatura)
    {
        $this->nombre_abreviatura = $nombre_abreviatura;

        return $this;
    }

    public function getAbreviaturas()
    {
        return $this->abreviaturas;
    }

    public function setAbreviaturas($abreviaturas)
    {
        $this->abreviaturas = $abreviaturas;

        return $this;
    }
}
