<?php require_once('validar_sesion.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>cj informatica</title>
    

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- menu -->
        <?php include 'include/menu.php'; ?>
        <!-- Fin menu -->
        <?php require('conexion.php'); ?>
        <!-- Contenedor principal -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Contenido -->
            <div id="content">
                <!-- menu-->
                <?php
                include 'include/perfil.php'; ?>
                <!-- fin  menu-->


                <main class="container-fluid mt-4">
                    <div id="tablas-contenedor">
                        <!-- Importar Servicios -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Listado de Servicios</h6>
                                <div>
                                    <!-- Botón Importar -->
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalImportarServicios">
                                        <i class="fas fa-file-import"></i> Importar
                                    </button>

                                    <!-- Botón Exportar -->
                                    <a href="controlador/exportar_servicio.php" class="btn btn-info btn-sm">
                                        <i class="fas fa-file-excel"></i> Exportar
                                    </a>

                                    <!-- Botón Agregar Servicio -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalServicio">
                                        <i class="fas fa-concierge-bell"></i> Agregar Servicio
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- Tabla -->
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Código</th> <!-- Nueva columna -->
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $servicios = $pdo->query("SELECT * FROM servicio ORDER BY id_servicio DESC")->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($servicios as $s): ?>
                                                <tr>
                                                    <td><?= $s->id_servicio ?></td>
                                                    <td><?= htmlspecialchars($s->codigo_serv) ?></td> <!-- Mostrar código -->
                                                    <td><?= htmlspecialchars($s->nombre_serv) ?></td>
                                                    <td><?= htmlspecialchars($s->descri_serv) ?></td>
                                                    <td><?= htmlspecialchars($s->precio_serv) ?></td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm btnEditarServicio" data-id="<?= $s->id_servicio ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm btnEliminarServicio" data-id="<?= $s->id_servicio ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>

                        </div>
                </main>

            </div>

        </div>
        <!-- Fin contenido principal -->
    </div>
    <!-- Fin contenedor principal -->
    </div>
    <!-- Fin wrapper -->

    <div class="modal fade" id="modalServicio" tabindex="-1" role="dialog" aria-labelledby="modalServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalServicioLabel"><i class="fas fa-concierge-bell"></i> Servicio</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form id="formServicio" action="controlador/insertar_servicio.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="id_servicio" name="id_servicio">

                        <div class="form-group">
                            <label>Nombre Servicio:</label>
                            <input type="text" class="form-control" id="nombre_serv" name="nombre_serv" placeholder="Nombre del servicio" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" id="descri_serv" name="descri_serv" placeholder="Descripción del servicio">
                        </div>

                        <div class="form-group">
                            <label>Precio:</label>
                            <input type="number" class="form-control" id="precio_serv" name="precio_serv" placeholder="Precio del servicio" required step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                        <button type="submit" id="btnGuardarServicio" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Importar Servicios -->
    <div class="modal fade" id="modalImportarServicios" tabindex="-1" role="dialog" aria-labelledby="modalImportarServiciosLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-file-import"></i> Importar Servicios</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formImportarServicios" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Seleccionar archivo Excel (.xlsx o .csv):</label>
                            <input type="file" name="archivo" class="form-control-file" accept=".xlsx,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-2 w-100">
                            <i class="fas fa-upload"></i> Importar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Editar Servicio -->
    <div class="modal fade" id="modalEditarServicio" tabindex="-1" role="dialog" aria-labelledby="modalEditarServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Servicio</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form id="formEditarServicio" action="controlador/editar_servicio.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id_servicio" name="id_servicio">

                        <div class="form-group">
                            <label>Código:</label>
                            <input type="text" class="form-control" id="edit_codigo_serv" name="codigo_serv" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nombre Servicio:</label>
                            <input type="text" class="form-control" id="edit_nombre_serv" name="nombre_serv" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" id="edit_descri_serv" name="descri_serv">
                        </div>

                        <div class="form-group">
                            <label>Precio:</label>
                            <input type="number" class="form-control" id="edit_precio_serv" name="precio_serv" step="0.01" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" id="btnActualizarServicio" class="btn btn-warning text-white">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>







    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>




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

    <!-- Script para mostrar el nombre del archivo en el input file -->
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>


    <script>
        $('#formServicio').on('submit', function(e) {
            e.preventDefault();
            let $btn = $('#btnGuardarServicio');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        Swal.fire('Éxito', res.mensaje, 'success').then(() => {
                            $('#modalServicio').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                }
            });
        });
    </script>


    <script>
        $('#formImportarServicios').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            Swal.fire({
                title: 'Importando...',
                text: 'Por favor, espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: 'controlador/importar_servicio.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    Swal.close();
                    if (!res.error) {
                        Swal.fire('Éxito', res.mensaje, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Ocurrió un error al procesar el archivo.', 'error');
                }
            });
        });
    </script>


    <script>
        $(document).on('click', '.btnEditarServicio', function() {
            let id = $(this).data('id');

            $.ajax({
                url: 'controlador/obtener_servicio.php',
                type: 'GET',
                data: {
                    id_servicio: id
                },
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        $('#edit_id_servicio').val(res.id_servicio);
                        $('#edit_codigo_serv').val(res.codigo_serv);
                        $('#edit_nombre_serv').val(res.nombre_serv);
                        $('#edit_descri_serv').val(res.descri_serv);
                        $('#edit_precio_serv').val(res.precio_serv);
                        $('#modalEditarServicio').modal('show');
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                }
            });
        });

        $('#formEditarServicio').on('submit', function(e) {
            e.preventDefault();
            let $btn = $('#btnActualizarServicio');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        Swal.fire('Actualizado', res.mensaje, 'success').then(() => {
                            $('#modalEditarServicio').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Actualizar');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.btnEliminarServicio', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar servicio?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'controlador/eliminar_servicio.php',
                        type: 'POST',
                        data: {
                            id_servicio: id
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (!res.error) {
                                Swal.fire('Eliminado', res.mensaje, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.mensaje, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                        }
                    });
                }
            });
        });
    </script>










</body>

</html>