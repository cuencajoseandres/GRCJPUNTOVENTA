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

    .servicio-icono {
      font-size: 45px;
      color: #4e73df;
      margin-bottom: 15px;
    }

    .card:hover {
      transform: scale(1.03);
      transition: 0.3s ease;
    }
  </style>
</head>

<body>
<?php include('include/header.php'); ?>

<div class="container my-5">
  <h2 class="text-center text-primary mb-4">Servicio Técnico de Celulares y Computadoras</h2>
  <p class="text-center mb-5">
    Reparamos notebooks, PC, monitores, celulares y tablets. Diagnóstico sin cargo.
  </p>

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <div class="card-body">
          <h4 class="mb-3 text-center">Formulario de Ingreso de Equipo</h4>
          <form action="procesar_servicio.php" method="post">
            <div class="mb-3">
              <label>Nombre y Apellido</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Teléfono / WhatsApp</label>
              <input type="text" name="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Tipo de Equipo</label>
              <select name="tipo_equipo" class="form-control" required>
                <option value="">Seleccionar...</option>
                <option>Notebook</option>
                <option>PC de escritorio</option>
                <option>Celular</option>
                <option>Tablet</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Marca / Modelo</label>
              <input type="text" name="modelo" class="form-control">
            </div>
            <div class="mb-3">
              <label>Falla / Descripción del problema</label>
              <textarea name="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="text-center">
              <button class="btn btn-primary">Enviar Solicitud</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>


</body>
</html>
