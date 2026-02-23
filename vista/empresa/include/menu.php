<?php
$paginaActual = basename($_SERVER['PHP_SELF']);

function activeItem($archivo)
{
    global $paginaActual;
    return $paginaActual === $archivo ? 'active' : '';
}

function openMenu($archivos = [])
{
    global $paginaActual;
    return in_array($paginaActual, $archivos) ? 'show' : '';
}

function expandedMenu($archivos = [])
{
    global $paginaActual;
    return in_array($paginaActual, $archivos) ? 'true' : 'false';
}
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Logo -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio_empresa.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-laptop-code"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            CJ POS<br>
            <small style="font-size:12px;">Punto de Venta</small>
        </div>

    </a>

    <hr class="sidebar-divider my-0">

    <?php
    $tipoUsuario = (int)$_SESSION['usuario']['tipo_usuario'];

    $permisosMenu = [
        1 => [ // ADMIN
            "INICIO",
            "VENTAS",
            "PRODUCTOS",
            "SERVICIOS",
            "GASTOS",
            "EMPLEADOS",
            "REPORTES",
            "CLIENTES",
            "CONFIGURACION",
            "PERFIL"
        ],

        2 => [ // SUPERVISOR
            "INICIO",
            "VENTAS",
            "PRODUCTOS",
            "SERVICIOS",
            "GASTOS",
            "REPORTES",
            "CLIENTES",
            "PERFIL"
        ],

        3 => [ // EMPLEADO
            "INICIO",
            "VENTAS",
            "PRODUCTOS",
            "SERVICIOS",
            "GASTOS",
            "CLIENTES",
            "PERFIL"
        ],
    ];



    if (!isset($permisosMenu[$tipoUsuario])) {
        header('Location: ../../index.php');
        exit;
    }
    ?>

    <!-- INICIO -->
    <?php if (in_array("INICIO", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item <?= activeItem('inicio_empresa.php') ?>">
            <a class="nav-link" href="inicio_empresa.php">
                <i class="fas fa-fw fa-home"></i>
                <span>Inicio</span>
            </a>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>

    <!-- VENTAS -->
    <?php if (in_array("VENTAS", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed d-flex justify-content-between align-items-center"
                href="#"
                data-toggle="collapse"
                data-target="#ventasMenu"
                aria-expanded="<?= expandedMenu(['venta_producto_servicio.php', 'tbla_vent_servi.php', 'presupuesto.php']) ?>">

                <span><i class="fas fa-fw fa-shopping-cart mr-2"></i>Ventas</span>

            </a>

            <div id="ventasMenu"
                class="collapse <?= openMenu(['venta_producto_servicio.php', 'tbla_vent_servi.php', 'presupuesto.php']) ?>"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= activeItem('venta_producto_servicio.php') ?>" href="venta_producto_servicio.php">
                        <i class="fas fa-cash-register mr-2"></i>Registrar venta
                    </a>

                    <a class="collapse-item <?= activeItem('tbla_vent_servi.php') ?>" href="tbla_vent_servi.php">
                        <i class="fas fa-list mr-2"></i>Historial de ventas
                    </a>

                    <a class="collapse-item <?= activeItem('presupuesto.php') ?>" href="presupuesto.php">
                        <i class="fas fa-file-signature mr-2"></i>Presupuestos
                    </a>
                </div>

            </div>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>

    <!-- PRODUCTOS / STOCK -->
    <?php if (in_array("PRODUCTOS", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed d-flex justify-content-between align-items-center"
                href="#"
                data-toggle="collapse"
                data-target="#productosMenu"
                aria-expanded="<?= expandedMenu(['producto.php']) ?>">

                <span><i class="fas fa-fw fa-box mr-2"></i>Productos / Stock</span>

            </a>

            <div id="productosMenu"
                class="collapse <?= openMenu(['producto.php']) ?>"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= activeItem('producto.php') ?>" href="producto.php">
                        <i class="fas fa-box-open mr-2"></i>Ver productos
                    </a>
                </div>

            </div>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>

    <!-- SERVICIOS -->
    <?php if (in_array("SERVICIOS", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed d-flex justify-content-between align-items-center"
                href="#"
                data-toggle="collapse"
                data-target="#serviciosMenu"
                aria-expanded="<?= expandedMenu(['servicio.php', 'servicio_nuevo.php']) ?>">

                <span><i class="fas fa-fw fa-tools mr-2"></i>Servicios</span>

            </a>

            <div id="serviciosMenu"
                class="collapse <?= openMenu(['servicio.php', 'servicio_nuevo.php']) ?>"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= activeItem('servicio.php') ?>" href="servicio.php">
                        <i class="fas fa-concierge-bell mr-2"></i>Ver servicios
                    </a>
                </div>

            </div>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>

    <!-- ADMINISTRACIÓN -->
    <?php if (
        in_array("GASTOS", $permisosMenu[$tipoUsuario]) ||
        in_array("EMPLEADOS", $permisosMenu[$tipoUsuario]) ||
        in_array("REPORTES", $permisosMenu[$tipoUsuario])
    ): ?>
        <li class="nav-item">
            <a class="nav-link collapsed"
                href="#"
                data-toggle="collapse"
                data-target="#adminMenu"
                aria-expanded="<?= expandedMenu(['gasto.php', 'empleado.php', 'reporte.php']) ?>">

                <span><i class="fas fa-fw fa-cogs mr-2"></i>Administración</span>
            </a>

            <div id="adminMenu"
                class="collapse <?= openMenu(['gasto.php', 'empleado.php', 'reporte.php']) ?>"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">

                    <?php if (in_array("GASTOS", $permisosMenu[$tipoUsuario])): ?>
                        <a class="collapse-item <?= activeItem('gasto.php') ?>" href="gasto.php">
                            <i class="fas fa-money-bill-wave mr-2"></i>Gastos
                        </a>
                    <?php endif; ?>

                    <?php if (in_array("EMPLEADOS", $permisosMenu[$tipoUsuario])): ?>
                        <a class="collapse-item <?= activeItem('empleado.php') ?>" href="empleado.php">
                            <i class="fas fa-id-badge mr-2"></i>Empleados
                        </a>
                    <?php endif; ?>

                    <?php if (in_array("REPORTES", $permisosMenu[$tipoUsuario])): ?>
                        <a class="collapse-item <?= activeItem('reporte.php') ?>" href="reporte.php">
                            <i class="fas fa-chart-line mr-2"></i>Reportes
                        </a>
                    <?php endif; ?>

                </div>

            </div>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>


    <!-- CLIENTES -->
    <?php if (in_array("CLIENTES", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item <?= activeItem('clientes.php') ?>">
            <a class="nav-link" href="clientes.php">
                <i class="fas fa-fw fa-user-friends"></i>
                <span>Clientes</span>
            </a>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>


    <!-- CONFIGURACIÓN -->
    <?php if (in_array("CONFIGURACION", $permisosMenu[$tipoUsuario])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed"
                href="#"
                data-toggle="collapse"
                data-target="#configMenu"
                aria-expanded="<?= expandedMenu([
                                    'config_empresa.php',
                                    'config_facturacion.php',
                                    'config_impuestos.php',
                                    'config_numeracion.php'
                                ]) ?>">

                <span>
                    <i class="fas fa-fw fa-cog mr-2"></i>Configuración
                </span>
            </a>

            <div id="configMenu"
                class="collapse <?= openMenu([
                                    'config_empresa.php',
                                    'config_facturacion.php',
                                    'config_impuestos.php',
                                    'config_numeracion.php'
                                ]) ?>"
                data-parent="#accordionSidebar">

                <div class="bg-white py-2 collapse-inner rounded">

                    <a class="collapse-item <?= activeItem('config_empresa.php') ?>"
                        href="config_empresa.php">
                        <i class="fas fa-building mr-2"></i>Empresa
                    </a>

                    <a class="collapse-item <?= activeItem('config_facturacion.php') ?>"
                        href="config_facturacion.php">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Facturación electrónica
                    </a>

                    <a class="collapse-item <?= activeItem('config_impuestos.php') ?>"
                        href="config_impuestos.php">
                        <i class="fas fa-percentage mr-2"></i>Impuestos
                    </a>

                    <a class="collapse-item <?= activeItem('config_numeracion.php') ?>"
                        href="config_numeracion.php">
                        <i class="fas fa-sort-numeric-up mr-2"></i>Numeración
                    </a>

                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>


    <!-- PERFIL -->
    <li class="nav-item <?= activeItem('perfil_empresa.php') ?>">
        <a class="nav-link" href="perfil_empresa.php">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Perfil</span>
        </a>
    </li>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>