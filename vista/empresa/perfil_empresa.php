<?php require_once('validar_sesion.php'); ?>
<?php require('conexion.php'); ?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>perfil</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">



    <!-- Page Wrapper  <sup>2</sup>-->
    <div id="wrapper">
        <!-- Sidebar -->
        <!-- menu -->
        <?php include 'include/menu.php'; ?>
        <!-- Fin menu -->
        <?php require('conexion.php'); ?>
        <!-- Contenedor principal -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- menu-->
                <?php
                include 'include/perfil.php';
                ?>
                <!-- fin  menu-->

                <!-- Begin Page Content -->
                <div class="container-fluid mt-4">
                    <!-- Título de la página -->
                    <h1 class="h3 mb-4 text-gray-800">Perfil de <?= $nombre_tipo_user; ?></h1>
                    <!-- Card para perfil -->
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Formulario de información del usuario -->
                            <!-- Usuario -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Usuario:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?= $usuario ?>" readonly>
                                </div>
                            </div>

                            <!-- Correo -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Correo:</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" value="<?= $correo ?>" readonly>
                                </div>
                            </div>

                            <!-- Botón -->
                            <div class="form-group row mt-4">
                                <div class="col-sm-12">
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalPassword">
                                        <i class="fas fa-key mr-2"></i>Cambiar contraseña
                                    </button>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Page Wrapper -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

  <div class="modal fade" id="modalPassword" tabindex="-1">
    <div class="modal-dialog">
        <form id="formCambioPassword">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        🔐 Cambiar contraseña
                    </h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <!-- Contraseña actual -->
                    <div class="form-group">
                        <label>Contraseña actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>

                    <!-- Nueva -->
                    <div class="form-group">
                        <label>Nueva contraseña</label>
                        <input type="password" name="password_nueva" class="form-control" required>
                    </div>

                    <!-- Confirmar -->
                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="password_confirmar" class="form-control" required>
                    </div>

                    <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-dark" type="submit">Actualizar</button>
                </div>

            </div>
        </form>
    </div>
</div>


    <!-- Modal de Cerrar Sesión -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius:10px;">

                <div class="modal-header bg-primary text-white" style="border-top-left-radius:10px; border-top-right-radius:10px;">
                    <h5 class="modal-title" id="logoutLabel">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p style="font-size:16px; margin-bottom:10px;">
                        ¿Seguro que querés cerrar tu sesión?
                    </p>
                    <p style="color:#555;">
                        Se cerrará tu acceso actual al sistema.
                    </p>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <a class="btn btn-primary" href="salir.php">
                        <i class="fas fa-check"></i> Cerrar sesión
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>
    <!-- tabla_plugin -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../js/demo/datatables-demo.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#formCambioPassword').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'controlador/update_password.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(resp) {

            if (resp.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resp.mensaje,
                    confirmButtonColor: '#d33'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Contraseña actualizada',
                    text: resp.mensaje,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#modalPassword').modal('hide');
                    $('#formCambioPassword')[0].reset();
                });
            }

        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error del servidor'
            });
        }
    });
});
</script>



</body>

</html>