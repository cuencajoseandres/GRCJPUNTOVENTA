<?php
session_start();
require_once('validacion_login/conexion.php');

// ==============================
// VALIDAR INPUT
// ==============================
if (
    !isset($_POST['product_id'], $_POST['quantity']) ||
    $_POST['quantity'] <= 0
) {
    die('Datos inválidos');
}

$idProducto = (int) $_POST['product_id'];
$cantidad   = (int) $_POST['quantity'];

// ==============================
// PRECIO POR DEFECTO
// ==============================
$campoPrecio = 'precio_publico_product';

// ==============================
// VALIDAR TIPO CLIENTE
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

    if ($stmtTipo->fetchColumn() === 'tecnico') {
        $campoPrecio = 'precio_gremio_product';
    }
}

// ==============================
// OBTENER PRODUCTO + PRECIO REAL
// ==============================
$stmt = $pdo->prepare("
    SELECT 
        p.id_producto,
        p.nombre_product,
        p.$campoPrecio AS precio,
        pi.ruta_imagen
    FROM producto p
    LEFT JOIN producto_imagen pi
        ON p.id_producto = pi.rela_producto
        AND pi.es_principal = 1
    WHERE p.id_producto = :id
    AND p.cant_product > 0
    LIMIT 1
");

$stmt->execute([
    ':id' => $idProducto
]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die('Producto no disponible');
}

// ==============================
// INICIALIZAR CARRITO
// ==============================
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ==============================
// AGREGAR / SUMAR PRODUCTO
// ==============================
if (isset($_SESSION['cart'][$idProducto])) {

    $_SESSION['cart'][$idProducto]['qty'] += $cantidad;

} else {

    $_SESSION['cart'][$idProducto] = [
        'id'     => $producto['id_producto'],
        'nombre' => $producto['nombre_product'],
        'precio' => (float) $producto['precio'],
        'qty'    => $cantidad,
        'img'    => $producto['ruta_imagen'] ?: 'uploads/productos/default.webp'
    ];
}

// ==============================
// REDIRECCIÓN
// ==============================
header('Location: cart.php');
exit;
