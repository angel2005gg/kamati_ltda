<?php
require_once '../modelo/dao/CargoDao.php';
require_once '../modelo/Cargo.php';
require_once '../controlador/ControladorCargos.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu = $_POST['menu'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if ($menu === 'cargos') {
        switch ($accion) {
            case 'Registrar':
                try {
                    $campos = [
                        'txt_nombre_cargo', 'txt_nombre_area', 'txt_estado_cargo'
                    ];
                    foreach ($campos as $campo) {
                        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                            header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                            exit;
                        }
                    }

                    $control = new ControladorCargos();
                    $cargo = new Cargo();
                    $cargoDao = new CargoDao();

                    if (!empty($_POST['txt_nombre_cargo'])) {

                        $cargo->setNombre_cargo($_POST['txt_nombre_cargo']);
                        $cargo->setId_area_fk($_POST['txt_nombre_area']);
                        $cargo->setEstado_cargo($_POST['txt_estado_cargo']);

                        if ($cargo != null) {

                            if ($cargoDao->idCargoExiste($_POST['txt_hidden_id'])) {
                                //alert
                                header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Error en el registro del cargo.'));
                                exit;
                            } else {
                                $control->controlInsertCargo($cargo);
                                header("Location: ../vista/consultaCargosAdmin.php?tipo=success&mensaje=" . urlencode('Registro de cargo exitoso.'));
                                exit;
                            }
                        } else {
                            //alert
                            header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Error en el registro del cargo.'));
                            exit;
                        }
                    } else {
                        //alert
                        header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Escribe primero el nombre del cargo nuevo.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Error en el registro del cargo.'));
                    exit;
                }


                break;
            case 'Actualizar':
                try {
                    $campos = [
                        'txt_nombre_cargo', 'txt_nombre_area', 'txt_estado_cargo'
                    ];
                    foreach ($campos as $campo) {
                        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                            header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Unos de los campos esta vacío.'));
                            exit;
                        }
                    }

                    $control = new ControladorCargos();
                    $cargo = new Cargo();
                    $cargoDao = new CargoDao();

                    if (!empty($_POST['txt_nombre_cargo'])) {

                        $cargo->setNombre_cargo($_POST['txt_nombre_cargo']);
                        $cargo->setId_area_fk($_POST['txt_nombre_area']);
                        $cargo->setEstado_cargo($_POST['txt_estado_cargo']);
                        $cargo->setId_Cargo($_POST['txt_hidden_id']);

                        if ($cargo != null) {
                            $control->controlUpdateCargo($cargo);
                            header("Location: ../vista/consultaCargosAdmin.php?tipo=success&mensaje=" . urlencode('Actualización de cargo exitosa.'));
                            exit;
                        } else {
                            header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Unos de los campos esta vacío.'));
                            exit;
                        }
                    } else {
                        //alert
                        header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Primero escribe un nombre para el cargo.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosAdmin.php?tipo=danger&mensaje=" . urlencode('Error en el registro del cargo.'));
                    exit;
                }



                break;
            case 'Regresar':
                header("Location: ../vista/DashboardAdmin.php");
                exit;

                break;
        }
    }
}
