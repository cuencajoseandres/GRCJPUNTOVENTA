<?php
require '../../../vendor/autoload.php';
require('conexion.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

if (!isset($_FILES['archivo']['tmp_name'])) {
    echo json_encode(['error' => true, 'mensaje' => 'No se subió ningún archivo.']);
    exit;
}

$archivo = $_FILES['archivo']['tmp_name'];
$fecha_actual = date('Y-m-d');
$rela_user = 2;
$rela_proveedor = 1;

try {
    // Verificar si el archivo es legible
    if (!file_exists($archivo) || filesize($archivo) === 0) {
        throw new Exception("El archivo está vacío o no se puede leer.");
    }

    $documento = IOFactory::load($archivo);
    $hoja = $documento->getActiveSheet();
    $filas = $hoja->toArray();

    if (count($filas) < 2) {
        throw new Exception("El archivo no contiene datos válidos.");
    }

    // Función fuera del loop para generar códigos
    function generarCodigoProducto($pdo, $categoria, $id_categoria) {
        $prefijo = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $categoria), 0, 3));
        $sql = "SELECT cod_product FROM producto WHERE rela_categoria = ? ORDER BY id_producto DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_categoria]);
        $ultimo = $stmt->fetch();
        $num = $ultimo ? intval(substr($ultimo['cod_product'], strpos($ultimo['cod_product'], '-') + 1)) + 1 : 1;
        return $prefijo . '-' . str_pad($num, 4, "0", STR_PAD_LEFT);
    }

    $importados = 0;

    for ($i = 1; $i < count($filas); $i++) {
        $nombre_product    = trim($filas[$i][0]);
        $descri_product    = trim($filas[$i][1]);
        $precio_costo      = floatval($filas[$i][2]);
        $precio_publico    = floatval($filas[$i][3]);
        $precio_gremio     = floatval($filas[$i][4]);
        $precio_mayorista  = floatval($filas[$i][5]);
        $cant_product      = intval($filas[$i][6]);
        $categoria         = trim($filas[$i][7]);
        $estado_nombre     = trim($filas[$i][8]);

        if (empty($nombre_product) || empty($categoria)) continue;

        // 🔸 Buscar o crear categoría
        $cat_stmt = $pdo->prepare("SELECT id_categoria FROM categoria WHERE descri_cat = ?");
        $cat_stmt->execute([$categoria]);
        $cat_row = $cat_stmt->fetch();
        $id_categoria = $cat_row ? $cat_row['id_categoria'] : null;

        if (!$id_categoria) {
            $insert_cat = $pdo->prepare("INSERT INTO categoria (descri_cat) VALUES (?)");
            $insert_cat->execute([$categoria]);
            $id_categoria = $pdo->lastInsertId();
        }

        // 🔸 Buscar o crear estado
        $estado_normalizado = strtoupper($estado_nombre);
        $estado_stmt = $pdo->prepare("SELECT id_prod_estado FROM estado_producto WHERE UPPER(descri_prod_est) = ?");
        $estado_stmt->execute([$estado_normalizado]);
        $estado_row = $estado_stmt->fetch();
        $rela_estado = $estado_row ? $estado_row['id_prod_estado'] : null;

        if (!$rela_estado) {
            $insert_estado = $pdo->prepare("INSERT INTO estado_producto (descri_prod_est) VALUES (?)");
            $insert_estado->execute([$estado_normalizado]);
            $rela_estado = $pdo->lastInsertId();
        }

        // 🔸 Verificar si el producto existe
        $check = $pdo->prepare("SELECT id_producto FROM producto WHERE nombre_product = ?");
        $check->execute([$nombre_product]);
        $existente = $check->fetch();

        if ($existente) {
            // 🔁 Actualizar
            $update = $pdo->prepare("
                UPDATE producto SET 
                    descri_product = ?, 
                    precio_costo_produc = ?, 
                    precio_publico_product = ?, 
                    precio_gremio_product = ?, 
                    precio_mayorista_product = ?, 
                    cant_product = ?, 
                    fecha_product = ?, 
                    rela_categoria = ?, 
                    rela_proveedor = ?, 
                    rela_user = ?, 
                    rela_estado = ?
                WHERE nombre_product = ?
            ");
            $update->execute([
                $descri_product,
                $precio_costo,
                $precio_publico,
                $precio_gremio,
                $precio_mayorista,
                $cant_product,
                $fecha_actual,
                $id_categoria,
                $rela_proveedor,
                $rela_user,
                $rela_estado,
                $nombre_product
            ]);
        } else {
            // 🆕 Insertar nuevo
            $cod_product = generarCodigoProducto($pdo, $categoria, $id_categoria);

            $insert = $pdo->prepare("
                INSERT INTO producto (
                    nombre_product, descri_product, cod_product,
                    precio_costo_produc, precio_publico_product, 
                    precio_gremio_product, precio_mayorista_product,
                    cant_product, fecha_product, 
                    rela_categoria, rela_proveedor, rela_user, rela_estado
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->execute([
                $nombre_product,
                $descri_product,
                $cod_product,
                $precio_costo,
                $precio_publico,
                $precio_gremio,
                $precio_mayorista,
                $cant_product,
                $fecha_actual,
                $id_categoria,
                $rela_proveedor,
                $rela_user,
                $rela_estado
            ]);
        }

        $importados++;
    }

    echo json_encode(['error' => false, 'mensaje' => "✅ Se importaron $importados productos correctamente."]);

} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => '❌ Error al importar: ' . $e->getMessage()]);
}
?>
