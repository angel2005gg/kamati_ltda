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
                            header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
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
                                header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('No puedes registrar un cargo ya existente.'));
                                exit;
                            } else {
                                $control->controlInsertCargo($cargo);
                                header("Location: ../vista/consultaCargosL.php?tipo=success&mensaje=" . urlencode('Registro de cargo exitosa.'));
                                exit;
                            }
                        } else {
                            //alert
                            header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Registro de cargo fallida.'));
                            exit;
                        }
                    } else {
                        //alert
                        header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Registro de cargo fallida.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Registro de cargo fallida.'));
                    exit;
                }


                break;
            case 'Actualizar':
                try {

                    if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                        $campos = [
                            'txt_nombre_cargo', 'txt_nombre_area', 'txt_estado_cargo'
                        ];
                        foreach ($campos as $campo) {
                            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
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
                                header("Location: ../vista/consultaCargosL.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                                exit;
                            }
                        } else {
                            //alert
                            header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Actualización de cargo fallida.'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Actualización de cargo fallida.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosL.php?tipo=danger&mensaje=" . urlencode('Actualización de cargo fallida.'));
                    exit;
                }

                break;
            case 'Regresar':
                header("Location: ../vista/DashboardJefeAdminL.php");
                exit;

                break;
        }
    }
}
