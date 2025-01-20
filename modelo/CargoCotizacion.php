<?php
class CargoCotizacion
{
    private $id_CargoCotizacion, $nombre_cargo_cotizacion, $estado_cargo_cotizacion, $valor_dia;


    public function __construct(
        $id_CargoCotizacion = null,
        $nombre_cargo_cotizacion = null,
        $estado_cargo_cotizacion = null,
        $valor_dia = null
    ) {
        $this->id_CargoCotizacion = $id_CargoCotizacion;
        $this->nombre_cargo_cotizacion = $nombre_cargo_cotizacion;
        $this->estado_cargo_cotizacion = $estado_cargo_cotizacion;
        $this->valor_dia = $valor_dia;
    }



    public function getId_CargoCotizacion()
    {
        return $this->id_CargoCotizacion;
    }


    public function setId_CargoCotizacion($id_CargoCotizacion)
    {
        $this->id_CargoCotizacion = $id_CargoCotizacion;

        return $this;
    }

    public function getNombre_cargo_cotizacion()
    {
        return $this->nombre_cargo_cotizacion;
    }


    public function setNombre_cargo_cotizacion($nombre_cargo_cotizacion)
    {
        $this->nombre_cargo_cotizacion = $nombre_cargo_cotizacion;

        return $this;
    }

    public function getEstado_cargo_cotizacion()
    {
        return $this->estado_cargo_cotizacion;
    }


    public function setEstado_cargo_cotizacion($estado_cargo_cotizacion)
    {
        $this->estado_cargo_cotizacion = $estado_cargo_cotizacion;

        return $this;
    }

    public function getValor_dia()
    {
        return $this->valor_dia;
    }


    public function setValor_dia($valor_dia)
    {
        $this->valor_dia = $valor_dia;

        return $this;
    }
}
