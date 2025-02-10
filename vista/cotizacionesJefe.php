<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
require_once '../modelo/dao/UsuariosDao.php';
require_once '../modelo/dao/CargoDao.php';
$cargosDao = new CargoDao();
$nombre_cargo = $cargosDao->consultarCargo();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleKamaInitrAp.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Consulta usuarios</title>
</head>

<body>
    <?php
    include 'navBar.php';

    $users = new UsuariosDao();
    $data = $users->consultarUsuarios();
    ?>
    <br>
    <div id="container">
        <div id="alertContainer"></div>
        <div id="alertContainerLimpiar"></div>
        <div class="container_table">
            <br><br><br>
            <h2>Listado de usuarios</h2>
            <br>


            <div class="form-floating tma">
                <input type="text" class="form-control" name="txt_filtro_nombre" id="txt_filtro_nombre" placeholder="">
                <label for="floatingInput">Ej Oscar...</label>
            </div>

            <div class="tabla_solicitudes">
                <br>
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Primer nombre</th>
                            <th scope="col">Segundo nombre</th>
                            <th scope="col">Primer apellido</th>
                            <th scope="col">Segundo&nbsp;apellido</th>
                            <th scope="col">Identificación</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono&nbsp;móvil</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Sede</th>
                            <th scope="col">Area</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['primer_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['segundo_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['primer_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['segundo_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['numero_identificacion']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                                <td><?php echo htmlspecialchars($row['numero_telefono_movil']); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion_residencia']); ?></td>
                                <td><?php echo htmlspecialchars($row['sede_laboral']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_area']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_cargo']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_usuario']); ?></td>
                                <td><button type="button" class="button_color seleccionar" data-id="<?php echo htmlspecialchars($row['numero_identificacion']); ?>">Seleccionar</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <br><br>
        </div>
        <form method="POST" action="../controlador/Ser.php">
            <div class="container">
                <h2>Actualización de Usuarios</h2>
                <br><br>
                <div class="row g-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_primerNombre" id="primerNombre" placeholder="">
                            <label for="floatingInput">Primer Nombre *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_segundoNombre" id="segundoNombre" placeholder="">
                            <label for="floatingPassword">Segundo Nombre</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3 col">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_primerApellido" id="primerApellido" placeholder="">
                            <label for="floatingInput">Primer Apellido *</label>
                        </div>
                    </div>
                </div>
                <br>
                <br>


                <div class="row g-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_segundoApellido" id="segundoApellido" placeholder="">
                            <label for="">Segundo Apellido *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control limpiar" name="txt_numeroIdentificacion" id="numeroIdentificacion" placeholder="">
                            <label for="floatingNumber">Número Identificación *</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3 col">
                        <div class="form-floating">
                            <input type="email" class="form-control limpiar" name="txt_correoElectronico" id="correoElectronico" placeholder="">
                            <label for="floatingInput">Correo Electrónico *</label>
                        </div>
                    </div>
                </div>
                <br>
                <br>


                <div class="row g-3">

                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control limpiar" name="txt_telefonoMovil" id="numeroTelefonoMovil" placeholder="">
                            <label for="floatingInput">Teléfono Móvil *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_direccionResidencia" id="direccionResidencia" placeholder="">
                            <label for="floatingInput">Dirección residencia *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <select class="form-select limpiar" name="txt_sede" id="sedeLaboral">
                                <option selected>Selecciona la sede laboral</option>
                                <option value="Cali">Cali</option>
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
                            <select class="form-select limpiar" name="idcargo_area" id="idcargo_area">
                                <option selected>Selecciona el cargo</option>
                                <?php foreach ($nombre_cargo as $id => $nombre) : ?>
                                    <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($nombre); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="floatingSelectGrid">Cargo *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <select class="form-select limpiar" name="select_estado_user" id="select_estado_user">
                                <option selected>Selecciona el estado</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                            <label for="floatingSelectGrid">Estado usuario</label>
                        </div>
                    </div>
                </div>

                <br><br>
                <div class="row g-3">
                    <div class="col-md">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="cambio_contrasena" name="cambio_contrasena" value='1'>
                            <label class="form-check-label" for="cambio_contrasena">Cambiar contraseña del usuario</label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="txt_contrasena" id="txt_contrasena" placeholder="" disabled>
                                <label for="floatingPassword">Contraseña *</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3 col">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="txt_verificarContrasena" id="txt_verificarContrasena" placeholder="" disabled>
                                <label for="floatingPassword">Verificar contraseña *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="row g-3">


                        <br><br>
                        <div class="col-md centro">
                            <div class="form-floating">
                                <input type="button" class="ingreso_submit" name="limpiar" id="limpiar_campos" value="Limpiar">
                                <input type="hidden" name="menu" value="actualizar">
                                <input type="hidden" id="hiddenContrasenaVerificacion" name="hiddenContrasenaVerificacion" value="0">
                                <input type="submit" class="ingreso_submit" name="accion" value="Actualizar">
                                <input type="submit" id="submitContrasena" class="ingreso_submit" name="accion" value="Cambiar" style="background-color: red;" disabled>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <br><br>
    <script src="../js/JavaScriptSeleccionar.js"></script>
    <script src="../js/alertsKam.js"></script>
    <script src="../js/verificarCheckContrasena.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>