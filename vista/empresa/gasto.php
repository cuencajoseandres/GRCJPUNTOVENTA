<?php require_once('validar_sesion.php'); ?>
<?php require('conexion.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registrar Gasto</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'include/menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'include/perfil.php'; ?>

                <main class="container-fluid mt-4">
                    <div id="tablas-contenedor">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarGasto">
                                        <i class="fas fa-plus-circle"></i> Registrar Gasto
                                    </button>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalImportarExcel">
                                        <i class="fas fa-file-upload"></i> Importar Excel
                                    </button>
                                </div>
                                <button id="btnExportarExcel" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Exportar a Excel
                                </button>
                            </div>



                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tablaGastos" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre Gasto</th>
                                                <th>Tipo Gasto</th>
                                                <th>Monto</th>
                                                <th>Fecha</th>
                                                <th>Método Pago</th>
                                                <th>Observaciones</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>

    <!-- MODAL REGISTRAR GASTO -->
    <div class="modal fade" id="modalRegistrarGasto" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formGasto">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-money-bill-wave"></i> Registrar Gasto</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- Datos Gasto -->
                            <!-- Datos Gasto -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre del Gasto:</label>
                                    <input type="text" name="nombre_gasto" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Descripción:</label>
                                    <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción breve del gasto" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Tipo de Gasto:</label>
                                    <select name="tipo_gasto" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $stmt = $pdo->query("SELECT id_tipo_gasto, descri_tipo_gasto FROM tipo_gasto ORDER BY descri_tipo_gasto");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['id_tipo_gasto']}'>{$row['descri_tipo_gasto']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Monto:</label>
                                    <input type="number" name="monto" class="form-control" step="0.01" required>
                                </div>

                                <div class="form-group">
                                    <label>Fecha:</label>
                                    <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>


                            <!-- Método de Pago y Observaciones -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Método de Pago:</label>
                                    <select name="metodo_pago" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $stmt = $pdo->query("SELECT id_metodo_pago, descri_metodo_pago FROM metodo_pago ORDER BY descri_metodo_pago");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['id_metodo_pago']}'>{$row['descri_metodo_pago']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Observaciones:</label>
                                    <textarea name="observaciones" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Gasto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MODAL IMPORTAR GASTOS DESDE EXCEL -->
    <div class="modal fade" id="modalImportarExcel" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formImportarExcel" enctype="multipart/form-data">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-file-import"></i> Importar Gastos desde Excel</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccionar archivo Excel (.xlsx o .xls):</label>
                            <input type="file" name="archivo_excel" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                        <p class="text-muted small">
                            El archivo debe contener las columnas: <strong>nombre_gasto, descripcion, monto, fecha, rela_metodo_pago, rela_tipo_gasto, observaciones</strong>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-upload"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
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


    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            let tablaGastos = $('#tablaGastos').DataTable({
                ajax: 'controlador/fetch_gastos.php',
                columns: [{
                        data: 'id_gasto'
                    },
                    {
                        data: 'nombre_gasto'
                    },
                    {
                        data: 'tipo_gasto'
                    },
                    {
                        data: 'monto',
                        render: $.fn.dataTable.render.number(',', '.', 2, '$')
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'metodo_pago'
                    },
                    {
                        data: 'observaciones'
                    },
                    {
                        data: 'acciones'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
                }
            });

            // Guardar gasto
            $('#formGasto').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'controlador/insertar_gasto.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(resp) {
                        if (!resp.error) {
                            $('#modalRegistrarGasto').modal('hide');
                            Swal.fire('Éxito', resp.mensaje, 'success');
                            tablaGastos.ajax.reload(null, false);
                            $('#formGasto')[0].reset();
                        } else {
                            Swal.fire('Error', resp.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo registrar el gasto', 'error');
                    }
                });
            });

            // Eliminar gasto
            $('#tablaGastos').on('click', '.eliminarGasto', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "No se puede deshacer esta acción.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'controlador/delete_gasto.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(resp) {
                                if (!resp.error) {
                                    Swal.fire('Eliminado', resp.mensaje, 'success');
                                    tablaGastos.ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error', resp.mensaje, 'error');
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        // Exportar a Excel
        $('#btnExportarExcel').on('click', function() {
            window.location.href = 'controlador/exportar_gastos_excel.php';
        });
        // Importar Excel
        // Importar Excel
        $('#formImportarExcel').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'controlador/importar_gastos_excel.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(resp) {
                    if (!resp.error) {
                        $('#modalImportarExcel').modal('hide');
                        Swal.fire('Éxito', resp.mensaje, 'success');
                        $('#tablaGastos').DataTable().ajax.reload(null, false);
                        $('#formImportarExcel')[0].reset();
                    } else {
                        Swal.fire('Error', resp.mensaje, 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo importar el archivo.', 'error');
                }
            });
        });
    </script>

</body>

</html>