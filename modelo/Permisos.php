<?php
class Permisos
{
    private $id_Permisos, $fecha_elaboracion, $tipo_permiso, $tiempo, $cantidad_tiempo, $fecha_inicio_novedad;
    private $fecha_fin_novedad, $dias_compensados, $cantidad_dias_compensados, $total_horas_permiso, $motivo_novedad;
    private $remuneracion, $id_Tipo_Novedad_Permiso, $id_Usuarios_permiso, $estado_permiso, $id_jefe_usuario;
    private $id_gestionHumana1, $id_gestionHumana2;

    public function __construct(
        $id_Permisos = null,
        $fecha_elaboracion = null,
        $tipo_permiso = null,
        $tiempo = null,
        $cantidad_tiempo = null,
        $fecha_inicio_novedad = null,
        $fecha_fin_novedad = null,
        $dias_compensados = null,
        $cantidad_dias_compensados = null,
        $total_horas_permiso = null,
        $motivo_novedad = null,
        $remuneracion = null,
        $id_Tipo_Novedad_Permiso = null,
        $id_Usuarios_permiso = null,
        $estado_permiso = null,
        $id_jefe_usuario = null,
        $id_gestionHumana1 = null,
        $id_gestionHumana2 = null
    ) {
        $this->id_Permisos = $id_Permisos;
        $this->fecha_elaboracion = $fecha_elaboracion;
        $this->tipo_permiso = $tipo_permiso;
        $this->tiempo = $tiempo;
        $this->cantidad_tiempo = $cantidad_tiempo;
        $this->fecha_inicio_novedad = $fecha_inicio_novedad;
        $this->fecha_fin_novedad = $fecha_fin_novedad;
        $this->dias_compensados = $dias_compensados;
        $this->cantidad_dias_compensados = $cantidad_dias_compensados;
        $this->total_horas_permiso = $total_horas_permiso;
        $this->motivo_novedad = $motivo_novedad;
        $this->remuneracion = $remuneracion;
        $this->id_Tipo_Novedad_Permiso = $id_Tipo_Novedad_Permiso;
        $this->id_Usuarios_permiso = $id_Usuarios_permiso;
        $this->estado_permiso = $estado_permiso;
        $this->id_jefe_usuario = $id_jefe_usuario;
        $this->id_gestionHumana1 = $id_gestionHumana1;
        $this->id_gestionHumana2 = $id_gestionHumana2;
    }

    public function getId_Permisos()
    {
        return $this->id_Permisos;
    }

    public function setId_Permisos($id_Permisos)
    {
        $this->id_Permisos = $id_Permisos;

        return $this;
    }

    public function getFecha_elaboracion()
    {
        return $this->fecha_elaboracion;
    }

    public function setFecha_elaboracion($fecha_elaboracion)
    {
        $this->fecha_elaboracion = $fecha_elaboracion;

        return $this;
    }

    public function getTipo_permiso()
    {
        return $this->tipo_permiso;
    }

    public function setTipo_permiso($tipo_permiso)
    {
        $this->tipo_permiso = $tipo_permiso;

        return $this;
    }

    public function getTiempo()
    {
        return $this->tiempo;
    }

    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getCantidad_tiempo()
    {
        return $this->cantidad_tiempo;
    }

    public function setCantidad_tiempo($cantidad_tiempo)
    {
        $this->cantidad_tiempo = $cantidad_tiempo;

        return $this;
    }

    public function getFecha_inicio_novedad()
    {
        return $this->fecha_inicio_novedad;
    }

    public function setFecha_inicio_novedad($fecha_inicio_novedad)
    {
        $this->fecha_inicio_novedad = $fecha_inicio_novedad;

        return $this;
    }

    public function getFecha_fin_novedad()
    {
        return $this->fecha_fin_novedad;
    }

    public function setFecha_fin_novedad($fecha_fin_novedad)
    {
        $this->fecha_fin_novedad = $fecha_fin_novedad;

        return $this;
    }

    public function getDias_compensados()
    {
        return $this->dias_compensados;
    }

    public function setDias_compensados($dias_compensados)
    {
        $this->dias_compensados = $dias_compensados;

        return $this;
    }

    public function getCantidad_dias_compensados()
    {
        return $this->cantidad_dias_compensados;
    }

    public function setCantidad_dias_compensados($cantidad_dias_compensados)
    {
        $this->cantidad_dias_compensados = $cantidad_dias_compensados;

        return $this;
    }

    public function getTotal_horas_permiso()
    {
        return $this->total_horas_permiso;
    }

    public function setTotal_horas_permiso($total_horas_permiso)
    {
        $this->total_horas_permiso = $total_horas_permiso;

        return $this;
    }

    public function getMotivo_novedad()
    {
        return $this->motivo_novedad;
    }

    public function setMotivo_novedad($motivo_novedad)
    {
        $this->motivo_novedad = $motivo_novedad;

        return $this;
    }

    public function setRemuneracion($remuneracion)
    {
        $this->remuneracion = $remuneracion;

        return $this;
    }

    public function getId_Tipo_Novedad_Permiso()
    {
        return $this->id_Tipo_Novedad_Permiso;
    }

    public function setId_Tipo_Novedad_Permiso($id_Tipo_Novedad_Permiso)
    {
        $this->id_Tipo_Novedad_Permiso = $id_Tipo_Novedad_Permiso;

        return $this;
    }

    public function getId_Usuarios_permiso()
    {
        return $this->id_Usuarios_permiso;
    }

    public function setId_Usuarios_permiso($id_Usuarios_permiso)
    {
        $this->id_Usuarios_permiso = $id_Usuarios_permiso;

        return $this;
    }


    public function getRemuneracion()
    {
        return $this->remuneracion;
    }


    public function getEstado_permiso()
    {
        return $this->estado_permiso;
    }


    public function setEstado_permiso($estado_permiso)
    {
        $this->estado_permiso = $estado_permiso;

        return $this;
    }

    public function getId_jefe_usuario()
    {
        return $this->id_jefe_usuario;
    }

    public function setId_jefe_usuario($id_jefe_usuario)
    {
        $this->id_jefe_usuario = $id_jefe_usuario;

        return $this;
    }

    public function getId_gestionHumana1()
    {
        return $this->id_gestionHumana1;
    }

    public function setId_gestionHumana1($id_gestionHumana1)
    {
        $this->id_gestionHumana1 = $id_gestionHumana1;

        return $this;
    }

    public function getId_gestionHumana2()
    {
        return $this->id_gestionHumana2;
    }

    public function setId_gestionHumana2($id_gestionHumana2)
    {
        $this->id_gestionHumana2 = $id_gestionHumana2;

        return $this;
    }
}
