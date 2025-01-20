<?php

class EmailSoftware{

    private $correo, $clave;

    public function __construct($correo = null, $clave = null)
    {
        $this->correo = $correo;
        $this->clave = $clave;
    }



    public function getCorreo()
    {
        return $this->correo;
    }


    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

 
    public function getClave()
    {
        return $this->clave;
    }

  
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }
}