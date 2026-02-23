<?php require_once('validar_sesion.php'); ?>
<?php
require('conexion.php');
$tiposTel = $pdo->query("
    SELECT id_tip_tel, descri_tip_tel 
    FROM tipo_telefono 
    ORDER BY descri_tip_tel ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>CJ INFORMATICA | Ventas</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'include/menu.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'include/perfil.php'; ?>

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-cash-register"></i> Registrar Venta</h5>
                        </div>
                        <div class="card-body">
                            <form id="formVenta">
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
                                            <th>Item</th>
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
                                        <i class="fas fa-save"></i> Registrar Venta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Cliente Nuevo -->
    <!-- MODAL INSERTAR CLIENTE -->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form id="formAgregarCliente" method="POST" action="controlador/insert_cliente.php">

                    <!-- HEADER -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus"></i> Registrar Nuevo Cliente
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <div class="row">

                            <!-- Datos personales -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-id-card"></i> Datos Personales</h5>

                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input type="text" name="nombre_pers" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Apellido:</label>
                                    <input type="text" name="apellido_pers" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>DNI:</label>
                                    <input type="number" name="dni_pers" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Correo:</label>
                                    <input type="email" name="correo_pers" class="form-control">
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-phone"></i> Contacto</h5>

                                <div class="form-group">
                                    <label>Número de teléfono:</label>
                                    <input type="tel" name="num_tel" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Tipo de teléfono:</label>
                                    <select name="rela_tip_tel" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($tiposTel as $t): ?>
                                            <option value="<?= $t['id_tip_tel'] ?>">
                                                <?= $t['descri_tip_tel'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tipo de cliente:</label>
                                    <select name="tipo_cliente" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $tipos = $pdo->query("
                                        SELECT id_tip_cliente, descri_tip_cliente
                                        FROM tipo_cliente
                                        ORDER BY descri_tip_cliente
                                    ")->fetchAll();

                                        foreach ($tipos as $t) {
                                            echo "<option value='{$t['id_tip_cliente']}'>
                                            {$t['descri_tip_cliente']}
                                        </option>";
                                        }
                                        ?>
                                    </select>


                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cliente
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

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Inicialización global de Select2 -->
    <script>
        $(document).ready(function() {

            // Activar Select2 en todos los select con la clase "select2"
            //  $('.select2').select2({
            //      placeholder: "Seleccione una opción...",
            //      allowClear: true,
            //      width: '100%'
            // });

            // Activar Select2 con placeholder especial para CLIENTE


            $('#selectCliente').select2({
                placeholder: "Seleccione un cliente...",
                allowClear: true,
                width: 'resolve' // ← evita que el botón "+" se caiga
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            $('#item').select2({
                placeholder: "Buscar producto o servicio...",
                allowClear: true,
                width: '100%'
            });

            let detalles = [];
            let totalVenta = 0;
            $('#num_venta').val('VENTA-' + Date.now().toString().slice(-6));

            const actualizarTabla = () => {
                let html = '';
                detalles.forEach((d, i) => {
                    html += `
            <tr>
                <td>${d.nombre}</td>
                <td>${d.tipo}</td>
                <td>${d.cantidad}</td>
                <td>$${d.precio.toFixed(2)}</td>
                <td>$${d.subtotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm btnEliminar" data-index="${i}"><i class="fas fa-trash"></i></button></td>
            </tr>`;
                });
                $('#tablaDetalle tbody').html(html);
                $('#totalVenta').text(totalVenta.toFixed(2));
            };

            $('#item').change(function() {
                let opcion = $(this).find(':selected');
                let tipo = opcion.data('tipo');
                let tipoVenta = $('#tipoVenta').val();
                let precio = 0;

                if (tipo === 'producto') {
                    let precioGremio = parseFloat(opcion.data('precio_gremio')) || 0;
                    let precioPublico = parseFloat(opcion.data('precio_publico')) || 0;
                    precio = tipoVenta === 'mayorista' ? precioGremio : precioPublico;

                    let stock = parseFloat(opcion.data('stock')) || 0;
                    $('#cantidad').attr('max', stock);
                } else {
                    precio = parseFloat(opcion.data('precio')) || 0;
                    $('#cantidad').removeAttr('max');
                }

                $('#precio').val(precio);
            });

            $('#btnAgregar').click(function() {
                let opcion = $('#item option:selected');
                let id = opcion.val();
                let nombre = opcion.text();
                let tipo = opcion.data('tipo');
                let cantidad = parseFloat($('#cantidad').val());
                let precio = parseFloat($('#precio').val());
                let stock = parseFloat(opcion.data('stock')) || 0;

                if (!id || cantidad <= 0) {
                    Swal.fire('Atención', 'Seleccione un item y cantidad válida.', 'warning');
                    return;
                }
                if (tipo === 'producto' && cantidad > stock) {
                    Swal.fire('Error', 'Cantidad supera el stock disponible', 'error');
                    return;
                }

                let subtotal = cantidad * precio;
                totalVenta += subtotal;
                detalles.push({
                    id,
                    nombre,
                    tipo,
                    cantidad,
                    precio,
                    subtotal
                });
                actualizarTabla();
            });

            $(document).on('click', '.btnEliminar', function() {
                let index = $(this).data('index');
                totalVenta -= detalles[index].subtotal;
                detalles.splice(index, 1);
                actualizarTabla();
            });

            $('#toggleTipoVenta').click(function() {
                let tipoActual = $('#tipoVenta').val();
                if (tipoActual === 'minorista') {
                    $('#tipoVenta').val('mayorista');
                    $(this).text('Mayorista').removeClass('btn-info').addClass('btn-warning');
                } else {
                    $('#tipoVenta').val('minorista');
                    $(this).text('Minorista').removeClass('btn-warning').addClass('btn-info');
                }
                $('#item').trigger('change'); // actualizar precio
            });

            $('#formVenta').submit(function(e) {
                e.preventDefault();
                if (detalles.length === 0) {
                    Swal.fire('Error', 'Agregue al menos un item.', 'error');
                    return;
                }

                let cliente = $('#selectCliente').val();
                let metodo = $('#selectMetodoPago').val();
                let empleado = $('select[name="empleado"]').val();

                if (!cliente || !metodo || !empleado) {
                    Swal.fire('Error', 'Complete cliente, empleado y método de pago.', 'error');
                    return;
                }

                let datos = {
                    num_venta: $('#num_venta').val(),
                    fecha_fact: $('#fecha_fact').val(),
                    empleado: empleado,
                    cliente: cliente,
                    metodo_pago: metodo,
                    tipoVenta: $('#tipoVenta').val(),
                    detalles: detalles
                };

                $.ajax({
                    url: 'controlador/insertar_venta1.php',
                    type: 'POST',
                    data: {
                        data: JSON.stringify(datos)
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (!resp.error) {
                            Swal.fire('¡Éxito!', resp.mensaje, 'success').then(() => location.reload());
                        } else Swal.fire('Error', resp.mensaje, 'error');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo registrar la venta.', 'error');
                    }
                });
            });

            const fechaInput = document.getElementById("fecha_fact");
            fechaInput.value = new Date().toISOString().split('T')[0];
        });

        // Registrar cliente nuevo
        $('#formClienteNuevo').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: 'controlador/insertar_cliente.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        let nuevaOpcion = new Option(res.nombreCompleto, res.id_cliente, true, true);
                        $('#selectCliente').append(nuevaOpcion).trigger('change');
                        Swal.fire('Éxito', res.mensaje, 'success');
                        $('#modalClienteNuevo').modal('hide');
                        $('#formClienteNuevo')[0].reset();
                    } else Swal.fire('Error', res.mensaje, 'error');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo registrar el cliente', 'error');
                }
            });
        });
    </script>





    <script>
        $(document).ready(function() {

            function calcularTotales() {
                let subtotal = 0;

                // Sumamos subtotales limpiando el símbolo $
                $('#tablaDetalle tbody tr').each(function() {
                    const textoSubtotal = $(this).find('td:eq(4)').text().replace('$', '').trim();
                    const subtotalItem = parseFloat(textoSubtotal) || 0;
                    subtotal += subtotalItem;
                });

                // Detectar método de pago seleccionado
                const metodoPago = $('#selectMetodoPago option:selected').text().toLowerCase();
                let porcentajeRecargo = 0;

                if (metodoPago.includes('transferencia')) {
                    porcentajeRecargo = 2;
                } else if (metodoPago.includes('tarjeta')) {
                    porcentajeRecargo = 12;
                } else if (metodoPago.includes('efectivo')) {
                    porcentajeRecargo = 0;
                }

                // Calcular montos
                const montoRecargo = subtotal * (porcentajeRecargo / 100);
                const totalFinal = subtotal + montoRecargo;

                // Mostrar valores
                $('#subtotalVenta').text(subtotal.toFixed(2));
                $('#porcentajeRecargo').text(porcentajeRecargo + '%');
                $('#montoRecargo').text(montoRecargo.toFixed(2));
                $('#totalVenta').text(totalFinal.toFixed(2));
            }

            // Recalcular cuando cambie el método de pago
            $('#selectMetodoPago').on('change', calcularTotales);

            // Recalcular cuando se agregue un producto/servicio
            $('#btnAgregar').on('click', function() {
                setTimeout(calcularTotales, 300);
            });

            // Recalcular también cuando se elimine un ítem
            $(document).on('click', '.btnEliminar', function() {
                setTimeout(calcularTotales, 200);
            });

            // También recalcular cuando cambie el tipo de venta (por si cambia precio)
            $('#toggleTipoVenta').on('click', function() {
                setTimeout(calcularTotales, 300);
            });

            // Si la tabla se modifica dinámicamente (agregar/eliminar), recalcular automáticamente
            const observer = new MutationObserver(() => calcularTotales());
            observer.observe(document.querySelector('#tablaDetalle tbody'), {
                childList: true
            });
        });
    </script>

    <script>
        // ------------------------------------------
        // INSERTAR CLIENTE SIN RECARGAR
        // ------------------------------------------
        $("#formAgregarCliente").on("submit", function(e) {
            e.preventDefault();

            let datos = $(this).serialize();

            $.post("controlador/insert_cliente.php", datos, function(res) {

                if (!res.error) {
                    Swal.fire({
                        icon: "success",
                        title: "Cliente Guardado",
                        text: res.mensaje,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $("#modalAgregarCliente").modal("hide");
                    $("#formAgregarCliente")[0].reset();

                    tablaClientes.ajax.reload(null, false); // 🔥 Recargar tabla sin perder página

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.mensaje
                    });
                }

            }, "json");
        });
    </script>









</body>

</html>