<?php require_once('validar_sesion.php'); ?>
<?php require('conexion.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title></title>

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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEmpleado">
                                    <i class="fas fa-user-tie"></i> CARGAR EMPLEADO
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tablaEmpleados" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>DNI</th>
                                                <th>Teléfono</th> <!-- Nueva columna -->
                                                <th>Rol</th>
                                                <th>Fecha Registro</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "
                                    SELECT e.id_empleado, p.nombre_pers, p.apellido_pers, p.dni_pers, r.descri_rol_emple, e.fecha_registro,
                                        t.num_tel
                                    FROM empleado e
                                    INNER JOIN persona p ON e.rela_pers = p.id_persona
                                    INNER JOIN rol_empleado r ON e.rela_rol_empleado = r.id_rol_emple
                                    LEFT JOIN telefono t ON t.rela_persona = p.id_persona
                                    ORDER BY e.fecha_registro DESC
                                                                    ";
                                            $stmt = $pdo->query($sql);
                                            foreach ($stmt as $fila): ?>
                                                <tr>
                                                    <td><?= $fila['id_empleado'] ?></td>
                                                    <td><?= htmlspecialchars($fila['nombre_pers']) ?></td>
                                                    <td><?= htmlspecialchars($fila['apellido_pers']) ?></td>
                                                    <td><?= htmlspecialchars($fila['dni_pers']) ?></td>
                                                    <td><?= htmlspecialchars($fila['num_tel'] ?? '') ?></td> <!-- Teléfono -->
                                                    <td><?= htmlspecialchars($fila['descri_rol_emple']) ?></td>
                                                    <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm editarEmpleado" data-id="<?= $fila['id_empleado'] ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                       <!-- MODAL: REGISTRAR EMPLEADO  <button class="btn btn-danger btn-sm eliminarEmpleado" data-id="<?= $fila['id_empleado'] ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>   -->
                                                    </td>
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
    </div>

    <!-- MODAL: REGISTRAR EMPLEADO -->
    <!-- MODAL: REGISTRAR EMPLEADO -->
    <div class="modal fade" id="modalEmpleado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formEmpleado" method="POST">
                    <div class="modal-header bg-primary text-white"> <!-- Encabezado azul -->
                        <h5 class="modal-title"><i class="fas fa-user-tie"></i> NUEVO EMPLEADO</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Persona -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input type="text" class="form-control" name="nombre_pers" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellido:</label>
                                    <input type="text" class="form-control" name="apellido_pers" required>
                                </div>
                                <div class="form-group">
                                    <label>DNI:</label>
                                    <input type="number" class="form-control" name="dni_pers" required>
                                </div>
                                <div class="form-group">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control" name="correo_pers">
                                </div>
                                <div class="form-group">
                                    <label>Rol:</label>
                                    <select name="rela_rol_empleado" class="form-control" required>
                                        <option value="">Seleccione un rol</option>
                                        <?php
                                        $roles = $pdo->query("SELECT id_rol_emple, descri_rol_emple FROM rol_empleado")->fetchAll();
                                        foreach ($roles as $rol) {
                                            echo "<option value='{$rol['id_rol_emple']}'>{$rol['descri_rol_emple']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Rol y teléfono -->
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label>Número de Teléfono:</label>
                                    <input type="tel" name="num_tel" class="form-control" placeholder="Ej: 3794567890" pattern="[0-9]{7,15}" required>
                                </div>

                                <div class="form-group">
                                    <label>Tipo de Teléfono:</label>
                                    <select name="rela_tip_tel" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <?php
                                        $tipos = $pdo->query("SELECT id_tip_tel, descri_tip_tel FROM tipo_telefono ORDER BY descri_tip_tel ASC")->fetchAll();
                                        foreach ($tipos as $tipo) {
                                            echo "<option value='{$tipo['id_tip_tel']}'>{$tipo['descri_tip_tel']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Usuario:</label>
                                    <input type="text" name="usuario_user" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Contraseña:</label>
                                    <input type="password" name="contrasenia_user" class="form-control" required>
                                </div>

                                <?php
                                $tipoEmpleado = $pdo->query("
                                    SELECT id_tipo_usuario 
                                    FROM tipo_usuario 
                                    WHERE descri_tip_user = 'Empleado'
                                    LIMIT 1
                                ")->fetch(PDO::FETCH_ASSOC);

                                $idTipoEmpleado = $tipoEmpleado['id_tipo_usuario'] ?? '';
                                ?>



                                <div class="form-group">
                                    <label>Tipo de usuario:</label>

                                    <!-- Select solo visual -->
                                    <select class="form-control" disabled>
                                        <option selected>Empleado</option>
                                    </select>

                                    <!-- Valor real que se envía -->
                                    <input type="hidden" name="rela_tip_user" value="<?= (int)$idTipoEmpleado ?>">
                                </div>









                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- MODAL: EDITAR EMPLEADO -->
    <!-- MODAL: EDITAR EMPLEADO -->
    <div class="modal fade" id="modalEditarEmpleado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditarEmpleado" method="POST">
                    <div class="modal-header bg-primary text-white"> <!-- Encabezado azul -->
                        <h5 class="modal-title"><i class="fas fa-edit"></i> EDITAR EMPLEADO</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_empleado" id="edit_id_empleado">
                        <input type="hidden" name="id_persona" id="edit_id_persona">

                        <div class="row">
                            <!-- Persona -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input type="text" class="form-control" name="nombre_pers" id="edit_nombre" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellido:</label>
                                    <input type="text" class="form-control" name="apellido_pers" id="edit_apellido" required>
                                </div>
                                <div class="form-group">
                                    <label>DNI:</label>
                                    <input type="number" class="form-control" name="dni_pers" id="edit_dni" required>
                                </div>
                                <div class="form-group">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control" name="correo_pers" id="edit_correo">
                                </div>
                            </div>

                            <!-- Rol y teléfono -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rol:</label>
                                    <select name="rela_rol_empleado" class="form-control" id="edit_rol" required>
                                        <option value="">Seleccione un rol</option>
                                        <?php
                                        $roles = $pdo->query("SELECT id_rol_emple, descri_rol_emple FROM rol_empleado")->fetchAll();
                                        foreach ($roles as $rol) {
                                            echo "<option value='{$rol['id_rol_emple']}'>{$rol['descri_rol_emple']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Número de Teléfono:</label>
                                    <input type="tel" name="num_tel" id="edit_num_tel" class="form-control" placeholder="Ej: 3794567890" pattern="[0-9]{7,15}">
                                </div>

                                <div class="form-group">
                                    <label>Tipo de Teléfono:</label>
                                    <select name="rela_tip_tel" id="edit_rela_tip_tel" class="form-control">
                                        <option value="">Seleccione tipo</option>
                                        <?php
                                        $tipos = $pdo->query("SELECT id_tip_tel, descri_tip_tel FROM tipo_telefono ORDER BY descri_tip_tel ASC")->fetchAll();
                                        foreach ($tipos as $tipo) {
                                            echo "<option value='{$tipo['id_tip_tel']}'>{$tipo['descri_tip_tel']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
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
        $(document).ready(function() {

            // Inicializar DataTable
            $('#tablaEmpleados').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
                }
            });

            // Registrar empleado
            $("#formEmpleado").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "controlador/insertar_empleado.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire('Éxito', response.mensaje, 'success').then(() => {
                                $("#modalEmpleado").modal('hide');
                                $("#formEmpleado")[0].reset();
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire('Error', 'Revisar consola para más detalles', 'error');
                    }
                });
            });

            // Eliminar empleado
            $(document).on('click', '.eliminarEmpleado', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: '¿Eliminar empleado?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'controlador/eliminar_empleado.php',
                            type: 'POST',
                            data: {
                                id_empleado: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (!response.error) {
                                    Swal.fire('Eliminado', response.mensaje, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', response.mensaje, 'error');
                                }
                            }
                        });
                    }
                });
            });

            // Abrir modal de edición y cargar datos
            $('.editarEmpleado').click(function() {
                let id = $(this).data('id');

                $.ajax({
                    url: 'controlador/obtener_empleado.php',
                    type: 'GET',
                    data: {
                        id_empleado: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (!res.error) {
                            let e = res.empleado;
                            $('#edit_id_empleado').val(e.id_empleado);
                            $('#edit_id_persona').val(e.id_persona);
                            $('#edit_nombre').val(e.nombre_pers);
                            $('#edit_apellido').val(e.apellido_pers);
                            $('#edit_dni').val(e.dni_pers);
                            $('#edit_correo').val(e.correo_pers);
                            $('#edit_rol').val(e.rela_rol_empleado);
                            $('#edit_num_tel').val(e.num_tel ?? '');
                            $('#edit_rela_tip_tel').val(e.rela_tip_tel ?? '');
                            $('#modalEditarEmpleado').modal('show');
                        } else {
                            Swal.fire('Error', res.mensaje, 'error');
                        }
                    }
                });
            });

            //EDICCION
            $("#formEditarEmpleado").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'controlador/actualizar_empleado.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire('Éxito', response.mensaje, 'success').then(() => {
                                $("#modalEditarEmpleado").modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire('Error', 'Revisar consola para más detalles', 'error');
                    }
                });
            });



        });
    </script>





</body>

</html>