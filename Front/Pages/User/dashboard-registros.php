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
        <!-- DataTables's CSS -->
        <link rel="stylesheet" href="../../../Assets/Css/dataTables.bootstrap5.css">
        <link rel="stylesheet" href="../../../Assets/Css/responsive.bootstrap5.css">
        <!-- SweetAlert's CSS -->
        <link rel="stylesheet" href="../../../Libs/SweetAlert/sweetAlert.css">
        <!-- Style for the page -->
        <link rel="stylesheet" href="../../Assets/Css/dashboard-users.style.css">
        <title> | Registro de horas</title>
    </head>

    <body>
        <nav class="navbar-header">
            <div class="img">
                <img class="image img-fluid" src="../../../Assets/Media/Logo.png" alt="Logo">
            </div>
            <div class="navbar-navegation">
                <a class="btn" href="../User/dashboard-inicio.php">Inicio</a>
                <a class="btn current-page" href="../User/dashboard-registros.php">Registros</a>
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

        <div class="informacion-horas">
            <div class="subtitle">
                <p style="font-size:22px;">Cantidad de Horas <strong>Evento de 1 Hora: </strong></p>
                <p style="font-size:22px;" id="evento_1"></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;"><strong>-</strong></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;">Cantidad de Horas <strong>Evento de 2 Horas: </strong></p>
                <p style="font-size:22px;" id="evento_2"></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;"><strong>-</strong></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;"> Cantidad de Horas <strong>Evento de 6 Horas: </strong></p>
                <p style="font-size:22px;" id="evento_3"></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;"><strong>-</strong></p>
            </div>
            <div class="subtitle">
                <p style="font-size:22px;">Total de <strong>Horas: </strong></p>
                <p style="font-size:22px;" id="total_horas"></p>
            </div>
        </div>

        <main>
            <div class="table-responsive">
                <table id="tabla_registros" class="table table-striped" style="width:100%; font-size:20px;">
                </table>
            </div>
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
                                <button type="submit" class="btn btn-primary" id="confirmar-cambio-password">Cambiar Contraseña</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar -->
        <div class="modal fade" id="modal-editar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Editar registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-editar">
                            <div id="contenedor-editar">

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="confirmar-edicion">Editar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar -->
        <div class="modal fade" id="modal-eliminar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Eliminar registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-eliminar">
                            <div id="contenedor-eliminar">

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger" id="confirmar-eliminacion">Eliminar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../../Assets/Js/jquery.js"></script>
        <!-- Bootstrap's JS -->
        <script src="../../../Assets/Js/bootstrap.bundle.min.js"></script>
        <!-- DataTables's JS -->
        <script src="../../../Assets/Js/dataTables.js"></script>
        <script src="../../../Assets/Js/dataTables.bootstrap5.js"></script>
        <script src="../../../Assets/Js/dataTables.responsive.js"></script>
        <script src="../../../Assets/Js/responsive.bootstrap5.js"></script>
        <!-- SweetAlert's JS -->
        <script src="../../../Libs/SweetAlert/sweetAlert.js"></script>
        <!-- Js for the page -->
        <script type="module" src="../../Assets/Js/user-registros.js"></script>
    </body>

    </html>
<?php
} else {
    header('Location: http://localhost/Proyecto_Wilfred'); // Cambiar URL
}
?>