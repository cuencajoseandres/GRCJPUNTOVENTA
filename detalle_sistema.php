<?php
// --- Control de slug y sistemas ---
$slug = $_GET['slug'] ?? '';

$sistemas_map = [
    'control-stock'   => ['titulo' => 'Sistema de Control de Stock y Facturación', 'desc' => 'Sistema completo para gestionar inventario, ventas y clientes. Ideal para negocios mayoristas y minoristas.'],
    'system-gym'      => ['titulo' => 'System Gym', 'desc' => 'Control de socios, pagos y asistencias. Ideal para gimnasios y centros deportivos.'],
    'turnos'          => ['titulo' => 'Sistema de Turno', 'desc' => 'Gestión de turnos online y en local. Notificaciones automáticas.'],
    'servicio-tecnico'=> ['titulo' => 'Sistema de Servicio Técnico', 'desc' => 'Registra equipos, estados, repuestos y órdenes de trabajo.'],
    'peluqueria'      => ['titulo' => 'Sistema Turno Peluquería', 'desc' => 'Gestión de citas, clientes y servicios con control de agenda.']
];

$s = $sistemas_map[$slug] ?? null;

if (!$s) {
    include('include/header.php');
    echo '<div class="container my-5">
            <div class="alert alert-danger">⚠️ Sistema no encontrado.</div>
          </div>';
    include('include/footer.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CJ INFORMATICA | <?= htmlspecialchars($s['titulo']) ?></title>

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

    footer {
      background: #0f2027;
      color: #bbb;
      text-align: center;
      padding: 20px 0;
      margin-top: 40px;
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
  </style>
</head>

<body>

<?php include('include/header.php'); ?>

<div class="container my-5">
  <h2 class="text-primary mb-3"><?= htmlspecialchars($s['titulo']) ?></h2>
  <p><?= htmlspecialchars($s['desc']) ?></p>

  <h4>Características principales</h4>
  <ul>
    <li>Gestión de usuarios y roles</li>
    <li>Reportes exportables</li>
    <li>Integración con facturación</li>
    <li>Soporte y personalización</li>
  </ul>

  <!-- 🔹 Botones de acción -->
  <div class="mt-4 d-flex gap-3">
    <a href="solicitar-cuenta.php?sistema=<?= urlencode($s['titulo']) ?>" class="btn btn-primary">
      <i class="fas fa-paper-plane"></i> Solicitar demo / presupuesto
    </a>

    <!-- 🔹 Si querés también un botón de carrito -->
    <form method="post" action="add_to_cart.php" class="d-inline">
      <input type="hidden" name="product_id" value="<?= htmlspecialchars($s['titulo']) ?>">
      <input type="hidden" name="quantity" value="1">
      <button type="submit" class="btn btn-success">
        <i class="fas fa-cart-plus"></i> Agregar al carrito
      </button>
    </form>
  </div>
</div>

<?php include('include/footer.php'); ?>

</body>
</html>
