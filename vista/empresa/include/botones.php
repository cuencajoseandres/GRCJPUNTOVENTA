 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
     <!-- Botón para ocultar sidebar en pantallas pequeñas -->
     <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
         <i class="fa fa-bars"></i>
     </button>

     <!-- Contenedor flex para elementos de la barra -->
     <div class="d-flex align-items-center w-100">

         <!-- Información de cadetes disponibles en un badge -->
         <div class="mr-auto d-flex align-items-center">
             <!-- FECHA Y HORA EN TIEMPO REAL -->
    <span id="fechaHora" class="badge badge-primary shadow-sm p-2"></span>
         </div>

         <!-- Menú derecho de la barra superior -->
         <ul class="navbar-nav ml-auto align-items-center">

             <!-- Búsqueda compacta (solo móvil visible en dropdown) -->
             <li class="nav-item dropdown no-arrow d-sm-none mr-2">
                 <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown">
                     <i class="fas fa-search fa-fw"></i>
                 </a>
                 <div class="dropdown-menu dropdown-menu-right p-2 shadow animated--grow-in" aria-labelledby="searchDropdown">
                     <form class="form-inline w-100">
                         <div class="input-group">
                             <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Buscar">
                             <div class="input-group-append">
                                 <button class="btn btn-primary" type="button">
                                     <i class="fas fa-search fa-sm"></i>
                                 </button>
                             </div>
                         </div>
                     </form>
                 </div>
             </li>

             <!-- Separador visual -->
             <div class="topbar-divider d-none d-sm-block"></div>

             <!-- Menú de usuario con dropdown y foto redonda -->
             <!-- Menú de usuario mejorado -->
             <li class="nav-item dropdown no-arrow">
                 <a class="nav-link dropdown-toggle d-flex align-items-center"
                     href="#" id="userDropdown" data-toggle="dropdown"
                     style="padding: 8px 14px; border-radius: 8px; transition: 0.2s;">

                     <span class="mr-3 d-none d-lg-inline text-gray-800 small font-weight-bold"
                         style="font-size: 15px;">
                         <?= htmlspecialchars($usuario . ' (' . $nombre_tipo_user . ')') ?>
                     </span>

                     <img class="img-profile rounded-circle shadow"
                         src="img/logo-dd.png"
                         alt="Perfil"
                         style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #ddd;">
                 </a>

                 <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                     style="min-width: 200px; border-radius: 8px;">

                     <h6 class="dropdown-header text-primary font-weight-bold">
                         Mi Cuenta
                     </h6>

                     <a class="dropdown-item" href="perfil_empresa.php"
                         style="padding: 10px 15px;">
                         <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-500"></i>
                         Perfil
                     </a>

                     <a class="dropdown-item" href="#"
                         style="padding: 10px 15px;">
                         <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-500"></i>
                         Configuración
                     </a>

                     <div class="dropdown-divider"></div>

                     <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"
                         style="padding: 10px 15px;">
                         <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-500"></i>
                         Cerrar sesión
                     </a>
                 </div>
             </li>
         </ul>
     </div>
 </nav>



 <div class="container-fluid">
     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <?php
            // echo '<h1 class="h3 mb-0 text-gray-800">' . $localidad . '</h1>';
            ?>

     </div>

     <!-- Content Row -->
     <!-- Content Row -->
     <div class="row">

         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-primary shadow h-100 py-2">
                 <div class="card-body">
                     <a href="pedido_disponible.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">INSUMOS AL PUBLICO</div>

                                 <?php
                                    /* require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_pedido, fecha_inicio, descripcion_ped, direccion_ped, nombre_pers, apellido, estado
                                              FROM pedido, cliente, usuario, persona
                                              where rela_usuario=id_usuario and rela_persona=id_persona and rela_cliente=id_cliente  and estado= 'activo'");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';

*/
                                    ?>

                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-calendar fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>
                 </div>
             </div>
         </div>

         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-success shadow h-100 py-2">
                 <div class="card-body">
                     <a href="pedido_confirmado.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-success text-uppercase mb-1">INSUMOS AL GREMIO</div>
                                 <?php
                                    /*require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_oferta, ID_PEDIDO, nombre_pers, apellido, direccion_ped, descripcion_ped,  monto_oferta, duracion_oferta, nombre_empresa, situacion_oferta
                                              FROM oferta_pedido, persona, pedido, empresa, cliente
                                             WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido
                                             and  rela_empresa=id_empresa and situacion_oferta='Aceptado' and id_empresa= '" . $id_empresa . "' ");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                    ?>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-comments fa-2x text-gray-300  "></i>
                             </div>
                         </div>
                     </a>

                 </div>
             </div>
         </div>

         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-info shadow h-100 py-2">
                 <div class="card-body">
                     <a href="tabla_venta.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-info text-uppercase mb-1">VENTAS REALIZADAS</div>
                                 <div class="row no-gutters align-items-center">
                                     <div class="col-auto">
                                         <?php
                                            /*require('conexion.php');

                                            $correo_existe = $pdo->query("SELECT id_oferta, nombre_pers, apellido, direccion_ped, descripcion_ped, monto_oferta, duracion_oferta, nombre_empresa, situacion_oferta, rela_empleado 
                                              FROM oferta_pedido, persona, pedido, empresa, cliente 
                                              WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido and rela_empresa=id_empresa and situacion_oferta='Enproceso' and id_empresa= '" . $id_empresa . "'");
                                            $correo_existe->execute();
                                            $pedido_disponible = $correo_existe->rowCount();
                                            echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                            ?>
                                     </div>

                                 </div>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>

                 </div>
             </div>
         </div>

         <!-- Pending Requests Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-warning shadow h-100 py-2">
                 <div class="card-body">
                     <a href="historial_pedido.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">INSUMOS FALTANTES</div>
                                 <?php
                                    /*  require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_oferta, nombre_pers, apellido, direccion_ped, descripcion_ped, monto_oferta, duracion_oferta, id_empresa, situacion_oferta, rela_empleado 
                                              FROM oferta_pedido, persona, pedido, empresa, cliente 
                                              WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido and rela_empresa=id_empresa and situacion_oferta='completado' and id_empresa= '" . $id_empresa . "'");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                    ?>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>
                 </div>
             </div>
         </div>
         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-primary shadow h-100 py-2">
                 <div class="card-body">
                     <a href="pedido_disponible.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">SERVICIOS AL PUBLICO</div>

                                 <?php
                                    /* require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_pedido, fecha_inicio, descripcion_ped, direccion_ped, nombre_pers, apellido, estado
                                              FROM pedido, cliente, usuario, persona
                                              where rela_usuario=id_usuario and rela_persona=id_persona and rela_cliente=id_cliente  and estado= 'activo'");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                    ?>

                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-calendar fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>
                 </div>
             </div>
         </div>

         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-success shadow h-100 py-2">
                 <div class="card-body">
                     <a href="pedido_confirmado.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-success text-uppercase mb-1">SERVICIOS AL GREMIO</div>
                                 <?php
                                    /*require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_oferta, ID_PEDIDO, nombre_pers, apellido, direccion_ped, descripcion_ped,  monto_oferta, duracion_oferta, nombre_empresa, situacion_oferta
                                              FROM oferta_pedido, persona, pedido, empresa, cliente
                                             WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido
                                             and  rela_empresa=id_empresa and situacion_oferta='Aceptado' and id_empresa= '" . $id_empresa . "' ");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';

*/
                                    ?>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-comments fa-2x text-gray-300  "></i>
                             </div>
                         </div>
                     </a>

                 </div>
             </div>
         </div>

         <!-- Earnings (Monthly) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-info shadow h-100 py-2">
                 <div class="card-body">
                     <a href="pedido_encurso.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-info text-uppercase mb-1">SERVICIOS EN PROCESO</div>
                                 <div class="row no-gutters align-items-center">
                                     <div class="col-auto">
                                         <?php
                                            /*require('conexion.php');

                                            $correo_existe = $pdo->query("SELECT id_oferta, nombre_pers, apellido, direccion_ped, descripcion_ped, monto_oferta, duracion_oferta, nombre_empresa, situacion_oferta, rela_empleado 
                                              FROM oferta_pedido, persona, pedido, empresa, cliente 
                                              WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido and rela_empresa=id_empresa and situacion_oferta='Enproceso' and id_empresa= '" . $id_empresa . "'");
                                            $correo_existe->execute();
                                            $pedido_disponible = $correo_existe->rowCount();
                                            echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                            ?>
                                     </div>

                                 </div>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>

                 </div>
             </div>
         </div>

         <!-- Pending Requests Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-warning shadow h-100 py-2">
                 <div class="card-body">
                     <a href="historial_pedido.php">
                         <div class="row no-gutters align-items-center">
                             <div class="col mr-2">
                                 <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">SERVICIOS COMPLETADO</div>
                                 <?php
                                    /* require('conexion.php');

                                    $correo_existe = $pdo->query("SELECT id_oferta, nombre_pers, apellido, direccion_ped, descripcion_ped, monto_oferta, duracion_oferta, id_empresa, situacion_oferta, rela_empleado 
                                              FROM oferta_pedido, persona, pedido, empresa, cliente 
                                              WHERE rela_persona=id_persona and rela_cliente=id_cliente and rela_pedido=id_pedido and rela_empresa=id_empresa and situacion_oferta='completado' and id_empresa= '" . $id_empresa . "'");
                                    $correo_existe->execute();
                                    $pedido_disponible = $correo_existe->rowCount();
                                    echo '<h1 class="h5 mb-0 font-weight-bold text-gray-800">' . $pedido_disponible . '</h1>';
*/

                                    ?>
                             </div>
                             <div class="col-auto">
                                 <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                             </div>
                         </div>
                     </a>
                 </div>
             </div>
         </div>

     </div>

     <!-- Content Row -->
 </div>

 
<script>
function actualizarFechaHora() {
    const ahora = new Date();

    // Formatear fecha
    const fecha = ahora.toLocaleDateString('es-AR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Formatear hora
    const hora = ahora.toLocaleTimeString('es-AR');

    // Insertar en el span
    document.getElementById('fechaHora').textContent = `${fecha} - ${hora}`;
}

// Actualizar cada segundo
setInterval(actualizarFechaHora, 1000);

// Ejecutar una vez al cargar
actualizarFechaHora();
</script>