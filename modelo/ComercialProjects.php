<?php

class ComercialProjects
{
    //Atributos privados de la clase
    private $id_Cotizacion, $nombre_cotizacion, $fecha_creacion, $codigo_cotizacion, $nombreCliente, $fechaActual, $dolarCotizacion, $euroCotizacion;

    //Método constructor de la clase
    public function __construct($id_Cotizacion = null, $nombre_cotizacion = null, $fecha_creacion = null, $codigo_cotizacion = null, $nombreCliente = null, $fechaActual = null, $dolarCotizacion = null, $euroCotizacion = null)
    {
        $this->id_Cotizacion = $id_Cotizacion;
        $this->nombre_cotizacion = $nombre_cotizacion;
        $this->fecha_creacion = $fecha_creacion;
        $this->codigo_cotizacion = $codigo_cotizacion;
        $this->nombreCliente = $nombreCliente;
        $this->fechaActual = $fechaActual;
        $this->dolarCotizacion = $dolarCotizacion;
        $this->euroCotizacion = $euroCotizacion;
    }

    //Métodos de sett y gett de la clase
    public function getId_Cotizacion()
    {
        return $this->id_Cotizacion;
    }

    public function setId_Cotizacion($id_Cotizacion)
    {
        $this->id_Cotizacion = $id_Cotizacion;

        return $this;
    }

    public function getNombre_cotizacion()
    {
        return $this->nombre_cotizacion;
    }


    public function setNombre_cotizacion($nombre_cotizacion)
    {
        $this->nombre_cotizacion = $nombre_cotizacion;

        return $this;
    }

    public function getFecha_creacion()
    {
        return $this->fecha_creacion;
    }


    public function setFecha_creacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getCodigo_cotizacion()
    {
        return $this->codigo_cotizacion;
    }


    public function setCodigo_cotizacion($codigo_cotizacion)
    {
        $this->codigo_cotizacion = $codigo_cotizacion;

        return $this;
    }

    public function getNombreCliente()
    {
        return $this->nombreCliente;
    }

    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    public function getFechaActual()
    {
        return $this->fechaActual;
    }

    public function setFechaActual($fechaActual)
    {
        $this->fechaActual = $fechaActual;

        return $this;
    }

    public function getDolarCotizacion()
    {
        return $this->dolarCotizacion;
    }

    public function setDolarCotizacion($dolarCotizacion)
    {
        $this->dolarCotizacion = $dolarCotizacion;

        return $this;
    }

    public function getEuroCotizacion()
    {
        return $this->euroCotizacion;
    }

    public function setEuroCotizacion($euroCotizacion)
    {
        $this->euroCotizacion = $euroCotizacion;

        return $this;
    }
}
