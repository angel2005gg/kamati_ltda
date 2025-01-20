<?php

class HorasJornada
{

    private $idHoras, $nombreJornada, $horaJornada;


    public function __construct($idHoras = null, $nombreJornada = null, $horaJornada = null)
    {
        $this->idHoras = $idHoras;
        $this->nombreJornada = $nombreJornada;
        $this->horaJornada = $horaJornada;
    }

    public function getIdHoras()
    {
        return $this->idHoras;
    }


    public function setIdHoras($idHoras)
    {
        $this->idHoras = $idHoras;

        return $this;
    }


    public function getNombreJornada()
    {
        return $this->nombreJornada;
    }


    public function setNombreJornada($nombreJornada)
    {
        $this->nombreJornada = $nombreJornada;

        return $this;
    }


    public function getHoraJornada()
    {
        return $this->horaJornada;
    }


    public function setHoraJornada($horaJornada)
    {
        $this->horaJornada = $horaJornada;

        return $this;
    }
}
