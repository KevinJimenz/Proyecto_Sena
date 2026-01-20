<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./Assets/Media/Favicon.ico" type="image/x-icon">
    <!-- Bootstrap's CSS -->
    <link rel="stylesheet" href="./Assets/Css/bootstrap-icons.css">
    <link rel="stylesheet" href="./Assets/Css/bootstrap.min.css">
    <!-- Libraries'S CSS -->
    <link rel="stylesheet" href="./Libs/SweetAlert/sweetAlert.css">
    <!-- Style for the page -->
    <link rel="stylesheet" href="./Front/Assets/Css/login.style.css">
    <title>Login</title>
</head>

<body>
    <form id="form" class="form-login">
        <div class="content-img">
            <img class="mb-2 image img-fluid" src="./Assets/Media/Logo.png" alt="logo">
        </div>
        <h1 class="h3 mb-3">Ingresa tus credenciales</h1>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo Electronico" required>
            <label for="correo">Correo Electronico</label>
        </div>
        <div class="input-group mb-3">
            <div class="form-control p-0 border-end-0">
                <div class="form-floating">
                    <input type="password" class="form-control border-0 rounded-start-0 rounded-end-0" id="password" name="password" placeholder="Password">
                    <label for="password">Contraseña</label>
                </div>
            </div>
            <div class="input-group-text border-start-0">
                <button class="btn p-0 border-0" type="button" onclick="togglePassword()">
                    <i class="bi bi-eye fs-4" id="toggleIcon"></i>
                </button>
            </div>
        </div>
        <div class="form-button">
            <button type="submit" class="btn btn-success" id="iniciar-sesion">Iniciar Sesion</button>
        </div>
    </form>
    <script src="./Libs/SweetAlert/sweetAlert.js"></script>
    <script src="./Front/Assets/Js/login.js"></script>
</body>

</html>