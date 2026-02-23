<?php
require('conexion.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Sesión no válida']);
    exit;
}

try {

    $campos = ['id_producto','nombre','precio_costo','precio_venta','estado'];
    foreach ($campos as $c) {
        if (empty($_POST[$c])) {
            echo json_encode(['error' => true, 'mensaje' => "Falta el campo: $c"]);
            exit;
        }
    }

    $id_producto       = (int)$_POST['id_producto'];
    $nombre            = trim($_POST['nombre']);
    $descripcion       = $_POST['descripcion'] ?? '';
    $precio_costo      = (float)$_POST['precio_costo'];
    $precio_venta      = (float)$_POST['precio_venta'];
    $precio_gremio     = (float)($_POST['precio_gremio'] ?? 0);
    $precio_mayorista  = (float)($_POST['precio_mayorista'] ?? 0);
    $cantidad          = (int)$_POST['unidad'];
    $fecha_ingreso     = $_POST['fecha_ingreso'] ?? date('Y-m-d');
    $categoria         = $_POST['categoria'] ?? null;
    $proveedor         = $_POST['proveedor'] ?? null;
    $estado            = $_POST['estado'];

    // ==============================
    // UPDATE PRODUCTO
    // ==============================
    $sql = "
        UPDATE producto SET
            nombre_product = :nombre,
            descri_product = :descripcion,
            precio_costo_produc = :precio_costo,
            precio_publico_product = :precio_venta,
            precio_gremio_product = :precio_gremio,
            precio_mayorista_product = :precio_mayorista,
            cant_product = :cantidad,
            fecha_product = :fecha,
            rela_categoria = :categoria,
            rela_proveedor = :proveedor,
            rela_estado = :estado
        WHERE id_producto = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio_costo' => $precio_costo,
        ':precio_venta' => $precio_venta,
        ':precio_gremio' => $precio_gremio,
        ':precio_mayorista' => $precio_mayorista,
        ':cantidad' => $cantidad,
        ':fecha' => $fecha_ingreso,
        ':categoria' => $categoria,
        ':proveedor' => $proveedor,
        ':estado' => $estado,
        ':id' => $id_producto
    ]);

    // ==============================
    // IMAGEN (MISMA LÓGICA QUE INSERT)
    // ==============================
    // ==============================
// IMAGEN (INSERT o UPDATE SEGÚN EXISTA)
// ==============================
if (!empty($_FILES['foto_producto']['name'])) {

    $tmp  = $_FILES['foto_producto']['tmp_name'];
    $mime = mime_content_type($tmp);

    if (!in_array($mime, ['image/png', 'image/jpeg'])) {
        throw new Exception('Formato de imagen no permitido');
    }

    // 📁 RUTAS (IGUAL QUE INSERT)
    $dirFisico = __DIR__ . "/../../../uploads/productos/" . date('Y/m') . "/";
    $dirWeb    = "uploads/productos/" . date('Y/m') . "/";

    if (!is_dir($dirFisico)) {
        mkdir($dirFisico, 0777, true);
    }

    $nombreImg   = "prod_{$id_producto}_" . uniqid() . ".webp";
    $rutaFisica  = $dirFisico . $nombreImg;
    $rutaWeb     = $dirWeb . $nombreImg;

    // 🎨 Convertir a WEBP
    $img = ($mime === 'image/png')
        ? imagecreatefrompng($tmp)
        : imagecreatefromjpeg($tmp);

    imagewebp($img, $rutaFisica, 80);
    imagedestroy($img);

    // 🔎 VER SI YA TIENE IMÁGENES
    $stmtCheck = $pdo->prepare("
        SELECT COUNT(*) 
        FROM producto_imagen 
        WHERE rela_producto = ?
    ");
    $stmtCheck->execute([$id_producto]);
    $tieneImagen = $stmtCheck->fetchColumn() > 0;

    if ($tieneImagen) {
        // ❌ Quitar principal anterior
        $pdo->prepare("
            UPDATE producto_imagen
            SET es_principal = 0
            WHERE rela_producto = ?
        ")->execute([$id_producto]);
    }

    // ✅ INSERTAR IMAGEN (SIEMPRE)
    $pdo->prepare("
        INSERT INTO producto_imagen (rela_producto, ruta_imagen, es_principal)
        VALUES (?, ?, 1)
    ")->execute([$id_producto, $rutaWeb]);
}


    echo json_encode(['error'=>false,'mensaje'=>'Producto actualizado correctamente']);

} catch (Exception $e) {
    echo json_encode(['error'=>true,'mensaje'=>$e->getMessage()]);
}
