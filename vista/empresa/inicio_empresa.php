<?php require_once('validar_sesion.php'); ?>
<?php require_once('conexion.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CJ INFORMATICA</title>

    <!-- Fonts & Styles -->
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
                
                <?php include 'include/botones.php'; ?>

                <main class="container-fluid mt-4">
                    <div id="tablas-contenedor">

                        <!-- ======================= 1. INSUMOS AL PUBLICO ======================= -->
                        <div id="tabla-publico" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-box"></i> Listado de Productos
                                </h6>
                                <div>
                                    <!-- Botón Exportar -->
                                    <button type="button" class="btn btn-info btn-sm" id="btnExportarProductosP">
                                        <i class="fas fa-file-excel"></i> Exportar
                                    </button>                                 
                                </div>


                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTablePublico" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Código</th>
                                                <th>Precio Público</th>


                                                <th>Stock</th>
                                                <th>Fecha</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $pdo->query("SELECT * FROM producto WHERE cant_product > 0 ORDER BY id_producto DESC");
                                            foreach ($stmt as $fila): ?>
                                                <tr>
                                                    <td><?= $fila['id_producto'] ?></td>
                                                    <td><?= htmlspecialchars($fila['nombre_product']) ?></td>
                                                    <td><?= htmlspecialchars($fila['cod_product']) ?></td>
                                                    <td><?= htmlspecialchars($fila['precio_publico_product']) ?></td>


                                                    <td><?= htmlspecialchars($fila['cant_product']) ?></td>
                                                    <td><?= htmlspecialchars($fila['fecha_product']) ?></td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ======================= 2. INSUMOS AL GREMIO ======================= -->
                        <div id="tabla-gremio" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h4 class="m-0 font-weight-bold text-success"><i class="fas fa-users"></i> Insumos al Gremio</h4>
                                 <div>
                                    <!-- Botón Exportar -->
                                    <button type="button" class="btn btn-info btn-sm" id="btnExportarProductos">
                                        <i class="fas fa-file-excel"></i> Exportar
                                    </button>                                 
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableGremio" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Código</th>

                                                <th>Precio Gremio</th>

                                                <th>Stock</th>
                                                <th>Fecha</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $pdo->query("SELECT * FROM producto WHERE cant_product > 0 ORDER BY id_producto DESC");
                                            foreach ($stmt as $fila): ?>
                                                <tr>
                                                    <td><?= $fila['id_producto'] ?></td>
                                                    <td><?= htmlspecialchars($fila['nombre_product']) ?></td>
                                                    <td><?= htmlspecialchars($fila['cod_product']) ?></td>

                                                    <td><?= htmlspecialchars($fila['precio_gremio_product']) ?></td>

                                                    <td><?= htmlspecialchars($fila['cant_product']) ?></td>
                                                    <td><?= htmlspecialchars($fila['fecha_product']) ?></td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ======================= 3. VENTAS REALIZADAS ======================= -->
                        <div id="tabla-ventas" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-shopping-cart"></i> Ventas Realizadas</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableVentas" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>numero de venta</th>
                                                <th>fecha_venta</th>
                                                <th>mondo_total</th>

                                                <th>Vendedor</th>

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

                        <!-- ======================= 4. INSUMOS FALTANTES ======================= -->
                        <div id="tabla-faltantes" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-warning"><i class="fas fa-exclamation-triangle"></i> Insumos Faltantes</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableFaltantes" width="100%" cellspacing="0">
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
                                    WHERE cant_product < 3
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

                        <!-- ======================= 5. SERVICIOS AL PUBLICO ======================= -->
                        <div id="tabla-servPublico" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-primary"><i class="fas fa-tools"></i> Servicios al Público</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableServPublico" width="100%" cellspacing="0">
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

                        <!-- ======================= 6. SERVICIOS AL GREMIO ======================= -->
                        <div id="tabla-servGremio" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-success"><i class="fas fa-handshake"></i> Servicios al Gremio</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableServGremio" width="100%" cellspacing="0">
                                        <thead class="bg-success text-white">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Precio Gremio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Mantenimiento completo</td>
                                                <td>$10.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ======================= 7. SERVICIOS EN PROCESO ======================= -->
                        <div id="tabla-servProceso" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-spinner fa-spin"></i> Servicios en Proceso</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableServProceso" width="100%" cellspacing="0">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Servicio</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>María López</td>
                                                <td>Reemplazo de disco</td>
                                                <td>En curso</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ======================= 8. SERVICIOS COMPLETADOS ======================= -->
                        <div id="tabla-servCompletado" class="tabla-dinamica card shadow mb-4" style="display:none;">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-success"><i class="fas fa-check-circle"></i> Servicios Completados</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableServCompletado" width="100%" cellspacing="0">
                                        <thead class="bg-success text-white">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Servicio</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ana Gómez</td>
                                                <td>Instalación de drivers</td>
                                                <td>08/10/2025</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
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



    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- click en car -->
    <script>
        /* Inicializa DataTables en todas las tablas con id que comiencen con "dataTable" o que tengan clase .datatable.
          También se asegura de ajustar columnas cuando se muestre una tabla oculta.
           */

        $(document).ready(function() {
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

            // 2) Manejar clicks en las tarjetas (como ya tenías)
            document.querySelectorAll('.card a').forEach(boton => {
                boton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Ocultar todas las tablas
                    document.querySelectorAll('.tabla-dinamica').forEach(tabla => tabla.style.display = 'none');

                    // Mapear textos -> id de contenedor
                    const mapa = {
                        "insumos al publico": "tabla-publico",
                        "insumos al gremio": "tabla-gremio",
                        "ventas realizadas": "tabla-ventas",
                        "insumos faltantes": "tabla-faltantes",
                        "servicios al publico": "tabla-servPublico",
                        "servicios al gremio": "tabla-servGremio",
                        "servicios en proceso": "tabla-servProceso",
                        "servicios completado": "tabla-servCompletado"
                    };

                    const texto = this.textContent.trim().toLowerCase();
                    let idMostrar = null;
                    for (const key in mapa) {
                        if (texto.includes(key)) {
                            idMostrar = mapa[key];
                            break;
                        }
                    }

                    if (idMostrar) {
                        const cont = document.getElementById(idMostrar);
                        cont.style.display = 'block';
                        // Ajuste visual: si la tabla dentro ya es DataTable, ajustamos sus columnas
                        const tablaEl = $(cont).find('table').first();
                        if (tablaEl.length) {
                            // Si DataTable ya está inicializada -> ajustar columnas
                            if ($.fn.dataTable.isDataTable(tablaEl)) {
                                tablaEl.DataTable().columns.adjust().draw();
                            } else {
                                // Si no está inicializada (por ejemplo tabla nueva o no inicializada), la inicializamos ahora
                                tablaEl.DataTable({
                                    language: {
                                        url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                                    }
                                });
                            }
                        }
                    }

                    // Scroll suave hacia las tablas
                    document.getElementById('tablas-contenedor').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
    <!-- mostrar factura -->
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

    <!-- script para generar factura-->
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
    <!-- actualizar stock -->
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

            // ==========================
            // EXPORTAR PRODUCTOS
            // ==========================
            $('#btnExportarProductos').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Exportar productos?',
                    text: "Se descargará un archivo Excel con todos los productos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, exportar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'controlador/exportar_productos_gremio.php';
                        Swal.fire({
                            title: 'Exportando...',
                            text: 'El archivo se descargará en unos segundos.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            $('#btnExportarProductosP').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Exportar productos?',
                    text: "Se descargará un archivo Excel con todos los productos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, exportar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'controlador/exportar_productos_publico.php';
                        Swal.fire({
                            title: 'Exportando...',
                            text: 'El archivo se descargará en unos segundos.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            
        });
    </script>

</body>

</html>