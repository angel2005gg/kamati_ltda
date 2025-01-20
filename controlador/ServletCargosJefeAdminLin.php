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
                            header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
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
                                header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('No puedes registrar un cargo ya existente.'));
                                exit;
                            } else {
                                $control->controlInsertCargo($cargo);
                                header("Location: ../vista/consultaCargosLin.php?tipo=success&mensaje=" . urlencode('Registro exitoso.'));
                                exit;
                            }
                        } else {
                            header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('No se registró correctamente.'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('Hay campos vacíos.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('No se registró correctamente.'));
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
                            header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
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
                            header("Location: ../vista/consultaCargosLin.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                            exit;
                        } else {
                            //alert
                            header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('No se actualizó.'));
                            exit;
                        }
                    } else {
                        //alert
                        header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('Hay campos vacíos.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/consultaCargosLin.php?tipo=danger&mensaje=" . urlencode('No se actualizó.'));
                    exit;
                }



                break;
            case 'Regresar':
                header("Location: ../vista/DashboardJefeAdminLin.php");
                exit;

                break;
        }
    }
}
