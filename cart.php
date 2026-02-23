

<?php
session_start();
require_once('validacion_login/conexion.php');

// ==============================
// PRECIO POR DEFECTO
// ==============================
$campoPrecio = 'precio_publico_product';

// ==============================
// DEFINIR PRECIO SEGÚN CLIENTE
// ==============================
if (
    isset($_SESSION['usuario']['id_usuario']) &&
    ($_SESSION['usuario']['tipo_usuario'] ?? null) == 4
) {
    $stmtTipo = $pdo->prepare("
        SELECT LOWER(tc.descri_tip_cliente)
        FROM clientes c
        INNER JOIN tipo_cliente tc
            ON c.rela_tipo_cliente = tc.id_tip_cliente
        WHERE c.rela_usuario = :id_usuario
        LIMIT 1
    ");
    $stmtTipo->execute([
        ':id_usuario' => $_SESSION['usuario']['id_usuario']
    ]);

    if ($stmtTipo->fetchColumn() === 'tecnico') {
        $campoPrecio = 'precio_gremio_product';
    }
}

// ==============================
// REVALIDAR PRECIOS DEL CARRITO
// ==============================
if (!empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $id => &$item) {

        $stmt = $pdo->prepare("
            SELECT 
                p.nombre_product,
                p.$campoPrecio AS precio,
                pi.ruta_imagen
            FROM producto p
            LEFT JOIN producto_imagen pi
                ON p.id_producto = pi.rela_producto
                AND pi.es_principal = 1
            WHERE p.id_producto = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el producto ya no existe → eliminar del carrito
        if (!$producto) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        // 🔒 ACTUALIZAR DATOS REALES
        $item['precio'] = (float) $producto['precio'];
        $item['nombre'] = $producto['nombre_product'];
        $item['img']    = $producto['ruta_imagen'] ?: 'uploads/productos/default.webp';
    }
    unset($item); // limpiar referencia
}?>
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
  <h2 class="mb-4">Tu Carrito</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Tu carrito está vacío. <a href="tienda.php">Ver productos</a></div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; foreach($_SESSION['cart'] as $item): 
             $subtotal = $item['precio'] * $item['qty'];
             $total += $subtotal;
          ?>
          <tr>
            <td>
              <img src="<?= $item['img'] ?>" style="width:60px; height:auto;" class="mr-2">
              <?= htmlspecialchars($item['nombre']) ?>
            </td>
            <td>$ <?= number_format($item['precio'],0,',','.') ?></td>
            <td>
              <form style="display:inline;" method="post" action="update_cart.php">
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                <input type="number" name="quantity" value="<?= $item['qty'] ?>" min="1" style="width:70px" class="form-control d-inline-block">
                <button class="btn btn-sm btn-secondary mt-1">Actualizar</button>
              </form>
            </td>
            <td>$ <?= number_format($subtotal,0,',','.') ?></td>
            <td>
              <form method="post" action="remove_from_cart.php">
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                <button class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="text-right">
      <h4>Total: $ <?= number_format($total,0,',','.') ?></h4>
      <a href="checkout.php" class="btn btn-primary btn-lg">Finalizar compra</a>
    </div>
  <?php endif; ?>
</div>

<?php include('include/footer.php'); ?>








</body>
</html>
