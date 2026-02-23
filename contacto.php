<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CJ INFORMATICA | Inicio</title>

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
  </style>
</head>

<body>

<?php include('include/header.php'); ?>

<section class="hero text-center text-light py-5" style="background:radial-gradient(circle, #1b2735, #090a0f);">
  <div class="container">
    <h1 class="display-4 text-primary">Soluciones Tecnológicas para tu Negocio</h1>
    <p class="lead">Venta mayorista de repuestos, insumos informáticos y sistemas personalizados.</p>
    <a href="tienda.php" class="btn btn-primary mt-3">Ver Tienda</a>
  </div>
</section>

<section class="py-5 text-center bg-light">
  <div class="container">
    <h2 class="mb-4 text-dark">Nuestros Productos Destacados</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow">
          <img src="img/producto1.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Repuestos Notebook</h5>
            <p>Cargadores, pantallas y baterías originales.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow">
          <img src="img/producto2.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Componentes de PC</h5>
            <p>Motherboards, procesadores, memorias RAM y más.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow">
          <img src="img/producto3.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Insumos Tecnológicos</h5>
            <p>Cables, adaptadores, periféricos y accesorios.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('include/footer.php'); ?>








</body>
</html>
