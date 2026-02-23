<?php
require('conexion.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Error: usuario no logueado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];

try {

    // ==============================
    // Validar datos obligatorios
    // ==============================
    if (empty($_POST['nombre']) || empty($_POST['precio_costo']) || empty($_POST['precio_venta'])) {
        echo json_encode(['error' => true, 'mensaje' => 'Faltan datos obligatorios']);
        exit;
    }

    // ==============================
    // Capturar variables
    // ==============================
    $nombre           = $_POST['nombre'];
    $descripcion      = $_POST['descripcion'] ?? '';
    $marca            = $_POST['marca'] ?? '';
    $precio_costo     = $_POST['precio_costo'];
    $precio_venta     = $_POST['precio_venta'];
    $precio_gremio    = $_POST['precio_gremio'] ?? 0;
    $precio_mayorista = $_POST['precio_mayorista'] ?? 0;
    $cantidad         = $_POST['unidad'];
    $fecha_ingreso    = $_POST['fecha_ingreso'];
    $categoria        = $_POST['categoria'];
    $proveedor        = $_POST['proveedor'];
    $estado           = $_POST['estado'];

    // ==============================
    // Insertar producto
    // ==============================
    $codigo_auto = strtoupper(substr($nombre, 0, 3)) . rand(100, 999);

    $sql = "INSERT INTO producto 
        (nombre_product, descri_product, cod_product, precio_costo_produc, precio_publico_product, 
         precio_gremio_product, precio_mayorista_product, cant_product, fecha_product, 
         rela_categoria, rela_proveedor, rela_user, rela_estado)
        VALUES 
        (:nombre, :descripcion, :codigo, :precio_costo, :precio_venta, :precio_gremio, :precio_mayorista,
         :cantidad, :fecha, :categoria, :proveedor, :usuario, :estado)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre'           => $nombre,
        ':descripcion'      => $descripcion,
        ':codigo'           => $codigo_auto,
        ':precio_costo'     => $precio_costo,
        ':precio_venta'     => $precio_venta,
        ':precio_gremio'    => $precio_gremio,
        ':precio_mayorista' => $precio_mayorista,
        ':cantidad'         => $cantidad,
        ':fecha'            => $fecha_ingreso,
        ':categoria'        => $categoria,
        ':proveedor'        => $proveedor,
        ':usuario'          => $id_usuario,
        ':estado'           => $estado
    ]);

    // ==============================
    // ID del producto creado
    // ==============================
    $id_producto = $pdo->lastInsertId();

    // ==============================
    // Subida y guardado de imagen
    // ==============================
    if (!empty($_FILES['foto_producto']['name'])) {

        $tmp = $_FILES['foto_producto']['tmp_name'];

        // MIME seguro
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        // 🔴 RUTA FÍSICA (para guardar archivo)
        $directorioFisico = __DIR__ . "/../../../uploads/productos/" . date('Y/m') . "/";

        // 🟢 RUTA WEB (para guardar en BD)
        $directorioWeb = "uploads/productos/" . date('Y/m') . "/";

        if (!is_dir($directorioFisico)) {
            mkdir($directorioFisico, 0777, true);
        }

        $nombreImg = 'prod_' . $id_producto . '_' . uniqid() . '.webp';

        $rutaFisicaFinal = $directorioFisico . $nombreImg;
        $rutaWebFinal    = $directorioWeb . $nombreImg;

        if ($mime === 'image/png') {
            $img = imagecreatefrompng($tmp);
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
        } elseif ($mime === 'image/jpeg') {
            $img = imagecreatefromjpeg($tmp);
        } else {
            throw new Exception('Formato de imagen no permitido');
        }

        imagewebp($img, $rutaFisicaFinal, 80);
        imagedestroy($img);

        // ==============================
        // Guardar SOLO RUTA WEB en BD
        // ==============================
        $stmtImg = $pdo->prepare("
            INSERT INTO producto_imagen (rela_producto, ruta_imagen, es_principal)
            VALUES (:producto, :ruta, 1)
        ");

        $stmtImg->execute([
            ':producto' => $id_producto,
            ':ruta'     => $rutaWebFinal
        ]);
    }

    echo json_encode([
        'error' => false,
        'mensaje' => 'Producto agregado correctamente'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error SQL: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}
?>
