<?php

class FactoresAdicionales
{

    private $id_Factores, $id_CotizacionesComercialFK, $factores, $valorFactor;

    public function __construct($id_Factores = null, $valorFactor = null, $factores = null, $id_CotizacionesComercialFK = null)
    {
        $this->valorFactor = $valorFactor;
        $this->id_Factores = $id_Factores;
        $this->factores = $factores;
        $this->id_CotizacionesComercialFK = $id_CotizacionesComercialFK;
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

    public function getId_CotizacionesComercialFK()
    {
        return $this->id_CotizacionesComercialFK;
    }

    public function setId_CotizacionesComercialFK($id_CotizacionesComercialFK)
    {
        $this->id_CotizacionesComercialFK = $id_CotizacionesComercialFK;

        return $this;
    }
}
