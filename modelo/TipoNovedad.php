<?php
class TipoNovedad
{
    private int $id_TipoNovedad;
    private String $codigo_novedad, $nombre_novedad;

    public function __construct(
        int $id_TipoNovedad = null,
        String $codigo_novedad = null
    ) {
        $this->id_TipoNovedad = $id_TipoNovedad;
        $this->codigo_novedad = $codigo_novedad;
    }

    public function getId_TipoNovedad()
    {
        return $this->id_TipoNovedad;
    }

    public function setId_TipoNovedad($id_TipoNovedad)
    {
        $this->id_TipoNovedad = $id_TipoNovedad;

        return $this;
    }

    public function getCodigo_novedad()
    {
        return $this->codigo_novedad;
    }

    public function setCodigo_novedad($codigo_novedad)
    {
        $this->codigo_novedad = $codigo_novedad;

        return $this;
    }
}
