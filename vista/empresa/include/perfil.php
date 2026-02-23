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