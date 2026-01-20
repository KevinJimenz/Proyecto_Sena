<?php
session_start();
if ($_SESSION == true) {

?>
    <!DOCTYPE html>
    <html lang="es" data-bs-theme="auto">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../../Assets/Media/Favicon.ico" type="image/x-icon">
        <!-- Bootstrap's CSS -->
        <link rel="stylesheet" href="../../../Assets/Css/bootstrap-icons.css">
        <link rel="stylesheet" href="../../../Assets/Css/bootstrap.min.css">
        <!-- Libraries'S CSS -->
        <link rel="stylesheet" href="../../../Libs/SweetAlert/sweetAlert.css">
        <!-- Style for the page -->
        <link rel="stylesheet" href="../../Assets/Css/dashboard-users.style.css">
        <title> | Inicio</title>
    </head>

    <body>
        <nav class="navbar-header">
            <div class="img">
                <img class="image img-fluid" src="../../../Assets/Media/Logo.png" alt="Logo">
            </div>
            <div class="navbar-navegation">
                <a class="btn current-page" href="../User/dashboard-inicio.php">Inicio</a>
                <a class="btn" href="../User/dashboard-registros.php">Registros</a>
            </div>
            <div class="detail-user">
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex gap-2 align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <?= htmlspecialchars($_SESSION['usuario']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item change-password" data-bs-toggle="modal" data-bs-target="#cambiar-password" style="cursor: pointer;">
                                <i class="bi bi-key-fill">
                                    Cambiar Contraseña
                                </i>
                            </a>
                            <a class="dropdown-item log-out" href="../../../Back/Controllers/Global/cerrar-sesion.php">
                                <i class="bi bi-door-open-fill">
                                    Cerrar Sesion
                                </i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="row mb-3">
                <div class="col-5">
                    <div class="form-floating">
                        <select class="form-select" name="" id="select-eventos">
                            <option value="0"></option>
                            <option value="1">Eventos de 1 hora</option>
                            <option value="2">Eventos de 2 horas</option>
                            <option value="3">Eventos de 6 horas</option>
                        </select>
                        <label for="select-eventos" class="form-label">Seleccione el <strong>Tipo de Evento</strong>:</label>
                    </div>
                </div>
                <div class="col-7">
                    <div class="d-flex justify-content-end gap-3">
                        <div>
                            <button type="button" id="agregar-registro" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
                                <i class="bi bi-plus fs-4"></i>
                                Agregar Registro
                            </button>
                        </div>
                        <div>
                            <button type="button" id="eliminar-registro" class="btn btn-danger d-flex justify-content-center align-items-center gap-2">
                                <i class="bi bi-x fs-4"></i>
                                Quitar Registro
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form-crear">
                <div id="contenedor-main" class="contenedor-main" style="display: none;">

                    <div id="registros" class="registros"></div>

                    <div id="boton-guardar" class="d-flex justify-content-end" style="display: none;">
                        <button type="button" id="guardar-registro" class="btn btn-success">
                            Guardar Registro
                        </button>
                    </div>

                </div>
            </form>
        </main>

        <footer>
            <div class="d-flex flex-row gap-2">
                <p style="color:rgb(192, 192, 192);">Website developed by</p>
                <a class="d-flex gap-2" href="https://github.com/KevinJimenz" style="text-decoration: none; color:black;">
                    <i class="bi bi-github"></i>
                    Kevin Jimenez
                    &#169 2025
                </a>
            </div>
        </footer>
        <!-- Modal Cambiar Contraseña -->
        <div class="modal fade" id="cambiar-password" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Cambiar Contraseña</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form">

                            <div class="input-group mb-3">
                                <div class="input-group mb-3">
                                    <div class="form-control p-0 border-end-0">
                                        <div class="form-floating">
                                            <input type="password" class="form-control border-0 rounded-start-0 rounded-end-0" id="old-password" name="old-password" placeholder="Old Password">
                                            <label for="old-password">Contraseña Anterior</label>
                                        </div>
                                    </div>
                                    <div class="input-group-text border-start-0">
                                        <button class="btn p-0 border-0" type="button">
                                            <i class="bi bi-eye fs-4" id="old-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group mb-3">
                                    <div class="form-control p-0 border-end-0">
                                        <div class="form-floating">
                                            <input type="password" class="form-control border-0 rounded-start-0 rounded-end-0" id="new-password" name="new-password" placeholder="New Password">
                                            <label for="new-password">Nueva Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="input-group-text border-start-0">
                                        <button class="btn p-0 border-0" type="button">
                                            <i class="bi bi-eye fs-4" id="new-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="confirmar-cambio">Cambiar Contraseña</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../../Assets/Js/jquery.js"></script>
        <!-- Bootstrap's JS -->
        <script src="../../../Assets/Js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert's JS -->
        <script src="../../../Libs/SweetAlert/sweetAlert.js"></script>
        <!-- Js for the page -->
        <script type="module" src="../../Assets/Js/user-inicio.js"></script>
    </body>

    </html>
<?php
} else {
    header('Location: http://localhost/Proyecto_Wilfred'); // Cambiar URL
}
?>