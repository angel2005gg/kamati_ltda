<?php

require_once '../modelo/dao/VariablesDao.php';
require_once '../modelo/Variables.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu = $_POST['menu'] ?? '';

    $variablesDao = new VariablesDao();
    $variables = new Variables();

    if ($menu === 'variables') {
        if (isset($_POST['btn_update_num1'])) {
            $horaSemanal = ($_POST['txt_nombre_cargo0']);

            if ($horaSemanal != null && $_POST['txt_nombre_cargo0'] != 0) {
                $variables->setValor_variable($horaSemanal);
                $variables->setId_Variables($_POST['txt_hidden_btn1']);
                if ($variables != null) {
                    $variablesDao->updateVariables($variables);
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=success&mensaje=" . urlencode('Actualización exitosa de las horas laborales semanales.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida de las horas laborales semanales.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida de las horas laborales semanales.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num2'])) {
            $salarioMensual = ($_POST['txt_nombre_cargo1']);

            if ($salarioMensual != null && $_POST['txt_nombre_cargo1'] != 0) {
                $variables->setValor_variable($salarioMensual);
                $variables->setId_Variables($_POST['txt_hidden_btn2']);
                if ($variables != null) {
                    $variablesDao->updateVariables($variables);
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=success&mensaje=" . urlencode('Actualización exitosa del salario mínimo.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida del salario mínimo.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida del salario mínimo.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num3'])) {
            $horasDia = ($_POST['txt_nombre_cargo2']);

            if ($horasDia != null && $_POST['txt_nombre_cargo2'] != 0) {
                $variables->setValor_variable($horasDia);
                $variables->setId_Variables($_POST['txt_hidden_btn3']);
                if ($variables != null) {
                    $variablesDao->updateVariables($variables);
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=success&mensaje=" . urlencode('Actualización exitosa de las horas diarias laborales.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida de las horas diarias laborales.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida de las horas diarias laborales.'));
                exit;
            }
        } else {
            header("Location: ../vista/variablesL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
            exit;
        }
    }else{
        return false;
    }
}else{
    return false;
}
