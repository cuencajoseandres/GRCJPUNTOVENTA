<?php require_once('validar_sesion.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>cj info</title>

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

                        <div class="btn-group mb-3" role="group" aria-label="Filtros tiempo">
                            <button class="btn btn-outline-primary filtro-tiempo active" data-filtro="hoy">
                                Hoy
                            </button>
                            <button class="btn btn-outline-primary filtro-tiempo" data-filtro="semana">
                                Semana
                            </button>
                            <button class="btn btn-outline-primary filtro-tiempo" data-filtro="mes">
                                Mes
                            </button>
                            <button class="btn btn-outline-primary filtro-tiempo" data-filtro="anio">
                                Año
                            </button>
                        </div>

                        <div class="row">

                            <!-- Cantidad de ventas de productos -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Cantidad de ventas de productos
                                        </div>
                                        <div id="cantVentasProductos"
                                            class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total vendido en productos -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total vendido en productos
                                        </div>
                                        <div id="totalVentasProductos"
                                            class="h5 mb-0 font-weight-bold text-gray-800">$0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cantidad de servicios realizados -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Cantidad de servicios realizados
                                        </div>
                                        <div id="cantServicios"
                                            class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total facturado en servicios -->
                            <div class="col-md-3 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Total en servicios
                                        </div>
                                        <div id="totalServicios"
                                            class="h5 mb-0 font-weight-bold text-gray-800">$0</div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- historial de ventas-->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-shopping-cart"></i> Historial de ventas</h4>
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

                                                <th>Vendedor</th>
                                                <th>forma de pago</th>
                                                <th>cliente</th>
                                                <th>estado_factura</th>



                                                <th>Acciones</th>
                                            </tr>
                                        </thead>


                                        <!-- =============================================
                                            PHP: Consultar e imprimir productos
                                        ============================================== -->
                                        <?php
                                        // Consultar facturas con empleado
                                        $stmt = $pdo->prepare("
                                            SELECT 
                                                f.id_factura,
                                                f.num_venta,
                                                f.fecha_fact,
                                                f.monto_total,

                                                -- ESTADO
                                                ef.nombre_estado,

                                                -- VENDEDOR
                                                CONCAT(pv.nombre_pers,' ',pv.apellido_pers) AS vendedor,

                                                -- METODO DE PAGO
                                                mp.descri_metodo_pago AS metodo_pago,

                                                -- CLIENTE
                                                CONCAT(pc.nombre_pers,' ',pc.apellido_pers) AS cliente,
                                                tc.descri_tip_cliente AS tipo_cliente

                                            FROM factura f

                                            INNER JOIN estado_factura ef ON f.rela_estado_factura = ef.id_estado_factura

                                            -- VENDEDOR
                                            INNER JOIN empleado e ON f.rela_empleado = e.id_empleado
                                            INNER JOIN persona pv ON e.rela_pers = pv.id_persona

                                            -- METODO DE PAGO
                                            INNER JOIN metodo_pago mp ON f.rela_metodo_pago = mp.id_metodo_pago

                                            -- CLIENTE
                                            INNER JOIN clientes c ON f.rela_cliente = c.id_clientes
                                            INNER JOIN persona pc ON c.rela_persona = pc.id_persona
                                            INNER JOIN tipo_cliente tc ON c.rela_tipo_cliente = tc.id_tip_cliente

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

                                                    <td><?= htmlspecialchars($fila->vendedor) ?></td>

                                                    <!-- METODO DE PAGO CON BADGE -->
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            <?= htmlspecialchars($fila->metodo_pago) ?>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($fila->cliente) ?><br>
                                                        <small class="text-muted">(<?= htmlspecialchars($fila->tipo_cliente) ?>)</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        switch ($fila->nombre_estado) {
                                                            case 'NO_FACTURADA':
                                                                echo '<span class="badge badge-warning">No facturada</span>';
                                                                break;

                                                            case 'FACTURADA':
                                                                echo '<span class="badge badge-success">Facturada</span>';
                                                                break;

                                                            case 'ANULADA':
                                                                echo '<span class="badge badge-danger">Anulada</span>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>


                                                    <td class="text-center">

                                                        <!-- Ver detalle (siempre) -->
                                                        <button class="btn btn-info btn-sm verDetalle"
                                                            data-id="<?= $fila->id_factura ?>"
                                                            title="Ver detalle">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Facturar SOLO si NO_FACTURADA -->
                                                        <?php if ($fila->nombre_estado === 'NO_FACTURADA'): ?>
                                                            <button class="btn btn-success btn-sm facturar"
                                                                data-id="<?= $fila->id_factura ?>"
                                                                title="Generar factura electrónica">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </button>

                                                        <?php endif; ?>
                                                        <!-- Editar venta (solo NO_FACTURADA) -->
                                                        <?php if ($fila->nombre_estado === 'NO_FACTURADA'): ?>
                                                            <button
                                                                class="btn btn-warning btn-sm btnEditarVenta"
                                                                data-id="<?= $fila->id_factura; ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>


                                                        <?php endif; ?>


                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>





                                    </table>
                                </div>
                            </div>
                        </div>



                        <!-- detalle producto-->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-shopping-cart"></i>productos vendidos</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- Tabla -->
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>N° Venta</th>
                                                <th>Fecha</th>
                                                <th>Producto</th>
                                                <th>Cant.</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>


                                        <!-- =============================================
                                            PHP: Consultar e imprimir productos
                                        ============================================== -->
                                        <?php
                                        $stmtProd = $pdo->prepare("
                                        SELECT 
                                            f.num_venta,
                                            f.fecha_fact,
                                            p.nombre_product AS producto,
                                            df.cant_venta_product AS cantidad,
                                            df.precio_unit_product AS precio_unitario,
                                            df.subtotal_product AS subtotal
                                        FROM detalle_factura df
                                        INNER JOIN factura f ON df.rela_factura = f.id_factura
                                        INNER JOIN producto p ON df.rela_producto = p.id_producto
                                        ORDER BY f.fecha_fact DESC
                                    ");
                                        $stmtProd->execute();
                                        $productos = $stmtProd->fetchAll(PDO::FETCH_OBJ);
                                        ?>





                                        <tbody>
                                            <?php foreach ($productos as $p): ?>
                                                <tr>
                                                    <td><?= $p->num_venta ?></td>
                                                    <td><?= $p->fecha_fact ?></td>
                                                    <td><?= htmlspecialchars($p->producto) ?></td>
                                                    <td class="text-center"><?= $p->cantidad ?></td>
                                                    <td>$<?= number_format($p->precio_unitario, 2) ?></td>
                                                    <td>$<?= number_format($p->subtotal, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>






                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- detalle servicio-->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-info"><i class="fas fa-shopping-cart"></i> servicios realizados</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- Tabla -->
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>N° Venta</th>
                                                <th>Fecha</th>
                                                <th>Servicio</th>
                                                <th>Cant.</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>


                                        <!-- =============================================
                                            PHP: Consultar e imprimir productos
                                        ============================================== -->
                                        <?php
                                        $stmtServ = $pdo->prepare("
                                            SELECT 
                                                f.num_venta,
                                                f.fecha_fact,
                                                s.nombre_serv AS servicio,
                                                ds.cant_vent_serv AS cantidad,
                                                ds.precio_unit_serv AS precio_unitario,
                                                ds.subtotal_serv AS subtotal
                                            FROM detalle_fact_serv ds
                                            INNER JOIN factura f ON ds.rela_factura = f.id_factura
                                            INNER JOIN servicio s ON ds.rela_servicio = s.id_servicio
                                            ORDER BY f.fecha_fact DESC
                                        ");
                                        $stmtServ->execute();
                                        $servicios = $stmtServ->fetchAll(PDO::FETCH_OBJ);
                                        ?>




                                        <tbody>
                                            <?php foreach ($servicios as $s): ?>
                                                <tr>
                                                    <td><?= $s->num_venta ?></td>
                                                    <td><?= $s->fecha_fact ?></td>
                                                    <td><?= htmlspecialchars($s->servicio) ?></td>
                                                    <td class="text-center"><?= $s->cantidad ?></td>
                                                    <td>$<?= number_format($s->precio_unitario, 2) ?></td>
                                                    <td>$<?= number_format($s->subtotal, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>





                                    </table>
                                </div>
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
                                <th>Item</th>
                                <th>Tipo</th>
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
                        <i class="fas fa-print"></i>A4
                    </a>
                    <a href="#" id="btnImprimirticket" class="btn btn-success" target="_blank">
                        <i class="fas fa-print"></i>Ticket
                    </a>
                    <a href="#" id="btnFACTURA" class="btn btn-success" target="_blank">
                        <i class="fab fa-PRINT"></i> GENERAR FACTURA
                    </a>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalEditarVenta" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Venta
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="formEditarVenta">


                        <input type="hidden" id="edit_id_factura">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Tipo de Venta:</label>
                                <div>
                                    <button type="button" id="toggleTipoVenta" class="btn btn-info btn-sm">Minorista</button>
                                    <input type="hidden" id="tipoVenta" value="minorista">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Número de Venta</label>
                                <input type="text" class="form-control" id="num_venta" name="num_venta" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="fecha_fact" name="fecha_fact" required>
                            </div>
                            <div class="col-md-4">
                                <label>Usuario:</label>
                                <select name="empleado" class="form-control select2" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php
                                    $empleados = $pdo->query("
                                            SELECT e.id_empleado, CONCAT(p.nombre_pers,' ',p.apellido_pers) AS nombre
                                            FROM empleado e
                                            INNER JOIN persona p ON e.rela_pers = p.id_persona
                                            ORDER BY nombre ASC
                                        ")->fetchAll();
                                    foreach ($empleados as $emp) {
                                        echo "<option value='{$emp['id_empleado']}'>{$emp['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Cliente:</label>
                                <div class="input-group">
                                    <select name="cliente" class="form-control select2" id="selectCliente" style="width: 90%;">
                                        <option value="">Seleccione un cliente...</option>
                                        <?php
                                        $clientes = $pdo->query("
                                                        SELECT 
                                                            c.id_clientes,
                                                            CONCAT(
                                                                p.nombre_pers, ' ', 
                                                                p.apellido_pers, 
                                                                ' - DNI: ', p.dni_pers,
                                                                ' - Tel: ', IFNULL(t.num_tel, 'Sin teléfono')
                                                            ) AS nombre
                                                        FROM clientes c
                                                        INNER JOIN persona p ON c.rela_persona = p.id_persona
                                                        LEFT JOIN telefono t ON t.rela_persona = p.id_persona
                                                        ORDER BY p.nombre_pers ASC
                                                    ")->fetchAll();

                                        foreach ($clientes as $cli) {
                                            echo "<option value='{$cli['id_clientes']}'>{$cli['nombre']}</option>";
                                        }
                                        ?>
                                    </select>

                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modalAgregarCliente">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Método de pago:</label>
                                <div class="input-group">
                                    <select name="metodo_pago" class="form-control select2" id="selectMetodoPago" required>
                                        <option value="">Seleccione un método de pago</option>
                                        <?php
                                        $metodos = $pdo->query("
                                                SELECT id_metodo_pago, descri_metodo_pago
                                                FROM metodo_pago
                                                ORDER BY descri_metodo_pago ASC
                                            ")->fetchAll();

                                        foreach ($metodos as $mp) {
                                            echo "<option value='{$mp['id_metodo_pago']}'>{$mp['descri_metodo_pago']}</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Producto / Servicio</label>
                                <select id="item" class="form-control select2" required>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    // Productos
                                    $stmtP = $pdo->query("
                                                    SELECT 
                                                        id_producto,
                                                        nombre_product,
                                                        precio_gremio_product,
                                                        precio_publico_product,
                                                        cant_product
                                                    FROM producto
                                                    ORDER BY nombre_product
                                                ");

                                    while ($p = $stmtP->fetch(PDO::FETCH_ASSOC)) {

                                        $sinStock = ($p['cant_product'] <= 0);
                                        $textoStock = $sinStock
                                            ? ' (SIN STOCK)'
                                            : ' (Stock: ' . $p['cant_product'] . ')';

                                        echo "<option value='{$p['id_producto']}'
                                                data-precio_gremio='{$p['precio_gremio_product']}'
                                                data-precio_publico='{$p['precio_publico_product']}'
                                                data-stock='{$p['cant_product']}'
                                                data-tipo='producto'
                                                " . ($sinStock ? 'disabled' : '') . ">
                                                {$p['nombre_product']}{$textoStock}
                                            </option>";
                                    }

                                    // Servicios (sin stock)
                                    $stmtS = $pdo->query("
                                                SELECT id_servicio, nombre_serv, precio_serv 
                                                FROM servicio 
                                                ORDER BY nombre_serv
                                            ");

                                    while ($s = $stmtS->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$s['id_servicio']}'
                                                data-precio='{$s['precio_serv']}'
                                                data-tipo='servicio'>
                                                {$s['nombre_serv']} (Servicio)
                                            </option>";
                                    }
                                    ?>

                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Cantidad</label>
                                <input type="number" id="cantidad" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label>Precio</label>
                                <input type="number" id="precio" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btnAgregar" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <hr>

                        <table class="table table-bordered table-sm mt-3" id="tablaDetalle">
                            <thead class="table-light">
                                <tr>
                                    <th>producto</th>
                                    <th>Tipo</th>
                                    <th>Cant.</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <div class="text-right mt-4">
                            <p>TOTAL: $<span id="subtotalVenta">0.00</span></p>
                            <p>Recargo (<span id="porcentajeRecargo">0%</span>): $<span id="montoRecargo">0.00</span></p>
                            <h5>Total con recargo: $<span id="totalVenta">0.00</span></h5>
                        </div>


                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> guardar cambioi
                            </button>
                        </div>


                    </form>
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
            let facturaActual = 0;

            // Mostrar detalle al hacer click en "Ver detalle"
            $('.verDetalle').click(function() {
                facturaActual = $(this).data('id');

                $.ajax({
                    url: 'controlador/get_detalle_factura.php',
                    type: 'GET',
                    data: {
                        id: facturaActual
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (!resp.error) {
                            let html = '';
                            let total = 0;

                            resp.data.forEach(d => {
                                html += `
                        <tr>
                            <td>${d.nombre}</td>
                            <td>${d.tipo}</td>
                            <td>${d.cantidad}</td>
                            <td>$${parseFloat(d.precio_unitario).toFixed(2)}</td>
                            <td>$${parseFloat(d.subtotal).toFixed(2)}</td>
                        </tr>`;
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

            // Imprimir Factura completa
            $('#btnImprimirFactura').click(function(e) {
                e.preventDefault();
                if (facturaActual > 0) {
                    window.open('controlador/impresion_factura.php?id=' + facturaActual, '_blank');
                }
            });

            // Imprimir Ticket
            $('#btnImprimirticket').click(function(e) {
                e.preventDefault();
                if (facturaActual > 0) {
                    window.open('controlador/impresion_factura_ticket.php?id=' + facturaActual, '_blank');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            function formatearMoneda(valor) {
                let num = parseFloat(valor) || 0;
                return '$ ' + num.toFixed(2)
                    .replace('.', ',')
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function cargarTotales(filtro = 'hoy') {
                $.ajax({
                    url: 'controlador/totales_ventas.php',
                    type: 'GET',
                    data: {
                        filtro: filtro
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (!res.error) {
                            $('#cantVentasProductos').text(res.cantVentasProductos);
                            $('#totalVentasProductos').text(formatearMoneda(res.totalVentasProductos));
                            $('#cantServicios').text(res.cantServicios);
                            $('#totalServicios').text(formatearMoneda(res.totalServicios));
                        }
                    }
                });
            }

            // carga inicial
            cargarTotales('hoy');

            // click en botones
            $('.filtro-tiempo').click(function() {
                $('.filtro-tiempo').removeClass('active');
                $(this).addClass('active');

                let filtro = $(this).data('filtro');
                cargarTotales(filtro);
            });

        });
    </script>






    <script>
$(document).ready(function () {

    /* ===============================
       SELECT2
    =============================== */
    $('#item').select2({
        placeholder: 'Buscar producto o servicio...',
        width: '100%',
        allowClear: true
    });

    /* ===============================
       ABRIR MODAL EDITAR VENTA
    =============================== */
    $(document).on('click', '.btnEditarVenta', function () {

        const idFactura = $(this).data('id');

        $.ajax({
            url: 'controlador/obtener_venta.php',
            type: 'POST',
            dataType: 'json',
            data: { id_factura: idFactura },
            success: function (res) {

                if (res.error) {
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }

                $('#edit_id_factura').val(res.factura.id_factura);
                $('#num_venta').val(res.factura.num_venta);
                $('#fecha_fact').val(res.factura.fecha_fact);

                $('select[name="empleado"]').val(res.factura.rela_empleado).trigger('change');
                $('select[name="cliente"]').val(res.factura.rela_cliente).trigger('change');
                $('select[name="metodo_pago"]').val(res.factura.rela_metodo_pago).trigger('change');

                $('#tablaDetalle tbody').empty();

                res.detalles.forEach(d => {

                    const subtotal = d.cantidad * d.precio;

                    $('#tablaDetalle tbody').append(`
                        <tr 
                            data-id-detalle="${d.id_detalle}" 
                            data-id-item="${d.id}" 
                            data-tipo="${d.tipo}">
                            <td>${d.item}</td>
                            <td>${d.tipo}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm cantidad" value="${d.cantidad}" min="1">
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm precio" value="${d.precio}" step="0.01">
                            </td>
                            <td class="subtotal">${subtotal.toFixed(2)}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm btnEliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });

                recalcularTotales();
                $('#modalEditarVenta').modal('show');
            }
        });
    });

    /* ===============================
       SELECCIONAR ITEM
    =============================== */
    $('#item').on('change', function () {

        const op = $(this).find(':selected');
        if (!op.val()) return;

        const tipo = op.data('tipo');
        let precio = 0;

        if (tipo === 'producto') {
            const tipoVenta = $('#tipoVenta').val();
            precio = tipoVenta === 'mayorista'
                ? parseFloat(op.data('precio_gremio')) || 0
                : parseFloat(op.data('precio_publico')) || 0;
        } else {
            precio = parseFloat(op.data('precio')) || 0;
        }

        $('#precio').val(precio.toFixed(2));
        $('#cantidad').val(1);
    });

    /* ===============================
       AGREGAR ITEM NUEVO
    =============================== */
    $('#btnAgregar').on('click', function () {

        const op = $('#item option:selected');

        const idItem = op.val();
        const nombre = op.text();
        const tipo = op.data('tipo');
        const cantidad = parseFloat($('#cantidad').val());
        const precio = parseFloat($('#precio').val());

        if (!idItem || cantidad <= 0 || precio <= 0) {
            Swal.fire('Atención', 'Seleccione un ítem válido', 'warning');
            return;
        }

        const subtotal = cantidad * precio;

        $('#tablaDetalle tbody').append(`
            <tr 
                data-id-detalle="0" 
                data-id-item="${idItem}" 
                data-tipo="${tipo}">
                <td>${nombre}</td>
                <td>${tipo}</td>
                <td>
                    <input type="number" class="form-control form-control-sm cantidad" value="${cantidad}" min="1">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm precio" value="${precio.toFixed(2)}" step="0.01">
                </td>
                <td class="subtotal">${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btnEliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $('#item').val(null).trigger('change');
        $('#precio').val('');
        $('#cantidad').val(1);

        recalcularTotales();
    });

    /* ===============================
       ELIMINAR ITEM
    =============================== */
    $(document).on('click', '.btnEliminar', function () {

        const fila = $(this).closest('tr');

        Swal.fire({
            title: '¿Eliminar ítem?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar'
        }).then(r => {
            if (r.isConfirmed) {
                fila.remove();
                recalcularTotales();
            }
        });
    });

    /* ===============================
       RECALCULAR
    =============================== */
    $(document).on('input', '.cantidad, .precio', recalcularTotales);
    $('#selectMetodoPago').on('change', recalcularTotales);

    function recalcularTotales() {

        let subtotal = 0;

        $('#tablaDetalle tbody tr').each(function () {

            const cant = parseFloat($(this).find('.cantidad').val()) || 0;
            const prec = parseFloat($(this).find('.precio').val()) || 0;
            const sub = cant * prec;

            $(this).find('.subtotal').text(sub.toFixed(2));
            subtotal += sub;
        });

        let porcentaje = 0;
        const metodo = $('#selectMetodoPago option:selected').text().toLowerCase();

        if (metodo.includes('transferencia')) porcentaje = 2;
        if (metodo.includes('tarjeta')) porcentaje = 12;

        const recargo = subtotal * porcentaje / 100;
        const total = subtotal + recargo;

        $('#subtotalVenta').text(subtotal.toFixed(2));
        $('#porcentajeRecargo').text(porcentaje + '%');
        $('#montoRecargo').text(recargo.toFixed(2));
        $('#totalVenta').text(total.toFixed(2));
    }

    /* ===============================
       SUBMIT EDITAR VENTA
    =============================== */
    $('#formEditarVenta').on('submit', function (e) {
        e.preventDefault();

        const detalles = [];

        $('#tablaDetalle tbody tr').each(function () {
            detalles.push({
                id_detalle: $(this).data('id-detalle'),
                id: $(this).data('id-item'),
                tipo: $(this).data('tipo'),
                cantidad: parseFloat($(this).find('.cantidad').val()),
                precio: parseFloat($(this).find('.precio').val())
            });
        });

        if (detalles.length === 0) {
            Swal.fire('Error', 'La venta debe tener al menos un ítem', 'error');
            return;
        }

        const data = {
            id_factura: $('#edit_id_factura').val(),
            empleado: $('select[name="empleado"]').val(),
            cliente: $('select[name="cliente"]').val(),
            metodo_pago: $('select[name="metodo_pago"]').val(),
            tipoVenta: $('#tipoVenta').val(),
            detalles: detalles
        };

        $.ajax({
            url: 'controlador/editar_venta.php',
            type: 'POST',
            dataType: 'json',
            data: { data: JSON.stringify(data) },
            success: function (res) {
                if (!res.error) {
                    Swal.fire('Éxito', res.mensaje, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', res.mensaje, 'error');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'No se pudo editar la venta', 'error');
            }
        });
    });

});
</script>









</body>

</html>