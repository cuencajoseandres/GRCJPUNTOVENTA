<?php
require('../../../vendor/autoload.php');
require('conexion.php'); // Ajusta según tu estructura
use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

if (!isset($_FILES['archivo'])) {
    echo json_encode(['error' => true, 'mensaje' => 'No se subió ningún archivo.']);
    exit;
}

$archivo_tmp = $_FILES['archivo']['tmp_name'];

try {
    $documento = IOFactory::load($archivo_tmp);
    $hoja = $documento->getActiveSheet();
    $filas = $hoja->toArray();

    if (count($filas) < 2) {
        throw new Exception("El archivo está vacío o no tiene filas suficientes.");
    }

    // Leer encabezados
    $encabezados = array_map('strtolower', $filas[0]); // fila 0
    $idxNombre = array_search('nombre', $encabezados);
    $idxDescripcion = array_search('descripcion', $encabezados);
    $idxPrecio = array_search('precio', $encabezados);

    if ($idxNombre === false || $idxDescripcion === false || $idxPrecio === false) {
        throw new Exception("El Excel debe tener encabezados: Nombre, Descripcion, Precio");
    }

    $rela_user = 2; // Usuario que importa
    $prefijo = "SER";

    for ($i = 1; $i < count($filas); $i++) {
        $nombre = trim($filas[$i][$idxNombre]);
        $descri = trim($filas[$i][$idxDescripcion]);
        $precio = floatval($filas[$i][$idxPrecio]);

        if (empty($nombre)) continue;

        // Validar precio
        if (!is_numeric($precio) || $precio <= 0) $precio = 0;

        // Verificar si existe
        $check = $pdo->prepare("SELECT id_servicio FROM servicio WHERE nombre_serv = ?");
        $check->execute([$nombre]);
        $existente = $check->fetch();

        if ($existente) {
            $update = $pdo->prepare("
                UPDATE servicio SET descri_serv = ?, precio_serv = ?, rela_user = ?
                WHERE nombre_serv = ?
            ");
            $update->execute([$descri, $precio, $rela_user, $nombre]);
        } else {
            // Generar código
            $stmt = $pdo->query("SELECT codigo_serv FROM servicio ORDER BY id_servicio DESC LIMIT 1");
            $ultimo = $stmt->fetch();
            $num = $ultimo ? intval(substr($ultimo['codigo_serv'], strpos($ultimo['codigo_serv'], '-') + 1)) + 1 : 1;
            $codigo_serv = $prefijo . '-' . str_pad($num, 4, "0", STR_PAD_LEFT);

            $insert = $pdo->prepare("
                INSERT INTO servicio (codigo_serv, nombre_serv, descri_serv, precio_serv, rela_user)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert->execute([$codigo_serv, $nombre, $descri, $precio, $rela_user]);
        }
    }

    echo json_encode(['error' => false, 'mensaje' => 'Importación de servicios completada correctamente.']);

} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error al importar: ' . $e->getMessage()]);
}
?>