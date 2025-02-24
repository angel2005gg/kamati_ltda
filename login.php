<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="img/logo.png">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .contenedor_section {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .formulario_inicio_sesion {
            position: relative;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .logo {
            position: absolute;
            top: -40px;
            left: 0;
            width: 100%;
            height: 80px;
            object-fit: contain;
        }

        .ingreso_submit {
            background-color: #002d4b;
            color: white;
            border: none;
            border-radius: 10px;
            width: 100%;
            height: 45px;
            font-size: 18px;
            transition: background 0.3s;
        }

        .ingreso_submit:hover {
            background-color: #004a75;
        }

        .form-floating input {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <section class="contenedor_section">
        <form id="loginForm" method="POST">
            <div class="formulario_inicio_sesion">
                <img src="img/logo.png" alt="Logo" class="logo">
                <h2>Inicio de Sesión</h2>
                <br>
                <br>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="identificacion" placeholder="Ej: 1234567890" name="txt_identificacion" required>
                    <label for="identificacion">Identificación</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="contrasena" placeholder="Contraseña" name="txt_contrasena" required>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
