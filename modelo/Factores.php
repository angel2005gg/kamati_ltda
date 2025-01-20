<?php

class Factores
{

    private $id_Factores, $factores, $valorFactor;

    public function __construct($id_Factores = null, $valorFactor = null, $factores = null)
    {
        $this->valorFactor = $valorFactor;
        $this->id_Factores = $id_Factores;
        $this->factores = $factores;
    }

    public function getValorFactor()
    {
        return $this->valorFactor;
    }

    public function setValorFactor($valorFactor)
    {
        $this->valorFactor = $valorFactor;

        return $this;
    }

    public function getFactores()
    {
        return $this->factores;
    }

    public function setFactores($factores)
    {
        $this->factores = $factores;

        return $this;
    }


    public function getId_Factores()
    {
        return $this->id_Factores;
    }

    public function setId_Factores($id_Factores)
    {
        $this->id_Factores = $id_Factores;

        return $this;
    }
}
