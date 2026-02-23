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
                include 'include/perfil.php'; 
                ?>

                <main class="container-fluid mt-4">
                    <div id="tablas-contenedor">
                <!-- fin  menu-->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                       <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-shopping-cart"></i> Ventas Realizadas</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Tabla -->
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>numero de venta</th>
                                        <th>fecha_venta</th>
                                        <th>mondo_total</th>

                                        <th>VENDEDOR</th>

                                        <th>Acciones</th>
                                    </tr>
                                </thead>


                                <!-- =============================================
                                 PHP: Consultar e imprimir productos
                            ============================================== -->
                                <?php
                                // Consultar facturas con empleado
                                $stmt = $pdo->prepare("
                                        SELECT f.id_factura, f.num_venta, f.fecha_fact, f.monto_total,
                                            CONCAT(p.nombre_pers,' ',p.apellido_pers) AS nombre_empleado
                                        FROM factura f
                                        INNER JOIN empleado e ON f.rela_empleado = e.id_empleado
                                        INNER JOIN persona p ON e.rela_pers = p.id_persona
                                        ORDER BY f.fecha_fact DESC
                                    ");
                                $stmt->execute();
                                $facturas = $stmt->fetchAll(PDO::FETCH_OBJ);
                                ?>



                                <tbody>
                                    <?php foreach ($facturas as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila->num_venta) ?></td>
                                            <td><?= htmlspecialchars($fila->fecha_fact) ?></td>
                                            <td>$<?= number_format($fila->monto_total, 2) ?></td>
                                            <td><?= htmlspecialchars($fila->nombre_empleado) ?></td>
                                            <td>
                                                <button class="btn btn-info btn-sm verDetalle" data-id="<?= $fila->id_factura ?>">
                                                    <i class="fas fa-eye"></i> Ver detalle
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>



                            </table>
                        </div>
                    </div>
                </div>
</div>
</div>
            </div>
            <!-- Fin contenido principal -->
        </div>
        <!-- Fin contenedor principal -->
    </div>
    <!-- Fin wrapper -->


    <!-- Modal Detalle Factura -->
    <div class="modal fade" id="modalDetalleFactura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-receipt"></i> Detalle de Factura</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm" id="tablaDetalleFactura">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="text-right">
                        <h5>Total: $<span id="totalFactura">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <a href="#" id="btnImprimirFactura" class="btn btn-success" target="_blank">
                        <i class="fas fa-print"></i> Imprimir Factura
                    </a>
                </div>

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
   <!-- SCRIPTS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
         // 1) Inicializar todas las tablas que ya existan
            $('[id^="dataTable"], .datatable').each(function() {
                if (!$.fn.dataTable.isDataTable(this)) {
                    $(this).DataTable({
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                        }
                    });
                }
            });
    </script>

    <!-- Script para mostrar el nombre del archivo en el input file -->
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.verDetalle').click(function() {
                let idFactura = $(this).data('id');

                $.ajax({
                    url: 'controlador/get_detalle_factura.php',
                    type: 'GET',
                    data: {
                        id: idFactura
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (!resp.error) {
                            let html = '';
                            let total = 0;
                            resp.data.forEach(d => {
                                html += `
                            <tr>
                                <td>${d.nombre_producto}</td>
                                <td>${d.cantidad}</td>
                                <td>$${parseFloat(d.precio_unitario).toFixed(2)}</td>
                                <td>$${parseFloat(d.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                                total += parseFloat(d.subtotal);
                            });
                            $('#tablaDetalleFactura tbody').html(html);
                            $('#totalFactura').text(total.toFixed(2));
                            $('#modalDetalleFactura').modal('show');
                        } else {
                            Swal.fire('Error', resp.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo obtener el detalle.', 'error');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let facturaActual = 0;

            $('.verDetalle').click(function() {
                facturaActual = $(this).data('id');
                // Ajax para cargar detalle ya implementado
            });

            $('#btnImprimirFactura').click(function(e) {
                e.preventDefault();
                if (facturaActual > 0) {
                    window.open('controlador/impresion_factura.php?id=' + facturaActual, '_blank');
                }
            });
        });
    </script>





</body>

</html>