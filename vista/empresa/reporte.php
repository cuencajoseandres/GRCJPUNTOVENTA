<?php
require_once('validar_sesion.php');
require('conexion.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reporte Ventas y Servicios</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
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


                        <div class="container mt-4">
                            <h3>Reporte de Ventas y Servicios</h3>

                            <!-- Filtros de fechas -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <input type="date" id="fecha_inicio" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" id="fecha_fin" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary" id="btnFiltrar">Filtrar</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-info filtroTiempo" data-tipo="hoy">Hoy</button>
                                    <button class="btn btn-info filtroTiempo" data-tipo="semana">Esta semana</button>
                                    <button class="btn btn-info filtroTiempo" data-tipo="mes">Este mes</button>
                                    <button class="btn btn-info filtroTiempo" data-tipo="años">Este años</button>
                                </div>
                            </div>
                            <!-- Totales cantidad -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Productos Vendidos</h5>
                                            <p class="card-text" id="totalProductos">0</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Servicios Realizados</h5>
                                            <p class="card-text" id="totalServicios">0</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">GASTOS MENSUAL</h5>
                                            <p class="card-text" id="totalgastos">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Ingresos en dinero -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ingreso Productos</h5>
                                            <p class="card-text" id="ingresoProductos">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ingreso Servicios</h5>
                                            <p class="card-text" id="ingresoServicios">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ingreso Total</h5>
                                            <p class="card-text" id="ingresoTotal">$0.00</p>
                                        </div>
                                    </div>
                                </div>








                            </div>
                            <!-- Ganancias -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ganancia Productos</h5>
                                            <p class="card-text" id="gananciaProductos">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-warning mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ganancia Servicios</h5>
                                            <p class="card-text" id="gananciaServicios">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Ganancia Total</h5>
                                            <p class="card-text" id="gananciaTotal">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Tablas -->
                            <h5>Detalle Productos</h5>
                            <table id="tablaProductos" class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <h5>Detalle Servicios</h5>
                            <table id="tablaServicios" class="table table-bordered">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <!-- Gráficos -->
                            <h5>Resumen de Cantidad Vendida</h5>
                            <canvas id="ventasChart" width="400" height="150"></canvas>

                            <h5>Ganancias por Día</h5>
                            <canvas id="gananciasChart" width="400" height="150"></canvas>
                        </div>

                        
                    </div>
                </main>
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

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var ctxVentas = document.getElementById('ventasChart').getContext('2d');
        var ventasChart = new Chart(ctxVentas, {
            type: 'bar',
            data: {
                labels: ['Productos', 'Servicios'],
                datasets: [{
                    label: 'Cantidad Vendida/Realizada',
                    data: [0, 0],
                    backgroundColor: ['#007bff', '#28a745']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        var ctxGanancias = document.getElementById('gananciasChart').getContext('2d');
        var gananciasChart = new Chart(ctxGanancias, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                        label: 'Productos',
                        data: [],
                        borderColor: '#007bff',
                        fill: false
                    },
                    {
                        label: 'Servicios',
                        data: [],
                        borderColor: '#28a745',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        function cargarReporte(inicio = '', fin = '') {
            $.ajax({
                url: 'reporte_ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    fecha_inicio: inicio,
                    fecha_fin: fin
                },
                success: function(res) {
                    if (res.error) {
                        Swal.fire('Error', res.mensaje, 'error');
                        return;
                    }

                    // Totales cantidad
                    $('#totalProductos').text(res.totales.cantidad_productos_vendidos ?? 0);
                    $('#totalServicios').text(res.totales.cantidad_servicios_realizados ?? 0);

                    // Totales dinero
                    $('#ingresoProductos').text(`$${parseFloat(res.totales.total_productos_vendidos).toFixed(2)}`);
                    $('#ingresoServicios').text(`$${parseFloat(res.totales.total_servicios_realizados).toFixed(2)}`);
                    $('#ingresoTotal').text(`$${parseFloat(res.totales.total_general).toFixed(2)}`);

                    // Ganancia neta
                    // Totales ganancia
                    $('#gananciaProductos').text(`$${parseFloat(res.totales.ganancia_neta_productos).toFixed(2)}`);
                    $('#gananciaServicios').text(`$${parseFloat(res.totales.ganancia_servicios).toFixed(2)}`);
                    $('#gananciaTotal').text(`$${parseFloat(res.totales.ganancia_total).toFixed(2)}`);

                    // Tabla productos
                    let htmlProd = '';
                    res.productos.forEach(p => {
                        htmlProd += `<tr>
                    <td>${p.cod_product}</td>
                    <td>${p.nombre_product}</td>
                    <td>${p.cantidad}</td>
                    <td>$${parseFloat(p.total).toFixed(2)}</td>
                </tr>`;
                    });
                    $('#tablaProductos tbody').html(htmlProd);

                    // Tabla servicios
                    let htmlServ = '';
                    res.servicios.forEach(s => {
                        htmlServ += `<tr>
                    <td>${s.codigo_serv}</td>
                    <td>${s.nombre_serv}</td>
                    <td>${s.cantidad}</td>
                    <td>$${parseFloat(s.total).toFixed(2)}</td>
                </tr>`;
                    });
                    $('#tablaServicios tbody').html(htmlServ);

                    // Gráfico cantidad
                    ventasChart.data.datasets[0].data = [
                        res.totales.cantidad_productos_vendidos ?? 0,
                        res.totales.cantidad_servicios_realizados ?? 0
                    ];
                    ventasChart.update();

                    // Gráfico ganancias
                    gananciasChart.data.labels = res.ganancias.fechas;
                    gananciasChart.data.datasets[0].data = res.ganancias.productos;
                    gananciasChart.data.datasets[1].data = res.ganancias.servicios;
                    gananciasChart.update();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar el reporte.', 'error');
                }
            });
        }


        // Inicial
        $(document).ready(function() {
            cargarReporte();

            $('#btnFiltrar').on('click', function() {
                cargarReporte($('#fecha_inicio').val(), $('#fecha_fin').val());
            });

            $('.filtroTiempo').on('click', function() {
                let tipo = $(this).data('tipo');
                $.ajax({
                    url: 'reporte_ajax.php',
                    type: 'POST',
                    data: {
                        filtro: tipo
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#fecha_inicio').val(res.inicio);
                        $('#fecha_fin').val(res.fin);
                        cargarReporte(res.inicio, res.fin);
                    }
                });
            });
        });
    </script>
</body>

</html>