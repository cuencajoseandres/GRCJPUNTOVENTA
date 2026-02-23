<?php
session_start();
require_once('validacion_login/conexion.php');

// ==============================
// PRECIO POR DEFECTO
// ==============================
$campoPrecio = 'precio_publico_product';
$tituloCatalogo = 'Catálogo para Público';

// ==============================
// TIPO CLIENTE (SOLO CLIENTES)
// ==============================
if (
  isset($_SESSION['usuario']['id_usuario']) &&
  ($_SESSION['usuario']['tipo_usuario'] ?? null) == 4
) {

  $idUsuario = $_SESSION['usuario']['id_usuario'];

  $stmtTipo = $pdo->prepare("
        SELECT LOWER(tc.descri_tip_cliente)
        FROM clientes c
        INNER JOIN tipo_cliente tc 
            ON c.rela_tipo_cliente = tc.id_tip_cliente
        WHERE c.rela_usuario = :id_usuario
        LIMIT 1
    ");

  $stmtTipo->execute([
    ':id_usuario' => $idUsuario
  ]);

  $tipoCliente = $stmtTipo->fetchColumn();

  // 🎯 DEFINIR PRECIO
  if ($tipoCliente === 'tecnico') {
    $campoPrecio = 'precio_gremio_product';
  } else {
    $campoPrecio = 'precio_publico_product';
  }

  // ==============================
  // TEXTO DEL CATÁLOGO
  // ==============================


  if (!empty($tipoCliente) && $tipoCliente === 'tecnico') {
    $tituloCatalogo = 'Catálogo para Gremio';
  }
}


// ==============================
// CONSULTA PRODUCTOS
// ==============================
$stmt = $pdo->prepare("
    SELECT 
        p.id_producto,
        p.nombre_product,
        p.descri_product,
        p.$campoPrecio AS precio,
        p.cant_product,
        pi.ruta_imagen
    FROM producto p
    LEFT JOIN producto_imagen pi
        ON p.id_producto = pi.rela_producto
        AND pi.es_principal = 1
    ORDER BY p.nombre_product ASC
");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categorias = $pdo->query("
  SELECT id_categoria, descri_cat
  FROM categoria
  ORDER BY descri_cat
")->fetchAll(PDO::FETCH_ASSOC);




?>



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

    /* ===== BUSCADOR ===== */
    #buscador {
      border-radius: 30px 0 0 30px;
      border: 1px solid #4e73df;
      box-shadow: 0 0 6px rgba(78, 115, 223, 0.3);
    }

    .input-group-append .btn {
      border-radius: 0 30px 30px 0;
    }

    #sugerencias {
      border-radius: 10px;
      overflow: hidden;
      max-height: 220px;
      overflow-y: auto;
    }

    #sugerencias .list-group-item {
      transition: background 0.2s;
    }

    #sugerencias .list-group-item:hover {
      background: #4e73df;
      color: #fff;
    }

    /* ===== TOAST CARRITO ===== */
    .toast-carrito {
      position: fixed;
      top: 20px;
      right: 20px;
      background: #4e73df;
      color: white;
      padding: 12px 20px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
      opacity: 0;
      transform: translateY(-20px);
      transition: all 0.4s ease;
      z-index: 9999;
    }

    .toast-carrito.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ===== TARJETA PRODUCTO ===== */
    .product-card {
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, .15);
    }

    /* ===== IMAGEN ===== */
    .product-img-wrapper {
      height: 230px;
      background: #f8f9fc;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px;
    }

    .product-img-wrapper img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    /* ===== BADGE STOCK ===== */
    .badge-stock {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: .75rem;
      padding: 6px 12px;
      border-radius: 20px;
      z-index: 5;
      box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
    }

    /* ===== ULTIMAS UNIDADES ===== */
    .stock-warning {
      font-size: .75rem;
      color: #f6c23e;
      font-weight: 600;
    }

    /* ===== PRECIO ===== */
    .price {
      font-size: 1.4rem;
      font-weight: 700;
      color: #4e73df;
    }

    .product-card h5 {
      font-weight: 600;
      font-size: 1.05rem;
    }

    /* ===== ZOOM SUAVE IMAGEN PRODUCTO ===== */
    .product-img-wrapper {
      overflow: hidden;
      /* clave para que no se salga */
    }

    .product-img-wrapper img {
      transition: transform 0.35s ease;
    }

    .product-card:hover .product-img-wrapper img {
      transform: scale(1.08);
      filter: brightness(1.05);
    }
  </style>
</head>

