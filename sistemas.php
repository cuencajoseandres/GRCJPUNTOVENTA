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
    <h2 class="mb-4 text-dark">Sistemas a Medida</h2>
    <p class="lead mb-5">Desarrollamos sistemas adaptados a tu negocio. Pedí una demo o presupuesto.</p>

    <div class="row">
      <?php
      $sistemas = [
        ['slug'=>'control-stock','titulo'=>'Sistema de Control de Stock y Facturación','desc'=>'Control integral de inventario, facturación y reportes.'],
        ['slug'=>'system-gym','titulo'=>'System Gym','desc'=>'Gestión de socios, cuotas, horarios y cobros.'],
        ['slug'=>'turnos','titulo'=>'Sistema de Turno','desc'=>'Reservas y gestión de turnos para distintas actividades.'],
        ['slug'=>'servicio-tecnico','titulo'=>'Sistema de Servicio Técnico','desc'=>'Tickets, historial de reparaciones y control de garantía.'],
        ['slug'=>'peluqueria','titulo'=>'Sistema Turno Peluquería','desc'=>'Agenda, clientes y control de servicios por profesional.'],
      ];
      foreach($sistemas as $s):
      ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5><?= $s['titulo'] ?></h5>
              <p><?= $s['desc'] ?></p>
              <a href="detalle_sistema.php?slug=<?= $s['slug'] ?>" class="btn btn-sm btn-outline-primary">Ver detalle</a>

              <!-- Botón solicitar demo: va a solicitar-cuenta con prefill -->
              <a href="solicitar-cuenta.php?sistema=<?= urlencode($s['titulo']) ?>" class="btn btn-sm btn-primary ml-2">
                <i class="fas fa-play-circle"></i> Solicitar demo
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include('include/footer.php'); ?>








</body>
</html>
