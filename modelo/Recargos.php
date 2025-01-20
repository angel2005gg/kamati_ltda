<?php

class Recargos
{

    private $idRecargos, $nombreRecargo, $valorRecargo;


    public function __construct($idRecargos = null, $nombreRecargo = null, $valorRecargo = null)
    {
        $this->idRecargos = $idRecargos;
        $this->nombreRecargo = $nombreRecargo;
        $this->valorRecargo = $valorRecargo;
    }


    public function getIdRecargos()
    {
        return $this->idRecargos;
    }


    public function setIdRecargos($idRecargos)
    {
        $this->idRecargos = $idRecargos;

        return $this;
    }


    public function getNombreRecargo()
    {
        return $this->nombreRecargo;
    }


    public function setNombreRecargo($nombreRecargo)
    {
        $this->nombreRecargo = $nombreRecargo;

        return $this;
    }


    public function getValorRecargo()
    {
        return $this->valorRecargo;
    }


    public function setValorRecargo($valorRecargo)
    {
        $this->valorRecargo = $valorRecargo;

        return $this;
    }
}