<body>

  <?php include('include/header.php'); ?>

  <section class="py-5 bg-light">




    <div class="container">
      <h2 class="text-center text-dark mb-4">
        <?= htmlspecialchars($tituloCatalogo) ?>
      </h2>


      <!-- 🔍 BUSCADOR CON AUTOCOMPLETADO -->
      <div class="text-center mb-5 position-relative">
        <div class="input-group" style="max-width:500px; margin:auto;">
          <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre, precio o descripción...">
          <div class="input-group-append">
            <span class="btn btn-primary"><i class="fas fa-search"></i></span>
          </div>
        </div>

        <!-- 📋 Lista de sugerencias -->
        <ul id="sugerencias" class="list-group position-absolute w-100 shadow"
          style="max-width:500px; margin:auto; left:0; right:0; z-index:1000; display:none;"></ul>
      </div>


      <div class="row" id="productosContainer">
        <?php foreach ($productos as $p):
          $busqueda = strtolower(
            $p['nombre_product'] . ' ' .
              $p['precio'] . ' ' .
              $p['descri_product']
          );
        ?>
          <div class="col-md-4 col-sm-6 mb-4 producto-item"
            data-busqueda="<?= htmlspecialchars($busqueda) ?>">

            <div class="card product-card h-100 shadow-sm">

              <!-- BADGE STOCK -->
              <?php if ($p['cant_product'] > 0): ?>
                <span class="badge badge-success badge-stock">
                  Stock <?= (int)$p['cant_product'] ?>
                </span>
              <?php else: ?>
                <span class="badge badge-danger badge-stock">
                  Sin stock
                </span>
              <?php endif; ?>

              <!-- IMAGEN -->
              <div class="product-img-wrapper">
                <img
                  src="<?= $p['ruta_imagen'] ?: 'uploads/productos/default.webp' ?>"
                  alt="<?= htmlspecialchars($p['nombre_product']) ?>">
              </div>

              <div class="card-body text-center d-flex flex-column">

                <h5 class="mb-1"><?= htmlspecialchars($p['nombre_product']) ?></h5>

                <!-- PRECIO -->
                <div class="price mb-1">
                  $<?= number_format($p['precio'], 2) ?>
                </div>

                <!-- ALERTA ULTIMAS UNIDADES -->
                <?php if ($p['cant_product'] > 0 && $p['cant_product'] <= 5): ?>
                  <div class="stock-warning mb-2">
                    ⚠ Últimas unidades
                  </div>
                <?php endif; ?>

                <p class="text-muted small flex-grow-1">
                  <?= htmlspecialchars($p['descri_product']) ?>
                </p>

                <!-- AGREGAR AL CARRITO -->
                <form method="post" action="add_to_cart.php">
                  <input type="hidden" name="product_id" value="<?= $p['id_producto'] ?>">

                  <div class="d-flex justify-content-center mb-2">
                    <input
                      type="number"
                      name="quantity"
                      value="1"
                      min="1"
                      max="<?= (int)$p['cant_product'] ?>"
                      class="form-control text-center"
                      style="width:80px"
                      <?= ($p['cant_product'] <= 0) ? 'disabled' : '' ?>>
                  </div>

                  <button
                    class="btn btn-block <?= ($p['cant_product'] > 0) ? 'btn-success' : 'btn-secondary' ?>"
                    <?= ($p['cant_product'] <= 0) ? 'disabled' : '' ?>>

                    <?php if ($p['cant_product'] > 0): ?>
                      <i class="fas fa-cart-plus"></i> Agregar al carrito
                    <?php else: ?>
                      <i class="fas fa-ban"></i> No disponible
                    <?php endif; ?>

                  </button>
                </form>

              </div>
            </div>
          </div>


        <?php endforeach; ?>
      </div>
    </div>


  </section>

  <script>
    const buscador = document.getElementById('buscador');
    const productos = document.querySelectorAll('.producto-item');
    const sugerencias = document.getElementById('sugerencias');

    buscador.addEventListener('keyup', () => {
      const texto = buscador.value.toLowerCase().trim();
      let encontrados = 0;
      sugerencias.innerHTML = '';

      if (texto === '') {
        sugerencias.style.display = 'none';
        productos.forEach(p => p.style.display = '');
        return;
      }

      productos.forEach(p => {
        const info = p.dataset.busqueda;
        const nombre = p.querySelector('h5').textContent;
        if (info.includes(texto)) {
          p.style.display = '';
          encontrados++;

          // Agregar sugerencias (máximo 6)
          if (sugerencias.children.length < 6) {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = nombre;
            li.style.cursor = 'pointer';
            li.onclick = () => {
              buscador.value = nombre;
              sugerencias.style.display = 'none';
              filtrarProductos(nombre.toLowerCase());
            };
            sugerencias.appendChild(li);
          }
        } else {
          p.style.display = 'none';
        }
      });

      sugerencias.style.display = (sugerencias.children.length > 0) ? 'block' : 'none';
    });

    // 🔎 Función para filtrar productos
    function filtrarProductos(texto) {
      productos.forEach(p => {
        const info = p.dataset.busqueda;
        p.style.display = info.includes(texto) ? '' : 'none';
      });
    }

    // Ocultar sugerencias al hacer clic fuera
    document.addEventListener('click', (e) => {
      if (!sugerencias.contains(e.target) && e.target !== buscador) {
        sugerencias.style.display = 'none';
      }
    });
  </script>







  <?php include('include/footer.php'); ?>

</body>

</html>