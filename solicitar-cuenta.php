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

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-lg border-0">
        <div class="card-body p-5 text-center">
          <h3 class="text-primary mb-4">
            <i class="fas fa-user-plus"></i> Solicitar Cuenta
          </h3>
          <p class="text-muted">Completá tus datos y te contactaremos para crear tu cuenta.</p>

          <form action="procesar_solicitud.php" method="POST">
            <div class="form-group text-left">
              <label for="nombre">Nombre completo</label>
              <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Tu nombre completo" required>
            </div>

            <div class="form-group text-left">
              <label for="email">Correo electrónico</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group text-left">
              <label for="telefono">Teléfono</label>
              <input type="text" name="telefono" id="telefono" class="form-control" placeholder="+54 3704 123456">
            </div>

            <div class="form-group text-left">
              <label for="mensaje">Mensaje (opcional)</label>
              <textarea name="mensaje" id="mensaje" rows="3" class="form-control" placeholder="Contanos brevemente qué necesitás"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">
              <i class="fas fa-paper-plane"></i> Enviar Solicitud
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include('include/footer.php'); ?>


<style>
body {
  background: radial-gradient(circle, #0f2027, #203a43, #2c5364);
  color: white;
}

.card {
  border-radius: 1rem;
  background-color: rgba(255, 255, 255, 0.95);
}

.text-primary {
  color: #2a5298 !important;
}

.btn-primary {
  background: linear-gradient(45deg, #4e73df, #1cc88a);
  border: none;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #1cc88a, #4e73df);
  transform: scale(1.03);
}
</style>





</body>
</html>
