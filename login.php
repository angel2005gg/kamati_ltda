<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Link de CSS personalizado -->
    <link rel="icon" type="image/png" href="img/logo.png">   
    <link rel="stylesheet" href="css/styleNew.css">
    <title>Inicio_De_Sesion</title>
</head>
<body>
    <section class="contenedor_section">
        <form id="loginForm" method="POST">
            <div class="formulario_inicio_sesion">
                <h2>Inicio de Sesión</h2>
                <br>
                <br>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="identificacion" placeholder="Ej: 1234567890" name="txt_identificacion">
                    <label for="identificacion">Identificación</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="contrasena" placeholder="Contraseña" name="txt_contrasena">
                    <label for="contrasena">Contraseña</label>
                </div>
                <br>
                <br>
                <br>
                <input type="submit" class="ingreso_submit" value="Ingresar">
            </div>
            <div id="alertContainer"></div>
        </form>
    </section>

    <script src="js/login.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>