<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../conexion.php");
header('Content-Type: application/json');

$id = $_POST['id_producto'] ?? null;

if (!$id) {
    echo json_encode(['error' => true, 'mensaje' => 'ID no recibido']);
    exit;
}

if (empty($_FILES['imagen']['name'])) {
    echo json_encode(['error' => true, 'mensaje' => 'No se recibió imagen']);
    exit;
}

try {
    // Verificar que NO tenga imagen
    $check = $pdo->prepare("
        SELECT COUNT(*) 
        FROM producto_imagen 
        WHERE rela_producto = ?
    ");
    $check->execute([$id]);

    if ($check->fetchColumn() > 0) {
        throw new Exception('El producto ya tiene imagen');
    }

    $tmp = $_FILES['imagen']['tmp_name'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $tmp);
    finfo_close($finfo);

    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
        throw new Exception('Formato no permitido');
    }

    $dirFisico = __DIR__ . "/../../../uploads/productos/" . date('Y/m') . "/";
    $dirWeb    = "uploads/productos/" . date('Y/m') . "/";

    if (!is_dir($dirFisico)) {
        mkdir($dirFisico, 0777, true);
    }

    $nombreImg = 'prod_' . $id . '_' . uniqid() . '.webp';

    $rutaFisica = $dirFisico . $nombreImg;
    $rutaWeb    = $dirWeb . $nombreImg;

    $img = ($mime === 'image/png')
        ? imagecreatefrompng($tmp)
        : imagecreatefromjpeg($tmp);

    imagewebp($img, $rutaFisica, 80);
    imagedestroy($img);

    $stmt = $pdo->prepare("
        INSERT INTO producto_imagen (rela_producto, ruta_imagen, es_principal)
        VALUES (:producto, :ruta, 1)
    ");

    $stmt->execute([
        ':producto' => $id,
        ':ruta'     => $rutaWeb
    ]);

    echo json_encode([
        'error' => false,
        'mensaje' => 'Imagen cargada correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
