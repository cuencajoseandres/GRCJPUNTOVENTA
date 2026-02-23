<?php require_once('validar_sesion.php'); ?>
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
                            <h5 class="mb-0"><i class="fas fa-cash-register"></i> GENERAR PRESUPUESTO</h5>
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
                                        <label>Cliente:</label>
                                        <div class="input-group">
                                            <select name="cliente" class="form-control select2" id="selectCliente" required>
                                                <option value="">Seleccione un cliente</option>
                                                <?php
                                                $clientes = $pdo->query("
                                                SELECT c.id_clientes, CONCAT(p.nombre_pers,' ',p.apellido_pers) AS nombre
                                                FROM clientes c
                                                INNER JOIN persona p ON c.rela_persona = p.id_persona
                                                ORDER BY p.nombre_pers ASC
                                            ")->fetchAll();
                                                foreach ($clientes as $cli) {
                                                    echo "<option value='{$cli['id_clientes']}'>{$cli['nombre']}</option>";
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modalClienteNuevo">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
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
                                            $stmtP = $pdo->query("SELECT id_producto, nombre_product, precio_gremio_product, precio_publico_product, cant_product FROM producto ORDER BY nombre_product");
                                            while ($p = $stmtP->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$p['id_producto']}' 
                                                data-precio_gremio='{$p['precio_gremio_product']}' 
                                                data-precio_publico='{$p['precio_publico_product']}' 
                                                data-stock='{$p['cant_product']}' 
                                                data-tipo='producto'>{$p['nombre_product']}</option>";
                                            }
                                            // Servicios
                                            $stmtS = $pdo->query("SELECT id_servicio, nombre_serv, precio_serv FROM servicio ORDER BY nombre_serv");
                                            while ($s = $stmtS->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$s['id_servicio']}' data-precio='{$s['precio_serv']}' data-tipo='servicio'>{$s['nombre_serv']}</option>";
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

                                <div class="text-right">
                                    <h5>Total: $<span id="totalVenta">0.00</span></h5>
                                </div>

                                <div class="text-right mt-3">
                                    <button type="button" id="btnPresupuesto" class="btn btn-success">
                                        <i class="fas fa-file-pdf"></i> Generar Presupuesto
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
    <div class="modal fade" id="modalClienteNuevo" tabindex="-1" role="dialog" aria-labelledby="modalClienteNuevoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formClienteNuevo">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalClienteNuevoLabel">Registrar Cliente Nuevo</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre_pers" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="apellido_pers" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" name="dni_pers" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" name="correo_pers" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar Cliente</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#item').select2({
                placeholder: "Buscar producto o servicio...",
                allowClear: true,
                width: '100%'
            });

            // variables
            let detalles = [];
            let totalVenta = 0;
            $('#num_venta').val('VENTA-' + Date.now().toString().slice(-6));

            // helper: recalcula subtotales y muestra tabla
            const actualizarTabla = () => {
                let html = '';
                totalVenta = 0;

                detalles.forEach((d, i) => {
                    // asegurar números
                    d.cantidad = parseFloat(d.cantidad) || 0;
                    d.precio = parseFloat(d.precio) || 0;
                    d.subtotal = d.cantidad * d.precio;
                    totalVenta += d.subtotal;

                    html += `
                <tr>
                    <td>${d.nombre}</td>
                    <td>${d.tipo}</td>
                    <td>${d.cantidad}</td>
                    <td>$${d.precio.toFixed(2)}</td>
                    <td>$${d.subtotal.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btnEliminar" data-index="${i}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                });

                $('#tablaDetalle tbody').html(html);
                $('#totalVenta').text(totalVenta.toFixed(2));
                console.log('Tabla actualizada. totalVenta=', totalVenta, 'detalles=', detalles);
            };

            // Cuando cambia la selección en el select2
            $('#item').on('change', function() {
                let opcion = $(this).find(':selected');
                if (!opcion || opcion.length === 0) {
                    $('#precio').val('');
                    return;
                }

                let tipo = opcion.data('tipo');
                let tipoVenta = $('#tipoVenta').val();
                let precio = 0;

                if (tipo === 'producto') {
                    let precioGremio = parseFloat(opcion.data('precio_gremio')) || 0;
                    let precioPublico = parseFloat(opcion.data('precio_publico')) || 0;
                    precio = (tipoVenta === 'mayorista') ? precioGremio : precioPublico;

                    let stock = parseFloat(opcion.data('stock')) || 0;
                    $('#cantidad').attr('max', stock);
                } else { // servicio
                    precio = parseFloat(opcion.data('precio')) || 0;
                    $('#cantidad').removeAttr('max');
                }

                $('#precio').val(precio);
                console.log('Cambio item -> tipo:', tipo, 'tipoVenta:', tipoVenta, 'precio seleccionado:', precio);
            });

            // Agregar al detalle
            $('#btnAgregar').on('click', function() {
                let opcion = $('#item').find(':selected');
                if (!opcion || opcion.length === 0) {
                    Swal.fire('Atención', 'Seleccione un item válido.', 'warning');
                    return;
                }

                let id = opcion.val();
                let nombre = opcion.text();
                let tipo = opcion.data('tipo');
                let cantidad = parseFloat($('#cantidad').val()) || 0;
                let precio = parseFloat($('#precio').val()) || 0;
                let stock = parseFloat(opcion.data('stock')) || 0;

                if (!id || cantidad <= 0) {
                    Swal.fire('Atención', 'Seleccione un item y cantidad válida.', 'warning');
                    return;
                }
                if (tipo === 'producto' && cantidad > stock) {
                    Swal.fire('Error', 'Cantidad supera el stock disponible', 'error');
                    return;
                }

                // Si ya existe el mismo producto/servicio lo sumamos en cantidad (opcional)
                let idxExist = detalles.findIndex(x => x.id == id && x.tipo == tipo);
                if (idxExist !== -1) {
                    detalles[idxExist].cantidad += cantidad;
                    detalles[idxExist].precio = precio; // actualizar precio según selección actual
                    detalles[idxExist].subtotal = detalles[idxExist].cantidad * detalles[idxExist].precio;
                } else {
                    detalles.push({
                        id: id,
                        nombre: nombre,
                        tipo: tipo,
                        cantidad: cantidad,
                        precio: precio,
                        subtotal: cantidad * precio
                    });
                }

                actualizarTabla();
                // mantener select y cantidad/prcio listos
                $('#item').val(null).trigger('change'); // limpiar selección
                $('#cantidad').val(1);
                $('#precio').val('');
            });

            // Eliminar item
            $(document).on('click', '.btnEliminar', function() {
                let index = $(this).data('index');
                if (typeof index === 'undefined') return;
                detalles.splice(index, 1);
                actualizarTabla();
            });

            // Toggle minorista/mayorista
            $('#toggleTipoVenta').on('click', function() {
                // leer valor actual
                let tipoActual = $('#tipoVenta').val();
                // alternar
                if (tipoActual === 'minorista') {
                    $('#tipoVenta').val('mayorista');
                    $(this).text('Mayorista').removeClass('btn-info').addClass('btn-warning');
                } else {
                    $('#tipoVenta').val('minorista');
                    $(this).text('Minorista').removeClass('btn-warning').addClass('btn-info');
                }

                console.log('Toggle tipoVenta ->', $('#tipoVenta').val());

                // actualizar precios de LOS ITEMS YA AGREGADOS
                detalles = detalles.map(d => {
                    // buscamos la opción original (puede ser producto o servicio)
                    let opcion = $('#item').find(`option[value="${d.id}"]`);
                    if (opcion && opcion.length) {
                        if (d.tipo === 'producto') {
                            let precioGremio = parseFloat(opcion.data('precio_gremio')) || 0;
                            let precioPublico = parseFloat(opcion.data('precio_publico')) || 0;
                            d.precio = ($('#tipoVenta').val() === 'mayorista') ? precioGremio : precioPublico;
                        } else if (d.tipo === 'servicio') {
                            // para servicios usamos data-precio (no cambia con tipoVenta)
                            d.precio = parseFloat(opcion.data('precio')) || d.precio || 0;
                        }
                    } else {
                        console.warn('Opción no encontrada para id=', d.id, '. No se actualizó su precio.');
                    }
                    d.subtotal = d.cantidad * d.precio;
                    return d;
                });

                // si el usuario tiene un item seleccionado, actualizar el campo precio que muestra
                $('#item').trigger('change');

                actualizarTabla();
                // mostrar notificación corta
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tipo de venta: ' + ($('#tipoVenta').val() === 'mayorista' ? 'Mayorista' : 'Minorista'),
                    showConfirmButton: false,
                    timer: 1000
                });
            });

            // Envío de venta o presupuesto (mantener igual que antes)
            $('#formVenta').submit(function(e) {
                e.preventDefault();
                if (detalles.length === 0) {
                    Swal.fire('Error', 'Agregue al menos un item.', 'error');
                    return;
                }

                let datos = {
                    num_venta: $('#num_venta').val(),
                    fecha_fact: $('#fecha_fact').val(),
                    empleado: $('select[name="empleado"]').val(),
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

            // Generar presupuesto (igual)
            $('#btnPresupuesto').on('click', function() {
                if (detalles.length === 0) {
                    Swal.fire('Error', 'Agregue al menos un item.', 'error');
                    return;
                }

                let datos = {
                    num_presupuesto: 'PRES-' + Date.now().toString().slice(-6),
                    fecha: $('#fecha_fact').val(),
                    cliente: $('#selectCliente option:selected').text(),
                    tipoVenta: $('#tipoVenta').val(),
                    total: totalVenta,
                    detalles: detalles
                };

                Swal.fire({
                    title: 'Generando presupuesto...',
                    text: 'Por favor espere un momento.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: 'controlador/generar_presupuesto_pdf.php',
                    type: 'POST',
                    data: {
                        data: JSON.stringify(datos)
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        Swal.close();
                        const blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = datos.num_presupuesto + '.pdf';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function(xhr) {
                        Swal.close();
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo generar el presupuesto.', 'error');
                    }
                });
            });

            // Registrar cliente nuevo (igual)
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

            // fecha por defecto
            $('#fecha_fact').val(new Date().toISOString().split('T')[0]);
        });
    </script>


</body>

</html>