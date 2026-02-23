<?php require_once('validar_sesion.php'); ?>
<?php
 require('conexion.php');
 // Consulta para el select de tipo teléfono
$tiposTel = $pdo->query("SELECT id_tip_tel, descri_tip_tel 
FROM tipo_telefono ORDER BY descri_tip_tel ASC")->fetchAll();
 // Consulta para el select de tipo CLIENTE
$tipos = $pdo->query("SELECT id_tip_cliente, descri_tip_cliente
FROM tipo_cliente ORDER BY descri_tip_cliente ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>cj</title>

    

    <!-- Fonts y estilos -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- MENU LATERAL -->
        <?php include 'include/menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- PERFIL USUARIO -->
                <?php include 'include/perfil.php'; ?>

                <main class="container-fluid mt-4">
                    <div id="tablas-contenedor">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCliente">
                                    <i class="fas fa-user-plus"></i> Nuevo Cliente
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tablaClientes" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre cliente</th>
                                                <th>Aellido</th>
                                                <th>numero de telefono</th>
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
                                            


                                            foreach ($tipos as $y): 
                                        ?>
                                            <option value="<?= (int)$y['id_tip_cliente'] ?>">
                                                <?= htmlspecialchars($y['descri_tip_cliente']) ?>
                                            </option>
                                        <?php endforeach; ?>
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
    <!-- MODAL EDITAR CLIENTE -->
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form id="formEditarCliente">

                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-edit"></i> Editar Cliente
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_clientes" id="edit_id_clientes">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input type="text" id="edit_nombre" name="nombre_pers" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Apellido:</label>
                                    <input type="text" id="edit_apellido" name="apellido_pers" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teléfono:</label>
                                    <input type="text" id="edit_telefono" name="num_tel" class="form-control" required>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Guardar Cambios
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
        let tablaClientes;

        // CARGAR TABLA CON AJAX
        function cargarTabla() {
            tablaClientes = $("#tablaClientes").DataTable({

                destroy: true,
                ajax: {
                    url: "controlador/listar_clientes.php",
                    type: "GET",
                    dataSrc: ""
                },
                columns: [{
                        data: "id_clientes"
                    },
                    {
                        data: "nombre"
                    },
                    {
                        data: "apellido"
                    },
                    {
                        data: "telefono"
                    },
                    {
                        data: null,
                        render: function(data) {

                            let whatsapp = data.telefono ?
                                `https://wa.me/54${data.telefono}` :
                                "#";

                            return `
                            <button class="btn btn-primary btn-sm editar" data-id="${data.id_clientes}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <a href="${whatsapp}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        `;
                        }
                    }
                ]
            });
        }

        cargarTabla();


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



        // ABRIR MODAL EDITAR
        $("#tablaClientes").on("click", ".editar", function() {

            let id = $(this).data("id");

            $.ajax({
                url: "controlador/obtener_cliente.php",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(data) {
                    $("#edit_id_clientes").val(data.id_clientes);
                    $("#edit_nombre").val(data.nombre);
                    $("#edit_apellido").val(data.apellido);
                    $("#edit_telefono").val(data.telefono);

                    $("#modalEditarCliente").modal("show");
                }
            });

        });

        // GUARDAR EDICIÓN
        $("#formEditarCliente").on("submit", function(e) {
            e.preventDefault();

            $.post("controlador/editar_cliente.php", $(this).serialize(), function(res) {

                if (!res.error) {
                    Swal.fire({
                        icon: "success",
                        title: "Cliente actualizado",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $("#modalEditarCliente").modal("hide");
                    tablaClientes.ajax.reload(null, false);
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