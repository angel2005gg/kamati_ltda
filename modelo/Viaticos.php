<?php
class Viaticos
{
    private $id_Viaticos, $nombre_viatico, $valor_diario;


    public function __construct($nombre_viatico = null, $valor_diario = null, $id_Viaticos = null)
    {
        $this->nombre_viatico = $nombre_viatico;
        $this->valor_diario = $valor_diario;
        $this->id_Viaticos = $id_Viaticos;
    }


    public function getNombre_viatico()
    {
        return $this->nombre_viatico;
    }


    public function setNombre_viatico($nombre_viatico)
    {
        $this->nombre_viatico = $nombre_viatico;

        return $this;
    }


    public function getValor_diario()
    {
        return $this->valor_diario;
    }

    public function setValor_diario($valor_diario)
    {
        $this->valor_diario = $valor_diario;

        return $this;
    }

    public function getId_Viaticos()
    {
        return $this->id_Viaticos;
    }

    public function setId_Viaticos($id_Viaticos)
    {
        $this->id_Viaticos = $id_Viaticos;

        return $this;
    }
}
