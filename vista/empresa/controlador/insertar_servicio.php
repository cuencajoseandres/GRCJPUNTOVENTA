<?php
require_once('../conexion.php'); // Ajusta la ruta según tu estructura

header('Content-Type: application/json');

try {
    // Recibir datos POST
    $id_servicio  = $_POST['id_servicio'] ?? null; // opcional, por si se quiere editar
    $nombre_serv  = trim($_POST['nombre_serv'] ?? '');
    $descri_serv  = trim($_POST['descri_serv'] ?? '');
    $precio_serv  = floatval($_POST['precio_serv'] ?? 0);
    $rela_user    = 2; // Usuario por defecto, cambiar según sesión

    // Validar campos obligatorios
    if ($nombre_serv === '' || $precio_serv <= 0) {
        echo json_encode(['error' => true, 'mensaje' => '⚠️ Nombre y precio son obligatorios.']);
        exit;
    }

    // Función para generar código automático
    function generarCodigoServicio($pdo, $nombre) {
        $prefijo = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $nombre), 0, 3));
        $stmt = $pdo->prepare("SELECT codigo_serv FROM servicio WHERE codigo_serv LIKE ? ORDER BY id_servicio DESC LIMIT 1");
        $stmt->execute([$prefijo . '-%']);
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = 1;

        if ($ultimo) {
            preg_match('/-(\d+)$/', $ultimo['codigo_serv'], $m);
            if (isset($m[1])) $num = intval($m[1]) + 1;
        }

        return $prefijo . '-' . str_pad($num, 4, "0", STR_PAD_LEFT);
    }

    // Verificar si el servicio ya existe
    $check = $pdo->prepare("SELECT id_servicio FROM servicio WHERE nombre_serv = ?");
    $check->execute([$nombre_serv]);
    $existente = $check->fetch(PDO::FETCH_ASSOC);

    if ($existente) {
        // Actualizar servicio existente
        $update = $pdo->prepare("
            UPDATE servicio SET
                descri_serv = ?,
                precio_serv = ?,
                rela_user = ?
            WHERE nombre_serv = ?
        ");
        $update->execute([$descri_serv, $precio_serv, $rela_user, $nombre_serv]);

        echo json_encode(['error' => false, 'mensaje' => '🔄 Servicio actualizado correctamente.']);
    } else {
        // Insertar nuevo servicio
        $codigo_serv = generarCodigoServicio($pdo, $nombre_serv);

        $insert = $pdo->prepare("
            INSERT INTO servicio (codigo_serv, nombre_serv, descri_serv, precio_serv, rela_user)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insert->execute([$codigo_serv, $nombre_serv, $descri_serv, $precio_serv, $rela_user]);

        echo json_encode(['error' => false, 'mensaje' => '✅ Servicio agregado correctamente.']);
    }

} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => '❌ Error al procesar: ' . $e->getMessage()]);
}
?>