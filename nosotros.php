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

<section class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="text-dark mb-4">Sobre Nosotros</h2>
    <p class="lead">
      En <strong>CJ INFORMATICA</strong> nos especializamos en la venta mayorista de repuestos, insumos informáticos y sistemas personalizados para empresas y técnicos.
    </p>
    <p>
      Con más de 10 años de experiencia en el rubro, brindamos soluciones confiables, asesoramiento técnico y soporte integral.
    </p>
    <img src="img/logo.svg" alt="CJ INFORMATICA" style="width:150px;margin-top:30px;">
  </div>
</section>

<?php include('include/footer.php'); ?>








</body>
</html>
