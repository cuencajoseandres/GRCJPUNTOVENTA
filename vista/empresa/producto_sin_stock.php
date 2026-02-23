





<?php require_once('validar_sesion.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MOTOSENT FSA</title>

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
                      <div class="row">
                            <!-- Total Productos -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Productos</div>
                                        <div id="totalProductos" class="h5 mb-0 font-weight-bold text-gray-800" data-target="0">0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor costo -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Valor total (Costo)</div>
                                        <div id="totalCosto" class="h5 mb-0 font-weight-bold text-gray-800" data-target="0">$0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor gremio -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Valor total (Gremio)</div>
                                        <div id="totalInsumos" class="h5 mb-0 font-weight-bold text-gray-800" data-target="0">$0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor público -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Valor total (Precio Público)</div>
                                        <div id="totalPublico" class="h5 mb-0 font-weight-bold text-gray-800" data-target="0">$0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-box"></i> Listado de Productos con stock faltante
                                </h6>
                                <div>
                                    

                                    <!-- Productos sin stock -->
                                    <a href="producto.php" class="btn btn-primary btn-sm ">
                                        <i class="fas fa-exclamation-triangle"></i> IR A PRODUCTO
                                    </a>

                                   
                                </div>
                            </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Tabla -->
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Código</th>

                                        <th>Precio Público</th>
                                        <th>Precio Gremio</th>
                                        <th>Precio Mayorista</th>
                                        <th>Stock</th>
                                        <th>Fecha</th>

                                        <th>Acciones</th>
                                    </tr>
                                </thead>


                                <!-- =============================================
                                 PHP: Consultar e imprimir productos
                            ============================================== -->
                                <?php
                                include("conexion.php");

                                // Traer productos activos
                                $stmt = $pdo->prepare("
                                    SELECT 
                                        id_producto,
                                        nombre_product,
                                        cod_product,
                                        precio_publico_product,
                                        precio_gremio_product,
                                        precio_mayorista_product,
                                        cant_product,
                                        fecha_product
                                    FROM producto
                                    WHERE cant_product < 2
                                    ORDER BY cant_product ASC
                                ");
                                $stmt->execute();
                                $productos = $stmt->fetchAll(PDO::FETCH_OBJ); // Para usar $fila->campo
                                ?>


                                <tbody>
                                    <?php foreach ($productos as $fila): ?>
                                        <tr>
                                            <td><?= $fila->id_producto ?></td>
                                            <td><?= htmlspecialchars($fila->nombre_product) ?></td>
                                            <td><?= htmlspecialchars($fila->cod_product) ?></td>
                                            <td><?= htmlspecialchars($fila->precio_publico_product) ?></td>
                                            <td><?= htmlspecialchars($fila->precio_gremio_product) ?></td>
                                            <td><?= htmlspecialchars($fila->precio_mayorista_product) ?></td>
                                            <td><span class="badge badge-danger"><?= htmlspecialchars($fila->cant_product) ?></span></td>
                                            <td><?= htmlspecialchars($fila->fecha_product) ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm autorizarStock"
                                                    data-id="<?= $fila->id_producto ?>"
                                                    data-nombre="<?= htmlspecialchars($fila->nombre_product) ?>"
                                                    data-stock="<?= $fila->cant_product ?>">
                                                    <i class="fas fa-plus"></i> Autorizar Stock
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>

                    </div>
                </main>
            </div>
            <!-- Fin contenido principal -->
        </div>
        <!-- Fin contenedor principal -->
    </div>
    <!-- Fin wrapper -->

    <!-- Modal Autorizar Stock -->
    <div class="modal fade" id="modalStock" tabindex="-1" role="dialog" aria-labelledby="modalStockLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalStockLabel"><i class="fas fa-boxes"></i> Autorizar Stock</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formStock" action="controlador/update_stock.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_producto" id="stock_id_producto">

                        <div class="form-group">
                            <label>Producto:</label>
                            <input type="text" id="stock_nombre" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>Stock actual:</label>
                            <input type="text" id="stock_actual" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>Agregar cantidad:</label>
                            <input type="number" name="cantidad" id="stock_cantidad" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" id="btnAutorizar" class="btn btn-success">
                            <i class="fas fa-check"></i> Autorizar
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
        $(document).ready(function() {

        
            // Abrir modal Autorizar Stock
            $(document).on('click', '.autorizarStock', function() {
                let id = $(this).data('id');
                let nombre = $(this).data('nombre');
                let stock = $(this).data('stock');

                $('#stock_id_producto').val(id);
                $('#stock_nombre').val(nombre);
                $('#stock_actual').val(stock);
                $('#stock_cantidad').val('');

                $('#modalStock').modal('show');
            });

            // Enviar formulario stock
            $('#formStock').on('submit', function(e) {
                e.preventDefault();
                let $btn = $('#btnAutorizar');
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (!res.error) {
                            Swal.fire('Éxito', res.mensaje, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Ocurrió un error', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Autorizar');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            function formatearMoneda(valor) {
                // Convierte a número y luego formato AR
                let num = parseFloat(valor) || 0;
                return '$ ' + num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function cargarTotales() {
                $.ajax({
                    url: 'controlador/obtener_totales.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (!res.error) {
                            $('#totalProductos').text(parseInt(res.total_productos));
                            $('#totalInsumos').text(formatearMoneda(res.total_gremio)); // valor gremio
                            $('#totalCosto').text(formatearMoneda(res.total_costo));
                            $('#totalPublico').text(formatearMoneda(res.total_publico));
                        } else {
                            console.error(res.mensaje);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Cargar totales al iniciar
            cargarTotales();

            // Actualizar cada 30 segundos si querés
            // setInterval(cargarTotales, 30000);

        });
    </script>





</body>

</html>