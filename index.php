<?php

require_once('validar_sesion.php');

if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo_usuario'] == 1) {
  header("Location: vista/empresa/inicio_empresa.php");
  exit;
}


require_once('validacion_login/conexion.php');

$stmt = $pdo->query("
  SELECT 
    p.id_producto,
    p.nombre_product,
    p.descri_product,
    p.precio_publico_product AS precio,
    pi.ruta_imagen
  FROM producto p
  LEFT JOIN producto_imagen pi
    ON p.id_producto = pi.rela_producto
    AND pi.es_principal = 1
  ORDER BY RAND()
  LIMIT 8
");

$destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PUNTO DE VENTAS| Inicio</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">

  <style>
    header {
      background: linear-gradient(90deg, #1b2735, #2a5298);
      color: white;
      padding: 15px 0;
    }

    header .navbar-brand {
      font-weight: bold;
      letter-spacing: 1px;
      color: #fff;
    }

    .hero {
      background: radial-gradient(circle, #1b2735, #090a0f);
      color: white;
      padding: 120px 0;
      text-align: center;
      animation: fadeIn 1.2s ease-in-out;
    }

    .hero h1 {
      font-size: 3rem;
      color: #4e73df;
    }

    .btn-primary {
      background: linear-gradient(45deg, #4e73df, #1cc88a);
      border: none;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: scale(1.05);
      background: linear-gradient(45deg, #1cc88a, #4e73df);
    }

    footer {
      background: #0f2027;
      color: #bbb;
      text-align: center;
      padding: 20px 0;
      margin-top: 40px;
    }

    .servicio-icono {
      font-size: 45px;
      color: #4e73df;
      margin-bottom: 15px;
    }

    .card:hover {
      transform: scale(1.03);
      transition: 0.3s ease;
    }



    .product-card{
  border-radius:14px;
  transition:.25s;
  border:none;
}

.product-card:hover{
  transform:translateY(-6px);
  box-shadow:0 12px 28px rgba(0,0,0,.15);
}

.product-img-wrapper{
  height:180px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#f8f9fc;
  padding:10px;
}

.product-img-wrapper img{
  max-height:100%;
  max-width:100%;
  object-fit:contain;
}

.product-title{
  font-size:14px;
  min-height:40px;
}

.price{
  font-weight:700;
  font-size:18px;
  color:#4e73df; /* azul sistema */
}




/* ============================= */
/* HERO TECNOLÓGICO */
/* ============================= */
.hero-tech{
  position:relative;
  min-height:520px;

  /* imagen tech */
  background:url('img/imgtecno.webp') center/cover no-repeat;

  display:flex;
  align-items:center;
}

/* capa oscura */
.hero-overlay{
  width:100%;
  padding:120px 0;
  background:linear-gradient(
      rgba(10,20,40,.85),
      rgba(10,20,40,.85)
  );
}

/* títulos */
.hero-tech h1{
  color:#4e73df;
  text-shadow:0 3px 10px rgba(0,0,0,.6);
}

.hero-tech p{
  max-width:800px;
  margin:auto;
  opacity:.95;
}

/* botones */
.hero-tech .btn-primary{
  background:#4e73df;
  border:none;
}

.hero-tech .btn-primary:hover{
  background:#2a5298;
}
  </style>
</head>

<body>

  <?php include('include/header.php'); ?>

  <!-- SECCIÓN HERO PRINCIPAL -->
<section class="hero-tech">
  <div class="hero-overlay">
    <div class="container text-center text-white">

      <h1 class="display-4 font-weight-bold mb-3">
        Tecnología, Servicio y Soluciones en un solo lugar
      </h1>

      <p class="lead mb-4">
        En <strong>GR/CJ Insumos</strong> ofrecemos venta mayorista y minorista de insumos informáticos,
        servicio técnico especializado en computadoras y celulares iPhone y otras marcas),
        y desarrollo de sistemas a medida para negocios.
      </p>

      <a href="tienda.php" class="btn btn-primary btn-lg mx-2">
        <i class="fas fa-shopping-cart"></i> Ver Tienda
      </a>

      <a href="in.php" class="btn btn-outline-light btn-lg mx-2">
        <i class="fas fa-laptop-code"></i> Ver Sistemas
      </a>
<a href="in.php" class="btn btn-outline-light btn-lg mx-2">
        <i class="fas fa-laptop-code"></i> Ver Sistemas
      </a>
    </div>
  </div>
</section>

  <!-- SECCIÓN PRODUCTOS DESTACADOS -->
 <section class="py-5 bg-light">
  <div class="container">

    <h2 class="text-center mb-5 font-weight-bold text-primary">
      ⭐ Productos Destacados
    </h2>

    <div class="row">

      <?php foreach($destacados as $p): ?>
        <div class="col-lg-3 col-md-4 col-6 mb-4">

          <div class="card product-card h-100 shadow-sm text-center">

            <div class="product-img-wrapper">
              <img 
                src="<?= $p['ruta_imagen'] ?: 'uploads/productos/default.webp' ?>"
                alt="<?= htmlspecialchars($p['nombre_product']) ?>"
              >
            </div>

            <div class="card-body d-flex flex-column">

              <h6 class="product-title">
                <?= htmlspecialchars($p['nombre_product']) ?>
              </h6>

              <div class="price mb-3">
                $<?= number_format($p['precio'],2) ?>
              </div>

              <a href="tienda.php?buscar=<?= urlencode($p['nombre_product']) ?>" 
                 class="btn btn-primary btn-sm mt-auto">
                Ver producto
              </a>

            </div>

          </div>

        </div>
      <?php endforeach; ?>

    </div>

    <!-- Botón catálogo -->
    <div class="text-center mt-4">
      <a href="tienda.php" class="btn btn-outline-primary btn-lg">
        Ver todos los productos
      </a>
    </div>

  </div>
</section>

  <!-- SECCIÓN SISTEMAS A MEDIDA -->
  <section class="py-5">
    <div class="container text-center">
      <h2 class="text-dark mb-5">Desarrollo de Sistemas a Medida</h2>
      <div class="row">
        <div class="col-md-4">
          <i class="fas fa-store servicio-icono"></i>
          <h5>Sistema de Facturación y Stock</h5>
          <p>Control total de ventas, stock, proveedores y reportes automáticos.</p>
        </div>
        <div class="col-md-4">
          <i class="fas fa-dumbbell servicio-icono"></i>
          <h5>System Gym</h5>
          <p>Gestión de socios, pagos, vencimientos y rutinas.</p>
        </div>
        <div class="col-md-4">
          <i class="fas fa-calendar-check servicio-icono"></i>
          <h5>Turnos y Reservas</h5>
          <p>Ideal para peluquerías, talleres o centros de servicio técnico.</p>
        </div>
      </div>
      <a href="sistemas.php" class="btn btn-primary mt-4">Ver más sistemas</a>
    </div>
  </section>

  <!-- SECCIÓN SERVICIO TÉCNICO -->
  <section class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="text-dark mb-5">Servicio Técnico Profesional</h2>
      <div class="row">
        <div class="col-md-6">
          <i class="fas fa-laptop-medical servicio-icono"></i>
          <h5>Reparación de Computadoras</h5>
          <p>Diagnóstico, mantenimiento, cambio de componentes y formateo con instalación de sistemas.</p>
        </div>
        <div class="col-md-6">
          <i class="fas fa-mobile-alt servicio-icono"></i>
          <h5>Servicio Técnico de Celulares</h5>
          <p>Reparación de placa, cambio de pantalla, batería y software de celulares.</p>
        </div>
      </div>
      <a href="servicio-tecnico.php" class="btn btn-primary mt-4">Solicitar Servicio</a>
    </div>
  </section>

  <?php include('include/footer.php'); ?>

</body>

</html>