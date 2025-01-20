<?php
class Variables{

        private $id_Variables;
        private $nombre_variables;
        private $valor_variable;

        public function __construct(
                $id_Variables = null,
                $nombre_variables = null,
                $valor_variable = null
        ) {
                $this->id_Variables = $id_Variables;
                $this->nombre_variables = $nombre_variables;
                $this->valor_variable = $valor_variable;
        }

        public function getId_Variables()
        {
                return $this->id_Variables;
        }

        public function setId_Variables($id_Variables)
        {
                $this->id_Variables = $id_Variables;

                return $this;
        }

        public function getNombre_variables()
        {
                return $this->nombre_variables;
        }

        public function setNombre_variables($nombre_variables)
        {
                $this->nombre_variables = $nombre_variables;

                return $this;
        }

        public function getValor_variable()
        {
                return $this->valor_variable;
        }

        public function setValor_variable($valor_variable)
        {
                $this->valor_variable = $valor_variable;

                return $this;
        }
}
