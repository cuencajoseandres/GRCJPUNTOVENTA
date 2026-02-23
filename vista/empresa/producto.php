<?php require_once('validar_sesion.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PRODUCTO</title>

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
                                    <i class="fas fa-box"></i> Listado de Productos
                                </h6>
                                <div>
                                    <!-- Agregar proveedor -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalProveedorProducto">
                                        <i class="fas fa-truck"></i> Agregar Proveedor
                                    </button>

                                    <!-- Productos sin stock -->
                                    <a href="producto_sin_stock.php" class="btn btn-warning btn-sm">
                                        <i class="fas fa-exclamation-triangle"></i> Producto sin stock
                                    </a>

                                    <!-- Botón Exportar -->
                                    <button type="button" class="btn btn-info btn-sm" id="btnExportarProductos">
                                        <i class="fas fa-file-excel"></i> Exportar
                                    </button>

                                    <!-- Botón Importar -->
                                    <button type="button" class="btn btn-success btn-sm" id="btnImportarProductos">
                                        <i class="fas fa-file-import"></i> Importar
                                    </button>
                                    <!-- Botón Agregar producto -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCRUD">
                                        <i class="fas fa-plus-circle"></i> Agregar producto
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- Tabla -->
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>NRO</th>
                                                <th>Imagen</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Precio Público</th>
                                                <th>Precio Gremio</th>
                                                <th>Stock</th>
                                                <th>Fecha</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>


                                        <tbody>
                                            <?php
                                            include("conexion.php");
                                            $stmt = $pdo->prepare("
                                                SELECT 
                                                    p.id_producto,
                                                    p.nombre_product,
                                                    p.cod_product,
                                                    p.precio_publico_product,
                                                    p.precio_gremio_product,
                                                    p.cant_product,
                                                    p.fecha_product,
                                                    pi.ruta_imagen
                                                FROM producto p
                                                LEFT JOIN producto_imagen pi 
                                                    ON p.id_producto = pi.rela_producto 
                                                    AND pi.es_principal = 1
                                                WHERE p.cant_product >= 0
                                                ORDER BY p.cant_product DESC
                                            ");


                                            $stmt->execute();
                                            $productos = $stmt->fetchAll(PDO::FETCH_OBJ);

                                            $contador = 1; // 🔥 CONTADOR AUTOMÁTICO
                                            foreach ($productos as $fila):
                                            ?>
                                                <tr>
                                                    <!-- 🔥 CONTADOR EN VEZ DE ID -->
                                                    <td><?= $contador++ ?></td>


                                                    <td class="text-center">

                                                        <?php if ($fila->ruta_imagen): ?>

                                                            <img
                                                                src="../../<?= $fila->ruta_imagen ?>"
                                                                class="imgProductoTabla"
                                                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;cursor:pointer;"
                                                                title="Ver imagen">

                                                        <?php else: ?>

                                                            <button
                                                                class="btn btn-sm btn-outline-primary btnAgregarImagen"
                                                                data-id="<?= $fila->id_producto ?>"
                                                                title="Agregar imagen">
                                                                <i class="fas fa-image"></i>
                                                            </button>

                                                        <?php endif; ?>

                                                    </td>




                                                    <td><?= htmlspecialchars($fila->cod_product) ?></td>
                                                    <td><?= htmlspecialchars($fila->nombre_product) ?></td>

                                                    <td><?= number_format($fila->precio_publico_product, 2) ?></td>
                                                    <td><?= number_format($fila->precio_gremio_product, 2) ?></td>

                                                    <td><?= $fila->cant_product ?></td>
                                                    <td><?= $fila->fecha_product ?></td>

                                                    <td>
                                                        <button class="btn btn-warning btn-sm btnEditarProducto"
                                                            data-id="<?= $fila->id_producto ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-success btn-sm autorizarStock"
                                                            data-id="<?= $fila->id_producto ?>"
                                                            data-nombre="<?= htmlspecialchars($fila->nombre_product) ?>"
                                                            data-stock="<?= $fila->cant_product ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </button>

                                                        <button class="btn btn-info btn-sm imprimirCodigoBarra"
                                                            data-codigo="<?= htmlspecialchars($fila->cod_product) ?>"
                                                            data-nombre="<?= htmlspecialchars($fila->nombre_product) ?>">
                                                            <i class="fas fa-barcode"></i>
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
                </main>
            </div>
            <!-- Fin contenido principal -->
        </div>
        <!-- Fin contenedor principal -->
    </div>
    <!-- Fin wrapper -->

    <!-- MODAL IMAGEN PRODUCTO -->
    <div class="modal fade" id="modalImagenProducto" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imagen del producto</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <img id="imgProductoGrande"
                        src=""
                        style="max-width:100%;max-height:70vh;border-radius:10px;">
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para cargar producto -->
    <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="modalCRUDLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCRUDLabel"><i class="fas fa-box"></i> Producto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="formProducto" action="controlador/insertar_producto.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">

                                <input type="hidden" id="id_producto" name="id_producto">

                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre Producto:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tag"></i></span></div>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del producto" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="descripcion">Descripción:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-align-left"></i></span></div>
                                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del producto" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="marca">Marca:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-industry"></i></span></div>
                                            <input type="text" class="form-control" id="marca" name="marca" placeholder="Marca" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="precio_costo">Precio Costo:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                            <input type="number" class="form-control" id="precio_costo" name="precio_costo" placeholder="Precio de costo" step="0.01" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="precio_venta">Precio Venta:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                            <input type="number" class="form-control" id="precio_venta" name="precio_venta" placeholder="Precio de venta" step="0.01" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="precio_gremio">Precio Gremio:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                            <input type="number" class="form-control" id="precio_gremio" name="precio_gremio" placeholder="Precio gremio" step="0.01" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="precio_mayorista">Precio Mayorista:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                            <input type="number" class="form-control" id="precio_mayorista" name="precio_mayorista" placeholder="Precio mayorista" step="0.01" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="unidad">Unidad:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-boxes"></i></span></div>
                                            <input type="number" class="form-control" id="unidad" name="unidad" placeholder="Cantidad de unidades" min="0" required autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fecha_ingreso">Fecha de Ingreso:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_producto">Foto Producto:</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="foto_producto" name="foto_producto" accept="image/*">
                                            <label class="custom-file-label" for="foto_producto">Seleccionar archivo</label>
                                        </div>
                                    </div>

                                    <!-- Select de Categoría -->
                                    <div class="form-group">
                                        <label for="categoria">Categoría Producto:</label>
                                        <select class="form-control" id="categoria" name="categoria" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php
                                            // Uso de $pdo que debe existir en la página
                                            try {
                                                $categorias = $pdo->query("SELECT id_categoria, descri_cat FROM categoria ORDER BY descri_cat ASC")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($categorias as $cat) {
                                                    echo "<option value=\"" . htmlentities($cat['id_categoria']) . "\">" . htmlentities($cat['descri_cat']) . "</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "<option value=''>Error al cargar categorías</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Select de Proveedor -->
                                    <div class="form-group">
                                        <label for="proveedor">Proveedor:</label>
                                        <select class="form-control" id="proveedor" name="proveedor" required>
                                            <option value="">Seleccione un proveedor</option>
                                            <?php
                                            try {
                                                $proveedores = $pdo->query("
                                                    SELECT proveedor.id_proveedor, persona.nombre_pers, persona.apellido_pers
                                                    FROM proveedor
                                                    INNER JOIN persona ON proveedor.rela_pers = persona.id_persona
                                                    ORDER BY persona.nombre_pers ASC
                                                ")->fetchAll(PDO::FETCH_ASSOC);

                                                foreach ($proveedores as $prov) {
                                                    $nom = trim($prov['nombre_pers'] . ' ' . $prov['apellido_pers']);
                                                    echo "<option value=\"" . htmlentities($prov['id_proveedor']) . "\">" . htmlentities($nom) . "</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "<option value=''>Error al cargar proveedores</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Select de Estado del Producto -->
                                    <div class="form-group">
                                        <label for="estado">Estado del Producto:</label>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="">Seleccione estado</option>
                                            <?php
                                            // Cargar los estados de la tabla estado_producto
                                            $estados = $pdo->query("SELECT id_prod_estado, descri_prod_est FROM estado_producto ORDER BY descri_prod_est ASC")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($estados as $est) {
                                                echo "<option value='{$est['id_prod_estado']}'>{$est['descri_prod_est']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>


                                </div> <!-- Fin col-md-6 derecha -->

                            </div> <!-- Fin row -->
                        </div> <!-- Fin container -->
                    </div> <!-- Fin modal-body -->

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para agregar categoría -->
    <div class="modal fade" id="modalCategoriaProducto" tabindex="-1" role="dialog" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCategoriaLabel">
                        <i class="fas fa-tags"></i> Nueva Categoría
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="formCategoria" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre_categoria">Nombre Categoría:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" placeholder="Nombre de la categoría" required autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion_categoria">Descripción:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                </div>
                                <input type="text" class="form-control" id="descripcion_categoria" name="descripcion_categoria" placeholder="Descripción de la categoría" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" id="btnGuardarCategoria" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para AGREGAR PROVEEDOR -->
    <div class="modal fade" id="modalProveedorProducto" tabindex="-1" role="dialog" aria-labelledby="modalProveedorLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalProveedorLabel"><i class="fas fa-truck"></i> Nuevo Proveedor</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="formProveedor">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MODAL EDITAR PRODUCTO -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditarProducto" enctype="multipart/form-data">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id_producto" id="edit_id_producto">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Nombre</label>
                                <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>codigo producto</label>
                                <input type="text" id="edit_marca" name="marca" class="form-control" disabled>
                            </div>

                            <div class="col-md-12">
                                <label>Descripción</label>
                                <textarea id="edit_descripcion" name="descripcion" class="form-control"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label>Precio Costo</label>
                                <input type="number" step="0.01" id="edit_precio_costo" name="precio_costo" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Precio Venta</label>
                                <input type="number" step="0.01" id="edit_precio_venta" name="precio_venta" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Precio Gremio</label>
                                <input type="number" step="0.01" id="edit_precio_gremio" name="precio_gremio" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Precio Mayorista</label>
                                <input type="number" step="0.01" id="edit_precio_mayorista" name="precio_mayorista" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Unidad</label>
                                <input type="number" id="edit_unidad" name="unidad" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Fecha Ingreso</label>
                                <input type="date" id="edit_fecha_ingreso" name="fecha_ingreso" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Categoria:</label>
                                <select class="form-control" id="edit_categoria" name="categoria" required>
                                    <option value="">Seleccione categoria</option>
                                    <?php
                                    $categoria = $pdo->query("SELECT id_categoria, descri_cat FROM categoria ORDER BY descri_cat ASC")->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($categoria as $est) {
                                        echo "<option value='{$est['id_categoria']}'>{$est['descri_cat']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Proveedor:</label>
                                <select class="form-control" id="edit_proveedor" name="proveedor" required>
                                    <option value="">Seleccione un proveedor</option>
                                    <?php
                                    try {
                                        $proveedores = $pdo->query("
                                                    SELECT proveedor.id_proveedor, persona.nombre_pers, persona.apellido_pers
                                                    FROM proveedor
                                                    INNER JOIN persona ON proveedor.rela_pers = persona.id_persona
                                                    ORDER BY persona.nombre_pers ASC
                                                ")->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($proveedores as $prov) {
                                            $nom = trim($prov['nombre_pers'] . ' ' . $prov['apellido_pers']);
                                            echo "<option value=\"" . htmlentities($prov['id_proveedor']) . "\">" . htmlentities($nom) . "</option>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<option value=''>Error al cargar proveedores</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Estado del Producto:</label>
                                <select class="form-control" id="edit_estado" name="estado" required>
                                    <option value="">Seleccione estado</option>
                                    <?php
                                    $estados = $pdo->query("SELECT id_prod_estado, descri_prod_est FROM estado_producto ORDER BY descri_prod_est ASC")->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($estados as $est) {
                                        echo "<option value='{$est['id_prod_estado']}'>{$est['descri_prod_est']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Foto (opcional)</label>
                                <input type="file" id="edit_foto_producto" name="foto_producto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnEditarProducto" class="btn btn-warning">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Importar Productos -->
    <div class="modal fade" id="modalImportarProductos" tabindex="-1" role="dialog" aria-labelledby="modalImportarProductosLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalImportarProductosLabel">
                        <i class="fas fa-file-import"></i> Importar Productos
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formImportarProductos" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Seleccionar archivo Excel (.xlsx):</label>
                            <input type="file" name="archivo" class="form-control-file" accept=".xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">
                            <i class="fas fa-upload"></i> Importar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- cargar stock Stock -->
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
    <!-- MODAL AGREGAR IMAGEN -->
    <div class="modal fade" id="modalAgregarImagen" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form id="formAgregarImagen" enctype="multipart/form-data">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Agregar imagen al producto</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body text-center">

                        <input type="hidden" name="id_producto" id="id_producto_img">

                        <input type="file" name="imagen" class="form-control" accept="image/*" required>

                        <small class="text-muted d-block mt-2">
                            JPG / PNG / WEBP
                        </small>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Subir imagen
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

    <!-- Script para agregar cat, proveedores, eliminar producto, agregar producto -->
    <script>
        //Script para agregar cat
        $(document).ready(function() {
            $('#formCategoria').on('submit', function(e) {
                e.preventDefault(); // Evita recargar la página

                let $form = $(this);
                let $btn = $('#btnGuardarCategoria');

                // Cambiar texto y deshabilitar botón mientras procesa
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                $.ajax({
                    url: 'controlador/insertar_categoria.php', // PHP que procesa el insert
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(respuesta) {
                        console.log(respuesta);

                        if (!respuesta.error) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: respuesta.mensaje,
                                confirmButtonColor: '#3085d6'
                            });

                            // Resetear formulario
                            $form.trigger('reset');

                            // Cerrar modal
                            $('#modalCategoriaProducto').modal('hide');

                            // TODO: Aquí puedes actualizar la tabla con la nueva categoría sin recargar
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: respuesta.mensaje,
                                confirmButtonColor: '#e74a3b'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de servidor',
                            text: 'Ocurrió un error inesperado.',
                            confirmButtonColor: '#e74a3b'
                        });
                    },
                    complete: function() {
                        // Rehabilitar botón
                        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                    }
                });
            });
        });
        //Script para agregar proveedor
        $('#formProveedor').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $btn = $form.find('button[type="submit"]');

            // Botón cargando
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: 'controlador/insertar_proveedor.php',
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function(respuesta) {
                    if (!respuesta.error) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: respuesta.mensaje
                        }).then(() => {
                            $('#modalProveedor').modal('hide');
                            $form[0].reset();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: respuesta.mensaje
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en servidor',
                        text: 'Revisa la consola para más detalles.'
                    });
                    console.error(xhr.responseText);
                },
                complete: function() {
                    // Restablecer el botón
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                }
            });
        });
        // ELIMINAR PRODUCTO
        $(document).on('click', '.eliminarProducto', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('controlador/delete_producto.php', {
                        id: id
                    }, function(resp) {
                        if (!resp.error) {
                            Swal.fire('Eliminado', resp.mensaje, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', resp.mensaje, 'error');
                        }
                    }, 'json');
                }
            });
        });


        $("#btnNuevo").click(function() {
            $("#formProducto").trigger("reset"); // Limpia el formulario
            $("#modalCRUDLabel").text("Agregar Producto");
            $("#formProducto").attr("action", "controlador/insertar_producto.php");
            $("#btnGuardar").html('<i class="fas fa-save"></i> Guardar');
            $("#modalCRUD").modal("show");
        });
        // FORMULARIO AGREGAR PRODUCTO
        $('#formProducto').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this); // incluye archivos
            let $btn = $('#btnGuardar');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: $(this).attr('action'), // insert o update
                type: 'POST',
                data: formData,
                contentType: false, // importante para FormData
                processData: false, // importante para FormData
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        Swal.fire('Éxito', res.mensaje, 'success').then(() => {
                            $('#modalCRUD').modal('hide');
                            $('#formProducto')[0].reset();
                            location.reload(); // refrescar tabla
                        });
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Ocurrió un error inesperado', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                }
            });
        });
    </script>
    <script>
        // --- ABRIR MODAL DE EDICIÓN ---
        $(document).on('click', '.btnEditarProducto', function() {
            const id = $(this).data('id');

            $.getJSON('controlador/get_producto.php', {
                id
            }, function(resp) {
                if (!resp.error) {
                    const d = resp.data;

                    // Asignar valores al modal de edición
                    $('#edit_id_producto').val(d.id_producto);
                    $('#edit_nombre').val(d.nombre_product);
                    $('#edit_descripcion').val(d.descri_product);
                    $('#edit_marca').val(d.cod_product);
                    $('#edit_precio_costo').val(d.precio_costo_produc);
                    $('#edit_precio_venta').val(d.precio_publico_product);
                    $('#edit_precio_gremio').val(d.precio_gremio_product);
                    $('#edit_precio_mayorista').val(d.precio_mayorista_product);
                    $('#edit_unidad').val(d.cant_product);
                    $('#edit_fecha_ingreso').val(d.fecha_product);
                    $('#edit_categoria').val(d.rela_categoria);
                    $('#edit_proveedor').val(d.rela_proveedor);
                    $('#edit_estado').val(d.rela_estado); // ✅ Estado del producto

                    // Mostrar el modal
                    $('#modalEditarProducto').modal('show');
                } else {
                    Swal.fire('Error', resp.mensaje, 'error');
                }
            }).fail(() => {
                Swal.fire('Error', 'No se pudo obtener el producto.', 'error');
            });
        });

        // --- GUARDAR EDICIÓN ---
        $('#formEditarProducto').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const $btn = $('#btnEditarProducto');

            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: 'controlador/update_producto.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (!res.error) {
                        Swal.fire('Éxito', res.mensaje, 'success').then(() => {
                            $('#modalEditarProducto').modal('hide');
                            $('#formEditarProducto')[0].reset();
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                }
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
                        window.location.href = 'controlador/exportar_productos.php';
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

            // ==========================
            // IMPORTAR PRODUCTOS
            // ==========================
            $('#btnImportarProductos').on('click', function() {
                $('#modalImportarProductos').modal('show');
            });

            // 🔹 Importar archivo con mensaje de carga
            $('#formImportarProductos').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                // Mostrar SweetAlert de carga
                Swal.fire({
                    title: 'Importando productos...',
                    text: 'Por favor, espera unos segundos.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // 🔹 Muestra spinner
                    }
                });

                $.ajax({
                    url: 'controlador/importar_productos.php', // ruta de tu PHP
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(resp) {
                        Swal.close(); // 🔹 Cerrar SweetAlert de carga
                        if (!resp.error) {
                            $('#modalImportarProductos').modal('hide');
                            Swal.fire('Éxito', resp.mensaje, 'success').then(() => {
                                location.reload(); // recarga la tabla completa
                            });
                            $('#formImportarProductos')[0].reset();
                        } else {
                            Swal.fire('Error', resp.mensaje, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'No se pudo importar el archivo.', 'error');
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
        $(document).on('click', '.imprimirCodigoBarra', function() {
            const codigo = $(this).data('codigo');
            const nombre = $(this).data('nombre');

            // Construir POST invisible para descargar PDF sin abrir pestaña
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "controlador/imprimir_codigo_barra.php";
            form.style.display = "none";

            let inputCodigo = document.createElement("input");
            inputCodigo.name = "codigo";
            inputCodigo.value = codigo;

            let inputNombre = document.createElement("input");
            inputNombre.name = "nombre";
            inputNombre.value = nombre;

            form.appendChild(inputCodigo);
            form.appendChild(inputNombre);
            document.body.appendChild(form);

            form.submit(); // 🔥 DESCARGA DIRECTA
        });
    </script>


    <script>
        $(document).on('click', '.imgProductoTabla', function() {
            const src = $(this).attr('src');

            $('#imgProductoGrande').attr('src', src);
            $('#modalImagenProducto').modal('show');
        });
    </script>



    <script>
        $(document).on('submit', '#formAgregarImagen', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'controlador/subir_imagen_producto.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(resp) {

                    if (resp.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.mensaje,
                            confirmButtonColor: '#d33'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Imagen cargada',
                            text: resp.mensaje,
                            timer: 1800,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1800);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo subir la imagen'
                    });
                }
            });
        });
    </script>




    <script>
        $(document).on('click', '.btnAgregarImagen', function() {
            const idProducto = $(this).data('id');

            $('#formAgregarImagen input[name="id_producto"]').val(idProducto);
            $('#formAgregarImagen input[type="file"]').val('');

            $('#modalAgregarImagen').modal('show');
        });
    </script>










</body>

</html>