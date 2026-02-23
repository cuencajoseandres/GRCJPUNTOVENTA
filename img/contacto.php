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

<section class="py-5 text-center">
  <div class="container">
    <h2 class="mb-4 text-dark">Contáctanos</h2>
    <p class="lead mb-4">Estamos disponibles para asesorarte y ofrecerte cotizaciones personalizadas.</p>

    <form class="col-md-6 mx-auto text-left">
      <div class="form-group mb-3">
        <label>Nombre</label>
        <input type="text" class="form-control" required>
      </div>
      <div class="form-group mb-3">
        <label>Correo electrónico</label>
        <input type="email" class="form-control" required>
      </div>
      <div class="form-group mb-3">
        <label>Mensaje</label>
        <textarea class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Enviar Mensaje</button>
    </form>

    <div class="mt-5">
      <a href="https://wa.me/543704XXXXXX" target="_blank" class="btn btn-success">
        <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
      </a>
    </div>
  </div>
</section>

<?php include('include/footer.php'); ?>








</body>
</html>
