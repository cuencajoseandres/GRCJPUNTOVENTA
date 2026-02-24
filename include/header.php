<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$usuario = $_SESSION['usuario']['usuario_user'] ?? null;
$nombre_tipo_user = $_SESSION['usuario']['nombre_tipo_user'] ?? null;
?>


<header class="header-cj sticky-top">
  

    <nav class="navbar navbar-expand-lg navbar-dark p-0  container">

      <!-- LOGO -->
      <a class="navbar-brand mr-4" href="index.php">
        <i class="fas fa-laptop-code"></i> GR/CJ INSUMOS
      </a>

      <!-- BOTÓN MÓVIL -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">

        <!-- ===== BUSCADOR AZUL =====
        <form action="tienda.php" method="GET" class="search-box mx-lg-auto my-3 my-lg-0">
          <div class="input-group">
            <input
              type="text"
              name="buscar"
              class="form-control search-input"
              placeholder="Buscar productos, marcas y más..."
            >
            <div class="input-group-append">
              <button class="btn search-btn" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>-->

        <!-- MENÚ -->
        <ul class="navbar-nav align-items-lg-center ml-lg-auto">

          <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="tienda.php">Tienda</a>
          </li>

            <li class="nav-item">
            <a class="nav-link" href="tienda.php">Nosotros</a>
          </li>

          <li class="nav-item ml-lg-3 mt-3 mt-lg-0">

          <?php if (!isset($usuario)): ?>

           

            <a href="login.php" class="btn btn-primary btn-sm mx-1">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>

          <?php else: ?>

            <div class="dropdown d-inline-block">
              <button class="btn btn-outline-light btn-sm dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-user"></i> <?= htmlspecialchars($usuario) ?>
              </button>

              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="perfil.php">Mi Perfil</a>
                <a class="dropdown-item" href="mis-compras.php">Mis Compras</a>
                <a class="dropdown-item" href="configuracion.php">Configuración</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="salir.php">Cerrar sesión</a>
              </div>
            </div>

          <?php endif; ?>

          </li>
        </ul>

      </div>
    </nav>
  
</header>


<style>
  header {
    background: linear-gradient(90deg, #1b2735, #2a5298);
    color: white;
    padding: 10px 0;
  }

  .navbar-brand {
    font-weight: bold;
    color: #fff;
  }

  .nav-link {
    color: #ddd !important;
    transition: color 0.3s ease;
  }

  .nav-link:hover {
    color: #fff !important;
  }

  .btn-outline-light {
    border: 1px solid #fff;
    color: #fff;
    transition: 0.3s;
  }

  .btn-outline-light:hover {
    background-color: #fff;
    color: #2a5298;
  }

  .dropdown-menu a {
    font-size: 14px;
  }
</style>
<style>
/* HEADER */
.header-cj{
  background: linear-gradient(90deg,#1b2735,#2a5298);
  padding: 12px 0;
}

/* BUSCADOR */
.search-box{
  width:100%;
  max-width:520px;
}

.search-input{
  border-radius:30px 0 0 30px;
  border:none;
  padding-left:18px;
}

.search-btn{
  background:#0d6efd;
  color:white;
  border:none;
  border-radius:0 30px 30px 0;
}

.search-btn:hover{
  background:#0b5ed7;
}

/* animaciones suaves */
.navbar .btn{
  transition:.2s;
}

.navbar .btn:hover{
  transform:translateY(-1px);
}

/* responsive */
@media (max-width: 991px){
  .search-box{
    max-width:100%;
  }
}
</style>