<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
require_once "../modelo/dao/CargoDao.php";
require_once "../modelo/Cargo.php";
require_once "../modelo/Usuarios.php";
require_once "../modelo/dao/RolUsuarioDao.php";
require_once "../modelo/dao/JefeAreaDao.php";

$cargosDao = new CargoDao();
$nombre_cargo = $cargosDao->consultarCargo();
$rolesDao = new RolUsuarioDao();
$nombre_rol = $rolesDao->consultaRoles();



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/styleKamaInitrAp.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="../js/javaScriptNewCo.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="../js/JavaScriptJss.js" defer></script>
    <title>DashBoard</title>
</head>

<body>

    <?php include 'navBarJefeAdminL.php' ?>
    <br><br><br><br>
    <section>
        <div id="alertContainer"></div>
        <div class="container">


            <form method="POST" action="../controlador/ServletJefeAdminL.php">
                <div class="container">
                    <h2>Registro de Usuarios</h2>
                    <br><br>
                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_primerNombre" id="" placeholder="">
                                <label for="floatingInput">Primer Nombre *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_segundoNombre" id="" placeholder="">
                                <label for="floatingPassword">Segundo Nombre</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3 col">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_primerApellido" id="" placeholder="">
                                <label for="floatingInput">Primer Apellido *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>


                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_segundoApellido" id="" placeholder="">
                                <label for="">Segundo Apellido *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="txt_numeroIdentificacion" id="" placeholder="">
                                <label for="floatingNumber">Número Identificación *</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3 col">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="txt_correoElectronico" id="" placeholder="">
                                <label for="floatingInput">Correo Electrónico *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>


                    <div class="row g-3">

                        <div class="col-md">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="txt_telefonoMovil" id="" placeholder="">
                                <label for="floatingInput">Teléfono Móvil *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_direccionResidencia" id="" placeholder="">
                                <label for="floatingInput">Dirección residencia *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" name="txt_sede" id="">
                                    <option selected>Selecciona la sede laboral</option>
                                    <option value="Yumbo">Yumbo</option>
                                    <option value="Bogota">Bogota</option>
                                </select>
                                <label for="floatingSelectGrid">Sede Laboral *</label>
                            </div>
                        </div>

                    </div>
                    <br>
                    <br>

                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" name="idcargo_area" id="idcargo_area">
                                    <?php foreach ($nombre_cargo as $id => $nombre) : ?>
                                        <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="floatingSelectGrid">Cargo *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="txt_contrasena" id="" placeholder="">
                                <label for="floatingPassword">Contraseña *</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3 col">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="txt_verificarContrasena" id="" placeholder="">
                                <label for="floatingPassword">Verificar contraseña *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" name="txt_rolUsuario" id="txt_rolUsuario">
                                    <?php foreach ($nombre_rol as $id_rol => $nombre_rol) : ?>
                                        <option value="<?php echo $id_rol; ?>"><?php echo $nombre_rol; ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <label for="floatingSelectGrid">Rol *</label>
                            </div>
                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="id_cargos_seleccionados[]" value='1'>
                                <label class="form-check-label" for="inlineCheckbox1">Ingeniería</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="id_cargos_seleccionados[]" value='2'>
                                <label class="form-check-label" for="inlineCheckbox2">Administración</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="id_cargos_seleccionados[]" value='3'>
                                <label class="form-check-label" for="inlineCheckbox3">Almacén</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox4" name="id_cargos_seleccionados[]" value='4'>
                                <label class="form-check-label" for="inlineCheckbox4">Comercial</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox5" name="id_cargos_seleccionados[]" value='5'>
                                <label class="form-check-label" for="inlineCheckbox5">Compras</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox6" name="id_cargos_seleccionados[]" value='6'>
                                <label class="form-check-label" for="inlineCheckbox6">Contabilidad</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox7" name="id_cargos_seleccionados[]" value='7'>
                                <label class="form-check-label" for="inlineCheckbox7">Gerencia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox8" name="id_cargos_seleccionados[]" value='8'>
                                <label class="form-check-label" for="inlineCheckbox8">Gestion Humana</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox9" name="id_cargos_seleccionados[]" value='9'>
                                <label class="form-check-label" for="inlineCheckbox9">Instalaciones Electricas</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox10" name="id_cargos_seleccionados[]" value='10'>
                                <label class="form-check-label" for="inlineCheckbox10">SHEQ</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" name="selectinmediato" id="selectinmediato">

                                </select>
                                <label for="floatingSelectGrid">Jefe inmediato *</label>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="col-md centro">
                        <div class="form-floating">
                            <input type="hidden" name="menu" value="registro">
                            <input type="submit" class="ingreso_submit" name="accion" value="Registrar">
                            <input type="submit" class="ingreso_submit" name="accion" value="Regresar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/alertsKam.js"></script>

</body>

</html>